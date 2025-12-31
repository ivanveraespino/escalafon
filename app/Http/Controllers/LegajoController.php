<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Personal;
use App\Models\Domicilio;
use App\Models\Explaboral;
use App\Models\CargoEntidad;
use App\Models\Condicionlab;
use App\Models\Vinculo;
use App\Models\Estudios;
use App\Models\Familiares;
use App\Models\Reconocimientos;
use App\Models\Idiomas;
use App\Models\Cese;
use App\Models\Sancion;
use App\Models\Nombramiento;
use App\Models\Vacaciones;
use App\Models\Licencias;
use App\Models\Permisos;
use App\Models\CronogramaVacaciones;
use App\Models\EstudiosEsp;
use App\Models\Movimientos;
use App\Models\Adenda;
use App\Models\Archivo;
use App\Models\ArchivosAdjuntos;
use App\Models\Compensaciones;
use App\Models\TiempoServicio;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\File;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage; 
use Carbon\Carbon;

use setasign\Fpdf\Fpdf;
use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\Fpdi\PdfReader\PdfReader;
use setasign\Fpdi\PdfParser\StreamReader;
use DB;
use PDF;



class LegajoController extends Controller
{
    //VIEWS
    public function index()
    {
        return view('ficha.index');
    }

    public function viewDatosPersonal(Request $request){
        $estado = $request->route('estado');

        return view('datoslegajo.views.d_personales',compact('estado'));
    }

    public function viewFamiliares(Request $request){
        $idp = $request->id;
        $dp = null;
        if (is_numeric($idp) && intval($idp) == $idp) {
            $dp = Personal::selectRaw("CONCAT(Apaterno, ' ', Amaterno, ' ', Nombres) as nombre")
            ->where('id_personal', $idp)
            ->first();
        }
        if (!$idp) {
            return view('datosLegajo.views.familiares',compact('idp','dp'));
        }
        if (!$dp && !$idp) {
            abort(404, 'El registro no existe.');
        }

        return view('datosLegajo.views.familiares',compact('idp','dp'));
    }

    public function viewVinculos(Request $request){
        $idp = $request->id;
        $dp = null;
        //AGREGANDO FUNCIONES PARA TERMINAR VINCULO
        $vin_fin = DB::table('motivo_fin_vinculo')->select('id', 'nombre')->get();

        if (is_numeric($idp) && intval($idp) == $idp) {
            $dp = Personal::selectRaw("CONCAT(Apaterno, ' ', Amaterno, ' ', Nombres) as nombre")
            ->where('id_personal', $idp)
            ->first();
        }
        if (!$idp) {
            return view('datosLegajo.views.vinculos',compact('idp','dp','vin_fin'));
        }
        if (!$dp) {
            abort(404, 'El registro no existe.');
        }
        return view('datosLegajo.views.vinculos',compact('idp','dp','vin_fin'));
    }


    public function viewExperiencia(Request $request){
        $idp = $request->id;
        $dp = null;
        if (is_numeric($idp) && intval($idp) == $idp) {
            $dp = Personal::selectRaw("CONCAT(Apaterno, ' ', Amaterno, ' ', Nombres) as nombre")
            ->where('id_personal', $idp)
            ->first();
        }
        if (!$idp) {
            return view('datosLegajo.views.experienciaLab',compact('idp','dp'));
        }
        if (!$dp) {
            abort(404, 'El registro no existe.');
        }
        return view('datosLegajo.views.experienciaLab',compact('idp','dp'));
    }

    public function viewMovimientos(Request $request){
        $idp = $request->id;
        $dp = null;
        $vl_last = null;
        if (is_numeric($idp) && intval($idp) == $idp) {

            $vl_last = DB::table('vinculos as v')
            ->join('area as a', 'v.id_unidad_organica', '=', 'a.id') 
            ->where('personal_id', $idp)
            ->where(function ($query) {
                $query->whereNull('fecha_fin') // Fecha fin es nula
                      ->orWhere('fecha_fin', '>=', now()); // Fecha fin es mayor o igual a la actual
            })
            ->orderBy('v.fecha_ini', 'desc') 
            ->select('v.*', 'a.nombre as nombre_area') 
            ->first(); 
            $dp = Personal::selectRaw("CONCAT(Apaterno, ' ', Amaterno, ' ', Nombres) as nombre")
            ->where('id_personal', $idp)
            ->first();
        
        }
        if (!$idp) {
            return view('datosLegajo.views.movimientos',compact('idp','dp','vl_last'));
        }
        if (!$dp) {
            abort(404, 'El registro no existe.');
        }
        return view('datosLegajo.views.movimientos',compact('idp','dp','vl_last'));
    }

    public function get_uo_vigente(Request $request){
        
        $idp = $request->id;
        $vl_last = DB::table('vinculos as v')
        ->join('area as a', 'v.id_unidad_organica', '=', 'a.id') 
        ->where('personal_id', $idp)
        ->where(function ($query) {
            $query->whereNull('fecha_fin') // Fecha fin es nula
                  ->orWhere('fecha_fin', '>=', now()); 
        })
        ->orderBy('v.fecha_ini', 'desc') 
        ->select('v.*', 'a.nombre as nombre_unidad_organica') 
        ->first();
        return response()->json($vl_last);
    }


