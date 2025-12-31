<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Personal;
use App\Models\Domicilio;
use App\Models\Explaboral;
use App\Models\CargoEntidad;
use App\Models\Condicionlab;
use App\Models\Vinculo;
use App\Models\Area;
use App\Models\ArchivosAdjuntos;
use App\Models\Cargo;
use App\Models\Estudios;
use App\Models\Regimen;
use App\Models\CondicionLaboral;
use App\Models\Familiares;
use App\Models\Archivo;
use App\Models\Tipodoc;
use App\Models\Idiomas;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use DB;
use Rap2hpoutre\FastExcel\FastExcel;
use Carbon\Carbon;


class EmployeeController extends Controller
{

    public function index()
    {
        $dni = "";
        $dp = Personal::where('id_personal',$dni)->first();
        $dd = Domicilio::where('personal_id',$dni)->first();

        return view('employees.index',compact('dp','dd'));
    }

    public function mostrardp(Request $request) {
        $dp = Personal::where('id_personal', $request->id)->first();
        $dd = Domicilio::where('personal_id', $request->id)->first();

        $data1 = json_decode($dp, true);
        $data2 = $dd ? json_decode($dd, true) : [];

        $combinedData = array_merge($data1, $data2);
        return response()->json($combinedData);
    }


    public function datos_personal(Request $request)
    {
        $personal_id = $request->route('id');

        $estado = $request->route('estado');

        $tipo_docs = Tipodoc::where('categoria', 'LIKE', '%"DAP"%')->get();
        // Si el ID no está presente o es nulo, redirigir a una vista para ingresar información

        $dp = null;
        $ult_vin = null;
        $tipo_personal = null;
        if (is_numeric($personal_id) && intval($personal_id) == $personal_id) {
            $dp = DB::table('personal as p')
                        ->join('tipo_personal', 'p.id_tipo_personal', '=', 'tipo_personal.id')
                        ->select('p.*', 'tipo_personal.nombre as nombre_tipo_personal')
                        ->where('id_personal', $personal_id)
                        ->first();
            $ult_vin = DB::table('vinculos as v')
                        ->join('regimen as r', 'v.id_regimen', '=', 'r.id')
                        ->join('condicion_laboral as c', 'v.id_condicion_laboral', '=', 'c.id')
                        ->select('v.*', 'r.nombre as nombre_regimen', 'c.nombre as nombre_condicion')
                        ->where('v.personal_id', $personal_id)
                        ->orderBy('v.fecha_ini', 'desc')
                        ->first();
        }
        if (!$personal_id) {
            return view('employees.personal',compact('personal_id','estado', 'tipo_docs','dp','ult_vin'));
        }

        $fileContent = null;
        $base64Image = null;
        if ($dp) {
            $archivo = Archivo::find($dp->archivo);
            if ($archivo) {
                $fileContent = $archivo->data_archivo;
                $base64Image = 'data:image/' . $archivo->extension . ';base64,' . $fileContent;
            }
        }

        if (!$dp) {
            abort(404, 'El registro no existe.');
        }
        return view('employees.personal',compact('personal_id','estado', 'tipo_docs','base64Image','dp','ult_vin'));
    }


//
public function importExcel(Request $request)
{
    $file = $request->file('excelFile');
    $rows = (new FastExcel)->import($file);
    $duplicates = [];
    $errors = [];

    // Validación previa de todos los datos
    foreach ($rows as $index => $row) {
        $nroDocumento = (string)$row['NRO_DOCUMENTO'];
        if (!ctype_digit($nroDocumento)) {
            $errors[] = ['fila' => $index + 2, 'campo' => 'NRO_DOCUMENTO'];
            continue;
        }
        $existing = Personal::where('nro_documento_id', $nroDocumento)->first();
        if ($existing) {
            $duplicates[] = ['fila' => $index + 2, 'documento' => $nroDocumento];
        } else {
            $regimenId = Regimen::where('nombre', 'LIKE', '%' . $row['REGIMEN'] . '%')->value('id');
            $condicion_lab_id = CondicionLaboral::where('nombre', 'LIKE', '%' . $row['CONDICION LABORAL'] . '%')->value('id');
            $tipo_personal = DB::table('tipo_personal')->where('nombre', $row['TIPO PERSONAL'])->value('id');
            $distritoid = DB::table('distritos')->where('nombre', 'LIKE', '%' . $row['DISTRITO'] . '%')->value('id');
            $AreaId = Area::where('nombre', '=',$row['UNIDAD ORGANICA'])->value('id');
            $CargoId = Cargo::where('nombre', 'LIKE', '%' . $row['CARGO'] . '%')->value('id');
            if (!$regimenId) {
                $errors[] = ['fila' => $index + 2, 'campo' => 'REGIMEN'];
                continue;
            }
            if (!$condicion_lab_id) {
                $errors[] = ['fila' => $index + 2, 'campo' => 'CONDICION LABORAL'];
                continue;
            }
            if (!$tipo_personal) {
                $errors[] = ['fila' => $index + 2, 'campo' => 'TIPO PERSONAL'];
                continue;
            }
            if (!$AreaId) {
                $errors[] = ['fila' => $index + 2, 'campo' => 'UNIDAD ORGANICA'];
                continue;
            }
            if (!$CargoId) {
                $errors[] = ['fila' => $index + 2, 'campo' => 'CARGO'];
                continue;
            }
        }
    }

    // Si hay errores o duplicados, devolver respuesta y no proceder con la carga
    if (!empty($errors) || !empty($duplicates)) {
        return response()->json(['errors' => $errors, 'warnings' => $duplicates], 400);
    }

    // Si todas las validaciones pasaron, proceder con la importación
    DB::beginTransaction();
    try {
        foreach ($rows as $index => $row) {
            $nroDocumento = (string)$row['NRO_DOCUMENTO'];
            $regimenId = Regimen::where('nombre', 'LIKE', '%' . $row['REGIMEN'] . '%')->value('id');
            $condicion_lab_id = CondicionLaboral::where('nombre', 'LIKE', '%' . $row['CONDICION LABORAL'] . '%')->value('id');
            $tipo_personal = DB::table('tipo_personal')->where('nombre', 'LIKE', '%' . $row['TIPO PERSONAL'] . '%')->value('id');
            $distritoid = DB::table('distritos')->where('nombre', 'LIKE', '%' . $row['DISTRITO'] . '%')->value('id');
            $Tipodocid = Tipodoc::where('nombre', 'LIKE', '%' . $row['TIPO DOCUMENTO'] . '%')->value('id');
            $AreaId = Area::where('nombre', 'LIKE', '%' . $row['UNIDAD ORGANICA'] . '%')->value('id');
            $CargoId = Cargo::where('nombre', 'LIKE', '%' . $row['CARGO'] . '%')->value('id');

            $idDep = null;
            $idPro = null;
            if ($distritoid) {
                $provincia = DB::table('provincias')->where('id', function ($query) use ($distritoid) {
                    $query->select('provincia_id')
                          ->from('distritos')
                          ->where('id', $distritoid)
                          ->limit(1);
                })->first();
                $departamento = DB::table('departamentos')->where('id', function ($query) use ($provincia) {
                    $query->select('departamento_id')
                          ->from('provincias')
                          ->where('id', $provincia->id)
                          ->limit(1);
                })->first();
                $idDep = $departamento->id ?? null;
                $idPro = $provincia->id ?? null;
            }

            $personalData = [
                'nro_documento_id' => $nroDocumento,
                'Apaterno' => $row['APELLIDO_PATERNO'] ?? null,
                'Amaterno' => $row['APELLIDO_MATERNO'] ?? null,
                'Nombres' => $row['NOMBRES'] ?? null,
                'FechaNacimiento' => $row['FECHA NACIMIENTO'] ?? null,
                'lprocedencia' => $row['LUGAR PROCEDENCIA'] ?? null,
                'NroColegiatura' => $row['NRO COLEGIATURA'] ?? null,
                'NroRuc' => $row['RUC'] ?? null,
                'id_identificacion' => $row['TIPO DOC IDENTIDAD'] ?? null,
                'NroEssalud' => $row['CODIGO ESSALUD'] ?? null,
                'CentroEssalud' => $row['CENTRO DE ATENCION (ESSALUD)'] ?? null,
                'GrupoSanguineo' => $row['GRUPO SANGUINEO'] ?? null,
                'afiliacion_salud' => $row['AFILIACION SEGURO'] ?? null,
                'NroTelefono' => $row['TELEFONO'] ?? null,
                'NroCelular' => $row['CELULAR'] ?? null,
                'Correo' => $row['CORREO'] ?? null,
                'EstadoCivil' => $row['ESTADO CIVIL'] ?? null,
                'sexo' => $row['SEXO'] ?? null,
                'id_tipo_personal' => $tipo_personal,
            ];

            if (!empty($personalData['nro_documento_id'])) {
                $personal = Personal::create(array_filter($personalData));


                $domicilioData = [
                    'personal_id' => $personal->id_personal,
                    'dactual' => $row['DOMICILIO'] ?? null,
                    'tipodom' => $row['TIPO VIA'] ?? null,
                    'numero' => $row['NUMERO'] ?? null,
                    'iddep' => $idDep,
                    'idpro' => $idPro,
                    'iddis' => $distritoid
                ];

                if ($domicilioData['dactual'] ) {
                    Domicilio::create(array_filter($domicilioData));
                }

                $contratoData = [
                    'personal_id' => $personal->id_personal,
                    'id_unidad_organica' => $AreaId ?? null,
                    'id_cargo' => $CargoId ?? null,
                    'id_regimen' => $regimenId ?? null,
                    'id_condicion_laboral' => $condicion_lab_id ?? null,
                    'fecha_ini' => $row['FECHA INGRESO'] ?? null,
                    'id_tipo_documento' => $Tipodocid ?? null,
                    'nro_doc' => $row['NRO DOC'] ?? null,
                ];
                $estudiosData = [
                    'personal_id' => $personal->id_personal,
                    'nivel_educacion' => $row['GRADO ACADEMICO'] ?? null,
                    'especialidad' => $row['ESPECIALIDAD'] ?? null,
                ];
                //FILTRA VALORES NULOS
                if ($estudiosData['nivel_educacion']) {
                    Estudios::create(array_filter($estudiosData));
                }
                if ($estudiosData['especialidad']) {
                    Estudios::create(array_filter($estudiosData));
                }

                if ($contratoData['fecha_ini']) {
                    Vinculo::create(array_filter($contratoData));
                }
            }
        }

        DB::commit();
        return response()->json(['message' => 'Personales importados exitosamente.'], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function store(Request $request)
{
        $validator = Validator::make($request->all(), [
            'id_personal' => 'required|unique:Personal,id_personal'
        ]);
        $personal_id =  $request->id_personal;

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => "El personal ya existe"
            ]);
        }else{
            $data = $request->all();
            $personal = Personal::create($data);
            $pt = Vinculo::create(['personal_id' => $personal_id,'regimen' => $request->regimen]);
            $pt = Estudios::create(['personal_id' => $personal_id,'GradoAcademico' => $request->GradoAcademico,'Especialidad' => $request->Especialidad]);
            $pt = Condicionlab::create(['personal_id' => $personal_id,'CargoActual' => $request->CargoActual,'Oficina' => $request->Oficina,'idregimen' => $request->regimen,'FechaIngreso' => $request->FechaIngreso]);
            Domicilio::create(['personal_id' => $personal_id]);


            return response()->json([
                'duplicates' => $duplicates,
                'newRecords' => $newRecords,
                'success' => true
            ]);
        }

    }
    public function ValidarPersonal(Request $request)
    {
        $nroDocumento = $request->get('query');

        // Busca el personal por número de documento
        $personal = Personal::where('nro_documento_id', $nroDocumento)->first();
        if ($personal) {
            $nombreCompleto = "{$personal->Apaterno} {$personal->Amaterno} {$personal->Nombres}";
            return response()->json([
                'existe' => true,
                'personal_id' => $personal->id_personal,
                'nombreCompleto' => $nombreCompleto,
            ]);
        }
        return response()->json([
            'existe' => false,
        ]);
    }

