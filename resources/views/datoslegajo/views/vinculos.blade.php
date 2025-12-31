
<style>

#estado {
    width: auto; /* Ajusta el tamaño del select */
    min-width: 120px; /* Tamaño mínimo para que no sea demasiado pequeño */
}
.align-left {
    text-align: left;
}

.form-floating input,
.form-floating select {
    max-width: 100%; /* Asegura que no se desborden dentro de su contenedor */
    width: 100%; /* Ajusta al tamaño del contenedor */
}
.col-md-1 {
    max-width: 80px; /* Ajusta según sea necesario para limitar el tamaño */
}

</style>

<div id="adendaModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="adendaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adendaModalLabel">Adendas</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="adendaDataTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Archivo</th>
                            <th>Nro Documento</th>
                            <th>Acciones</th> <!-- Nueva columna para acciones -->
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div id="adendaModalForm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="adendaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adendacrud">Agregar Adenda</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="adendaForm">
                    <div class="form-row gap-3">
                        <input type="hidden" name="adenda_id" id="adenda_id"> 
                        <input type="hidden" name="id_vinculo" id="id_vinculo"> <!-- Campo oculto -->
                        <div class="form-floating">
                            <input type="date" name="fecha_ini" id="fecha_ini" class="form-control" required>
                            <label for="fecha_ini" class="required">Fecha Inicio<span class="required">*</span></label>
                        </div>
                        <div class="form-floating">
                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
                            <label for="fecha_fin" class="required">Fecha Fin<span class="required">*</span></label>
                        </div>
                        <x-select-tipo-doc id="Eidtd" name="idtd" :tdoc="$tdoc" categoria="ADE" label="Tipo Documento"/>
                        <div class="form-floating">
                            <input type="text" name="nrodoc" id="nrodoc" class="form-control" required>
                            <label for="nrodoc" class="required">Nro Documento<span class="required">*</span></label>
                        </div>

                        <x-file-upload 
                            id="Edarchivo_aden" 
                            name="archivo"
                            label="Adenda"
                            nameFolio="nro_folios"
                        />
                    </div>
                    <button type="submit" class="btn btn-success ml-auto mr-0"  style="margin-right: 10px;">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ModalTerminar Vinculo -->
<div id="tvinculoModalForm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="tvinculoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tvinculo_title">Terminar Vinculo</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tvinculoForm">
                    <div class="form-row gap-3">
                        <input type="hidden" name="id" id="id_vinculo_f">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="fecha_fin_vin" name="fecha_fin" >
                            <label for="entidad" class="required">Fecha Fin<span class="required">*</span></label>
                        </div>

                        <div class="form-floating">
                            <select id="id_motivo_fin_vinculo" name="id_motivo_fin_vinculo" class="form-select">
                                <option value=""></option>
                                @foreach($vin_fin as $vin_fin2)
                                    <option value="{{ $vin_fin2->id ?? '' }}">{{ $vin_fin2->nombre }}</option>
                                @endforeach
                            </select>
                            <label for="Eid_motivo_fin_vinculo">Motivo de Cese</label>
                        </div>
                        <x-select-tipo-doc id="Eid_tipo_documento_fin" name="id_tipo_documento_fin" :tdoc="$tdoc" categoria="DES" label="Tipo Documento"/>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="Enro_doc_fin" name="nro_doc_fin" placeholder="Ejm. 232-2023">
                                <label for="entidad" class="required">Nro Documento<span class="required">*</span></label>
                            </div>
                        <x-file-upload 
                            id="Earchivo_cese2" 
                            name="archivo_cese"
                            label="Archivo Cese"
                            nameFolio="nro_folios2"
                        />
                    </div>
                    <button type="submit" class="btn btn-primary" >Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>



<x-layout-dato-legajo 
    title="Vinculo laboral"
    :columns="[1,2,3,4, 'Acciones']"
>
    @include('datoslegajo.forms.formVinculos')
</x-layout-dato-legajo>

@include('datoslegajo.modals.modalVinculos')



