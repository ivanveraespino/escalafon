<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ArchivosAdjuntos;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;
use App\Models\Archivo;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Schema;

use DB;

class ArchivosAdjuntosController extends Controller
{

    public function index(Request $request)
    {
        $columns = Schema::getColumnListing('archivos_adjuntos');
        $columns = array_diff($columns, ['updated_at']);
            $prefixedColumns = array_map(function ($column) {
            return "archivos_adjuntos.$column";
        }, $columns);
    
        $df = DB::table('archivos_adjuntos')
            ->leftJoin('tipodoc', 'archivos_adjuntos.idtd', '=', 'tipodoc.id')
            ->leftJoin('personal', 'archivos_adjuntos.personal_id', '=', 'personal.id_personal')
            
            ->leftJoin('archivo', 'archivos_adjuntos.archivo', '=', 'archivo.id')
            
            ->select(
                array_merge($prefixedColumns, [ 
                    'tipodoc.nombre as tipo_archivo',
                    
                    DB::raw('CONCAT(personal.Apaterno, \' \', personal.Amaterno, \' \', personal.Nombres) AS nombre_completo'),
                    DB::raw('ISNULL(archivo.id, NULL) as archivo'), 
                    DB::raw('ISNULL(archivo.nro_folio, NULL)as nro_folio') 
                ])
            );
            if ($request->has('filter_field') && $request->has('filter_value')) {
                $filterField = $request->input('filter_field');
                $filterValue = $request->input('filter_value');
                if (!empty($filterValue) && in_array($filterField, $columns)) {
                    $df->where("archivos_adjuntos.$filterField", $filterValue);
                }
            
            }
            $df = $df->get();

        return Datatables::of($df)->make(true);
    }

    public function show($id)
    {
        $c = ArchivosAdjuntos::find($id);
        if ($c) {
            return response()->json($c);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }

    public static function store(Request $request) {
        $request->validate([
            'personal_id' => 'required',
        ]);
    
        try {
            $archivo = FileHelper::createArchivo($request, $request->personal_id, 20);
            $data = $request->except('archivo');
            if ($archivo) {
                $data['archivo'] = $archivo->id; 
            } 
            $o_archivos = ArchivosAdjuntos::create($data);
            return response()->json($o_archivos->toArray());

        } catch (Exception $e) {
            Log::error('Error en el proceso de guardar: ' . $e->getMessage());
            return response()->json(['error' => 'Error en el proceso de guardar: ' . $e->getMessage()], 500);
        }
    }
    
    
    public function update($id, Request $request)
    {

        $t = ArchivosAdjuntos::find($id);
        if ($t) {
            if ($request->hasFile('archivo')) {   
                $archivo = FileHelper::updateArchivo($request, $t,20);        
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
        $t = ArchivosAdjuntos::find($id);
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