@extends('layouts.app')
@section('content')
<h1 class="text-munilc">Generar Documentos de Vacaciones Masivo</h1>
<p class="text-secondary"> Se encontraron {{ count($registros)}} colaboradores para el mes</p>

<div class="container bg-white">
    <div class="row p-2 ">
        <div class="col-2">
            <div class="form-floating mb-3">
                <input type="number" class="form-control" id="periodo-gen" name="periodo-gen" value="{{ $periodo }}" >
                <label for="periodo-gen">Periodo</label>
            </div>
        </div>
        <div class="col-2">
            <div class="form-floating">
                <select class="form-select" id="mes-vacaciones-gen" name="mes-vacaciones-gen" aria-label="Floating label select example" required>
                    <option disabled selected></option>
                    <option value="ENERO" {{ $mes=="ENERO"?'selected':'' }}>ENERO</option>
                    <option value="FEBRERO" {{ $mes=="FEBRERO"?'selected':'' }}>FEBRERO</option>
                    <option value="MARZO" {{ $mes=="MARZO"?'selected':'' }}>MARZO</option>
                    <option value="ABRIL" {{ $mes=="ABRIL"?'selected':'' }}>ABRIL</option>
                    <option value="MAYO" {{ $mes=="MAYO"?'selected':'' }}>MAYO</option>
                    <option value="JUNIO" {{ $mes=="JUNIO"?'selected':'' }}>JUNIO</option>
                    <option value="JULIO" {{ $mes=="JULIO"?'selected':'' }}>JULIO</option>
                    <option value="AGOSTO" {{ $mes=="AGOSTO"?'selected':'' }}>AGOSTO</option>
                    <option value="SETIEMBRE" {{ $mes=="SETIEMBRE"?'selected':'' }}>SETIEMBRE</option>
                    <option value="OCTUBRE" {{ $mes=="OCTUBRE"?'selected':'' }}>OCTUBRE</option>
                    <option value="NOVIEMBRE" {{ $mes=="NOVIEMBRE"?'selected':'' }}>NOVIEMBRE</option>
                    <option value="DICIEMBRE" {{ $mes=="DICIEMBRE"?'selected':'' }}>DICIEMBRE</option>
                </select>
                <label for="mes-vacaciones-gen">Mes de vacaciones</label>
            </div>
        </div>
        <div class="col-2">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="tipo-doc-vac-gen" name="tipo-doc-vac-gen" list="datalistOptions" required>
                <label for="tipo-doc-vac-gen">Tipo Documento</label>

                <datalist id="datalistOptions">
                    @foreach($tiposdoc as $docs)
                    <option value="{{$docs->nombre}}">
                        @endforeach
                </datalist>
            </div>
        </div>
        <div class="col-2">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="nro-doc-vac-gen" name="nro-doc-vac-gen" required>
                <label for="nro-doc-vac-gen">Nro Documento Incio</label>
            </div>
        </div> 
    </div>
    <div class="row">

        <div class="col-5  bg-white border shadow-sm">
            <!-- Tabla origen -->
            <table id="tablaOrigen" class="display">
                <thead>
                    <tr>
                        <th class="pl-1 pr-4"><input type="checkbox" id="checkAllOrigen"></th>
                        <th class="p-1">Nombre</th>
                        <th class="p-1">Régimen</th>
                        <th class="p-1" style="display:none;">Historial</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach ($registros as $per)
                    <tr data-bs-toggle="tooltip" data-bs-placement="top" title="{{$per->id_identificacion}}: {{ $per->nro_documento_id }}">
                        <td class="pl-1 pr-0"><input type="checkbox" id="or-{{$per->idvinculo}}" value="{{$per->idvinculo}}" class="checkOrigen"></td>
                        <td class="p-1">{{ \Illuminate\Support\Str::limit($per->Apaterno.' '.$per->Amaterno.' '.$per->Nombres, 28) }}</td>
                        <td class="p-1">{{$per->regimen}}</td>
                        <td class="p-1" style="display:none;">{{$per->mes ?? ''}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-1 d-flex align-items-center">
            <button id="devolver" class="btn btn-outline-info text-bold"> ← </button>
            <button id="pasar" class="btn btn-outline-warning"> → </button>
        </div>

        <div class="col-5 bg-white border shadow-sm">
            <table id="tablaDestino" class="display">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="checkAllDestino"></th>
                        <th>Nombre</th>
                        <th>Régimen</th>
                        <th class="p-1" style="display:none;">Historial</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="col text-center pt-3">
            <button id="generarDocumento" class="btn btn-success"> Descargar Generado</button>
        </div>
    </div>

</div>
@push('scripts')

<script>
    
$(document).ready(function() {
    // Inicializar tabla origen
    $('#tablaOrigen').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        language: {
            url: '/js/es-ES.json'
        }
    });

    // Inicializar tabla destino
    $('#tablaDestino').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        language: {
            url: '/js/es-ES.json'  // ✅ referencia local
        }
    });
});
</script>

<script>
    function recargarPagina() {
        let periodo = document.getElementById("periodo-gen").value;
        let mes = document.getElementById("mes-vacaciones-gen").value;

        if (periodo && mes) {
            // Redirige a la ruta con parámetros
            window.location.href = "/generar-vacaciones-masivo/" + periodo + "/" + mes;
        }
    }

    // Detectar cambios
    document.getElementById("periodo-gen").addEventListener("change", recargarPagina);
    document.getElementById("mes-vacaciones-gen").addEventListener("change", recargarPagina);
</script>
<script>
// Pasar seleccionados de tablaOrigen a tablaDestino
$('#pasar').on('click', function() {
    let tablaOrigen = $('#tablaOrigen').DataTable();
    let tablaDestino = $('#tablaDestino').DataTable();

    $('#tablaOrigen tbody input.checkOrigen:checked').each(function() {
        let fila = $(this).closest('tr');
        let datos = tablaOrigen.row(fila).data();

        // Convertir el checkbox a checkDestino
        datos[0] = datos[0].replace('checkOrigen', 'checkDestino');

        tablaDestino.row.add(datos).draw();       // agregar a destino
        tablaOrigen.row(fila).remove().draw();    // eliminar de origen
    });
});

// Devolver seleccionados de tablaDestino a tablaOrigen
$('#devolver').on('click', function() {
    let tablaOrigen = $('#tablaOrigen').DataTable();
    let tablaDestino = $('#tablaDestino').DataTable();

    $('#tablaDestino tbody input.checkDestino:checked').each(function() {
        let fila = $(this).closest('tr');
        let datos = tablaDestino.row(fila).data();

        // Convertir el checkbox a checkOrigen
        datos[0] = datos[0].replace('checkDestino', 'checkOrigen');

        tablaOrigen.row.add(datos).draw();
        tablaDestino.row(fila).remove().draw();
    });
});

// CheckAll Origen
$('#checkAllOrigen').on('change', function() {
    let checked = $(this).is(':checked');
    $('#tablaOrigen tbody .checkOrigen').prop('checked', checked);
});

// CheckAll Destino
$('#checkAllDestino').on('change', function() {
    let checked = $(this).is(':checked');
    $('#tablaDestino tbody .checkDestino').prop('checked', checked);
});


</script>

@endpush

@stop
