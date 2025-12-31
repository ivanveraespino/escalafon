
<style>

#estado {
    width: auto; /* Ajusta el tamaño del select */
    min-width: 120px; /* Tamaño mínimo para que no sea demasiado pequeño */
}
.align-left {
    text-align: left;
}

.derecho-hab-style {
    border-radius: 5px; /* Bordes redondeados */
    display: none; /* Ocultar inicialmente */
}
.dderecho-hab-style {
    border-radius: 5px; /* Bordes redondeados */
    display: none; /* Ocultar inicialmente */
}
.derecho-hab-s-style {
    background-color: #eaf7e8; /* Verde claro pastel */
    border-radius: 5px; /* Bordes redondeados */
}
.emergencia-style {

    border-radius: 5px; /* Bordes redondeados */
    display: none; /* Ocultar inicialmente */
}
.demergencia-style {

border-radius: 5px; /* Bordes redondeados */
display: none; /* Ocultar inicialmente */
}
.emergencia-s-style {
    background-color: #fff8dc; /* Amarillo claro pastel */
    border-radius: 5px; /* Bordes redondeados */
}
/**/ 
.parent-hab-style {
    background-color: #edf5fc; /* Azul muy claro */
    border-radius: 5px; /* Bordes redondeados */
    display: none; /* Ocultar inicialmente */
}
.dparent-hab-style {
    background-color: #edf5fc; /* Azul muy claro */
    border-radius: 5px; /* Bordes redondeados */
    display: none; /* Ocultar inicialmente */
}
.parent-hab-s-style {
    background-color: #edf5fc; /* Azul muy claro */
    border-radius: 5px; /* Bordes redondeados */
}

table.dataTable tbody tr.blue > .dtfc-fixed-left,
table.dataTable tbody tr.blue > .dtfc-fixed-right {
  background-color: blue;
}

</style>

<x-layout-dato-legajo 
    title="Familiares"
    :columns="[1,2,3,4,1,2,3,4,1,2, 'Acciones']"
>
    @include('datoslegajo.forms.formFamiliares')
</x-layout-dato-legajo>

@include('datoslegajo.modals.modalFamiliares')


<link href="https://cdn.datatables.net/fixedcolumns/4.2.1/css/fixedColumns.dataTables.css" rel="stylesheet" type="text/css" />
<script src="https://cdn.datatables.net/fixedcolumns/4.2.1/js/dataTables.fixedColumns.js"></script>

