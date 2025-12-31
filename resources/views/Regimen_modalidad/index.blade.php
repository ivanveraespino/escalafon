@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-munilc">Condicion laboral</h6>
                    <button id="btnDescargarArchivos"  onclick="descargarArchivos(4)">Descargar Archivos</button>                    
                </div>
                <div class="card-body">
                <div class='row'>
                    <div class='col-12 d-flex justify-content-start'>
                        <button class='btn btn-primary' data-toggle="modal" onclick="btnmodal()"><i class='fas fa-plus-circle'></i>Agregar Condicion laboral</button>

                        
                    </div>
                </div>
                    <hr/>
                    <table class="table table-bordered table-striped" id="campo-table">
                        <thead>
                            <tr>
                                <th>NRO</th>
                                <th>Nombre</th>
                                <th>Descripcion</th>
                                <th>Accion</th>
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
                <h5 class="modal-title" id="addModalLabel">Agregar Condicion laboral</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
            <form id="guardarcampo" action="{{ route('guardarrm') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div id="message3"></div>
                    <div class="form-group">
                        <label for="entidad">Nombre</label>
                        <input type="text" class="form-control" id="Enombre" name="nombre" placeholder="Ejm. Nombrado">
                    </div>
                    <div class="form-group">
                        <label for="entidad">Descripcion</label>
                        <input type="text" class="form-control" id="Edescripcion_regimen" name="descripcion_regimen" placeholder="Ejm. EMPLEADO A PLAZO">
                    </div>
             
                    <button id="btncl" type="button" class="btn btn-success btn-block btn-lg" onclick="guardarts()">Guardar </button>
                </form>
            </div>
        </div>
    </div>
</div>

@stop

@push('scripts')

<script>

function descargarArchivos(dni) {
    $.ajax({
        url: "/descargarAllArchivos/" + dni,
        method: 'GET',
        xhrFields: {
            responseType: 'blob'
        },
        success: function(data, status, xhr) {
            var blob = new Blob([data], { type: 'application/pdf' });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'reporte_final.pdf';
            link.click();

            Swal.fire({
                icon: 'success',
                title: 'Archivos descargados!',
                text: 'Los archivos se han descargado correctamente'
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al descargar los archivos: ' + textStatus
            });
        }
    });
}

$(document).ready(function() {
    convertirAMayusculas();
 });
 function btnmodal() {
    $('#addModalLabel').html('Agregar Condicion laboral');
    $('#btncl').attr('onclick', 'guardarts()');
    $('#per').show();
    $('#addModal').modal('show');
    var formtra = $('#guardarcampo')[0]; 
    formtra.reset();
};

var tablacampos= $('#campo-table').DataTable({
        language: {
            search: "Buscar : ",
            zeroRecords: "No se encontraron resultados",
            info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
            lengthMenu: "Mostrar _MENU_ entradas por página",
            emptyTable: "No hay datos disponibles en la tabla", 
            infoEmpty: "Mostrando 0 de 0 of 0 entradas",
            loadingRecords: "Cargando...",
        },
        processing: true,
        serverSide: true,
        ajax: 'getCamporm',
        columns: [
            { data: null, name: 'nr', orderable: false, searchable: false },
            { data: 'nombre', name: 'nombre', orderable: false},
            { data: 'descripcion_regimen', name: 'descripcion_regimen', orderable: false},
 
            { data: 'id', name: 'id', orderable: false, render: function(data, type){
                    return "<button onclick='borrar("+data+")' class='btn btn-danger btn-sm' title='Eliminar'><i class='fas fa-trash'></i></button><button class='btn btn-warning btn-sm edit-btn' onclick='actualizars("+data+")'><i class='fas fa-edit'></i></button>";
                    return "@hasanyrole('ADMIN|COORDINADOR')<button onclick='borrar("+data+")' class='btn btn-danger btn-sm' title='Eliminar'><i class='fas fa-trash'></i></button><button class='btn btn-warning btn-sm edit-btn' onclick='actualizars("+data+")'><i class='fas fa-edit'></i></button>@endhasanyrole";
                }
            },
        ],
        order: [[3, 'desc']],
        rowCallback: function(row, data, index) {
        $('td:eq(0)', row).html(index + 1);
    },
    });



function guardarts() {
    var formtra = $('#guardarcampo')[0]; 
    var formData = new FormData(formtra); 
    if ($('#Enombre').val() == "" ) {
        Swal.fire({
            icon: 'error',
            title: '¡Debe llenar el nombre!',
        })
    } else {
        $.ajax({
            url: '{{ route('guardarrm') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                $('#addModal').modal('hide');
                tablacampos.ajax.url('getCamporm').load();
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
        text: "¡Esta acción afectará a otros registros y podría perder información!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, bórralo!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "borrarrm/" + id,
                success: function (response) {
                    if (response.success) {
                        tablacampos.ajax.url('getCamporm').load();
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
                error: function (jqXHR, textStatus, errorThrown) {
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
                        text: errorMessage
                    });
                }
            });
        }
    });
}



function actualizars(id) {
    $('#per').hide();
    $('#addModalLabel').html('Editar Condicion laboral');

    conid = id;
    $.ajax({
        url: 'mostrarrm/' + id, 
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
                text: 'Hubo un problema al cargar los datos del trabajo.'
            });
        }
    });
}

function guardarActualizacion() {
    var form = $('#guardarcampo')[0];
    var formData = new FormData(form);
    if ($('#Enombre').val() == "" ) {
            Swal.fire({
                icon: 'error',
                title: '¡Debe llenar el nombre!',
            })
        } else {
        $.ajax({
            url: 'editrm/' + conid, 
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
            
                $('#btncl').attr('onclick', 'guardarts()');
                $('#addModal').modal('hide');
                tablacampos.ajax.url('getCamporm').load();
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