    public function viewReconocimientos(Request $request)
    {
        $idp = $request->id;
        $dp = null;
    
        if (is_numeric($idp) && intval($idp) == $idp) {
            $dp = Personal::selectRaw("CONCAT(Apaterno, ' ', Amaterno, ' ', Nombres) as nombre")
                ->where('id_personal', $idp)
                ->first();
        }
    
        $filter = $request->filter;
    
        if ($filter) {
            // Ruta del archivo de la vista Blade
            $viewName = 'datosLegajo.views.reconocimientos';
            $data = compact('idp', 'dp');

            // Obtener el path completo del archivo Blade
            $viewPath = resource_path('views/' . str_replace('.', '/', $viewName) . '.blade.php');
            
            // Leer el contenido del archivo Blade
            //$content = File::get($viewPath);
            $content = view($viewName, $data)->render();

            // Eliminar directivas Blade como @extends, @section, etc.
            $cleanedContent = $this->cleanBladeContent($content);
    
            // Retornar el contenido limpio en formato JSON
            return response()->json(['html' => $cleanedContent]);
        }
    
        // Si no se pasa el filtro, se devuelve la vista normalmente
        if (!$idp) {
            return view('datosLegajo.views.reconocimientos', compact('idp', 'dp'));
        }
    
        if (!$dp) {
            abort(404, 'El registro no existe.');
        }
    
        return view('datosLegajo.views.reconocimientos', compact('idp', 'dp'));
    }
    
    public function viewVacaciones(Request $request){
        $idp = $request->id;
        $dp = null;
        if (is_numeric($idp) && intval($idp) == $idp) {
            $dp = Personal::selectRaw("CONCAT(Apaterno, ' ', Amaterno, ' ', Nombres) as nombre")
            ->where('id_personal', $idp)
            ->first();
        }
        if (!$idp) {
            return view('datosLegajo.views.vacaciones',compact('idp','dp'));
        }
        if (!$dp) {
            abort(404, 'El registro no existe.');
        }
        return view('datosLegajo.views.vacaciones',compact('idp','dp'));
    }
    
    public function viewLicencias(Request $request){
        $idp = $request->id;
        $dp = null;
        if (is_numeric($idp) && intval($idp) == $idp) {
            $dp = Personal::selectRaw("CONCAT(Apaterno, ' ', Amaterno, ' ', Nombres) as nombre")
            ->where('id_personal', $idp)
            ->first();
        }
        if (!$idp) {
            return view('datosLegajo.views.licencias',compact('idp','dp'));
        }
        if (!$dp) {
            abort(404, 'El registro no existe.');
        }
        return view('datosLegajo.views.licencias',compact('idp','dp'));
    }
    
    public function viewPermisos(Request $request){
        $idp = $request->id;
        $dp = null;
        if (is_numeric($idp) && intval($idp) == $idp) {
            $dp = Personal::selectRaw("CONCAT(Apaterno, ' ', Amaterno, ' ', Nombres) as nombre")
            ->where('id_personal', $idp)
            ->first();
        }
        if (!$idp) {
            return view('datosLegajo.views.permisos',compact('idp','dp'));
        }
        if (!$dp) {
            abort(404, 'El registro no existe.');
        }
        return view('datosLegajo.views.permisos',compact('idp','dp'));
    }

    public function viewIdiomas(Request $request){
        $idp = $request->id;
        $dp = null;
        if (is_numeric($idp) && intval($idp) == $idp) {
            $dp = Personal::selectRaw("CONCAT(Apaterno, ' ', Amaterno, ' ', Nombres) as nombre")
            ->where('id_personal', $idp)
            ->first();
        }
        if (!$idp) {
            return view('datosLegajo.views.idiomas',compact('idp','dp'));
        }
        if (!$dp) {
            abort(404, 'El registro no existe.');
        }
        return view('datosLegajo.views.idiomas',compact('idp','dp'));
    }

    public function viewCompesaciones(Request $request){
        $idp = $request->id;
        $tcomp = DB::table('tipo_compensacion')->get();
        $dp = null;
        if (is_numeric($idp) && intval($idp) == $idp) {
            $dp = Personal::selectRaw("CONCAT(Apaterno, ' ', Amaterno, ' ', Nombres) as nombre")
            ->where('id_personal', $idp)
            ->first();
        }
        if (!$idp) {
            return view('datosLegajo.views.compensaciones',compact('tcomp','idp','dp'));
        }
        if (!$dp) {
            abort(404, 'El registro no existe.');
        }
        return view('datosLegajo.views.compensaciones',compact('tcomp','idp','dp'));
    }

