@extends('layouts.app')


@section('content')
<style>
    /* Estilos generales */
    .container {
        max-width: 1000px;
        margin: auto;
    }

    /* Estilos personalizados para los botones de selección de reporte */
    .btn-outline-secondary {
        color: #28a745; /* Verde como color de texto */
        border-color: #28a745; /* Verde como color de borde */
        transition: background-color 0.3s, color 0.3s; /* Transición para hover */
    }

    /* Estilo al hacer hover (verde opaco) */
    .btn-outline-secondary:hover {
        background-color: rgba(40, 167, 69, 0.1); /* Verde claro opaco */
        color: #28a745; /* Mantener el color de texto verde */
    }

    /* Estilo para el botón activo */
    .btn-outline-secondary.active,
    .btn-outline-secondary:active {
        background-color: #28a745; /* Verde sólido de fondo */
        color: #fff; /* Texto en blanco */
        border-color: #28a745; /* Borde verde */
    }

    /* Estilo para centrar los botones */
    .btn-group-toggle {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    /* Espaciado entre botones */
    .btn-group-toggle label {
        margin: 0 5px;
    }

    /* Estilo del botón Generar Reporte */
    .generate-report-btn {
        display: block;
        width: 100%;
        max-width: 200px;
        margin-left: auto;
        margin-top: 20px;
    }

    /*CHOSEN*/

/* Contenedor de Chosen */
.chosen-container {
    font-size: 1rem; /* Tamaño de fuente similar a Bootstrap */
    border: 1px solid #ddd; /* Borde gris claro */
    border-radius: 0.25rem; /* Bordes redondeados */
    height: auto;
}

/* Estilo para el campo seleccionado */
.chosen-container .chosen-single {
    display: flex; /* Utiliza Flexbox para el alineamiento */
    align-items: center; /* Centra el texto verticalmente */
    justify-content: space-between; /* Alinea los elementos al principio y al final */
    background-color: #fff; /* Fondo blanco */
    color: #495057; /* Color de texto neutro */
    border: 1px solid #ddd; /* Borde gris claro */
    height: calc(2.25rem + 2px); /* Altura similar a un select estándar de Bootstrap */
    padding-left: 0.75rem; /* Añade espacio a la izquierda */
    padding-right: 0.75rem; /* Añade espacio a la derecha */
}

/* Sin hover en el campo seleccionado */
.chosen-container .chosen-single:hover {
    border-color: #ddd; /* Sin cambio de color en hover */
}

/* Contenedor de las opciones del dropdown */
.chosen-container .chosen-drop {
    border: 1px solid #ddd; /* Borde gris claro */
    border-radius: 0.25rem; /* Bordes redondeados */
    background-color: #fff; /* Fondo blanco */
}

/* Opciones dentro del dropdown */
.chosen-container .chosen-results li {
    padding: 0.375rem 0.75rem; /* Espaciado de las opciones */
    color: #495057; /* Color neutro para las opciones */
}

/* Opciones seleccionadas dentro del dropdown */
.chosen-container .chosen-results li.highlighted {
    background-color: #f8f9fa; /* Fondo gris muy claro para las opciones seleccionadas */
    color: #212529; /* Color oscuro para el texto */
}

/* Placeholder (texto por defecto) */
.chosen-container .chosen-single.chosen-default {
    color: #6c757d; /* Color gris para el texto del placeholder */
}

</style>

<div class="container">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary text-center">Generar Reportes</h6>
        </div>
        <div class="card-body">
            <!-- Botones de selección de tipo de reporte -->
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-outline-success hover-effect" onclick="mostrarFiltros('personal')" id="personalBtn">
                    Personal
                </label>
                <label class="btn btn-outline-success hover-effect" onclick="mostrarFiltros('vinculo_laboral')" id="vinculo_laboralBtn">
                    Vínculo Laboral
                </label>
                <label class="btn btn-outline-success hover-effect" onclick="mostrarFiltros('movimientos')" id="movimientosBtn">
                    Movimientos Personal
                </label>  
                <label class="btn btn-outline-success hover-effect" onclick="mostrarFiltros('cronograma_vac')" id="cronograma_vacBtn">
                    Cronograma Vacaciones
                </label>
                <label class="btn btn-outline-success hover-effect" onclick="mostrarFiltros('didasAdeudados')" id="didasAdeudadosBtn">
                    Dias Adeudados
                </label>
                <label class="btn btn-outline-success hover-effect" onclick="mostrarFiltros('vacaciones')" id="vacacionesBtn">
                    Vacaciones
                </label>
                <label class="btn btn-outline-success hover-effect" onclick="mostrarFiltros('licencias')" id="licenciasBtn">
                    Licencias
                </label>         
                <label class="btn btn-outline-success hover-effect" onclick="mostrarFiltros('permisos')" id="permisosBtn">
                    Permisos
                </label>  
            </div>
            <!-- Contenedor donde se carga el formulario -->
            <div id="filtrosContainer"></div>
        </div>
    </div>
</div>
@stop

@push('scripts')
<script>
    $(document).ready(function() {
        mostrarFiltros('personal'); // Muestra los filtros del reporte Personal por defecto
    });

    // Mapear los formularios
// Selectores reutilizables
const selects = {
    personal: `
        <div class="form-group">
            <label for="regimen">Personal</label>
            <select id="personal" class="form-control dynamic" name="personal" data-route="{{ route('getPersonal_list') }}">
                </select>
        </div>
    `,
    regimen: `
        <div class="form-group">
            <label for="regimen">Régimen</label>
            <select id="regimen" class="form-control dynamic" name="regimen" data-route="{{ route('getRegimen_list') }}">
                </select>
        </div>
    `,
    condicion: `
        <div class="form-group">
            <label for="condicion">Condición Laboral</label>
            <select id="condicion" class="form-control dynamic" name="condicion" data-route="{{ route('getCondicion_list') }}">
                </select>
        </div>
    `,
    cargo: `
        <div class="form-group">
            <label for="cargo">Cargo</label>
            <select id="cargo" class="form-control dynamic" name="cargo" data-route="{{ route('getCargo_list') }}">
                </select>
        </div>
    `,
    oficina: `
        <div class="form-group">
            <label for="oficina">Oficina</label>
            <select id="oficina" class="form-control dynamic" name="oficina" data-route="{{ route('getAreas_list') }}">
                </select>
        </div>
    `,
    mes: `
        <div class="form-group">
            <label for="cargo">Mes</label>
            <select id="mes" class="form-control dynamic" name="mes" data-route="{{ route('getMeses') }}">
                </select>
        </div>
    `,
    periodo: `
        <div class="form-group">
            <label for="p">Periodo</label>
            <input type="number" class="form-control" id="periodo" name="periodo">
        </div>
    `,
    movimiento: `
        <div class="form-group">
            <label for="condicion">Movimientos</label>
            <select id="movimiento" class="form-control dynamic" name="movimiento" data-route="{{ route('getMovimiento_list') }}">
                </select>
        </div>
    `
};

// Campo de fecha reutilizable
const dateFields = (idPrefix, label) => `
    <div class="form-row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="${idPrefix}_desde">${label} (Desde)</label>
                <input type="date" class="form-control" id="${idPrefix}_desde" name="${idPrefix}_desde">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="${idPrefix}_hasta">${label} (Hasta)</label>
                <input type="date" class="form-control" id="${idPrefix}_hasta" name="${idPrefix}_hasta">
            </div>
        </div>
    </div>
`;

// Función que genera el formulario y el botón de envío
const formWrapper = (actionRoute, tipoReporte, formContent) => `
    <form id="reportForm" action="${actionRoute}" method="POST">
        @csrf
        <input type="hidden" name="tipoReporte" value="${tipoReporte}">
        ${formContent}
        <button type="submit" class="btn btn-success generate-report-btn">Generar <i class="fas fa-file-excel"></i></button>
    </form>
`;

// Formularios específicos ensamblados con formWrapper
const formularios = {
    personal: formWrapper(
        "{{ route('generarReporte') }}",
        "personal",
        `
            ${selects.regimen}
            ${selects.condicion}
            ${selects.cargo}
            ${selects.oficina}
            ${dateFields("fecha_inicio", "Fecha de Ingreso")}
        `
    ),
    vinculo_laboral: formWrapper(
        "{{ route('generarReporte') }}",
        "vinculo_laboral",
        `
            ${selects.personal}
            ${selects.regimen}
            ${selects.condicion}
            ${selects.cargo}
            ${selects.oficina}
            ${dateFields("fecha_inicio", "Fecha de Ingreso")}
            ${dateFields("fecha_fin", "Fecha de Cese")}
        `
    ),
    movimientos: formWrapper(
        "{{ route('generarReporte') }}",
        "movimientos",
        `
            ${selects.personal}
            ${selects.regimen}
            ${selects.condicion}
            ${selects.movimiento}
            ${selects.cargo}
            ${selects.oficina}
            ${dateFields("Fechas", "Fechas")}
        `
    ),
    cronograma_vac: formWrapper(
        "{{ route('generarReporte') }}",
        "cronograma_vac",
        `
            ${selects.personal}
            ${selects.regimen}
            ${selects.condicion}
            ${selects.mes}
            ${selects.periodo}
            ${dateFields("Fechas", "Fechas")}
        `
    ),
    didasAdeudados: formWrapper(
        "{{ route('generarReporte') }}",
        "didasAdeudados",
        `
            ${selects.personal}
            ${selects.regimen}
            ${selects.condicion}
            ${selects.periodo}
        `
    ),
    vacaciones: formWrapper(
        "{{ route('generarReporte') }}",
        "vacaciones",
        `
            ${selects.personal}
            ${selects.regimen}
            ${selects.condicion}
            ${selects.mes}
            ${selects.periodo}
            ${dateFields("Fechas", "Fechas")}
        `
    ),
    licencias: formWrapper(
        "{{ route('generarReporte') }}",
        "licencias",
        `
            ${selects.personal}
            ${selects.regimen}
            ${selects.condicion}
            ${selects.mes}
            ${selects.periodo}
            ${dateFields("Fechas", "Fechas")}
        `
    ),
    permisos: formWrapper(
        "{{ route('generarReporte') }}",
        "permisos",
        `
            ${selects.personal}
            ${selects.regimen}
            ${selects.condicion}
            ${selects.mes}
            ${selects.periodo}
            ${dateFields("Fechas", "Fechas")}
        `
    )
};

// Variables globales para almacenar los datos cargados
let datosCargados = {

};
function mostrarFiltros(tipoReporte) {
    const filtrosContainer = $('#filtrosContainer');
    filtrosContainer.empty();

    // Limpiar la clase 'active' de todos los botones
    $('.btn-group label').removeClass('active');

    // Activar el botón seleccionado y cargar el formulario correspondiente
    if (formularios[tipoReporte]) {
        $(`#${tipoReporte}Btn`).addClass('active');
        filtrosContainer.append(formularios[tipoReporte]);
    } else {
        console.warn("Formulario no encontrado para el tipo de reporte:", tipoReporte);
    }

    // Inicializar selects dinámicos con Chosen
    inicializarSelectsDinamicos();
}

function inicializarSelectsDinamicos() {
    // Selecciona todos los selects dinámicos y configura Chosen
    $('select.dynamic').chosen({
        no_results_text: "No se encontraron resultados",
        placeholder_text_single: "Seleccione una opción...",
        search_contains: true,
        width: "100%"
    }).on('chosen:showing_dropdown', function() {
        let selectId = $(this).attr('id');
        let tipo = $(this).data('tipo');
        let route = $(this).data('route');
        if (!datosCargados[tipo]) {
            cargarSelectDinamico(`#${selectId}`, route, tipo);
        }
    });
}

function cargarSelectDinamico(selectId, route, tipo) {
    let select = $(selectId);
        // Realizar la solicitud AJAX solo la primera vez
        $.ajax({
            url: route,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                if (Array.isArray(data)) {
                    llenarOpciones(select, data); // Llenar las opciones
                } else {
                    console.error("La respuesta no es un array:", data);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'La respuesta del servidor no es válida.'
                    });
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al cargar los datos.'
                });
            }
        });
}

// Función para llenar el select con opciones y aplicar Chosen
function llenarOpciones(select, data) {
    select.empty(); // Limpiar las opciones actuales
    select.append('<option value="">Seleccione una opción...</option>'); // Añadir la opción predeterminada
    data.forEach(item => {
        let option = $('<option>').val(item.nombre).text(item.nombre);
        select.append(option);
    });

    // Actualizar Chosen
    select.trigger("chosen:updated");
}

$(document).ready(function() {
    // Mostrar los filtros por defecto (Personal)
    mostrarFiltros('personal');
});

</script>
@endpush






