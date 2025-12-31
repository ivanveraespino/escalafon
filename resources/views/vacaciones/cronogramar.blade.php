@extends('layouts.app')
@section('content')
<h1 class="text-munilc">Cronogramar vacaciones de manera individual.</h1>
<div class="container p-0">

    <div class="row">

        <div class="col-sm-6">
            <form id="individual" action="{{ route('guardarEdicion', ['id' => 'aquivacodigo']) }}" method="POST"
                class="flex-1 d-flex flex-column gap-4" enctype="multipart/form-data">

                <input type="hidden" name="idcronograma" id="idcronograma">
                <div class="container p-0">

                    <div class="form-group mb-3 bg-white p-2">
                        <label for="mes-vacaciones">Seleccione Personal</label>
                        <select class="form-select select2" id="individuo" name="individuo">
                            <option disabled selected></option>
                            @foreach($personal as $per)
                            <option value="{{$per->id_personal}}">{{$per->Nombres}} {{$per->Apaterno}} {{$per->Amaterno}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row bg-white shadow pt-2">
                        <div class="col-sm-4 col-6">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="periodo" name="periodo" min="1995" value="{{now()->year+1}}">
                                <label for="periodo">Periodo</label>
                            </div>
                        </div>
                        <div class="col-sm-4 col-6">
                            <div class="form-floating">
                                <select class="form-select" id="mes-vacaciones" name="mes-vacaciones" aria-label="Floating label select example">
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
                        <div class="col-6 col-sm-3">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="tipo-doc-vac" name="tipo-doc-vac">
                                <label for="tipo-doc-vac">Tipo Documento</label>
                            </div>
                        </div>
                        <div class=" col-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="nro-doc-vac" name="nro-doc-vac">
                                <label for="nro-doc-vac">Nro Documento</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="observaciones-vac" name="observaciones-vac">
                                <label for="observaciones-vac">observación</label>
                            </div>
                        </div>
                        <div class="col">
                            <input type="file" id="subidor-cron" accept=".pdf">
                            <input type="hidden" name="doc-cron" id="doc-cron">
                            <div id="mensaje-cron"></div>
                        </div>
                        <div class="text-center p-4">
                            <a onclick="guardarRegistro()" class="btn btn-outline-success">GUARDAR EDICIÓN</a>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        <div class="col-sm-6">
            <div id="informe">

            </div>
        </div>
    </div>

</div>

@stop
@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush