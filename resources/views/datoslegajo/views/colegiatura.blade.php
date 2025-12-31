


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
    title="Colegiatura"
    :columns="['TRABJADOR','COLEGIO','NRO COLEGIATURA','FECHA DOC','NRO FOLIO','ARCHIVO', 'Acciones']"
>
    @include('datoslegajo.forms.formColegiatura')
</x-layout-dato-legajo>

@include('datoslegajo.modals.modalColegiatura')


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
    $(document).ready(function() {
        convertirAMayusculas();
    });

    document.getElementById('formAdd').addEventListener('submit', function(e) {
        e.preventDefault(); 
        onClickSaveAdd("colegiatura_crud","colegiatura_crud")
    });
    
    document.getElementById('formEdit').addEventListener('submit', function(e) {
        e.preventDefault();
        onClickSaveEdit("colegiatura_crud", "colegiatura_crud");
    });

    var dataTable = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,  
        scrollX: true,   
        ajax: {
            url: '/colegiatura_crud',
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
            right: 1 // Fija la última columna
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
            
            {title: 'COLEGIO', data: 'nombre_colegio', name: 'nombre_colegio' },
            {title: 'NRO COLEGIATURA', data: 'nrodoc', name: 'nrodoc' },
            { 
                title: 'ESTADO', 
                data: 'estado', 
                name: 'estado',
                render: function(data, type, row) {
                    return data == 1 ? 'Habilitado' : 'No habilitado';
                }
            },
            {title: 'FECHA DOCUMENTO', data: 'fechadoc', name: 'fechadoc', orderable: false, render: function(data, type, row) {
                    return formatDate(data);
            }},        
            {title: 'NRO FOLIO', data: 'nro_folio', name: 'nro_folio' },
            {
                    title: 'ARCHIVO',
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
            
            
            { 
                title: "ACCIONES",  data: 'id', name: 'id', orderable: false, render: function(data, type) {
                    return `
                    <div class="d-flex flex-row">
                        <button onclick='viewCRUD(${data},"colegiatura_crud","Sanción")' id="btnVer" class="btn btn-info mr-2">
                            <i class="fas fa-eye" id="editIcon"></i>
                        </button>
                        @hasanyrole('ADMIN|COORDINADOR')
                        <button onclick='deleteCRUD(${data},"colegiatura_crud","colegiatura_crud")' class='btn btn-danger btn-sm mr-2' title='Eliminar'>
                            <i class='fas fa-trash'></i>
                        </button>
                        <button onclick='editCRUD(${data},"colegiatura_crud","Sanción")' class='btn btn-info' title='Editar'>
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
