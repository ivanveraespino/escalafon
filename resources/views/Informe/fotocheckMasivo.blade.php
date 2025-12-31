@extends('layouts.app')
@section('content')
<h1 class="text-munilc">Generar Fotocheck de forma masiva</h1>
<p class="text-secondary"> Se encontraron {{ count($personal)}}</p>
<div class="container">
    <div class="row">
        <div class="col-5  bg-white border shadow-sm">
            <!-- Tabla origen -->
            <table id="tablaOrigen" class="display">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="checkAllOrigen"></th>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Régimen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($personal as $per)
                    <tr>
                        <td><input type="checkbox" id="or-{{$per->id_personal}}" value="{{$per->id_personal}}" class="checkOrigen"></td>
                        <td>{{$per->Nombres}} {{$per->Apaterno}} {{$per->Amaterno}}</td>
                        <td>{{$per->cargo}}</td>
                        <td>{{$per->regimen}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-1 d-flex align-items-center">
            <!-- Botón para pasar seleccionados -->
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
            <button id="generarPDF" class="btn btn-success"> Generar </button>
        </div>
    </div>
</div>

<!-- Modal Bootstrap -->
<div class="modal fade" id="modalPDF" tabindex="-1" aria-labelledby="modalPDFLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Reporte PDF generado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="contenidoModal">
      </div>
    </div>
  </div>
</div>

@stop
@push('scripts')
<script src="{{asset('js/fotocheck.js')}}"></script>

@endpush