<script>   

    var idp = {!! json_encode($idp ?? null) !!};
    var dp = {!! json_encode($dp ?? null) !!};

    var select2Config = {
            url: '/getPersonal_list',
            minInputLength: 0,
            loadAllIfEmpty: false
        };
    if (dp !== null && idp !== null) {
        select2Config.preselectedData = [
            { selectId: 'dnipcv', text: dp.nombre, id: idp }
        ];
    }    
    initializeSelect2(['dnipcv'], select2Config);
    $(document).ready(function () {
        idp = {!! json_encode($idp ?? null) !!};
        convertirAMayusculas();
        // Función para actualizar la visibilidad
        $('#Eparentesco, #Ederecho_habiente, #Eemergencia, #Edparentesco, #Edderecho_habiente, #Edemergencia').on('change', updateVisibility);
        $('#Edparentesco, #Edderecho_habiente, #Edemergencia').on('change', updateVisibility2);
        updateVisibility();

    });

    document.getElementById('formAdd').addEventListener('submit', function(e) {
        e.preventDefault(); 
        onClickSaveAdd("familiares_crud","familiares_crud")
        updateVisibility()
    });
    
    document.getElementById('formEdit').addEventListener('submit', function(e) {
        e.preventDefault();
        onClickSaveEdit("familiares_crud", "familiares_crud");
    });

    function onClickEdit(data) {
        editCRUD(data,"familiares_crud","Familiar")
        .done(function(r){
            updateVisibility2();
        });
    }

    function onClickView (data) {
        viewCRUD(data,"familiares_crud","Familiar")
        .done(function(r){
            updateVisibility2();
        });
        
    }

    function updateVisibility() {
            const derechoHabiente = $('#Ederecho_habiente').val();
            const emergencia = $('#Eemergencia').val();
            const parentesco = $('#Eparentesco').val();
            if (parentesco === 'CONYUGUE') {
                $('.parent-hab-style').show();
                $('.estadocv').hide();
            } else {
                $('.parent-hab-style').hide();
                $('.estadocv').show();
            }
            if (derechoHabiente === 'SI') {
                $('.derecho-hab-style').show();
            } else {
                $('.derecho-hab-style').hide();
            }

            if (emergencia === 'SI') {
                $('.emergencia-style').show();
            } else {
                $('.emergencia-style').hide();
            }
    }

    function updateVisibility2() {
            const derechoHabiente = $('#Edderecho_habiente').val();
            const emergencia = $('#Edemergencia').val();
            const parentesco = $('#Edparentesco').val();
            
            if (parentesco === 'CONYUGUE') {
                $('.dparent-hab-style').show();
                $('.destadocv').hide();
            } else {
                $('.dparent-hab-style').hide();
                $('.destadocv').show();
            }
            if (derechoHabiente === 'SI') {
                $('.dderecho-hab-style').show();
            } else {
                $('.dderecho-hab-style').hide();
            }

            if (emergencia === 'SI') {
                $('.demergencia-style').show();
            } else {
                $('.demergencia-style').hide();
            }
    }

    var dataTable = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,  
        scrollX: true,   
        ajax: {
            url: '{{ url('/familiares_crud/') }}',
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
            emptyTable: "No existen registros", 
            infoEmpty: "Mostrando 0 de 0 of 0 entradas",
            loadingRecords: "Cargando...",
        },
        columns: [
            {title: 'PERSONAL', data: 'nombre_completo', name: 'nombre_completo' },
            {title: 'APELLIDO PATERNO',data: 'apaterno', name: 'apaterno' },
            {title: 'APELLIDO MATERNO',data: 'amaterno', name: 'amaterno' },
            {title: 'NOMBRES',data: 'nombres', name: 'nombres' },
            {title: 'PARENTESCO', data: 'parentesco', name: 'parentesco' },
            {title: 'VIVE', data: 'vive', name: 'vive' },
            {title: 'EMERGENCIA', data: 'emergencia', name: 'emergencia' },
            {
                title: 'DNI',
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
            { title: 'DERECHO HABIENTE', data: 'derecho_habiente', name: 'derecho_habiente' },
            {
                title: 'ARCHIVO VINCULO',
                data: 'archivo_vinculo',
                name: 'archivo_vinculo',
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
                title: "ACCIONES", data: 'id', name: 'id', orderable: false, render: function(data, type) {
                    return `
                    <div class="d-flex flex-row">
                        <button onclick='onClickView(${data})' id="btnVer" class="btn btn-info mr-2">
                            <i class="fas fa-eye" id="editIcon"></i>
                        </button>
                        @hasanyrole('ADMIN|COORDINADOR')
                        <button onclick='deleteCRUD(${data},"familiares_crud","familiares_crud")' class='btn btn-danger btn-sm mr-2' title='Eliminar'>
                            <i class='fas fa-trash'></i>
                        </button>
                        <button onclick='onClickEdit(${data})' class='btn btn-info' title='Editar'>
                            <i class='fas fa-edit'></i>
                        </button>
                        @endhasanyrole
                    </div>
                    `;
                }
            }
        ],
        order: [[1, 'asc'], [2, 'asc'], [3, 'asc']],

    });
    //FILTROS Y SELECCIONAR PERSONAL
    setupFilters(dataTable, '#dnipcv', '#dt-search-0');
</script>
