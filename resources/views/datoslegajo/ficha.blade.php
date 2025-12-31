<!DOCTYPE html>
<html>
<head>
    <title>Ficha</title> 
<style>
 body{
        font-size: 0.8em;
        font-family: sans-serif;
    }
    h2{
        text-align: center;
        color: black;
    }

    table, th, td {
        border: 1px solid;
        padding: 5px;
    }

    h3{
        color: #black;
        margin: 1px;

    }
    h4{
        margin-bottom: 1px;
    }
    table{
        
        border-collapse: collapse;
        width: 100%;
    }
    p{
        /*border-left: 2px solid #094293;*/
        padding-left: 15px;
        padding-right: 20px;
        text-align: justify;
    }


    ul.prov{
        /*border-left: 2px solid #094293;*/
        padding-left: 20px ;
        text-align: justify;
    }
    ul{
        /*border-left: 2px solid #094293;*/

        padding-right: 20px ;
        text-align: justify;
    }

    .page-break {
	    page-break-after: always;
	}

    #header,
#footer {
  position: fixed;
  left: 0;
	right: 0;
	color: #aaa;
	font-size: 0.9em;
}
#footer {
  bottom: 0;
  border-bottom: 1pt solid #aaa;
  margin-bottom: 10px;
}
.justify-text {
  text-align: justify;
  padding-right: 5px; 

}
.centered-row td {
    text-align: center;
    display: table-cell;
    vertical-align: middle;
    text-transform: uppercase;

}
.column {
    text-align: center; /* Centra el contenido dentro de la celda */
    width: 20%; /* Ajusta el ancho de cada columna según tus necesidades */
    display: table-cell;
    vertical-align: middle;
}
</style>
</head>
<body>
    <div id="footer">
            <div class="page-number"></div>
    </div>
    <h2>FICHA DE DATOS PERSONALES<br><small>TRABAJADOR</small></h2>
    <div id="perfil">
        <center>
            <img id="profileImage" src="{{ $base64Image }}" style="width: 150px; height: auto; object-fit: cover; border: 2px solid #000;">
        </center>
    </div>
    
    <br>
    <h3>1. DATOS PERSONALES</h3>
    <table>
        <tr>
            <th>APELLIDO PATERNO</th>
            <th>APELLIDO MATERNO</th>
            <th>NOMBRES</th>
        </tr>
        <tr class="centered-row">
            <td>{{ $dp->Apaterno ?? '' }}</td>
            <td>{{ $dp->Amaterno ?? '' }}</td>
            <td>{{ $dp->Nombres ?? '' }}</td>
        </tr>
    </table>    
   
    <table>
        <tr>
            <th>FECHA DE NACIMIENTO</th>
            <th>LUGAR DE PROCEDENCIA</th>
            <th>N° DOCUMENTO DE IDENTIDAD</th>
            <th>N° DE COLEGIATURA</th>
            <th>NRO. DE RUC</th>

        </tr>
        <tr class="centered-row">
            <td>{{ \Carbon\Carbon::parse($dp->FechaNacimiento)->format('d-m-Y') ?? '' }}</td>
            <td>{{ $dp->lprocedencia ?? '' }}</td>
            <td>{{ $dp->nro_documento_id ?? '' }}</td>
            <td>{{ $dp->NroColegiatura ?? '' }}</td>
            <td>{{ $dp->NroRuc ?? '' }}</td>
        </tr>
    </table> 
    <table>
        <tr>
            <th>N° DE CARNE ESSALUD (AUTOGENERADO)</th>
            <th>CENTRO DE ATENCION ESSALUD</th>
            <th>GRUPO SANGUINEO</th>
        </tr>
        <tr class="centered-row">
            <td>{{ $dp->NroEssalud ?? '' }}</td>
            <td>{{ $dp->CentroEssalud ?? '' }}</td>
            <td>{{ $dp->GrupoSanguineo ?? '' }}</td>
        </tr>
    </table> 
    <table>
        <tr>
            <th>N° TELEF. DOMICILIO</th>
            <th>N° TELEF. CELULAR</th>
            <th>CORREO ELECTRONICO</th>
        </tr>
        <tr class="centered-row">
            <td>{{ $dp->NroTelefono ?? '' }}</td>
            <td>{{ $dp->NroCelular ?? '' }}</td>
            <td>{{ $dp->Correo ?? '' }}</td>
        </tr>
    </table> 
    <table>
        <tr>
            <th>TIPO</th>
            <th>DOMICILIO ACTUAL</th>
        </tr>
        <tr class="centered-row">
            <td>{{ $dd->tipodom ?? '' }}</td>
            <td>{{ $dd->dactual ?? '' }}</td>
        </tr>
    </table>
    <table>
        <tr>
            <th>DEPARTAMENTO</th>
            <th>PROVINCIA</th>
            <th>DISTRITO</th>
            <th>NUMERO</th>
            <th>INTERIOR</th>

        </tr>
        <tr class="centered-row">
            <td>{{ $dep[$dd->iddep ?? ''] ?? '' }}</td>
            <td>{{ $pro[$dd->idpro ?? ''] ?? '' }}</td>
            <td>{{ $dis[$dd->iddis ?? ''] ?? '' }}</td>
        
            <td>{{ $dd->numero ?? '' }}</td>
            <td>{{ $dd->interior ?? '' }}</td>
        </tr>
    </table>
    <table>
        <tr>
            <th>REFERENCIA</th>
        </tr>
        <tr class="centered-row">
            <td>{{ $dd->referencia ?? '' }}</td>
        </tr>
    </table>
    <table>
    <thead>
        <tr>
            <th>ESTADO CIVIL Y/O CONYUGAL</th>
         

        </tr>
    </thead>
    <tbody>
        <tr class="centered-row">
            <td class="column">
                {{ $dp->EstadoCivil ?? '' }}
            </td>
        </tr>
    </tbody>
