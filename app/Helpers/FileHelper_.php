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
            // Validar si el archivo tiene la extensión PDF
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


    public static function createArchivo(Request $request, $personal_id, $categoriaId)
    {   
        if ($request->hasFile('archivo')) {
            ini_set('memory_limit', '1G'); 
            // Obtener la ruta temporal del archivo
            $file = $request->file('archivo');
            $filePath = $file->getRealPath();  
            // Nombres de archivos
            $ntp = '';
            $nro_doc = $request->nro_documento ?? $request->nrodoc ?? $request->nro_doc_fin ?? $request->nro_doc ?? '';
            if ($request->has('idtd') || $request->has('id_tipo_documento') || $request->has('id_tipo_documento_fin')) {
                $id = $request->idtd ?? $request->id_tipo_documento ?? $request->id_tipo_documento_fin;
                $tipoDocumento = Tipodoc::find($id);
                $ntp = $tipoDocumento ? $tipoDocumento->nombre : '';
            }
            $nom_doc = $ntp . ' ' . $nro_doc;
            // Consulta SQL con OPENROWSET para insertar el archivo
            $sql = "
                INSERT INTO dbo.archivo (personal_id, clave, peso, nombre, nro_folio, extension,data_archivo, created_at, updated_at)
                OUTPUT INSERTED.id  -- Esto te devuelve el ID insertado
                VALUES (?, ?, ?, ?, ?, ?, 
                    (SELECT * FROM OPENROWSET(BULK '$filePath', SINGLE_BLOB) AS archivoBinario), 
                    GETDATE(), GETDATE());
            ";
    
            // Preparar los parámetros para la consulta
            $params = [
                $personal_id,                     // personal_id
                $categoriaId,              // clave
                filesize($filePath),       // peso (tamaño del archivo en bytes)
                $nom_doc,                  // nombre
                $request->nro_folios ?? self::countPdfPages($filePath), // nro_folio
                $file->getClientOriginalExtension(), // extension
            ];
            // Ejecutar la consulta
            //$stmt = DB::statement($sql, $params);
            $idResult = DB::selectOne($sql, $params); 
            if ($idResult) {
                $archivo = new Archivo();
                $archivo->id = $idResult->id; // Acceder al ID insertado desde el resultado
                return $archivo; // Devolver el objeto con el ID
            }
        }

        return null;
    }

    
    public static function createArchivo2(Request $request, $nombrecampo,$nfolios, $personal_id, $categoriaId)
    {
        if ($request->hasFile($nombrecampo)) {
            ini_set('memory_limit', '1G'); 

            $file = $request->file($nombrecampo);
            $filePath = $file->getRealPath();  // Ruta temporal del archivo
    
            //NOMBRES ARCHIVOS
            $ntp = '';
            $nro_doc = $request->nro_documento ?? $request->nrodoc ?? $request->nro_doc_fin ?? $request->nro_doc ?? '';
            if ($request->has('idtd') || $request->has('id_tipo_documento') || $request->has('id_tipo_documento_fin')) {
                $id = $request->idtd ?? $request->id_tipo_documento ?? $request->id_tipo_documento_fin;
                $tipoDocumento = Tipodoc::find($id);
                $ntp = $tipoDocumento ? $tipoDocumento->nombre : '';
            }
            $nom_doc = $ntp . ' ' . $nro_doc;

            $sql = "
                INSERT INTO dbo.archivo (personal_id, clave, peso, nombre, nro_folio, extension,data_archivo, created_at, updated_at)
                OUTPUT INSERTED.id  -- Esto te devuelve el ID insertado
                VALUES (?, ?, ?, ?, ?, ?, 
                    (SELECT * FROM OPENROWSET(BULK '$filePath', SINGLE_BLOB) AS archivoBinario), 
                    GETDATE(), GETDATE());
            ";

            // Preparar los parámetros para la consulta
            $params = [
                $personal_id,                     // personal_id
                $categoriaId,              // clave
                filesize($filePath),       // peso (tamaño del archivo en bytes)
                $nom_doc,                  // nombre
                $request->nro_folios ?? self::countPdfPages($filePath), // nro_folio
                $file->getClientOriginalExtension(), // extension
            ];
            // Ejecutar la consulta
            //$stmt = DB::statement($sql, $params);
            $idResult = DB::selectOne($sql, $params); 
            if ($idResult) {
                $archivo = new Archivo();
                $archivo->id = $idResult->id; // Acceder al ID insertado desde el resultado
                return $archivo; // Devolver el objeto con el ID
            }
        }
        return null;
    }

    public static function updateArchivo(Request $request, $t, $categoriaId)
    {
            if (is_null($t->archivo)) {
                return self::createArchivo($request, $request->personal_id, $categoriaId);
            }else{
                ini_set('memory_limit', '1G'); 
                $file = $request->file('archivo');
                $filePath = $file ? $file->getRealPath() : null; 
                $file_ext = $file ? $file->getClientOriginalExtension() : null; 
                $file_size = $file ? filesize($filePath) : null; 
                $file_pages = $file ? self::countPdfPages($filePath) : null;
                if ($file) {
                    $dataArchivoSQL = "(SELECT * FROM OPENROWSET(BULK '$filePath', SINGLE_BLOB) AS archivoBinario)";
                } else {
                    $dataArchivoSQL = "data_archivo";
                }
                // Preparar la consulta SQL
                $sql = "
                UPDATE dbo.archivo
                SET 
                    clave = COALESCE(?, clave),
                    peso = COALESCE(?, peso), 
                    nombre = COALESCE(NULLIF(?, ''), nombre),
                    nro_folio = COALESCE(?, nro_folio),
                    extension = COALESCE(NULLIF(?, ''), extension),
                    data_archivo = COALESCE($dataArchivoSQL, data_archivo),
                    updated_at = GETDATE()
                
                WHERE id = ?;
                ";
                //NOMBRES ARCHIVOS
                $ntp = '';
                $nro_doc = $request->nro_documento ?? $request->nrodoc ?? $request->nro_doc_fin ?? $request->nro_doc ?? '';
                if ($request->has('idtd') || $request->has('id_tipo_documento')|| $request->has('id_tipo_documento_fin')) {
                    $id = $request->idtd ?? $request->id_tipo_documento ?? $request->id_tipo_documento_fin;                
                    $tipoDocumento = Tipodoc::find($id);
                    $ntp = $tipoDocumento ? $tipoDocumento->nombre : '';
                }
                $nom_doc = $ntp . ' ' . $nro_doc;
                if ($request->has('idtd') || $request->has('id_tipo_documento')|| $request->has('id_tipo_documento_fin')) {
                    $nom_doc = $nom_doc;
                } else {
                    $nom_doc = mb_convert_encoding($file->getClientOriginalName(), 'UTF-8', 'auto');
                }
                // Preparar los parámetros para la consulta
                $params = [
                    $categoriaId ?? null,       // clave (si existe)
                    $file_size ?? null,         // peso (si hay archivo)
                    $nom_doc ?? null,           // nombre (si existe)
                    $request->nro_folios ?? null,  // nro_folio (si existe)
                    $file_ext ?? null,          // extension (si existe archivo)
                    $t->archivo,                      // id (debe ser el último)
                ];
            
                // Ejecutar la consulta
                $result = DB::update($sql, $params);
                // Verificar si la consulta fue exitosa y obtener el ID del archivo insertado
                if ($result) {
                    $archivo = new Archivo();
                    $archivo->id = $t->archivo; // Obtener el último ID insertado
                    return $archivo; // Devolver el objeto con el ID
                }
            }
         
    }

    //PARA LA FOTO
    public static function updateArchivo3(Request $request, $t, $categoriaId)
    {
        if (is_null($t->archivo)) {
            //PASAR id del PERSONAL A ACTUALIZAR
            return self::createArchivo($request, $request->id_personal, $categoriaId);
        }else{
            ini_set('memory_limit', '1G'); 
            $file = $request->file('archivo');
            $filePath = $file ? $file->getRealPath() : null; 
            $file_ext = $file ? $file->getClientOriginalExtension() : null; 
            $file_size = $file ? filesize($filePath) : null; 
            $file_pages = $file ? self::countPdfPages($filePath) : null;
            if ($file) {
                $dataArchivoSQL = "(SELECT * FROM OPENROWSET(BULK '$filePath', SINGLE_BLOB) AS archivoBinario)";
            } else {
                $dataArchivoSQL = "data_archivo";
            }
            // Preparar la consulta SQL
            $sql = "
            UPDATE dbo.archivo
            SET 
                clave = COALESCE(?, clave),
                peso = COALESCE(?, peso), 
                nombre = COALESCE(NULLIF(?, ''), nombre),
                nro_folio = COALESCE(?, nro_folio),
                extension = COALESCE(NULLIF(?, ''), extension),
                data_archivo = COALESCE($dataArchivoSQL, data_archivo),
                updated_at = GETDATE()
            
            WHERE id = ?;
            ";
            // Preparar los parámetros para la consulta
            $params = [
                $categoriaId ?? null,       // clave (si existe)
                $file_size ?? null,         // peso (si hay archivo)
                "FOTO DE PERFIL" ?? null,           // nombre (si existe)
                $request->nro_folios ?? null,  // nro_folio (si existe)
                $file_ext ?? null,          // extension (si existe archivo)
                $t->archivo,                      // id (debe ser el último)
            ];
            // Ejecutar la consulta
            $result = DB::update($sql, $params);
            // Verificar si la consulta fue exitosa y obtener el ID del archivo insertado
            if ($result) {
                $archivo = new Archivo();
                $archivo->id = $t->archivo; // Obtener el último ID insertado
                return $archivo; // Devolver el objeto con el ID
            }
        }

    }
    
    //VINCULO LABORAL
    public static function updateArchivo2(Request $request, $t, $categoriaId, $nombrecampo,$nfolios)
    {
        if (is_null($t->$nombrecampo)) {
            return self::createArchivo2($request, $nombrecampo,$nfolios, $request->personal_id, $categoriaId);
        }else{
            ini_set('memory_limit', '1G'); 
            $file = $request->file($nombrecampo);
            $filePath = $file ? $file->getRealPath() : null; 
            $file_ext = $file ? $file->getClientOriginalExtension() : null; 
            $file_size = $file ? filesize($filePath) : null; 
            $file_pages = $file ? self::countPdfPages($filePath) : null;
            if ($file) {
                $dataArchivoSQL = "(SELECT * FROM OPENROWSET(BULK '$filePath', SINGLE_BLOB) AS archivoBinario)";
            } else {
                $dataArchivoSQL = "data_archivo";
            } 

            //NOMBRES ARCHIVOS
            $ntp = '';
            $nro_doc = $request->nro_documento ?? $request->nrodoc ?? $request->nro_doc_fin ?? $request->nro_doc ?? '';
            if ($request->has('idtd') || $request->has('id_tipo_documento') || $request->has('id_tipo_documento_fin')) {
                $id = $request->idtd ?? $request->id_tipo_documento ?? $request->id_tipo_documento_fin;
                $tipoDocumento = Tipodoc::find($id);
                $ntp = $tipoDocumento ? $tipoDocumento->nombre : '';
            }
            $nom_doc = $ntp . ' ' . $nro_doc;
            $sql = "
            UPDATE dbo.archivo
            SET 
                clave = COALESCE(?, clave),
                peso = COALESCE(?, peso), 
                nombre = COALESCE(NULLIF(?, ''), nombre),
                nro_folio = COALESCE(?, nro_folio),
                extension = COALESCE(NULLIF(?, ''), extension),
                data_archivo = COALESCE($dataArchivoSQL, data_archivo),
                updated_at = GETDATE()
            
            WHERE id = ?;
            ";
            // Preparar los parámetros para la consulta
            $params = [
                $categoriaId ?? null,       
                $file_size ?? null,         
                $nom_doc ?? null,           // nombre (si existe)
                $request->nro_folios ?? null,  // nro_folio (si existe)
                $file_ext ?? null,          // extension (si existe archivo)
                $t->$nombrecampo,                      // id (debe ser el último)
            ];
            // Ejecutar la consulta
            $result = DB::update($sql, $params);
            // Verificar si la consulta fue exitosa y obtener el ID del archivo insertado
            if ($result) {
                $archivo = new Archivo();
                $archivo->id = $t->$nombrecampo; // Obtener el último ID insertado
                return $archivo; // Devolver el objeto con el ID
            }            
        }
    }
    
}
