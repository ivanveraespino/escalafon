<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Area;
use Illuminate\Support\Facades\Storage; 
use Yajra\Datatables\Datatables;
use App\Models\Archivo;
use App\Helpers\FileHelper;
use App\Models\Cargo;
use DB;

class AreaController extends Controller
{

    public function getall() {
        $areas = Area::select('area.id', 'area.nombre', DB::raw("CASE WHEN area.estado = 1 THEN 'ACTIVO' ELSE 'INACTIVO' END AS estado"), 'dependencias.nombre AS dependencia')
        ->leftJoin('area as dependencias', 'area.dependencia', '=', 'dependencias.id')
        ->get();
        return Datatables::of($areas)->make(true);
    }


    public function index()
    {
        return view('Area.index');
    }

    public function mostrar($id)
    {
        $area = Area::find($id);
    
        // Obtener el nombre de la dependencia
        if ($area->dependencia) {
            $dependenciaNombre = Area::where('id', $area->dependencia)->value('nombre');
            $area->dependencia_nombre = $dependenciaNombre;
        } else {
            $area->dependencia_nombre = null;
        }
    
        return response()->json($area);
    }
    

    public static function guardar(Request $request)
    {
        $data = $request->all();
        Area::create($data);
    }
    
    public function edit($id, Request $request)
    {
        $t = Area::find($id);
        $data = $request->all();
        if ($t) {
            $t->update($data);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }
    
    public function borrar(Request $request)
    {
        try {
            $t = Area::find($request->id);
            
            if (!$t) {
                return response()->json(['error' => 'Unidad Organica no encontrado'], 404);
            }

            $t->delete();

            return response()->json(['success' => 'Campo eliminado correctamente']);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json(['error' => 'No se puede eliminar el Campo porque está asociada a un registro'], 400);
            }

            return response()->json(['error' => 'Error de base de datos: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error inesperado: ' . $e->getMessage()], 500);
        }
    }

    public function agregarArea(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string'
        ]);
        $nuevo = new Cargo();
        $nuevo->nombre=$request->nombre;
        $nuevo->estado=1;
        $nuevo->save();
        $guardado=DB::table('cargo')->select()->where('estado','=','1')->orderBy('nombre', 'ASC')->get();
        return response()->json($guardado);
    }
}
