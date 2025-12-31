<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CronogramaVacaciones;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;
use App\Helpers\FileHelper;
use App\Models\Archivo;
use Rap2hpoutre\FastExcel\FastExcel;
use Carbon\Carbon;
use App\Models\Personal;
use Illuminate\Support\Str;
use DB;

use App\Models\VacacionesRe;
use App\Models\Vinculo;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;

class CronogramaController extends Controller
{

    public function index(Request $request)
    {
        $df = CronogramaVacaciones::where('personal_id', $request->id)->get();
        return Datatables::of($df)->make(true);
    }

    public function show($id)
    {
        $c = DB::table('cronograma_vac')->where('id', $id)->first();
        if ($c) {
            return response()->json($c);
        } else {
            return response()->json(['error' => 'Contrato no encontrado'], 404);
        }
    }

    public static function store(Request $request)
    {
        $data = $request->except('archivo', 'idvr');
        $vr = null;
        if ($request->has('idvo')) {
            $vr = CronogramaVacaciones::find((int)$request->idvo);
            if ($vr) {
                $data['personal_id'] = $vr->personal_id;
                $data['idvo'] = $vr->idvo !== null ? $vr->idvo : $vr->id;
            }
        }
        // Validación de los datos directamente
        $validator = Validator::make($data, [
            'personal_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $archivo = FileHelper::createArchivo($request, $data['personal_id'], "05");
        if ($archivo) {
            $data['archivo'] = $archivo->id;
        }
        $vacacion = CronogramaVacaciones::create($data);
        if ($vr) {
            $vr->update(['idvr' => $vacacion->id]);
            if ($vr->idvo == NULL) {
                $vr->update(['idvo' => $vr->id]);
            }
        } else {
            if ($vacacion->idvo === NULL) {
                $vacacion->update(['idvo' => $vacacion->id]);
            }
        }


        return response()->json($vacacion->toArray());
    }


    public static function guardarrepro(Request $request)
    {
        $request->validate([
            'personal_id' => 'required',
        ]);
        $vacacion = null;
        if ($request->has('idvr')) {
            $vacacion = CronogramaVacaciones::find($request->idvr);
        }
        if (!$vacacion) {
            $data['idvo'] = $archivo ? $archivo->id : null;
            $vacacion = CronogramaVacaciones::create($data);
        }
        $archivo = FileHelper::createArchivo($request, $request->personal_id, "05");
        if ($archivo) {
            $data = $request->except('archivo');
            $data['archivo'] = $archivo->id;
            $vacacion = CronogramaVacaciones::create($data);
        } else {
            $data = $request->except('archivo');
            $vacacion = CronogramaVacaciones::create($data);
        }

        return response()->json($vacacion->toArray());
    }

    public function update($id, Request $request)
    {
        $t = CronogramaVacaciones::find($id);
        $data = $request->except('personal_id', 'desder', 'hastar', 'diasr', 'idvr', 'idvo');

        if ($t) {
            $archivo = FileHelper::updateArchivo($request, $t, "05");
            if ($archivo !== null) {
                $data['archivo'] = $archivo->id;
            }
            $t->update($data);

            return response()->json(['success' => 'Datos actualizados correctamente']);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }

    public function destroy($id)
    {
        $t = CronogramaVacaciones::find($id);
        $vr = CronogramaVacaciones::where("idvr", $t->id)->first();
        if ($vr) {
            $vr->update(['idvr' => null]);
        }
        if ($t) {
            if ($t->archivo) {
                Archivo::where('id', $t->archivo)->delete();
            }
            $t->delete();
            return response()->json(['success' => 'Eliminado correctamente']);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }

    //EXCEL CARGA
    public function importExcelCron(Request $request)
    {
        $request->validate([
            'excelFile' => 'required|mimes:xlsx,xls|max:2048'
        ]);

        if ($request->hasFile('excelFile')) {
            $file = $request->file('excelFile');
            $noCargado = [];
            $rows = (new FastExcel)->import($file);

            // Validación previa de todos los campos
            foreach ($rows as $index => $line) {
                $fila = $index + 2; // Número de fila (ajustando el índice base 0 a base 1)
                // Validar que el DNI tenga exactamente 8 dígitos y sea numérico
                if (!preg_match('/^\d{8}$/', $line['DNI'])) {
                    $nombreCompleto = ($line['APELLIDO_PATERNO'] ?? ' ') . ' ' . ($line['APELLIDO_MATERNO'] ?? ' ') . ' ' . ($line['NOMBRES'] ?? 'Nombre no disponible');
                    $noCargado[] = "Fila $fila: " . $nombreCompleto . " (DNI inválido: " . $line['DNI'] . ")";
                    continue;
                }
                // Validar que el personal esté registrado
                $personal = Personal::where('nro_documento_id', $line['DNI'])->first();
                if (!$personal) {
                    $nombreCompleto = ($line['APELLIDO_PATERNO'] ?? ' ') . ' ' . ($line['APELLIDO_MATERNO'] ?? ' ') . ' ' . ($line['NOMBRES'] ?? 'Nombre no disponible');
                    $noCargado[] = "Fila $fila: " . $nombreCompleto . " (No registrado)";
                    continue;
                }

                //FAST EXCEL CONVIERTE AUTOMATICAMENTE A FORMATO FECHA
                // Validar FECHA INICIO y FECHA FIN con condicionales
                $fechaInicio = $line['FECHA INICIO'] ?? null;
                $fechaFin = $line['FECHA FIN'] ?? null;
                $diasDeclarados = intval($line['DIAS'] ?? 0);

                if ($fechaInicio instanceof \DateTimeImmutable) {
                    $fechaInicio = Carbon::instance($fechaInicio);
                } else {
                    // Si no es un objeto DateTimeImmutable, agregarlo a la lista de errores
                    $noCargado[] = "Fila $fila: FECHA INICIO no es un objeto DateTimeImmutable válido.";
                    continue;
                }

                if ($fechaFin instanceof \DateTimeImmutable) {
                    $fechaFin = Carbon::instance($fechaFin);
                } else {
                    // Si no es un objeto DateTimeImmutable, agregarlo a la lista de errores
                    $noCargado[] = "Fila $fila: FECHA FIN no es un objeto DateTimeImmutable válido.";
                    continue;
                }


                // Validar que FECHA FIN no sea anterior a FECHA INICIO
                if ($fechaFin->lt($fechaInicio)) {
                    $noCargado[] = "Fila $fila: La FECHA FIN ({$line['FECHA FIN']}) no puede ser anterior a la FECHA INICIO ({$line['FECHA INICIO']}).";
                    continue;
                }

                // Calcular la diferencia en días
                $diasCalculados = $fechaInicio->diffInDays($fechaFin) + 1; // +1 para incluir el día de inicio

                // Validar si los días calculados coinciden con los días declarados
                if ($diasCalculados !== $diasDeclarados) {
                    $noCargado[] = "Fila $fila: Los días calculados ($diasCalculados) no coinciden con los días declarados ($diasDeclarados).";
                    continue;
                }

                // Validar que los días calculados no excedan el límite máximo de 30 días
                if ($diasCalculados > 30) {
                    $noCargado[] = "Fila $fila: Los días calculados ($diasCalculados) exceden el límite máximo permitido (30 días).";
                    continue;
                }
            }

            // Si hay errores de validación, devolver respuesta y no proceder con la carga
            if (!empty($noCargado)) {
                return response()->json(['errors' => $noCargado, 'message' => 'Errores de validación encontrados. Por favor corrige los siguientes errores:'], 400);
            }

            // Si todas las validaciones pasaron, proceder con la importación
            foreach ($rows as $line) {
                $personal = Personal::where('nro_documento_id', $line['DNI'])->first();

                $cronv = CronogramaVacaciones::create([
                    'id_subida' => Carbon::now()->format('YmdHis'),
                    'personal_id' => $personal->id_personal,
                    'periodo' => $line['PERIODO'],
                    'mes' => json_encode([$line['MES']]),
                    'fecha_ini' => json_encode([Carbon::parse($line['FECHA INICIO'])->format('Y-m-d')]),
                    'fecha_fin' => json_encode([Carbon::parse($line['FECHA FIN'])->format('Y-m-d')]),
                    'dias' => json_encode([$line['DIAS']]),
                ]);

                $archivo = FileHelper::createArchivo($request, $personal->id_personal, "05");
                if ($archivo) {
                    $cronv->update(['archivo' => $archivo->id]);
                }
                if ($cronv) {
                    $cronv->update(['idvo' => $cronv->id]);
                }
            }

            return response()->json(['message' => 'Archivo importado exitosamente.'], 200);
        }

        return response()->json(['message' => 'No se pudo subir el archivo.'], 400);
    }

    public function cronogramarIndividual()
    {
        $trabajadores = DB::table('personal as p')
            ->select('p.*', 'v.id as vinculo', 'c.nombre as cargo', 'a.nombre as area', 'r.nombre as regimen')
            ->leftjoin('vinculos as v', 'v.personal_id', '=', 'p.id_personal')
            ->leftjoin('cargo as c', 'c.id', '=', 'v.id_cargo')
            ->leftjoin('area as a', 'a.id', '=', 'v.id_unidad_organica')
            ->leftjoin('regimen as r', 'r.id', '=', 'v.id_regimen')
            ->get();
        return view('vacaciones.cronogramar', array('personal' => $trabajadores));
    }

    public function consultarIndividuo($id)
    {
        $cronogramas=DB::table('personal  as p')
        ->select('v.id as vinculo','cr.id','cr.nombredoc','cr.nrodoc', 'cr.observaciones','cr.fecha_ini','cr.archivo','cr.estado' ,'c.nombre as cargo', 'a.nombre as area', 'r.nombre as regimen','cr.periodo','cr.mes')
        ->leftjoin('vinculos as v', 'v.personal_id', '=', 'p.id_personal')
        ->leftjoin('cargo as c', 'c.id', '=', 'v.id_cargo')
        ->leftjoin('area as a', 'a.id', '=', 'v.id_unidad_organica')
        ->leftjoin('regimen as r', 'r.id', '=', 'v.id_regimen')
        ->join('cronograma_vac as cr','cr.idvinculo','=','v.id')
        ->where('p.id_personal',$id)->get();
        $reporte=view('vacaciones.tablacronograma',array('cronogramas'=>$cronogramas))->render();
        if ($cronogramas) {
            return response()->json([
                'rpta' => 'ok',
                'datos' => $reporte
            ]);
        } else {
            return response()->json([
                'rpta' => 'error',
                'mensaje' => 'No se encontró el individuo'
            ], 404);
        }
    }
    public function guardarIndividual( Request $request){
        $datos= $request->input('datos');
        
        if ($request->input("datos") != null) {
            
            $cronogramas = json_decode($datos);
            foreach ($cronogramas as $item) {
                
                if ($item->cambio == 1) {

                    if ($item->id > 0) {
                        
                        $cronograma = CronogramaVacaciones::find($item->id);
                        Log::info($cronograma->periodo);
                        $cronograma->periodo = $item->periodo;
                        $cronograma->nrodoc = $item->nrodoc;
                        $cronograma->nombredoc = $item->tipodoc;
                        $cronograma->observaciones = $item->observaciones;
                        $cronograma->fecha_ini = $item->inicio;
                        $cronograma->mes = $item->mes;
                        $cronograma->archivo = $item->archivo;
                        $cronograma->save();
                    } else {
                        $cronograma = new CronogramaVacaciones();
                        $cronograma->periodo = $item->periodo;
                        $cronograma->nrodoc = $item->nrodoc;
                        $cronograma->nombredoc = $item->nombredoc;
                        $cronograma->observaciones = $item->observaciones;
                        $cronograma->fecha_ini = $item->inicio;
                        $cronograma->mes = $item->mes;
                        $cronograma->archivo = $item->archivo;
                        $cronograma->save();
                    }
                }
                if ($item->cambio == 2) {
                    if ($item->id > 0) {
                        $cronograma = CronogramaVacaciones::find($item->id);
                        $cronograma->delete();
                    }
                }
            }
        }
        return 1;
    }


    public function cronogramarMasivo()
    {
        $anio = Carbon::now()->year + 1;
        $cronogramados = DB::table('cronograma_vac')->where('periodo', $anio)->pluck('idvinculo');
        $trabajadores = DB::table('personal as p')
            ->select('p.*', 'v.id as vinculo', 'c.nombre as cargo', 'a.nombre as area', 'r.nombre as regimen')
            ->leftjoin('vinculos as v', 'v.personal_id', '=', 'p.id_personal')
            ->leftjoin('cargo as c', 'c.id', '=', 'v.id_cargo')
            ->leftjoin('area as a', 'a.id', '=', 'v.id_unidad_organica')
            ->leftjoin('regimen as r', 'r.id', '=', 'v.id_regimen')
            ->whereNotIn('v.id', $cronogramados)
            ->get();
        return view('vacaciones.cronogramarMasivo', array('trabajadores' => $trabajadores));
    }

    public function guardarCronogramaMasivo(Request $request)
    {
        $ids = $request->query('ids');
        $mes = $request->query('mes');
        $periodo = $request->query('periodo');
        $tipoDocumento = $request->query('tipodoc');
        $nroDocVac = $request->query('nrodoc');
        $observaciones = $request->query('observaciones');
        foreach ($ids as $vinculo) {
            $programacion = new CronogramaVacaciones();
            $programacion->idvinculo = $vinculo;
            $programacion->nombredoc = $tipoDocumento;
            $programacion->nrodoc = $nroDocVac;
            $programacion->periodo = $periodo;
            $programacion->mes = $mes;
            //$programacion->fecha_ini=
            $programacion->observaciones = $observaciones;
            $programacion->save();
        }
        return response()->json(['rpta' => 'ok', 'mensaje' => 'Se ha guardado correctamente']);
    }
}
