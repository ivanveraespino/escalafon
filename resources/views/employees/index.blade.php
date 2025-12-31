<style>
    .align-left {
        text-align: left;
    }

    .form-select-sm {
        height: 15px;
        /* Reduce el alto de los select */
        padding: 0.25rem 0.5rem;
        /* Ajusta el relleno interno */
        font-size: 0.85rem;
        /* Reduce el tamaño de la fuente */
    }

    .form-floating {
        margin-bottom: 0.5rem;
        /* Reduce los márgenes inferiores */
    }

    .form-floating label {
        font-size: 0.75rem;
        /* Reduce el tamaño de la etiqueta flotante */
        line-height: 1;
        /* Ajusta el espaciado de la etiqueta */
    }

    .container {
        padding: 0px;
    }

    
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <button class="btn btn-light">
                            <h6 class="m-0 font-weight-bold text-primary" id="titleg">Gestión del Personal</h6>
                        </button>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="form-floating">
                                <select id="tipo_personals" class="form-select form-select-sm">
                                    <option value="">TODOS</option>
                                    @foreach($tpersonal as $tp)
                                    <option value="{{ $tp->id ?? '' }}">{{ $tp->nombre }}</option>
                                    @endforeach
                                </select>
                                <label for="tipo_personal">Tipo Personal</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <select id="motivo_cese" class="form-select form-select-sm">
                                    <option value="">TODOS</option>
                                    @foreach($mcese as $mcese)
                                    <option value="{{ $mcese->id ?? '' }}">{{ $mcese->nombre }}</option>
                                    @endforeach
                                </select>
                                <label for="motivo_cese">Motivo Cese</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <select id="regimen" class="form-select form-select-sm">
                                    <option value="">TODOS</option>
                                    @foreach($reg as $rg)
                                    <option value="{{ $rg->id ?? '' }}">{{ $rg->nombre }}</option>
                                    @endforeach
                                </select>
                                <label for="regimen">Régimen</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <select id="condicion_laboral" class="form-select form-select-sm">
                                    <option value="">TODOS</option>
                                    @foreach($conlab as $cl)
                                    <option value="{{ $cl->id ?? '' }}">{{ $cl->nombre }}</option>
                                    @endforeach
                                </select>
                                <label for="condicion_laboral">Condición Laboral</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class='row'>
                        <div class='col-12'>
                            <a id="btnEdicion" href='{{ route('nuevoPersonal') }}' class="btn btn-success"><i class="fas fa-user-plus mr-1"></i>Agregar Personal</a>
                            <a id="btnAgregar" href='{{ route('datos_personal') }}' class="btn btn-success" style="display:none;"><i class="fas fa-plus-circle mr-1"></i>Nuevo Personal</a>
                            <a id="btnFicha" class="btn btn-danger disabled" target="_blank"><i class="fas fa-file-pdf"></i> Generar Ficha</a>
                            <a id="btnVer" class="btn btn-info mr-1" style="display: none;" disabled><i class="fas fa-eye" id="editIcon"></i>Ver Datos Personal</a>
                            <a id="btnEditar" class="btn btn-info mr-1" disabled><i class="fas fa-edit" id="editIcon"></i>Editar Personal</a>
                            <button id="btnUpload" class="btn btn-success" onclick=CargaMasivaPersonal()><i class='fas fa-upload'></i></button>
                            <button id="btnDescargarArchivos" class="btn btn-success" disabled>Descargar Archivos<i class='fas fa-download pl-1'></i></button>

                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped " id="employees-table">
                                <thead>
                                    <tr>
                                        <th>REGIMEN</th>
                                        <th>DNI</th>
                                        <th>APELLIDO PATERNO</th>
                                        <th>APELLIDO MATERNO</th>
                                        <th>NOMBRES</th>
                                        <th>FECHA NACIMIENTO</th>
                                        <th>CONDICION LABORAL</th>
                                        <th>FECHA INGRESO</th>
                                        <th>DEPENDENCIA</th>
                                        <th>CARGO</th>
                                        <th>TIPO DE PERSONAL</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal para subir el archivo -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Importar Personal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulario para subir archivo -->
                <form id="uploadForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="excelFile">Seleccione el archivo Excel</label>
                            <input type="file" class="form-control" id="excelFile" name="excelFile" accept=".xlsx, .xls">
                        </div>
                        <div class="col-6 mb-3 mt-3">
                            <button type="submit" class="btn btn-primary mt-3 w-100"><i class="fas fa-file-upload"></i> Subir Archivo</button>
                        </div>
                    </div>

                </form>
                <hr>
                <!-- Enlace para descargar el formato de Excel -->
                <a href="{{ asset('../storage/app/public/archivos/MODELO_BASE.xlsx') }}" class="btn btn-link">Descargar Formato de Excel</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEmpleado" tabindex="-1" role="dialog" aria-labelledby="modalEmpleadoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEmpleadoLabel">Detalles del empleado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="contenidoModal">
                <!-- Aquí se insertarán los datos -->
            </div>
        </div>
    </div>
