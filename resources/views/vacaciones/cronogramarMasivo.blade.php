@extends('layouts.app')
@section('content')
<h1 class="text-munilc">Cronogramar Masivo</h1>
<p class="text-secondary"> Se encontraron {{ count($trabajadores)}} personas con vínculo laboral para el presente periodo {{ date('Y') }}.</p>

<div class="container bg-white">
    <div class="row p-2 ">
        <div class="col-2">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="periodo" name="periodo" value="{{ $periodo }}">
                <label for="periodo">Periodo Planificado</label>
            </div>
        </div>
        <div class="col-2">
            <div class="form-floating">
                <select class="form-select" id="mes-vacaciones" name="mes-vacaciones"
                    aria-label="Floating label select example" required>
                    <option disabled selected></option>
                    <option value="ENERO">ENERO</option>
                    <option value="FEBRERO">FEBRERO</option>
                    <option value="MARZO">MARZO</option>
                    <option value="ABRIL">ABRIL</option>
                    <option value="MAYO">MAYO</option>
                    <option value="JUNIO">JUNIO</option>
                    <option value="JULIO">JULIO</option>
                    <option value="AGOSTO">AGOSTO</option>
                    <option value="SETIEMBRE">SETIEMBRE</option>
                    <option value="OCTUBRE">OCTUBRE</option>
                    <option value="NOVIEMBRE">NOVIEMBRE</option>
                    <option value="DICIEMBRE">DICIEMBRE</option>
                </select>
                <label for="mes-vacaciones">Mes de vacaciones</label>
            </div>
        </div>
        <div class="col-2">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="tipo-doc-vac" name="tipo-doc-vac" list="datalistOptions"
                    required>
                <label for="tipo-doc-vac">Tipo Documento</label>

                <datalist id="datalistOptions">
                    @foreach($tiposdoc as $docs)
                        <option value="{{$docs->nombre}}">
                    @endforeach
                </datalist>
            </div>
        </div>
        <div class="col-2">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="nro-doc-vac" name="nro-doc-vac" required>
                <label for="nro-doc-vac">Nro Documento</label>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="observaciones-vac" name="observaciones-vac" required>
                <label for="observaciones-vac">observación</label>
            </div>
        </div>
        <div class="col">
            <input type="file" id="subidor-cron" accept=".pdf">
            <input type="hidden" name="doc-cron" id="doc-cron">
            <div id="mensaje-cron"></div>
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
                        <th class="p-1">Cargo</th>
                        <th class="p-1">Régimen</th>
                        <th class="p-1" style="display:none;">Historial</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($trabajadores as $per)
                        <tr data-bs-toggle="tooltip" data-bs-placement="top"
                            title="{{$per->id_identificacion}}: {{ $per->nro_documento_id }}">
                            <td class="pl-1 pr-0"><input type="checkbox" id="or-{{$per->vinculo}}" value="{{$per->vinculo}}"
                                    class="checkOrigen"></td>
                            <td class="p-1">
                                {{ \Illuminate\Support\Str::limit($per->Apaterno . ' ' . $per->Amaterno . ' ' . $per->Nombres, 28) }}
                            </td>
                            <td class="p-1">{{ \Illuminate\Support\Str::limit($per->cargo, 10) }}</td>
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
                        <th>Cargo</th>
                        <th>Régimen</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="col-6">
            <a href="{{ asset('archivos/programacion-vacaciones.csv') }}" download>
                Descargar plantilla CSV
            </a>
        </div>
        <div class="col-6">
            <form action="{{ route('cronograma.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="periodo-hidden" id="periodo-hidden">
                <input type="hidden" name="mes-vacaciones-hidden" id="mes-vacaciones-hidden">
                <input type="hidden" name="tipo-doc-vac-hidden" id="tipo-doc-vac-hidden">
                <input type="hidden" name="nro-doc-vac-hidden" id="nro-doc-vac-hidden">


                <label for="archivo">Subir CSV(Opcional):</label>
                <input type="file" name="archivo" id="archivo" accept=".csv" required>
                <button type="submit" class="btn btn-outline-secondary btn-sm">Procesar y Guardar</button>
            </form>
        </div>
        <div class="col text-center pt-3">
            <button id="guardarCronogramados" class="btn btn-success"> Generar Cronograma</button>
        </div>
    </div>



    <div class="card mt-5 mb-3">
        <div class="card-header ">
            <strong>Historial de Cronogramados del periodo {{ $periodo }}</strong>
        </div>
        <ul class="list-group list-group-flush">
            @foreach ($documentos as $doc)
                <li class="list-group-item d-flex justify-content-between ">
                    {{ $doc->nombredoc }} {{ $doc->nrodoc }}
                    <div class="tex-right">
                        <a class="badge text-bg-secondary rounded-pill" href="#">Editar Enacabezado</a>
                        <a class="badge text-bg-success rounded-pill" target="_blank"
                            href="{{ route('generarCronograma', ['nombredoc' => $doc->nombredoc, 'nrodoc' => $doc->nrodoc]) }}">
                            Ver reporte
                        </a>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

</div>

<script>
    document.getElementById("subidor-cron").addEventListener("change", function () {
        let archivo = this.files[0]; // Capturar el archivo seleccionado

        if (!archivo) {
            document.getElementById("mensaje-cron").innerHTML = "⚠ No se ha seleccionado ningún archivo.";
            return;
        }

        let formData = new FormData();
        formData.append("archivo", archivo);

        fetch("/subir-archivo", {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            }
        })
            .then(response => response.json())
            .then(data => {
                //document.getElementById("mensaje").innerHTML = data.mensaje;
                document.getElementById("mensaje-cron").innerHTML = data.mensaje + ' <a href="../repositories/' + data.name + '" target="_blank" class="text-success"><i class="fa fa-eye" ></i>Ver</a>';
                document.getElementById("doc-cron").value = data.name;
            })
            .catch(error => console.error("Error al subir el archivo:", error));
    });
    document.querySelector("form").addEventListener("submit", function () {
        document.getElementById("periodo-hidden").value = document.getElementById("periodo").value;
        document.getElementById("mes-vacaciones-hidden").value = document.getElementById("mes-vacaciones").value;
        document.getElementById("tipo-doc-vac-hidden").value = document.getElementById("tipo-doc-vac").value;
        document.getElementById("nro-doc-vac-hidden").value = document.getElementById("nro-doc-vac").value;
    });
</script>

@stop
@push('scripts')
    <script src="{{asset('js/cronograma.js')}}"></script>

@endpush