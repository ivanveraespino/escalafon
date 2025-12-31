<style>

.especialidad-style {
    border-radius: 5px; /* Bordes redondeados */
    display: none; /* Ocultar inicialmente */
}
.despecialidad-style {
    border-radius: 5px; /* Bordes redondeados */
    display: none; /* Ocultar inicialmente */
}
</style>

<x-layout-dato-legajo 
    title="Formación Academica"
    :columns="['TRABJADOR','EDUCACION','CENTRO DE ESTUDIOS','FECHA INICIO','FECHA FIN','GRADO ACADEMICO','ARCHIVO','FOLIOS', 'Acciones']"
>
    @include('datoslegajo.forms.formEstudios')
</x-layout-dato-legajo>
@include('datoslegajo.modals.modalEstudios')


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
    
    function updateVisibility() { 
        const nivel_educacion = $('#Enivel_educacion').val();
        // Verificar si contiene "PRIMARIA" usando includes
        if (nivel_educacion.includes('PRIMARIA') || nivel_educacion.includes('SECUNDARIA') || nivel_educacion === "") {
            $('.especialidad-style').hide();
        } else {
            $('.especialidad-style').show();
        }
    }

    function updateVisibility2() { 
        const nivel_educacion = $('#Ednivel_educacion').val();
        // Verificar si contiene "PRIMARIA" usando includes
        if (nivel_educacion.includes('PRIMARIA') || nivel_educacion.includes('SECUNDARIA') || nivel_educacion === "") {
            $('.despecialidad-style').hide();
        } else {
            $('.despecialidad-style').show();
        }
    }

    $(document).ready(function() {
        convertirAMayusculas();
        $('#Enivel_educacion').on('change', updateVisibility);
        $('#Ednivel_educacion').on('change', updateVisibility2);
        updateVisibility();  
    });

    document.getElementById('formAdd').addEventListener('submit', function(e) {
        e.preventDefault(); 
        onClickSaveAdd("estudios_crud","estudios_crud")
    });
    
    document.getElementById('formEdit').addEventListener('submit', function(e) {
        e.preventDefault();
        onClickSaveEdit("estudios_crud", "estudios_crud");
    });

    function onClickEdit(data) {
        editCRUD(data,"estudios_crud","estudios_crud")
        .done(function(r){
            updateVisibility2();
        });
    }

    function onClickView (data) {
        viewCRUD(data,"estudios_crud","estudios_crud")
        .done(function(r){
            updateVisibility2();
        });
        
    }

    var dataTable = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,  
        scrollX: true,   
        ajax: {
            url: '/estudios_crud',
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
            {title: 'PERSONAL', data: 'nombre_completo', name: 'nombre_completo' },
            
            {title: 'NIVEL DE EDUCACIÓN', data: 'nivel_educacion', name: 'nivel_educacion' },
            {title: 'CENTRO DE ESTUDIOS', data: 'centroestudios', name: 'centroestudios' },
            { title: 'FECHA INICIO', data: 'fecha_ini', name: 'fecha_ini', render: function(data, type, row) {
                return formatDate(data);
            }},
            { title: 'FECHA FIN', data: 'fecha_fin', name: 'fecha_fin', orderable: false, render: function(data, type, row) {
                return formatDate(data);
            }},
            {title: 'ESPECIALIDAD', data: 'especialidad', name: 'especialidad', orderable: false },
            { title: 'ARCHIVO',
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
            {title: 'FOLIOS', data: 'nro_folio', name: 'nro_folio' },
            { 
                title: "ACCIONES",  data: 'id', name: 'id', orderable: false, render: function(data, type) {
                    return `
                    <div class="d-flex flex-row">
                        <button onclick='onClickView(${data},"estudios_crud","Formación Academica")' id="btnVer" class="btn btn-info mr-2">
                            <i class="fas fa-eye" id="editIcon"></i>
                        </button>
                        @hasanyrole('ADMIN|COORDINADOR')
                        <button onclick='deleteCRUD(${data},"estudios_crud","estudios_crud")' class='btn btn-danger btn-sm mr-2' title='Eliminar'>
                            <i class='fas fa-trash'></i>
                        </button>
                        <button onclick='onClickEdit(${data},"estudios_crud","Formación Academica")' class='btn btn-info' title='Editar'>
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
