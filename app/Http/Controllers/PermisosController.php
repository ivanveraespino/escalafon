<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permisos;
use Illuminate\Support\Facades\Storage; 
use Yajra\Datatables\Datatables;
use App\Models\Archivo;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Schema;
use DB;

class PermisosController extends Controller
{
    public function index(Request $request) {
        $columns = Schema::getColumnListing('permisos');
        $columns = array_diff($columns, ['created_at', 'updated_at']);
    
        $prefixedColumns = array_map(function ($column) {
            return "permisos.$column";
        }, $columns);
    
        $latestVinculos = DB::table('vinculos as v1')
            ->select('v1.personal_id', 'v1.id_regimen', 'v1.id_condicion_laboral', 'v1.fecha_ini')
            ->whereRaw('v1.fecha_ini = (SELECT MAX(v2.fecha_ini) FROM vinculos v2 WHERE v1.personal_id = v2.personal_id)');
    
        $df = DB::table('permisos')
            ->leftJoin('tipodoc', 'permisos.idtd', '=', 'tipodoc.id')
            ->leftJoin('archivo', 'permisos.archivo', '=', 'archivo.id')
            ->leftJoin('personal', 'permisos.personal_id', '=', 'personal.id_personal')
            ->leftJoinSub($latestVinculos, 'v', function ($join) {
                $join->on('permisos.personal_id', '=', 'v.personal_id');
            })
            ->leftJoin('regimen as r', 'v.id_regimen', '=', 'r.id')
            ->leftJoin('condicion_laboral as cl', 'v.id_condicion_laboral', '=', 'cl.id')
            ->select(
                array_merge($prefixedColumns, [
                    DB::raw("tipodoc.nombre as tdoc"),
                    DB::raw('personal.nro_documento_id'),
                    DB::raw('CONCAT(personal.Apaterno, \' \', personal.Amaterno, \' \', personal.Nombres) AS personal'),
                    DB::raw("CONCAT(tipodoc.nombre, COALESCE(CONCAT(' N° ', permisos.nrodoc), '')) AS nrodoc"),
                    DB::raw('ISNULL(archivo.id, NULL) as archivo'),
                    DB::raw('ISNULL(archivo.nro_folio, NULL) as nro_folio'),
                    DB::raw('CONCAT(COALESCE(r.nombre, \'\'), \' \', COALESCE(cl.nombre, \'\')) AS regimen_condicion'),
                ])
            );
    
            if ($request->has('filter_field') && $request->has('filter_value')) {
                $filterField = $request->input('filter_field');
                $filterValue = $request->input('filter_value');
                if (!empty($filterValue) && in_array($filterField, $columns)) {
                    $df->where("permisos.$filterField", $filterValue);
                }
            }
            $df = $df->get();  

        return Datatables::of($df)->make(true);
    }




    public function show($id)
    {
        $c = Permisos::find($id);
        if ($c) {
            return response()->json($c);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }


    public static function store(Request $request)
    {
        $request->validate([
            'personal_id' => 'required',
        ]);
        $archivo = FileHelper::createArchivo($request, $request->personal_id, "07");
        if ($archivo) {
            $data = $request->except('archivo');
            $data['archivo'] = $archivo->id; 
            $p = Permisos::create($data);
            return response()->json($p->toArray());
        } else {
            $data = $request->except('archivo');
            Permisos::create($data);
        }

    }

    public function update($id, Request $request)
    {

        $t = Permisos::find($id);
        $data = $request->except('personal_id');
        if ($t) {
            $archivo = FileHelper::updateArchivo($request, $t,"07");        
            if ($archivo !== null) {
                $data['archivo'] = $archivo->id; 
            }
            $t->update($data);
            return response()->json($t->toArray());
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }
    
    public function destroy($id)
    {
        $t = Permisos::find($id);
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

}
