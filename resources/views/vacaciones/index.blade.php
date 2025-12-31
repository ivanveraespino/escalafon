@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-munilc">Vacaciones</h6>
                </div>
                <div class="card-body">
                    <div class='row'>
                        <div class='col-12'>
                            <button class='btn btn-primary' data-toggle="modal" data-target="#newVacacionModal"><i class='fas fa-plus-circle'></i> Nuevo</button>
                        </div>
                    </div>
                    <hr/>
                    <table class="table table-bordered table-striped" id="vacaciones-table">
                        <thead>
                            <tr>
                                <th>DNI</th>
                                <th>Apellidos y Nombres</th>
                                <th>Motivo</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Final</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="newVacacionModal" tabindex="-1" role="dialog" aria-labelledby="newVacacionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newVacacionModalLabel">Registrar Periodo Vacacional</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      @include('vacaciones.form_newvacacion')
      <div id="message"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-success" id="guardar_vacacion">Guardar</button>
      </div>
    </div>
  </div>
</div>
@stop

@push('scripts')

<script>
var tablaVacaciones= $('#vacaciones-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: 'getVacaciones',
        columns: [
            { data: 'dni', name: 'dni', orderable: false, searchable: false},
            { data: 'empleado', name: 'empleado' },
            { data: 'motivo', name: 'motivo', orderable: false, searchable: false},
            { data: 'fecha_inicio', name: 'fecha_inicio', render: function(data,type){
                return data.substr(8,2)+"-"+data.substr(5,2)+"-"+data.substr(0,4);
            }},
            { data: 'fecha_final', name: 'fecha_final', render: function(data, type){
                return data.substr(8,2)+"-"+data.substr(5,2)+"-"+data.substr(0,4);
            }},
            { data: 'id', name: 'id', orderable: false, searchable: false, render: function(data, type){
                return "<button onclick='borrarVacacion("+data+")' class='btn btn-danger' title='Eliminar'><i class='fas fa-trash'></i></button>";
            } },

        ],
        order: [[3, 'desc']],
        
    });

function borrarVacacion(idVacacion){
    if (confirm("¿Esta seguro de eliminar el registro de vacacion?")) {
        $.ajax({
            url: "borrarVacaciones/"+idVacacion,
            cache: false,
            success: function(data){
                tablaVacaciones.ajax.url('getVacaciones').load();
            }
        });
    } else {
        tablaVacaciones.ajax.url('getVacaciones').load();
    }
}

$('#buscarEmpleado').click(function(){
    var dni = $('#dni').val();
    if(dni.length == 8){
        $.ajax({
            url: "buscarEmpleado/"+dni,
            cache: false,
            success: function(data){
                data = $.parseJSON(data);
                if(data != null){
                    $('#id_employee').val(data.id);
                    $('#nombres_apellidos').html("<div class='alert alert-success' role='alert'>"+data.name+" "+data.plastname+" "+data.mlastname+"</div>");
                    $('#message').html('');
                }else{
                    $('#message').html('<br/><div class="alert alert-danger" role="alert">No se encuentra a personal.</div>')
                }
            }
        });
    }
});

$('#guardar_vacacion').click( function() {
    if($('#id_employee').val() == ""){
        $('#message').html('<br/><div class="alert alert-danger" role="alert">No se ha identificado al personal.</div>')
    }else if($('#motivo').val() == ""){
        $('#message').html('<br/><div class="alert alert-warning" role="alert">Se require un motivo.</div>');
    }else if($('#fecha_inicio').val() == ""){
        $('#message').html('<br/><div class="alert alert-warning" role="alert">Se require una fecha de inicio.</div>')
    }else if($('#fecha_final').val() == ""){
        $('#message').html('<br/><div class="alert alert-warning" role="alert">Se require una fecha de fin.</div>')
    }else if(Date.parse($('#fecha_final').val()) <= Date.parse($('#fecha_inicio').val())){
        $('#message').html('<br/><div class="alert alert-warning" role="alert">La fecha de fin debe ser mayor a la fecha de inicio.</div>')
    }else{
        $('#message').html('');
        $.post( "{{route('vacaciones.store')}}", $('#formVacacion').serialize(), function(data) {
            tablaVacaciones.ajax.url('getVacaciones').load();
            $('#newVacacionModal').modal('hide');
            $(':input','#formVacacion')
            .not(':button, :submit, :reset')
            .val('');
            $('#nombres_apellidos').html('<br/>');
            $('#message').html('');
        },
        'json'
        );
    }
});
</script>


@endpush