<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Idiomas;
use Illuminate\Support\Facades\Storage; 
use Yajra\Datatables\Datatables;
use App\Helpers\FileHelper;
use App\Models\Archivo;
use Illuminate\Support\Facades\Schema;

use DB;

class IdiomaController extends Controller
{
    public function index(Request $request) {
        $columns = Schema::getColumnListing('idiomas');
        $columns = array_diff($columns, ['created_at','updated_at']);
            $prefixedColumns = array_map(function ($column) {
            return "idiomas.$column";
        }, $columns);

        $df = DB::table('idiomas')
            ->leftJoin('archivo', 'idiomas.archivo', '=', 'archivo.id')
            ->leftJoin('personal', 'idiomas.personal_id', '=', 'personal.id_personal')
            
            
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
                    $df->where("idiomas.$filterField", $filterValue);
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
        $archivo = FileHelper::createArchivo($request, $request->personal_id, "01");
        if ($request->hasFile('archivo')) {
            $data = $request->except('archivo');
            $data['archivo'] = $archivo->id; 
            $s = Idiomas::create($data);
            return response()->json($s->toArray());
        } else {
            $archivoPath = "";
            $data = $request->except('archivo');
            $data['archivo'] = $archivoPath;
            Idiomas::create($data);
        }

    }
    
    public function update($id, Request $request)
    {
        $t = Idiomas::find($id);
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
        $t = Idiomas::find($id);
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
       $t = Idiomas::find($id);
       if ($t) {
           return response()->json($t);
       } else {
           return response()->json(['error' => 'Campo no encontrado'], 404);
       }
   }

   
}