</table>

<h3>2. DATOS FAMILIARES</h3>
    <h4>DATOS  DEL CONYUGE</h4>
    <table>
        <tr>
            <th>APELLIDO PATERNO</th>
            <th>APELLIDO MATERNO</th>
            <th>NOMBRES</th>
        </tr>
        @foreach($dft as $item)
        @if ($item->parentesco === "CONYUGUE")
        <tr class="centered-row">
            <td>{{ $item->apaterno ?? '' }}</td>
            <td>{{ $item->amaterno ?? '' }}</td>
            <td>{{ $item->nombres ?? '' }}</td>
        </tr>
        @endif
        @endforeach

    </table> 
    <table>
        <tr>
            <th>FECHA DE NACIMIENTO</th>
            <th>LUGAR DONDE LABORA EL CONYUGE</th>
        </tr>
        @foreach($dft as $item)
        @if ($item->parentesco === "CONYUGUE")
        <tr class="centered-row">
            <td>{{ \Carbon\Carbon::parse($df->fechanacimiento)->format('d-m-Y') ?? '' }}</td>
            <td>{{ $df->lugarlaboral ?? '' }}</td>
        </tr>
        @endif
        @endforeach
    </table> 
<h3>3. DATOS REFERENTES A LOS PADRES E HIJOS DEL TRABAJADOR</h3>
    <table>
        <tr>
            <th>NOMBRES Y APELLIDOS</th>
            <th>PARENTESCO</th>
            <th>FECHA DE NACIMIENTO</th>
            <th>OCUPACION</th>
            <th>ESTADO CIVIL</th>
            <th>VIVE</th>
        </tr>
        @foreach($dft as $item)
        @if ($item->parentesco !== "CONYUGUE")

        <tr class="centered-row">
            <td>{{ $item->nombres ?? '' }} {{ $item->apaterno ?? '' }} {{ $item->amaterno ?? '' }}</td>
            <td>{{ $item->parentesco ?? '' }}</td>
            <td>{{ \Carbon\Carbon::parse($item->fechanacimiento)->format('d-m-Y') ?? '' }}</td>
            <td>{{ $item->ocupacion ?? '' }}</td>
            <td>{{ $item->estadocivil ?? '' }}</td>
            <td>{{ $item->vive ?? '' }}</td>
        </tr>
        @endif

        @endforeach
    </table>
    <h3>4. DATOS DE FAMILIARES A QUIENES NOTIFICAR EN UNA SITUACION DE EMERGENCIA</h3>
    <table>
        <tr>
            <th>NOMBRES Y APELLIDOS</th>
            <th>PARENTESCO</th>
            <th>DIRECCION</th>
            <th>TELEFONO</th>
        </tr>
        @foreach($dft as $item)
        @if ($item->emergencia === 'SI')
        <tr class="centered-row">
            <td>{{ $item->nombres ?? '' }} {{ $item->apaterno ?? '' }} {{ $item->amaterno ?? '' }}</td>
            <td>{{ $item->parentesco ?? '' }}</td>
            <td>{{ $item->direccion ?? '' }}</td>
            <td>{{ $item->telefono ?? '' }}</td>
        </tr>
        @endif
        @endforeach
    </table>

    <h3>5. CONDICIÓN LABORAL</h3>
    <table>
        <tr>
            <th>CONDICIÓN LABORAL</th>
        </tr>
        <tr class="centered-row">
            <?php
            $latestRegimen = $dtlel->sortByDesc('fecha_ini')->last();
            ?>
            <td>{{ $latestRegimen->regimen ?? '' }} {{ $latestRegimen->condicion_laboral ?? '' }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <th>REGIMEN PENSIONARIO</th>
            <th>AFP</th>
        </tr>
        <tr class="centered-row">
            <td>{{ $dp->id_regimenp ?? '' }}</td>
            <td>{{ $dp->afp ?? '' }}</td>
        </tr>
    </table> 

    <h3>6. EXPERIENCIA LABORAL</h3>
    <table>
        <tr>
            <th>ENTIDAD</th>
            <th>PERIODO DEL AL</th>
            <th>CARGO</th>
        </tr>
        @foreach($exp as $item)
        <tr class="centered-row">
            <td>{{ $item->entidad ?? 'MUNICIPALIDAD PROVINCIAL DE LA CONVENCION' }}</td> 
            <td>{{ \Carbon\Carbon::parse($item->fecha_ini)->format('d-m-Y') ?? '' }} AL {{ \Carbon\Carbon::parse($item->fecha_fin)->format('d-m-Y') ?? '' }}</td>
            <td>{{ $item->cargo ?? '' }}</td>
        </tr>
        @endforeach
    </table>    

    <h3>7. DATOS DE ESTUDIO</h3>
    <table>
        <tr>
            <th>EDUCACION </th>
            <th>CENTRO DE ESTUDIOS</th>
            <th>DESDE</th>
            <th>HASTA</th>
            <th>COMPLETA Y/O INCOMPLETA</th>
            <th>ESPECIALIDAD</th>
        </tr>
        @foreach($des as $item)
        <tr class="centered-row">
            <td>{{ $item->nivel_educacion ?? '' }}</td>
            <td>{{ $item->centroestudios ?? '' }}</td>
            <td>{{ \Carbon\Carbon::parse($item->fecha_ini)->format('d-m-Y') ?? '' }}</td>
            <td>{{ \Carbon\Carbon::parse($item->fecha_fin)->format('d-m-Y') ?? '' }}</td>
            <td>{{ $item->estado ?? '' }}</td>
            <td>{{ $item->especialidad ?? '' }}</td>
        </tr>
        @endforeach
    </table> 
        <h3>8. CONOCIMIENTO DE IDIOMAS Y/O DIALECTO</h3>
        <table>
        <tr>
            <th>IDIOMA Y/O DIALESTO</th>
            <th>LEE</th>
            <th>HABLA</th>
            <th>ESCRIBE</th>

        </tr>
        @foreach($di as $item)
        <tr class="centered-row">
            <td>{{ $item->idioma ?? '' }}</td>
            <td>{{ $item->lectura ?? '' }}</td>
            <td>{{ $item->habla ?? '' }}</td>
            <td>{{ $item->escritura ?? '' }}</td>
        </tr>
        @endforeach
    </table> 
        <script type="text/php">
    if (isset($pdf)) {
        $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
        $size = 10;
        $font = $fontMetrics->getFont("Verdana");
        $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
        $x = ($pdf->get_width() - $width) / 2;
        $y = $pdf->get_height() - 35;
        $pdf->page_text($x, $y, $text, $font, $size);
    }
</script>
</html>