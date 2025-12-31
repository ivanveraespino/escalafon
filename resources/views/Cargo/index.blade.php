@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-munilc">Cargos</h6>
                </div>
                <div class="card-body">
                <div class='row'>
                    <div class='col-12 d-flex justify-content-start'>
                        <button class='btn btn-primary' data-toggle="modal" onclick="btnmodal()"><i class='fas fa-plus-circle mr-2'></i>Agregar Cargos</button>
                    </div>
                </div>
                    <hr/>
                    <table class="table table-bordered table-striped" id="campo-table">
                        <thead>
                            <tr>
                                <th>NRO</th>
                                <th>Nombre</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de agregar -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Agregar Cargos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
            <form id="guardarcampo" action="{{ route('guardarcarg') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div id="message3"></div>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="Enombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Estado</label>
                        <select name="estado" id="Eestado" class="form-control" required>
                            <option value="1">ACTIVO</option>
                            <option value="0">INACTIVO</option>
                        </select> 
                    </div>

                    <button id="btncarg" type="button" class="btn btn-success btn-block btn-lg" onclick="guardarts(event)">Guardar </button>
                </form>
            </div>
        </div>
    </div>
</div>

@stop

@push('scripts')
<script src="{{ asset('js/blob_handler.js') }}"></script>

<script>
$(document).ready(function() {
    convertirAMayusculas();
 });
    function btnmodal() {
    $('#addModalLabel').html('Agregar Cargos');
    $('#btncarg').attr('onclick', 'guardarts()');
    $('#per').show();
    $('#addModal').modal('show');
    var formtra = $('#guardarcampo')[0]; 
    formtra.reset();
};

var tablacampos = $('#campo-table').DataTable({
    processing: true,
    serverSide: true,
    autowith: true,   
    language: {
        search: "Buscar",
        zeroRecords: "No se encontraron resultados",
        info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
        lengthMenu: "Mostrar _MENU_ entradas por página",
        emptyTable: "No hay datos disponibles en la tabla", 
        infoEmpty: "Mostrando 0 de 0 of 0 entradas",
        loadingRecords: "Cargando...",
    },

    searching: true,     
    info: true,           
    ajax: 'getCampocarg',
    columns: [
        { data: null, name: 'nr', orderable: false, searchable: false },
        { data: 'nombre', name: 'nombre', orderable: false },
        { data: 'estado', name: 'estado', orderable: false },
        { data: 'id', name: 'id', orderable: false, render: function(data, type) {
            return "<button onclick='borrar("+data+")' class='btn btn-danger btn-sm' title='Eliminar'><i class='fas fa-trash'></i></button><button class='btn btn-warning btn-sm edit-btn' onclick='actualizars("+data+")'><i class='fas fa-edit'></i></button>";
        }},
    ],
    order: [[3, 'desc']],
    rowCallback: function(row, data, index) {
        $('td:eq(0)', row).html(index + 1);
    },
});



function guardarts(event) {
    
    var formtra = $('#guardarcampo')[0]; 
    var formData = new FormData(formtra); 
    if ($('#Enombre').val() == "" ) {
        Swal.fire({
            icon: 'error',
            title: '¡Debe llenar el nombre del Cargo!',
        })
    } else {
        $.ajax({
            url: '{{ route('guardarcarg') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                $('#addModal').modal('hide');
                tablacampos.ajax.url('getCampocarg').load();
                Swal.fire({
                    icon: 'success',
                    title: 'Campo agregado!',
                });
            }
        });
        
    }       
};


function borrar(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡Esta acción afectará a otros registros y podría perder información!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, bórralo!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "borrarcarg/" + id,
                success: function(response) {
                    if (response.success) {
                        tablacampos.ajax.url('getCampocarg').load();
                        Swal.fire({
                            icon: 'success',
                            title: 'Campo eliminado!',
                            text: response.success,
                            showConfirmButton: false, 
                            timer: 1000 
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.error,
                            showConfirmButton: false, 
                            timer: 1000 
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    let errorMessage = 'Ocurrió un error inesperado';
                    // Intenta obtener el mensaje de error específico del servidor
                    if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                        errorMessage = jqXHR.responseJSON.error;
                    } else {
                        errorMessage += ': ' + textStatus + ' (' + errorThrown + ')';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                    });
                }
            });
        }
    });
}

var conid;
function actualizars(id) {
    $('#addModalLabel').html('Actualizar Cargos');
    $('#per').hide();
    conid = id;
        $.ajax({
            url: 'mostrarcarg/' + id, 
            type: 'GET',
            success: function(data) {
                $.each(data, function(key, value) {
                    $('#E' + key).val(value);
                    $('#addModal').modal('show');
                });
                $('#btncarg').attr('onclick', 'guardarActualizacion()');
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al cargar los datos del trabajo.'
                });
            }
        });
    }


    function guardarActualizacion() {
    var form = $('#guardarcampo')[0];
    var formData = new FormData(form);
    console.log("formData");
    if ($('#Enombre').val() == "" ) {
        Swal.fire({
            icon: 'error',
            title: '¡Debe llenar el nombre del Area!',
        })
    } else {
        $.ajax({
            url: 'editcarg/' + conid, 
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
            
                $('#btncarg').attr('onclick', 'guardarts()');
                $('#addModal').modal('hide');
                tablacampos.ajax.url('getCampocarg').load();
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
}
</script>


@endpush
