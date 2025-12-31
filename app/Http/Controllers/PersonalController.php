<?php

namespace App\Http\Controllers;

use App\Models\Distrito;
use App\Models\Employee;
use App\Models\Personal;
use App\Models\Domicilio;
use App\Models\Explaboral;
use App\Models\CargoEntidad;
use App\Models\Condicionlab;
use App\Models\Provincia;
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
use App\Http\Controllers\Controller;
use App\Models\Colegiatura;
use App\Models\Compensaciones;
use App\Models\EstudiosEsp;
use App\Models\Licencias;
use App\Models\Movimientos;
use App\Models\Permisos;
use App\Models\Reconocimientos;
use App\Models\Sancion;
use App\Models\TipoCompensacion;
use App\Models\Vacaciones;
use App\Models\TipoPersonal;
use App\Models\TipoVia;
use Illuminate\Support\Facades\Auth;
use Psy\Readline\Hoa\Console;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Output\NullOutput;

use function Laravel\Prompts\select;
use function PHPUnit\Framework\isEmpty;

class PersonalController extends Controller
{
    //
    public function nuevoPersonal(Request $request)
    {
        $personal_id = $request->route('id');

        $estado = $request->route('estado');

        $tipo_docs = Tipodoc::where('categoria', 'LIKE', '%"DAP"%')->get();
        // Si el ID no está presente o es nulo, redirigir a una vista para ingresar información

        $dp = null;
        $ult_vin = null;
        $tipo_personal = null;
        $pa = null; //personal activo
        $pa = DB::select('exec recogerPersonal');
        $vin_fin = null; //motivo de finalizacion de contrato
        $vin_fin = DB::table('motivo_fin_vinculo')->select('id', 'nombre')->get();
        $tcomp = null; //tipo de compensacion
        $tcomp = DB::table('tipo_compensacion')->select('id', 'nombre')->get();
        $areas = null; //areas habilitadas
        $areas = DB::table('area')
            ->select('id', 'nombre')->where('estado', 1)->get();
        $tiposdoc = Tipodoc::all();
        $cargos = null; //nombres de cargo
        $cargos = DB::table('cargo')
            ->select('id', 'nombre')->where('estado', 1)->get();
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
            return view('Personal.index', compact('personal_id', 'tiposdoc', 'estado', 'tipo_docs', 'dp', 'ult_vin', 'pa', 'vin_fin', 'tcomp', 'areas', 'cargos'));
        }
        $vacaciones = null;
        $licencias = null;
        $permisos = null;
        $compensaciones = null;
        $sanciones = null;
        $reconocimientos = null;
        $rotaciones = null;
        $encargaturas = null;
        $historialv = null;
        $historiall = null;
        $historialp = null;
        $historialVinculos = null;

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
        return view('personal.index', compact(
            'personal_id',
            'tiposdoc',
            'estado',
            'tipo_docs',
            'base64Image',
            'dp',
            'ult_vin',
            'rotaciones',
            'encargaturas',
            'vacaciones',
            'historialv',
            'historialp',
            'historiall',
            'licencias',
            'permisos',
            'compensaciones',
            'reconocimientos',
            'sanciones'
        ));
    }

    public function edicionPersonal(Request $request)
    {
        $personal_id = $request->query('id');
        $estado = $request->route('estado');

        $tipo_docs = Tipodoc::where('categoria', 'LIKE', '%"DAP"%')->get();
        // Si el ID no está presente o es nulo, redirigir a una vista para ingresar información
        $dp = null;
        $ult_vin = null;
        $tipo_personal = null;
        $pa = null; //personal activo 
        $pa = DB::select('exec recogerPersonal');
        $vin_fin = null; //motivo de finalizacion de contrato
        $vin_fin = DB::table('motivo_fin_vinculo')->select('id', 'nombre')->get();
        $tcomp = null; //tipo de compensacion
        $tcomp = DB::table('tipo_compensacion')->select('id', 'nombre')->get();
        $areas = null; //areas habilitadas
        $areas = DB::table('area')
            ->select('id', 'nombre')->where('estado', 1)->orderBy('nombre')->get();
        $cargos = null; //nombres de cargo
        $cargos = DB::table('cargo')
            ->select('id', 'nombre')->where('estado', 1)->orderBy('nombre')->get();
        $tiposdoc = Tipodoc::all();
        if (is_numeric($personal_id) && intval($personal_id) == $personal_id) {

            $dp = DB::table('personal as p')
                ->join('tipo_personal', 'p.id_tipo_personal', '=', 'tipo_personal.id')
                ->select('p.*', 'tipo_personal.nombre as nombre_tipo_personal')
                ->where('id_personal', $personal_id)
                ->first();
            $ult_vin = DB::table('vinculos as v')
                ->join('regimen as r', 'v.id_regimen', '=', 'r.id')
                ->join('condicion_laboral as c', 'v.id_condicion_laboral', '=', 'c.id')
                ->join('area as a', 'v.id_unidad_organica', '=', 'a.id')
                ->select('v.*', 'r.nombre as nombre_regimen', 'c.nombre as nombre_condicion', 'a.nombre as area')
                ->where('v.personal_id', $personal_id)
                ->orderBy('v.fecha_ini', 'desc')
                ->first();
            Log::debug("2");
        }
        if (!$personal_id) {
            return view('Personal.index', compact('personal_id', 'estado', 'tipo_docs', 'dp', 'ult_vin', 'pa', 'vin_fin', 'tcomp', 'areas', 'cargos'));
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

        $domicilio = Domicilio::select('*')->where('personal_id', $personal_id)->first();
        $familiares = Familiares::select('*')
            ->where('personal_id', $personal_id)
            ->get();
        $estudios = Estudios::select('*')->where('personal_id', $personal_id)->get();
        $especialidad = EstudiosEsp::select('*')->where('personal_id', $personal_id)->get();
        $colegiatura = Colegiatura::select('*')->where('personal_id', $personal_id)->get();
        $idiomas = Idiomas::select('*')->where('personal_id', $personal_id)->get();
        $experiencia = Explaboral::select('*')->where('personal_id', $personal_id)->get();
        $vacaciones = null;
        $licencias = null;
        $permisos = null;
        $compensaciones = null;
        $sanciones = null;
        $reconocimientos = null;
        $rotaciones = null;
        $encargaturas = null;
        $historialv = null;
        $historiall = null;
        $historialp = null;
        $historialVinculos = null;

        if ($ult_vin != null) {
            $historialVinculos = DB::select('exec historialVinculo ?', [$personal_id]);
            $rotaciones = DB::select('exec ultimasRotaciones ?', [$ult_vin->id]);
            $encargaturas = DB::select('exec ultEncargaturas ?', [$ult_vin->id]);
            $vacaciones = DB::select('exec historialVacaciones ?, ?', [$ult_vin->id, date("Y")]);
            $historialv = DB::select('exec historialVacaciones ?, ?', [$ult_vin->id, date("Y")]);
            $historiall = DB::select('exec historialLicencias ?, ?', [$ult_vin->id, date("Y")]);
            $historialp = DB::select('exec historialPermisos ?, ?', [$ult_vin->id, date("Y")]);
            $licencias = DB::select('exec verLicencias ?', [$ult_vin->id]);
            $permisos = DB::select('exec verPermisos ?', [$ult_vin->id]);
            $compensaciones = DB::select('exec verCompensaciones ?', [$ult_vin->id]);
            $sanciones = DB::select('exec verSanciones ?', [$ult_vin->id]);
            $reconocimientos = DB::select('exec verReconocimientos ?', [$ult_vin->id]);
        }

        return view('personal.edicionPersonal', compact(
            'personal_id',
            'tiposdoc',
            'estado',
            'tipo_docs',
            'base64Image',
            'dp',
            'historialVinculos',
            'ult_vin',
            'pa',
            'areas',
            'cargos',
            'vin_fin',
            'tcomp',
            'domicilio',
            'familiares',
            'estudios',
            'especialidad',
            'colegiatura',
            'idiomas',
            'experiencia',
            'rotaciones',
            'encargaturas',
            'vacaciones',
            'historialv',
            'historialp',
            'historiall',
            'licencias',
            'permisos',
            'compensaciones',
            'reconocimientos',
            'sanciones'
        ));
    }

    public function consultaExistencia(Request $request)
    {
        $request->validate([
            'tipo' => 'required|string',
            'identificacion' => 'required|string'
        ]);
        $existe = Personal::where('id_identificacion', $request->tipo)
            ->where('nro_documento_id', $request->identificacion)->first();
        if ($existe) {

            return response()->json([
                'cod' => 1,
                'res' => 'El personal ya existe',
                'id' => $existe->id_personal
            ]);
        } else {
            return response()->json([
                'cod' => 0,
                'res' => 'La persona no está identificado'
            ]);
        }
    }

    public function obtenerProvincias(Request $request)
    {

        $id = $request->input('id');
        $provincias = Provincia::select('id', 'nombre')->where('departamento_id', $id)->where('activo', 1)->orderBy('nombre', 'ASC')->get();
        return response()->json($provincias);
    }

    public function obtenerDistritos(Request $request)
    {

        $id = $request->input('id');
        $distritos = Distrito::select('id', 'nombre')->where('provincia_id', $id)->orderBy('nombre', 'ASC')->get();
        return response()->json($distritos);
    }
    public function guardarNuevo(Request $request)
    {
        $tipodoc = $request->input('doc-identificacion');
        $nroidentificacion = $request->input('nro-identificacion');
        $tipopersonal = $request->input('tipo-personal') ?? 1;
        $apaterno = $request->input('apaterno');
        $amaterno = $request->input('amaterno');
        $nombres = $request->input('nombres');
        $sexo = $request->input('sexo');
        $fechanacimiento = $request->input('fecha-nacimiento');
        $estadocivil = $request->input('estadocivil');
        $procedencia = $request->input('procedencia');
        $celular = $request->input('celular');
        $correo = $request->input('correo');
        $ruc = $request->input('ruc');
        $nroEssalud = $request->input('nroessalud');
        $PCentroEssalud = $request->input('pcentroessalud');
        $PGrupoSanguineo = $request->input('pgruposanguineo');
        $sistemapensionario = $request->input('sistema-pensionario');
        $regimenp = $request->input('regimenp');
        $discapacidad = $request->input('discapacidad');
        $ffaa = $request->input('ffaa');
        $tipodom = $request->input('tipodom');
        $dactual = $request->input('dactual');
        $numero = $request->input('numero');
        $iddep = $request->input('iddep');
        $idpro = $request->input('idpro');
        $iddis = $request->input('iddis');
        
        $afiliacion = $request->input('radioDefault');
        $referencia = $request->input('referencia');
        $interior = $request->input('interior');
        $foto = $request->input('foto-perfil');
        $personal = new Personal();
        $personal->id_identificacion = $tipodoc;
        $personal->nro_documento_id = $nroidentificacion;
        $personal->apaterno = $apaterno;
        $personal->amaterno = $amaterno;
        $personal->nombres = $nombres;
        $personal->sexo = $sexo;
        $personal->id_tipo_personal = $tipopersonal;
        $personal->fechanacimiento = $fechanacimiento;
        $personal->lprocedencia = $procedencia;
        $personal->nrocelular = $celular;
        $personal->correo = $correo;
        $personal->nroruc = $ruc;
        $personal->nroessalud = $nroEssalud;
        $personal->grupoSanguineo = $PGrupoSanguineo;
        $personal->afp = $sistemapensionario;
        $personal->id_regimenp = $regimenp;
        $personal->discapacidad = $discapacidad;
        $personal->afiliacion_salud = $afiliacion;
        $personal->ffaa = $ffaa;
        $personal->foto=$foto;
        $personal->estadocivil = $estadocivil;
        $personal->centroessalud = $PCentroEssalud;
        $personal->estado = 1;
        $personal->verificador=0;
        
        $personal->save();
        $domicilio = new Domicilio();
        $domicilio->tipodom = $tipodom;
        $domicilio->dactual = $dactual;
        $domicilio->numero = $numero;
        $domicilio->iddep = $iddep;
        $domicilio->idpro = $idpro;
        $domicilio->iddis = $iddis;
        $domicilio->personal_id = $personal->id_personal;
        $domicilio->referencia = $referencia;
        $domicilio->interior = $interior;
        $domicilio->save();

        //Datos complementarios
        $datosFamilia = $request->input("familiares");
        $familiares = json_decode($datosFamilia);
        if (isset($familiares)) {
            foreach ($familiares as $familiar) {
                //$trabajador = DB::table('personal')->where('nro_documento_id', $familiar->dni)->where('id_identificacion', 'DNI')->first();
                $pariente = new Familiares();
                $pariente->personal_id = $personal->id_personal;
                $pariente->apaterno = $familiar->paterno;
                $pariente->docid = $familiar->doc;
                $pariente->nroid = $familiar->nro;
                $pariente->amaterno = $familiar->materno;
                $pariente->nombres = $familiar->nombre;
                $pariente->direccion = $familiar->direccion;
                $pariente->telefono = $familiar->tel;
                $pariente->parentesco = $familiar->parentesco;
                $pariente->save();
            }
        }

        $datosEstudio = $request->input("estudios-rea");
        $estudios = json_decode($datosEstudio);
        if (isset($estudios)) {
            //Log::channel('daily')->info('Acción registrada', $estudios);
            foreach ($estudios as $estudio) {
                $formacion = new Estudios();
                $formacion->personal_id = $personal->id_personal;
                $formacion->nivel_educacion = $estudio->nivel;
                $formacion->especialidad = $estudio->especialidad;
                $formacion->centroestudios = $estudio->institucion;
                $formacion->fecha_ini = $estudio->inicio;
                $formacion->fecha_fin = $estudio->fin;
                $formacion->nombredoc = $estudio->doc;
                $formacion->archivo = $estudio->archivo;
                $formacion->save();
            }
        }
        $datosestudioscom = $request->input('estudios-com');
        $estudioscom = json_decode($datosestudioscom);
        if (isset($estudioscom)) {
            foreach ($estudioscom as $especialidad) {
                $complementarios = new EstudiosEsp();
                $complementarios->personal_id = $personal->id_personal;
                $complementarios->nombre = $especialidad->denominacion;
                $complementarios->centroestudios = $especialidad->institucion;
                $complementarios->horas = $especialidad->horas;
                $complementarios->fecha_ini = $especialidad->inicio;
                $complementarios->fecha_fin = $especialidad->fin;
                $complementarios->nombredoc = $especialidad->tipodoc;
                $complementarios->archivo = $especialidad->archivo;
                $complementarios->save();
                $doctipo = Tipodoc::findBy('nombre', '=', $especialidad->tipodoc)->get();
                if ($doctipo->count() < 1) {
                    $nuevotipo = new Tipodoc();
                    $nuevotipo->nombre = $especialidad->tipodoc;
                }
            }
        }
        $datoscolegiatura = $request->input('colegiatura');
        $colegiatura = json_decode($datoscolegiatura);
        if (isset($colegiatura)) {
            foreach ($colegiatura as $colegio) {
                $col = new Colegiatura();
                $col->personal_id = $personal->id_personal;
                $col->nombre_colegio = $colegio->colegio;
                $col->nombredoc = $colegio->tipodoc;
                $col->nrodoc = $colegio->nrocol;
                $col->estado = $colegio->estado;
                $col->fechadoc = $colegio->fechacol;
                $col->archivo = $colegio->doccol;
                $col->save();
            }
        }
        $datosidiomas = $request->input('idiomas');
        $idiomas = json_decode($datosidiomas);
        if (isset($idiomas)) {
            foreach ($idiomas as $idioma) {
                $habla = new Idiomas();
                $habla->personal_id = $personal->id_personal;
                $habla->idioma = $idioma->idioma;
                $habla->lectura = $idioma->lectura;
                $habla->habla = $idioma->habla;
                $habla->escritura = $idioma->escritura;
                $habla->nombredoc = $idioma->tipodoc;
                $habla->save();
            }
        }
        $datosexperiencia = $request->input('experiencia');
        $experiencia = json_decode($datosexperiencia);
        if (isset($experiencia)) {
            foreach ($experiencia as $antecedente) {
                $trabajo = new Explaboral();
                $trabajo->personal_id = $personal->id_personal;
                $trabajo->tipo_entidad = $antecedente->tipo;
                $trabajo->entidad = $antecedente->entidad;
                $trabajo->cargo = $antecedente->cargo;
                $trabajo->fecha_ini = $antecedente->inicio;
                $trabajo->fecha_fin = $antecedente->fin;
                $trabajo->area = $antecedente->area;
                $trabajo->nombredoc = $antecedente->tipodoc;
                $trabajo->nrodoc = $antecedente->nrodoc;
                $trabajo->save();
            }
        }
        /////vimculo laboral
        Log::info('vinculo');
        if ($request->input('fecha-ini-vin') != null && $request->input('nrodoc-vin') != null) {
            Log::info($request->input('fecha-ini-vin') . " --- " . $request->input('nrodoc-vin'));
            $vinculo = new Vinculo();
            $vinculo->personal_id = $personal->id_personal;
            $vinculo->id_cargo = $request->input('id-cargo-vinculo');
            $vinculo->id_unidad_organica = $request->input('id-area-vin');
            $vinculo->fecha_ini = $request->input('fecha-ini-vin');
            $vinculo->id_regimen = $request->input('id-regimen-vin');
            $vinculo->nombredocvin = $request->input('tipodoc-vin');

            $vinculo->id_condicion_laboral = $request->input('id-condicion-laboral-vin');
            $vinculo->nro_doc = $request->input('nrodoc-vin');
            $vinculo->denominacion = $request->input('denominacion');
            $vinculo->filea = $request->input('periodo-file');
            $vinculo->lomo = $request->input('num-file');
            $vinculo->archivo = $request->input('doc-ingreso');

            if ($request->input('fecha-fin-vinculo') != null && $request->input('nrodoc-fin-vin') && $request->input('motivo-cese')) {
                $vinculo->fecha_fin = $request->input('fecha-fin-vinculo');
                $vinculo->id_motivo_fin_vinculo = $request->input('id-motivo-fin-vinculo');
                $vinculo->nombredoccese = $request->input('tipodoc-fin-vin');
                $vinculo->nro_doc_fin = $request->input('nrodoc-fin-vin');
            }
            $vinculo->save();

            ///GUARDAR ROTACIÓN
            Log::info("rotacion");
            if ($request->input('rotaciones') != null) {
                $datosrotaciones = $request->input('rotaciones');
                $rotaciones = json_decode($datosrotaciones);
                foreach ($rotaciones as $rotacion) {
                    if ($rotacion->cambio == 1) {
                        if ($rotacion->id == 0) {
                            $movimiento = new Movimientos();
                            $movimiento->personal_id = $personal->id_personal;
                            $movimiento->idvinculo = $vinculo->id;
                            $movimiento->unidad_organica_destino = $rotacion->destino;
                            $movimiento->cargo = $rotacion->cargo;
                            $movimiento->descripcion = $rotacion->descripcion;
                            $movimiento->fecha_ini = $rotacion->inicio;
                            $movimiento->fecha_fin = $rotacion->fin;
                            $movimiento->nombredoc = $rotacion->docini;
                            $movimiento->nrodoc = $rotacion->nroini;
                            $movimiento->nombredocfin = $rotacion->docfin;
                            $movimiento->nrodocfin = $rotacion->nrofin;
                            $movimiento->archivo = $rotacion->archivoini;
                            $movimiento->archivofin = $rotacion->archivofin;

                            $movimiento->tipo = 0;
                            $movimiento->estado = 1;
                            $movimiento->save();
                        } else {
                            $movimiento = Movimientos::find($rotacion->id);
                            $movimiento->unidad_organica_destino = $rotacion->destino;
                            $movimiento->cargo = $rotacion->cargo;
                            $movimiento->descripcion = $rotacion->descripcion;
                            $movimiento->fecha_ini = $rotacion->inicio;
                            $movimiento->fecha_fin = $rotacion->fin;
                            $movimiento->nombredoc = $rotacion->docini;
                            $movimiento->nrodoc = $rotacion->nroini;
                            $movimiento->nombredocfin = $rotacion->docfin;
                            $movimiento->nrodocfin = $rotacion->nrofin;
                            $movimiento->archivo = $rotacion->archivoini;
                            $movimiento->archivofin = $rotacion->archivofin;
                            $movimiento->save();
                        }
                    }
                    if ($rotacion->cambio == 2) {
                        if ($rotacion->id > 0) {
                            $movimiento = Movimientos::find($rotacion->id);
                            $movimiento->estado = 0;
                            $movimiento->save();
                        }
                    }
                }
            }

            ///////////guardar ENCARGATURAS
            if ($request->input('encargaturas') != null) {
                $datosencargaturas = $request->input('encargaturas');
                $encargaturas = json_decode($datosencargaturas);
                foreach ($encargaturas as $encargatura) {
                    if ($encargatura->cambio == 1) {
                        if ($encargatura->id == 0) {
                            $movimiento = new Movimientos();
                            $movimiento->personal_id = $personal->id_personal;
                            $movimiento->idvinculo = $vinculo->id;
                            $movimiento->unidad_organica_destino = $encargatura->destino;
                            $movimiento->cargo = $encargatura->cargo;
                            $movimiento->fecha_ini = $encargatura->inicio;
                            $movimiento->fecha_fin = $encargatura->fin;
                            $movimiento->nombredoc = $encargatura->docini;
                            $movimiento->nrodoc = $encargatura->nroini;
                            $movimiento->nombredocfin = $encargatura->docfin;
                            $movimiento->nrodocfin = $encargatura->nrofin;
                            $movimiento->descripcion = $encargatura->descripcion;
                            $movimiento->archivo = $encargatura->archivoini;
                            $movimiento->archivofin = $encargatura->archivofin;
                            $movimiento->tipo = 1;
                            $movimiento->estado = 1;
                            $movimiento->save();
                        } else {
                            $movimiento = Movimientos::find($encargatura->id);
                            $movimiento->unidad_organica_destino = $encargatura->destino;
                            $movimiento->cargo = $encargatura->cargo;
                            $movimiento->fecha_ini = $encargatura->inicio;
                            $movimiento->fecha_fin = $encargatura->fin;
                            $movimiento->nombredoc = $encargatura->docini;
                            $movimiento->nrodoc = $encargatura->nroini;
                            $movimiento->nombredocfin = $encargatura->docfin;
                            $movimiento->nrodocfin = $encargatura->nrofin;
                            $movimiento->archivo = $encargatura->archivoini;
                            $movimiento->archivofin = $encargatura->archivofin;
                            $movimiento->save();
                        }
                    }
                    if ($encargatura->cambio == 2) {
                        if ($encargatura->id > 0) {
                            $movimiento = Movimientos::find($encargatura->id);
                            $movimiento->estado = 0;
                            $movimiento->save();
                        }
                    }
                }
            }

            ///////GUARDAR VACACIONES
            if ($request->input('vacaciones') != null) {
                $datosVacaciones = $request->input('vacaciones');
                $vacaciones = json_decode($datosVacaciones);
                foreach ($vacaciones as $vac) {
                    if ($vac->cambio == 1) {
                        if ($vac->id == 0) {
                            $vacacion = new Vacaciones();
                            $vacacion->idvinculo = $vinculo->id;
                            $vacacion->nombredoc = $vac->tipodoc;
                            $vacacion->nrodoc = $vac->nrodoc;
                            $vacacion->periodo = $vac->periodo;
                            $vacacion->mes = $vac->mes;
                            $vacacion->fecha_ini = $vac->inicio;
                            $vacacion->fecha_fin = $vac->fin;
                            $vacacion->observaciones = $vac->observaciones;
                            $vacacion->dias = $vac->dias;
                            $vacacion->suspension = $vac->suspension;
                            $vacacion->archivo = $vac->archivo;
                            $vacacion->save();
                        } else {
                            $vacacion = Vacaciones::find($vac->id);

                            $vacacion->nombredoc = $vac->tipodoc;
                            $vacacion->nrodoc = $vac->nrodoc;
                            $vacacion->periodo = $vac->periodo;
                            $vacacion->mes = $vac->mes;
                            $vacacion->fecha_ini = $vac->inicio;
                            $vacacion->fecha_fin = $vac->fin;
                            $vacacion->observaciones = $vac->observaciones;
                            $vacacion->dias = $vac->dias;
                            $vacacion->suspension = $vac->suspension;
                            $vacacion->archivo = $vac->archivo;
                            $vacacion->save();
                        }
                    }
                    if ($vac->cambio == 2) {
                        if ($vac->id > 0) {
                            $vacacion = Vacaciones::find($vac->id);
                            $vacacion->delete();
                        }
                    }
                }
            }


            ///////////GUARDAR LICENCIAS
            if ($request->input("licencias") != null) {
                $datoslicencias = $request->input('licencias');
                $licencias = json_decode($datoslicencias);
                foreach ($licencias as $lic) {
                    if ($lic->cambio == 1) {
                        if ($lic->id > 0) {
                            $licencia = Licencias::find($lic->id);
                            $licencia->nombredoc = $lic->tipodoc;
                            $licencia->descripcion = $lic->descripcion;
                            $licencia->nrodoc = $lic->nrodoc;
                            $licencia->observaciones = $lic->observaciones;
                            $licencia->fecha_ini = $lic->inicio;
                            $licencia->fecha_fin = $lic->fin;
                            $licencia->dias = $lic->dias;
                            $licencia->mes = $lic->meses;
                            $licencia->anio = $lic->agnos;
                            $licencia->acuentavac = $lic->acuenta;
                            $licencia->congoce = $lic->congoce;
                            $licencia->archivo = $lic->archivo;
                            $licencia->periodo = Carbon::parse($lic->inicio)->year;
                            $licencia->save();
                        } else {
                            $licencia = new Licencias();
                            $licencia->idvinculo = $vinculo->id;
                            $licencia->nombredoc = $lic->tipodoc;
                            $licencia->descripcion = $lic->descripcion;
                            $licencia->nrodoc = $lic->nrodoc;
                            $licencia->observaciones = $lic->observaciones;
                            $licencia->fecha_ini = $lic->inicio;
                            $licencia->fecha_fin = $lic->fin;
                            $licencia->dias = $lic->dias;
                            $licencia->mes = $lic->meses;
                            $licencia->anio = $lic->agnos;
                            $licencia->acuentavac = $lic->acuenta;
                            $licencia->congoce = $lic->congoce;
                            $licencia->archivo = $lic->archivo;
                            $licencia->periodo = Carbon::parse($lic->inicio)->year;
                            $licencia->save();
                        }
                    }
                    if ($lic->cambio == 2) {
                        if ($lic->id > 0) {
                            $licencia = Licencias::find($lic->id);
                            $licencia->delete();
                        }
                    }
                }
            }

            ////////////GUARDAR PERMISOS
            if ($request->input("permisos") != null) {
                $datospermisos = $request->input('permisos');
                $permisos = json_decode($datospermisos);
                foreach ($permisos as $per) {
                    if ($per->cambio == 1) {
                        if ($per->id > 0) {
                            $permiso = Permisos::find($per->id);
                            $permiso->descripcion = $per->descripcion;
                            $permiso->nombredoc = $per->tipodoc;
                            $permiso->nrodoc = $per->nrodoc;
                            $permiso->observaciones = $per->observaciones;
                            $permiso->fecha_ini = $per->inicio;
                            $permiso->fecha_fin = $per->fin;
                            $permiso->dias = $per->dias;
                            $permiso->mes = $per->meses;
                            $permiso->anio = $per->agnos;
                            $permiso->acuentavac = $per->acuenta;
                            $permiso->congoce = $per->congoce;
                            $permiso->archivo = $per->archivo;
                            $permiso->periodo = Carbon::parse($per->inicio)->year;
                            $permiso->save();
                        } else {
                            $permiso = new Permisos();
                            $permiso->idvinculo = $vinculo->id;
                            $permiso->descripcion = $per->descripcion;
                            $permiso->nombredoc = $per->tipodoc;
                            $permiso->nrodoc = $per->nrodoc;
                            $permiso->observaciones = $per->observaciones;
                            $permiso->fecha_ini = $per->inicio;
                            $permiso->fecha_fin = $per->fin;
                            $permiso->dias = $per->dias;
                            $permiso->mes = $per->meses;
                            $permiso->anio = $per->agnos;
                            $permiso->acuentavac = $per->acuenta;
                            $permiso->congoce = $per->congoce;
                            $permiso->archivo = $per->archivo;
                            $permiso->periodo = Carbon::parse($per->inicio)->year;
                            $permiso->save();
                        }
                    }
                    if ($per->cambio == 2) {
                        if ($per->id > 0) {
                            $permiso = Permisos::find($per->id);
                            $permiso->delete();
                        }
                    }
                }
            }
            ///////////////GUARDAR COMPENSACIONES
            if ($request->input("compensaciones")) {
                $datoscom = $request->input("compensaciones");
                $compensaciones = json_decode($datoscom);
                foreach ($compensaciones as $comp) {
                    if ($comp->cambio == 1) {
                        if ($comp->id == 0) {
                            $compensacion = new Compensaciones();
                            $compensacion->idvinculo = $vinculo->id;
                            $compensacion->tipo_compensacion = $comp->tipocom;
                            $compensacion->nombredoc = $comp->tipodoc;
                            $compensacion->descripcion = $comp->descripcion;
                            $compensacion->nrodoc = $comp->nrodoc;
                            $compensacion->fecha_ini = $comp->inicio;
                            $compensacion->dias = $comp->dias;
                            $compensacion->fecha_fin = $comp->fin;
                            $compensacion->archivo = $comp->archivo;
                            $compensacion->save();
                        } else {
                            $compensacion = Compensaciones::find($comp->id);
                            $compensacion->tipo_compensacion = $comp->tipocom;
                            $compensacion->nombredoc = $comp->tipodoc;
                            $compensacion->descripcion = $comp->descripcion;
                            $compensacion->nrodoc = $comp->nrodoc;
                            $compensacion->fecha_ini = $comp->inicio;
                            $compensacion->dias = $comp->dias;
                            $compensacion->fecha_fin = $comp->fin;
                            $compensacion->archivo = $comp->archivo;
                            $compensacion->save();
                        }
                    }
                    if ($comp->cambio == 2) {
                        if ($comp->id > 0) {
                            $compensacion = Compensaciones::find($comp->id);
                            $compensacion->delete();
                        }
                    }
                }
            }

            /////////////////GUARDAR RECONOCIMIENTOS
            if ($request->input('reconocimientos') != null) {
                $datosrecon = $request->input('reconocimientos');
                $reconocimientos = json_decode($datosrecon);
                foreach ($reconocimientos as $recon) {
                    if ($recon->cambio == 1) {
                        if ($recon->id == 0) {
                            $reconocimiento = new Reconocimientos();
                            $reconocimiento->idvinculo = $vinculo->id;
                            $reconocimiento->descripcion = $recon->descripcion;
                            $reconocimiento->nombredoc = $recon->tipodoc;
                            $reconocimiento->nrodoc = $recon->nrodoc;
                            $reconocimiento->fecharecon = $recon->fecharecon;
                            $reconocimiento->archivo = $recon->archivo;
                            $reconocimiento->save();
                        } else {
                            $reconocimiento = Reconocimientos::find($recon->id);
                            $reconocimiento->descripcion = $recon->descripcion;
                            $reconocimiento->nombredoc = $recon->tipodoc;
                            $reconocimiento->nrodoc = $recon->nrodoc;
                            $reconocimiento->fecharecon = $recon->fecharecon;
                            $reconocimiento->archivo = $recon->archivo;
                            $reconocimiento->save();
                        }
                    }
                    if ($recon->cambio == 2) {
                        if ($recon->id > 2) {
                            $reconocimiento = Reconocimientos::find($recon->id);
                            $reconocimiento->delete();
                        }
                    }
                }
            }

            /////////////GUARDAR SANCIONES
            if ($request->input('sanciones') != null) {
                $datossanciones = $request->input('sanciones');
                $sanciones = json_decode($datossanciones);
                foreach ($sanciones as $san) {
                    if ($san->cambio == 1) {
                        if ($san->id == 0) {
                            $sancion = new Sancion();
                            $sancion->idvinculo = $vinculo->id;
                            $sancion->descripcion = $san->motivo;
                            $sancion->nombredoc = $san->tipodoc;
                            $sancion->nrodoc = $san->nrodoc;
                            $sancion->fechadoc = $san->fechadoc;
                            $sancion->dias_san = $san->dias;
                            $sancion->fecha_ini = $san->inicio;
                            $sancion->fecha_fin = $san->fin;
                            $sancion->archivo = $san->archivo;
                            $sancion->save();
                        } else {
                            $sancion = Sancion::find($san->id);
                            $sancion->descripcion = $san->motivo;
                            $sancion->nombredoc = $san->tipodoc;
                            $sancion->nrodoc = $san->nrodoc;
                            $sancion->fechadoc = $san->fechadoc;
                            $sancion->dias_san = $san->dias;
                            $sancion->fecha_ini = $san->inicio;
                            $sancion->fecha_fin = $san->fin;
                            $sancion->archivo = $san->archivo;
                            $sancion->save();
                        }
                    }
                    if ($san->cambio == 2) {
                        if ($san->id > 2) {
                            $sancion = Sancion::find($san->id);
                            $sancion->delete();
                        }
                    }
                }
            }
        }

        return redirect('/edicion-personal?id=' . $personal->id_personal);
    }

    public function guardarEdicion(Request $request)
    {
        $id = $request->input('idpersonal');
        $tipodoc = $request->input('doc-identificacion');
        $nroidentificacion = $request->input('nro-identificacion');
        $tipopersonal = $request->input('tipo-personal');
        $apaterno = $request->input('apaterno');
        $amaterno = $request->input('amaterno');
        $nombres = $request->input('nombres');
        $sexo = $request->input('sexo');
        $fechanacimiento = $request->input('fecha-nacimiento');
        $estadocivil = $request->input('estadocivil');
        $procedencia = $request->input('procedencia');
        $celular = $request->input('celular');
        $correo = $request->input('correo');
        $ruc = $request->input('ruc');
        $nroEssalud = $request->input('nroessalud');
        $PCentroEssalud = $request->input('pcentroessalud');
        $PGrupoSanguineo = $request->input('pgruposanguineo');
        $sistemapensionario = $request->input('sistema-pensionario');
        $regimenp = $request->input('regimenp');
        $discapacidad = $request->input('discapacidad');
        $ffaa = $request->input('ffaa');
        $tipodom = $request->input('tipodom');
        $dactual = $request->input('dactual');
        $numero = $request->input('numero');
        $iddep = $request->input('iddep');
        $idpro = $request->input('idpro');
        $verficar=$request->input('verificar');
        $iddis = $request->input('iddis');
        $foto = $request->input('foto-perfil');
        $afiliacion = $request->input('radioDefault');
        $referencia = $request->input('referencia');
        $interior = $request->input('interior');
        $urlgeneral = $request->input('url-general');
        $personal = Personal::find($id);
        $personal->id_identificacion = $tipodoc;
        $personal->nro_documento_id = $nroidentificacion;
        $personal->apaterno = $apaterno;
        $personal->amaterno = $amaterno;
        $personal->nombres = $nombres;
        $personal->sexo = $sexo;
        $personal->id_tipo_personal = $tipopersonal;
        $personal->fechanacimiento = $fechanacimiento;
        $personal->lprocedencia = $procedencia;
        $personal->nrocelular = $celular;
        $personal->correo = $correo;
        $personal->nroruc = $ruc;
        $personal->nroessalud = $nroEssalud;
        $personal->grupoSanguineo = $PGrupoSanguineo;
        $personal->afp = $sistemapensionario;
        $personal->id_regimenp = $regimenp;
        $personal->discapacidad = $discapacidad;
        $personal->ffaa = $ffaa;
        $personal->foto=$foto;
        $personal->estadocivil = $estadocivil;
        $personal->centroessalud = $PCentroEssalud;
        $personal->afiliacion_salud = $afiliacion;
        $personal->urlgeneral = $urlgeneral;
        $personal->estado = 1;
        $personal->verificador=$verficar;
        $personal->save();
        $domicilio = Domicilio::where('personal_id', $id)->first();
        if (!$domicilio) {
            $domicilio = new Domicilio();
        }
        $domicilio->tipodom = $tipodom;
        $domicilio->dactual = $dactual;
        $domicilio->numero = $numero;
        $domicilio->iddep = $iddep;
        $domicilio->idpro = $idpro;
        $domicilio->iddis = $iddis;
        $domicilio->personal_id = $personal->id_personal;
        $domicilio->referencia = $referencia;
        $domicilio->interior = $interior;
        $domicilio->save();

        //Datos complementarios
        $datosFamilia = $request->input("familiares");
        $familiares = $objeto = json_decode($datosFamilia);
        if (isset($familiares)) {
            Familiares::where('personal_id', $id)->delete();
            foreach ($familiares as $familiar) {
                //$trabajador = DB::table('personal')->where('nro_documento_id', $familiar->dni)->where('id_identificacion', 'DNI')->first();
                $pariente = new Familiares();
                $pariente->personal_id = $personal->id_personal;
                $pariente->apaterno = $familiar->paterno;
                $pariente->docid = $familiar->doc;
                $pariente->nroid = $familiar->nro;
                $pariente->amaterno = $familiar->materno;
                $pariente->nombres = $familiar->nombre;
                $pariente->direccion = $familiar->direccion;
                $pariente->telefono = $familiar->tel;
                $pariente->parentesco = $familiar->parentesco;
                $pariente->save();
            }
        }

        $datosEstudio = $request->input("estudios-rea");
        $estudios = $objeto = json_decode($datosEstudio);
        if (isset($estudios)) {
            Estudios::where('personal_id', $id)->delete();
            foreach ($estudios as $estudio) {
                $formacion = new Estudios();
                $formacion->personal_id = $personal->id_personal;
                $formacion->nivel_educacion = $estudio->nivel;
                $formacion->especialidad = $estudio->especialidad;
                $formacion->centroestudios = $estudio->institucion;
                $formacion->fecha_ini = $estudio->inicio;
                $formacion->fecha_fin = $estudio->fin;
                $formacion->nombredoc = $estudio->doc;
                $formacion->archivo = $estudio->archivo;
                $formacion->save();
            }
        }
        $datosestudioscom = $request->input('estudios-com');
        $estudioscom = json_decode($datosestudioscom);
        if (isset($estudioscom)) {
            EstudiosEsp::where('personal_id', $id)->delete();
            foreach ($estudioscom as $especialidad) {
                $complementarios = new EstudiosEsp();
                $complementarios->personal_id = $personal->id_personal;
                $complementarios->nombre = $especialidad->denominacion;
                $complementarios->centroestudios = $especialidad->institucion;
                $complementarios->horas = $especialidad->horas;
                $complementarios->fecha_ini = $especialidad->inicio;
                $complementarios->fecha_fin = $especialidad->fin;
                $complementarios->nombredoc = $especialidad->tipodoc;
                $complementarios->archivo = $especialidad->archivo;
                $complementarios->save();
            }
        }
        $datoscolegiatura = $request->input('colegiatura');
        $colegiatura = json_decode($datoscolegiatura);
        if (isset($colegiatura)) {
            Colegiatura::where('personal_id', $id)->delete();
            foreach ($colegiatura as $colegio) {

                $col = new Colegiatura();
                $col->personal_id = $personal->id_personal;
                $col->nombre_colegio = $colegio->colegio;
                $col->nombredoc = $colegio->tipodoc;
                $col->nrodoc = $colegio->nrocol;
                $col->estado = $colegio->estado;
                $col->fechadoc = $colegio->fechacol;
                $col->archivo = $colegio->doccol;
                $col->save();
            }
        }
        $datosidiomas = $request->input('idiomas');
        $idiomas = json_decode($datosidiomas);
        if (isset($idiomas)) {
            Idiomas::where('personal_id', $id)->delete();
            foreach ($idiomas as $idioma) {

                $habla = new Idiomas();
                $habla->personal_id = $personal->id_personal;
                $habla->idioma = $idioma->idioma;
                $habla->lectura = $idioma->lectura;
                $habla->habla = $idioma->habla;
                $habla->escritura = $idioma->escritura;
                $habla->nombredoc = $idioma->tipodoc;
                $habla->archivo = $idioma->archivo;
                $habla->save();
            }
        }
        $datosexperiencia = $request->input('experiencia');
        $experiencia = json_decode($datosexperiencia);

        if (isset($experiencia)) {
            Explaboral::where('personal_id', $id)->delete();
            foreach ($experiencia as $antecedente) {
                $trabajo = new Explaboral();
                $trabajo->personal_id = $personal->id_personal;
                $trabajo->tipo_entidad = $antecedente->tipo;
                $trabajo->entidad = $antecedente->entidad;
                $trabajo->cargo = $antecedente->cargo;
                $trabajo->fecha_ini = $antecedente->inicio;
                $trabajo->fecha_fin = $antecedente->fin;
                $trabajo->area = $antecedente->area;
                $trabajo->nombredoc = $antecedente->tipodoc;
                $trabajo->nrodoc = $antecedente->nrodoc;
                $trabajo->archivo = $antecedente->archivo;
                $trabajo->save();
            }
        }

        if ($request->input('fecha-ini-vin') != null && $request->input('nrodoc-vin') != null) {
            if ($request->input('id-contrato') != null) {
                $vinculo = Vinculo::find($request->input('id-contrato'));
            } else {
                $vinculo = new Vinculo();
            }

            $vinculo->personal_id = $personal->id_personal;
            $vinculo->id_cargo = $request->input('id-cargo-vinculo');
            $vinculo->id_unidad_organica = $request->input('id-area-vin');
            $vinculo->fecha_ini = $request->input('fecha-ini-vin');
            $vinculo->id_regimen = $request->input('id-regimen-vin');
            $vinculo->nombredocvin = $request->input('tipodoc-vin');
            $vinculo->id_condicion_laboral = $request->input('id-condicion-laboral-vin');
            $vinculo->nro_doc = $request->input('nrodoc-vin');
            $vinculo->denominacion = $request->input('denominacion-cargo');
            $vinculo->filea = $request->input('periodo-file');
            $vinculo->lomo = $request->input('num-file');
            $vinculo->archivo = $request->input('doc-ingreso');

            if ($request->input('fecha-fin-vinculo') != null && $request->input('nrodoc-fin-vin') != null) {
                $vinculo->fecha_fin = $request->input('fecha-fin-vinculo');
                $vinculo->nombredoccese = $request->input('tipodoc-fin-vin');
                $vinculo->nro_doc_fin = $request->input('nrodoc-fin-vin');
                $vinculo->id_motivo_fin_vinculo = $request->input('id-motivo-fin-vinculo');
                $vinculo->motivocese = $request->input('motivo-cese');
                $vinculo->archivo_cese = $request->input('doc-cese');
            }
            $vinculo->save();
            ///GUARDAR ROTACIÓN
            if ($request->input('rotaciones') != null) {
                $datosrotaciones = $request->input('rotaciones');
                $rotaciones = json_decode($datosrotaciones);
                foreach ($rotaciones as $rotacion) {
                    if ($rotacion->cambio == 1) {
                        if ($rotacion->id == 0) {
                            $movimiento = new Movimientos();
                            $movimiento->personal_id = $personal->id_personal;
                            $movimiento->idvinculo = $vinculo->id;
                            $movimiento->unidad_organica_destino = $rotacion->destino;
                            $movimiento->cargo = $rotacion->cargo;
                            $movimiento->descripcion = $rotacion->descripcion;
                            $movimiento->fecha_ini = $rotacion->inicio;
                            $movimiento->fecha_fin = $rotacion->fin;
                            $movimiento->nombredoc = $rotacion->docini;
                            $movimiento->nrodoc = $rotacion->nroini;
                            $movimiento->nombredocfin = $rotacion->docfin;
                            $movimiento->nrodocfin = $rotacion->nrofin;
                            $movimiento->archivo = $rotacion->archivoini;
                            $movimiento->archivofin = $rotacion->archivofin;

                            $movimiento->tipo = 0;
                            $movimiento->estado = 1;
                            $movimiento->save();
                        } else {
                            $movimiento = Movimientos::find($rotacion->id);
                            $movimiento->unidad_organica_destino = $rotacion->destino;
                            $movimiento->cargo = $rotacion->cargo;
                            $movimiento->descripcion = $rotacion->descripcion;
                            $movimiento->fecha_ini = $rotacion->inicio;
                            $movimiento->fecha_fin = $rotacion->fin;
                            $movimiento->nombredoc = $rotacion->docini;
                            $movimiento->nrodoc = $rotacion->nroini;
                            $movimiento->nombredocfin = $rotacion->docfin;
                            $movimiento->nrodocfin = $rotacion->nrofin;
                            $movimiento->archivo = $rotacion->archivoini;
                            $movimiento->archivofin = $rotacion->archivofin;
                            $movimiento->save();
                        }
                    }
                    if ($rotacion->cambio == 2) {
                        if ($rotacion->id > 0) {
                            $movimiento = Movimientos::find($rotacion->id);
                            $movimiento->estado = 0;
                            $movimiento->save();
                        }
                    }
                }
            }

            ///////////guardar ENCARGATURAS
            if ($request->input('encargaturas') != null) {
                $datosencargaturas = $request->input('encargaturas');
                $encargaturas = json_decode($datosencargaturas);
                foreach ($encargaturas as $encargatura) {
                    if ($encargatura->cambio == 1) {
                        if ($encargatura->id == 0) {
                            $movimiento = new Movimientos();
                            $movimiento->personal_id = $personal->id_personal;
                            $movimiento->idvinculo = $vinculo->id;
                            $movimiento->unidad_organica_destino = $encargatura->destino;
                            $movimiento->cargo = $encargatura->cargo;
                            $movimiento->fecha_ini = $encargatura->inicio;
                            $movimiento->fecha_fin = $encargatura->fin;
                            $movimiento->nombredoc = $encargatura->docini;
                            $movimiento->nrodoc = $encargatura->nroini;
                            $movimiento->nombredocfin = $encargatura->docfin;
                            $movimiento->nrodocfin = $encargatura->nrofin;
                            $movimiento->descripcion = $encargatura->descripcion;
                            $movimiento->archivo = $encargatura->archivoini;
                            $movimiento->archivofin = $encargatura->archivofin;
                            $movimiento->tipo = 1;
                            $movimiento->estado = 1;
                            $movimiento->save();
                        } else {
                            $movimiento = Movimientos::find($encargatura->id);
                            $movimiento->unidad_organica_destino = $encargatura->destino;
                            $movimiento->cargo = $encargatura->cargo;
                            $movimiento->fecha_ini = $encargatura->inicio;
                            $movimiento->fecha_fin = $encargatura->fin;
                            $movimiento->nombredoc = $encargatura->docini;
                            $movimiento->nrodoc = $encargatura->nroini;
                            $movimiento->nombredocfin = $encargatura->docfin;
                            $movimiento->nrodocfin = $encargatura->nrofin;
                            $movimiento->archivo = $encargatura->archivoini;
                            $movimiento->archivofin = $encargatura->archivofin;
                            $movimiento->save();
                        }
                    }
                    if ($encargatura->cambio == 2) {
                        if ($encargatura->id > 0) {
                            $movimiento = Movimientos::find($encargatura->id);
                            $movimiento->estado = 0;
                            $movimiento->save();
                        }
                    }
                }
            }

            ///////GUARDAR VACACIONES
            if ($request->input('vacaciones') != null) {
                $datosVacaciones = $request->input('vacaciones');
                $vacaciones = json_decode($datosVacaciones);
                foreach ($vacaciones as $vac) {
                    if ($vac->cambio == 1) {
                        if ($vac->id == 0) {
                            $vacacion = new Vacaciones();
                            $vacacion->idvinculo = $vinculo->id;
                            $vacacion->nombredoc = $vac->tipodoc;
                            $vacacion->nrodoc = $vac->nrodoc;
                            $vacacion->periodo = $vac->periodo;
                            $vacacion->mes = $vac->mes;
                            $vacacion->fecha_ini = $vac->inicio;
                            $vacacion->fecha_fin = $vac->fin;
                            $vacacion->observaciones = $vac->observaciones;
                            $vacacion->dias = $vac->dias;
                            $vacacion->suspension = $vac->suspension;
                            $vacacion->archivo = $vac->archivo;
                            $vacacion->save();
                        } else {
                            $vacacion = Vacaciones::find($vac->id);

                            $vacacion->nombredoc = $vac->tipodoc;
                            $vacacion->nrodoc = $vac->nrodoc;
                            $vacacion->periodo = $vac->periodo;
                            $vacacion->mes = $vac->mes;
                            $vacacion->fecha_ini = $vac->inicio;
                            $vacacion->fecha_fin = $vac->fin;
                            $vacacion->observaciones = $vac->observaciones;
                            $vacacion->dias = $vac->dias;
                            $vacacion->suspension = $vac->suspension;
                            $vacacion->archivo = $vac->archivo;
                            $vacacion->save();
                        }
                    }
                    if ($vac->cambio == 2) {
                        if ($vac->id > 0) {
                            $vacacion = Vacaciones::find($vac->id);
                            $vacacion->delete();
                        }
                    }
                }
            }


            ///////////GUARDAR LICENCIAS
            if ($request->input("licencias") != null) {
                $datoslicencias = $request->input('licencias');
                $licencias = json_decode($datoslicencias);
                foreach ($licencias as $lic) {
                    if ($lic->cambio == 1) {
                        if ($lic->id > 0) {
                            $licencia = Licencias::find($lic->id);
                            $licencia->nombredoc = $lic->tipodoc;
                            $licencia->descripcion = $lic->descripcion;
                            $licencia->nrodoc = $lic->nrodoc;
                            $licencia->observaciones = $lic->observaciones;
                            $licencia->fecha_ini = $lic->inicio;
                            $licencia->fecha_fin = $lic->fin;
                            $licencia->dias = $lic->dias;
                            $licencia->mes = $lic->meses;
                            $licencia->anio = $lic->agnos;
                            $licencia->acuentavac = $lic->acuenta;
                            $licencia->congoce = $lic->congoce;
                            $licencia->archivo = $lic->archivo;
                            $licencia->periodo = Carbon::parse($lic->inicio)->year;
                            $licencia->save();
                        } else {
                            $licencia = new Licencias();
                            $licencia->idvinculo = $vinculo->id;
                            $licencia->nombredoc = $lic->tipodoc;
                            $licencia->descripcion = $lic->descripcion;
                            $licencia->nrodoc = $lic->nrodoc;
                            $licencia->observaciones = $lic->observaciones;
                            $licencia->fecha_ini = $lic->inicio;
                            $licencia->fecha_fin = $lic->fin;
                            $licencia->dias = $lic->dias;
                            $licencia->mes = $lic->meses;
                            $licencia->anio = $lic->agnos;
                            $licencia->acuentavac = $lic->acuenta;
                            $licencia->congoce = $lic->congoce;
                            $licencia->archivo = $lic->archivo;
                            $licencia->periodo = Carbon::parse($lic->inicio)->year;
                            $licencia->save();
                        }
                    }
                    if ($lic->cambio == 2) {
                        if ($lic->id > 0) {
                            $licencia = Licencias::find($lic->id);
                            $licencia->delete();
                        }
                    }
                }
            }

            ////////////GUARDAR PERMISOS
            if ($request->input("permisos") != null) {
                $datospermisos = $request->input('permisos');
                $permisos = json_decode($datospermisos);
                foreach ($permisos as $per) {
                    if ($per->cambio == 1) {
                        if ($per->id > 0) {
                            $permiso = Permisos::find($per->id);
                            $permiso->descripcion = $per->descripcion;
                            $permiso->nombredoc = $per->tipodoc;
                            $permiso->nrodoc = $per->nrodoc;
                            $permiso->observaciones = $per->observaciones;
                            $permiso->fecha_ini = $per->inicio;
                            $permiso->fecha_fin = $per->fin;
                            $permiso->dias = $per->dias;
                            $permiso->mes = $per->meses;
                            $permiso->anio = $per->agnos;
                            $permiso->acuentavac = $per->acuenta;
                            $permiso->archivo = $per->archivo;
                            $permiso->periodo = (!is_null($per->periodo) && trim($per->periodo) !== '')
                                ? $per->periodo
                                : date('Y');
                            $permiso->save();
                        } else {
                            $permiso = new Permisos();
                            $permiso->idvinculo = $vinculo->id;
                            $permiso->descripcion = $per->descripcion;
                            $permiso->nombredoc = $per->tipodoc;
                            $permiso->nrodoc = $per->nrodoc;
                            $permiso->observaciones = $per->observaciones;
                            $permiso->fecha_ini = $per->inicio;
                            $permiso->fecha_fin = $per->fin;
                            $permiso->dias = $per->dias;
                            $permiso->mes = $per->meses;
                            $permiso->anio = $per->agnos;
                            $permiso->acuentavac = $per->acuenta;
                            $permiso->archivo = $per->archivo;
                            $permiso->periodo = (!is_null($per->periodo) && trim($per->periodo) !== '')
                                ? $per->periodo
                                : date('Y');
                            $permiso->save();
                        }
                    }
                    if ($per->cambio == 2) {
                        if ($per->id > 0) {
                            $permiso = Permisos::find($per->id);
                            $permiso->delete();
                        }
                    }
                }
            }
            ///////////////GUARDAR COMPENSACIONES
            if ($request->input("compensaciones")) {
                $datoscom = $request->input("compensaciones");
                $compensaciones = json_decode($datoscom);
                foreach ($compensaciones as $comp) {
                    if ($comp->cambio == 1) {
                        if ($comp->id == 0) {
                            $compensacion = new Compensaciones();
                            $compensacion->idvinculo = $vinculo->id;
                            $compensacion->tipo_compensacion = $comp->tipo;
                            $compensacion->nombredoc = $comp->tipodoc;
                            $compensacion->descripcion = $comp->descripcion;
                            $compensacion->nrodoc = $comp->nrodoc;
                            $compensacion->fecha_ini = $comp->inicio;
                            $compensacion->dias = $comp->dias;
                            $compensacion->fecha_fin = $comp->fin;
                            $compensacion->archivo = $comp->archivo;
                            $compensacion->save();
                        } else {
                            $compensacion = Compensaciones::find($comp->id);
                            $compensacion->tipo_compensacion = $comp->tipo;
                            $compensacion->nombredoc = $comp->tipodoc;
                            $compensacion->descripcion = $comp->descripcion;
                            $compensacion->nrodoc = $comp->nrodoc;
                            $compensacion->fecha_ini = $comp->inicio;
                            $compensacion->dias = $comp->dias;
                            $compensacion->fecha_fin = $comp->fin;
                            $compensacion->archivo = $comp->archivo;
                            $compensacion->save();
                        }
                    }
                    if ($comp->cambio == 2) {
                        if ($comp->id > 0) {
                            $compensacion = Compensaciones::find($comp->id);
                            $compensacion->delete();
                        }
                    }
                }
            }

            /////////////////GUARDAR RECONOCIMIENTOS
            if ($request->input('reconocimientos') != null) {
                $datosrecon = $request->input('reconocimientos');
                $reconocimientos = json_decode($datosrecon);
                foreach ($reconocimientos as $recon) {
                    if ($recon->cambio == 1) {
                        if ($recon->id == 0) {
                            $reconocimiento = new Reconocimientos();
                            $reconocimiento->idvinculo = $vinculo->id;
                            $reconocimiento->forma = $recon->forma;
                            $reconocimiento->descripcion = $recon->descripcion;
                            $reconocimiento->nombredoc = $recon->tipodoc;
                            $reconocimiento->nrodoc = $recon->nrodoc;
                            $reconocimiento->fecharecon = $recon->fecharecon;
                            $reconocimiento->fecha_ini = $recon->inicio;
                            $reconocimiento->fecha_fin = $recon->fin;
                            $reconocimiento->archivo = $recon->archivo;
                            $reconocimiento->save();
                        } else {
                            $reconocimiento = Reconocimientos::find($recon->id);
                            $reconocimiento->forma = $recon->forma;
                            $reconocimiento->descripcion = $recon->descripcion;
                            $reconocimiento->nombredoc = $recon->tipodoc;
                            $reconocimiento->nrodoc = $recon->nrodoc;
                            $reconocimiento->fecharecon = $recon->fecharecon;
                            $reconocimiento->fecha_ini = $recon->inicio;
                            $reconocimiento->fecha_fin = $recon->fin;
                            $reconocimiento->archivo = $recon->archivo;
                            $reconocimiento->save();
                        }
                    }
                    if ($recon->cambio == 2) {
                        if ($recon->id > 2) {
                            $reconocimiento = Reconocimientos::find($recon->id);
                            $reconocimiento->delete();
                        }
                    }
                }
            }

            /////////////GUARDAR SANCIONES
            if ($request->input('sanciones') != null) {

                $datossanciones = $request->input('sanciones');
                $sanciones = json_decode($datossanciones);
                foreach ($sanciones as $san) {

                    if ($san->cambio == 1) {
                        if ($san->id == 0) {
                            $sancion = new Sancion();
                            $sancion->idvinculo = $vinculo->id;
                            $sancion->descripcion = $san->motivo;
                            $sancion->nombredoc = $san->tipodoc;
                            $sancion->nrodoc = $san->nrodoc;
                            $sancion->fechadoc = $san->fechadoc;
                            $sancion->dias_san = $san->dias;
                            $sancion->fecha_ini = $san->inicio;
                            $sancion->fecha_fin = $san->fin;
                            $sancion->archivo = $san->archivo;
                            $sancion->save();
                        } else {
                            $sancion = Sancion::find($san->id);
                            $sancion->descripcion = $san->motivo;
                            $sancion->nombredoc = $san->tipodoc;
                            $sancion->nrodoc = $san->nrodoc;
                            $sancion->fechadoc = $san->fechadoc;
                            $sancion->dias_san = $san->dias;
                            $sancion->fecha_ini = $san->inicio;
                            $sancion->fecha_fin = $san->fin;
                            $sancion->archivo = $san->archivo;
                            $sancion->save();
                        }
                    }
                    if ($san->cambio == 2) {
                        if ($san->id > 2) {
                            $sancion = Sancion::find($san->id);
                            $sancion->delete();
                        }
                    }
                }
            }
        }

        return redirect('/edicion-personal?id=' . $personal->id_personal);
    }

    public function consultarVacaciones(Request $request)
    {
        $request->validate([
            'periodo' => 'required|int',
            'vinculo' => 'required|int'
        ]);
        $vacaciones = DB::select('exec historialVacaciones ?, ?', [$request->vinculo, $request->periodo]);

        if (!empty($vacaciones)) {
            return response()->json($vacaciones);
        } else {
            return response()->json([
                'cod' => 0,
                'res' => 'No se encontró resultados'
            ]);
        }
    }
    public function consultarLicencias(Request $request)
    {
        $request->validate([
            'periodo' => 'required|int',
            'vinculo' => 'required|int'
        ]);
        $licencias = DB::select('exec historialLicencias ?, ?', [$request->vinculo, $request->periodo]);

        if (!empty($licencias)) {
            return response()->json($licencias);
        } else {
            return response()->json([
                'cod' => 0,
                'res' => 'No se encontró resultados'
            ]);
        }
    }
    public function consultarPermisos(Request $request)
    {
        $request->validate([
            'periodo' => 'required|int',
            'vinculo' => 'required|int'
        ]);
        $permisos = DB::select('exec historialPermisos ?, ?', [$request->vinculo, $request->periodo]);

        if (!empty($permisos)) {
            return response()->json($permisos);
        } else {
            return response()->json([
                'cod' => 0,
                'res' => 'No se encontró resultados'
            ]);
        }
    }

    public function anularPersonal($id)
    {
        $personal = Personal::find($id);
        $personal->estado = 0;
        $personal->save();
        return redirect('/home');
    }

    public function agregarTipoPersonal(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string'
        ]);
        $nuevo = DB::table('tipo_personal')->insert(['nombre' => mb_strtoupper($request->nombre, 'UTF-8')]);
        $guardado = DB::table('tipo_personal')->select()->orderBy('nombre', 'ASC')->get();
        return response()->json($guardado);
    }

    public function agregarNuevaVia(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string'
        ]);
        $nuevo = new TipoVia();
        $nuevo->nombre = mb_strtoupper($request->nombre, 'UTF-8');
        $nuevo->save();
        $guardado = DB::table('tipo_via')->select()->orderBy('nombre', 'ASC')->get();
        return response()->json($guardado);
    }

    public function agregarNuevoCargo(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string'
        ]);
        $nuevo = new Cargo();
        $nuevo->nombre = mb_strtoupper($request->nombre, 'UTF-8');
        $nuevo->estado = 1;
        $nuevo->save();
        $guardado = DB::table('cargo')->select()->orderBy('nombre', 'ASC')->get();
        return response()->json($guardado);
    }

    public function nuevaCondicionLab(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string'
        ]);
        $nuevo = new CondicionLaboral();
        $nuevo->nombre = mb_strtoupper($request->nombre, 'UTF-8');
        $nuevo->descripcion_regimen = $request->descripcion;
        $nuevo->save();
        $guardado = DB::table('condicion_laboral')->select()->orderBy('nombre', 'ASC')->get();
        return response()->json($guardado);
    }

    public function agregarNuevaCompensacion(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string'
        ]);
        $nuevo = new TipoCompensacion();
        $nuevo->nombre = mb_strtoupper($request->nombre, 'UTF-8');
        $nuevo->save();
        $guardado = DB::table('tipo_compensacion')->select()->orderBy('nombre', 'ASC')->get();
        return response()->json($guardado);
    }

    public function ingresoNumero()
    {

        return view('Personal.ingresarNumero');
    }
    public function validarNumero(Request $request)
    {
        $numero = $request->input('num-wpp');
        return view('Personal.validarNumero');
    }
    public function setPersonal(Request $request)
    {
        $areas = null; //areas habilitadas
        $areas = DB::table('area')
            ->select('id', 'nombre')->where('estado', 1)->orderBy('nombre')->get();
        $cargos = null; //nombres de cargo
        $cargos = DB::table('cargo')
            ->select('id', 'nombre')->where('estado', 1)->orderBy('nombre')->get();
        $tipo_docs = Tipodoc::where('categoria', 'LIKE', '%"DAP"%')->get();
        return view('Personal.ingresarDatos', array('tiposdoc' => $tipo_docs, 'areas' => $areas, 'cargos' => $cargos));
    }

    public function guardarIngresoMasivo(Request $request)
    {
        $tipodoc = $request->input('doc-identificacion');
        $nroidentificacion = $request->input('nro-identificacion');
        $tipopersonal = $request->input('tipo-personal') ?? 1;
        $apaterno = $request->input('apaterno');
        $amaterno = $request->input('amaterno');
        $nombres = $request->input('nombres');
        $sexo = $request->input('sexo');
        $fechanacimiento = $request->input('fecha-nacimiento');
        $estadocivil = $request->input('estadocivil');
        $procedencia = $request->input('procedencia');
        $celular = $request->input('celular');
        $correo = $request->input('correo');
        $ruc = $request->input('ruc');
        $foto = $request->input('perfil-base64');
        $nroEssalud = $request->input('nroessalud');
        $PCentroEssalud = $request->input('pcentroessalud');
        $PGrupoSanguineo = $request->input('pgruposanguineo');
        $sistemapensionario = $request->input('sistema-pensionario');
        $regimenp = $request->input('regimenp');
        $discapacidad = $request->input('discapacidad');
        $ffaa = $request->input('ffaa');
        $tipodom = $request->input('tipodom');
        $dactual = $request->input('dactual');
        $numero = $request->input('numero');
        $iddep = $request->input('iddep');
        $idpro = $request->input('idpro');
        $iddis = $request->input('iddis');
        $afiliacion = $request->input('radioDefault');
        $referencia = $request->input('referencia');
        $interior = $request->input('interior');
        $id = $request->input('id-personal');
        if (empty($id)) {
            $personal = new Personal();
        } else {
            $personal = Personal::find($id);
        }

        $personal->id_identificacion = $tipodoc;
        $personal->nro_documento_id = $nroidentificacion;
        $personal->apaterno = $apaterno;
        $personal->amaterno = $amaterno;
        $personal->nombres = $nombres;
        $personal->sexo = $sexo;
        $personal->id_tipo_personal = $tipopersonal;
        $personal->fechanacimiento = $fechanacimiento;
        $personal->lprocedencia = $procedencia;
        $personal->nrocelular = $celular;
        $personal->correo = $correo;
        $personal->nroruc = $ruc;
        $personal->nroessalud = $nroEssalud;
        $personal->grupoSanguineo = $PGrupoSanguineo;
        $personal->afp = $sistemapensionario;
        $personal->id_regimenp = $regimenp;
        $personal->discapacidad = $discapacidad;
        $personal->afiliacion_salud = $afiliacion;
        $personal->ffaa = $ffaa;
        $personal->estadocivil = $estadocivil;
        $personal->centroessalud = $PCentroEssalud;
        $personal->foto = $foto;
        $personal->estado = 1;
        $personal->save();
        if (empty($id)) {
            $domicilio = new Domicilio();
        } else {
            $domicilio = Domicilio::where('personal_id', $id)->first();

            if(!isset($domicilio))
                $domicilio=new Domicilio();
        }

        $domicilio->tipodom = $tipodom;
        $domicilio->dactual = $dactual;
        $domicilio->numero = $numero;
        $domicilio->iddep = $iddep;
        $domicilio->idpro = $idpro;
        $domicilio->iddis = $iddis;
        $domicilio->personal_id = $personal->id_personal;
        $domicilio->referencia = $referencia;
        $domicilio->interior = $interior;
        $domicilio->save();

        if ($request->input('id-area-vin') != null && $request->input('id-cargo-vinculo') != null) {
            if (empty($id)) {
                $vinculo = new Vinculo();
            } else {
                $vinculo = Vinculo::where('personal_id', $id)->first();
                if (!isset($vinculo))
                    $vinculo = new Vinculo();
            }

            $vinculo->personal_id = $personal->id_personal;
            $vinculo->id_cargo = $request->input('id-cargo-vinculo');
            $vinculo->id_unidad_organica = $request->input('id-area-vin');
            $vinculo->fecha_ini = $request->input('fecha-ini-vin');
            $vinculo->id_regimen = $request->input('id-regimen-vin');
            $vinculo->nombredocvin = $request->input('tipodoc-vin');

            $vinculo->id_condicion_laboral = $request->input('id-condicion-laboral-vin');
            $vinculo->nro_doc = $request->input('nrodoc-vin');
            $vinculo->denominacion = $request->input('denominacion');
            $vinculo->filea = $request->input('periodo-file');
            $vinculo->lomo = $request->input('num-file');
            $vinculo->archivo = $request->input('doc-ingreso');


            $vinculo->save();
        }

        return redirect('/agradecimiento?id=' . $personal->id_personal);
    }
    public function agradecimiento(Request $request)
    {
        return view('Personal.agradecimiento');
    }

    public function editSetPersonal(Request $request)
    {
        $id = $request->query('id');
        $personal = Personal::find($id);
        $domicilio = Domicilio::where('personal_id', $id)->first();
        $provincias=array();
        $distritos=array();
        if (!empty($domicilio)) {
            $provincias = Provincia::where('departamento_id', $domicilio->iddep)->get();
            $distritos = Distrito::where('provincia_id', $domicilio->idpro)->get();
        }

        $vinculo = Vinculo::where('personal_id', $id)->first();
        $areas = null; //areas habilitadas
        $areas = DB::table('area')
            ->select('id', 'nombre')->where('estado', 1)->orderBy('nombre')->get();
        $cargos = null; //nombres de cargo
        $cargos = DB::table('cargo')
            ->select('id', 'nombre')->where('estado', 1)->orderBy('nombre')->get();
        $tipo_docs = Tipodoc::where('categoria', 'LIKE', '%"DAP"%')->get();
        return view('Personal.editarDatos', array('tiposdoc' => $tipo_docs, 'areas' => $areas, 'cargos' => $cargos, 'id' => $id, 'personal' => $personal, 'domicilio' => $domicilio, 'vinculo' => $vinculo, 'provincias' => $provincias, 'distritos' => $distritos));
    }
}
