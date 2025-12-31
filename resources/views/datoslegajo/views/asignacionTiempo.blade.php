


<x-layout-dato-legajo 
    title="Asignación de Tiempo"
    :columns="['TRABJADOR','DESCRIPCION','TIPO DOC','NRO DOC','FECHA INCIO','FECHA FIN','FECHA DOC','ARCHIVO','NRO FOLIO', 'Acciones']"
>
    @include('datoslegajo.forms.formAsignacionTiempo')
</x-layout-dato-legajo>

@include('datoslegajo.modals.modalAsignacionTiempo')



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
        onClickSaveAdd("tiempo_servicio_crud","tiempo_servicio_crud")
    });
    
    document.getElementById('formEdit').addEventListener('submit', function(e) {
        e.preventDefault();
        onClickSaveEdit("tiempo_servicio_crud", "tiempo_servicio_crud");
    });


    var dataTable = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,  
        scrollX: true,   
        ajax: {
            url: '/tiempo_servicio_crud',
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
            {title: 'DESCRIPCION', data: 'descripcion', name: 'descripcion' },
            {title: 'TIPO DOCUMENTO', data: 'tdoc', name: 'tdoc' },
            {title: 'NRO DOCUMENTO', data: 'nrodoc', name: 'nrodoc' },
            {title: 'FECHA INICIO', data: 'fecha_ini', name: 'fecha_ini', render: function(data, type, row) {
                return formatDate(data);
            }},
            {title: 'FECHA FIN', data: 'fecha_fin', name: 'fecha_fin', render: function(data, type, row) {
                return formatDate(data);
            }},
            {title: 'FECHA DOCUMENTO', data: 'fecha_doc', name: 'fecha_doc', render: function(data, type, row) {
                return formatDate(data);
            }},
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
            {title: 'NRO FOLIO', data: 'nro_folio', name: 'nro_folio' },
            
            { 
                title: "ACCIONES",  data: 'id', name: 'id', orderable: false, render: function(data, type) {
                    return `
                    <div class="d-flex flex-row">
                        <button onclick='viewCRUD(${data},"tiempo_servicio_crud","Asignacion de Tiempo")' id="btnVer" class="btn btn-info mr-2">
                            <i class="fas fa-eye" id="editIcon"></i>
                        </button>
                        @hasanyrole('ADMIN|COORDINADOR')
                        <button onclick='deleteCRUD(${data},"tiempo_servicio_crud","tiempo_servicio_crud")' class='btn btn-danger btn-sm mr-2' title='Eliminar'>
                            <i class='fas fa-trash'></i>
                        </button>
                        <button onclick='editCRUD(${data},"tiempo_servicio_crud","Asignacion de Tiempo")' class='btn btn-info' title='Editar'>
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
