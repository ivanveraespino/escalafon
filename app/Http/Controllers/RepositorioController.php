<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;



class RepositorioController extends Controller
{
    //
    public function subirArchivo(Request $request)
    {
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $nombre = time() . "_" . $archivo->getClientOriginalName();
            $archivo->move(public_path('repositories'), $nombre);

            return response()->json(['mensaje' => '✅ Archivo subido correctamente.', 'name' => $nombre]);
        }

        return response()->json(['mensaje' => '⚠ Error al subir el archivo.'], 400);
    }

    public function subirCarga(Request $request)
    {
        //ini_set('upload_max_filesize', '40M');
        //ini_set('post_max_size', '40M');
        //ini_set('max_execution_time', '500');
        //ini_set('max_input_time', '500');

        $request->validate([
            'archivo' => 'required|file|mimes:pdf|max:41600', // 200MB
        ]);

        $archivo = $request->file('archivo');

        $archivo = $request->file('archivo');
        $nombre = time() . '_' . $archivo->getClientOriginalName();

        $archivo->move(public_path('archivos'), $nombre);

        // Generar nombre único con hash y extensión original
        //$nombreHash = Str::random(40) . '.' . $archivo->getClientOriginalExtension();

        // Carpeta por usuario (puedes usar auth()->id() si estás autenticando)
        //$carpeta = 'usuarios/' . ($request->user()->id ?? 'anonimo');

        // Subir a S3
        //$ruta = Storage::disk('s3')->putFileAs($carpeta, $archivo, $nombreHash);

        // Generar URL firmada que expira en 1 hora
        //$urlFirmada = Storage::disk('s3')->temporaryUrl($ruta, now()->addHour());

        return response()->json([
            'mensaje' => 'Archivo subido correctamente a S3',
            'url' => $nombre
        ]);
        //ini_set('upload_max_filesize', '2M');
        //ini_set('post_max_size', '8M');
        //ini_set('max_execution_time', '120');
        //ini_set('max_input_time', '60');
    }
}
