<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TIpoArch;
use Illuminate\Support\Facades\Storage; 
use Yajra\Datatables\Datatables;
use App\Models\Archivo;
use App\Helpers\FileHelper;
use DB;

class TipoArchivoController extends Controller
{


    public function getCampota(){    
        $data = DB::table('tipo_archivo')->get();
        return Datatables::of($data)->make(true);

    }

    public function index()
    {
        return view('Tipos_archivos.index');
    }

    public function mostrar($id)
    {
        $c = TIpoArch::find($id);
        if ($c) {
            return response()->json($c);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }

    public static function guardar(Request $request)
    {
            $data = $request->all();
            TIpoArch::create($data);
    }
    
    public function edit($id, Request $request)
    {
        $t = TIpoArch::find($id);
        $data = $request->all();
        if ($t) {
            $t->update($data);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }
    
    public function borrar(Request $request)
    {
        $t = TIpoArch::find($request->id);
        if ($t) {
            $t->delete();
            return response()->json(['success' => 'Eliminado correctamente']);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }
}
