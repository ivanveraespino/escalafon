@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-munilc">Modalidad</h6>
                </div>
                <div class="card-body">
                <div class='row'>
                    <div class='col-12 d-flex justify-content-start'>
                        <button class='btn btn-primary' data-toggle="modal" onclick="btnmodal()"><i class='fas fa-plus-circle'></i>Ingresar Modalidad</button>
                    </div>
                </div>
                    <hr/>
                    <table class="table table-bordered table-striped" id="campo-table">
                        <thead>
                            <tr>
                                <th>NRO</th>
                                <th>Nombre</th>
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Ingresar Modalidad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
            <form id="guardarcampo" action="{{ route('guardarmo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div id="message3"></div>
                    <div class="form-group">
                        <label for="entidad">Nombre</label>
                        <input type="text" class="form-control" id="Enombre" name="nombre" placeholder="Ejm. A plazo fijo">
                    </div>
                    <button id="btncl" type="button" class="btn btn-success btn-block btn-lg" onclick="guardarts()">Guardar </button>
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
    $('#addModalLabel').html('Ingresar Modalidad');
    $('#btncl').attr('onclick', 'guardarts()');
    $('#per').show();
    $('#addModal').modal('show');
    var formtra = $('#guardarcampo')[0]; 
    formtra.reset();
};

var tablacampos = $('#campo-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: 'getCampomo',
    columns: [
        { data: null, name: 'nr', orderable: false, searchable: false },
        { data: 'nombre', name: 'nombre', orderable: false },
        { data: 'id', name: 'id', orderable: false, render: function(data, type) {
            return "<button onclick='sanciones_crud("+data+")' class='btn btn-danger btn-sm' title='Eliminar'><i class='fas fa-trash'></i></button><button class='btn btn-warning btn-sm edit-btn' onclick='actualizars("+data+")'><i class='fas fa-edit'></i></button>";
        }},
    ],
    order: [[3, 'desc']],
    rowCallback: function(row, data, index) {
        $('td:eq(0)', row).html(index + 1);
    },
});



function guardarts() {
        var formtra = $('#guardarcampo')[0]; 
    var formData = new FormData(formtra); 

        $.ajax({
            url: '{{ route('guardarmo') }}',
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

function sanciones_crud(id){
    if (confirm("¿Esta seguro de eliminar el registro?")) {
        $.ajax({
            url: "borrarmo/"+id,
            cache: false,
            success: function(data){
                tablacampos.ajax.url('getCampomo').load();
            }
        });
    } else {
        tablacampos.ajax.url('getCampomo').load();
    }
}
function actualizars(id) {
    $('#addModalLabel').html('Actualizar Modalidad');
    $('#per').hide();
    conid = id;
    $.ajax({
        url: 'mostrarmo/' + id, 
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
    console.log("formData");
    console.log(formData);
    $.ajax({
        url: 'editmo/' + conid, 
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
            tablacampos.ajax.url('getCampomo').load();
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