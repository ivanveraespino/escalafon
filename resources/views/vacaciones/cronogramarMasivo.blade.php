@extends('layouts.app')
@section('content')
<h1 class="text-munilc">Cronogramar Masivo</h1>
<p class="text-secondary"> Se encontraron {{ count($trabajadores)}}</p>

<div class="container bg-white">
    <div class="row p-2 ">
        <div class="col-2">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="periodo" name="periodo" value="{{now()->year+1}}" readonly>
                <label for="periodo">Periodo</label>
            </div>
        </div>
        <div class="col-2">
            <div class="form-floating">
                <select class="form-select" id="mes-vacaciones" name="mes-vacaciones" aria-label="Floating label select example" required>
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
                <input type="text" class="form-control" id="tipo-doc-vac" name="tipo-doc-vac" required>
                <label for="tipo-doc-vac">Tipo Documento</label>
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
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trabajadores as $per)
                    <tr data-bs-toggle="tooltip" data-bs-placement="top" title="{{$per->id_identificacion}}: {{ $per->nro_documento_id }}">
                        <td class="pl-1 pr-0"><input type="checkbox" id="or-{{$per->vinculo}}" value="{{$per->vinculo}}" class="checkOrigen"></td>
                        <td class="p-1">{{ \Illuminate\Support\Str::limit($per->Apaterno.' '.$per->Amaterno.' '.$per->Nombres, 28) }}</td>
                        <td class="p-1">{{ \Illuminate\Support\Str::limit($per->cargo, 10) }}</td>
                        <td class="p-1">{{$per->regimen}}</td>
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
        <div class="col text-center pt-3">
            <button id="guardarCronogramados" class="btn btn-success"> Generar Cronograma</button>
        </div>
    </div>

</div>


@stop
@push('scripts')
<script src="{{asset('js/cronograma.js')}}"></script>
@endpush