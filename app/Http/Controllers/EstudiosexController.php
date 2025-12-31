<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EstudiosEsp;
use Illuminate\Support\Facades\Storage; 
use Yajra\Datatables\Datatables;
use App\Helpers\FileHelper;
use App\Models\Archivo;
use Illuminate\Support\Facades\Schema;

use DB;

class EstudiosexController extends Controller
{

    public function index(Request $request)
    {
        $columns = Schema::getColumnListing('estudios_especializacion');
        $columns = array_diff($columns, ['created_at', 'updated_at']);
            $prefixedColumns = array_map(function ($column) {
            return "estudios_especializacion.$column";
        }, $columns);
    
        $df = DB::table('estudios_especializacion')
            ->leftJoin('personal', 'estudios_especializacion.personal_id', '=', 'personal.id_personal')
            
            ->leftJoin('archivo', 'estudios_especializacion.archivo', '=', 'archivo.id')
            
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
                    $df->where("estudios_especializacion.$filterField", $filterValue);
                }
            }
            $df = $df->get();
    
        return Datatables::of($df)->make(true);
    }

    public function show($id)
    {
        $c = EstudiosEsp::find($id);
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
        $archivo = FileHelper::createArchivo($request, $request->personal_id, "03", "esx");
        if ($request->hasFile('archivo')) {
            $data = $request->except('archivo');
            $data['archivo'] = $archivo->id; 
            $s= EstudiosEsp::create($data);
            return response()->json($s->toArray());

        } else {
            $data = $request->except('archivo');
            EstudiosEsp::create($data);
        }

    }
    
    public function update($id, Request $request)
    {
        $t = EstudiosEsp::find($id);
        if ($t) {
            if ($request->hasFile('archivo')) {   
                $archivo = FileHelper::updateArchivo($request, $t,"03","esx_");        
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
        $t = EstudiosEsp::find($id);
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