<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js/script>"></script>
<script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>
<script>


    var idp = {!! json_encode($idp ?? null) !!};
    var dp = {!! json_encode($dp ?? null) !!};

    var select2Config = {
            url: '/getPersonal_list',
            minInputLength: 0,
            loadAllIfEmpty: true
        };
    if (dp !== null && idp !== null) {
        select2Config.preselectedData = [
            { selectId: 'dnipcv', text: dp.nombre, id: idp }
        ];
    }
    initializeSelect2(['dnipcv'], select2Config);
    initializeSelect2(['Eid_unidad_organica'], {
        url: '/getAreas_list',
        placeholder: 'Seleccione una unidad orgánica',
        minInputLength: 0,
        loadAllIfEmpty: true,
    });
    initializeSelect2(['Eid_depens'], {
        url: '/getAreas_list',
        placeholder: 'Seleccione una unidad orgánica',
        minInputLength: 0,
        loadAllIfEmpty: true,
    });
    initializeSelect2(['Eid_cargo'], {
        url: '/getCargo_list',
        placeholder: 'Seleccione una unidad orgánica',
        minInputLength: 0,
        loadAllIfEmpty: true,
    });


    //
    $('#Eid_unidad_organica').on('change', function() {
        const selectedUnitId = $(this).val();
        $.ajax({
            url: '/getAreas_list', // Ajusta la URL si es necesario
            data: {
                id: selectedUnitId
            },
            success: function(data) {
                const option = new Option(data[0].nombre_dep, data[0].dependencia, true, true);
                $(`#Eid_depens`).append(option).trigger('change');
            }
        });
    });
    document.getElementById('formAdd').addEventListener('submit', function(e) {
        e.preventDefault();
        onClickSaveAdd("vinculo_crud","vinculo_crud")
        $('#Eid_unidad_organica, #Eid_depens, #Eid_cargo').val(null).trigger('change');
    });
    
    document.getElementById('formEdit').addEventListener('submit', function(e) {
        e.preventDefault();
        onClickSaveEdit("vinculo_crud", "vinculo_crud");
        $('#Edcargo, #Edunidad_organica, #Eddepens').val(null).trigger('change');
    });

    document.getElementById('tvinculoForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var idvf = $('#id_vinculo_f').val();
        onClickSaveEdit("vinculo_crud", "vinculo_crud","tvinculoForm",idvf);
    });

    
    document.getElementById('adendaForm').addEventListener('submit', function(e) {
        e.preventDefault();
        storeOrUpdateAdenda();
    });


    document.querySelectorAll('.nav-link').forEach(tab => {     
        tab.addEventListener('shown.bs.tab', function (event) {
            disableInputs()
        });
    });

    function onClickView (row) {
        $('#Edunidad_organica, #Eddepens, #Edcargo').val(null).trigger('change');
        initializeSelect2(['Edunidad_organica','Eddepens'], {
            url: '/getAreas_list',
            placeholder: 'Seleccione una unidad orgánica',
            minInputLength: 0,
            loadAllIfEmpty: true,
            preselectedData: [
                { selectId: 'Edunidad_organica', text: row.unidad_organica, id: row.id_unidad_organica },
                { selectId: 'Eddepens', text: row.depens, id: row.id_depens },
            ]
        });
        initializeSelect2(['Edcargo'], {
            url: '/getCargo_list',
            placeholder: 'Seleccione una unidad orgánica',
            minInputLength: 0,
            loadAllIfEmpty: true,
            preselectedData: [
                { selectId: 'Edcargo', text: row.cargo, id: row.id_cargo }
            ]
        });
        viewCRUD(row.id,"vinculo_crud","Vinculo");
    }
    function onClickEdit (row) {
        $('#Edunidad_organica, #Eddepens, #Edcargo').val(null).trigger('change');
        initializeSelect2(['Edunidad_organica','Eddepens'], {
            url: '/getAreas_list',
            placeholder: 'Seleccione una unidad orgánica',
            minInputLength: 0,
            loadAllIfEmpty: true,
            preselectedData: [
                { selectId: 'Edunidad_organica', text: row.unidad_organica, id: row.id_unidad_organica },
                { selectId: 'Eddepens', text: row.depens, id: row.id_depens },
            ]
        });
        initializeSelect2(['Edcargo'], {
            url: '/getCargo_list',
            placeholder: 'Seleccione una unidad orgánica',
            minInputLength: 0,
            loadAllIfEmpty: true,
            preselectedData: [
                { selectId: 'Edcargo', text: row.cargo, id: row.id_cargo }
            ]
        });
        editCRUD(row.id,"vinculo_crud","Vinculo");
    }


    function disableInputs(){
        document.querySelectorAll('.tab-pane').forEach(pane => {
            if (pane.classList.contains('active')) {
                const inputs = pane.querySelectorAll('input, select, archivo');
                inputs.forEach(input => {
                    input.classList.add('save');
                });

                pane.querySelectorAll('input, .form-select, archivo').forEach(input => {
                    input.disabled = false;
                });
            } else{
                const inputs = pane.querySelectorAll('input, select, archivo');
                inputs.forEach(input => input.classList.remove('save'));

                pane.querySelectorAll('input, .form-select, archivo').forEach(input => {
                    input.disabled = true;
                });
                
            }
        });       
    }


    
    var dataTable = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,  
        scrollX: true,   
        ajax: {
        url: '/vinculo_crud',
        //FILTROS PARA PERSONAL
        data: function (d) {
                if (idp) {
                    d.filter_field = 'personal_id';
                    d.filter_value = idp;
                    idp = null; 
                } else {
                    d.filter_field = 'personal_id';
                    d.filter_value = $('#dnipcv').val();
                }
            }
        },
        //NECESARIO PARA LOS BOTONES EN CABEZERA
        layout: {
            topStart: {
                buttons: [
            ],
            }
        },
        fixedColumns: {
            leftColumns: 0, 
            rightColumns: 1
        },
        
        columnDefs: [
                { orderable: false, targets: -1 }, // Deshabilita el orden en la columna de acciones
            ],

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
            { title: 'PERSONAL', data: 'nombre_completo', name: 'nombre_completo' },
            { title: 'REGIMEN', data: 'id_regimen', name: 'id_regimen' },
            { title: 'CONDICION LABORAL', data: 'id_condicion_laboral', name: 'id_condicion_laboral' },
            { 
                title: 'ADENDAS', 
                data: 'cantidad_adendas', 
                name: 'cantidad_adendas', 
                orderable: false, 
                render: function(data, type, row) {
                    return data !== "0"  ? `<button class='btn btn-primary' onclick='mostrar_adendas(${row.id})'>${data}</button>` : '';
                }
            }, 
            { title: 'CARGO', data: 'cargo', name: 'cargo' },
            { title: 'UNIDAD ORGANICA', data: 'unidad_organica', name: 'unidad_organica' },
            { title: 'FECHA INICIO', data: 'fecha_ini', name: 'fecha_ini', render: function(data, type, row) {
                    return formatDate(data);
            }},
         
            { title: 'FECHA FIN', data: 'fecha_fin', name: 'fecha_fin' , render: function(data, type, row) {
                    return formatDate(data);
            }},

       
            {
                title: 'ARCH ING',
                data: 'archivo',
                name: 'archivo',
                render: function(data, type, row) {
                    if (data) {
                        return `<button type="button" class="btn btn-primary btn-sm ver-pdf" data-pdf="${data}">
                                        <i class="fas fa-file-pdf"></i>
                                </button>`;
                    } else {
                        return 'No hay archivo';
                    }
                }
            }, 
            {
                title: 'ARCH CESE',
                data: 'archivo_cese',
                name: 'archivo_cese',
                render: function(data, type, row) {
                    if (data) {
                        return '<button type="button" class="btn btn-primary btn-sm ver-pdf" data-pdf="' + data + '"><i class="fas fa-file-pdf"></button>';
                    } else {
                        return 'No hay archivo';
                    }
                }
            },         
            { 
                title: "",  data: 'id', name: 'id', orderable: false, render: function(data, type,row) {
                    return `
                    <div class="d-flex flex-row">
                        <button onclick='onClickView(${JSON.stringify(row)})' id="btnVer" class="btn btn-info mr-2">
                            <i class="fas fa-eye" id="editIcon"></i>
                        </button>
                        @hasanyrole('ADMIN|COORDINADOR')
                        <button onclick='deleteCRUD(${data},"vinculo_crud","vinculo_crud")' class='btn btn-danger btn-sm mr-2' title='Eliminar'>
                            <i class='fas fa-trash'></i>
                        </button>
                        <button onclick='onClickEdit(${JSON.stringify(row)})' class='btn btn-info' title='Editar'>
                            <i class='fas fa-edit'></i>
                        </button>
                        @endhasanyrole
                    </div>
                    `
                    ;
                }
            }
        ]
    ,
    order: [[6, 'desc']],
    columnDefs: [{
    targets: 0,
    className: 'dt-left'
    }],

});

    //FILTROS Y SELECCIONAR PERSONAL
    setupFilters(dataTable, '#dnipcv', '#dt-search-0');

