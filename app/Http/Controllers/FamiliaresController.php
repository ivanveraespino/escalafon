<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Familiares;
use Illuminate\Support\Facades\Storage; 
use Yajra\Datatables\Datatables;
use App\Models\Archivo;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Schema;
use DB;

class FamiliaresController extends Controller
{
    public function index(Request $request)
    {
        $columns = Schema::getColumnListing('familiares');
        $columns = array_diff($columns, ['created_at', 'updated_at']);
            $prefixedColumns = array_map(function ($column) {
            return "familiares.$column";
        }, $columns);
        $df = DB::table('familiares')
            ->leftJoin('personal', 'familiares.personal_id', '=', 'personal.id_personal')
            ->select(
                array_merge($prefixedColumns, [ 
                    
                    DB::raw('CONCAT(personal.Apaterno, \' \', personal.Amaterno, \' \', personal.Nombres) AS nombre_completo')
                ])
            );
        if ($request->has('filter_field') && $request->has('filter_value')) {
            $filterField = $request->input('filter_field');
            $filterValue = $request->input('filter_value');
            if (!empty($filterValue) && in_array($filterField, $columns)) {
                $df->where("familiares.$filterField", $filterValue);
            }
        }
        $df = $df->get();
        return Datatables::of($df)->make(true);
    }

    public function store(Request $request){
        $request->validate([
            'personal_id' => 'required',
        ]);
    
        $data = $request->except('archivo');
        $archivo = FileHelper::createArchivo2($request->file('archivo'), $request->nro_folios ?? null, $request->personal_id, "01");
        if ($archivo) {
            $data['archivo'] = $archivo->id;
        }
        $archivo2 = FileHelper::createArchivo2($request->file('archivo_vinculo'), $request->nro_folios2 ?? null, $request->personal_id, "01");
        if ($archivo2) {
            $data['archivo_vinculo'] = $archivo2->id;
        }
        $estudio = Familiares::create($data);
    
    }
    public function show($id)
    {
        $t = Familiares::find($id);
        if ($t) {
            return response()->json($t);
        } else {
            return response()->json(['error' => 'Familiar no encontrado'], 404);
        }
    }

    //CAMBIAR
    public function update($id, Request $request)
    {
        $t = Familiares::find($id);
        if ($t) {
            $data = $request->except('archivo');
            $archivo = FileHelper::updateArchivo2($request->file('archivo'),$t->personal_id, "01", $t->archivo,$request->nro_folios ?? null);
            if ($archivo !== null) {
                $data['archivo'] = $archivo->id;
            }
            $archivo2 = FileHelper::updateArchivo2($request->file('archivo_vinculo'),$t->personal_id, "01", $t->archivo_vinculo,$request->nro_folios2 ?? null);
            if ($archivo2 !== null) {
                $data['archivo_vinculo'] = $archivo2->id;
            }
            $t->update($data);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
     
    }

    public function destroy($id)
    {
        $t = Familiares::find($id);
        if ($t) {
            if ($t->archivo) {
                Archivo::where('id', $t->archivo)->delete();
            }
            if ($t->archivo_vinculo) {
                Archivo::where('id', $t->archivo_vinculo)->delete();
            }
            $t->delete();
            return response()->json(['success' => 'Eliminado correctamente']);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }
    
    
}



