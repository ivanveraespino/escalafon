

<x-layout-dato-legajo 
    title="Experiencia Laboral"
    :columns="['1','2','3','4','5','6','7','8']"
>
    @include('datoslegajo.forms.formExpLab')
</x-layout-dato-legajo>
    @include('datoslegajo.modals.modalExpLab')

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
        onClickSaveAdd("experiencia_crud","experiencia_crud")
    });
    
    document.getElementById('formEdit').addEventListener('submit', function(e) {
        e.preventDefault();
        onClickSaveEdit("experiencia_crud", "experiencia_crud");
    });
    
    var dataTable = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,  
        scrollX: true,   
        ajax: {
            url: '/experiencia_crud',
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
            {title: 'TIPO DE ENTIDAD', data: 'tipo_entidad', name: 'tipo_entidad' },
            {title: 'ENTIDAD', data: 'entidad', name: 'entidad' },
            {title: 'CARGO', data: 'cargo', name: 'cargo' },
            { title: 'FECHA DE INICIO', data: 'fecha_ini', name: 'fecha_ini', orderable: false, render: function(data, type, row) {
                return formatDate(data);
            }},
            { title: 'FECHA DE FIN', data: 'fecha_fin', name: 'fecha_fin', orderable: false, render: function(data, type, row) {
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
            {title: 'FOLIOS', data: 'nro_folio', name: 'nro_folio' },
            { 
                title: "ACCIONES",  data: 'id', name: 'id', orderable: false, render: function(data, type) {
                    return `
                    <div class="d-flex flex-row">
                        <button onclick='viewCRUD(${data},"experiencia_crud","Expericia Laboral")' id="btnVer" class="btn btn-info mr-2">
                            <i class="fas fa-eye" id="editIcon"></i>
                        </button>
                        @hasanyrole('ADMIN|COORDINADOR')
                        <button onclick='deleteCRUD(${data},"experiencia_crud","experiencia_crud")' class='btn btn-danger btn-sm mr-2' title='Eliminar'>
                            <i class='fas fa-trash'></i>
                        </button>
                        <button onclick='editCRUD(${data},"experiencia_crud","Experiencia Laboral")' class='btn btn-info' title='Editar'>
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
