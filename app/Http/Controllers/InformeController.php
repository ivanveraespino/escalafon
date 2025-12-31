<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;
use App\Helpers\FileHelper;
use App\Models\Departamento;
use App\Models\Domicilio;
use App\Models\Explaboral;
use App\Models\Familiares;
use App\Models\Idiomas;
use App\Models\Personal;
use App\Models\Movimientos;
use App\Models\Vinculo;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use \PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWord\Style\Table;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Matrix\Decomposition\QR;
use PhpParser\Node\Stmt\Foreach_;
use TCPDF;
use ZipArchive;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Vinkla\Hashids\Facades\Hashids;

class InformeController extends Controller
{

    public function index()
    {
        return view('Area.index');
    }

    public function verReporte()
    {
        $personal = Personal::select('id_personal', 'nro_documento_id', DB::raw("CONCAT(Apaterno, ' ', Amaterno, ' ', Nombres) as nombre"))
            ->orderBy('Apaterno', 'asc')
            ->orderBy('Amaterno', 'asc')
            ->orderBy('Nombres', 'asc')
            ->get();
        return view('Informe.verReporte', compact('personal'));
    }


    public function descargarArchivos(Request $request)
    {
        $ids = explode(",", $request->query('personal'));
        $datos = explode(",", $request->query('datos'));
        $zip = new \ZipArchive();
        $tempFile = tempnam(sys_get_temp_dir(), 'zip');
        $fecha = Carbon::now()->format('Ymd_His'); // Resultado: 2025-08-14
        $nombreArchivo = "descarga_{$fecha}.zip";

        $archivosAgregados = false;
        if ($zip->open($tempFile, \ZipArchive::CREATE | ZipArchive::OVERWRITE)) {

            foreach ($ids as $id) {
                $nombre = Personal::find($id);
                foreach ($datos as $dato) {
                    if ($dato == 3) {
                        $vinculos = DB::table('vinculos')
                            ->where('personal_id', $id)->get();
                        foreach ($vinculos as $vinculo) {
                            if (!empty($vinculo->archivo)) {
                                $ruta = public_path("repositories/{$vinculo->archivo}");

                                if (file_exists($ruta)) {
                                    //$zip->addFile($tempFile, basename($movimiento->archivo));
                                    $zip->addFile($ruta, "{$nombre->Nombres}/" . basename($vinculo->archivo));
                                    $archivosAgregados = true;
                                } else {
                                    // El archivo no existe físicamente
                                }
                            } else {
                                // El campo 'archivo' está vacío o es null
                            }
                        }
                    }
                    if ($dato == 4) {
                        $vinculos = DB::table('estudios')
                            ->where('personal_id', $id)->get();

                        foreach ($vinculos as $vinculo) {
                            if (!empty($vinculo->archivo)) {
                                $ruta = public_path("repositories/{$vinculo->archivo}");

                                if (file_exists($ruta)) {
                                    //$zip->addFile($tempFile, basename($movimiento->archivo));
                                    $zip->addFile($ruta, "{$nombre->Nombres}/" . basename($vinculo->archivo));
                                    $archivosAgregados = true;
                                } else {
                                    // El archivo no existe físicamente
                                }
                            } else {
                                // El campo 'archivo' está vacío o es null
                            }
                        }
                    }
                    if ($dato == 5) {
                        $vinculos = DB::table('estudios_especializacion')
                            ->where('personal_id', $id)->get();
                        foreach ($vinculos as $vinculo) {
                            if (!empty($vinculo->archivo)) {
                                $ruta = public_path("repositories/{$vinculo->archivo}");

                                if (file_exists($ruta)) {
                                    //$zip->addFile($tempFile, basename($movimiento->archivo));
                                    $zip->addFile($ruta, "{$nombre->Nombres}/" . basename($vinculo->archivo));
                                    $archivosAgregados = true;
                                } else {
                                    // El archivo no existe físicamente
                                }
                            } else {
                                // El campo 'archivo' está vacío o es null
                            }
                        }
                    }
                    if ($dato == 6) {
                        $vinculos = DB::table('idiomas')
                            ->where('personal_id', $id)->get();
                        foreach ($vinculos as $vinculo) {
                            if (!empty($vinculo->archivo)) {
                                $ruta = public_path("repositories/{$vinculo->archivo}");

                                if (file_exists($ruta)) {
                                    //$zip->addFile($tempFile, basename($movimiento->archivo));
                                    $zip->addFile($ruta, "{$nombre->Nombres}/" . basename($vinculo->archivo));
                                    $archivosAgregados = true;
                                } else {
                                    // El archivo no existe físicamente
                                }
                            } else {
                                // El campo 'archivo' está vacío o es null
                            }
                        }
                    }
                    if ($dato == 7) {
                        $vinculos = DB::table('explaboral')
                            ->where('personal_id', $id)->get();
                        foreach ($vinculos as $vinculo) {
                            if (!empty($vinculo->archivo)) {
                                $ruta = public_path("repositories/{$vinculo->archivo}");

                                if (file_exists($ruta)) {
                                    //$zip->addFile($tempFile, basename($movimiento->archivo));
                                    $zip->addFile($ruta, "{$nombre->Nombres}/" . basename($vinculo->archivo));
                                    $archivosAgregados = true;
                                } else {
                                    // El archivo no existe físicamente
                                }
                            } else {
                                // El campo 'archivo' está vacío o es null
                            }
                        }
                    }
                    if ($dato == 9) {

                        $movimientos = DB::table('movimientos as m')
                            ->join('vinculos as v', 'v.id', '=', 'm.idvinculo')
                            ->where('v.personal_id', $id)
                            ->where('m.tipo', 0)->get();
                        foreach ($movimientos as $movimiento) {
                            if (!empty($movimiento->archivo)) {
                                $tempFile = storage_path("repositories/{$movimiento->archivo}");

                                if (file_exists($tempFile)) {
                                    //$zip->addFile($tempFile, basename($movimiento->archivo));
                                    $zip->addFile($tempFile, "{$nombre->nombre}/" . basename($movimiento->archivo));
                                    $archivosAgregados = true;
                                } else {
                                    // El archivo no existe físicamente
                                }
                            } else {
                                // El campo 'archivo' está vacío o es null
                            }
                        }
                    }
                    if ($dato == 10) {

                        $movimientos = DB::table('movimientos as m')
                            ->join('vinculos as v', 'v.id', '=', 'm.idvinculo')
                            ->where('v.personal_id', $id)
                            ->where('m.tipo', 1)->get();
                        foreach ($movimientos as $movimiento) {
                            if (!empty($movimiento->archivo)) {
                                $tempFile = storage_path("repositories/{$movimiento->archivo}");

                                if (file_exists($tempFile)) {
                                    //$zip->addFile($tempFile, basename($movimiento->archivo));
                                    $zip->addFile($tempFile, "{$nombre->nombre}/" . basename($movimiento->archivo));
                                    $archivosAgregados = true;
                                } else {
                                    // El archivo no existe físicamente
                                }
                            } else {
                                // El campo 'archivo' está vacío o es null
                            }
                        }
                    }
                    if ($dato == 11) {

                        $movimientos = DB::table('licencias as m')
                            ->join('vinculos as v', 'v.id', '=', 'm.idvinculo')
                            ->where('v.personal_id', $id)
                            ->get();
                        foreach ($movimientos as $movimiento) {
                            if (!empty($movimiento->archivo)) {
                                $tempFile = storage_path("repositories/{$movimiento->archivo}");
                                if (file_exists($tempFile)) {
                                    //$zip->addFile($tempFile, basename($movimiento->archivo));
                                    $zip->addFile($tempFile, "{$nombre->nombre}/" . basename($movimiento->archivo));
                                    $archivosAgregados = true;
                                } else {
                                    // El archivo no existe físicamente
                                }
                            } else {
                                // El campo 'archivo' está vacío o es null
                            }
                        }
                    }
                    if ($dato == 12) {
                        $movimientos = DB::table('permisos as m')
                            ->join('vinculos as v', 'v.id', '=', 'm.idvinculo')
                            ->where('v.personal_id', $id)
                            ->get();
                        foreach ($movimientos as $movimiento) {
                            if (!empty($movimiento->archivo)) {
                                $tempFile = storage_path("repositories/{$movimiento->archivo}");
                                if (file_exists($tempFile)) {
                                    //$zip->addFile($tempFile, basename($movimiento->archivo));
                                    $zip->addFile($tempFile, "{$nombre->nombre}/" . basename($movimiento->archivo));
                                    $archivosAgregados = true;
                                } else {
                                    // El archivo no existe físicamente
                                }
                            } else {
                                // El campo 'archivo' está vacío o es null
                            }
                        }
                    }

                    if ($dato == 13) {
                        $movimientos = DB::table('compensaciones as m')
                            ->join('vinculos as v', 'v.id', '=', 'm.idvinculo')
                            ->where('v.personal_id', $id)
                            ->get();
                        foreach ($movimientos as $movimiento) {
                            if (!empty($movimiento->archivo)) {
                                $tempFile = storage_path("repositories/{$movimiento->archivo}");
                                if (file_exists($tempFile)) {
                                    //$zip->addFile($tempFile, basename($movimiento->archivo));
                                    $zip->addFile($tempFile, "{$nombre->nombre}/" . basename($movimiento->archivo));
                                    $archivosAgregados = true;
                                } else {
                                    // El archivo no existe físicamente
                                }
                            } else {
                                // El campo 'archivo' está vacío o es null
                            }
                        }
                    }
                    if ($dato == 14) {
                        $movimientos = DB::table('reconocimientos as m')
                            ->join('vinculos as v', 'v.id', '=', 'm.idvinculo')
                            ->where('v.personal_id', $id)
                            ->get();
                        foreach ($movimientos as $movimiento) {
                            if (!empty($movimiento->archivo)) {
                                $tempFile = storage_path("repositories/{$movimiento->archivo}");
                                if (file_exists($tempFile)) {
                                    //$zip->addFile($tempFile, basename($movimiento->archivo));
                                    $zip->addFile($tempFile, "{$nombre->nombre}/" . basename($movimiento->archivo));
                                    $archivosAgregados = true;
                                } else {
                                    // El archivo no existe físicamente
                                }
                            } else {
                                // El campo 'archivo' está vacío o es null
                            }
                        }
                    }
                    if ($dato == 15) {
                        $movimientos = DB::table('sanciones as m')
                            ->join('vinculos as v', 'v.id', '=', 'm.idvinculo')
                            ->where('v.personal_id', $id)
                            ->get();
                        foreach ($movimientos as $movimiento) {
                            if (!empty($movimiento->archivo)) {
                                $tempFile = storage_path("repositories/{$movimiento->archivo}");
                                if (file_exists($tempFile)) {
                                    //$zip->addFile($tempFile, basename($movimiento->archivo));
                                    $zip->addFile($tempFile, "{$nombre->nombre}/" . basename($movimiento->archivo));
                                    $archivosAgregados = true;
                                } else {
                                    // El archivo no existe físicamente
                                }
                            } else {
                                // El campo 'archivo' está vacío o es null
                            }
                        }
                    }
                    if ($dato == 16) {
                        $movimientos = DB::table('vacaciones as m')
                            ->join('vinculos as v', 'v.id', '=', 'm.idvinculo')
                            ->where('v.personal_id', $id)
                            ->get();
                        foreach ($movimientos as $movimiento) {
                            if (!empty($movimiento->archivo)) {
                                $tempFile = storage_path("repositories/{$movimiento->archivo}");
                                if (file_exists($tempFile)) {
                                    //$zip->addFile($tempFile, basename($movimiento->archivo));
                                    $zip->addFile($tempFile, "{$nombre->nombre}/" . basename($movimiento->archivo));
                                    $archivosAgregados = true;
                                } else {
                                    // El archivo no existe físicamente
                                }
                            } else {
                                // El campo 'archivo' está vacío o es null
                            }
                        }
                    }
                }
            }
        }
        if ($archivosAgregados) {
            $zip->close();
            return response()->download($tempFile, $nombreArchivo)->deleteFileAfterSend(true);
        } else {
            $zip->close(); // aún debes cerrarlo
            return response()->json(['error' => 'No se encontraron archivos válidos para agregar al ZIP'], 404);
        }
    }

    public function informe70(Request $request)
    {
        $ids = explode(",", $request->query('personal'));
        Carbon::setLocale('es');
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new Language('ES-PE'));
        $protocolo = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $dominio = $_SERVER['HTTP_HOST'];
        //$rutaDominio = $protocolo . '://' . $dominio;
        //$encabezado = $phpWord->addSection(
        //array(
        //'marginTop' => 75 // Ajustar el margen superior (valor en twips)
        //)
        //);
        //configuracion de bordes
        $tableStyle = [
            'borderSize' => 6, // Grosor del borde
            'borderColor' => '000000', // Color negro
            'cellMargin' => 50, // Margen interno de las celdas
        ];
        $tableStyle2 = [
            'borderSize' => 6, // Grosor del borde
            'borderColor' => '000000', // Color negro
            'cellMargin' => 50, // Margen interno de las celdas
            'width' => 100 * 50,
            'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT,
            'layout' => \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED
        ];
        // Configurar los estilos de títulos
        $phpWord->addTitleStyle(1, array('name' => 'Arial', 'size' => 12, 'bold' => true, 'color' => '333333', 'underline' => Font::UNDERLINE_SINGLE), array('alignment' => 'center', 'lineHeight' => 1.5));
        //$phpWord->addTitleStyle(2, array('name' => 'Arial', 'size' => 12, 'bold' => true, 'color' => '666666', 'italic' => true), array('alignment' => 'left'));
        $phpWord->addTableStyle('bordes', $tableStyle);
        $phpWord->addTableStyle('bordes2', $tableStyle2);
        $paragraphStyle = array(
            'tabs' => array(
                new \PhpOffice\PhpWord\Style\Tab('left', 2000), // Primera tabulador a 2 cm
                new \PhpOffice\PhpWord\Style\Tab('left', 4000)  // Segunda tabulador a 4 cm
            )
        );
        // Añadir una sección al documento
        $contenido = $phpWord->addSection();
        // Agregar un título de nivel 1
        $contenido->addTitle('INFORME N° 008-2025-WCB-E-OGRH-MPLC', 1);

        $contenido->addText(
            "A\t:",
            array('bold' => true, 'name' => 'Arial', 'size' => 12),
            $paragraphStyle
        );
        $contenido->addText(
            "DE\t: C.P.C. WILFREDO CAMPAR BAUTISTA",
            array('bold' => true, 'name' => 'Arial', 'size' => 12),
            $paragraphStyle
        );
        $contenido->addText(
            "\t  PROFESIONAL IV - COORDINADOR DE ESCALAFÓN",
            array('bold' => true, 'name' => 'Arial', 'size' => 12),
            $paragraphStyle
        );
        $contenido->addText(
            "ASUNTO\t: REMITO INFORME ESCALAFONARIO",
            array('bold' => true, 'name' => 'Arial', 'size' => 12),
            $paragraphStyle
        );

        $contenido->addText(
            "FECHA\t: Quillabamba," . Carbon::now()->isoFormat(' D [de] MMMM [de] YYYY'),
            array('bold' => true, 'name' => 'Arial', 'size' => 12),
            $paragraphStyle
        );

