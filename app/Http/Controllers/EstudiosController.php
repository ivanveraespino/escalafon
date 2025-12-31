<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estudios;
use Illuminate\Support\Facades\Storage; 
use Yajra\Datatables\Datatables;
use App\Helpers\FileHelper;
use App\Models\Archivo;
use Illuminate\Support\Facades\Schema;

use DB;

class EstudiosController extends Controller
{

    //Mostar un solo estudio
    public function show($id)
    {
        $t = Estudios::find($id);
        if ($t) {
            return response()->json($t);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }

    //CAMBIAR
    public function update($id, Request $request)
    {
        $t = Estudios::find($id);
        if ($t) {
            $data = $request->except('archivo');
            $archivo = FileHelper::updateArchivo($request, $t,"03");        
            if ($archivo !== null) {
                $data['archivo'] = $archivo->id;
            }

            $t->update($data);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }

    }

    public static function store(Request $request)
    {
        $request->validate([
            'personal_id' => 'required',
        ]);
        $archivo = FileHelper::createArchivo($request, $request->personal_id, "03");
        
        if ($request->hasFile('archivo')) {
            $data = $request->except('archivo');
            $data['archivo'] = $archivo->id; 
            $s= Estudios::create($data);
            return response()->json($s->toArray());

        } else {
            $data = $request->except('archivo');
            Estudios::create($data);
        }

    }

    public function destroy($id)
    {
        $t = Estudios::find($id);
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

   public function index(Request $request) {
    $columns = Schema::getColumnListing('estudios');
    $columns = array_diff($columns, ['created_at','updated_at']);
        $prefixedColumns = array_map(function ($column) {
        return "estudios.$column";
    }, $columns);

    $df = DB::table('estudios')
        ->leftJoin('archivo', 'estudios.archivo', '=', 'archivo.id')
        ->leftJoin('personal', 'estudios.personal_id', '=', 'personal.id_personal')
        
        
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
                $df->where("estudios.$filterField", $filterValue);
            }
        }
        $df = $df->get();
    return Datatables::of($df)->make(true);
    }
}