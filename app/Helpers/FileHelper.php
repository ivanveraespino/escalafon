<?php

namespace App\Helpers;
use setasign\Fpdi\Fpdi;
use setasign\Fpdf\Fpdf;
use Illuminate\Http\Request;
use App\Models\Archivo;
use App\Models\Tipodoc;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\PdfParser\PdfParser;
use setasign\Fpdi\PdfReader\PdfReader;

use Smalot\PdfParser\Parser;

use TCPDF;

use DB;
class FileHelper
{        
    public static function countPdfPages($filePath)
    {
        try {
            if (pathinfo($filePath) !== 'pdf') {
                return 0; // No es un PDF, devolver 0
            }
            // Instanciar el parser
            $parser = new Parser();
            // Cargar el archivo PDF
            $pdf = $parser->parseFile($filePath);
    
            $pageCount = count($pdf->getPages());
            return $pageCount;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public static function createArchivo(Request $request, $personal_id, $categoriaId) {
        if ($request->hasFile('archivo')) {
            ini_set('memory_limit', '1G'); 
            //nom
            $file = $request->file('archivo');
            $fileContent = file_get_contents($request->file('archivo')->getRealPath());
            $archivo = new Archivo();
            $archivo->peso = $file->getSize();
            $archivo->extension = $file->getClientOriginalExtension();
            $archivo->nro_folio = $request->nro_folios ?? 0;
            $archivo->data_archivo = base64_encode($fileContent);
            $archivo->save();
            return $archivo;
        }
        return null;
    }
    //SIN BLOBS
    public static function createArchivo2($file, $nfolios, $personal_id, $categoriaId)
    {

        if ($file instanceof \Illuminate\Http\UploadedFile) { // Verifica que sea un archivo válido
            ini_set('memory_limit', '1G');
            $fileContent = file_get_contents($file->getRealPath());
            $archivo = new Archivo();
            $archivo->peso = $file->getSize();
            $archivo->extension = $file->getClientOriginalExtension();
            
            if (is_null($nfolios) && $archivo->extension === 'pdf') {
                $archivo->nro_folio = self::countPdfPages($file);
            } else {
                $archivo->nro_folio = $nfolios ?? 0;
            }
    
            $archivo->data_archivo = base64_encode($fileContent);
            $archivo->save();
            return $archivo;
        }
    
        return null;
    }
      
    public static function updateArchivo(Request $request, $t, $categoriaId)
    {
        $archivo = Archivo::find($t->archivo); 
        if ($archivo) {
            ini_set('memory_limit', '1G'); 

            if ($request->hasFile('archivo')) {
                $file = $request->file('archivo');
                $fileContent = file_get_contents($file->getRealPath());
                $archivo->data_archivo = base64_encode($fileContent);
                $archivo->peso = filesize($file->getRealPath());
                $archivo->extension = $file->getClientOriginalExtension();
                $archivo->nro_folio = $request->nro_folios ?? 0;
              }
            $archivo->save();
            return $archivo;
        }else{
            return self::createArchivo($request, $request->personal_id, $categoriaId);
        }
    }

    //VINCULO LABORAL
    public static function updateArchivo2($file, $personal_id, $categoriaId, $id,$nfolios)
    {
        $archivo = Archivo::find($id); 
        if ($archivo) {
            ini_set('memory_limit', '1G'); 

            if ($file instanceof \Illuminate\Http\UploadedFile) { 
                $fileContent = file_get_contents($file->getRealPath());
                $archivo->data_archivo = base64_encode($fileContent);
                $archivo->peso = filesize($file->getRealPath());
                $archivo->extension = $file->getClientOriginalExtension();
                $archivo->nro_folio = $nfolios ?? 0;            
                }
            $archivo->save();
            return $archivo;
        }else{
            return self::createArchivo2($file,$nfolios, $personal_id, $categoriaId);

        }
            
    }

        
        //PARA LA FOTO
        public static function updateArchivo3(Request $request, $t, $categoriaId)
        {
            $archivo = Archivo::find($t->archivo); 
            if ($archivo) {
                ini_set('memory_limit', '1G'); 
                $file = $request->file('archivo');
                $fileContent = file_get_contents($file->getRealPath());
                $archivo->data_archivo = base64_encode($fileContent);
                $archivo->peso = filesize($file->getRealPath());
                $archivo->extension = $file->getClientOriginalExtension();
                $archivo->nro_folio = 0;
                $archivo->save();
                return $archivo;
            }else{
                return self::createArchivo($request, $request->id_personal, $categoriaId);
            }
        }

    
}
