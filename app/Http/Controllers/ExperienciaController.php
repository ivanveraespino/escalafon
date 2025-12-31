<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Explaboral;
use Illuminate\Support\Facades\Storage; 
use Yajra\Datatables\Datatables;
use App\Models\Archivo;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Schema;
use DB;

class ExperienciaController extends Controller
{

    public function index(Request $request)
    {
        $columns = Schema::getColumnListing('explaboral');
        $columns = array_diff($columns, ['created_at', 'updated_at']);
            $prefixedColumns = array_map(function ($column) {
            return "explaboral.$column";
        }, $columns);
    
        $df = DB::table('explaboral')
            ->leftJoin('personal', 'explaboral.personal_id', '=', 'personal.id_personal')
            
            ->leftJoin('archivo', 'explaboral.archivo', '=', 'archivo.id')
            
            ->select(
                array_merge($prefixedColumns, [ 
                    
                    DB::raw('CONCAT(personal.Apaterno, \' \', personal.Amaterno, \' \', personal.Nombres) AS nombre_completo'),
                    DB::raw('ISNULL(archivo.id, NULL) as archivo'), 
                    DB::raw('ISNULL(archivo.nro_folio, NULL)as nro_folio') 
                ])
            );
            if ($request->has('filter_field') && $request->has('filter_value')) {
                $filterField = $request->input('filter_field');
                $filterValue = $request->input('filter_value');

                if (!empty($filterValue) && in_array($filterField, $columns)) {
                    $df->where("explaboral.$filterField", $filterValue);
                }
            }
            $df = $df->get();
    
        return Datatables::of($df)->make(true);
    }
    
    public static function store(Request $request)
    {
        $request->validate([
            'personal_id' => 'required',
        ]);
        $archivo = FileHelper::createArchivo($request, $request->personal_id, "04");
        if ($archivo) {
            $data = $request->except('archivo');
            $data['archivo'] = $archivo->id; 
            $estudio = Explaboral::create($data);
            return response()->json($estudio->toArray());
        } else {
            $data = $request->except('archivo');
            Explaboral::create($data);
        }
    }

    
    public function update($id, Request $request)
    {

        $t = Explaboral::find($id);
        if ($t) {
            if ($request->hasFile('archivo')) {   
                $archivo = FileHelper::updateArchivo($request, $t,"04");        
                $data = $request->except('archivo');
                $data['archivo'] = $archivo->id;
                $t->update($data);
            } else {
                $data = $request->except('archivo');
                $t->update($data);
            }
            return response()->json($t->toArray());
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }
    
    public function destroy($id)
    {
        $t = Explaboral::find($id);
        if ($t) {
            if ($t->archivo) {
                Archivo::where('id', $t->archivo)->delete();
            }
            $t->delete();
            return response()->json(['success' => 'Campo eliminado correctamente']);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }

    public function show($id)
    {
        $t = Explaboral::find($id);
        if ($t) {
            return response()->json($t);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }
}
