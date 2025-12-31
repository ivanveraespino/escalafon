<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage; 
use App\Models\Regimen;

use DB;
use PDF;

class RegimenController extends Controller
{


    public function getCampo(){    
        $data = DB::table('regimen')->get();
        return Datatables::of($data)->make(true);

    }

    public function index()
    {
        return view('Regimen.index');
    }

    public function mostrarcam($id)
    {
        $c = Regimen::find($id);
        if ($c) {
            return response()->json($c);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }

    public static function guardarcam(Request $request)
    {
            $data = $request->all();
            Regimen::create($data);
    }
    
    public function editcam($id, Request $request)
    {
        $t = Regimen::find($id);
        $data = $request->all();
        if ($t) {
            $t->update($data);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }
    
    public function borrarcam(Request $request)
    {
        $t = Regimen::find($request->id);
        if ($t) {
            $t->delete();
            return response()->json(['success' => 'Eliminado correctamente']);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }
    public function nuevoRegimen(Request $request){
        $request->validate([
            'nombre' => 'required|string'
        ]);
        $nuevo = new Regimen();
        $nuevo->nombre=$request->nombre;
        $nuevo->save();
        $guardado=DB::table('regimen')->select()->orderBy('nombre', 'ASC')->get();
        return response()->json($guardado);
    }
}
