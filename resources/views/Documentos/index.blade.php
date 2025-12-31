@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-munilc">Tipo de Documentos</h6>
                </div>
                <div class="card-body">
                <div class='row'>
                    <div class='col-12 d-flex justify-content-start'>
                        <button class='btn btn-primary' data-toggle="modal" onclick="btnmodal()"><i class='fas fa-plus-circle mr-2'></i>Ingresar Documentos</button>
                    </div>
                </div>
                    <hr/>
                    <table class="table table-bordered table-striped" id="campo-table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Secciones</th>
                                <th>Accion</th>
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
    <div class="modal-dialog lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Ingresar Documentos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="guardarcampo" action="{{ route('guardar') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="Enombre">Nombre</label>
                            <input type="text" class="form-control" id="Enombre" name="nombre" placeholder="Ejm. RESOLUCIÓN DE GENRECIA MUNICIPAL">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="entidad">Categoría</label>
                        <div id="categoria-container" class="row p-2 border rounded">
                            @foreach($dcat as $regbd)
                                <div class="form-check col-md-4 mb-2">
                                    <input class="form-check-input categoria-checkbox" type="checkbox" id="categoria-{{ $regbd->clave }}" name="categoria[]" value="{{ $regbd->clave }}">
                                    <label class="form-check-label" for="categoria-{{ $regbd->clave }}">
                                        {{ $regbd->nombre }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-2 d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary btn-sm" id="select-all">Seleccionar Todo</button>
                            <button type="button" class="btn btn-secondary btn-sm" id="deselect-all">Deseleccionar Todo</button>
                        </div>
                    </div>

                    <button id="btncl" type="button" class="btn btn-success btn-block btn-lg" onclick="guardarts()">Guardar</button>
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
    document.getElementById('select-all').addEventListener('click', function() {
        var checkboxes = document.querySelectorAll('.categoria-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
    });

    document.getElementById('deselect-all').addEventListener('click', function() {
        var checkboxes = document.querySelectorAll('.categoria-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    });

    function guardarts() {
        var formtra = $('#guardarcampo')[0];
        var formData = new FormData(formtra);

        if ($('.categoria-checkbox:checked').length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Por favor, seleccione al menos una categoría.'
            });
            return; 
        }
        if ($('#Enombre').val() == "" ) {
            Swal.fire({
                icon: 'error',
                title: '¡Debe llenar el nombre!',
            })
        } else {
            $.ajax({
                url: '{{ route('guardar') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('#addModal').modal('hide');
                    tablacampos.ajax.url('datoscampo').load();
                    Swal.fire({
                        icon: 'success',
                        title: 'Campo agregado!',
                    });
                }
            });
      }
    }


function btnmodal() {
    $('#addModalLabel').html('Ingresar Campo');
    $('#btncl').attr('onclick', 'guardarts()');
    $('#per').show();
    $('#addModal').modal('show');
    var formtra = $('#guardarcampo')[0];
    var formData = new FormData(formtra);
    formtra.reset();

};

var tablacampos= $('#campo-table').DataTable({
        processing: true,
        serverSide: true,
        paging: false,         
        searching: true,     
        info: true,           
        lengthChange: true,   
        language: {
            search: "Buscar",
            zeroRecords: "No se encontraron resultados",
            info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
            lengthMenu: "Mostrar _MENU_ entradas por página",
            emptyTable: "No hay datos disponibles en la tabla", 
            infoEmpty: "Mostrando 0 de 0 of 0 entradas",
            loadingRecords: "Cargando...",
        },
        scrollX: true,
        autoWidth: false,        
        ajax: 'datoscampo',
        columns: [
            { data: 'id', name: 'id', orderable: false},
            { data: 'nombre', name: 'nombre', orderable: false},
            { data: 'categoria', name: 'categoria', orderable: false},
            { data: 'id', name: 'id', orderable: false, render: function(data, type){
                return "<button onclick='sanciones_crud("+data+")' class='btn btn-danger btn-sm' title='Eliminar'><i class='fas fa-trash'></i></button><button class='btn btn-warning btn-sm edit-btn' onclick='actualizars("+data+")'><i class='fas fa-edit'></i></button>";
            } },
        ],
        order: [[3, 'desc']],
    });



function sanciones_crud(id){
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡Esta accion afectara a otros registros, podria perder información!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, bórralo!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "borrar/"+id,
                success: function(response) {
                    if (response.success) {
                        tablacampos.ajax.url('datoscampo').load();
                        Swal.fire({
                            icon: 'success',
                            title: 'Campo eliminado!',
                            text: response.success
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.error
                        });
                    }
                }
            });
        }
    });
}
function actualizars(id) {
    $('#per').hide();
    $('#addModalLabel').html('Editar campo');
    conid = id;
    $.ajax({
        url: 'mostrar/' + id,
        type: 'GET',
        success: function(data) {
            // Limpiar las categorías previamente seleccionadas
            $('.categoria-checkbox').prop('checked', false);

            // Asignar valores al formulario
            $.each(data, function(key, value) {
                if (key === 'categoria') {
                    // Verificar si la categoría está vacía
                    if (value) {
                        // Convertir la cadena JSON a un array
                        var categorias = JSON.parse(value);
                        // Marcar los checkboxes de las categorías
                        categorias.forEach(function(categoriaId) {
                            $('#categoria-' + categoriaId).prop('checked', true);
                        });
                    }
                } else {
                    $('#E' + key).val(value);
                }
            });

            // Mostrar el modal
            $('#addModal').modal('show');

            // Actualizar el botón de guardado
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
    console.log("formData");
    console.log(formData);
    if ($('.categoria-checkbox:checked').length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Por favor, seleccione al menos una categoría.'
            });
            return; // No continuar si no hay categorías seleccionadas
        }
        if ($('#Enombre').val() == "" ) {
            Swal.fire({
                icon: 'error',
                title: '¡Debe llenar el nombre!',
            })
        } else {
            $.ajax({
                url: 'edit/' + conid, 
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
                    $('#addModalLabel').html('Ingresar campo');
                    $('#btncl').attr('onclick', 'guardarts()');
                    $('#addModal').modal('hide');
                    tablacampos.ajax.url('datoscampo').load();
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
