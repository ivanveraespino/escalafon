<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage; 
use App\Models\RegimenPen;

use DB;
use PDF;

class RegimenPenController extends Controller
{


    public function getrp(){    
        $data = DB::table('regimen_pensionario')->get();
        return Datatables::of($data)->make(true);

    }

    public function index()
    {
        return view('RegimenPen.index');
    }

    public function mostrarrp($id)
    {
        $c = RegimenPen::find($id);
        if ($c) {
            return response()->json($c);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }

    public static function guardarrp(Request $request)
    {
            $data = $request->all();
            RegimenPen::create($data);
    }
    
    public function editrp($id, Request $request)
    {
        $t = RegimenPen::find($id);
        $data = $request->all();
        if ($t) {
            $t->update($data);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }
    
    public function borrarrp(Request $request)
    {
        $t = RegimenPen::find($request->id);
        if ($t) {
            $t->delete();
            return response()->json(['success' => 'Eliminado correctamente']);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }
    public function nuevoRegimenPen(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string'
        ]);
        $nuevo = new RegimenPen();
        $nuevo->nombre = $request->nombre;
        $nuevo->save();
        $guardado = DB::table('regimen_pensionario')->select()->orderBy('nombre', 'ASC')->get();
        return response()->json($guardado);
    }
}
