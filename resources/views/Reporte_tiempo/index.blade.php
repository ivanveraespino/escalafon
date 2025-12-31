@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">        
        <div class="col-md-12">
            <div class="card shadow ">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-munilc">REGISTRO PERSONAL POR TIEMPO </h6>
                </div>
                <div class="card-body">
                <div class="row">
                        <div class="form-group col-md-3">
                            <label for="columnSelect">TIPO</label>
                            <select id="columnSelect" class="form-control">
                                <option value="0">GENERAL</option>
                                <option value="7">25 AÑOS DE SERVICIO</option>
                                <option value="8">30 AÑOS DE SERVICIO</option>
                                <option value="9">CESE 70 AÑOS</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="regimen">CONDICION LABORAL</label>
                            <select id="regimen" class="form-control">
                                <option value=""></option>
                                <option value="D.L. 276 NOMBRADO">D.L. 276 NOMBRADO</option>
                                <option value="D.L. 276 PERMANENTE">D.L. 276 PERMANENTE</option>
                                <option value="D.L. 728 NOMBRADO">D.L. 728 NOMBRADO</option>
                                <option value="D.L. 728 PERMANENTE">D.L. 728 PERMANENTE</option>
                                <option value="D.L. 1057 INDETERMINADO">D.L. 1057 INDETERMINADO</option>
                                <option value="D.L. 1057 TRANSITORIO">D.L. 1057 TRANSITORIO</option>
                                <option value="CONFIANZA">CONFIANZA</option>
                                <option value="INVERSIONES">INVERSIONES</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="regimen">AÑO</label>
                            <input type="number" class="form-control" id="anio" name="anio" >
                        </div>                 
                    </div>

                    <table class="table table-bordered table-striped"  style="width:100%" id="campo-table">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@push('scripts')
<script src="{{ asset('js/blob_handler.js') }}"></script>

<script>
var regimen;
var selectedText;
let searchYear = '';
var table;
let parts = [];

if (selectedText) {
    parts.push(selectedText);
}

if (regimen) {
    parts.push(regimen);
}

if (searchYear) {
    parts.push(searchYear);
}