    public function viewFormacion(Request $request){
        $idp = $request->id;
        $dp = null;
        if (is_numeric($idp) && intval($idp) == $idp) {
            $dp = Personal::selectRaw("CONCAT(Apaterno, ' ', Amaterno, ' ', Nombres) as nombre")
            ->where('id_personal', $idp)
            ->first();
        }
        if (!$idp) {
            return view('datosLegajo.views.formacion',compact('idp','dp'));
        }
        if (!$dp) {
            abort(404, 'El registro no existe.');
        }
        return view('datosLegajo.views.formacion',compact('idp','dp'));
    }

    public function viewColegiatura(Request $request){
        $idp = $request->id;
        $dp = null;
        if (is_numeric($idp) && intval($idp) == $idp) {
            $dp = Personal::selectRaw("CONCAT(Apaterno, ' ', Amaterno, ' ', Nombres) as nombre")
            ->where('id_personal', $idp)
            ->first();
        }
        if (!$idp) {
            return view('datosLegajo.views.colegiatura',compact('idp','dp'));
        }
        if (!$dp) {
            abort(404, 'El registro no existe.');
        }
        return view('datosLegajo.views.colegiatura',compact('idp','dp'));
    }

    public function viewSanciones(Request $request){
        $idp = $request->id;
        $dp = null;
        if (is_numeric($idp) && intval($idp) == $idp) {
            $dp = Personal::selectRaw("CONCAT(Apaterno, ' ', Amaterno, ' ', Nombres) as nombre")
            ->where('id_personal', $idp)
            ->first();
        }
        if (!$idp) {
            return view('datosLegajo.views.sanciones',compact('idp','dp'));
        }
        if (!$dp) {
            abort(404, 'El registro no existe.');
        }
        return view('datosLegajo.views.sanciones',compact('idp','dp'));
    }

    public function viewEstudiosCom(Request $request){
        $idp = $request->id;
        $dp = null;
        if (is_numeric($idp) && intval($idp) == $idp) {
            $dp = Personal::selectRaw("CONCAT(Apaterno, ' ', Amaterno, ' ', Nombres) as nombre")
            ->where('id_personal', $idp)
            ->first();
        }
        if (!$idp) {
            return view('datosLegajo.views.estudiosCom',compact('idp','dp'));
        }
        if (!$dp) {
            abort(404, 'El registro no existe.');
        }
        return view('datosLegajo.views.estudiosCom',compact('idp','dp'));
    }

    public function viewAsignacionTiempo(Request $request){
        $idp = $request->id;
        $dp = null;
        if (is_numeric($idp) && intval($idp) == $idp) {
            $dp = Personal::selectRaw("CONCAT(Apaterno, ' ', Amaterno, ' ', Nombres) as nombre")
            ->where('id_personal', $idp)
            ->first();
        }
        if (!$idp) {
            return view('datosLegajo.views.asignacionTiempo',compact('idp','dp'));
        }
        if (!$dp) {
            abort(404, 'El registro no existe.');
        }
        return view('datosLegajo.views.asignacionTiempo',compact('idp','dp'));
    }

    public function viewOtrosArchivo(Request $request){
        $idp = $request->id;
        $tarch = DB::table('tipo_archivo')->get();
        $cat = DB::table('categorias')->get();
        $dp = null;
        if (is_numeric($idp) && intval($idp) == $idp) {
            $dp = Personal::selectRaw("CONCAT(Apaterno, ' ', Amaterno, ' ', Nombres) as nombre")
            ->where('id_personal', $idp)
            ->first();
        }
        if (!$idp) {
            return view('datosLegajo.views.otrosArchivos',compact('tarch','cat','idp','dp'));
        }
        if (!$dp) {
            abort(404, 'El registro no existe.');
        }
        return view('datosLegajo.views.otrosArchivos',compact('tarch','cat','idp','dp'));
    }

    function cleanBladeContent($content)
    {
        // Eliminar directivas comunes de Blade
        $patterns = [
            '/@extends\([^)]+\)/' => '',   // Eliminar @extends
            '/@section\([^)]+\)/' => '',   // Eliminar @section
            '/@endsection/' => '',         // Eliminar @endsection
            '/@yield\([^)]+\)/' => '',     // Eliminar @yield
            '/@include\([^)]+\)/' => '',   // Eliminar @include
            '/@stop/' => '',               // Eliminar @stop
        ];

        return preg_replace(array_keys($patterns), array_values($patterns), $content);
    }

    //ACTIONS
    public function editarFicha(Request $request){
        $dp = Personal::where('id_personal',$request->dni)->first();
        $tireg = DB::table('regimen')->pluck('nombre', 'id');
        return view('ficha.index', compact('dp','tireg'));
    }
    

    public function mostrardp(Request $request) {
        $dp = Personal::where('id_personal', $request->id)->first();
        $dd = Domicilio::where('personal_id', $request->id)->first();
    
        $data1 = json_decode($dp, true);
        $data2 = $dd ? json_decode($dd, true) : [];
    
        $combinedData = array_merge($data1, $data2);
        return response()->json($combinedData);
    }
    
