
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
    title="Compensaciones"
    :columns="['TRABJADOR','TIPO COMPENSACION','TIPO DOCUMENTO','NRO DOCUMENTO','FECHA DOCUMENTO','NRO FOLIO','TELEFONO', 'Acciones']"
>
    @include('datoslegajo.forms.formCompensaciones')
</x-layout-dato-legajo>

@include('datoslegajo.modals.modalCompensaciones')


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
        onClickSaveAdd("conpensaciones_crud","conpensaciones_crud")
    });
    
    document.getElementById('formEdit').addEventListener('submit', function(e) {
        e.preventDefault();
        onClickSaveEdit("conpensaciones_crud", "conpensaciones_crud");
    });

    var dataTable = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,  
        scrollX: true,   
        ajax: {
            url: '/conpensaciones_crud',
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
            
            {title: 'TIPO COMPENSACION', data: 'tipo_compensacion', name: 'tipo_compensacion' },
            {title: 'TIPO DOCUMENTO', data: 'tdoc', name: 'tdoc' },
            {title: 'NRO DOCUMENTO', data: 'nrodoc', name: 'nrodoc' },
            {title: 'FECHA DOCUMENTO', data: 'fecha_documento', name: 'fecha_documento', render: function(data, type, row) {
                return formatDate(data);
            }},
            {title: 'NRO FOLIO', data: 'nro_folio', name: 'nro_folio' },
            {
                title: 'DOCUMENTO',
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
                        <button onclick='viewCRUD(${data},"conpensaciones_crud","Compensación")' id="btnVer" class="btn btn-info mr-2">
                            <i class="fas fa-eye" id="editIcon"></i>
                        </button>
                        @hasanyrole('ADMIN|COORDINADOR')
                        <button onclick='deleteCRUD(${data},"conpensaciones_crud","conpensaciones_crud")' class='btn btn-danger btn-sm mr-2' title='Eliminar'>
                            <i class='fas fa-trash'></i>
                        </button>
                        <button onclick='editCRUD(${data},"conpensaciones_crud","Compensación")' class='btn btn-info' title='Editar'>
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