$(document).ready(function() {


table =  $('#campo-table').DataTable({
    paging: true,
    processing: true,
    serverSide: true,
    ajax: 'getRepoTiempo',
    responsive: true, 
    columns: [
        { title: 'REGIMEN' , data: 'regimen'},
        { title: 'DNI' ,data: 'nro_documento_id'},
        { title: 'APELLIDO PATERNO' ,data: 'Apaterno'},
        { title: 'APELLIDO MATERNO' ,data: 'Amaterno'},
        { title: 'NOMBRES' ,data: 'Nombres'},
        { title: 'FECHA NACIMIENTO' ,data: 'FechaNacimiento',
        render: function(data, type, row) {
        return formatDate(data);
    } }, // Inicialmente oculta
        { title: 'INICIO VINCULO' ,data: 'inicio_vinculo',
        render: function(data, type, row) {
        return formatDate(data);
    }}, // Inicialmente oculta
        { title: '25 AÑOS DE SERVICIO' ,data: 'fecha_25_vinculo',
        render: function(data, type, row) {
        return formatDate(data);
    } }, // Inicialmente oculta
        { title: '30 AÑOS DE SERVICIO' ,data: 'fecha_30_vinculo',
        render: function(data, type, row) {
        return formatDate(data);
    } }, // Inicialmente oculta
        { title: 'FECHA DE CESE' ,data: 'fecha_70',
        render: function(data, type, row) {
        return formatDate(data);
    } } // Inicialmente oculta
    ],
    language: {
        search: "Buscar: ",
        zeroRecords: "No se encontraron resultados",
        info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
        lengthMenu: "Mostrar _MENU_ entradas por página",
        emptyTable: "No hay datos disponibles en la tabla", 
        infoEmpty: "Mostrando 0 de 0 of 0 entradas",
        loadingRecords: "Cargando...",
    },
    layout: {
        top2Start: {
            buttons: [
            {
                extend: 'pdfHtml5',
                className: 'btn btn-danger',
                text: '<i class="fas fa-file-pdf"></i>',
                titleAttr: 'PDF',
                extension: ".pdf",
                filename: function() {
                    return 'REPORTE ' + (selectedText ? '- ' + selectedText : '')  + (regimen ? '- ' + regimen : '') + (searchYear ? '- ' + searchYear : '') + ' - MPLC';
                },
                title: "",
                orientation: 'landscape',
                customize: function (doc) {
                    // Aumentar el tamaño de la letra del título
                    doc.content.splice(0, 0, {
                        text: [
                            { text: 'REPORTE ' + (selectedText ? '- ' + selectedText : '')  + (regimen ? '- ' + regimen : '') + (searchYear ? '- ' + searchYear : '') +' - MPLC', italics: false, bold: true, fontSize: 15 }
                        ],
                        margin: [0, 0, 0, 12],
                        alignment: 'center'
                    });

                    // Ajustar estilo de cabecera de tabla
                    doc.styles.tableHeader = {
                        color: 'black',
                        alignment: 'center',
                        fontSize: 10.5,
                        bold: true
                    };
                    
                    doc.styles.tableBodyEven = { alignment: 'center' };
                    doc.styles.tableBodyOdd = { alignment: 'center' };
                    // Añadir bordes a la tabla
                    var objLayout = {};
                    objLayout['hLineWidth'] = function(i) { return .8; };
                    objLayout['vLineWidth'] = function(i) { return .5; };
                    objLayout['hLineColor'] = function(i) { return '#aaa'; };
                    objLayout['vLineColor'] = function(i) { return '#aaa'; };
                    objLayout['paddingLeft'] = function(i) { return 8; };
                    objLayout['paddingRight'] = function(i) { return 8; };
                    
                    // Establecer la configuración del layout de la tabla
                    doc.content[1].layout = objLayout;

                    // Asegurar que la tabla ocupe todo el espacio disponible
                    doc.content[1].table.widths = '*'; // Ajustar ancho de la tabla

                    // Opcional: colores alternativos para filas
                    var rowCount = doc.content[1].table.body.length;
                    for (var i = 1; i < rowCount; i++) {
                        if (i % 2 === 0) {
                            var row = doc.content[1].table.body[i];
                            row.forEach(function(cell) {
                                cell.fillColor = '#f3f3f3';
                            });
                        }
                    }
                },
                
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i>',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                exportOptions: {
                    columns: ':visible'
                },
                title: function() {
                    return 'REPORTE ' + (selectedText ? '- ' + selectedText : '')  + (regimen ? '- ' + regimen : '') + (searchYear ? '- ' + searchYear : '') +' - MPLC';    
                },
                createEmptyCells: true,
                customize: customizeExcel
            }

        ],
        }
    },

    }).on('click', 'button[data-action="pdf"]', function() {
    // Ocultar columnas no deseadas antes de exportar
    $('#campo-table').columns([1, 3, 5, 6, 7, 8]).visible(false, false);
    // Reiniciar la visibilidad después de la exportación
    $('#campo-table').one('draw', function() {
        $('#campo-table').columns([1, 3, 5, 6, 7, 8]).visible(true, false);
    });
});

function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}



$('#anio').on('keyup', debounce(function () {
    var column = $('#columnSelect').val();
    if (column) {
        searchYear = $(this).val();
        table.column(column).search(searchYear).draw();
    }
    }, 300));

    function filterData() {
            var column = $('#columnSelect').val();
            var date = $('#dateFilter').val();
            var day = $('#dayFilter').val();
            var month = $('#monthFilter').val();
            var searchDate = '';   
            regimen = $('#regimen').val();
            selectedText = $('#columnSelect option:selected').text();

            if (column) {
            table.column(0).visible(column === '7' || column === '8'  || column === '9' || column === '0' );
            table.column(1).visible(column === '7' || column === '8'  || column === '9' || column === '0' );
            table.column(2).visible(column === '7' || column === '8'  || column === '9' || column === '0' );
            table.column(3).visible(column === '7' || column === '8'  || column === '9' || column === '0' );
            table.column(4).visible(column === '7' || column === '8'  || column === '9' || column === '0' );

            table.column(6).visible(column === '7' || column === '8'|| column === '0' );
            table.column(7).visible(column === '7'|| column === '0');
            table.column(8).visible(column === '8'|| column === '0');
            table.column(9).visible(column === '9'|| column === '0');
            table.column(5).visible(column === '9'|| column === '0');
            table.column(0).search(regimen).draw();

        }

    }

    $('#columnSelect, #regimen').on('change', filterData);


});

</script>


@endpush