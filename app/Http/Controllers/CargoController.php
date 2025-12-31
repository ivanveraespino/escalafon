<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cargo;
use Illuminate\Support\Facades\Storage; 
use Yajra\Datatables\Datatables;
use App\Models\Archivo;
use App\Helpers\FileHelper;
use DB;

class CargoController extends Controller
{


    public function getall() {
        $data = DB::table('cargo')
            ->select('id', 'nombre', 'estado', DB::raw("CASE WHEN estado = 1 THEN 'ACTIVO' ELSE 'INACTIVO' END AS estado"))
            ->get();
    
        return Datatables::of($data)->make(true);
    }

    public function index()
    {
        return view('Cargo.index');
    }

    public function mostrar($id)
    {
        $c = Cargo::find($id);
        if ($c) {
            return response()->json($c);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }

    public static function guardar(Request $request)
    {
        $data = $request->all();
        Cargo::create($data);
    }
    
    public function edit($id, Request $request)
    {
        $t = Cargo::find($id);
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
            $t = Cargo::find($request->id);
            
            if (!$t) {
                return response()->json(['error' => 'Campo no encontrado'], 404);
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
}