//FUNCIONES CLICK TABLA
$('#dataTable tbody').on('click', 'tr', function() {
    //EVALUAR
    $('#adendarButton').prop('disabled', true);
    $('#terminarVButton').prop('disabled', true);
    var data = dataTable.row(this).data();
    if ($(this).hasClass('selected')) {
        $(this).removeClass('selected');
        $('#adendarButton').prop('disabled', true);
        $('#terminarVButton').prop('disabled', true);
    } else {
        dataTable.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
        if (data.fecha_ini !== null && data.fecha_fin !== null) {
            console.log(data.fecha_ini, data.fecha_fin);
            $('#adendarButton').prop('disabled', false);
                $('#adendarButton').off('click').on('click', function() {
                adendar(data.id); 
            });
        }
        if (data.fecha_fin === null) {
            $('#terminarVButton').prop('disabled', false);
            $('#terminarVButton').off('click').on('click', function() {
                terminar_vinculo(data.id); 
            });
        }
    }
});


// Crear y agregar el select personalizado al contenedor de botones
var customInput = $(`
    <button type="button" id="terminarVButton" class="btn btn-danger" style="margin-right: 5px;" disabled>Terminar Vínculo</button>
    <button type="button" id="adendarButton" class="btn btn-primary" disabled>Adendar</button>

`);
$('#dataTable_wrapper .dt-buttons').append(customInput);

