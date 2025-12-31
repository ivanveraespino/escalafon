@extends('layouts.app')

@section('content')
<style>


.form-control-sm {
    width: auto;
    min-width: 150px; /* Ajusta según tus necesidades */
    margin-left: 10px; /* Espacio entre el texto y el select */
}

</style>


<div class="container ">
    <div class="card shadow ">
        <div class="card-header py-3">
            <div class="row justify-content-between">
                <div class="col-md-3">
                    <h6 class="m-0 font-weight-bold text-munilc">Archivos del Personal</h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form id="formot" enctype="multipart/form-data">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="dnip2">Buscar personal</label>
                            <div class="input-group">
                                <input type="hidden" class="form-control" id="personal_id" name="personal_id">
                                <input type="text" class="form-control" placeholder="Ingrese el nombre del personal" id="dnip2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
                                </div>
                                <div class="dropdown-menu" id="suggestions2"></div>

                            </div>
                        </div>
                        <div class="col-md-9 mb-3">
                            <div class="border p-3 m-0">
                                <label class="font-weight-bold">Subir Archivos</label>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <input type="text" class="form-control" placeholder="Ingrese Nombre archivo" id="nombre" name="nombrea">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <select id="categoryFilter2" name="clave" class="form-control">
                                            <option value="" selected disabled>Seleccionar Categoría</option>
                                            <!-- Aquí van las opciones dinámicas -->
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <input type="file" accept="application/*" class="form-control archivo" name="archivo" id="archivop">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <input type="number" class="form-control nro_folios" name="nro_folios">
                                    </div>
                                    <div class="col-md-2 mb-3 d-flex align-items-end">
                                        <button id="btncom" class="btn btn-success" type="button" onclick="guardartcom(event)">
                                            <i class="fas fa-upload"></i> SUBIR
                                        </button>
                                    </div>
                                    
                              
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
            <div id="table-container" class="table-responsive">
                <table id="dataTableot" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>
                                <div class="d-flex justify-content-between align-items-center">
                                    Categoría
                                    <select id="categoryFilter" class="form-control form-control-sm ml-2">
                                        <option value="">Todas</option>
                                        <!-- Las opciones de categorías se llenarán dinámicamente -->
                                    </select>
                                </div>
                            </th>                           
                            <th>Número de Folios</th>
                            <th>Fecha registrado</th>

                            
                        </tr>
                    </thead>

                    
                    <tbody>
                        <!-- DataTable content will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<script>
    const token = $('meta[name="csrf-token"]').attr('content');
    var table;
    var personal_id;
    $('#dnip2').on('input', function() {
        var query = $(this).val();
        if (query.length > 1) {
            $.ajax({
                url: "buscarUsuarios",
                data: { query: query },
                success: function(data) {
                    var suggestions = $('#suggestions2');
                    suggestions.empty();
                    $.each(data, function(index, item) {
                        suggestions.append('<a class="dropdown-item" data-personal_id="' + item.personal_id + '" data-nombre="' + item.nombre_completo + '">' + item.nombre_completo + '</a>');
                    });
                    suggestions.show();
                }
            });
        } else {
            $('#suggestions2').empty().hide();
        }
    });


    $(document).on('click', '.dropdown-item', function() {
        personal_id = $(this).data('personal_id');
        
        nombre = $(this).data('nombre');
        $('#personal_id').val(personal_id);
        $('#dnip2').val(nombre);

        $('#suggestions2').empty().hide();
        fetch(`{{ url('/MostrarArchivos') }}?id=${personal_id}`, {
            headers: {
                'X-CSRF-TOKEN': token
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                table=$('#dataTableot').DataTable({
                processing: true,
                serverSide: true,
                destroy: true, // Destroy existing table if it exists
                ajax: {
                    url: `{{ url('/MostrarArchivos') }}?id=${personal_id}`,
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                },
                language: {
                    search: "Buscar",
                    zeroRecords: "No se encontraron resultados",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    lengthMenu: "Mostrar _MENU_ entradas por página",
                    emptyTable: "Seleccione el tipo de personal a filtrar", 
                    infoEmpty: "Mostrando 0 de 0 of 0 entradas",
                    loadingRecords: "Cargando...",
                },
                columns: [
                    { data: 'nombre', name: 'nombre' },
                    { data: 'categorias', name: 'categorias' },

                    { data: 'nro_folio', name: 'nro_folio' },
                    { data: 'created_at', name: 'created_at' },
                    { 
                        data: 'archivo',
                        name: 'archivo',
                        render: function(data, type, row) {
                            if (data) {
                                return '<button type="button" class="btn btn-primary btn-sm ver-pdf" data-pdf="' + data + '"><i class="fas fa-file-pdf"></i></button>';
                            } else {
                                return 'No hay archivo';
                            }
                        }
                    },
                    { data: 'archivo', name: 'archivo', orderable: false, render: function(data, type) {
                        return "@hasanyrole('ADMIN')<button onclick='conpensaciones_crud(" + data + ")' class='btn btn-danger btn-sm' title='Eliminar'><i class='fas fa-trash'></i></button>@endhasanyrole";
                    }}
                ],

             
                columnDefs: [
                    { orderable: false, targets: 5 }
                ],
                paging: true,
                searching: true,
                info: true,
                lengthChange: true,
                scrollX: true,
                scrollY: '600px',
                autoWidth: false,
                order: [[3, 'desc']]
            });
            }
        })
        .catch(error => {
            console.error('Error al buscar archivos:', error);
        });
    });

    $(document).click(function(e) {
        if (!$(e.target).closest('#dnip2').length) {
            $('#suggestions2').empty().hide();
        }
    });


        fetch('MostrarCategorias', {
            headers: {
                'X-CSRF-TOKEN': token
            }
        })
        .then(response => response.json())
        .then(categorias => {
            const categoryFilter = document.getElementById('categoryFilter');
            categorias.sort((a, b) => parseInt(a.clave) - parseInt(b.clave));
            categorias.forEach(categoria => {
                const option = document.createElement('option');
                option.value = categoria.nombre;
                option.text = categoria.nombre;
                categoryFilter.appendChild(option);
            });

            // Filtrar por categoría
            categoryFilter.addEventListener('change', function() {
                const selectedCategory = this.value;
                if (selectedCategory) {
                    table.column(1).search(`^${selectedCategory}$`, true, false).draw();
                } else {
                    table.column(1).search('').draw();
                }
            });
        });

            fetch('MostrarCategorias', {
            headers: {
                'X-CSRF-TOKEN': token
            }
        })
        .then(response => response.json())
        .then(categorias => {
            const categoryFilter2 = document.getElementById('categoryFilter2');
            categorias.sort((a, b) => parseInt(a.clave) - parseInt(b.clave));
            categorias.forEach(categoria => {
                const option = document.createElement('option');
                option.value = categoria.clave;
                option.text = categoria.nombre;
                categoryFilter2.appendChild(option);
            });

        });
    
    
