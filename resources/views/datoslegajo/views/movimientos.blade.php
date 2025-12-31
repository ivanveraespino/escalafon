
<style>

#estado {
    width: auto; /* Ajusta el tamaño del select */
    min-width: 120px; /* Tamaño mínimo para que no sea demasiado pequeño */
}
.align-left {
    text-align: left;
}

</style>

<x-layout-dato-legajo 
    title="Rotaciones"
    :columns="['TRABJADOR','U. ORG ORIGEN','U. ORG DESTINO','LUGAR DESTINO','CARGO','FECHA INCIO','FECHA FIN','TIPO DOC','DOCUMENTO','FECHA DOC','ARCHIVO',  'Acciones']"
>
    @include('datoslegajo.forms.formMovimientos')
</x-layout-dato-legajo>
@include('datoslegajo.modals.modalMovimientos')



<script>

    var idp = {!! json_encode($idp ?? null) !!};
    var dp = {!! json_encode($dp ?? null) !!};
    var vl_last = {!! json_encode($vl_last ?? null) !!};

    var select2Config = {
            url: '/getPersonal_list',
            minInputLength: 0,
            loadAllIfEmpty: true
        };
    var select2Config_uo_orig = {
        url: '/getAreas_list',
        minInputLength: 0,
        loadAllIfEmpty: true
    };
    if (dp !== null && idp !== null) {
        select2Config.preselectedData = [
            { selectId: 'dnipcv', text: dp.nombre, id: idp }
        ];
    }    
    
    if (vl_last !== null) {        
            select2Config_uo_orig.preselectedData = [
            { selectId: 'Eunidad_organica', text: vl_last.nombre_area, id: vl_last.id_unidad_organica }
        ];
    }
    initializeSelect2(['dnipcv'], select2Config);
    initializeSelect2(['Eunidad_organica'], select2Config_uo_orig);
    initializeSelect2(['Eunidad_organica_destino'], {
        url: '/getAreas_list',
        placeholder: 'Seleccione un cargo',
        minInputLength: 0,
        loadAllIfEmpty: true,
    });

    initializeSelect2(['Ecargo'], {
        url: '/getCargo_list',
        placeholder: 'Seleccione un cargo',
        minInputLength: 1,
        loadAllIfEmpty: true,
    });

    $(document).ready(function() {
        convertirAMayusculas();      
        disableInputs();  
    });
    //UNIDAD ORGANICA ORIGEN
    function fetchUnidadOrganica(selectedValue) {
        if (!selectedValue) {
            $('#Eunidad_organica').val(null).trigger('change');
            return;
        }
        $.ajax({
            url: '/get_uo_vigente/' + selectedValue,
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response && response.id_unidad_organica && response.nombre_unidad_organica) {
                    const idUnidad = response.id_unidad_organica;
                    const nombreUnidad = response.nombre_unidad_organica;
                    // Verificar si el valor ya existe en el select
                    const existingOption = $('#Eunidad_organica').find(`option[value="${idUnidad}"]`);

                    if (existingOption.length === 0) {
                        // Si no existe, agregarlo como una nueva opción
                        const newOption = new Option(nombreUnidad, idUnidad, true, true);
                        $('#Eunidad_organica').append(newOption);
                    }

                    // Seleccionar el valor en el select
                    $('#Eunidad_organica').val(idUnidad).trigger('change');
                } else {
                    // Si no hay datos válidos, limpiar el select
                    $('#Eunidad_organica').val(null).trigger('change');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error al obtener datos:', error);
                // Limpiar el select en caso de error
                $('#Eunidad_organica').val(null).trigger('change');
            }
        });
    }
    $('#dnipcv').on('change', function () {
        const selectedValue = $(this).val();
        fetchUnidadOrganica(selectedValue);
    });
  

    document.querySelectorAll('.nav-link').forEach(tab => {     
        tab.addEventListener('shown.bs.tab', function (event) {
            disableInputs()
        });
    });

    document.getElementById('formAdd').addEventListener('submit', function(e) {
        e.preventDefault();
        onClickSaveAdd("movimientos_crud","movimientos_crud")
        $('#Eunidad_organica, #Eunidad_organica_destino, #Ecargo').val(null).trigger('change');
    });
    
    document.getElementById('formEdit').addEventListener('submit', function(e) {
        e.preventDefault();
        onClickSaveEdit("movimientos_crud", "movimientos_crud");
    });


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

    function onClickView(row) {
        $('#Edunidad_organica, #Edunidad_organica_destino, #Edcargo').val(null).trigger('change');
        initializeSelect2(['Edunidad_organica', 'Edunidad_organica_destino'], {
            url: '/getAreas_list',
            placeholder: 'Seleccione una unidad orgánica',
            minInputLength: 0,
            loadAllIfEmpty: true,
            preselectedData: [
                { selectId: 'Edunidad_organica', text: row.unidad_organica, id: row.unidad_organica_id },
                { selectId: 'Edunidad_organica_destino', text: row.unidad_organica_destino, id: row.unidad_organica_destino_id }
            ]
        });
        initializeSelect2(['Edcargo'], {
            url: '/getCargo_list',
            placeholder: 'Seleccione una unidad orgánica',
            minInputLength: 0,
            loadAllIfEmpty: true,
            preselectedData: [
                { selectId: 'Edcargo', text: row.cargo, id: row.cargo_id },
            ]
        });
        viewCRUD(row.id,"movimientos_crud","Movimiento");
    };

    function onClickEdit(row) {
        $('#Edunidad_organica, #Edunidad_organica_destino, #Edcargo').val(null).trigger('change');
        initializeSelect2(['Edunidad_organica', 'Edunidad_organica_destino'], {
            url: '/getAreas_list',
            placeholder: 'Seleccione una unidad orgánica',
            minInputLength: 0,
            loadAllIfEmpty: true,
            preselectedData: [
                { selectId: 'Edunidad_organica', text: row.unidad_organica, id: row.unidad_organica_id },
                { selectId: 'Edunidad_organica_destino', text: row.unidad_organica_destino, id: row.unidad_organica_destino_id }
            ]
        });
        initializeSelect2(['Edcargo'], {
            url: '/getCargo_list',
            placeholder: 'Seleccione una unidad orgánica',
            minInputLength: 0,
            loadAllIfEmpty: true,
            preselectedData: [
                { selectId: 'Edcargo', text: row.cargo, id: row.cargo_id },
            ]
        });
        editCRUD(row.id,"movimientos_crud","Movimiento");
    };

    var dataTable = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,  
        scrollX: true,   
        ajax: {
            url: '/movimientos_crud',
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
            { title: 'U. ORGANICA ORIGEN', data: 'unidad_organica', name: 'unidad_organica' },
            { title: 'U. ORGANICA DESTINO', data: 'unidad_organica_destino', name: 'unidad_organica_destino' },
            { title: 'CARGO', data: 'cargo', name: 'cargo' },
            { title: 'FECHA INI',  data: 'fecha_ini', name: 'fecha_ini', orderable: false, render: function(data, type, row) {
            return formatDate(data);
            }},        
            { title: 'FECHA FIN',  data: 'fecha_fin', name: 'fecha_fin', orderable: false, render: function(data, type, row) {
                return formatDate(data);
            }},
            { title: 'TIPO DOC',  data: 'tdoc', name: 'tdoc' },
            { title: 'DOCUMENTO',  data: 'nrodoc', name: 'nrodoc' },
            { title: 'FECHA DOC',  data: 'fechadoc', name: 'fechadoc', orderable: false, render: function(data, type, row) {
                return formatDate(data);
            }},
            {   title: 'ARCHIVO',
                data: 'archivo',
                name: 'archivo',
                render: function(data, type, row) {
                    if (data) {
                        return '<button type="button" class="btn btn-primary btn-sm ver-pdf" data-pdf="' + data + '"><i class="fas fa-file-pdf"></button>';
                    } else {
                        return 'No hay archivo';
                    }
                }
            },
            { title: 'FOLIOS', data: 'nro_folio', name: 'nro_folio' },

            { 
                title: "",  data: 'id', name: 'id', orderable: false, render: function(data, type,row) {
                    return `
                <div class="d-flex flex-row">
                        <button onclick='onClickView(${JSON.stringify(row)})' id="btnVer" class="btn btn-info mr-2">
                            <i class="fas fa-eye" id="editIcon"></i>
                        </button>
                        @hasanyrole('ADMIN|COORDINADOR')
                        <button onclick='deleteCRUD(${data},"movimientos_crud","movimientos_crud")' class='btn btn-danger btn-sm mr-2' title='Eliminar'>
                            <i class='fas fa-trash'></i>
                        </button>
                        <button onclick='onClickEdit(${JSON.stringify(row)},"movimientos_crud","Movimiento")' class='btn btn-info' title='Editar'>
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

    order: [[1, 'asc'], [2, 'asc'], [3, 'asc']],
    columnDefs: [{
    targets: 0,
    className: 'dt-left'
    }],

    });
    
    //FILTROS Y SELECCIONAR PERSONAL
    setupFilters(dataTable, '#dnipcv', '#dt-search-0');

</script>