</div>


<!-- Modal ver datos -->
<div class="modal fade" id="modalDatos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Datos del usuario</h5>
            </div>
            <div class="modal-body">
                <div id="qrCode"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-close btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cerrar</button>
            </div>
        </div>
    </div>
</div>




<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js/script>"></script>
<script>
    var tipo_personal;
    var regimen;
    var condicion;
    var motivo_cese;

    //PERSONAL SELECCIONADO
    var id_personal_seleccionado;




    //QR
    async function showQRModal(dni) {
        const response = await fetch("{{ route('mostrardpqr') }}" + "/" + dni);
        const data = await response.json();
        const qrcode = new QRCode(document.getElementById("qrCode"), {
            text: JSON.stringify(data),
            width: 128,
            height: 128
        });

        // Mostrar el modal
        const modal = new bootstrap.Modal(document.getElementById('modalDatos'));
        modal.show();
    }

    async function CargaMasivaPersonal() {
        $('#uploadModal').modal('show');
    }

    $(document).ready(function() {
        initializeChosen('#Eid_oficina', "{{ route('getAreas_list') }}");
        convertirAMayusculas();
        $('#Pdni').on('input', function() {
            var pdniValue = $(this).val();
        });

        $('#uploadModal').on('show.bs.modal', function() {
            $(this).find('form')[0].reset();
        });


    });

    //DATATABLE PERSONAL

    var tablaEmployees = $('#employees-table').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        scrollX: true,
        ajax: {
            url: 'getEmployees',
            data: function(d) {
                d.regimen = regimen;
                d.condicion = condicion;
                d.tipo_personal = tipo_personal;
                d.tipo_personal = tipo_personal;
                d.motivo_cese = motivo_cese;
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
        columns: [{
                data: 'regimenn',
                name: 'regimenn'
            },
            {
                data: 'nro_documento_id',
                name: 'nro_documento_id',
                orderable: false,
                searchable: true
            },
            {
                data: 'Apaterno',
                name: 'Apaterno'
            },
            {
                data: 'Amaterno',
                name: 'Amaterno'
            },
            {
                data: 'Nombres',
                name: 'Nombres'
            },
            {
                data: 'FechaNacimiento',
                name: 'FechaNacimiento',
                render: function(data, type, row) {
                    return formatDate(data);
                }
            },
            {
                data: 'condicion',
                name: 'condicion'
            },
            {
                data: 'inicio_vinculo',
                name: 'inicio_vinculo',
                render: function(data, type, row) {
                    return formatDate(data);
                }
            },
            {
                data: 'oficina',
                name: 'oficina'
            },
            {
                data: 'cargo',
                name: 'cargo'
            },
            {
                title: "TIPO DE PERSONAL",
                data: 'tipo_personal',
                name: 'tipo_personal',
                searchable: true
            },
            {
                title: "MOTIVO CESE",
                data: 'motivo_nombre',
                name: 'motivo_nombre',
                searchable: true
            },
            {
                title: "",
                data: 'id_personal',
                name: 'id_personal',
                orderable: false,
                render: function(data, type) {
                    //return "<button onclick='borrar_per(" + data + ")' class='btn btn-outline-info btn-sm mr-1' title='Eliminar'><i class='fas fa-eye'></i></button>";
                    return "<button onclick='generarFotocheck(" + data + ")' class='btn btn-outline-info btn-sm mr-1' title='Eliminar'><i class='fas fa-id-card'></i></button>";
                }
            }
        ],

        columnDefs: [{
            targets: 0,
            className: 'dt-left'
        }],

    });
    //ver modal
    $('#employees-table tbody').on('dblclick', 'tr', function() {
        const data = tablaEmployees.row(this).data(); // Obtener datos de la fila

        if (!data) return;
        else {
            $.ajax({
                url: "datos-personal",
                method: "GET",
                data: {
                    id: data.id_personal
                },
                success: function(response) {
                    //$('#user-container').html(response.html);
                    document.getElementById('contenidoModal').innerHTML = response.reporte
                    $('#modalEmpleado').modal('show');
                },
                error: function(xhr, status, error) {
                    alert("Error:", error);
                }
            })
        }
    });

    //VALIDAR EXISTENCIA DE PERSONAL
    $('#Pnro_documento_id').on('input', function() {
        var query = $(this).val();
        console.log(query);
        if (query.length > 4) {
            $.ajax({
                url: "ValidarPersonal",
                method: "GET",
                data: {
                    query: query
                },
                success: function(data) {
                    if (data.existe) {
                        // Muestra un mensaje con SweetAlert
                        Swal.fire({
                            title: 'Personal ya registrado',
                            text: `El personal ${data.nombreCompleto}, ya está registrado . ¿Deseas ver su información?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Sí, mostrar',
                            cancelButtonText: 'Cancelar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                actualizardp(data.personal_id, false);
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                }
            });
        }
    });


    //FILTRO

    $('#tipo_personals').on('change', function() {
        tipo_personal = $(this).val();
        tablaEmployees.ajax.reload();
    });
    $('#regimen').on('change', function() {
        regimen = $(this).val();
        tablaEmployees.ajax.reload();
    });
    $('#motivo_cese').on('change', function() {
        motivo_cese = $(this).val();
        tablaEmployees.ajax.reload();
    });

    $('#condicion_laboral').on('change', function() {
        condicion = $(this).val();
        tablaEmployees.ajax.reload();
    });

    $('#employees-table tbody').on('click', 'tr', function() {
        var data = tablaEmployees.row(this).data();
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $('#btnLegajo, #btnFicha').addClass('disabled');
            $('#btnActualizar').prop('disabled', true);
            $('#btnVer').prop('disabled', true);
            $('#btnEditar').prop('disabled', true);
            $('#btnDescargarArchivos').prop('disabled', true);
            $('#btnVer').prop('disabled', true);
            $('#btnArchivos').prop('disabled', true);
            $('#btnQR').prop('disabled', true);

        } else {
            tablaEmployees.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $('#btnLegajo').removeClass('disabled').attr('href', 'Legajo/' + data.id_personal);
            $('#btnFicha').removeClass('disabled').attr('href', 'ReporteFicha/' + data.id_personal);
            $('#btnActualizar').removeClass('disabled').attr('href', 'datos_personal/' + data.id_personal + '/' + 'editar');
            $('#btnVer').removeClass('disabled').attr('href', 'datos_personal/' + data.id_personal);
            $('#btnEditar').removeClass('disabled').attr('href', 'edicion-personal?id=' + data.id_personal);
            $('#btnDescargarArchivos').prop('disabled', false).attr('onclick', 'descargarArchivos(' + data.id_personal + ')');
            $('#btnInforme').prop('disabled', false).attr('onclick', 'informe(' + data.id_personal + ')');
            $('#btnArchivos').prop('disabled', false).attr('onclick', 'archivos(' + data.id_personal + ')');
            $('#btnQR').prop('disabled', false).attr('onclick', 'showQRModal(' + data.id_personal + ')');

        }
    });


    function guardarp() {
        if ($('#dni').val() == "" || $('#PApaterno').val() == "" || $('#PAmaterno').val() == "" || $('#Pid_regimen_modalidad').val() == "" || $('#Pid_regimen').val() == "") {
            Swal.fire({
                icon: 'error',
                title: '¡Debe llenar los campos requeridos!',
            });
        } else {
            var formulario = $('#Personal')[0];
            var formulario2 = $('#formdomicilio')[0];
            var formData = new FormData(formulario); // Añadir todos los campos de 'Personal' automáticamente

            var elementsForm2 = formulario2.elements; // Obtener los elementos del formulario 'formdomicilio'

            for (var i = 0; i < elementsForm2.length; i++) {
                var element = elementsForm2[i];
                if (element.name && element.value) {
                    // Si es 'afiliacion_salud', convierte a cadena antes de añadir
                    if (element.name === 'afiliacion_salud[]') {
                        var afiliacionSaludArray = $('input[name="afiliacion_salud[]"]:checked').map(function() {
                            return this.value;
                        }).get();
                        formData.append('afiliacion_salud', afiliacionSaludArray.join(','));
                    } else {
                        formData.append(element.name, element.value);
                    }
                }
            }
            console.log(formulario);
            $.ajax({
                url: "{{ route('guardarPersonal') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Personal Registrado!',
                        text: 'Los datos se han guardado correctamente.',
                        timer: 1000,
                    });
                    tablaEmployees.ajax.url('getEmployees').load();
                    //Habilitar moal campos
                    $('#ModalCampos .btn-block').data('id', response.id);
                    $('#ModalCampos .btn-block').data('idr', response.idr);
                    $('#ModalCampos .btn-block').prop('disabled', false);
                    $('#dnip_out').val(response.nombre_completo);
                    $('#modal_personal').modal('hide');
                    $('#ModalDomi').modal('hide');
                    actualizardp(response.id, false);
                    $(':input', '#formJustificacion')
                        .not(':button, :submit, :reset')
                        .val('');
                    $('#message').html('');
                },
                error: function(error) {
                    if (error.responseJSON && error.responseJSON.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: error.responseJSON.error
                        });
                    }
                }
            });
        }
    }

    function borrar_per($id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Todos los campos del personal seran eliminados",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, bórralo!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('borrarPersonal') }}" + "/" + $id,
                    success: function(response) {
                        tablaEmployees.ajax.url('getEmployees').load();
                        Swal.fire({
                            icon: 'success',
                            title: '¡Personal eliminado!',
                            text: response.success
                        });
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un problema al cargar los datos del personal.'
                        });
                    }
                });
            }
        });
    }

    function generarFotocheck($id) {

        if (!$id) return;
        else {
            $.ajax({
                url: "generar-fotocheck",
                method: "GET",
                data: {
                    id: $id
                },
                success: function(response) {
                    //$('#user-container').html(response.html);
                    document.getElementById('modalEmpleadoLabel').innerHTML = "<h5>Detalle del fotocheck</h5>"
                    document.getElementById('contenidoModal').innerHTML = response.reporte
                    $('#modalEmpleado').modal('show');
                },
                error: function(xhr, status, error) {
                    alert("Error:", error);
                }
            })
        }
    }

    //carga excel
    $('#uploadForm').on('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: '{{ route("importExcel") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                Swal.fire({
                    title: 'Subiendo...',
                    text: 'Por favor, espera',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                var message = response.message;
                var title = '¡Éxito!';
                var icon = 'success';

                if (response.warnings && response.warnings.length > 0) {
                    var warnings = response.warnings;
                    var warningRows = warnings.map(function(warning) {
                        return warning.fila;
                    });
                    message += '<br>Personales duplicados en filas: ' + warningRows.join(', ');
                    title = 'Advertencia';
                    icon = 'warning';
                }

                Swal.fire({
                    icon: icon,
                    title: title,
                    html: message,
                    customClass: {
                        title: response.warnings && response.warnings.length > 0 ? 'warning-title' : ''
                    }
                });
                $('#uploadModal').modal('hide');
                $('#uploadForm')[0].reset();
                tablaEmployees.ajax.url('getEmployees').load();
            },
            error: function(response) {
                var errorMessage = 'Hubo un problema al subir el archivo. Por favor, intenta nuevamente.<br>';
                if (response.responseJSON && response.responseJSON.errors) {
                    var errors = response.responseJSON.errors;
                    var errorFields = {};
                    errors.forEach(function(error) {
                        if (!errorFields[error.campo]) {
                            errorFields[error.campo] = [];
                        }
                        errorFields[error.campo].push(error.fila);
                    });
                    for (var campo in errorFields) {
                        errorMessage += 'Campo "' + campo + '" ingresado incorrectamente, en las filas: ' + errorFields[campo].join(', ') + '<br>';
                    }
                }
                if (response.responseJSON && response.responseJSON.warnings) {
                    var warnings = response.responseJSON.warnings;
                    var warningRows = warnings.map(function(warning) {
                        return warning.fila;
                    });
                    errorMessage += '<br>Advertencias de duplicados en filas: ' + warningRows.join(', ');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: errorMessage,
                });
            }
        });
    });

    // RECARGAR Y EVITAR MODAL AGREGAR
    function detectReload() {
        if (performance.navigation.type === 1) {
            var url = new URL(window.location.href);

            if (url.searchParams.has('agregar_modal')) {
                url.searchParams.delete('agregar_modal');
                window.location.href = url.toString();
            }
        }
    }
    document.onreadystatechange = function() {
        if (document.readyState === 'interactive') {
            detectReload();
        }
    };

    //DESCARGAR TODOS LOS ARCHIVOS
    function descargarArchivos(dni) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡Se descargarán todos los archivos asociados!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, descargarlos!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/descargarTodo/" + dni,
                    method: 'GET',
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data, status, xhr) {
                        var blob = new Blob([data], {
                            type: 'application/pdf'
                        });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);

                        var disposition = xhr.getResponseHeader('Content-Disposition');
                        var fileName = 'LEGAJO.pdf'; // Valor predeterminado
                        if (disposition && disposition.indexOf('filename=') !== -1) {
                            var matches = /filename="([^"]*)"/.exec(disposition);
                            if (matches != null && matches[1]) {
                                fileName = matches[1];
                            }
                        }

                        link.download = fileName;
                        link.click();

                        Swal.fire({
                            icon: 'success',
                            title: 'Archivos descargados!',
                            text: 'Los archivos se han descargado correctamente',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        let errorMessage = 'Ocurrió un error inesperado';

                        if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                            errorMessage = jqXHR.responseJSON.error;
                        } else {
                            errorMessage += ': ' + textStatus + ' (' + errorThrown + ')';
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                            showConfirmButton: false,
                            timer: 1000
                        });
                    }
                });
            }
        });
    }
</script>