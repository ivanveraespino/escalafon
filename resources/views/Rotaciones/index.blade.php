@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-munilc">Movimientos</h6>
                </div>
                <div class="card-body">
                    <div class='row'>
                        <div class='col-12 d-flex justify-content-start'>
                            <button class='btn btn-primary' data-toggle="modal" onclick="btnmodal()"><i class='fas fa-plus-circle'></i>Ingresar Cargo</button>
                        </div>
                    </div>
                    <hr/>
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" data-toggle="tab" href="#rotacion-table">Movimientos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#table1">Nombramientos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#table2">Cese</a>
                        </li>
                    </ul>

                    <table class="table table-bordered table-striped" id="rotacion-table">
                        <thead>
                            <tr>
                                <th>Regimen</th>
                                <th>DNI</th>
                                <th>Apellidos y Nombres</th>
                                <th>Oficina</th>
                                <th>Cargo</th>
                                <th>Documento</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Nro Folio</th>
                                <th>Arch</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                    <table class="table table-bordered table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>DNI</th>
                                <th>Apellides y Nombres</th>
                                <th>Regimen</th>
                                <th>Descripción</th>
                                <th>Fecha de inicio</th>
                                <th>Documento</th>
                                <th>Fecha documento</th>
                                <th>Arch</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                    <table class="table table-bordered table-striped" id="table2">
                        <thead>
                            <tr>
                                <th>DNI</th>
                                <th>Apellides y Nombres</th>
                                <th>Regimen</th>
                                <th>Descripción</th>
                                <th>Fecha de inicio</th>
                                <th>Documento</th>
                                <th>Fecha documento</th>
                                <th>Arch</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                 
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal de Ingresar -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Ingresar Cargo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <form id="vinculo_crud" action="{{ route('vinculo_crud') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12" id="per">
                            <label for="dni">Personal</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="DNI" id="dni2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="buscarEmpleado2"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12" id ="dnied">
                            <div id="nombres_apellidos2"></br></div>
                            <input type="hidden" id="id_employee2" name="personal_id"/>    
                        </div>
                    </div>
                    <div id="message3"></div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-3 mb-3">
                                <label for="regimen">Tipo</label>
                                <select id="Eidtd" name="idtd" class="form-control">
                                    <option value="" selected>Seleccione--</option>
                                    @foreach($tdoc as $t)
                                        <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-9 mb-3">
                                <label for="entidad">Nro Documento</label>
                                <input type="text" class="form-control" id="EResolucionContrato" name="ResolucionContrato" placeholder="Ejm. 232-2023">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="periodo">Fecha Inicio</label>
                            <input type="date" class="form-control" id="EFechaIngreso" name="FechaIngreso" >
                        </div>
                        <div class="col-6 mb-3">
                            <label for="periodo">Fecha Fin</label>
                            <input type="date" class="form-control" id="EFechaFin" name="FechaFin" >
                        </div>
                    </div>

                    <div class="form-group">
                            <label for="entidad">Regimen</label>
                            <select id="Eidregimen" name="idregimen" class="form-control">
                            <option value="" selected>Seleccione--</option>
                                @foreach($reg as $r)
                                    <option value="{{ $r->id }}">{{ $r->nombre }}</option>
                                @endforeach
                            </select>                    
                    </div>

                    <div class="form-group">
                        <label for="entidad">Cargo</label>
                        <input type="text" class="form-control" id="ECargoActual" name="CargoActual" >
                    </div>

                    <div class="form-group">
                        <label for="entidad">Oficina</label>
                        <input type="text" class="form-control" id="EOficina" name="Oficina" >
                    </div>
                    <div class="row">
                        <div class="col-3 mb-3">                        
                            <label for="entidad">Nro Folio</label>
                            <input type="number" class="form-control" id="Enrofolio" name="nrofolio" >
                        </div>
                        <div class="col-9 mb-3">                        
                            <label for="archivo">Archivo</label>
                            <input type="file" class="form-control" id="archivo" name="archivo" >
                        </div>
                    </div>
                    <button id="btncl" type="button" class="btn btn-success btn-block btn-lg" onclick="guardartcl()">Guardar </button>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="newJustificacionModal" tabindex="-1" role="dialog" aria-labelledby="newJustificacionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newJustificacionModalLabel">Registrar Cargo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      @include('justificaciones.form_newjustificacion')
      <div id="message"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-success" id="guardar_justificacion">Justificar</button>
      </div>
    </div>
  </div>
</div>


@stop

@push('scripts')

<script>
var tablag;
$(document).ready(function() {
    $('.table').hide();
    $('#rotacion-table').show();
    var config = getTableConfig('#rotacion-table');
    tablag = initializeDataTable('#rotacion-table', config.ajaxUrl, config.columns);
    $('.nav-link').on('click', function() {
        var targetTab = $(this).attr('href');
        
        if (!$.fn.DataTable.isDataTable(targetTab)) {
            var config = getTableConfig(targetTab);
            tablag = initializeDataTable(targetTab, config.ajaxUrl, config.columns);        
        }

        $('.table').hide();
        $(targetTab).show();
    });
});


function initializeDataTable(tableId, ajaxUrl, columnsConfig) {
    return $(tableId).DataTable(
    {
    searching: false,
    paging: false,
    lengthChange: false,
    info: false,
    language: {
            search: "Buscar",
            zeroRecords: "No se encontraron resultados", 
            info: "Mostrando _START_ a _END_ de _TOTAL_ entradas", 
            lengthMenu: "Mostrar _MENU_ entradas por página",
            emptyTable: "No hay datos disponibles en la tabla",
            infoEmpty:  "Mostrando 0 de 0 of 0 entradas",
            loadingRecords: "Cargando...",
        },
        ajax: ajaxUrl,
        columns: columnsConfig,
        order: [[5, 'desc']],        

    });
};


