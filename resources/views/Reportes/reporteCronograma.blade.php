<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte</title>
    <style>
        .encabezado {
            width: 100%;
            margin-bottom: 20px;
        }
        .encabezado td {
            vertical-align: middle;
        }
        .logo {
            width: 80px; /* ajusta tamaño del logo */
        }
        .titulo {
            font-size: 18px;
            font-weight: bold;
            text-align: left;
            padding-left: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Encabezado con logo y texto -->
    <table class="encabezado">
        <tr>
            <td class="logo">
                <img src="{{ public_path('img/logo_informe.png') }}" alt="Logo" style="width:100%;">
            </td>
            <td class="titulo" style="">
                <strong style="color: green; font-size: 20px; text-align: center; display: block;"> MUNICIPALIDAD PROVINCIAL DE LA CONVENCIÓN </strong>
                <hr>
                <strong style="color: green; font-size: 20px; text-align: center; display: block;">                UNIDAD DE ESCALAFÓN</strong>

            </td>
        </tr>
    </table>

    <h3 style="text-align: center;">REPORTE DE CRONOGRAMA SEGÚN {{ $nombredoc }} {{ $nrodoc }}</h1>

    <table class="table table-bordered table-sm" style="width: 100%;">
        <tbody>
           @forelse ($encabezado as $reg)
                <tr>
                    <td style="width: 20%;">Periodo: <strong>{{ $reg->periodo }}</strong></td>
                    <td>Observaciones: {{ $reg->observaciones }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Sin encabezado</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <br>
    
    <table class="table table-bordered table-sm" style="width: 100%;">
        <thead>
            <tr>
                <th>Identificación</th>
                <th>Nombres</th>
                <th>Mes</th>
                <th>Días</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($registros as $reg)
                <tr>
                    <td>{{ $reg->id_identificacion }}: {{ $reg->nro_documento_id }}</td>
                    <td>{{ $reg->Nombres }} {{ $reg->Apaterno }} {{ $reg->Amaterno }}</td>
                    <td>{{ $reg->mes }}</td>
                    <td>{{ $reg->dias ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No hay registros disponibles</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