    public function mostrardpqr(Request $request) {
        $dp = Personal::where('id_personal', $request->id)
                        ->select('Nombres', 'Apaterno', 'Correo', 'Amaterno')
                        ->first();
    
        return response()->json($dp);
    }
    //Ver archivos
    public function showfiles($id)
    {
        
        ini_set('memory_limit', '1G');
        $archivo = Archivo::findOrFail($id);
        $categoria = DB::table('categorias')->where('id', $archivo->clave)->first();
        $nombreCategoria = $categoria->nombre ?? ''; 
        $fileContent = base64_decode($archivo->data_archivo);
        $filename = $archivo->personal_id . '_' . str_replace(' ', '_', $nombreCategoria) . '_' . time() . '.pdf';
    
        return response($fileContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function FichaNueva(Request $request){
        $dp = Personal::where('id_personal',$request->dni)->first();
        $dcon = Vinculo::where('personal_id', $request->dni)->first(); 
        $tipo = $request->tipo;
        return view('ficha.index', compact('dp','dcon','tipo'));
    }
    //Personal

    public function buscarpersonal(Request $request){
        $p = Personal::where('id_personal',$request->dni)->first();
        return json_encode($p);
    }

    public function editarPersonal(Request $request) {

        $edp = Personal::where('id_personal', $request->dni)->first();
        $edd = Domicilio::where('personal_id', $request->dni)->first();
        $data = $request->except('id_personal');

        foreach ($data as $key => $value) {
            if (empty($value) && $edp) {
                $data[$key] = $edp->$key;
            }
        }
        if (isset($data['afiliacion_salud']) && is_array($data['afiliacion_salud'])) {
            $data['afiliacion_salud'] = json_encode($data['afiliacion_salud']);
        }

        if ($request->hasFile('archivo')) {   
            $archivo = FileHelper::updateArchivo3($request, $edp,"01");        
            $data = $request->except('archivo');
            $data['archivo'] = $archivo->id;
        } 
        //Archivos Adjuntos  
        if ($request->has('archivos')) {
            foreach ($request->file('archivos') as $id_tipo_doc => $archivo2) {
                // Verificar si el archivo ya existe y actualizarlo
                $archivoExistente = ArchivosAdjuntos::where('personal_id', $request->dni)
                                                     ->where('idtd', $id_tipo_doc)
                                                     ->first();
                if ($archivoExistente) {
                    // Actualizar el archivo existente
                    $archivo3 = FileHelper::updateArchivo2($archivo2,$edp->id_personal,"01",$archivoExistente->archivo ,$request->nro_folios ?? null);
           
                } else {
                    // Si no existe, crear un nuevo registro
                    $archivo3 = FileHelper::createArchivo2($archivo2, $request->nro_folios ?? null, $edp->id_personal, "01");
                    ArchivosAdjuntos::create([
                        'personal_id' => $edp->id_personal,
                        'idtd' => $id_tipo_doc,
                        'archivo' => $archivo3->id,
                    ]);
                }
            }
        }

        if ($edp) {
            $edp->update($data);
        } else {
            $edp = Personal::create($data);
        }
      // Verificar si hay datos para domicilio y actualizar o crear
      $domicilioData = $request->only('dactual', 'tipodom', 'numero', 'iddep', 'idpro', 'iddis');
      if (!empty(array_filter($domicilioData))) {
          if ($edd) {
              $edd->update($domicilioData);
          } else {
              Domicilio::create(array_merge($domicilioData, ['personal_id' => $request->dni]));
          }
      }
    }

    public function guardarPersonal(Request $request) {
        try {
            $data = $request->except('archivo');
        
            // Manejar archivos
            // Convertir afiliacion_salud a JSON
            if (isset($data['afiliacion_salud']) && is_array($data['afiliacion_salud'])) {
                $data['afiliacion_salud'] = json_encode($data['afiliacion_salud']);
            }
            $p = Personal::create($data);
            $archivo = FileHelper::createArchivo($request, $p->id_personal, "01");
            if ($archivo) {   
                $p->update(['archivo' => $archivo->id]);
            }
            
            //Archivos Adjuntos  
            if ($request->has('archivos')) {
                foreach ($request->file('archivos') as $id_tipo_doc => $archivo2) {
                    $archivo3 = FileHelper::createArchivo2($archivo2, $request->nro_folios ?? null, $p->id_personal, "01");
                    ArchivosAdjuntos::create([
                        'personal_id' => $p->id_personal,
                        'idtd' => $id_tipo_doc,
                        'archivo' => $archivo3->id,
                    ]);
                }
            }
            
            $personalData = [
                'id' => $p->id_personal,
                'nombre_completo' => "{$p->Apaterno} {$p->Materno} {$p->Nombres}",
            ];
         

            // Manejar datos de domicilio
            $domicilioData = $request->all();
            //$domicilioData['personal_id'] = $domicilioData['dni'];
            $domicilioData['personal_id'] = $p->id_personal;
            Domicilio::create($domicilioData);
            // Manejar datos de estudios
            return response()->json($personalData);

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'error' => 'Hubo un problema en el registro de los datos.',
                    'details' => $e->getMessage(),
                ], 400);
            }
            // Capturar otros tipos de excepciones y manejarlos de forma adecuada
            return response()->json(['error' => 'Ocurrió un error inesperado.'], 500);
        }
    }
    public function borrarPersonal(Request $request)
    {
        $edp = Personal::where('id_personal', $request->dni)->delete();
        return response()->json(['success' => 'Personal eliminado correctamente']);
    }

    
 

    ///
    //Cargos entidad
    public function getCargosE($dni){        
        $data = CargoEntidad::where('personal_id', $dni)->get(); 
        return Datatables::of($data)->make(true);
    } 
   
    public function getRotacion(){    
        $data = DB::select('
        SELECT
            con.idcl, 
            con.personal_id, 
            CONCAT(p.Apaterno," ",p.Amaterno, " ",p.Nombres) as personal, 
            CASE 
                WHEN td.id IS NOT NULL THEN CONCAT(td.nombre, " Nº ", con.ResolucionContrato)
                ELSE con.ResolucionContrato
            END AS ResolucionContrato,
            CONCAT(r.nombre) as idregimen, 
            con.FechaIngreso,
            con.FechaFin,
            con.CargoActual,
            con.Oficina,
            con.nrofolio,
            con.archivo
        FROM
            condicionlaboral AS con
        LEFT JOIN personal AS p ON con.personal_id = p.id_personal
        LEFT JOIN regimen AS r ON r.id = con.idregimen
        LEFT JOIN tipodoc AS td ON con.idtd = td.id
        ');  
        return Datatables::of($data)->make(true);
    }

    public function getSancion(){    
        $data = DB::select('
        SELECT
            san.id, 
            san.personal_id, 
            CONCAT(p.Apaterno," ",p.Amaterno, " ",p.Nombres) as personal, 
            san.nrodoc, 
            san.descripcion,
            san.desde,
            san.hasta,
            san.archivo
        FROM sanciones AS san
        LEFT JOIN personal AS p ON(san.personal_id = p.id_personal)');  
        return Datatables::of($data)->make(true);
    }

    public function getNombramiento(){    
        $data = DB::select('
        SELECT
            nom.idnom, 
            nom.personal_id, 
            CONCAT(p.Apaterno," ",p.Amaterno, " ",p.Nombres) as personal, 
            CASE 
                WHEN td.id IS NOT NULL THEN CONCAT(td.nombre, " Nº ", nom.nrodoc)
                ELSE nom.nrodoc
            END AS documento,
            CONCAT(r.nombre) as idregimen, 
            nom.descripcion,
            nom.fechaini,
            nom.fechadoc,
            nom.archivo
        FROM
            nombramiento AS nom
        LEFT JOIN personal AS p ON nom.personal_id = p.id_personal
        LEFT JOIN contrato AS c ON nom.personal_id = c.personal_id
        LEFT JOIN regimen AS r ON r.id = c.condicion
        LEFT JOIN tipodoc AS td ON nom.idtd = td.id
        '); 
        return Datatables::of($data)->make(true);
    }


    
    public function obtenerAdendas($contratoId)
    {
        $adendas = Adenda::where('id_contrato', $contratoId)->get();
        return response()->json($adendas);
    }


    //RUTAS_CHECK
    public function reportFicha($dni)
    {   
        $dep = DB::table('departamentos')->pluck('nombre', 'id');
        $pro = DB::table('provincias')->pluck('nombre', 'id');
        $dis = DB::table('distritos')->pluck('nombre', 'id');
        $reg = DB::table('regimen')->pluck('nombre', 'id');
        $dp = Personal::where('id_personal',$dni)->first();
        $dd = Domicilio::where('personal_id',$dni)->first();
        $del = Explaboral::where('personal_id', $dni)->get(); 
        $dtlel = DB::table('vinculos')
        ->leftJoin('area', 'vinculos.id_unidad_organica', '=', 'area.id')
        ->leftJoin('area as depen', 'vinculos.id_depens', '=', 'depen.id')
        ->leftJoin('cargo', 'vinculos.id_unidad_organica', '=', 'cargo.id')
        ->leftJoin('condicion_laboral', 'vinculos.id_condicion_laboral', '=', 'condicion_laboral.id')
        ->leftJoin('regimen', 'vinculos.id_regimen', '=', 'regimen.id')
        ->where('vinculos.personal_id', $dni)
        ->select('vinculos.*', 'area.nombre as id_unidad_organica', 'depen.nombre as id_depens', 'cargo.nombre as cargo', 'regimen.nombre as regimen','condicion_laboral.nombre as condicion_laboral')
        ->get();
        $exp = $dtlel->merge($del)->sortBy('fecha_ini');        
        $dft = Familiares::where('personal_id', $dni)->get(); 
        $des = Estudios::where('personal_id', $dni)->get()->sortBy('fecha_ini'); 
        $di = Idiomas::where('personal_id', $dni)->get();
        $archivo = Archivo::where('id', $dp->archivo)->first();
        if ($archivo) {
            $fileContent = $archivo->data_archivo;
            //$base64Image = 'data:image/' . $archivo->extension . ';base64,' . base64_encode($fileContent);
            $base64Image = 'data:image/' . $archivo->extension . ';base64,' . $fileContent;
        } else {
            $base64Image = '';
        }        
        $pdf = PDF::loadView('datosLegajo.ficha',compact('dp','exp','dd','dft','des','di','dep','pro','dis','reg','base64Image','dtlel'));
        return $pdf->stream('FICHA_'.$dni.'.pdf');
    }

    //EVALUA
    public function informe(Request $request) {
        $dniArray = $request->input('personal');
        $camposSeleccionados = $request->input('campos', []);
        $adjuntarArchivos = $request->input('adjuntarArchivos');
        $years = $request->input('years', '');
        $archivoIds = collect();
        $informes = [];
        $dep = DB::table('departamentos')->get();
        $pro = DB::table('provincias')->get();
        $dis = DB::table('distritos')->get(); 
        $tconlab = DB::table('condicion_laboral')
        ->select('id', 'descripcion_regimen','nombre')
        ->get()
        ->keyBy('id');
        $tarea = DB::table('area')->pluck('nombre', 'id');
        $tcargo = DB::table('cargo')->pluck('nombre', 'id');
        $tireg = DB::table('regimen')->pluck('nombre', 'id');
        $tdoc = DB::table('tipodoc')->pluck('nombre', 'id');
        $tcomp = DB::table('tipo_compensacion')->pluck('nombre', 'id');
        foreach ($dniArray as $dni) {
        $dp = Personal::where('id_personal', $dni)->first();
        $dd = Domicilio::where('personal_id',$dni)->first();
        $del = Explaboral::where('personal_id', $dni)->get(); 
        $df = Familiares::where('personal_id', $dni)->first(); 
        $dft = Familiares::where('personal_id', $dni)->get(); 
       /* $dtl = DB::table('vinculos')
        ->leftJoin('area', 'vinculos.id_unidad_organica', '=', 'area.id')
        ->leftJoin('area as depen', 'vinculos.id_depens', '=', 'depen.id')
        ->leftJoin('cargo', 'vinculos.id_unidad_organica', '=', 'cargo.id')
        ->where('vinculos.personal_id', $dni)
        ->select('vinculos.*', 'area.nombre as id_unidad_organica', 'depen.nombre as id_depens', 'cargo.nombre as id_unidad_organica')
        ->orderBy('fecha_ini', 'asc')
        ->get();*/
        $dtl = DB::table('vinculos')
        ->leftJoin('area', 'vinculos.id_unidad_organica', '=', 'area.id')
        ->leftJoin('area as depen', 'vinculos.id_depens', '=', 'depen.id')
        ->leftJoin('cargo', 'vinculos.id_unidad_organica', '=', 'cargo.id')
        ->where('vinculos.personal_id', $dni)
        ->select(
            'vinculos.id',
            'vinculos.fecha_ini',
            'vinculos.fecha_fin',
            'vinculos.id_regimen',
            'vinculos.id_tipo_documento',
            'vinculos.id_condicion_laboral',
            'vinculos.archivo',
            'vinculos.archivo_cese',
            'area.nombre as id_unidad_organica',
            'depen.nombre as id_depens',
            'cargo.nombre as id_cargo',
        )
        ->union(
            DB::table('adendas')
            ->leftJoin('vinculos', 'adendas.id_vinculo', '=', 'vinculos.id')
            ->leftJoin('area', 'vinculos.id_unidad_organica', '=', 'area.id')
            ->leftJoin('area as depen', 'vinculos.id_depens', '=', 'depen.id')
            ->leftJoin('cargo', 'vinculos.id_unidad_organica', '=', 'cargo.id')
            ->where('vinculos.personal_id', $dni)  
            ->select(
                'adendas.id_vinculo as id',
                'adendas.fecha_ini',
                'adendas.fecha_fin',
                'vinculos.id_regimen',
                'adendas.idtd as id_tipo_documento',
                'vinculos.id_condicion_laboral',
                'adendas.archivo',
                'vinculos.archivo_cese',
                'area.nombre as id_unidad_organica',
                'depen.nombre as id_depens',
                'cargo.nombre as id_cargo',
            )
        )
        ->orderBy('fecha_ini', 'asc')
        ->get();
    
        $dtser = TiempoServicio::where('personal_id', $dni)->orderBy('fecha_ini', 'desc')->get();
        $ddesplz = Movimientos::where('personal_id', $dni)->orderBy('fecha_ini', 'asc')->get();
       
        $exp = $dtl->merge($del)->sortByDesc('fecha_ini');

        $fechaActual = Carbon::now();
        //  Vinculo Laboral
        $dconi = Vinculo::where('personal_id', $dni)->whereNotNull('fecha_ini')->orderBy('fecha_ini', 'asc')->first();
        $dvin_ult = Vinculo::where('personal_id', $dni)->whereNotNull('fecha_ini')->orderBy('fecha_ini', 'desc')->first();

        $estado = null;
        $fechacese = null;
        $fechaActual = Carbon::now();
        
        if ($dtl->isEmpty()) {
            $estado = 'SIN VINCULO LABORAL';
        } else {
            foreach ($dtl as $contrato) {
                $fechaFin = $contrato->fecha_fin ? Carbon::parse($contrato->fecha_fin) : null;
                $fechaIni = Carbon::parse($contrato->fecha_ini);
                // Caso donde fecha fin es null
                if (is_null($fechaFin)) {
                    $estado = 'VINCULO LABORAL VIGENTE';
                    break;
                } else {
                    // Evaluación de fecha fin no nula
                    if ($fechaFin >= $fechaActual) {
                        $estado = 'VINCULO LABORAL VIGENTE';
                        break; // Si encontramos un contrato con fecha fin mayor a la actual, podemos detener la búsqueda
                    } elseif ($fechaFin < $fechaActual) {
                        $estado = 'SIN VINCULO LABORAL';
                        $fechacese = $fechaFin; // Guardar la fecha de cese del último contrato finalizado
                    }
                }
            }
        }
        $dat = TiempoServicio::where('personal_id', $dni)->get(); 
        $des = Estudios::where('personal_id', $dni)->get(); 
        $desex = EstudiosEsp::where('personal_id', $dni)->get(); 
        $drec = Reconocimientos::where('personal_id', $dni)->get(); 
        $dsan = Sancion::where('personal_id', $dni)->orderBy('fechadoc', 'desc')->get(); 
        $dvac = Vacaciones::where('personal_id', $dni)->orderBy('fecha_ini', 'desc')->get(); 
        $dli = Licencias::where('personal_id', $dni)->where('congoce', 'NO')->orderBy('fecha_ini', 'desc')->get(); 
        $dper = Permisos::where('personal_id', $dni)->orderBy('fecha_ini', 'desc')->get(); 
        $dcom = Compensaciones::where('personal_id', $dni)->orderBy('fecha_documento', 'desc')->get(); 
        $di = Idiomas::where('personal_id', $dni)->get();
        ///DIAS ADEUDADOS
        // Obtener los registros necesarios
        $dosAniosAtras = Carbon::now()->subYears(2)->year;
        $dcron = CronogramaVacaciones::where('personal_id', $dni)->where('periodo', '<=', $dosAniosAtras)->orderBy('fecha_ini', 'desc')->get();
        $d_adeuda = collect(); // Inicializar la colección para almacenar los días adeudados
        
        $periodosProcesados = []; // Array para mantener el registro de periodos procesados
        //EVALUAR
        foreach ($dcron as $cronograma) {
            $periodo = $cronograma->periodo;
        
            if (in_array($periodo, $periodosProcesados)) {
                continue; // Si el periodo ya ha sido procesado, saltar al siguiente cronograma
            }
        
            // Filtrar vacaciones, licencias y permisos por periodo
            $vacaciones = $dvac->where('periodo', $periodo);
            $licencias = $dli->where('periodo', $periodo);
            $permisos = $dper->where('periodo', $periodo);
        
            // Calcular los días totales utilizados
            $diasVacaciones = $vacaciones->sum('dias');
            $diasLicencias = $licencias->sum(function ($licencia) {
                return $licencia->acuentavac == 'SI' ? $licencia->dias : 0;
            });
            $diasPermisos = $permisos->sum(function ($permiso) {
                return $permiso->acuentavac == 'SI' ? $permiso->dias : 0;
            });
        
            $diasTotalesUtilizados = $diasVacaciones + $diasLicencias + $diasPermisos;
            $diasRestantes = 30 - $diasTotalesUtilizados;
        
            // Almacenar los días adeudados en la colección
            $d_adeuda->push((object)[
                'periodo' => $periodo,
                'dias_a' => $diasRestantes
            ]);
        
            $periodosProcesados[] = $periodo; // Marcar el periodo como procesado
        }
        
        //EVALUAR
        /// PROCESAR ARCHIVOS
        $collectionsMap = [
            '4' => $dtl,
            '6' => $des,
            '7' => $desex,
            '5' => $del,
            '15' => $ddesplz,
            '17' => $dcron,
            '11' => $dvac,
            '12' => $dli,
            '13' => $dper,
            '10' => $dcom,
            '16' => $dat,
            '8' => $drec,
            '9' => $dsan,
        ];
            foreach ($collectionsMap as $campo => $collection) {
                if (in_array($campo, $camposSeleccionados)) {
                    $archivoIds = $archivoIds->merge(
                        $collection->flatMap(function ($item) {
                            return [$item->archivo, $item->archivo_cese];
                        })
                    );            
                }
            }
            $archivoIds = $archivoIds->filter()->unique(); // Eliminar IDs duplicados
            $files = []; 
            foreach ($collectionsMap as $campo => $collection) {
                if (in_array($campo, $camposSeleccionados)) {
                    foreach ($collection as $item) {
                        $nroDoc = $item->nro_doc ?? $item->nro_documento ?? $item->nrodoc ?? '';
                        $idtd = $item->id_tipo_documento ?? $item->idtd ?? $item->id_tipo_documento_fin ?? null;
                        $tipoDocNombre = $tdoc[$idtd] ?? '';
            
                        // Si el archivo tiene un ID, lo agregamos a la lista de nombres
                        if ($item->archivo) {
                            $files[] = ['name' => "{$tipoDocNombre} {$nroDoc}"];
                        }
                        if ($item->archivo_cese) {
                            $files[] = ['name' => "{$tipoDocNombre} {$nroDoc}"];
                        }
                    }
                }
            }
            $informes[] = [
                'archivoIds' => $files,
                'd_adeuda' => $d_adeuda,
                'dat' => $dat,
                'dp' => $dp,
                'exp' => $exp,
                'dd' => $dd,
                'ddesplz' => $ddesplz,
                'dft' => $dft,
                'dtser' => $dtser,
                'dconi' => $dconi,
                'dvin_ult' => $dvin_ult,
                'des' => $des,
                'di' => $di,
                'desex' => $desex,
                'drec' => $drec,
                'dvac' => $dvac,
                'dper' => $dper,
                'dcom' => $dcom,
                'dsan' => $dsan,
                'dtl' => $dtl,
                'dli' => $dli,
                'estado' => $estado,
                'fechacese' => $fechacese,
            ];
        }
        $pdf = PDF::loadView('Reportes.informeM', [
            'tcomp' => $tcomp,  
            'tarea' => $tarea,  
            'tcargo' => $tcargo,  
            'tconlab' => $tconlab,  
            'informes' => $informes,
            'tireg' => $tireg,
            'dtl' => $dtl,  
            'tdoc' => $tdoc,  
            'camposSeleccionados' => $camposSeleccionados,
            'years' => $years,
        ]);

        $pdfDomContent = $pdf->output(); 
        // Importar el PDF generado por DomPDF
        $pdfDomStream = StreamReader::createByString($pdfDomContent);
        $pdf = new Fpdi();

        $pageCountDom = $pdf->setSourceFile($pdfDomStream);
        for ($pageNo = 1; $pageNo <= $pageCountDom; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);
        }
        ini_set('memory_limit', '1G'); 
        if ($adjuntarArchivos==1) {
            // Anexar archivos PDF adicionales directamente desde base64
            foreach ($archivoIds as $archivoId) {
                $archivo = DB::table('archivo')->where('id', $archivoId)->first();
                if ($archivo) {
                    $pdfContent = base64_decode($archivo->data_archivo);
                    $pdfStream = StreamReader::createByString($pdfContent);
                    $pageCount = $pdf->setSourceFile($pdfStream);
                    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                        $templateId = $pdf->importPage($pageNo);
                        $size = $pdf->getTemplateSize($templateId);
                        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                        $pdf->useTemplate($templateId);
                    }
                }
            }
        }
    
        // Generar el PDF final en memoria
        $pdfContent = $pdf->Output('reporte_finals.pdf', 'S');
    
        // Retornar el PDF final para descargar
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="reporte_finals.pdf"');
    }

    public function descargarTodo($dni)
    {
        $dp = Personal::where('id_personal',$dni)->first();
        $archivoIds = collect();
        // Lista de tablas a consultar
        $tables = [
            'archivos_adjuntos',
            'familiares',
            'vinculos',
            'estudios',
            'estudios_especializacion',
            'colegiatura',
            'idiomas',
            'explaboral',
            'movimientos',
            'vacaciones',
            'licencias',
            'permisos',
            'compensaciones',
            'tiempo_servicio',
            'reconocimientos',
            'sanciones',
        ];
    
        // Recorrer todas las tablas y recoger los archivo_ids
        foreach ($tables as $table) {
            $collection = DB::table($table)->where('personal_id', $dni)->get();
            $archivoIds = $archivoIds->merge(
                $collection->flatMap(function ($item) {
                    $archivos = [];
                    if (isset($item->archivo)) {
                        $archivos[] = $item->archivo;
                    }
                    if (isset($item->archivo_cese)) {
                        $archivos[] = $item->archivo_cese;
                    }
                    if (isset($item->archivo_vinculo)) {
                        $archivos[] = $item->archivo_vinculo;
                    }
                    return $archivos;
                })
            );
        }
    
        $archivoIds = $archivoIds->filter()->unique(); // Eliminar IDs duplicados
    
        // Generar el PDF combinando los archivos
        $pdf = new Fpdi();
        ini_set('memory_limit', '1G');
    
        foreach ($archivoIds as $archivoId) {
            $archivo = DB::table('archivo')->where('id', $archivoId)->first();
            if ($archivo) {
                $pdfContent = base64_decode($archivo->data_archivo);
                $pdfStream = StreamReader::createByString($pdfContent);
                $pageCount = $pdf->setSourceFile($pdfStream);
                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    $templateId = $pdf->importPage($pageNo);
                    $size = $pdf->getTemplateSize($templateId);
                    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $pdf->useTemplate($templateId);
                }
            }
        }
        $fileName = $dp->Apaterno . '_' . $dp->Amaterno . '_' . $dp->Nombres . '_LEGAJO.pdf';
        // Generar el PDF final en memoria y retornar la respuesta
        $pdfContent = $pdf->Output($fileName, 'S');
        return response($pdfContent) ->header('Content-Type', 'application/pdf') ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

}