function getTableConfig(tableId) {
    switch(tableId) {
        case '#rotacion-table':
            return {
                ajaxUrl: 'getRotacion',
                columns: [
                    { data: 'idregimen', name: 'idregimen', orderable: false},
                    { data: 'personal_id', name: 'personal_id', orderable: false},
                    { data: 'personal', name: 'personal', orderable: false},
                    { data: 'Oficina', name: 'Oficina', orderable: false},
                    { data: 'CargoActual', name: 'CargoActual', orderable: false},
                    { data: 'ResolucionContrato', name: 'ResolucionContrato' },
                    {
                        data: 'FechaIngreso',
                        name: 'FechaIngreso',
                        render: function (data, type, row) {
                            if (data) { 
                                const fecha = new Date(data);
                                const opciones = { year: 'numeric', month: '2-digit', day: '2-digit' };
                                return fecha.toLocaleDateString('es-ES', opciones);
                            } else {
                                return ''; 
                            }
                        }
                    },
                    {
                        data: 'FechaFin',
                        name: 'FechaFin',
                        render: function (data, type, row) {
                            if (data) { 
                                const fecha = new Date(data);
                                const opciones = { year: 'numeric', month: '2-digit', day: '2-digit' };
                                return fecha.toLocaleDateString('es-ES', opciones);
                            } else {
                                return ''; 
                            }
                        }
                    },
                    { data: 'nrofolio', name: 'nrofolio', orderable: false},
                    {
                        data: 'archivo',
                        name: 'archivo',
                        render: function(data, type, row) {
                            if (data) {
                                return '<button type="button" class="btn btn-primary btn-sm ver-pdf" data-pdf="' + data + '">Ver PDF</button>';
                            } else {
                                return 'No hay archivo';
                            }
                        }
                    }, 
                    { data: 'idcl', name: 'idcl', orderable: false, searchable: false, render: function(data, type){
                        return "<button onclick='vinculo_crud("+data+")' class='btn btn-danger btn-sm' title='Eliminar'><i class='fas fa-trash'></i></button><button class='btn btn-warning btn-sm edit-btn' onclick='actualizarcl("+data+")'><i class='fas fa-edit'></i></button>";
                    } },
                ]
            };
        default:
            return {
                ajaxUrl: '',
                columns: []
            };
    }
}


    function btnmodal() {
        $('#per').show();
        $('#addModal').modal('show');
    };

    $('#buscarEmpleado2').click(function(){
    var dni = $('#dni2').val();
    if(dni.length == 8){
        $.ajax({
            url: "buscarpersonal/"+dni,
            cache: false,
            success: function(data){
                data = $.parseJSON(data);
                if(data != null){
                    $('#id_employee2').val(data.id_personal);
                    $('#nombres_apellidos2').html("<div class='alert alert-success' role='alert'>"+data.Nombres+" "+data.Apaterno+" "+data.Amaterno+"</div>");
                    $('#message3').html('');
                }else{
                    $('#message3').html('<div class="alert alert-danger" role="alert">No se encuentra a personal.</div>')
                    setTimeout(function() {
                        $('.alert').alert('close');
                    }, 1000);
                }
            }
        });
    }
});






function guardartcl() {
        var formtra = $('#vinculo_crud')[0]; 
    var formData = new FormData(formtra); 

        $.ajax({
            url: '{{ route('vinculo_crud') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
            success: function(data) {
                $('#addModal').modal('hide');
                location.reload();

              
            $('#dataTable tbody').append(newrow2);
                Swal.fire({
                    icon: 'success',
                    title: 'Campo agregado!',
                });
            }
        });
    };

function vinculo_crud(id){
    if (confirm("¿Esta seguro de eliminar el registro?")) {
        $.ajax({
            url: "vinculo_crud/"+id,
            cache: false,
            success: function(data){
                tablag.ajax.url('getRotacion').load();
            }
        });
    } else {
        tablag.ajax.url('getRotacion').load();
    }
}
function actualizarcl(id) {
    $('#per').hide();

        conid = id;
    $.ajax({
        url: 'vinculo_crud/' + id, 
        type: 'GET',
        success: function(data) {
            $.each(data, function(key, value) {
                $('#E' + key).val(value);
                $('#addModal').modal('show');

            });
            $('#btncl').attr('onclick', 'guardarActualizacion()');

        },
        
        error: function(xhr) {
            console.error(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al cargar los datos del campo.'
            });
        }
    });
}

    function guardarActualizacion() {
    var form = $('#vinculo_crud')[0];
    var formData = new FormData(form);
    console.log("formData");
    console.log(formData);
    $.ajax({
        url: 'vinculo_crud/' + conid, 
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            Swal.fire({
                icon: 'success',
                title: 'Campo actualizado!',
                text: 'El campo se ha actualizado correctamente.'
            });
            $('#row2-' + data.id).find('td:eq(0)').text(data.entidad);
            $('#row2-' + data.id).find('td:eq(1)').text(data.periodo);
            $('#row2-' + data.id).find('td:eq(2)').html('<a href="/storage/' + data.archivo + '" target="_blank" class="btn btn-info btn-sm">Ver Archivo</a>');
            $('#btncl').attr('onclick', 'guardartcl()');
            $('#addModal').modal('hide');
            location.reload();
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al actualizar el campo.'
            });
        }
    });
}
</script>


@endpush