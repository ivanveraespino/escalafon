<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nombramiento;
use Illuminate\Support\Facades\Storage; 
use Yajra\Datatables\Datatables;

class NombramientoController extends Controller
{

    public function mostraralln(Request $request){
        $df = Nombramiento::where('personal_id', $request->id)->get(); 
        return Datatables::of($df)->make(true);
    }

    public function mostrar(Request $request)
    {
        $c = Nombramiento::find($request->id);
        if ($c) {
            return response()->json($c);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }

    public static function guardar(Request $request)
    {
        $request->validate([
            'personal_id' => 'required',
        ]);
        if ($request->hasFile('archivo')) {
            $fileName = 'nom_' . date('YmdHis') . '_' . $request->personal_id . '.' . $request->file('archivo')->getClientOriginalExtension();
            $archivoPath = $request->file('archivo')->storeAs('archivos', $fileName, 'public');
            $data = $request->except('archivo');
            $data['archivo'] = $archivoPath;
            $s= Nombramiento::create($data);
            return response()->json($s->toArray());
        } else {
            $archivoPath = "";
            $data = $request->except('archivo');
            $data['archivo'] = $archivoPath;
            Nombramiento::create($data);
        }

    }
    
    public function edit($id, Request $request)
    {
        $t = Nombramiento::find($id);
        $data = $request->except('archivo','personal_id');
        if ($t) {
            if ($request->hasFile('archivo')) {
                if ($t->archivo) {
                    Storage::disk('public')->delete($t->archivo);
                }
                $fileName = 'nom_' . date('YmdHis') . '_' . $request->personal_id . '.' . $request->file('archivo')->getClientOriginalExtension();
                $archivoPath = $request->file('archivo')->storeAs('archivos', $fileName, 'public');
                $data['archivo'] = $archivoPath;
            }   
            $t->update($data);

        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }
    
    public function borrar(Request $request)
    {
        $t = Nombramiento::find($request->id);
        if ($t) {
            if ($t->archivo) {
                Storage::disk('public')->delete($t->archivo);
            }
            $t->delete();
            return response()->json(['success' => 'Eliminado correctamente']);
        } else {
            return response()->json(['error' => 'Campo no encontrado'], 404);
        }
    }

}