// ADENDAS

function adendar(id_vinculo = null) {
    $('#adendaModalForm').modal('show');
    $('#adendacrud').text('Agregar Adenda');
    $('#adendaForm').trigger('reset');
    $('#adenda_id').val('');
    if (id_vinculo) {
        $('#id_vinculo').val(id_vinculo);
    }
}

function terminar_vinculo(id_vinculo = null) {
    $('#tvinculoModalForm').modal('show');
    if (id_vinculo) {
        $('#id_vinculo_f').val(id_vinculo);
    }
}

function storeOrUpdateAdenda() {
    const adendaId = $('#adenda_id').val();
    const formData = new FormData($('#adendaForm')[0]);

    let url = '../adendas_crud';
    let method = 'POST';
    if (adendaId) {
        url = `../adendas_crud/${adendaId}`;
        method = 'POST';
        formData.append('_method', 'PUT');
    }

    $.ajax({
        url: url,
        type: method,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            let mensaje = adendaId ? 'Adenda actualizada correctamente' : 'Adenda agregada correctamente';

            Swal.fire({
                icon: 'success',
                title: mensaje,
                showConfirmButton: false, 
                timer: 1000 
            }).then(() => { 
                $('#adendaModalForm').modal('hide');
                //Recargar ambas tablas
                dataTable.ajax.url('../vinculo_crud').load();
                $('#adendaDataTable').DataTable().ajax.reload();
            });


        },
        error: function (xhr, status, error) {
            let errorMessage = 'Error al guardar la adenda';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                //Recorrer errores y mostrarlos
                let errores = xhr.responseJSON.errors;
                errorMessage = "";
                for (let campo in errores) {
                    errorMessage += errores[campo][0] + "<br>";
                }
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }

            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: errorMessage, 
                showConfirmButton: false, 
                timer: 1000 
            });
        }
    });
}

function editAdenda(id) {
    $.ajax({
        url: `../adendas_crud/${id}`,
        type: 'GET',
        success: function (data) {

            $('#adendacrud').text('Actualizar Adenda');
            $('#adenda_id').val(data.id); // Guardar el ID de la adenda
            $('#id_vinculo').val(data.id_vinculo);
            $('#fecha_ini').val(data.fecha_ini);
            $('#fecha_fin').val(data.fecha_fin);
            $('#nrodoc').val(data.nrodoc);
            // El campo "archivo" no se llena porque es un archivo

            $('#adendaModalForm').modal('show');
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: 'Error al cargar los datos de la adenda', 
                showConfirmButton: false, 
                timer: 1000 
            });
        }
    });
}

function borrarAdenda(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `../adendas_crud/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminada!',
                        html: 'La adenda ha sido eliminada correctamente.', 
                        showConfirmButton: false, 
                        timer: 1000 
                    });  
                    $('#adenda_id').val(''); 
                    $('#adendaDataTable').DataTable().ajax.reload(); 
                    dataTable.ajax.url('../vinculo_crud').load();
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        html: 'Ocurrió un error al intentar eliminar la adenda.', 
                        showConfirmButton: false, 
                        timer: 1000 
                    });       
                }
            });
        } else {
            Swal.fire({
                icon: 'info',
                title: 'Cancelado',
                html: 'La adenda no fue eliminada.', 
                showConfirmButton: false, 
                timer: 1000 
            });
        }
    });
}


function mostrar_adendas(id) {
    $('#adendaModal').modal('show');
    $('#adendaModalLabel').text('Adendas');

    // Destruir el DataTable anterior si existe
    if ($.fn.DataTable.isDataTable('#adendaDataTable')) {
        $('#adendaDataTable').DataTable().destroy();
    }
    
    $('#adendaDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: `../adendas_crud`,
            data: {
                filter_field: 'id_vinculo',
                filter_value: `${id}`
            },
            type: 'GET',
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
            { data: 'id' },
            { data: 'fecha_ini' },
            { data: 'fecha_fin' },
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
            { data: 'nrodoc' },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-primary btn-sm mr-1" onclick="editAdenda(${row.id})">
                            Editar
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="borrarAdenda(${row.id})">
                            Borrar
                        </button>`;
                }
            }
        ],
   
        autoWidth: true, // Ajuste automático del ancho
        searching: false, // Deshabilitar la búsqueda
        paging: true, // Habilitar la paginación
        info: false // Mostrar información de la tabla (ej. "Mostrando 1 a 10 de 50")
    });
}

</script>