function guardartcom(event) {
    event.preventDefault();
    
    // Obtener el archivo
    var fileInput = document.getElementById('archivop');
    var file = fileInput.files[0];

    if ($('#personal_id').val() == "" ) {
        Swal.fire({
            icon: 'error',
            title: '¡Seleccione un Personal!',
        });
        return;
    } 
    if ($('#categoryFilter2').val() == "" ) {
        Swal.fire({
            icon: 'error',
            title: '¡Seleccione una categoria!',
        });
        return;
    } 
    if (!fileInput.value) {
        Swal.fire({
            icon: 'error',
            title: 'Por favor, selecciona un archivo antes de subir.',
        });
        return;
    }
    if (file.size > 100000000) { // 100MB = 100000000 bytes
        Swal.fire({
            icon: 'error',
            title: 'Archivo demasiado grande',
            text: 'El tamaño máximo permitido es de 100MB.'
        });
        return; // Detener la ejecución si el archivo es demasiado grande
    }
    
    var formtra = $('#formot')[0];
    var formData = new FormData(formtra);
    
    // Mostrar mensaje de carga con barra de progreso
    let swalLoading = Swal.fire({
        title: 'Cargando...',
        html: '<progress value="0" max="100" id="progressBar" style="width: 100%;"></progress><p id="progressText">0%</p>',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: '{{ route('guardar_archivos') }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        xhr: function() {
            var xhr = new window.XMLHttpRequest();
            
            // Evento de progreso para la carga del archivo
            xhr.upload.addEventListener('progress', function(evt) {
                if (evt.lengthComputable) {
                    var percentComplete = (evt.loaded / evt.total) * 100;
                    var progressBar = document.getElementById('progressBar');
                    var progressText = document.getElementById('progressText');
                    progressBar.value = percentComplete;
                    progressText.innerText = Math.round(percentComplete) + '%';
                }
            }, false);

            return xhr;
        },
        success: function(data) {
            Swal.close(); // Cerrar el Swal de carga
            $('#AddcomModal').modal('hide');
            table.ajax.url(`{{ url('/MostrarArchivos') }}?id=${personal_id}`).load();
            const elementsToClear = formtra.querySelectorAll('input, select');
            elementsToClear.forEach(element => {
                if (element.id !== 'personal_id' && element.id !== 'dnip2') {
                    element.value = '';
                }
            });
            Swal.fire({
                icon: 'success',
                title: 'Archivo cargado correctamente',
            });
        },
        error: function(xhr) {
            Swal.close(); // Cerrar el Swal de carga
            console.error(xhr.responseText);
            var response = JSON.parse(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al cargar los datos'
            });
        }
    });
}

    function conpensaciones_crud($id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Si el archivo esta asociado a un registro tendras que actualizarlo.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, bórralo!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('borrar_archivo') }}"+"/"+$id,
                    success: function(response) {
                        if (response.success) {
                            table.ajax.url(`{{ url('/MostrarArchivos') }}?id=${personal_id}`).load();
                            Swal.fire({
                                icon: 'success',
                                title: '¡Archivo eliminado!',
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


</script>
@endpush

@push('styles')
<link href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush
