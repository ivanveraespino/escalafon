<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use \PhpOffice\PhpWord\Style\Font;
use Carbon\Carbon;

class WordController extends Controller
{
    //
    public function generateWord()
    {
        Carbon::setLocale('es');
        // Crear una nueva instancia de PhpWord
        $phpWord = new PhpWord();
//  
        // Obtener protocolo (http o https)
        $protocolo = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        // Obtener el nombre del host
        $dominio = $_SERVER['HTTP_HOST'];
        // Obtener la ruta base del dominio
        $rutaDominio = $protocolo . '://' . $dominio;
        $encabezado = $phpWord->addSection(array(
            'marginTop' => 75 // Ajustar el margen superior (valor en twips)
        )
        );
        // Configurar los estilos de títulos
        $phpWord->addTitleStyle(1, array('name' => 'Arial', 'size' => 12, 'bold' => true, 'color' => '333333','underline' => Font::UNDERLINE_SINGLE), array('alignment' => 'center','lineHeight' => 1.5));
        $phpWord->addTitleStyle(2, array('name' => 'Arial', 'size' => 12, 'bold' => true, 'color' => '666666', 'italic'=>true), array('alignment' => 'left'));
        // Define un estilo de párrafo con tabuladores
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
        $cell= $table->addCell(100);
        $cell->addImage($rutaDominio.'/img/logo_informe.png',
            array(
                'width' => 50,
                'height' => 60,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER // Alineación a la izquierda
            ));
        $cell2 = $table->addCell(8800);
        $cell2->addText(
            'MUNICIPALIDAD PROVINCIAL DE LA CONVENCIÓN',
            array('bold' => true, 'size' => 13, 'color' => 'green' ) ,// Estilo del texto
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
            array( 'size' => 10, 'color' => 'green'), // Estilo del texto
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,'lineHeight' => 0.8)
        );
        //
        // Añadir una sección al documento
        $contenido = $phpWord->addSection();
        // Agregar un título de nivel 1
        $contenido->addTitle('INFORME N° 008-2025-WCB-E-OGRH-MPLC', 1);


        $contenido->addText(
            "A\t:",
            array('bold'=>true,'name' => 'Arial', 'size' => 12), $paragraphStyle
        );
        $contenido->addText(
            "DE\t: C.P.C. WILFREDO CAMPAR BAUTISTA",
            array('bold'=>true,'name' => 'Arial', 'size' => 12), $paragraphStyle
        );
        $contenido->addText(
            "\t  PROFESIONAL IV - COORDINADOR DE ESCALAFÓN",
            array('bold'=>true,'name' => 'Arial', 'size' => 12), $paragraphStyle
        );
        $contenido->addText(
            "ASUNTO\t: REMITO INFORME ESCALAFONARIO",
            array('bold'=>true,'name' => 'Arial', 'size' => 12),$paragraphStyle
        );
        
        $contenido->addText(
            "FECHA\t: Quillabamba,".Carbon::now()->isoFormat(' D [de] MMMM [de] YYYY'),
            array('bold'=>true,'name' => 'Arial', 'size' => 12),$paragraphStyle
        );

        $contenido->addText(
            str_repeat('_', 67), // Línea horizontal de texto subrayado
            array('size' => 12), // Tamaño de la línea
            array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'lineHeight' => 0.05)
        );
        $contenido->addText(
            "\tMediante el presente es grato dirigirme a Ud., con la finalidad de remitir información solicitada según el documento de la referencia, en donde se solicita información del servidor XXXXXXX, quien se desempeñó como XXXXXXXX en el periodo AÑO.",
            array('name' => 'Arial', 'size' => 12)
        );
        $contenido->addText(
            "\tAsí mismo debo indicar que la información que se remite es según acervo documentario encontrado en el área de ESCALAFÓN Y File Personal de la Unidad de Recursos Humanos",
            array('name' => 'Arial', 'size' => 12)
        );

        // Guardar el documento temporalmente
        $fileName = 'documento_ejemplo.docx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $phpWord->save($tempFile, 'Word2007');

        // Descargar el archivo
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
