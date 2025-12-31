<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Compensaciones;
use Illuminate\Support\Facades\Storage; 
use Yajra\Datatables\Datatables;
use App\Models\Archivo;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Schema;
use DB;

class CompensacionesController extends Controller
{

    public function index(Request $request) {
        $columns = Schema::getColumnListing('compensaciones');
        $columns = array_diff($columns, ['created_at','updated_at']);
            $prefixedColumns = array_map(function ($column) {
            return "compensaciones.$column";
        }, $columns);

        $df = DB::table('compensaciones')
            ->leftJoin('tipodoc', 'compensaciones.idtd', '=', 'tipodoc.id')
            ->leftJoin('tipo_compensacion', 'compensaciones.tipo_compensacion', '=', 'tipo_compensacion.id')
            ->leftJoin('archivo', 'compensaciones.archivo', '=', 'archivo.id')
            ->leftJoin('personal', 'compensaciones.personal_id', '=', 'personal.id_personal')
            ->select(
                array_merge($prefixedColumns, [ 
                    DB::raw("tipodoc.nombre as tdoc"),
                    DB::raw("tipo_compensacion.nombre as tipo_compensacion"),
                    
                    DB::raw('CONCAT(personal.Apaterno, \' \', personal.Amaterno, \' \', personal.Nombres) AS nombre_completo'),
                    DB::raw('ISNULL(archivo.id, NULL) as archivo'), 
                    DB::raw('ISNULL(archivo.nro_folio, NULL)as nro_folio') 
                ])
            );
            if ($request->has('filter_field') && $request->has('filter_value')) {
                $filterField = $request->input('filter_field');
                $filterValue = $request->input('filter_value');
                if (!empty($filterValue) && in_array($filterField, $columns)) {
                    $df->where("compensaciones.$filterField", $filterValue);
                }
             
            }
            $df = $df->get();
        return Datatables::of($df)->make(true);
    }


    public function show($id)
    {
        $c = Compensaciones::find($id);
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
        $archivo = FileHelper::createArchivo($request, $request->personal_id, "08");
        if ($archivo) {
            $data = $request->except('archivo');
            $data['archivo'] = $archivo->id; 
            $estudio = Compensaciones::create($data);
            return response()->json($estudio->toArray());
        } else {
            $data = $request->except('archivo');
            Compensaciones::create($data);
        }

    }
    public function update($id, Request $request)
    {
        $t = Compensaciones::find($id);
        $data = $request->except('archivo');
        if ($t) {
            $archivo = FileHelper::updateArchivo($request, $t,"08");        
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
        $t = Compensaciones::find($id);
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
