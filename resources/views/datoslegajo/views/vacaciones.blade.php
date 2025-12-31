
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
    title="Vacaciones"
    :columns="['1','2','3','4','5','6','7','8','9','10','11','12','13']"
>
    @include('datoslegajo.forms.formVacaciones')
</x-layout-dato-legajo>

@include('datoslegajo.modals.modalVacaciones')

<script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>
<script>
    var idp = {!! json_encode($idp ?? null) !!};
    var dp = {!! json_encode($dp ?? null) !!};
    var validacion = { value: 'SI' };

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

    if (!idp){
        $("#formAdd :input").not("#dnipcv").prop("disabled", true);
    }
    initializeSelect2(['dnipcv'], select2Config);
    
    $(document).ready(function() {
        convertirAMayusculas();
        setupCronograma();
        setmonth();

    });

    $("#dnipcv").on("change", function () {
        const selectedValue = $(this).val();
        if (selectedValue) {
            reset_all_vlp();
        } else {
            // Deshabilitar los campos si no hay valor seleccionado
            $("#formAdd :input").not("#dnipcv").prop("disabled", true);
        }
    });

    //AL CERRAR MODAL
    $('#modalFormEdit').on('hidden.bs.modal', function () {
        VolverIds();
        reset_all_vlp();
        vpl_act_valor = ''; 
        adelantado = 0;
    });

    document.getElementById('formAdd').addEventListener('submit', function(e) {
        e.preventDefault(); 
        onClickSaveAdd("vacaciones_crud","vacaciones_crud")
    });
    
    document.getElementById('formEdit').addEventListener('submit', function(e) {
        e.preventDefault();
        //OCULTAR TABLA DE VLP AL EDITAR
        $('.vlp-item').hide();
        onClickSaveEdit("vacaciones_crud", "vacaciones_crud");
    });

    function btn_editCRUD(data) {
        editCRUD(data, "vacaciones_crud", "Vacaciones");
        actualizarIds();
    }

    var dataTable = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,  
        scrollX: true,   
        ajax: {
            url: '/vacaciones_crud',
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
            { title: 'DNI', data: 'nro_documento_id', name: 'nro_documento_id' },
            { title: 'PERSONAL', data: 'personal', name: 'personal' },
            { title: 'REGIMEN', data: 'regimen_condicion', name: 'regimen_condicion', orderable: false},
            { title: 'PERIODO', data: 'periodo', name: 'periodo' },
            { title: 'MES', data: 'mes', name: 'mes' },
            { title: 'FECHA INICIO',
                data: 'fecha_ini',
                name: 'fecha_ini',
                render: function(data, type, row) {
                return formatDate(data);
            }
            },
            { title: 'FECHA FIN',
                data: 'fecha_fin',
                name: 'fecha_fin',
                render: function(data, type, row) {
                return formatDate(data);
            }
            },
            { title: 'DIAS',
                data: 'dias',
                name: 'dias'
            },
            { title: 'DOCUMENTO', data: 'nrodoc', name: 'nrodoc' },
            {
                title: 'ARCH',
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
            { title: 'SUSPEN', data: 'suspencion', name: 'suspencion' },
            { title: 'OBS.', data: 'observaciones', name: 'observaciones' },
            { 
                title: "ACCIONES",  data: 'id', name: 'id', orderable: false, render: function(data, type) {
                    return `
                    <div class="d-flex flex-row">
                        <button onclick='viewCRUD(${data},"vacaciones_crud","Vacaciones")' id="btnVer" class="btn btn-info mr-2">
                            <i class="fas fa-eye" id="editIcon"></i>
                        </button>
                        @hasanyrole('ADMIN|COORDINADOR')
                        <button onclick='deleteCRUD(${data},"vacaciones_crud","vacaciones_crud")' class='btn btn-danger btn-sm mr-2' title='Eliminar'>
                            <i class='fas fa-trash'></i>
                        </button>
                        <button onclick='btn_editCRUD(${data})' class='btn btn-info' title='Editar'>
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
    //ASIGNAR VARIABLES
    var fechaIniInput2 = document.getElementById('Efecha_ini');
    var fechaFinInput2 = document.getElementById('Efecha_fin');
    var mesSelect2 = document.getElementById('Emes');
    var mesSelect2d = document.getElementById('Edmes');
    var diasInput2 = document.getElementById('Edias');
    var diasRestntesInput2 = document.getElementById('Ediasr');
    var periodo2 = document.getElementById('Eperiodo');
    var CR2 = document.getElementById('CR2');
    var VC2 = document.getElementById('VC2');
    var LC2 = document.getElementById('LC2');
    var PC2 = document.getElementById('PC2');
    var btnfam = document.getElementById('btnfam');
    var diasWarning = document.getElementById('diasWarning');
    var cronogramaWarning = document.getElementById('cronogramaWarning');
    var elementos = [
        document.getElementById('lv_vlp'),
        document.getElementById('ll_vlp'),
        document.getElementById('lp_vlp')
    ];
</script>