<!DOCTYPE html>
<html>
<head>
    <title>Informe Escalafonario </title>
<style>

@page {
            margin: 0cm 0cm;
            font-family: Arial;
        }
body {
        font-size: 0.8em;
        padding-top: 80px; /* Increased top padding */
        padding-bottom: 80px; /* Increased bottom padding */
        padding-left: 25px; /* Keeping left and right padding the same */
        padding-right: 25px;
    }

    header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
            background-color: #27692d;
            color: white;
            text-align: center;
            line-height: 30px;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
            background-color: #27692d;
            color: white;
            text-align: center;
            line-height: 35px;
        }
   /*border-left: 2px solid #094293;*/

    h2{
        text-align: center;
        color: green;
    }

    table, th, td {
        border: 1px solid;
        padding: 5px;
    }

    h3{
        color: black;
        margin: 1px;
        padding-top: 10px; /* Increased top padding */
        padding-bottom: 10px; /* Increased bottom padding */

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
@php
use Carbon\Carbon;
@endphp

<body>
<header>
    <h3>MUNICIPALIDAD PROVINCIAL DE LA CONVENCIN<br>OFICINA DE GESTION DE RECURSOS HUMANOS - ESCALAFON</br>
</header>

    <div class="content">

    <h2>INFORME ESCALAFONARIO<br><small>Creado el: {{ Carbon::now()->format('d/m/Y') }}</small></h2>
    {{ $dcamposSeleccionados ?? '' }}
    <br>
    @php
    $sectionNumber = 1;
    @endphp

    @if(in_array('1', $camposSeleccionados))
        <h3>{{ $sectionNumber++ }}. DATOS PERSONALES</h3>
        <table>
            <tr class="centered-row">
                <th>NOMBRES Y APELLIDOS</th>
                <td>{{ $dp->Nombres ?? '' }} {{ $dp->Apaterno ?? '' }} {{ $dp->Amaterno ?? '' }} </td>
            </tr>
            <tr class="centered-row">
                <th>REGIMEN LABORAL</th>
                <td>{{ $tireg[$dp->id_regimen] ?? '' }}</td>
            </tr>
            <tr class="centered-row">
                <th>CONDICION LABORAL</th>
                <td>{{ $timod[$dp->id_regimen_modalidad] ?? '' }}</td>
            </tr>
            <tr class="centered-row">
                <th>DNI</th>
                <td>{{ $dp->nro_documento_id ?? '' }}</td>
            </tr>
            <tr class="centered-row">
                <th>FECHA DE NACIMIENTO</th>
                <td>{{ isset($dp->FechaNacimiento) ? Carbon::parse($dp->FechaNacimiento)->format('d/m/Y') : '' }}</td>
            </tr>
            <tr class="centered-row">
                <th>VINCULO LABORAL</th>
                <td>
                    @if(isset($last_baja))
                        VINCULO LABORAL VIGENTE
                    @else
                        SIN VINCULO LABORAL
                    @endif
                </td>
            </tr>
            <tr class="centered-row">
                <th>FECHA DE INGRESO A LA ENTIDAD</th>
                <td>{{ $dconi->fecha_ini ?? '' }}</td>
            </tr>
            <tr class="centered-row">
                <th>DEPENDENCIA</th>
                <td>{{ $dcon->id_unidad_organica ?? '' }}</td>
            </tr>
            <tr class="centered-row">
                <th>ULTIMO CARGO</th>
                <td>{{ $dcon->id_unidad_organica ?? '' }}</td>
            </tr>
        </table>
    @endif

    @if(in_array('2', $camposSeleccionados))
        <h3>{{ $sectionNumber++ }}. DATOS FAMILIARES</h3>
        <table>
            <tr>
                <th>APELLIDOS Y NOMBRES</th>
                <th>PARENTESCO</th>
                <th>FECHA NAC.</th>
                <th>OCUPACION</th>
                <th>ESTADO CIVIL</th>
                <th>VIVE</th>
                <th>DIRECCION</th>
                <th>TELEFONO</th>
            </tr>
            @foreach($dft as $item)
            <tr class="centered-row">
            <td>{{ $item->apaterno ?? '' }} {{ $item->amaterno ?? '' }} {{ $item->nombres ?? '' }}</td>
            <td>{{ $item->parentesco ?? '' }}</td>
            <td>{{ $df->fechanacimiento ?? '' }}</td>
            <td>{{ $item->ocupacion ?? '' }}</td>
            <td>{{ $item->estadocivil ?? '' }}</td>
            <td>{{ $item->vive ?? '' }}</td>
            <td>{{ $item->direccion ?? '' }}</td>
            <td>{{ $item->telefono ?? '' }}</td>
            </tr>
            @endforeach

        </table>

    @endif

    @if(in_array('3', $camposSeleccionados))
    <h3>{{ $sectionNumber++ }}. PERIODO LABORADO</h3>
    <table>
        <tr>
            <th>ACCION</th>
            <th>DOCUMENTO</th>
            <th>REGIMEN</th>
            <th colspan="2">FECHA LABORADA<br>DEL AL</br></th>
            <th>TIEMPO DE SERVICIOS</th>
        </tr>
        @php
            $totalYears = $totalMonths = $totalDays = 0;
            $totalYears2 = $totalMonths2 = $totalDays2 = 0;
            $today = Carbon::today();
        @endphp
        @foreach($dtl as $item)
        <tr class="centered-row">
            @if($tdoc2[$item->id_accion_vin] == "1")
                <td>{{ $tdoc[$item->id_accion_vin] ?? '' }}</td>
                <td>{{ $tdoc[$item->id_tipo_documento] ?? '' }}  {{ $item->nro_doc ?? '' }}</td>
                <td>{{ $tireg[$item->id_regimen] ?? '' }}</td>
                <td>{{ isset($item->fecha_ini) ? Carbon::parse($item->fecha_ini)->format('d/m/Y') : '' }}</td>
                <td>{{ isset($item->fecha_fin) ? Carbon::parse($item->fecha_fin)->format('d/m/Y') : Carbon::now()->format('d/m/Y') }}</td>
                <td>
                    @if(isset($item->fecha_ini))
                        @php
                            $inicio = Carbon::parse($item->fecha_ini);
                            $fin = isset($item->fecha_fin) ? Carbon::parse($item->fecha_fin) : $today;
                            if ($fin->greaterThan($today)) {
                                $fin = $today;
                            }
                            $diff = $inicio->diff($fin);
                            $totalYears += $diff->y;
                            $totalMonths += $diff->m;
                            $totalDays += $diff->d;
                            while ($totalDays >= 30) {
                                $totalDays -= 30;
                                $totalMonths++;
                            }
                            while ($totalMonths >= 12) {
                                $totalMonths -= 12;
                                $totalYears++;
                            }
                        @endphp
                        {{ $diff->y }} años, {{ $diff->m }} meses, {{ $diff->d }} días
                    @else
                        N/A
                    @endif
                </td>
            @else
                <td>{{ $tdoc[$item->id_accion_vin] ?? '' }}</td>
                <td>{{ $tdoc[$item->id_tipo_documento] ?? '' }}  {{ $item->nro_doc ?? '' }}</td>
                <td>{{ $tireg[$item->id_regimen] ?? '' }}</td>
                <td>{{ isset($item->fecha_ini) ? Carbon::parse($item->fecha_ini)->format('d/m/Y') : '' }}</td>
                <td>{{ isset($item->fecha_fin) ? Carbon::parse($item->fecha_fin)->format('d/m/Y') : '' }}</td>
                <td>
                    @if(isset($item->fecha_ini) && isset($item->fecha_fin))
                        @php
                            $inicio2 = Carbon::parse($item->fecha_ini);
                            $fin2 = isset($item->fecha_fin) ? Carbon::parse($item->fecha_fin) : $today;
                            if ($fin2->greaterThan($today)) {
                                $fin2 = $today;
                            }
                            $diff2 = $inicio2->diff($fin2);
                            $totalYears2 += $diff2->y;
                            $totalMonths2 += $diff2->m;
                            $totalDays2 += $diff2->d;
                            while ($totalDays2 >= 30) {
                                $totalDays2 -= 30;
                                $totalMonths2++;
                            }
                            while ($totalMonths2 >= 12) {
                                $totalMonths2 -= 12;
                                $totalYears2++;
                            }
                        @endphp
                        {{ $diff2->y }} años, {{ $diff2->m }} meses, {{ $diff2->d }} días
                    @else
                    N/A
                   @endif
                </td>
            @endif
        </tr>
    @endforeach

        <!-- Subtotal -->
        <tr class="centered-row">
            <td colspan="5" style="text-align: center;"><b>Subtotal</b></td>
            <td><b>{{ $totalYears }} años, {{ $totalMonths }} meses, {{ $totalDays }} días</b></td>
        </tr>

        <!-- Licencias -->
        @php
            $totalDiasLicencia = 0;
        @endphp
        @foreach($dli as $licencia)
            @if($licencia->congoce == 'No')
                @php
                    $inicioLicencia = Carbon::parse($licencia->fecha_ini);
                    $finLicencia = Carbon::parse($licencia->fecha_fin);
                    $diasLicencia = $inicioLicencia->diffInDays($finLicencia) + 1; // Incluir ambos días
                    $totalDiasLicencia += $diasLicencia;
                @endphp
            @endif
        @endforeach
        <tr class="centered-row">
            <td colspan="5" style="text-align: center;"><b>Días de Licencias (Sin Goce)</b></td>
            <td><b>{{ $totalDiasLicencia }} días</b></td>
        </tr>

        <!-- Sanciones -->
        @php
            $totalDiasSancion = 0;
        @endphp
        @foreach($dsan as $sancion)
            @php
                $inicioSancion = Carbon::parse($sancion->desde);
                $finSancion = Carbon::parse($sancion->hasta);
                $diasSancion = $inicioSancion->diffInDays($finSancion) + 1; // Incluir ambos días
                $totalDiasSancion += $diasSancion;
            @endphp
        @endforeach
        <tr class="centered-row">
            <td colspan="5" style="text-align: center;"><b>Días de Sanciones</b></td>
            <td><b>{{ $totalDiasSancion }} días</b></td>
        </tr>

        <!-- Total Tiempo de Servicio -->
        @php
            $totalDaysService = ($totalYears * 365) + ($totalMonths * 30) + $totalDays;
            $totalDaysService -= ($totalDiasLicencia + $totalDiasSancion);
            $finalYears = intdiv($totalDaysService, 365);
            $remainingDays = $totalDaysService % 365;
            $finalMonths = intdiv($remainingDays, 30);
            $finalDays = $remainingDays % 30;
        @endphp
        <tr class="centered-row">
            <td colspan="5" style="text-align: center;"><b>Total Tiempo de Servicio</b></td>
            <td><b>{{ $finalYears }} años, {{ $finalMonths }} meses, {{ $finalDays }} días</b></td>
        </tr>
    </table>
@endif




@if(in_array('4', $camposSeleccionados))
<h3>{{ $sectionNumber++ }}. TRAYECTORIA LABORAL</h3>
    <table>
        <tr>
            <th>ACCION</th>
            <th>REGIMEN</th>
            <th>MODALIDAD</th>
            <th>CARGO</th>
            <th>DEPENDENCIA</th>
            <th>FECHA INI</th>
            <th>FECHA FIN</th>
        </tr>

        @foreach($dtl as $item)
        <tr class="centered-row">
            <td>{{ $tdoc[$item->id_accion_vin] ?? '' }}</td>
            <td>{{ $tireg[$item->id_regimen] ?? '' }}</td>
            <td>{{ $timod[$item->id_regimen_modalidad] ?? '' }}</td>
            <td>
                {{ $item->id_unidad_organica ?? '' }}
                @if(isset($item->obras_pro))
                    @php
                        $obras = json_decode($item->obras_pro);
                    @endphp
                    @if(is_array($obras) && count($obras) > 0)
                        <ul>
                            @foreach($obras as $obra)
                                <li>{{ $obra }}</li>
                            @endforeach
                        </ul>
                    @endif
                @endif
            </td>
            <td>{{ $item->id_unidad_organica ?? '' }}</td>
            <td>{{ isset($item->fecha_ini) ? Carbon::parse($item->fecha_ini)->format('d/m/Y') : '' }}</td>
            <td>{{ isset($item->fecha_fin) ? Carbon::parse($item->fecha_fin)->format('d/m/Y') : '' }}</td>
        </tr>
        @endforeach
    </table>
@endif
@if(in_array('5', $camposSeleccionados))
    <h3>{{ $sectionNumber++ }}. EXPERIENCIA LABORAL</h3>
    <table>
        <tr>
            <th>ENTIDAD</th>
            <th>CARGO</th>
            <th>FECHA INI</th>
            <th>FECHA FIN</th>
        </tr>
        @foreach($exp as $item)
        <tr class="centered-row">
            <td>{{ $item->entidad ?? 'MUNICIPALIDAD PROVINCIAL DE LA CONVENCION' }}</td>
            <td>{{ $item->cargo ?? $item->id_unidad_organica ?? '' }}</td>
            <td>{{ isset($item->fecha_ini) ? Carbon::parse($item->fecha_ini)->format('d/m/Y') : '' }}</td>
            <td>{{ isset($item->fecha_fin) ? Carbon::parse($item->fecha_fin)->format('d/m/Y') : '' }}</td>
        </tr>
        @endforeach
    </table>
@endif

@if(in_array('6', $camposSeleccionados))

<h3>{{ $sectionNumber++ }}. ESTUDIOS</h3>
<table>
    <tr>

        <th>EDUCACION</th>
        <th>COMPLETA Y/O INCOMPLETA</th>
        <th>CENTRO DE ESTUDIOS</th>
        <th>DESDE</th>
        <th>HASTA</th>
    </tr>

    @foreach($des as $item)
        @if ($item->nivel_educacion == "PRIMARIA" || $item->nivel_educacion == "SECUNDARIA")
    <tr class="centered-row">
        <td>{{ $item->nivel_educacion ?? '' }}</td>
        <td>{{ $item->estado ?? '' }}</td>
        <td>{{ $item->centroestudios ?? '' }}</td>
        <td>{{ isset($item->fecha_ini) ? Carbon::parse($item->fecha_ini)->format('d/m/Y') : '' }}</td>
        <td>{{ isset($item->fecha_fin) ? Carbon::parse($item->fecha_fin)->format('d/m/Y') : '' }}</td>
    </tr>
        @endif
    @endforeach
</table>

<table>
    <tr>
        <th>EDUCACION SUPERIOR</th>
        <th>ESPECIALIDAD</th>
        <th>CENTRO DE ESTUDIOS</th>
        <th>DESDE</th>
        <th>HASTA</th>
    </tr>
    @foreach($des as $item)
        @if ($item->nivel_educacion !== "PRIMARIA" && $item->nivel_educacion !== "SECUNDARIA")
    <tr class="centered-row">
        <td>{{ $item->nivel_educacion ?? '' }}</td>
        <td>{{ $item->especialidad ?? '' }}</td>
        <td>{{ $item->centroestudios ?? '' }}</td>
        <td>{{ isset($item->fecha_ini) ? Carbon::parse($item->fecha_ini)->format('d/m/Y') : '' }}</td>
        <td>{{ isset($item->fecha_fin) ? Carbon::parse($item->fecha_fin)->format('d/m/Y') : '' }}</td>
    </tr>
        @endif

    @endforeach
</table>
@endif

@if(in_array('7', $camposSeleccionados))
<h3>{{ $sectionNumber++ }}. OTROS ESTUDIOS</h3>
<table>
    <tr>
        <th>DENOMINACION</th>
        <th>CENTRO DE ESTUDIOS</th>
        <th>DESDE</th>
        <th>HASTA</th>
        <th>HORAS</th>
    </tr>
    @foreach($desex as $item)
    <tr class="centered-row">
        <td>{{ $item->nombre ?? '' }} </td>
        <td>{{ $item->centroestudios ?? '' }}</td>
        <td>{{ isset($item->fecha_ini) ? Carbon::parse($item->fecha_ini)->format('d/m/Y') : '' }}</td>
        <td>{{ isset($item->fecha_fin) ? Carbon::parse($item->fecha_fin)->format('d/m/Y') : '' }}</td>
        <td>{{ $item->horas ?? '' }}</td>
    </tr>
    @endforeach
</table>
@endif

@if(in_array('8', $camposSeleccionados))
<h3>{{ $sectionNumber++ }}. RECONOCIMIENTOS</h3>
<table>
    <tr>
        <th>DESCRIPCIÓN</th>
        <th>FECHA DOCUMENTO</th>
    </tr>
    @foreach($drec as $item)
    <tr class="centered-row">
        <td>{{ $item->descripcion ?? '' }} </td>
        <td>{{ isset($item->fechadoc) ? Carbon::parse($item->fechadoc)->format('d/m/Y') : '' }}</td>
    </tr>
    @endforeach
</table>
@endif

@if(in_array('9', $camposSeleccionados))
<h3>{{ $sectionNumber++ }}. SANCIONES</h3>
<table>
    <tr>
        <th>DOCUMENTO</th>
        <th>DESCRIPCIÓN</th>
        <th>DIAS DE SANCION</th>
        <th>DESDE</th>
        <th>HASTA</th>
    </tr>
    @foreach($dsan as $item)
    <tr class="centered-row">
        <td>{{ $tdoc[$item->idtd] ?? '' }} {{ $item->nrodoc ?? '' }}</td>
        <td>{{ $item->descripcion ?? '' }} </td>
        <td>
            @if(isset($item->desde) && isset($item->hasta))
                @php
                    $inicio = Carbon::parse($item->desde);
                    $fin = Carbon::parse($item->hasta);
                    $dias = $inicio->diffInDays($fin) + 1;
                @endphp
                {{ $dias }}
            @else
                N/A
            @endif
        </td>
        <td>{{ isset($item->desde) ? Carbon::parse($item->desde)->format('d/m/Y') : '' }}</td>
        <td>{{ isset($item->hasta) ? Carbon::parse($item->hasta)->format('d/m/Y') : '' }}</td>
    </tr>
    @endforeach
</table>

@endif

@if(in_array('10', $camposSeleccionados))
<h3>{{ $sectionNumber++ }}. COMPENSACIONES</h3>
<table>
    <tr>
        <th>TIPO COMPENSACION</th>
        <th>DESCRIPCIÓN</th>
        <th>FECHA DOCUMENTO</th>
    </tr>
    @foreach($dcom as $item)
    <tr class="centered-row">
        <td>{{ $item->tipo_compensacion ?? '' }} </td>
        <td>{{ $item->descripcion ?? '' }} </td>
        <td>{{ isset($item->fecha_documento) ? Carbon::parse($item->fecha_documento)->format('d/m/Y') : '' }}</td>
    </tr>
    @endforeach
</table>
@endif

@if(in_array('11', $camposSeleccionados))
<h3>{{ $sectionNumber++ }}. VACACIONES</h3>
<table>
    <tr>
        <th>DOCUMENTO</th>
        <th>DESDE</th>
        <th>HASTA</th>
        <th>DIAS</th>
    </tr>
    @foreach($dvac as $item)
    <tr class="centered-row">
        <td>{{ $tdoc[$item->idtd] ?? '' }} {{ $item->nrodoc ?? '' }}</td>
        <td>{{ isset($item->fecha_ini) ? Carbon::parse($item->fecha_ini)->format('d/m/Y') : '' }}</td>
        <td>{{ isset($item->fecha_fin) ? Carbon::parse($item->fecha_fin)->format('d/m/Y') : '' }}</td>
        <td>
            @if(isset($item->fecha_ini) && isset($item->fecha_fin))
                @php
                    $inicio = Carbon::parse($item->fecha_ini);
                    $fin = Carbon::parse($item->fecha_fin);
                    $dias = $inicio->diffInDays($fin) + 1;
                @endphp
                {{ $dias }}
            @else
                N/A
            @endif
        </td>
    </tr>
    @endforeach
</table>
@endif




@if(in_array('12', $camposSeleccionados))
<h3>{{ $sectionNumber++ }}. LICENCIAS</h3>
<table>
    <tr>
        <th>DOCUMENTO</th>
        <th>DESCRIPCIÓN</th>
        <th>DIAS</th>
        <th>DESDE</th>
        <th>HASTA</th>
    </tr>
    @foreach($dli as $item)
    <tr class="centered-row">
        <td>{{ $tdoc[$item->idtd] ?? '' }} {{ $item->nrodoc ?? '' }}</td>
        <td>{{ $item->descripcion ?? '' }} </td>
        <td>
            @if(isset($item->fecha_ini) && isset($item->fecha_fin))
                @php
                    $inicio = Carbon::parse($item->fecha_ini);
                    $fin = Carbon::parse($item->fecha_fin);
                    $dias = $inicio->diffInDays($fin) + 1;
                @endphp
                {{ $dias }}
            @else
                N/A
            @endif
        </td>
        <td>{{ isset($item->fecha_ini) ? Carbon::parse($item->fecha_ini)->format('d/m/Y') : '' }}</td>
        <td>{{ isset($item->fecha_fin) ? Carbon::parse($item->fecha_fin)->format('d/m/Y') : '' }}</td>

    </tr>
    @endforeach
</table>
@endif

@if(in_array('13', $camposSeleccionados))
<h3>{{ $sectionNumber++ }}. PERMISOS</h3>
<table>
    <tr>
        <th>DOCUMENTO</th>
        <th>DIAS</th>
        <th>DESDE</th>
        <th>HASTA</th>
    </tr>
    @foreach($dper as $item)
    <tr class="centered-row">
        <td>{{ $tdoc[$item->idtd] ?? '' }} {{ $item->nrodoc ?? '' }}</td>
        <td>
            @if(isset($item->fecha_ini) && isset($item->fecha_fin))
                @php
                    $inicio = Carbon::parse($item->fecha_ini);
                    $fin = Carbon::parse($item->fecha_fin);
                    $dias = $inicio->diffInDays($fin) + 1;
                @endphp
                {{ $dias }}
            @else
                N/A
            @endif
        </td>
        <td>{{ isset($item->fecha_ini) ? Carbon::parse($item->fecha_ini)->format('d/m/Y') : '' }}</td>
        <td>{{ isset($item->fecha_fin) ? Carbon::parse($item->fecha_fin)->format('d/m/Y') : '' }}</td>

    </tr>
    @endforeach
</table>
@endif

@if(in_array('14', $camposSeleccionados))
<h3>{{ $sectionNumber++ }}. IDIOMA</h3>
<table>
    <tr>
        <th>IDIOMA Y/O DIALECTO</th>
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

@endif


</div>
<footer>
    <h1></h1>
</footer>
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
</body>
</html>