    ///LISTAR PERSONAL
    public function getEmployees(Request $request)
    {
        $regimen = $request->input('regimen');
        $tipo_personal = $request->input('tipo_personal');
        $condicion = $request->input('condicion');
        $motivo_cese = $request->input('motivo_cese');

        //EVALUAR
        $tipo_personal = $request->input('tipo_personal');
        $laststudy = DB::table('estudios')
            ->select('personal_id',
                DB::raw('COALESCE(MIN(fecha_ini), \'0000-00-00\') as fecha_ini'),
                'especialidad'
            )
            ->groupBy('personal_id', 'especialidad');

        $oldestContract = DB::table('vinculos')
            ->select('personal_id', DB::raw('MIN(fecha_ini) as inicio_vinculo'))
            ->groupBy('personal_id');
        $ultimosMovimientos = DB::select('SELECT m.personal_id, a.nombre FROM movimientos m INNER JOIN (SELECT personal_id, MAX(fecha_ini) AS ultima_fecha_ini FROM movimientos GROUP BY personal_id) t ON m.personal_id = t.personal_id AND m.fecha_ini = t.ultima_fecha_ini inner join area a on a.id=m.unidad_organica_destino;');
        Log::info('datos:',$ultimosMovimientos);
        $recentContract = DB::table('vinculos as c1')
            ->select('c1.personal_id','c1.id_condicion_laboral', 'c1.id_unidad_organica', 'condicion_laboral.nombre as condicion', 'regimen.id as id_regimen', 'regimen.nombre as regimenn', 'c1.id_cargo', 'area.nombre as oficina_nombre', 'cargo.nombre as cargo_nombre','motivo_fin_vinculo.nombre as motivo_nombre', 'motivo_fin_vinculo.id as id_motivo_fin_vinculo')
            ->leftJoin('area', 'c1.id_unidad_organica', '=', 'area.id')
            ->leftJoin('cargo', 'c1.id_cargo', '=', 'cargo.id')
            ->leftJoin('motivo_fin_vinculo', 'c1.id_motivo_fin_vinculo', '=', 'motivo_fin_vinculo.id')
            ->leftJoin('condicion_laboral', 'c1.id_condicion_laboral', '=', 'condicion_laboral.id')
            ->leftJoin('regimen', 'c1.id_regimen', '=', 'regimen.id')
            ->whereColumn('c1.fecha_ini', '=', DB::raw('(
                SELECT MAX(fecha_ini)
                FROM vinculos
                WHERE vinculos.personal_id = c1.personal_id
            )'))
            ->whereIn('c1.id', function($query) {
                $query->select('id')
                    ->from('vinculos')
                    ->whereColumn('vinculos.personal_id', 'c1.personal_id')
                    ->orderBy('fecha_ini', 'desc')
                    ->limit(1);
            })
            ->groupBy('c1.personal_id', 'c1.id_unidad_organica', 'c1.id_condicion_laboral', 'c1.id_cargo', 'area.nombre', 'cargo.nombre','condicion_laboral.nombre','regimen.nombre','regimen.id','motivo_fin_vinculo.nombre','motivo_fin_vinculo.id');

            $query = DB::table('personal')
            ->leftJoin('domicilio', 'personal.id_personal', '=', 'domicilio.personal_id')
            ->leftJoin('estudios', 'personal.id_personal', '=', 'estudios.personal_id')
            ->leftJoin('tipo_personal', 'personal.id_tipo_personal', '=', 'tipo_personal.id')
            ->leftJoinSub($oldestContract, 'oc', function($join) {
                $join->on('personal.id_personal', '=', 'oc.personal_id');
            })
            ->leftJoinSub($recentContract, 'rc', function($join) {
                $join->on('personal.id_personal', '=', 'rc.personal_id');
            })

            ->leftJoinSub($laststudy, 'ls', function($join) {
                $join->on('personal.id_personal', '=', 'ls.personal_id');
            })
            ->select(
                'rc.regimenn',
                'rc.id_regimen',
                'rc.id_condicion_laboral',
                'rc.id_motivo_fin_vinculo',
                'rc.condicion',
                'personal.id_personal',
                'personal.nro_documento_id',
                'personal.Apaterno',
                'personal.Amaterno',
                'personal.Nombres',
                'personal.FechaNacimiento',
                'tipo_personal.nombre as tipo_personal',
                'rc.cargo_nombre as cargo',
                'rc.cargo_nombre as cargo',
                'rc.oficina_nombre as oficina',
                'rc.motivo_nombre',
                'oc.inicio_vinculo',
            )
            ->when($tipo_personal, function($query) use ($tipo_personal) {
                return $query->where('personal.id_tipo_personal', '=', $tipo_personal);
            })
            ->when($regimen, function($query) use ($regimen) {
                return $query->where('rc.id_regimen','=',$regimen);
            })
            ->when($condicion, function($query) use ($condicion) {
                return $query->where('rc.id_condicion_laboral','=',$condicion);
            })
            ->when($motivo_cese, function($query) use ($motivo_cese) {
                return $query->where('rc.id_motivo_fin_vinculo','=',$motivo_cese);
            })
            ->groupBy(
                'personal.id_personal',
                'personal.nro_documento_id',
                'personal.Apaterno',
                'personal.Amaterno',
                'personal.Nombres',
                'personal.FechaNacimiento',
                'personal.id_tipo_personal',
                'rc.condicion',
                'rc.regimenn',
                'rc.id_regimen',
                'rc.id_condicion_laboral',
                'rc.id_motivo_fin_vinculo',
                'tipo_personal.nombre',
                'rc.cargo_nombre',
                'rc.oficina_nombre',
                'rc.motivo_nombre',
                'oc.inicio_vinculo'
            )
                ->where('personal.estado','=','1')
            ->orderBY('personal.Apaterno', 'ASC')
            ->orderBY('personal.Amaterno', 'ASC')
            ;

        $resultados = $query->distinct()->get();
        return Datatables::of($resultados)->make(true);
    }


    //ARCHIVOS ADJUNTOS
    public function obtenerArchivosAdjuntos($personalId)
    {
        // Buscar archivos asociados al personal_id
        $archivos = ArchivosAdjuntos::where('personal_id', $personalId)->get();
        return response()->json($archivos);
    }

    public function showPerfil($id)
    {
        $archivo = Archivo::findOrFail($id);
        $fileContent = $archivo->data_archivo;

        if ($fileContent === false) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Archivo no válido',
            ], 400);
        }

        $base64Image = 'data:image/' . $archivo->extension . ';base64,' . $fileContent;
        return response()->json(['archivo' => $base64Image]);
    }

    public function buscarEmpleado(Request $request){
        $empleado = Employee::where('id_personal',$request->id_personal)->first();
        return json_encode($empleado);
    }
}
