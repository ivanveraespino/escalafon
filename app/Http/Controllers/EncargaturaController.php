<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Encargatura;
use Illuminate\Support\Facades\Storage; 
use Yajra\Datatables\Datatables;
use App\Models\Archivo;
use App\Helpers\FileHelper;
use DB;

class EncargaturaController extends Controller
{
    public function mostrarallen(Request $request){
        $df = DB::table('encargatura')
            ->leftJoin('tipodoc', 'encargatura.idtd', '=', 'tipodoc.id')
            ->leftJoin('archivo', 'encargatura.archivo', '=', 'archivo.id')
            ->where('encargatura.personal_id', $request->id)
            ->select(
                'encargatura.*',
                'archivo.nro_folio',
                DB::raw("tipodoc.nombre as tdoc"),
                DB::raw('COALESCE(archivo.id, encargatura.archivo) as archivo')
            )
            ->get();        
            return Datatables::of($df)->make(true);
    }



    public function mostrar(Request $request)
    {
        $c = Encargatura::find($request->id);
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
        $archivo = FileHelper::createArchivo($request, $request->personal_id, 17);
        if ($archivo) {
            $data = $request->except('archivo');
            $data['archivo'] = $archivo->id; 
            $estudio = Encargatura::create($data);
            return response()->json($estudio->toArray());
        } else {
            $data = $request->except('archivo');
            Encargatura::create($data);
        }

    }
    
    public function edit($id, Request $request)
    {
        $t = Encargatura::find($id);
        if ($t) {
            if ($request->hasFile('archivo')) {   
                $archivo = FileHelper::updateArchivo($request, $t,"17");        
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
    
    public function borrar(Request $request)
    {
        $t = Encargatura::find($request->id);
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
