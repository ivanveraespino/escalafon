<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Adenda;
use Illuminate\Support\Facades\Storage; 
use Yajra\Datatables\Datatables;
use App\Helpers\FileHelper;
use App\Models\Archivo;
use Illuminate\Support\Facades\Schema;

use DB;

class AdendaController extends Controller
{

    public function index(Request $request) {
        $columns = Schema::getColumnListing('adendas');
        $columns = array_diff($columns, ['created_at', 'updated_at']);
        $prefixedColumns = array_map(function ($column) {
            return "adendas.$column";
        }, $columns);
    
        // Construcción de la consulta base
        $query = DB::table('adendas')
            ->leftJoin('archivo', 'adendas.archivo', '=', 'archivo.id')
            ->select(
                array_merge($prefixedColumns, [ 
                    DB::raw('ISNULL(archivo.id, NULL) as archivo'), 
                    DB::raw('ISNULL(archivo.nro_folio, NULL) as nro_folio') 
                ])
            );
    
        if ($request->has('filter_field') && $request->has('filter_value')) {
            $filterField = $request->input('filter_field');
            $filterValue = $request->input('filter_value');
    
            // Validación del campo para evitar SQL Injection
            if (in_array($filterField, $columns)) {
                $query->where("adendas.$filterField", $filterValue);
            }
        }
        $df = $query->get();
    
        return Datatables::of($df)->make(true);
    }
    

    public static function store(Request $request)
    {
  
        $archivo = FileHelper::createArchivo($request, $request->personal_id, "02");
        if ($request->hasFile('archivo')) {
            $data = $request->except('archivo');
            $data['archivo'] = $archivo->id; 
            $s = Adenda::create($data);
            return response()->json($s->toArray());
        } else {
            $archivoPath = "";
            $data = $request->except('archivo');
            $data['archivo'] = $archivoPath;
            Adenda::create($data);
        }

    }
    
    public function update($id, Request $request)
    {
        $t = Adenda::find($id);
        if ($t) {
            if ($request->hasFile('archivo')) {   
                $archivo = FileHelper::updateArchivo($request, $t,"01");        
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
        $t = Adenda::find($id);
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

   public function show($id)
   {
       $t = Adenda::find($id);
       if ($t) {
           return response()->json($t);
       } else {
           return response()->json(['error' => 'Campo no encontrado'], 404);
       }
   }

   
}