<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;
use App\Models\Archivo;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Schema;

use DB;

class ArchivosController extends Controller
{

    public function index()
    {
        return view('Archivos.index');
    }
    public function general()
    {
        return view('Archivos.general');
    }

    public function MostrarArchivos(Request $request) {
        $arch = DB::table('archivo')
            ->leftJoin('categorias', 'archivo.clave', '=', 'categorias.clave')
            ->where('archivo.personal_id', $request->id)
            ->select(
                'archivo.id as archivo',
                'archivo.peso',
                'archivo.nombre',
                'archivo.nro_folio',
                'archivo.created_at',
                'categorias.nombre as categorias'
            )->get();
                    
        return datatables()->of($arch)->make(true);
    }
    

    public static function guardar_archivos(Request $request) {
        $request->validate([
            'personal_id' => 'required',
        ]);
        try {
            $archivo = FileHelper::createArchivo($request, $request->personal_id, $request->clave);

        } catch (Exception $e) {
            Log::error('Error en el proceso de guardar: ' . $e->getMessage());
            return response()->json(['error' => 'Error en el proceso de guardar: ' . $e->getMessage()], 500);
        }
    }
   
    
    public function borrar_archivo($id)
    {
        $t = Archivo::where('id', $id);
        if ($t) {
            $t->delete();
            return response()->json(['success' => 'Eliminado correctamente']);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }
    
    public function MostrarCategorias(Request $request){
        $df = DB::table('categorias')
            ->select(
                'categorias.*',
            )
            ->get();        
            return response()->json($df);
        }

    public function getFolioPersonal(Request $request) {
        $data = DB::select("
            SELECT 
                p.nro_documento_id,
                r.nombre AS idregimen,
                rm.nombre AS idregimenm,
                (p.Apaterno + ' ' + p.Amaterno + ' ' + p.Nombres) AS personal,
                SUM(a.nro_folio) AS total_folios
            FROM personal p
            LEFT JOIN (
                SELECT 
                    vin.personal_id,
                    vin.id_regimen,
                    vin.id_condicion_laboral,
                    vin.fecha_ini
                FROM vinculos vin
                INNER JOIN (
                    SELECT 
                        personal_id,
                        MAX(fecha_ini) AS fecha_ini_reciente
                    FROM vinculos
                    GROUP BY personal_id
                ) v_max ON vin.personal_id = v_max.personal_id AND vin.fecha_ini = v_max.fecha_ini_reciente
            ) vin_reciente ON p.id_personal = vin_reciente.personal_id
            LEFT JOIN regimen r ON r.id = vin_reciente.id_regimen
            LEFT JOIN condicion_laboral rm ON rm.id = vin_reciente.id_condicion_laboral
            LEFT JOIN archivo a ON p.id_personal = a.personal_id
            GROUP BY 
                p.nro_documento_id, 
                r.nombre, 
                rm.nombre, 
                p.Apaterno, 
                p.Amaterno, 
                p.Nombres
        ");
    
        return Datatables::of($data)->make(true);
    }
    public function personal(Request $request) {
        $columns = Schema::getColumnListing('personal');
        $columns = array_diff($columns, ['created_at', 'updated_at']);
    
        $prefixedColumns = array_map(function ($column) {
            return "personal.$column";
        }, $columns);
    
        $latestVinculos = DB::table('vinculos as v1')
            ->select('v1.personal_id', 'v1.id_regimen', 'v1.id_condicion_laboral', 'v1.fecha_ini')
            ->whereRaw('v1.fecha_ini = (SELECT MAX(v2.fecha_ini) FROM vinculos v2 WHERE v1.personal_id = v2.personal_id)');
    
        $df = DB::table('personal')
            ->leftJoinSub($latestVinculos, 'v', function ($join) {
                $join->on('personal.id_personal', '=', 'v.personal_id');
            })
            ->leftJoin('regimen as r', 'v.id_regimen', '=', 'r.id')
            ->leftJoin('condicion_laboral as cl', 'v.id_condicion_laboral', '=', 'cl.id')
            ->leftJoin('archivo as a', 'personal.id_personal', '=', 'a.personal_id')
            ->select(
                DB::raw("tipodoc.nombre as tdoc"),
                DB::raw('CONCAT(personal.Apaterno, \' \', personal.Amaterno, \' \', personal.Nombres) AS personal'),
                DB::raw("CONCAT(tipodoc.nombre, COALESCE(CONCAT(' N° ', vacaciones.nrodoc), '')) AS nrodoc"),
                DB::raw('ISNULL(archivo.id, NULL) as archivo'),
                DB::raw('ISNULL(archivo.nro_folio, NULL) as nro_folio'),
                DB::raw('CONCAT(COALESCE(r.nombre, \'\'), \' \', COALESCE(cl.nombre, \'\')) AS regimen_condicion'),
            );
    
        $df = $df->get();
    
        return Datatables::of($df)->make(true);
    }

}