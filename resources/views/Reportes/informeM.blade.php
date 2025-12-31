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
            height: 2.5cm;
            background-color: #ceead6;
            color: white;
            text-align: center;
            line-height: 25px;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
            background-color: #ceead6;
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
.leftpersonal-row tr {
    text-align: left;
    display: table-cell;
    vertical-align: middle;
    text-transform: uppercase;

}
.left-row {
    text-align: left;
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
    <table style="width: 100%; border: 0px; cellpadding: '0'; border-collapse: collapse" >
        <tr style="border: none;">
            <td style="border: none; text-align: center;"><img src="{{ asset('/img/logo_informe.png') }}"></td>
            <td style="text-align: center; border: none;">
                <h3 style="color: green; font-size: 1.5em; padding: 0px;">MUNICIPALIDAD PROVINCIAL DE LA CONVENCIÓN<br>OFICINA DE GESTION DE RECURSOS HUMANOS - ESCALAFON</h3>
                <hr style="margin: 0px; padding: 0px; color: darkgreen">
                <p style="color:green; font-size: 1em; font-weight: bold; text-align:  center; padding: 0px; margin: 0px">Año de la Recuperación y la Consolidación de la Economía Peruana</p>
            </td>
        </tr>
    </table>
</header>
    <div class="content">
    <h2>INFORME ESCALAFONARIO<br><small>Creado el: {{ Carbon::now()->format('d/m/Y') }}</small></h2>
    {{ $dcamposSeleccionados ?? '' }}
    <br>
    @php
    $sectionNumber = 1;
    @endphp
@foreach($informes as $informe)
@if(in_array('1', $camposSeleccionados))
    <h3>{{ $sectionNumber++ }}. DATOS PERSONALES</h3>
    <table>
        <tr class="leftpersonal-row">
            <th class="left-row">NOMBRES Y APELLIDOS</th>
            <td>{{ $informe['dp']->Nombres ?? '' }} {{ $informe['dp']->Apaterno ?? '' }} {{ $informe['dp']->Amaterno ?? '' }}</td>
        </tr>
        <tr class="leftpersonal-row">
            <th class="left-row">DNI</th>
            <td>{{ $informe['dp']->nro_documento_id ?? '' }}</td>
        </tr>
        <tr class="leftpersonal-row">
            <th class="left-row">FECHA DE NACIMIENTO</th>
            <td>{{ isset($informe['dp']->FechaNacimiento) ? Carbon::parse($informe['dp']->FechaNacimiento)->format('d/m/Y') : '' }}</td>
        </tr>
        <tr class="leftpersonal-row">
            <th class="left-row">EDAD</th>
            <td>
                @if(isset($informe['dp']->FechaNacimiento))
                    {{ Carbon::parse($informe['dp']->FechaNacimiento)->age }}
                @endif
            </td>
        </tr>
        <tr class="leftpersonal-row">
            <th class="left-row">DOMICILIO</th>
                <td>
                @if(isset($dd))

                    {{ $dd->tipodom ?? '' }} {{ $dd->dactual ?? '' }} NRO {{ $dd->numero ?? 'S/N' }}
                    @endif
                </td>
        </tr>
        <tr class="leftpersonal-row">
            <th class="left-row">NRO CELULAR</th>
            <td>{{ $dp->NroCelular ?? '' }}</td>
        </tr>
        <tr class="leftpersonal-row">
            <th class="left-row">CORREO ELECTRONICO</th>
            <td>{{ $dp->Correo ?? '' }}</td>
        </tr>
        <tr class="leftpersonal-row">
            <th class="left-row">REGIMEN LABORAL</th>
            <td>{{ $tireg[$informe['dvin_ult']->id_regimen  ?? ''] ?? '' }}</td>
        </tr>
        <tr class="leftpersonal-row">
            <th class="left-row">CONDICION LABORAL</th>
            <td>{{ $tconlab[$informe['dvin_ult']->id_condicion_laboral  ?? '']->descripcion_regimen ?? '' }}</td>
        </tr>
        <tr class="leftpersonal-row">
            <th class="left-row">VINCULO LABORAL</th>
            <td>{{ $informe['estado'] ?? 'SIN VINCULO LABORAL' }}</td>
        </tr>
        @if(isset($informe['fechacese']) && !empty($informe['fechacese']))
        <tr class="leftpersonal-row">
            <th class="left-row">FECHA DE CESE</th>
                <td>{{ Carbon::parse($informe['fechacese'])->format('d/m/Y') }}</td>
            </tr>
        @endif

        <tr class="leftpersonal-row">
            <th class="left-row">FECHA DE INGRESO A LA ENTIDAD</th>
            <td>{{ isset($informe['dconi']->fecha_ini) ? Carbon::parse($informe['dconi']->fecha_ini)->format('d/m/Y') : '' }}</td>
        </tr>
        <tr class="leftpersonal-row">
            <th class="left-row">OFICINA</th>
            <td>{{ $informe['dcon']->id_unidad_organica ?? '' }}</td>
        </tr>
        <tr class="centered-row">
            <th class="left-row">CARGO</th>
            <td>{{ $informe['dcon']->id_unidad_organica ?? '' }}</td>
        </tr>
    </table>
@endif

@if(in_array('2', $camposSeleccionados))
<h3>DATOS FAMILIARES</h3>
    @if(count($informe['dft']) > 0)
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
            @foreach($informe['dft'] as $item)
            <tr class="centered-row">
                <td>{{ $item->apaterno ?? '' }} {{ $item->amaterno ?? '' }} {{ $item->nombres ?? '' }}</td>
                <td>{{ $item->parentesco ?? '' }}</td>
                <td>{{ $item->fechanacimiento ?? '' }}</td>
                <td>{{ $item->ocupacion ?? '' }}</td>
                <td>{{ $item->estadocivil ?? '' }}</td>
                <td>{{ $item->vive ?? '' }}</td>
                <td>{{ $item->direccion ?? '' }}</td>
                <td>{{ $item->telefono ?? '' }}</td>
            </tr>
            @endforeach
        </table>
    @else
        <p>No existen datos relacionados con el personal.</p>
    @endif
@endif


@if(in_array('3', $camposSeleccionados))
<h3>PERIODO LABORADO</h3>
<table>
    <tr>
        <th>DOCUMENTO</th>
        <th>REGIMEN</th>
        <th colspan="2">FECHA LABORADA<br>DEL AL</br></th>
        <th>TIEMPO DE SERVICIOS</th>
    </tr>
    @php
        $totalYears = $totalMonths = $totalDays = 0;
        $totalYears2 = $totalMonths2 = $totalDays2 = 0;
        $initialContract = collect($informe['dtl'])
        ->whereNull('fecha_fin')
        ->sortBy('fecha_ini')
        ->first();

        $today = Carbon::today();
    @endphp
    @foreach($informe['dtl'] as $item)
    <tr class="centered-row">
            <td>{{ $tdoc[$item->id_tipo_documento] ?? '' }}  {{ $item->nrodoc ?? '' }}</td>
            <td>{{ $tireg[$item->id_regimen] ?? '' }}</td>
            <td>{{ isset($item->fecha_ini) ? Carbon::parse($item->fecha_ini)->format('d/m/Y') : '' }}</td>
            <!-- Fecha calculada casos donde no hay fecha fin -->
                        <!-- PRIMERA VALIDACION SI HAY FECHA FIN COMPARA CON LA ACTUAL -->
                        <!-- SEGUNDA VALIDACION SI NO HAY FECHA FIN SE ASIGNA LA ACTUAL -->

            <td>
                {{
                    isset($item->fecha_fin)
                    ? (Carbon::parse($item->fecha_fin)->gt(Carbon::now())
                        ? Carbon::now()->format('d/m/Y')
                        : Carbon::parse($item->fecha_fin)->format('d/m/Y'))
                    : (Carbon::now()->format('d/m/Y'))
                }}
            </td>
            <td>
                @if(isset($item->fecha_ini))
                    @php
                        $inicio = Carbon::parse($item->fecha_ini);
                        $fin = isset($item->fecha_fin)
                        ? Carbon::parse($item->fecha_fin)
                        : Carbon::now();

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
    </tr>
    @endforeach

    <!-- Subtotal -->
    <tr class="centered-row">
        <td colspan="4" style="text-align: center;"><b>Total Tiempo de Servicios Oficiales</b></td>
        <td><b>{{ $totalYears }} años, {{ $totalMonths }} meses, {{ $totalDays }} días</b></td>
    </tr>

    <!-- TIEMPO SERVICIO -->
    @php
        $totalcompDias = 0;
        $totalcompMeses = 0;
        $totalcompAnios = 0;
    @endphp

    @foreach($informe['dtser'] as $comp)
        @php
            $iniciocomp = Carbon::parse($comp->fecha_ini);
            $fincomp = Carbon::parse($comp->fecha_fin);
            $diascomp = $iniciocomp->diffInDays($fincomp);
            $totalcompDias += $diascomp;

            // Calcular diferencia en días, meses y años
            $diff = $iniciocomp->diff($fincomp);
            $dias = $diff->d;
            $meses = $diff->m;
            $anios = $diff->y;
        @endphp
        <tr class="centered-row">
            <td colspan="4" style="text-align: right;">{{ $comp->descripcion }}</td>
            <td>(+) {{ $anios }} años, {{ $meses }} meses, {{ $dias }} días</td>
        </tr>
    @endforeach

    <!-- Licencias -->
    @php
        $totalDiasLicencia = 0;
    @endphp
    @foreach($informe['dli'] as $licencia)
            @php
                $inicioLicencia = Carbon::parse($licencia->fecha_ini);
                $finLicencia = Carbon::parse($licencia->fecha_fin);
                $diasLicencia = $inicioLicencia->diffInDays($finLicencia) + 1; // Incluir ambos días
                $totalDiasLicencia += $diasLicencia;
            @endphp
    @endforeach

    @php
        // Calcular la diferencia total en formato de días, meses y años
        $fechaReferencia = Carbon::now()->subDays($totalDiasLicencia);
        $fechaActual = Carbon::now();
        $diferencia = $fechaReferencia->diff($fechaActual);
        $aniosLicencia = $diferencia->y;
        $mesesLicencia = $diferencia->m;
        $diasLicencia = $diferencia->d;
    @endphp

    <tr class="centered-row">
        <td colspan="4" style="text-align: right;">Licencias (Sin Goce)</td>
        <td>(-) {{ $aniosLicencia }} AÑOS, {{ $mesesLicencia }} MESES, {{ $diasLicencia }} DÍAS</td>
    </tr>

    <!-- Sanciones -->
    @php
        $totalDiasSancion = 0;
    @endphp
    @foreach($informe['dsan'] as $sancion)
        @php
            $totalDiasSancion += $sancion->dias_san;
        @endphp
    @endforeach
    @php
        // Calcular la diferencia total en formato de días, meses y años
        $fechaReferencias = Carbon::now()->subDays($totalDiasSancion);
        $fechaActuals = Carbon::now();
        $diferencias = $fechaReferencias->diff($fechaActuals);
        $anioss = $diferencias->y;
        $mesess = $diferencias->m;
        $diass = $diferencias->d;
    @endphp

    <tr class="centered-row">
        <td colspan="4" style="text-align: right;">Sanciones</td>
        <td>(-) {{ $anioss }} AÑOS, {{ $mesess }} MESES, {{ $diass }} DÍAS</td>
    </tr>

    <!-- Total Tiempo de Servicio -->
    @php
        $totalDaysService = ($totalYears * 365) + ($totalMonths * 30) + $totalDays + $totalcompDias;
        $totalDaysService -= ($totalDiasLicencia + $totalDiasSancion);
        $finalYears = intdiv($totalDaysService, 365);
        $remainingDays = $totalDaysService % 365;
        $finalMonths = intdiv($remainingDays, 30);
        $finalDays = $remainingDays % 30;
    @endphp
    <tr class="centered-row">
        <td colspan="4" style="text-align: center;"><b>Total Tiempo de Servicio</b></td>
        <td><b>{{ $finalYears }} años, {{ $finalMonths }} meses, {{ $finalDays }} días</b></td>
    </tr>
</table>
@endif


@if(in_array('4', $camposSeleccionados))
    <h3>TRAYECTORIA LABORAL</h3>
    @if(count($informe['dtl']) > 0)
        @if(isset($years) && !empty($years))
            @foreach(explode(',', $years) as $year)
                <table>
                    <tr>
                        <th colspan="6">PERIODO: {{ $year }}</th>
                    </tr>
                    <tr>
                        <th>CONDICION LABORAL</th>
                        <th>DOCUMENTO</th>
                        <th>CARGO</th>
                        <th>DEPENDENCIA</th>
                        <th>FECHA INICIO</th>
                        <th>FECHA FIN</th>
                    </tr>
                    @foreach($informe['dtl'] as $item)
                        @if(Carbon::parse($item->fecha_ini)->year == $year)
                            <tr class="centered-row">
                                <td>{{ $tconlab[$item->id_condicion_laboral  ?? ''] ?? '' }}</td>
                                <td>{{ $tdoc[$item->id_tipo_documento] ?? '' }}  {{ $item->nrodoc ?? '' }}</td>
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
                        @endif
                    @endforeach
                </table>
            @endforeach
        @else
            <table>
                <tr>
                    <th>CONDICION LABORAL</th>
                    <th>DOCUMENTO</th>
                    <th>CARGO</th>
                    <th>DEPENDENCIA</th>
                    <th>FECHA INICIO</th>
                    <th>FECHA FIN</th>
                </tr>
                @foreach($informe['dtl'] as $item)
                    <tr class="centered-row">
                        <td>{{ $tconlab[$item->id_condicion_laboral  ?? '']->nombre ?? '' }}</td>
                        <td>{{ $tdoc[$item->id_tipo_documento] ?? '' }}  {{ $item->nrodoc ?? '' }}</td>
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
    @else
    <p>No existen datos relacionados con el personal.</p>
    @endif
@endif

@if(in_array('6', $camposSeleccionados))
    <h3>ESTUDIOS</h3>
    @if(count($informe['des']) > 0)
        <table>
            <tr>
                <th>EDUCACION </th>
                <th>CENTRO DE ESTUDIOS</th>
                <th>DESDE</th>
                <th>HASTA</th>
                <th>ESPECIALIDAD</th>
            </tr>
            @foreach($informe['des'] as $item)
                <tr class="centered-row">
                    <td>{{ $item->nivel_educacion ?? '' }}</td>
                    <td>{{ $item->centroestudios ?? '' }}</td>
                    <td>{{ $item->fecha_ini ?? '' }}</td>
                    <td>{{ $item->fecha_fin ?? '' }}</td>
                    <td>{{ $item->especialidad ?? '' }}</td>
                </tr>
            @endforeach
        </table>
    @else
    <p>No existen datos relacionados con el personal.</p>
    @endif
@endif

@if(in_array('7', $camposSeleccionados))
    <h3>OTROS ESTUDIOS</h3>
    @if(count($informe['desex']) > 0)
        <table>
            <tr>
                <th>DENOMINACION</th>
                <th>CENTRO DE ESTUDIOS</th>
                <th>DESDE</th>
                <th>HASTA</th>
                <th>HORAS</th>
            </tr>
            @foreach($informe['desex'] as $item)
            <tr class="centered-row">
                <td>{{ $item->nombre ?? '' }} </td>
                <td>{{ $item->centroestudios ?? '' }}</td>
                <td>{{ isset($item->fecha_ini) ? Carbon::parse($item->fecha_ini)->format('d/m/Y') : '' }}</td>
                <td>{{ isset($item->fecha_fin) ? Carbon::parse($item->fecha_fin)->format('d/m/Y') : '' }}</td>
                <td>{{ $item->horas ?? '' }}</td>
            </tr>
            @endforeach
        </table>
    @else
    <p>No existen datos relacionados con el personal.</p>
    @endif
@endif

@if(in_array('14', $camposSeleccionados))
    <h3>IDIOMA</h3>
    @if(count($informe['di']) > 0)
        <table>
            <tr>
                <th>IDIOMA Y/O DIALECTO</th>
                <th>LEE</th>
                <th>HABLA</th>
                <th>ESCRIBE</th>

            </tr>
            @foreach($informe['di'] as $item)
            <tr class="centered-row">
                <td>{{ $item->idioma ?? '' }}</td>
                <td>{{ $item->lectura ?? '' }}</td>
                <td>{{ $item->habla ?? '' }}</td>
                <td>{{ $item->escritura ?? '' }}</td>
            </tr>
            @endforeach
        </table>
    @else
    <p>No existen datos relacionados con el personal.</p>
    @endif
@endif

@if(in_array('5', $camposSeleccionados))
    <h3>EXPERIENCIA LABORAL</h3>
    @if(count($informe['exp']) > 0)
        <table>
            <tr>
                <th>ENTIDAD</th>
                    @if(isset($item->fecha_ini) && isset($item->fecha_fin))
                        @php
                            $inicio = Carbon::parse($item->fecha_ini);
                            $fin = Carbon::parse($item->fecha_fin);
                            $dias = $inicio->diffInDays($fin) + 1;
                        @endphp
                        {{ $dias }}
                    @else
                        0
                    @endif
                    <th>CARGO</th>
                <th>FECHA INI</th>
                <th>FECHA FIN</th>
            </tr>
            @foreach($informe['exp'] as $item)
            <tr class="centered-row">
                <td>{{ $item->entidad ?? 'MUNICIPALIDAD PROVINCIAL DE LA CONVENCIÓN' }}</td>
                <td>{{ $item->cargo ?? '' }}</td>
                <td>{{ isset($item->fecha_ini) ? Carbon::parse($item->fecha_ini)->format('d/m/Y') : '' }}</td>
                <td>{{ isset($item->fecha_fin) ? Carbon::parse($item->fecha_fin)->format('d/m/Y') : '' }}</td>
            </tr>
            @endforeach
        </table>
    @else
        <p>No existen datos relacionados con el personal.</p>
    @endif
@endif


@if(in_array('15', $camposSeleccionados))
    <h3>ROTACIONES</h3>
    @if(count($informe['ddesplz']) > 0)

        <table>
            <tr>
                <th>DOCUMENTO</th>
                <th>UNIDAD ORGANICA ORIGEN</th>
                <th>UNIDAD ORGANICA DESTINO</th>
                <th>CARGO</th>
                <th>FECHA INICIO</th>
                <th>FECHA FIN</th>
            </tr>
            @foreach($informe['ddesplz'] as $item)
            <tr class="centered-row">
                <td>{{ $tdoc[$item->idtd] ?? '' }}  {{ $item->nrodoc ?? '' }}</td>
                <td>{{ $tarea[$item->unidad_organica] ?? '' }}</td>
                <td>{{ $tarea[$item->unidad_organica_destino] ?? '' }}</td>
                <td>{{ $tcargo[$item->cargo] ?? '' }}</td>
                <td>{{ isset($item->fecha_ini) ? Carbon::parse($item->fecha_ini)->format('d/m/Y') : '' }}</td>
                <td>{{ isset($item->fecha_fin) ? Carbon::parse($item->fecha_fin)->format('d/m/Y') : '' }}</td>
            </tr>
            @endforeach
        </table>
    @else
        <p>No existen datos relacionados con el personal.</p>
    @endif
@endif

@if(in_array('11', $camposSeleccionados))

<h3>VACACIONES</h3>
    @if(count($informe['dvac']) > 0)

        <table>
            <tr>
                <th>DOCUMENTO</th>
                <th>PERIODO</th>
                <th>DESDE</th>
                <th>HASTA</th>
                <th>DIAS</th>
            </tr>
            @foreach($informe['dvac'] as $item)
            <tr class="centered-row">
                <td>{{ $tdoc[$item->idtd] ?? '' }} {{ $item->nrodoc ?? '' }}</td>
                <td>{{ isset($item->periodo) ? $item->periodo : '' }}</td>
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
    @else
    <p>No existen datos relacionados con el personal.</p>
    @endif
<h3>VACACIONES ADEUDADAS</h3>
    @if(count($informe['d_adeuda']) > 0)
        <table>
            <tr>
                <th>PERIODO</th>
                <th>DIAS ADEUDADOS</th>
            </tr>
            @foreach($informe['d_adeuda'] as $item)
            <tr class="centered-row">
                <td>{{ isset($item->periodo) ? $item->periodo : '' }}</td>
                <td>{{ isset($item->dias_a) ? $item->dias_a : '' }}</td>
            </tr>
            @endforeach
        </table>
    @else
    <p>No existen datos relacionados con el personal.</p>
    @endif
@endif

@if(in_array('12', $camposSeleccionados))
<h3>LICENCIAS SIN GOCE DE HABER</h3>
    @if(count($informe['dli']) > 0)
        <table>
            <tr>
                <th>DOCUMENTO</th>
                <th>DESCRIPCIÓN</th>
                <th>DIAS</th>
                <th>DESDE</th>
                <th>HASTA</th>
            </tr>
            @foreach($informe['dli'] as $item)
                    <tr class="centered-row">
                        <td>{{ $tdoc[$item->idtd] ?? '' }} {{ $item->nrodoc ?? '' }}</td>
                        <td>{{ $tdoc[$item->id_descripcion] ?? '' }} </td>
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
    @else
    <p>No existen datos relacionados con el personal.</p>
    @endif
@endif

@if(in_array('13', $camposSeleccionados))
<h3>PERMISOS</h3>
    @if(count($informe['dper']) > 0)
        <table>
            <tr>
                <th>DOCUMENTO</th>
                <th>DIAS</th>
                <th>DESDE</th>
                <th>HASTA</th>
            </tr>
            @foreach($informe['dper'] as $item)
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
    @else
    <p>No existen datos relacionados con el personal.</p>
    @endif
@endif

@if(in_array('10', $camposSeleccionados))
    <h3>COMPENSACIONES</h3>
    @if(count($informe['dcom']) > 0)
        <table>
            <tr>
                <th>TIPO COMPENSACION</th>
                <th>DOCUMENTO</th>
                <th>FECHA DOCUMENTO</th>
            </tr>
            @foreach($informe['dcom'] as $item)
            <tr class="centered-row">
                <td>{{ $tcomp[$item->tipo_compensacion] ?? '' }} </td>
                <td>{{ $tdoc[$item->idtd] ?? '' }} {{ $item->nrodoc ?? '' }}</td>
                <td>{{ isset($item->fecha_documento) ? Carbon::parse($item->fecha_documento)->format('d/m/Y') : '' }}</td>
            </tr>
            @endforeach
        </table>
    @else
    <p>No existen datos relacionados con el personal.</p>
    @endif
@endif

@if(in_array('16', $camposSeleccionados))
<h3>ASIGNACIONES</h3>
    @if(count($informe['dat']) > 0)
        <table>
            <tr>
                <th>DESCRIPCIÓN</th>
                <th>DOCUMENTO</th>
                <th>FECHA INI</th>
                <th>FECHA FIN</th>
                <th>DIAS</th>
            </tr>
            @foreach($informe['dat'] as $item)
            <tr class="centered-row">
                <td>{{ $item->descripcion ?? '' }}</td>
                <td>
                    {{ $tdoc[$item->idtd] ?? '' }}
                    @if (!empty($item->nro_doc))
                        Nº {{ $item->nro_doc }}
                    @endif
                </td>
                <td>{{ isset($item->fecha_ini) ? Carbon::parse($item->fecha_ini)->format('d/m/Y') : '' }}</td>
                <td>{{ isset($item->fecha_fin) ? Carbon::parse($item->fecha_fin)->format('d/m/Y') : '' }}</td>
                <td>

                </td>
            </tr>
            @endforeach
        </table>
    @else
        <p>No existen datos relacionados con el personal.</p>
    @endif
@endif

@if(in_array('8', $camposSeleccionados))
<h3>RECONOCIMIENTOS</h3>
    @if(count($informe['drec']) > 0)
    <table>
        <tr>
            <th>DOCUMENTO</th>
            <th>DESCRIPCIÓN</th>
            <th>FECHA DOCUMENTO</th>
        </tr>
        @foreach($informe['drec'] as $item)
        <tr class="centered-row">
            <td>{{ $tdoc[$item->idtd] ?? '' }} {{ $item->nrodoc ?? '' }}</td>
            <td>{{ $item->descripcion ?? '' }} </td>
            <td>{{ isset($item->fechadoc) ? Carbon::parse($item->fechadoc)->format('d/m/Y') : '' }}</td>
        </tr>
        @endforeach
    </table>
    @else
    <p>No existen datos relacionados con el personal.</p>
    @endif
@endif

@if(in_array('9', $camposSeleccionados))
<h3>SANCIONES</h3>
    @if(count($informe['dsan']) > 0)
        <table>
            <tr>
                <th>DOCUMENTO</th>
                <th>DESCRIPCIÓN</th>
                <th>DIAS DE SANCION</th>
                <th>DESDE</th>
                <th>HASTA</th>
            </tr>
            @foreach($informe['dsan'] as $item)
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
    @else
    <p>No existen datos relacionados con el personal.</p>
    @endif
@endif

<center> <h3>DATOS CONSIGNADOS DE ACUERDO A FILE PERSONAL</h3></center>

@if(count($informe['archivoIds']) > 0)
<p>ARCHIVOS ADJUNTOS:</p>
<ul>
    @foreach($informe['archivoIds'] as $file)
        <li>{{ $file['name'] }}</li>
    @endforeach
</ul>
@else
<p>No existen archivos del o los campos, relacionados con el personal.</p>
@endif


<!-- Repite para otros campos seleccionados -->
@endforeach


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
        $y = $pdf->get_height() - 25;
        $pdf->page_text($x, $y, $text, $font, $size);
    }
</script>
</body>
</html>