        $contenido->addText(
            str_repeat('_', 67), // Línea horizontal de texto subrayado
            array('size' => 12), // Tamaño de la línea
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'lineHeight' => 0.05)
        );
        $contenido->addText(
            "\tMediante el presente es grato dirigirme a Ud., con la finalidad de remitir información solicitada según el documento de la referencia, en donde se solicita un informe escalafonario de:",
            array('name' => 'Arial', 'size' => 12)
        );

        foreach ($ids as $id) {
            $personal = Personal::find($id);
            $contenido->addListItem(
                $personal->Nombres . " " . $personal->Apaterno . " " . $personal->Amaterno,
                0,
                null,
                ['bold' => true, 'listType' => 1]
            );
        }
        $contenido->addText(
            "\tAsí mismo debo indicar que la información que se remite es según acervo documentario encontrado en el área de ESCALAFÓN Y File Personal de la Unidad de Recursos Humanos.",
            array('name' => 'Arial', 'size' => 12)
        );
        $contenido->addText(
            "\tAsí mismo debo indicar que la información que se remite es según acervo documentario encontrado en el área de ESCALAFÓN Y File Personal de la Unidad de Recursos Humanos.",
            array('name' => 'Arial', 'size' => 12)
        );
        foreach ($ids as $id) {
            $personal = Personal::find($id);
            $contenido->addTitle($personal->Nombres . " " . $personal->Apaterno . " " . $personal->Amaterno, 1);
            $dentro = [];

            $domicilio = DB::table('domicilio as dom')
                ->join('departamentos', 'dom.iddep', '=', 'departamentos.id')
                ->join('provincias', 'dom.idpro', '=', 'provincias.id')
                ->join('distritos', 'dom.iddis', '=', 'distritos.id')
                ->select('dom.tipodom', 'dom.dactual', 'dom.numero', 'dom.interior', 'distritos.nombre as dis', 'provincias.nombre as pro', 'departamentos.nombre as dep')
                ->where('personal_id', $id)
                ->get();
            $contenido->addTitle("DATOS PERSONALES", 2);
            $table = $contenido->addTable('bordes2');
            $table->addRow();
            $table->addCell(2000)->addText($personal->id_identificacion, ['bold' => true]);
            $table->addCell(4000)->addText($personal->nro_documento_id);
            $table->addRow();
            $table->addCell(2000)->addText("NOMBRES Y APELLIDOS", ['bold' => true]);
            $table->addCell(4000)->addText($personal->Nombres . " " . $personal->Apaterno . " " . $personal->Amaterno);
            $table->addRow();

            $table->addCell(2000)->addText("DOMICILIO", ['bold' => true]);

            if ($domicilio->count() > 0) {
                //$dom=$domicilio[0]->tipodom . " " . $domicilio[0]->dactual . " " . $domicilio[0]->numero . " " . $domicilio[0]->interior . ", " . $domicilio[0]->dis . ", ". $domicilio[0]->pro . ", " . $domicilio[0]->dep;
                $table->addCell(4000)->addText(substr($domicilio[0]->tipodom . " " . $domicilio[0]->dactual . " " . $domicilio[0]->numero . " " . $domicilio[0]->interior . ", " . $domicilio[0]->dis . ", " . $domicilio[0]->pro . ", " . $domicilio[0]->dep, 0, 40));
            } else {
                $table->addCell(4000)->addText('-');
            }

            $table->addRow();
            $table->addCell(2000)->addText("FECHA NAC.", ['bold' => true]);
            $table->addCell(4000)->addText("" . Carbon::parse($personal->FechaNacimiento)->format('d-m-Y'));


            $fechaNacimiento = Carbon::parse($personal->FechaNacimiento);
            $hoy = Carbon::now();
            $diff = $fechaNacimiento->diff($hoy);
            $table->addRow();
            $table->addCell(2000)->addText("EDAD A LA FECHA", ['bold' => true]);
            $table->addCell(4000)->addText("Edad: {$diff->y} año(s), {$diff->m} mes(es), {$diff->d} día(s)");
            $table->addRow();

            $table->addRow();
            $table->addCell(2000)->addText("SEXO", ['bold' => true]);
            $table->addCell(4000)->addText($personal->sexo == 'M' ? 'MASCULINO' : 'FEMENINO');
            $table->addRow();
            $table->addCell(2000)->addText("ESTADO CIVIL", ['bold' => true]);
            $table->addCell(4000)->addText($personal->EstadoCivil ?? '-');
            $table->addRow();
            $table->addCell(2000)->addText("CELULAR", ['bold' => true]);
            $table->addCell(4000)->addText($personal->Celular ?? '-');
            $table->addRow();
            $table->addCell(2000)->addText("CORREO", ['bold' => true]);
            $table->addCell(4000)->addText($personal->Correo ?? '-');
            $table->addRow();
            $table->addCell(2000)->addText("DISCAPACIDAD", ['bold' => true]);
            $table->addCell(4000)->addText($personal->discapacidad ?? '-');
            $table->addRow();
            $table->addCell(2000)->addText("VÍNCULO", ['bold' => true]);
            $table->addCell(4000)->addText($personal->id_tipo_personal == 1 ? 'Activo' : ($personal->id_tipo_personal == 2 ? 'SIN VINCULO' : ($personal->id_tipo_personal == 3 ? 'PENSIONISTA' : '-')));

            
            $contenido->addTitle("SOBRE EL VÍNCULO CON LA INSTITUCIÓN", 2);
            $vinculos = DB::table('vinculos as v')
                ->leftJoin('cargo as c', 'v.id_cargo', '=', 'c.id')
                ->leftJoin('area as a', 'v.id_unidad_organica', '=', 'a.id')
                ->leftJoin('regimen as r', 'v.id_regimen', '=', 'r.id')
                ->leftJoin('motivo_fin_vinculo as m', 'v.id_motivo_fin_vinculo', '=', 'm.id')
                ->leftJoin('tipodoc as ii', 'v.id_tipo_documento', '=', 'ii.id')
                ->leftJoin('tipodoc as if', 'v.id_tipo_documento_fin', '=', 'if.id')
                ->select('*', 'v.id', 'c.nombre as cargo', 'a.nombre as area', 'r.nombre as regimen', 'm.nombre as motivofin', 'ii.nombre as docinicio', 'if.nombre as docfin')
                ->where('v.personal_id', $id)->get();
            if ($vinculos->count() > 0) {

                $vin = $contenido->addTable('bordes');
                $vin->addRow();
                $vin->addCell(1500)->addText('CARGO', ['bold' => true]);
                $vin->addCell(1500)->addText('AREA', ['bold' => true]);
                $vin->addCell(1500)->addText('REGIMEN', ['bold' => true]);
                $vin->addCell(1500)->addText('INICIO', ['bold' => true]);
                $vin->addCell(1500)->addText('FIN', ['bold' => true]);
                $vin->addCell(1500)->addText('TIEMPO DE SERVICIO', ['bold' => true]);
                foreach ($vinculos as $vinculo) {
                    $vin->addRow();
                    $vin->addCell(1500)->addText($vinculo->cargo ?? '-');
                    $vin->addCell(1500)->addText($vinculo->area ?? '-');
                    $vin->addCell(1500)->addText($vinculo->regimen ?? '-');
                    $vin->addCell(1500)->addText($vinculo->fecha_ini ?? '-');
                    $vin->addCell(1500)->addText($vinculo->fecha_fin ?? '-');

                    $inicio = Carbon::parse($vinculo->fecha_ini);
                    $fin = $vinculo->fecha_fin ? Carbon::parse($vinculo->fecha_fin) : Carbon::now();

                    $diff = $inicio->diff($fin);
                    $vin->addCell(1500)->addText($diff->y . " año(s), " . $diff->m . " mes(es), " . $diff->d . " día(s)");
                }
                Log::debug($vinculos);
                if (count($dentro) > 0) {
                    foreach ($vinculos as $vinculo) {
                        $contenido->addTitle("Sobre el vínculo con el cargo de " . $vinculo->cargo . " que inicia el " . $vinculo->fecha_ini . " hasta " . ($vinculo->fecha_fin ?? 'HOY') . ', se ha encontrado los siguientes registros. ' . $vinculo->id . ' id', 3);
                        foreach ($dentro as $idinforme) {


                            $contenido->addTitle("SUS LICENCIAS", 4);
                            $licencias = DB::table('licencias')
                                ->where('idvinculo', $vinculo->id)->get();
                            if ($licencias->count() > 0) {
                                $tablalic = $contenido->addTable('bordes');
                                $tablalic->addRow();
                                $tablalic->addCell(1500)->addText("DOCUMENTO", ['bold' => true]);
                                $tablalic->addCell(1500)->addText("DESCRIPCIÓN", ['bold' => true]);
                                $tablalic->addCell(1500)->addText("PERIODO", ['bold' => true]);
                                $tablalic->addCell(1500)->addText("FECHA DE INICIO", ['bold' => true]);
                                $tablalic->addCell(1500)->addText("FECHA FIN", ['bold' => true]);
                                $tablalic->addCell(1500)->addText("TIEMPO", ['bold' => true]);
                                $tablalic->addCell(1500)->addText("CON GOCE", ['bold' => true]);
                                $tablalic->addCell(1500)->addText("A CUENTA VAC.", ['bold' => true]);
                                foreach ($licencias as $licencia) {
                                    $tablalic->addRow();
                                    $tablalic->addCell(1500)->addText($licencia->nombredoc . $licencia->nrodoc);
                                    $tablalic->addCell(1500)->addText($licencia->descripcion);
                                    $tablalic->addCell(1500)->addText($licencia->periodo);
                                    $tablalic->addCell(1500)->addText($licencia->fecha_ini);
                                    $tablalic->addCell(1500)->addText($licencia->fecha_fin);
                                    $tablalic->addCell(1500)->addText($licencia->dias . "D " . $licencia->mes . "M " . $licencia->anio . "A");
                                    $tablalic->addCell(1500)->addText(($licencia->congoce == 0 ? 'NO' : 'SI'));
                                    $tablalic->addCell(1500)->addText(($licencia->acuentavac == 0 ? 'NO' : 'SI'));
                                }
                            } else {
                                $contenido->addText(
                                    "\tNo hay datos registrados",
                                    array('name' => 'Arial', 'size' => 12)
                                );
                            }


                            $contenido->addTitle("SUS PERMISOS", 4);
                            $permisos = DB::table('permisos')
                                ->where('idvinculo', $vinculo->id)->get();
                            if ($permisos->count() > 0) {
                                $tablalic = $contenido->addTable('bordes');
                                $tablalic->addRow();
                                $tablalic->addCell(1500)->addText("DOCUMENTO", ['bold' => true]);
                                $tablalic->addCell(1500)->addText("MOTIVO", ['bold' => true]);
                                $tablalic->addCell(1500)->addText("PERIODO", ['bold' => true]);
                                $tablalic->addCell(1500)->addText("FECHA DE INICIO", ['bold' => true]);
                                $tablalic->addCell(1500)->addText("FECHA FIN", ['bold' => true]);
                                $tablalic->addCell(1500)->addText("TIEMPO", ['bold' => true]);
                                $tablalic->addCell(1500)->addText("CON GOCE", ['bold' => true]);
                                $tablalic->addCell(1500)->addText("A CUENTA VAC.", ['bold' => true]);
                                foreach ($permisos as $permiso) {
                                    $tablalic->addRow();
                                    $tablalic->addCell(1500)->addText($permiso->nombredoc . '  ' . $permiso->nrodoc);
                                    $tablalic->addCell(1500)->addText($permiso->descripcion);
                                    $tablalic->addCell(1500)->addText($permiso->periodo);
                                    $tablalic->addCell(1500)->addText($permiso->fecha_ini);
                                    $tablalic->addCell(1500)->addText($permiso->fecha_fin);
                                    $tablalic->addCell(1500)->addText($permiso->dias . "D " . $permiso->mes . "M " . $permiso->anio . "A");
                                    $tablalic->addCell(1500)->addText(($permiso->congoce == 0 ? 'NO' : 'SI'));
                                    $tablalic->addCell(1500)->addText(($permiso->acuentavac == 0 ? 'NO' : 'SI'));
                                }
                            } else {
                                $contenido->addText(
                                    "\tNo hay datos registrados",
                                    array('name' => 'Arial', 'size' => 12)
                                );
                            }




                            $contenido->addTitle("SUS SANCIONES", 4);
                            $sanciones = DB::table("sanciones")
                                ->where('idvinculo', $vinculo->id)->get();
                            if ($sanciones->count() > 0) {
                                $tablaenc = $contenido->addTable('bordes');
                                $tablaenc->addRow();
                                $tablaenc->addCell(1500)->addText("FORMA", ['bold' => true]);
                                $tablaenc->addCell(1500)->addText("MOTIVO", ['bold' => true]);
                                $tablaenc->addCell(1500)->addText("DOCUMENTO", ['bold' => true]);
                                $tablaenc->addCell(1500)->addText("F. DE LA SANCIÓN", ['bold' => true]);
                                $tablaenc->addCell(1500)->addText("FECHA INICIO", ['bold' => true]);
                                $tablaenc->addCell(1500)->addText("FECHA FIN", ['bold' => true]);
                                $tablaenc->addCell(1500)->addText("DÍAS", ['bold' => true]);

                                foreach ($sanciones as $sancion) {
                                    $tablaenc->addRow();
                                    $tablaenc->addCell(1500)->addText($sancion->tiposancion == 1 ? 'Al tiempo de servicio' : 'Sólo a la remuneración');
                                    $tablaenc->addCell(1500)->addText($sancion->descripcion);
                                    $tablaenc->addCell(1500)->addText($sancion->nombredoc . ' ' . $sancion->nrodoc);

                                    $tablaenc->addCell(1500)->addText($sancion->fechadoc);
                                    $tablaenc->addCell(1500)->addText($sancion->fecha_ini);
                                    $tablaenc->addCell(1500)->addText($sancion->fecha_fin ?? 'HOY');
                                    $tablaenc->addCell(1500)->addText($sancion->dias_san);
                                }
                            } else {
                                $contenido->addText(
                                    "\tNo hay datos registrados",
                                    array('name' => 'Arial', 'size' => 12)
                                );
                            }


                            $contenido->addTitle("SUS VACACIONES DEL PERIODO", 4);
                            $vacaciones = DB::table("vacaciones")
                                ->where('idvinculo', $vinculo->id)->get();
                            if ($vacaciones->count() > 0) {
                                $tablavac = $contenido->addTable('bordes');
                                $tablavac->addRow();
                                $tablavac->addCell(1500)->addText("DOCUMENTO", ['bold' => true]);
                                $tablavac->addCell(1500)->addText("PERIODO", ['bold' => true]);
                                $tablavac->addCell(1500)->addText("INICIO", ['bold' => true]);
                                $tablavac->addCell(1500)->addText("FIN", ['bold' => true]);
                                $tablavac->addCell(1500)->addText("DÍAS", ['bold' => true]);
                                foreach ($vacaciones as $vacacion) {
                                    $tablavac->addRow();
                                    $tablavac->addCell(1500)->addText($vacacion->nombredoc . ' ' . $vacacion->nrodoc);

                                    $tablavac->addCell(1500)->addText($vacacion->periodo);
                                    $tablavac->addCell(1500)->addText($vacacion->fecha_ini);
                                    $tablavac->addCell(1500)->addText($vacacion->fecha_fin ?? 'HOY');
                                    $tablavac->addCell(1500)->addText($vacacion->dias);
                                }
                            } else {
                                $contenido->addText(
                                    "\tNo hay datos registrados",
                                    array('name' => 'Arial', 'size' => 12)
                                );
                            }
                        }
                    }
                }
            } else {
                $contenido->addText(
                    "\tNo hay datos registrados",
                    array('name' => 'Arial', 'size' => 12)
                );
            }
        }
        $contenido->addText(
            "\tEs todo en cuanto debo informar.",
            array('name' => 'Arial', 'size' => 12)
        );
        $contenido->addText(
            "\tAtentamente",
            array('name' => 'Arial', 'size' => 12)
        );




        // Guardar el documento temporalmente
        $fileName = 'informe70.docx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $phpWord->save($tempFile, 'Word2007');

        // Descargar el archivo
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
    public function informe30(Request $request)
    {
        $ids = explode(",", $request->query('personal'));
        Carbon::setLocale('es');
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new Language('ES-PE'));
        $protocolo = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $dominio = $_SERVER['HTTP_HOST'];
        //$rutaDominio = $protocolo . '://' . $dominio;
        //$encabezado = $phpWord->addSection(
        //array(
        //'marginTop' => 75 // Ajustar el margen superior (valor en twips)
        //)
        //);
        //configuracion de bordes
        $tableStyle = [
            'borderSize' => 6, // Grosor del borde
            'borderColor' => '000000', // Color negro
            'cellMargin' => 50, // Margen interno de las celdas
        ];
        $tableStyle2 = [
            'borderSize' => 6, // Grosor del borde
            'borderColor' => '000000', // Color negro
            'cellMargin' => 50, // Margen interno de las celdas
            'width' => 100 * 50,
            'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT,
            'layout' => \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED
        ];
        // Configurar los estilos de títulos
        $phpWord->addTitleStyle(1, array('name' => 'Arial', 'size' => 12, 'bold' => true, 'color' => '333333', 'underline' => Font::UNDERLINE_SINGLE), array('alignment' => 'center', 'lineHeight' => 1.5));
        //$phpWord->addTitleStyle(2, array('name' => 'Arial', 'size' => 12, 'bold' => true, 'color' => '666666', 'italic' => true), array('alignment' => 'left'));
        $phpWord->addTableStyle('bordes', $tableStyle);
        $phpWord->addTableStyle('bordes2', $tableStyle2);
        $paragraphStyle = array(
            'tabs' => array(
                new \PhpOffice\PhpWord\Style\Tab('left', 2000), // Primera tabulador a 2 cm
                new \PhpOffice\PhpWord\Style\Tab('left', 4000)  // Segunda tabulador a 4 cm
            )
        );
        // Añadir una sección al documento
        $contenido = $phpWord->addSection();
        // Agregar un título de nivel 1
        $contenido->addTitle('INFORME N° 008-2025-WCB-E-OGRH-MPLC', 1);

        $contenido->addText(
            "A\t:",
            array('bold' => true, 'name' => 'Arial', 'size' => 12),
            $paragraphStyle
        );
        $contenido->addText(
            "DE\t: C.P.C. WILFREDO CAMPAR BAUTISTA",
            array('bold' => true, 'name' => 'Arial', 'size' => 12),
            $paragraphStyle
        );
        $contenido->addText(
            "\t  PROFESIONAL IV - COORDINADOR DE ESCALAFÓN",
            array('bold' => true, 'name' => 'Arial', 'size' => 12),
            $paragraphStyle
        );
        $contenido->addText(
            "ASUNTO\t: REMITO INFORME ESCALAFONARIO",
            array('bold' => true, 'name' => 'Arial', 'size' => 12),
            $paragraphStyle
        );

        $contenido->addText(
            "FECHA\t: Quillabamba," . Carbon::now()->isoFormat(' D [de] MMMM [de] YYYY'),
            array('bold' => true, 'name' => 'Arial', 'size' => 12),
            $paragraphStyle
        );

        $contenido->addText(
            str_repeat('_', 67), // Línea horizontal de texto subrayado
            array('size' => 12), // Tamaño de la línea
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'lineHeight' => 0.05)
        );
        $contenido->addText(
            "\tMediante el presente es grato dirigirme a Ud., con la finalidad de remitir información solicitada según el documento de la referencia, en donde se solicita un informe escalafonario de:",
            array('name' => 'Arial', 'size' => 12)
        );

        foreach ($ids as $id) {
            $personal = Personal::find($id);
            $contenido->addListItem(
                $personal->Nombres . " " . $personal->Apaterno . " " . $personal->Amaterno,
                0,
                null,
                ['bold' => true, 'listType' => 1]
            );
        }
        $contenido->addText(
            "\tAsí mismo debo indicar que la información que se remite es según acervo documentario encontrado en el área de ESCALAFÓN Y File Personal de la Unidad de Recursos Humanos.",
            array('name' => 'Arial', 'size' => 12)
        );
        $contenido->addText(
            "\tAsí mismo debo indicar que la información que se remite es según acervo documentario encontrado en el área de ESCALAFÓN Y File Personal de la Unidad de Recursos Humanos.",
            array('name' => 'Arial', 'size' => 12)
        );
        foreach ($ids as $id) {
            $personal = Personal::find($id);
            $contenido->addTitle($personal->Nombres . " " . $personal->Apaterno . " " . $personal->Amaterno, 1);
            $dentro = [];

            $domicilio = DB::table('domicilio as dom')
                ->join('departamentos', 'dom.iddep', '=', 'departamentos.id')
                ->join('provincias', 'dom.idpro', '=', 'provincias.id')
                ->join('distritos', 'dom.iddis', '=', 'distritos.id')
                ->select('dom.tipodom', 'dom.dactual', 'dom.numero', 'dom.interior', 'distritos.nombre as dis', 'provincias.nombre as pro', 'departamentos.nombre as dep')
                ->where('personal_id', $id)
                ->get();
            $contenido->addTitle("DATOS PERSONALES", 2);
            $table = $contenido->addTable('bordes2');
            $table->addRow();
            $table->addCell(2000)->addText($personal->id_identificacion, ['bold' => true]);
            $table->addCell(4000)->addText($personal->nro_documento_id);
            $table->addRow();
            $table->addCell(2000)->addText("NOMBRES Y APELLIDOS", ['bold' => true]);
            $table->addCell(4000)->addText($personal->Nombres . " " . $personal->Apaterno . " " . $personal->Amaterno);
            $table->addRow();

            $table->addCell(2000)->addText("DOMICILIO", ['bold' => true]);

            if ($domicilio->count() > 0) {
                //$dom=$domicilio[0]->tipodom . " " . $domicilio[0]->dactual . " " . $domicilio[0]->numero . " " . $domicilio[0]->interior . ", " . $domicilio[0]->dis . ", ". $domicilio[0]->pro . ", " . $domicilio[0]->dep;
                $table->addCell(4000)->addText(substr($domicilio[0]->tipodom . " " . $domicilio[0]->dactual . " " . $domicilio[0]->numero . " " . $domicilio[0]->interior . ", " . $domicilio[0]->dis . ", " . $domicilio[0]->pro . ", " . $domicilio[0]->dep, 0, 40));
            } else {
                $table->addCell(4000)->addText('-');
            }
            $table->addRow();
            $table->addCell(2000)->addText("CELULAR", ['bold' => true]);
            $table->addCell(4000)->addText($personal->Celular ?? '-');
            $table->addRow();
            $table->addCell(2000)->addText("CORREO", ['bold' => true]);
            $table->addCell(4000)->addText($personal->Correo ?? '-');

            Log::debug("id vinculo: " . $id);
            $contenido->addTitle("SOBRE EL VÍNCULO CON LA INSTITUCIÓN", 2);
            $vinculos = DB::table('vinculos as v')
                ->leftJoin('cargo as c', 'v.id_cargo', '=', 'c.id')
                ->leftJoin('area as a', 'v.id_unidad_organica', '=', 'a.id')
                ->leftJoin('regimen as r', 'v.id_regimen', '=', 'r.id')
                ->leftJoin('motivo_fin_vinculo as m', 'v.id_motivo_fin_vinculo', '=', 'm.id')
                ->leftJoin('tipodoc as ii', 'v.id_tipo_documento', '=', 'ii.id')
                ->leftJoin('tipodoc as if', 'v.id_tipo_documento_fin', '=', 'if.id')
                ->select('*', 'v.id', 'c.nombre as cargo', 'a.nombre as area', 'r.nombre as regimen', 'm.nombre as motivofin', 'ii.nombre as docinicio', 'if.nombre as docfin')
                ->where('v.personal_id', $id)->get();
            if ($vinculos->count() > 0) {

                $vin = $contenido->addTable('bordes');
                $vin->addRow();
                $vin->addCell(1500)->addText('CARGO', ['bold' => true]);
                $vin->addCell(1500)->addText('AREA', ['bold' => true]);
                $vin->addCell(1500)->addText('REGIMEN', ['bold' => true]);
                $vin->addCell(1500)->addText('INICIO', ['bold' => true]);
                $vin->addCell(1500)->addText('FIN', ['bold' => true]);
                $vin->addCell(1500)->addText('TIEMPO DE SERVICIO', ['bold' => true]);
                foreach ($vinculos as $vinculo) {
                    $vin->addRow();
                    $vin->addCell(1500)->addText($vinculo->cargo ?? '-');
                    $vin->addCell(1500)->addText($vinculo->area ?? '-');
                    $vin->addCell(1500)->addText($vinculo->regimen ?? '-');
                    $vin->addCell(1500)->addText($vinculo->fecha_ini ?? '-');
                    $vin->addCell(1500)->addText($vinculo->fecha_fin ?? '-');

                    $inicio = Carbon::parse($vinculo->fecha_ini);
                    $fin = $vinculo->fecha_fin ? Carbon::parse($vinculo->fecha_fin) : Carbon::now();

                    $diff = $inicio->diff($fin);
                    $vin->addCell(1500)->addText($diff->y . " año(s), " . $diff->m . " mes(es), " . $diff->d . " día(s)");
                }
                Log::debug($vinculos);
                if (count($dentro) > 0) {
                    foreach ($vinculos as $vinculo) {
                        $contenido->addTitle("Sobre el vínculo con el cargo de " . $vinculo->cargo . " que inicia el " . $vinculo->fecha_ini . " hasta " . ($vinculo->fecha_fin ?? 'HOY') . ', se ha encontrado los siguientes registros. ' . $vinculo->id . ' id', 3);

                        $contenido->addTitle("SUS LICENCIAS", 4);
                        $licencias = DB::table('licencias')
                            ->where('idvinculo', $vinculo->id)->get();
                        if ($licencias->count() > 0) {
                            $tablalic = $contenido->addTable('bordes');
                            $tablalic->addRow();
                            $tablalic->addCell(1500)->addText("DOCUMENTO", ['bold' => true]);
                            $tablalic->addCell(1500)->addText("DESCRIPCIÓN", ['bold' => true]);
                            $tablalic->addCell(1500)->addText("PERIODO", ['bold' => true]);
                            $tablalic->addCell(1500)->addText("FECHA DE INICIO", ['bold' => true]);
                            $tablalic->addCell(1500)->addText("FECHA FIN", ['bold' => true]);
                            $tablalic->addCell(1500)->addText("TIEMPO", ['bold' => true]);
                            $tablalic->addCell(1500)->addText("CON GOCE", ['bold' => true]);
                            $tablalic->addCell(1500)->addText("A CUENTA VAC.", ['bold' => true]);
                            foreach ($licencias as $licencia) {
                                $tablalic->addRow();
                                $tablalic->addCell(1500)->addText($licencia->nombredoc . $licencia->nrodoc);
                                $tablalic->addCell(1500)->addText($licencia->descripcion);
                                $tablalic->addCell(1500)->addText($licencia->periodo);
                                $tablalic->addCell(1500)->addText($licencia->fecha_ini);
                                $tablalic->addCell(1500)->addText($licencia->fecha_fin);
                                $tablalic->addCell(1500)->addText($licencia->dias . "D " . $licencia->mes . "M " . $licencia->anio . "A");
                                $tablalic->addCell(1500)->addText(($licencia->congoce == 0 ? 'NO' : 'SI'));
                                $tablalic->addCell(1500)->addText(($licencia->acuentavac == 0 ? 'NO' : 'SI'));
                            }
                        } else {
                            $contenido->addText(
                                "\tNo hay datos registrados",
                                array('name' => 'Arial', 'size' => 12)
                            );
                        }

                        $contenido->addTitle("SUS PERMISOS", 4);
                        $permisos = DB::table('permisos')
                            ->where('idvinculo', $vinculo->id)->get();
                        if ($permisos->count() > 0) {
                            $tablalic = $contenido->addTable('bordes');
                            $tablalic->addRow();
                            $tablalic->addCell(1500)->addText("DOCUMENTO", ['bold' => true]);
                            $tablalic->addCell(1500)->addText("MOTIVO", ['bold' => true]);
                            $tablalic->addCell(1500)->addText("PERIODO", ['bold' => true]);
                            $tablalic->addCell(1500)->addText("FECHA DE INICIO", ['bold' => true]);
                            $tablalic->addCell(1500)->addText("FECHA FIN", ['bold' => true]);
                            $tablalic->addCell(1500)->addText("TIEMPO", ['bold' => true]);
                            $tablalic->addCell(1500)->addText("CON GOCE", ['bold' => true]);
                            $tablalic->addCell(1500)->addText("A CUENTA VAC.", ['bold' => true]);
                            foreach ($permisos as $permiso) {
                                $tablalic->addRow();
                                $tablalic->addCell(1500)->addText($permiso->nombredoc . '  ' . $permiso->nrodoc);
                                $tablalic->addCell(1500)->addText($permiso->descripcion);
                                $tablalic->addCell(1500)->addText($permiso->periodo);
                                $tablalic->addCell(1500)->addText($permiso->fecha_ini);
                                $tablalic->addCell(1500)->addText($permiso->fecha_fin);
                                $tablalic->addCell(1500)->addText($permiso->dias . "D " . $permiso->mes . "M " . $permiso->anio . "A");
                                $tablalic->addCell(1500)->addText(($permiso->congoce == 0 ? 'NO' : 'SI'));
                                $tablalic->addCell(1500)->addText(($permiso->acuentavac == 0 ? 'NO' : 'SI'));
                            }
                        } else {
                            $contenido->addText(
                                "\tNo hay datos registrados",
                                array('name' => 'Arial', 'size' => 12)
                            );
                        }

                        $contenido->addTitle("SUS SANCIONES", 4);
                        $sanciones = DB::table("sanciones")
                            ->where('idvinculo', $vinculo->id)->get();
                        if ($sanciones->count() > 0) {
                            $tablaenc = $contenido->addTable('bordes');
                            $tablaenc->addRow();
                            $tablaenc->addCell(1500)->addText("FORMA", ['bold' => true]);
                            $tablaenc->addCell(1500)->addText("MOTIVO", ['bold' => true]);
                            $tablaenc->addCell(1500)->addText("DOCUMENTO", ['bold' => true]);
                            $tablaenc->addCell(1500)->addText("F. DE LA SANCIÓN", ['bold' => true]);
                            $tablaenc->addCell(1500)->addText("FECHA INICIO", ['bold' => true]);
                            $tablaenc->addCell(1500)->addText("FECHA FIN", ['bold' => true]);
                            $tablaenc->addCell(1500)->addText("DÍAS", ['bold' => true]);

                            foreach ($sanciones as $sancion) {
                                $tablaenc->addRow();
                                $tablaenc->addCell(1500)->addText($sancion->tiposancion == 1 ? 'Al tiempo de servicio' : 'Sólo a la remuneración');
                                $tablaenc->addCell(1500)->addText($sancion->descripcion);
                                $tablaenc->addCell(1500)->addText($sancion->nombredoc . ' ' . $sancion->nrodoc);

                                $tablaenc->addCell(1500)->addText($sancion->fechadoc);
                                $tablaenc->addCell(1500)->addText($sancion->fecha_ini);
                                $tablaenc->addCell(1500)->addText($sancion->fecha_fin ?? 'HOY');
                                $tablaenc->addCell(1500)->addText($sancion->dias_san);
                            }
                        } else {
                            $contenido->addText(
                                "\tNo hay datos registrados",
                                array('name' => 'Arial', 'size' => 12)
                            );
                        }


                        $contenido->addTitle("SUS VACACIONES SEGÚN PERIODO", 4);
                        $vacaciones = DB::table("vacaciones")
                            ->where('idvinculo', $vinculo->id)->get();
                        if ($vacaciones->count() > 0) {
                            $tablavac = $contenido->addTable('bordes');
                            $tablavac->addRow();
                            $tablavac->addCell(1500)->addText("DOCUMENTO", ['bold' => true]);
                            $tablavac->addCell(1500)->addText("PERIODO", ['bold' => true]);
                            $tablavac->addCell(1500)->addText("INICIO", ['bold' => true]);
                            $tablavac->addCell(1500)->addText("FIN", ['bold' => true]);
                            $tablavac->addCell(1500)->addText("DÍAS", ['bold' => true]);
                            foreach ($vacaciones as $vacacion) {
                                $tablavac->addRow();
                                $tablavac->addCell(1500)->addText($vacacion->nombredoc . ' ' . $vacacion->nrodoc);

                                $tablavac->addCell(1500)->addText($vacacion->periodo);
                                $tablavac->addCell(1500)->addText($vacacion->fecha_ini);
                                $tablavac->addCell(1500)->addText($vacacion->fecha_fin ?? 'HOY');
                                $tablavac->addCell(1500)->addText($vacacion->dias);
                            }
                        } else {
                            $contenido->addText(
                                "\tNo hay datos registrados",
                                array('name' => 'Arial', 'size' => 12)
                            );
                        }
                    }
                }
            } else {
                $contenido->addText(
                    "\tNo hay datos registrados",
                    array('name' => 'Arial', 'size' => 12)
                );
            }
        }
        $contenido->addText(
            "\tEs todo en cuanto debo informar.",
            array('name' => 'Arial', 'size' => 12)
        );
        $contenido->addText(
            "\tAtentamente",
            array('name' => 'Arial', 'size' => 12)
        );




        // Guardar el documento temporalmente
        $fileName = 'informe30.docx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $phpWord->save($tempFile, 'Word2007');

        // Descargar el archivo
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    public function descargarWord(Request $request)
    {
        $ids = explode(",", $request->query('personal'));
        $datos = explode(",", $request->query('datos'));
        Carbon::setLocale('es');
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new Language('ES-PE'));
        $protocolo = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $dominio = $_SERVER['HTTP_HOST'];
        $rutaDominio = $protocolo . '://' . $dominio;
        $encabezado = $phpWord->addSection(
            array(
                'marginTop' => 10 // Ajustar el margen superior (valor en twips)
            )
        );
        //configuracion de bordes
        $tableStyle = [
            'borderSize' => 6, // Grosor del borde
            'borderColor' => '000000', // Color negro
            'cellMargin' => 50, // Margen interno de las celdas
        ];
        $tableStyle2 = [
            'borderSize' => 6, // Grosor del borde
            'borderColor' => '000000', // Color negro
            'cellMargin' => 50, // Margen interno de las celdas
            'width' => 100 * 50,
            'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT,
            'layout' => \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED
        ];
        // Configurar los estilos de títulos
        $phpWord->addTitleStyle(1, array('name' => 'Arial', 'size' => 12, 'bold' => true, 'color' => '333333', 'underline' => Font::UNDERLINE_SINGLE), array('alignment' => 'center', 'lineHeight' => 1.5));
        $phpWord->addTitleStyle(2, array('name' => 'Arial', 'size' => 12, 'bold' => true, 'color' => '666666', 'italic' => true), array('alignment' => 'left'));
        $phpWord->addTableStyle('bordes', $tableStyle);
        $phpWord->addTableStyle('bordes2', $tableStyle2);
        $paragraphStyle = array(
            'tabs' => array(
                new \PhpOffice\PhpWord\Style\Tab('left', 2000), // Primera tabulador a 2 cm
                new \PhpOffice\PhpWord\Style\Tab('left', 4000)  // Segunda tabulador a 4 cm
            )
        );

        // Crear un encabezado
        $header = $encabezado->addHeader();
        $table = $header->addTable(array('alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER, 'lineHeight' => 0.1));
        $table->addRow();
        $cell = $table->addCell(100);
        $cell->addImage(
            $rutaDominio . '/img/logo_informe.png',
            array(
                'width' => 50,
                'height' => 60,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER // Alineación a la izquierda
            )
        );
        $cell2 = $table->addCell(8800);
        $cell2->addText(
            'MUNICIPALIDAD PROVINCIAL DE LA CONVENCIÓN',
            array('bold' => true, 'size' => 13, 'color' => 'green'), // Estilo del texto
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'lineHeight' => 0.9)
        );
        $cell2->addText(
            'OFICINA DE GESTION DE RECURSOS HUMANOS - ESCALAFÓN',
            array('bold' => true, 'size' => 13, 'color' => 'green'), // Estilo del texto
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'lineHeight' => 0.9)
        );
        $cell2->addText(
            str_repeat('_', 55), // Línea horizontal de texto subrayado
            array('size' => 12), // Tamaño de la línea
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'lineHeight' => 0.05)
        );
        $cell2->addText(
            'Año de la Recuperación y la Consolidación de la Economía Peruana',
            array('size' => 10, 'color' => 'green'), // Estilo del texto
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'lineHeight' => 0.8)
        );
        //
        // Añadir una sección al documento
        $contenido = $phpWord->addSection();
        // Agregar un título de nivel 1
        $contenido->addTitle('INFORME N° 008-2025-WCB-E-OGRH-MPLC', 1);

        $contenido->addText(
            "A\t:",
            array('bold' => true, 'name' => 'Arial', 'size' => 12),
            $paragraphStyle
        );
        $contenido->addText(
            "DE\t: C.P.C. WILFREDO CAMPAR BAUTISTA",
            array('bold' => true, 'name' => 'Arial', 'size' => 12),
            $paragraphStyle
        );
        $contenido->addText(
            "\t  PROFESIONAL IV - COORDINADOR DE ESCALAFÓN",
            array('bold' => true, 'name' => 'Arial', 'size' => 12),
            $paragraphStyle
        );
        $contenido->addText(
            "ASUNTO\t: REMITO INFORME ESCALAFONARIO",
            array('bold' => true, 'name' => 'Arial', 'size' => 12),
            $paragraphStyle
        );

        $contenido->addText(
            "FECHA\t: Quillabamba," . Carbon::now()->isoFormat(' D [de] MMMM [de] YYYY'),
            array('bold' => true, 'name' => 'Arial', 'size' => 12),
            $paragraphStyle
        );

        $contenido->addText(
            str_repeat('_', 67), // Línea horizontal de texto subrayado
            array('size' => 12), // Tamaño de la línea
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'lineHeight' => 0.05)
        );
        $contenido->addText(
            "\tMediante el presente es grato dirigirme a Ud., con la finalidad de remitir información solicitada según el documento de la referencia, en donde se solicita un informe escalafonario de:",
            array('name' => 'Arial', 'size' => 12)
        );

        foreach ($ids as $id) {
            $personal = Personal::find($id);
            $contenido->addListItem(
                $personal->Nombres . " " . $personal->Apaterno . " " . $personal->Amaterno,
                0,
                null,
                ['bold' => true, 'listType' => 1]
            );
        }
        $contenido->addText(
            "\tAsí mismo debo indicar que la información que se remite es según acervo documentario encontrado en el área de ESCALAFÓN Y File Personal de la Unidad de Recursos Humanos",
            array('name' => 'Arial', 'size' => 12)
        );
        foreach ($ids as $id) {
            $personal = Personal::find($id);
            $contenido->addTitle($personal->Nombres . " " . $personal->Apaterno . " " . $personal->Amaterno, 1);
            $dentro = [];
            foreach ($datos as $datito) {
                if ($datito > 7)
                    array_push($dentro, $datito);
            }
            foreach ($datos as $dato) {
                if ($dato == 1) {
                    $domicilio = DB::table('domicilio as dom')
                        ->join('departamentos', 'dom.iddep', '=', 'departamentos.id')
                        ->join('provincias', 'dom.idpro', '=', 'provincias.id')
                        ->join('distritos', 'dom.iddis', '=', 'distritos.id')
                        ->select('dom.tipodom', 'dom.dactual', 'dom.numero', 'dom.interior', 'distritos.nombre as dis', 'provincias.nombre as pro', 'departamentos.nombre as dep')
                        ->where('personal_id', $id)
                        ->get();
                    $contenido->addTitle("DATOS PERSONALES", 2);
                    $table = $contenido->addTable('bordes2');
                    $table->addRow();
                    $table->addCell(2000)->addText($personal->id_identificacion, ['bold' => true]);
                    $table->addCell(4000)->addText($personal->nro_documento_id);
                    $table->addRow();
                    $table->addCell(2000)->addText("NOMBRE", ['bold' => true]);
                    $table->addCell(4000)->addText($personal->Nombres . " " . $personal->Apaterno . " " . $personal->Amaterno);
                    $table->addRow();

                    $table->addCell(2000)->addText("FECHA NAC.", ['bold' => true]);
                    $table->addCell(4000)->addText("" . Carbon::parse($personal->FechaNacimiento)->format('d-m-Y'));
                    $table->addRow();
                    $table->addCell(2000)->addText("DOMICILIO", ['bold' => true]);

                    if ($domicilio->count() > 0) {
                        //$dom=$domicilio[0]->tipodom . " " . $domicilio[0]->dactual . " " . $domicilio[0]->numero . " " . $domicilio[0]->interior . ", " . $domicilio[0]->dis . ", ". $domicilio[0]->pro . ", " . $domicilio[0]->dep;
                        $table->addCell(4000)->addText(substr($domicilio[0]->tipodom . " " . $domicilio[0]->dactual . " " . $domicilio[0]->numero . " " . $domicilio[0]->interior . ", " . $domicilio[0]->dis . ", " . $domicilio[0]->pro . ", " . $domicilio[0]->dep, 0, 40));
                    } else {
                        $table->addCell(4000)->addText('-');
                    }
                    $table->addRow();
                    $table->addCell(2000)->addText("SEXO", ['bold' => true]);
                    $table->addCell(4000)->addText($personal->sexo == 'M' ? 'MASCULINO' : 'FEMENINO');
                    $table->addRow();
                    $table->addCell(2000)->addText("ESTADO CIVIL", ['bold' => true]);
                    $table->addCell(4000)->addText($personal->EstadoCivil ?? '-');
                    $table->addRow();
                    $table->addCell(2000)->addText("CELULAR", ['bold' => true]);
                    $table->addCell(4000)->addText($personal->Celular ?? '-');
                    $table->addRow();
                    $table->addCell(2000)->addText("CORREO", ['bold' => true]);
                    $table->addCell(4000)->addText($personal->Correo ?? '-');
                    $table->addRow();
                    $table->addCell(2000)->addText("DISCAPACIDAD", ['bold' => true]);
                    $table->addCell(4000)->addText($personal->discapacidad ?? '-');
                    $table->addRow();
                    $table->addCell(2000)->addText("VÍNCULO", ['bold' => true]);
                    $table->addCell(4000)->addText($personal->id_tipo_personal == 1 ? 'Activo' : ($personal->id_tipo_personal == 2 ? 'SIN VINCULO' : ($personal->id_tipo_personal == 3 ? 'PENSIONISTA' : '-')));
                }
                if ($dato == 2) {
                    $contenido->addTitle("DATOS FAMILIARES", 2);
                    $familia = DB::table('familiares')->where('personal_id', $id)->get();
                    if ($familia->count() > 0) {
                        $familiares = $contenido->addTable('bordes');
                        $familiares->addRow();
                        $familiares->addCell(4000)->addText('APELLIDOS Y NOMBRES', ['bold' => true]);
                        $familiares->addCell(2000)->addText('PARENTESCO', ['bold' => true]);
                        $familiares->addCell(2000)->addText('FECHA NAC.', ['bold' => true]);
                        $familiares->addCell(2000)->addText('OCUPACIÓN', ['bold' => true]);
                        $familiares->addCell(2000)->addText('DIRECCIÓN', ['bold' => true]);
                        $familiares->addCell(2000)->addText('TELÉFONO', ['bold' => true]);
                        foreach ($familia as $familiar) {
                            $familiares->addRow();
                            $familiares->addCell(4000)->addText($familiar->apaterno . ' ' . $familiar->amaterno . ' ' . $familiar->amaterno);
                            $familiares->addCell(2000)->addText($familiar->parentesco ?? '-');
                            $familiares->addCell(2000)->addText($familiar->fechanacimiento ?? '-');
                            $familiares->addCell(2000)->addText($familiar->ocupacion ?? '-');
                            $familiares->addCell(2000)->addText($familiar->direccion ?? '-');
                            $familiares->addCell(2000)->addText($familiar->telefono ?? '-');
                        }
                    } else {
                        $contenido->addText(
                            "\tNo hay datos registrados",
                            array('name' => 'Arial', 'size' => 12)
                        );
                    }
                }
                if ($dato == 3) {
                    Log::debug("id vinculo: " . $id);
                    $contenido->addTitle("SOBRE EL VÍNCULO CON LA INSTITUCIÓN", 2);
                    $vinculos = DB::table('vinculos as v')
                        ->leftJoin('cargo as c', 'v.id_cargo', '=', 'c.id')
                        ->leftJoin('area as a', 'v.id_unidad_organica', '=', 'a.id')
                        ->leftJoin('regimen as r', 'v.id_regimen', '=', 'r.id')
                        ->leftJoin('motivo_fin_vinculo as m', 'v.id_motivo_fin_vinculo', '=', 'm.id')
                        ->leftJoin('tipodoc as ii', 'v.id_tipo_documento', '=', 'ii.id')
                        ->leftJoin('tipodoc as if', 'v.id_tipo_documento_fin', '=', 'if.id')
                        ->select('*', 'v.id', 'c.nombre as cargo', 'a.nombre as area', 'r.nombre as regimen', 'm.nombre as motivofin', 'ii.nombre as docinicio', 'if.nombre as docfin')
                        ->where('v.personal_id', $id)->get();
                    if ($vinculos->count() > 0) {

                        $vin = $contenido->addTable('bordes');
                        $vin->addRow();
                        $vin->addCell(1500)->addText('CARGO', ['bold' => true]);
                        $vin->addCell(1500)->addText('AREA', ['bold' => true]);
                        $vin->addCell(1500)->addText('INICIO', ['bold' => true]);
                        $vin->addCell(1500)->addText('NRO. DOC.', ['bold' => true]);
                        $vin->addCell(1500)->addText('REGIMEN', ['bold' => true]);
                        $vin->addCell(1500)->addText('FIN', ['bold' => true]);
                        $vin->addCell(1500)->addText('MOTIVO', ['bold' => true]);
                        $vin->addCell(1500)->addText('NRO. DOC.', ['bold' => true]);
                        foreach ($vinculos as $vinculo) {
                            $vin->addRow();
                            $vin->addCell(1500)->addText($vinculo->cargo ?? '-');
                            $vin->addCell(1500)->addText($vinculo->area ?? '-');
                            $vin->addCell(1500)->addText($vinculo->fecha_ini ?? '-');
                            $vin->addCell(1500)->addText(($vinculo->docinicio ?? '-') . ": " . ($vinculo->nro_doc ?? '-'));
                            $vin->addCell(1500)->addText($vinculo->regimen ?? '-');
                            $vin->addCell(1500)->addText($vinculo->fecha_fin ?? '-');
                            $vin->addCell(1500)->addText($vinculo->motivo ?? '-');
                            $vin->addCell(1500)->addText(($vinculo->docfin ?? '-') . ": ", $vinculo->nro_doc_fin);
                        }
                        Log::debug($vinculos);
                        if (count($dentro) > 0) {
                            foreach ($vinculos as $vinculo) {
                                $contenido->addTitle("Sobre el vínculo con el cargo de " . $vinculo->cargo . " que inicia el " . $vinculo->fecha_ini . " hasta " . ($vinculo->fecha_fin ?? 'HOY') . ', se ha encontrado los siguientes registros. ' . $vinculo->id . ' id', 3);
                                foreach ($dentro as $idinforme) {
                                    if ($idinforme == 9) {
                                        $contenido->addTitle("SUS ROTACIONES", 4);
                                        $rotaciones = DB::table('movimientos as v')
                                            ->select('*', 'c.nombre as cargo', 'a.nombre as area')
                                            ->join('cargo as c', 'v.cargo', '=', 'c.id')
                                            ->join('area as a', 'v.unidad_organica_destino', '=', 'a.id')
                                            ->where('v.tipo', 0)
                                            ->where('v.idvinculo', $vinculo->id)->get();
                                        if ($rotaciones->count() > 0) {
                                            $tablarot = $contenido->addTable('bordes');
                                            $tablarot->addRow();
                                            $tablarot->addCell(1500)->addText("AREA", ['bold' => true]);
                                            $tablarot->addCell(1500)->addText("CARGO", ['bold' => true]);
                                            $tablarot->addCell(1500)->addText("DESCRIPCIÓN", ['bold' => true]);
                                            $tablarot->addCell(1500)->addText("PERIODO", ['bold' => true]);
                                            $tablarot->addCell(1500)->addText("DOC. INICIO", ['bold' => true]);
                                            $tablarot->addCell(1500)->addText("DOC. FIN", ['bold' => true]);
                                            foreach ($rotaciones as $rotacion) {
                                                $tablarot->addRow();
                                                $tablarot->addCell(1500)->addText($rotacion->area);
                                                $tablarot->addCell(1500)->addText($rotacion->cargo);
                                                $tablarot->addCell(1500)->addText($rotacion->descripcion);
                                                $tablarot->addCell(1500)->addText("" . $rotacion->fecha_ini . " - " . ($rotacion->fecha_fin ?? 'HOY'));
                                                $tablarot->addCell(1500)->addText($rotacion->nombredoc . " " . $rotacion->nrodoc);
                                                $tablarot->addCell(1500)->addText(($rotacion->nombredocfin ?? '-'));
                                            }
                                        } else {
                                            $contenido->addText(
                                                "\tNo hay datos registrados",
                                                array('name' => 'Arial', 'size' => 12)
                                            );
                                        }
                                    }
                                    if ($idinforme == 10) {
                                        $contenido->addTitle("SUS ENCARGATURAS", 4);
                                        $encargaturas = DB::table('movimientos as v')
                                            ->select('*', 'c.nombre as cargo', 'a.nombre as area')
                                            ->join('cargo as c', 'v.cargo', '=', 'c.id')
                                            ->join('area as a', 'v.unidad_organica_destino', '=', 'a.id')
                                            ->where('v.tipo', 1)
                                            ->where('v.idvinculo', $vinculo->id)->get();
                                        if ($encargaturas->count() > 0) {
                                            $tablaenc = $contenido->addTable('bordes');
                                            $tablaenc->addRow();
                                            $tablaenc->addCell(1500)->addText("AREA", ['bold' => true]);
                                            $tablaenc->addCell(1500)->addText("CARGO", ['bold' => true]);
                                            $tablaenc->addCell(1500)->addText("FUNCIONES", ['bold' => true]);
                                            $tablaenc->addCell(1500)->addText("PERIODO", ['bold' => true]);
                                            $tablaenc->addCell(1500)->addText("DOC. INICIO", ['bold' => true]);
                                            $tablaenc->addCell(1500)->addText("DOC. FIN", ['bold' => true]);
                                            foreach ($rotaciones as $rotacion) {
                                                $tablaenc->addRow();
                                                $tablaenc->addCell(1500)->addText($rotacion->area);
                                                $tablaenc->addCell(1500)->addText($rotacion->cargo);
                                                $tablaenc->addCell(1500)->addText($rotacion->descripcion);
                                                $tablaenc->addCell(1500)->addText("" . $rotacion->fecha_ini . " - " . ($rotacion->fecha_fin ?? 'HOY'));
                                                $tablaenc->addCell(1500)->addText($rotacion->nombredoc . " " . $rotacion->nrodoc);
                                                $tablaenc->addCell(1500)->addText(($rotacion->nombredocfin ?? '-'));
                                            }
                                        } else {
                                            $contenido->addText(
                                                "\tNo hay datos registrados",
                                                array('name' => 'Arial', 'size' => 12)
                                            );
                                        }
                                    }

                                    if ($idinforme == 11) {
                                        $contenido->addTitle("SUS LICENCIAS", 4);
                                        $licencias = DB::table('licencias')
                                            ->where('idvinculo', $vinculo->id)->get();
                                        if ($licencias->count() > 0) {
                                            $tablalic = $contenido->addTable('bordes');
                                            $tablalic->addRow();
                                            $tablalic->addCell(1500)->addText("DOCUMENTO", ['bold' => true]);
                                            $tablalic->addCell(1500)->addText("DESCRIPCIÓN", ['bold' => true]);
                                            $tablalic->addCell(1500)->addText("PERIODO", ['bold' => true]);
                                            $tablalic->addCell(1500)->addText("FECHA DE INICIO", ['bold' => true]);
                                            $tablalic->addCell(1500)->addText("FECHA FIN", ['bold' => true]);
                                            $tablalic->addCell(1500)->addText("TIEMPO", ['bold' => true]);
                                            $tablalic->addCell(1500)->addText("CON GOCE", ['bold' => true]);
                                            $tablalic->addCell(1500)->addText("A CUENTA VAC.", ['bold' => true]);
                                            foreach ($licencias as $licencia) {
                                                $tablalic->addRow();
                                                $tablalic->addCell(1500)->addText($licencia->nombredoc . $licencia->nrodoc);
                                                $tablalic->addCell(1500)->addText($licencia->descripcion);
                                                $tablalic->addCell(1500)->addText($licencia->periodo);
                                                $tablalic->addCell(1500)->addText($licencia->fecha_ini);
                                                $tablalic->addCell(1500)->addText($licencia->fecha_fin);
                                                $tablalic->addCell(1500)->addText($licencia->dias . "D " . $licencia->mes . "M " . $licencia->anio . "A");
                                                $tablalic->addCell(1500)->addText(($licencia->congoce == 0 ? 'NO' : 'SI'));
                                                $tablalic->addCell(1500)->addText(($licencia->acuentavac == 0 ? 'NO' : 'SI'));
                                            }
                                        } else {
                                            $contenido->addText(
                                                "\tNo hay datos registrados",
                                                array('name' => 'Arial', 'size' => 12)
                                            );
                                        }
                                    }
                                    if ($idinforme == 12) {
                                        $contenido->addTitle("SUS PERMISOS", 4);
                                        $permisos = DB::table('permisos')
                                            ->where('idvinculo', $vinculo->id)->get();
                                        if ($permisos->count() > 0) {
                                            $tablalic = $contenido->addTable('bordes');
                                            $tablalic->addRow();
                                            $tablalic->addCell(1500)->addText("DOCUMENTO", ['bold' => true]);
                                            $tablalic->addCell(1500)->addText("MOTIVO", ['bold' => true]);
                                            $tablalic->addCell(1500)->addText("PERIODO", ['bold' => true]);
                                            $tablalic->addCell(1500)->addText("FECHA DE INICIO", ['bold' => true]);
                                            $tablalic->addCell(1500)->addText("FECHA FIN", ['bold' => true]);
                                            $tablalic->addCell(1500)->addText("TIEMPO", ['bold' => true]);
                                            $tablalic->addCell(1500)->addText("CON GOCE", ['bold' => true]);
                                            $tablalic->addCell(1500)->addText("A CUENTA VAC.", ['bold' => true]);
                                            foreach ($permisos as $permiso) {
                                                $tablalic->addRow();
                                                $tablalic->addCell(1500)->addText($permiso->nombredoc . '  ' . $permiso->nrodoc);
                                                $tablalic->addCell(1500)->addText($permiso->descripcion);
                                                $tablalic->addCell(1500)->addText($permiso->periodo);
                                                $tablalic->addCell(1500)->addText($permiso->fecha_ini);
                                                $tablalic->addCell(1500)->addText($permiso->fecha_fin);
                                                $tablalic->addCell(1500)->addText($permiso->dias . "D " . $permiso->mes . "M " . $permiso->anio . "A");
                                                $tablalic->addCell(1500)->addText(($permiso->congoce == 0 ? 'NO' : 'SI'));
                                                $tablalic->addCell(1500)->addText(($permiso->acuentavac == 0 ? 'NO' : 'SI'));
                                            }
                                        } else {
                                            $contenido->addText(
                                                "\tNo hay datos registrados",
                                                array('name' => 'Arial', 'size' => 12)
                                            );
                                        }
                                    }
                                    if ($idinforme == 13) {
                                        $contenido->addTitle("SUS COMPENSACIONES", 4);
                                        $compensaciones = DB::table('compensaciones as c')
                                            ->select('*', 't.nombre as tipo')
                                            ->join('tipo_compensacion as t', 'c.tipo_compensacion', '=', 't.id')
                                            ->where('idvinculo', $vinculo->id)->get();
                                        if ($compensaciones->count() > 0) {
                                            $tablaenc = $contenido->addTable('bordes');
                                            $tablaenc->addRow();
                                            $tablaenc->addCell(1500)->addText("TIPO", ['bold' => true]);
                                            $tablaenc->addCell(1500)->addText("DOCUMENTO", ['bold' => true]);
                                            $tablaenc->addCell(1500)->addText("DESCRIPCIÓN", ['bold' => true]);
                                            $tablaenc->addCell(1500)->addText("FECHA INICIO", ['bold' => true]);
                                            $tablaenc->addCell(1500)->addText("FECHA FIN", ['bold' => true]);
                                            $tablaenc->addCell(1500)->addText("DÍAS", ['bold' => true]);
                                            foreach ($compensaciones as $compensacion) {
                                                $tablaenc->addRow();
                                                $tablaenc->addCell(1500)->addText($compensacion->tipo);
                                                $tablaenc->addCell(1500)->addText($compensacion->nombredoc . ' ' . $compensacion->nrodoc);
                                                $tablaenc->addCell(1500)->addText($compensacion->descripcion);
                                                $tablaenc->addCell(1500)->addText($compensacion->fecha_ini);
                                                $tablaenc->addCell(1500)->addText($compensacion->fecha_fin ?? 'HOY');
                                                $tablaenc->addCell(1500)->addText(($compensacion->dias));
                                            }
                                        } else {
                                            $contenido->addText(
                                                "\tNo hay datos registrados",
                                                array('name' => 'Arial', 'size' => 12)
                                            );
                                        }
                                    }
                                    if ($idinforme == 14) {
                                        $contenido->addTitle("SUS RECONOCIMIENTOS", 4);
                                        $reconocimientos = DB::table("reconocimientos")
                                            ->where('idvinculo', $vinculo->id)->get();
                                        if ($reconocimientos->count() > 0) {
                                            $tablaenc = $contenido->addTable('bordes');
                                            $tablaenc->addRow();
                                            $tablaenc->addCell(1500)->addText("FORMA", ['bold' => true]);
                                            $tablaenc->addCell(1500)->addText("DOCUMENTO", ['bold' => true]);
                                            $tablaenc->addCell(1500)->addText("DESCRIPCIÓN", ['bold' => true]);
                                            $tablaenc->addCell(1500)->addText("F. DEL RECONOCIMIENTO", ['bold' => true]);
                                            $tablaenc->addCell(1500)->addText("FECHA INICIO", ['bold' => true]);
                                            $tablaenc->addCell(1500)->addText("FECHA FIN", ['bold' => true]);

                                            foreach ($reconocimientos as $reconocimiento) {
                                                $tablaenc->addRow();
                                                $tablaenc->addCell(1500)->addText($reconocimiento->forma == 0 ? 'De labores realizadas' : 'De tiempo de servicio');
                                                $tablaenc->addCell(1500)->addText($reconocimiento->nombredoc . ' ' . $reconocimiento->nrodoc);
                                                $tablaenc->addCell(1500)->addText($reconocimiento->descripcion);
                                                $tablaenc->addCell(1500)->addText($reconocimiento->fecharecon);
                                                $tablaenc->addCell(1500)->addText($reconocimiento->fecha_ini);
                                                $tablaenc->addCell(1500)->addText($reconocimiento->fecha_fin ?? 'HOY');
                                            }
                                        } else {
                                            $contenido->addText(
                                                "\tNo hay datos registrados",
                                                array('name' => 'Arial', 'size' => 12)
                                            );
                                        }
                                    }
                                    if ($idinforme == 15) {
                                        $contenido->addTitle("SUS SANCIONES", 4);
                                        $sanciones = DB::table("sanciones")
                                            ->where('idvinculo', $vinculo->id)->get();
                                        if ($sanciones->count() > 0) {
                                            $tablaenc = $contenido->addTable('bordes');
                                            $tablaenc->addRow();
                                            $tablaenc->addCell(1500)->addText("FORMA", ['bold' => true]);
                                            $tablaenc->addCell(1500)->addText("MOTIVO", ['bold' => true]);
                                            $tablaenc->addCell(1500)->addText("DOCUMENTO", ['bold' => true]);
                                            $tablaenc->addCell(1500)->addText("F. DE LA SANCIÓN", ['bold' => true]);
                                            $tablaenc->addCell(1500)->addText("FECHA INICIO", ['bold' => true]);
                                            $tablaenc->addCell(1500)->addText("FECHA FIN", ['bold' => true]);
                                            $tablaenc->addCell(1500)->addText("DÍAS", ['bold' => true]);

                                            foreach ($sanciones as $sancion) {
                                                $tablaenc->addRow();
                                                $tablaenc->addCell(1500)->addText($sancion->tiposancion == 1 ? 'Al tiempo de servicio' : 'Sólo a la remuneración');
                                                $tablaenc->addCell(1500)->addText($sancion->descripcion);
                                                $tablaenc->addCell(1500)->addText($sancion->nombredoc . ' ' . $sancion->nrodoc);

                                                $tablaenc->addCell(1500)->addText($sancion->fechadoc);
                                                $tablaenc->addCell(1500)->addText($sancion->fecha_ini);
                                                $tablaenc->addCell(1500)->addText($sancion->fecha_fin ?? 'HOY');
                                                $tablaenc->addCell(1500)->addText($sancion->dias_san);
                                            }
                                        } else {
                                            $contenido->addText(
                                                "\tNo hay datos registrados",
                                                array('name' => 'Arial', 'size' => 12)
                                            );
                                        }
                                    }

                                    if ($idinforme == 16) {
                                        $contenido->addTitle("SUS VACACIONES DEL PERIODO", 4);
                                        $vacaciones = DB::table("vacaciones")
                                            ->where('idvinculo', $vinculo->id)->get();
                                        if ($vacaciones->count() > 0) {
                                            $tablavac = $contenido->addTable('bordes');
                                            $tablavac->addRow();
                                            $tablavac->addCell(1500)->addText("DOCUMENTO", ['bold' => true]);
                                            $tablavac->addCell(1500)->addText("PERIODO", ['bold' => true]);
                                            $tablavac->addCell(1500)->addText("INICIO", ['bold' => true]);
                                            $tablavac->addCell(1500)->addText("FIN", ['bold' => true]);
                                            $tablavac->addCell(1500)->addText("DÍAS", ['bold' => true]);
                                            foreach ($vacaciones as $vacacion) {
                                                $tablavac->addRow();
                                                $tablavac->addCell(1500)->addText($vacacion->nombredoc . ' ' . $vacacion->nrodoc);

                                                $tablavac->addCell(1500)->addText($vacacion->periodo);
                                                $tablavac->addCell(1500)->addText($vacacion->fecha_ini);
                                                $tablavac->addCell(1500)->addText($vacacion->fecha_fin ?? 'HOY');
                                                $tablavac->addCell(1500)->addText($vacacion->dias);
                                            }
                                        } else {
                                            $contenido->addText(
                                                "\tNo hay datos registrados",
                                                array('name' => 'Arial', 'size' => 12)
                                            );
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $contenido->addText(
                            "\tNo hay datos registrados",
                            array('name' => 'Arial', 'size' => 12)
                        );
                    }
                }
                if ($dato == 4) {
                    $contenido->addTitle("SOBRE SUS ESTUDIOS", 2);
                    $experiencias = DB::table('estudios as e')
                        ->join('tipodoc as t', 'e.idtd', '=', 't.id')
                        ->select('*', 't.nombre as tipodoc')
                        ->where('e.personal_id', $id)->get();
                    if ($experiencias->count() > 0) {
                        $exp = $contenido->addTable('bordes');
                        $exp->addRow();
                        $exp->addCell(1500)->addText('ESPECIALIDAD', ['bold' => true]);
                        $exp->addCell(1500)->addText('GRADO', ['bold' => true]);
                        $exp->addCell(1500)->addText('CENTRO ESTUDIOS', ['bold' => true]);
                        $exp->addCell(1500)->addText('TIPO DOC.', ['bold' => true]);
                        $exp->addCell(1500)->addText('FECHA INICIO', ['bold' => true]);
                        $exp->addCell(1500)->addText('FECHA FIN', ['bold' => true]);
                        foreach ($experiencias as $experiencia) {
                            $exp->addRow();
                            $exp->addCell(1500)->addText($experiencia->especialidad ?? '-');
                            $exp->addCell(1500)->addText($experiencia->GradoAcademico ?? '-');
                            $exp->addCell(1500)->addText($experiencia->centroestudios ?? '-');
                            $exp->addCell(1500)->addText($experiencia->tipodoc ?? '-');
                            $exp->addCell(1500)->addText($experiencia->fecha_ini ?? '-');
                            $exp->addCell(1500)->addText($experiencia->fecha_fin ?? '-');
                        }
                    } else {
                        $contenido->addText(
                            "\tNo hay datos registrados",
                            array('name' => 'Arial', 'size' => 12)
                        );
                    }
                }
                if ($dato == 5) {
                    $contenido->addTitle("SOBRE SUS ESTUDIOS COMPLEMENTARIOS", 2);
                    $complementarios = DB::table('estudios_especializacion as e')
                        ->join('tipodoc as t', 'e.idtd', '=', 't.id')
                        ->select('*', 't.nombre as tipodoc')->where('e.personal_id', $id)->get();
                    if ($complementarios->count() > 0) {
                        $com = $contenido->addTable('bordes');
                        $com->addRow();
                        $com->addCell(1500)->addText('DENOMINACIÓN', ['bold' => true]);
                        $com->addCell(1500)->addText('CENTRO ESTUDIOS', ['bold' => true]);
                        $com->addCell(1500)->addText('TIPO DOC.', ['bold' => true]);
                        $com->addCell(1500)->addText('HORAS', ['bold' => true]);
                        $com->addCell(1500)->addText('INICIO', ['bold' => true]);
                        $com->addCell(1500)->addText('FIN', ['bold' => true]);
                        foreach ($complementarios as $comp) {
                            $com->addRow();
                            $com->addCell(1500)->addText($comp->nombre ?? '-');
                            $com->addCell(1500)->addText($comp->centroestudios ?? '-');
                            $com->addCell(1500)->addText($comp->tipodoc ?? '-');
                            $com->addCell(1500)->addText($comp->horas ?? '-');
                            $com->addCell(1500)->addText($comp->fecha_ini ?? '-');
                            $com->addCell(1500)->addText($comp->fecha_fin ?? '-');
                        }
                    } else {
                        $contenido->addText(
                            "\tNo hay datos registrados",
                            array('name' => 'Arial', 'size' => 12)
                        );
                    }
                }
                if ($dato == 6) {
                    $contenido->addTitle("SOBRE SUS IDIOMAS", 2);
                    $idiomas = Idiomas::where('personal_id', $id)->get();
                    if ($idiomas->count() > 0) {
                        $id = $contenido->addTable('bordes');
                        $id->addRow();
                        $id->addCell(1500)->addText('NOMBRE', ['bold' => true]);
                        $id->addCell(1500)->addText('LECTURA', ['bold' => true]);
                        $id->addCell(1500)->addText('HABLA', ['bold' => true]);
                        $id->addCell(1500)->addText('ESCRITURA', ['bold' => true]);
                        foreach ($idiomas as $idioma) {
                            $id->addRow();
                            $id->addCell(1500)->addText($idioma->idioma ?? '-');
                            $id->addCell(1500)->addText($idioma->lectura ?? '-');
                            $id->addCell(1500)->addText($idioma->habla ?? '-');
                            $id->addCell(1500)->addText($idioma->escritura ?? '-');
                        }
                    } else {
                        $contenido->addText(
                            "\tNo hay datos registrados",
                            array('name' => 'Arial', 'size' => 12)
                        );
                    }
                }
            }
        }
        $contenido->addText(
            "\tEs todo en cuanto debo informar",
            array('name' => 'Arial', 'size' => 12)
        );
        $contenido->addText(
            "\tAtentamente",
            array('name' => 'Arial', 'size' => 12)
        );
        // Guardar el documento temporalmente
        $fileName = 'informePersonal.docx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $phpWord->save($tempFile, 'Word2007');

        // Descargar el archivo
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    public function datosPersonal(Request $request)
    {
        $id = $request->query('id');

        $personales = DB::table('personal as p')
            ->join('tipo_personal as t', 'p.id_tipo_personal', '=', 't.id')
            ->select('*', 't.nombre as tipopersonal')
            ->where('p.id_personal', $id)->first();

        $vinculos = DB::table('vinculos as v')
            ->select('v.*', 'c.nombre as cargo', 'a.nombre as area', 'l.nombre as condicion', 'm.nombre as motivofin')
            ->leftjoin('cargo as c', 'c.id', '=', 'v.id_cargo')
            ->leftjoin('area as a', 'a.id', '=', 'v.id_unidad_organica')
            ->leftjoin('regimen as r', 'r.id', '=', 'v.id_regimen')
            ->leftjoin('condicion_laboral as l', 'l.id', '=', 'v.id_condicion_laboral')
            ->leftjoin('motivo_fin_vinculo as m', 'm.id', '=', 'v.id_motivo_fin_vinculo')
            ->where('v.personal_id', $id)->get();

        $rotaciones = DB::table('movimientos as m')
            ->leftjoin('vinculos as v', 'v.id', '=', 'm.idvinculo')
            ->leftjoin('area as ar', 'ar.id', 'v.id_unidad_organica')
            ->leftjoin('area as a', 'a.id', '=', 'm.unidad_organica_destino')
            ->leftjoin('cargo as c', 'c.id', '=', 'm.cargo')
            ->select('m.*', 'ar.nombre as origen', 'a.nombre as destino', 'c.nombre as cargo')
            ->where('m.personal_id', $id)
            ->where('m.tipo', 0)->get();

        $encargaturas = DB::table('movimientos as m')
            ->leftjoin('vinculos as v', 'v.id', '=', 'm.idvinculo')
            ->leftjoin('area as ar', 'ar.id', 'v.id_unidad_organica')
            ->leftjoin('area as a', 'a.id', '=', 'm.unidad_organica_destino')
            ->leftjoin('cargo as c', 'c.id', '=', 'm.cargo')
            ->select('m.*', 'ar.nombre as origen', 'a.nombre as destino', 'c.nombre as cargo')
            ->where('m.personal_id', $id)
            ->where('m.tipo', 1)->get();

        $licencias = DB::table('licencias as l')
            ->join('vinculos as v', 'v.id', '=', 'l.idvinculo')
            ->where('v.personal_id', $id)->get();

        $permisos = DB::table('permisos as p')
            ->join('vinculos as v', 'v.id', '=', 'p.idvinculo')
            ->where('v.personal_id', $id)->get();

        $compensaciones = DB::table('compensaciones as c')
            ->join('vinculos as v', 'v.id', '=', 'c.idvinculo')
            ->join('tipo_compensacion as t', 't.id', '=', 'c.tipo_compensacion')
            ->select('c.*', 't.nombre as tipocom')
            ->where('v.personal_id', $id)->get();

        $reconocimientos = DB::table('reconocimientos as r')
            ->join('vinculos as v', 'v.id', '=', 'r.idvinculo')
            ->where('v.personal_id', $id)->get();

        $sanciones = DB::table('sanciones as s')
            ->join('vinculos as v', 'v.id', '=', 's.idvinculo')
            ->where('v.personal_id', $id)->get();

        $cronograma = DB::table('cronograma_vac')->where('personal_id', $id);


        $vacaciones = DB::table('vacaciones as va')
            ->join('vinculos as v', 'v.id', '=', 'va.idvinculo')
            ->where('v.personal_id', $id)->get();

        $familiares = DB::table('familiares')
            ->where('personal_id', $id)->get();

        $estudios = DB::table('estudios')
            ->where('personal_id', $id)->get();

        $especialidad = DB::table('estudios_especializacion')
            ->where('personal_id', $id)->get();

        $colegiatura = DB::table('colegiatura')
            ->where('personal_id', $id)->get();

        $experiencias = DB::table('explaboral')
            ->where('personal_id', $id)->get();

        $idiomas = DB::table('idiomas')
            ->where('personal_id', $id)->get();

        $reporte = view('Informe/informeGeneral', [
            'personal' => $personales,
            'vinculos' => $vinculos,
            'rotaciones' => $rotaciones,
            'encargaturas' => $encargaturas,
            'licencias' => $licencias,
            'permisos' => $permisos,
            'compensaciones' => $compensaciones,
            'reconocimientos' => $reconocimientos,
            'sanciones' => $sanciones,
            'cronograma' => $cronograma,
            'vacaciones' => $vacaciones,
            'familiares' => $familiares,
            'estudios' => $estudios,
            'especialidades' => $especialidad,
            'colegiatura' => $colegiatura,
            'experiencias' => $experiencias,
            'idiomas' => $idiomas
        ])->render();
        return response()->json(['reporte' => $reporte]);
    }

    public function generarFotocheck(Request $request)
    {
        $id = $request->query('id');

        $personales = DB::table('personal as p')
            ->join('tipo_personal as t', 'p.id_tipo_personal', '=', 't.id')
            ->select('*', 't.nombre as tipopersonal')
            ->where('p.id_personal', $id)->first();

        $vinculos = DB::table('vinculos as v')
            ->select('v.*', 'c.nombre as cargo', 'a.nombre as area', 'l.nombre as condicion', 'm.nombre as motivofin', 'r.nombre as regimen')
            ->leftjoin('cargo as c', 'c.id', '=', 'v.id_cargo')
            ->leftjoin('area as a', 'a.id', '=', 'v.id_unidad_organica')
            ->leftjoin('regimen as r', 'r.id', '=', 'v.id_regimen')
            ->leftjoin('condicion_laboral as l', 'l.id', '=', 'v.id_condicion_laboral')
            ->leftjoin('motivo_fin_vinculo as m', 'm.id', '=', 'v.id_motivo_fin_vinculo')
            ->where('v.personal_id', $id)->first();

        $hash = Hashids::encode($id);
        $url = 'http://172.100.1.8/perfil?id=' . $hash;

        //$pngData = QrCode::format('png')->size(300)->generate($url);

        $svgQr = QrCode::format('svg')
            ->size(300)
            ->generate($url);

        // Crear imagen desde el binario PNG
        $tempPath = storage_path('app/public/qr.svg');
        file_put_contents($tempPath, $svgQr);

        // Ruta donde guardar el JPG
        $jpgPath = storage_path("app/public/qr_{$id}.jpg");
        // Guardar como JPG (calidad 90)
        //0imagejpeg($image, $jpgPath, 90);

        // Liberar memoria
        //imagedestroy($image);

        $pdf = new TCPDF('P', 'mm', [54, 86], true, 'UTF-8', false);
        $pdf->setCreator('Escalafón');
        $pdf->setAuthor('Ivan Vera');
        $pdf->setTitle('Fotocheck');
        //$pdf->setMargins(3, 3, 0);
        $pdf->SetMargins(1, 0, 0);
        $pdf->SetAutoPageBreak(false);
        $pdf->AddPage();
        $img_file = 'img/fotocheck1.jpg';
        $pdf->Image($img_file, 0, 0, 54, 86, '', '', '', false, 300, '', false, false, 0);
        $html = view('Informe.fotocheck', compact('personales', 'vinculos'))->render();
        $pdf->writeHTML($html, true, false, true, false, '');

        $x = 15;
        $y = 15;



        //$x = 20;
        //$y = 30;
        $r = 12.5;
        $diametro = 25;

        $pdf->StartTransform();
        $pdf->Circle($x + $r, $y + $r, $r, 0, 360, 'CNZ');
        $pdf->Image(public_path('repositories/' . $personales->foto), $x, $y, $diametro, $diametro, '', '', '', true, 300);
        $pdf->Circle($x + $r, $y + $r, $r, 0, 360, 'CNZ');
        $pdf->StopTransform();
        $slogan = 'img/slogan.jpg';
        $pdf->Image($slogan, 7, 76, 40, 10, '', '', '', false, 300, '', false, false, 0);

        //$pdf->Circle($x+12, $y+12, 12.5); // Dibuja el círculo 
        $pdf->AddPage();
        $html = view('Informe.fotocheckr', compact('personales', 'vinculos'))->render();
        $pdf->writeHTML($html, true, false, true, false, '');

        //$pdf->Output("fotocheck_{$personales->id_personal}.pdf", 'I');
        //$pdf->Image($jpgPath, $x = 10, $y = 10, $w = 30, $h = 30, 'JPG');
        $pdf->Image($tempPath,  $x = 10, $y = 10, $w = 30, $h = 30, 'SVG', '', '', false, 300, '', false, false, 0, false, false, false);

        $pdf->Output(public_path("img/fotocheck_{$personales->id_personal}.pdf"), 'F'); // 'F' para guardar
        //unlink($path);
        //unlink($jpgPath);

        $reporte = view('Informe/verFotocheck', [
            'personal' => $personales,
            'vinculos' => $vinculos,
        ])->render();
        return response()->json(['reporte' => $reporte]);
    }

    public function perfilPersonal(Request $request)
    {
        $id = Hashids::decode($request->query('id'));

        $personal = DB::table('personal as p')
            ->join('tipo_personal as t', 'p.id_tipo_personal', '=', 't.id')
            ->select('*', 't.nombre as tipopersonal')
            ->where('p.id_personal', $id)->first();

        $vinculos = DB::table('vinculos as v')
            ->select('v.*', 'c.nombre as cargo', 'a.nombre as area', 'l.nombre as condicion', 'm.nombre as motivofin', 'r.nombre as regimen')
            ->leftjoin('cargo as c', 'c.id', '=', 'v.id_cargo')
            ->leftjoin('area as a', 'a.id', '=', 'v.id_unidad_organica')
            ->leftjoin('regimen as r', 'r.id', '=', 'v.id_regimen')
            ->leftjoin('condicion_laboral as l', 'l.id', '=', 'v.id_condicion_laboral')
            ->leftjoin('motivo_fin_vinculo as m', 'm.id', '=', 'v.id_motivo_fin_vinculo')
            ->where('v.personal_id', $id)->first();


        return view('Informe.perfil', array('personal' => $personal, 'vinculo' => $vinculos));
    }

    public function fotocheckMasivo()
    {
        $personales = DB::table('personal as p')
            ->select('p.*', 'c.nombre as cargo', 'a.nombre as area', 'r.nombre as regimen')
            ->leftjoin('vinculos as v', 'v.personal_id', '=', 'p.id_personal')
            ->leftjoin('cargo as c', 'c.id', '=', 'v.id_cargo')
            ->leftjoin('area as a', 'a.id', '=', 'v.id_unidad_organica')
            ->leftjoin('regimen as r', 'r.id', '=', 'v.id_regimen')
            ->where('p.verificador', 1)
            ->get();
        return view('Informe.fotocheckMasivo', array('personal' => $personales));
    }
    public function generarFotocheckMasivo(Request $request)
    {
        $ids = $request->query('ids');
        // Validar que sea un array
        if (!is_array($ids)) {
            return response()->json(['error' => 'Formato inválido'], 400);
        }
        $pdf = new TCPDF('P', 'mm', [54, 86], true, 'UTF-8', false);
        $pdf->setCreator('Escalafón');
        $pdf->setAuthor('Ivan Vera');
        $pdf->setTitle('Fotocheck');
        //$pdf->setMargins(3, 3, 0);
        $pdf->SetMargins(1, 0, 0);
        $pdf->SetAutoPageBreak(false);
        Log::debug("id vinculo: " );
        foreach ($ids as $id) {
            logger('Mensaje para el log: '.$id);
            $personales = DB::table('personal as p')
                ->join('tipo_personal as t', 'p.id_tipo_personal', '=', 't.id')
                ->select('*', 't.nombre as tipopersonal')
                ->where('p.id_personal', $id)->first();

            $vinculos = DB::table('vinculos as v')
                ->select('v.*', 'c.nombre as cargo', 'a.nombre as area', 'l.nombre as condicion', 'm.nombre as motivofin', 'r.nombre as regimen')
                ->leftjoin('cargo as c', 'c.id', '=', 'v.id_cargo')
                ->leftjoin('area as a', 'a.id', '=', 'v.id_unidad_organica')
                ->leftjoin('regimen as r', 'r.id', '=', 'v.id_regimen')
                ->leftjoin('condicion_laboral as l', 'l.id', '=', 'v.id_condicion_laboral')
                ->leftjoin('motivo_fin_vinculo as m', 'm.id', '=', 'v.id_motivo_fin_vinculo')
                ->where('v.personal_id', $id)->first();

            $hash = Hashids::encode($id);
            $url = 'http://172.100.1.8/perfil?id=' . $hash;

            $svgQr = QrCode::format('svg')
                ->size(300)
                ->generate($url);

            $tempPath = storage_path('app/public/qr.svg');
            file_put_contents($tempPath, $svgQr);

            // Ruta donde guardar el JPG
            $jpgPath = storage_path("app/public/qr_{$id}.jpg");

            $pdf->AddPage();
            $img_file = 'img/fotocheck1.jpg';
            $pdf->Image($img_file, 0, 0, 54, 86, '', '', '', false, 300, '', false, false, 0);
            
            $html = view('Informe.fotocheck', compact('personales', 'vinculos'))->render();
            $pdf->writeHTML($html, true, false, true, false, '');

            $x = 15;
            $y = 15;

            $r = 12.5;
            $diametro = 25;

            $pdf->StartTransform();
            $pdf->Circle($x + $r, $y + $r, $r, 0, 360, 'CNZ');
            $pdf->Image(public_path('repositories/' . $personales->foto), $x, $y, $diametro, $diametro, '', '', '', true, 300);
            $pdf->Circle($x + $r, $y + $r, $r, 0, 360, 'CNZ');
            $pdf->StopTransform();
            $slogan = 'img/slogan.jpg';
            $pdf->Image($slogan, 7, 76, 40, 10, '', '', '', false, 300, '', false, false, 0);
            $pdf->AddPage();
            $html = view('Informe.fotocheckr', compact('personales', 'vinculos'))->render();
            $pdf->writeHTML($html, true, false, true, false, '');

            $pdf->Image($tempPath,  $x = 10, $y = 10, $w = 30, $h = 30, 'SVG', '', '', false, 300, '', false, false, 0, false, false, false);
            $personal=Personal::find($id);
            $personal->verificador=2;
            $personal->save();
        }
        // Obtener registros desde la base de datos
        $fechadehoy = Carbon::now()->format('Ymd_His');
        $pdf->Output(public_path("img/fotocheck_{$fechadehoy}.pdf"), 'F');
        //$personalSeleccionado = Personal::whereIn('id_personal', $ids)->get();
        // Procesar o retornar
        $reporte = view('Informe/verFotocheckMasivo', [
            
            'link' => "img/fotocheck_{$fechadehoy}.pdf",
            
        ])->render();

        return response()->json(['reporte' => $reporte]);
    }
}
