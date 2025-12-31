<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage; 
use App\Models\CondicionLaboral;

use DB;
use PDF;

class RegimenMController extends Controller
{
    public function getCampo(){    
        $data = DB::table('condicion_laboral')->get();
        return Datatables::of($data)->make(true);
    }

    public function index()
    {
        return view('Regimen_modalidad.index');
    }

    public function mostrar($id)
    {
        $c = CondicionLaboral::find($id);
        if ($c) {
            return response()->json($c);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }

    public static function guardar(Request $request)
    {
            $data = $request->all();
            CondicionLaboral::create($data);
    }
    
    public function edit($id, Request $request)
    {
        $t = CondicionLaboral::find($id);
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
            // Busca la condición laboral por ID
            $t = CondicionLaboral::find($request->id);
            
            if (!$t) {
                return response()->json(['error' => 'Condición laboral no encontrada'], 404);
            }

            $t->delete();

            return response()->json(['success' => 'Condición laboral eliminada correctamente']);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json(['error' => 'No se puede eliminar la condición laboral porque está asociada a vínculos laborales'], 400);
            }

            return response()->json(['error' => 'Error de base de datos: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error inesperado: ' . $e->getMessage()], 500);
        }
    }

}
