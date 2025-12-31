<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage; 

use DB;
use PDF;

class UbigeoController extends Controller
{
    public function obtener_departamentos(Request $request)
    {
        $departamentos = DB::table('departamentos')
        ->select('id', 'nombre')
        ->get();
        return response()->json($departamentos);
    }
    
    public function obtener_provincias_por_departamento(Request $request)
    {
        $departamentoId = $request->input('departamento_id');
        $provincias = DB::table('provincias')
            ->where('departamento_id', $departamentoId)
            ->get();
        return response()->json($provincias);
    }
    
    public function obtener_distritos_por_provincia(Request $request)
    {
        $provinciaId = $request->input('provincia_id');
        $distritos = DB::table('distritos')
            ->where('provincia_id', $provinciaId)
            ->get();
        return response()->json($distritos);
    }
}
