<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\Datatables\Datatables;
use App\Models\Movimientos;
use App\Models\Archivo;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Schema;

use DB;

class MovimientosController extends Controller
{
    public function index(Request $request)
    {        
        $columns = Schema::getColumnListing('movimientos');
        $columns = array_diff($columns, ['created_at', 'updated_at']);
            $prefixedColumns = array_map(function ($column) {
            return "movimientos.$column";
        }, $columns);
    
        $df = DB::table('movimientos')
            ->leftJoin('tipodoc', 'movimientos.idtd', '=', 'tipodoc.id')
            ->leftJoin('archivo', 'movimientos.archivo', '=', 'archivo.id')
            ->leftJoin('area as unidad_organica', 'movimientos.unidad_organica', '=', 'unidad_organica.id')
            ->leftJoin('area as unidad_organica_destino', 'movimientos.unidad_organica_destino', '=', 'unidad_organica_destino.id')
            ->leftJoin('cargo', 'movimientos.cargo', '=', 'cargo.id')
            ->leftJoin('personal', 'movimientos.personal_id', '=', 'personal.id_personal')
            
            
            ->select(
                'movimientos.*',
                'archivo.nro_folio',
                
                DB::raw('CONCAT(personal.Apaterno, \' \', personal.Amaterno, \' \', personal.Nombres) AS nombre_completo'),
                DB::raw("tipodoc.nombre as tdoc"),
                DB::raw("unidad_organica.nombre as unidad_organica"),
                DB::raw("unidad_organica.id as unidad_organica_id"),
                DB::raw("unidad_organica_destino.nombre as unidad_organica_destino"),
                DB::raw("unidad_organica_destino.id as unidad_organica_destino_id"),
                DB::raw("cargo.nombre as cargo"),
                DB::raw("cargo.id as cargo_id"),
                DB::raw("CONCAT(tipodoc.nombre, '-', movimientos.nrodoc) as documento"),
                DB::raw('COALESCE(archivo.id, movimientos.archivo) as archivo')
            );
            if ($request->has('filter_field') && $request->has('filter_value')) {
                $filterField = $request->input('filter_field');
                $filterValue = $request->input('filter_value');
                if (!empty($filterValue) && in_array($filterField, $columns)) {
                    $df->where("movimientos.$filterField", $filterValue);
                }
            }
            $df = $df->get(); 
            return Datatables::of($df)->make(true);
    }

    public function show($id)
    {
        $c = Movimientos::find($id);
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
        $archivo = FileHelper::createArchivo($request, $request->personal_id, $request->idtd);
        if ($archivo) {
            $data = $request->except('archivo');
            $data['archivo'] = $archivo->id; 
            $estudio = Movimientos::create($data);
            return response()->json($estudio->toArray());
        } else {
            $data = $request->except('archivo');
            Movimientos::create($data);
        }

    }
    //
    public function update($id, Request $request)
    {
        $t = Movimientos::find($id);
        $data = $request->except('archivo');
        if ($t) {
            $archivo = FileHelper::updateArchivo($request, $t, $request->idtd);        
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
        $t = Movimientos::find($id);
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
