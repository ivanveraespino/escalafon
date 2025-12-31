
<style>
    .dropdown-menu {
        max-height: 200px;
        overflow-y: auto;
        width: 100%; 
    }
    .dropdown-item {
        cursor: pointer;
    }
    input.largerCheckbox {
            transform: scale(2);
        }
    #cronogramaWarning {
    position: absolute;
    width: 100%;
    top: 100%; /* Posicionarlo justo debajo del div */
    left: 0;
    z-index: 10; /* Asegurarse de que aparezca encima de otros elementos */
    }
    #diasWarning {
        position: absolute;
        width: 100%;
        top: 100%; /* Posicionarlo justo debajo del div */
        left: 0;
        z-index: 10; /* Asegurarse de que aparezca encima de otros elementos */
    }

</style>
<div class="container container-fluid p-0">
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-munilc">Cronograma de Vacaciones</h6>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <button class="btn btn-primary mr-2" data-bs-toggle="modal" onclick="ModalProgram()" >
                        <i class='fas fa-plus-circle'></i> Ingresar Cronograma
                    </button>
                    <button  id="btnReprogramar" onclick="ModalRProgram()" class="btn btn-info" disabled>Reprogramar</button>
                    <button class="btn btn-success ml-2" data-bs-toggle="modal" data-target="#uploadModal">
                        <i class='fas fa-upload'></i>
                    </button> 
                </div>

                <div class="form-inline">
                    <div class="form-group mb-2">
                        <input type="number" class="form-control form-control-sm ml-3" id="periodo_select" placeholder="Periodo">
                    </div>
                    <div class="form-group mb-2">
                        <select id="mes_select" class="form-control form-control-sm ml-3">
                            <option value="">POR MES</option>
                            <option value="ENERO">ENERO</option>
                            <option value="FEBRERO">FEBRERO</option>
                            <option value="MARZO">MARZO</option>
                            <option value="ABRIL">ABRIL</option>
                            <option value="MAYO">MAYO</option>
                            <option value="JUNIO">JUNIO</option>
                            <option value="JULIO">JULIO</option>
                            <option value="AGOSTO">AGOSTO</option>
                            <option value="SEPTIEMBRE">SEPTIEMBRE</option>
                            <option value="OCTUBRE">OCTUBRE</option>
                            <option value="NOVIEMBRE">NOVIEMBRE</option>
                            <option value="DICIEMBRE">DICIEMBRE</option>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <select id="regimen_select" class="form-control form-control-sm ml-3">
                            <option value="">POR REGIMEN</option>
                            @foreach($reg as $regbd)
                                <option value="{{ $regbd->nombre ?? '' }}">{{ $regbd->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>
            
            <div class="tab-content" id="myTabContent">

                <div class="tab-pane fade show active" id="cronograma" role="tabpanel" aria-labelledby="cronograma-tab">

                    <table id="cronograma-table" class="table table-striped table-bordered" style="width:100%">
                    </table>
                </div>      
                <div class="tab-pane fade" id="vacaciones" role="tabpanel" aria-labelledby="vacaciones-tab">
                    <table id="vacaciones-table" class="table table-striped table-bordered" style="width:100%">
                    </table>
                </div>
                <div class="tab-pane fade" id="licencias" role="tabpanel" aria-labelledby="licencias-tab">
                    <table id="licencias-table" class="table table-striped table-bordered" style="width:100%">

                    </table>
                </div>
                <div class="tab-pane fade" id="permisos" role="tabpanel" aria-labelledby="permisos-tab">
                    <table id="permisos-table" class="table table-striped table-bordered" style="width:100%">

                    </table>
                </div>
                <div class="tab-pane fade" id="reincorporacion" role="tabpanel" aria-labelledby="reincorporacion-tab">
                    <table id="reincorporacion-table" class="table table-striped table-bordered" style="width:100%">

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal para subir el archivo -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Subir Cronograma varios Personales</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulario para subir archivo -->
                <form id="uploadForm" enctype="multipart/form-data">
                    @csrf
                    <label for="excelFile">Ingrese documento</label>
                    <div class="row">

                        <div class="col-3 mb-3">
                            <label for="regimen">Tipo Doc</label>
                            <select name="idtd" class="form-control" required>
                                <option hidden selected></option>
                                @foreach($tdoc as $t)
                                    @if($t->categoria && in_array("CRO", json_decode($t->categoria, true))) 
                                        <option value="{{ $t->id }}" data-categoria="{{ $t->categoria }}">{{ $t->nombre }}</option>
                                    @endif
                                @endforeach
                            </select> 
                        </div>

                        <div class="col-7 mb-3">
                            <label for="entidad">N° Documento</label>
                            <input type="text" class="form-control"name="nrodoc" placeholder="Ejm. 232-2023" required>
                        </div>   
                        <div class="form-group col-md-2">
                            <label for="archivo">Archivo</label>
                            <input type="file" accept="application/pdf" class="form-control archivo" name="archivo" id="archivo_multiple" required>
                        </div>
                        <div class="form-group col-md-2 d-none">
                            <input type="number" class="form-control nro_folios" name="nro_folios">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="excelFile">Seleccione el archivo</label>
                            <div class="input-group">

                                <input type="file" class="form-control" id="excelFile" name="excelFile" accept=".xlsx, .xls" required>
                            </div>
                        </div>
                        <div class="col-6 mb-3 mt-3">
                            <button type="submit" class="btn btn-primary mt-3 w-100" ><i class="fas fa-file-upload"></i> Subir Archivo</button>
                        </div>
                    </div>
                </form>
                <hr>
                <!-- Enlace para descargar el formato de Excel -->
                <a href="{{ asset('../storage/app/public/archivos/MODELO_CARGA_CRONOGRAMAS.xlsx') }}" class="btn btn-link">Descargar Formato de Excel</a>
            </div>
        </div>
    </div>
</div>




<!-- Modal -->
<div class="modal fade" id="CronogramaModal" tabindex="-1" role="dialog" aria-labelledby="CronogramaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-ml">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="CronogramaModalLabel">Programar Cronograma</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('VLP.formCronograma')
            </div>
        </div>
    </div>
</div>


@include('datoslegajo.modal_files')


<script>
//Filtro y reprograaciones
$('#periodo_select').change(function() {
    searchper = $(this).val();
    activeTable.ajax.url(activeTable.ajax.url()).load();
});

//VALIDAR TOTAL DIAS CRONOGRAMA
function validarDias() {
    let valorActual = parseInt($("#diasdperiodo").val(), 10) || 0; 
    var personal_id = $("#dnipcv").val(); // Obtener el valor del select
    var periodo = $("#Cperiodo").val(); // Obtener el valor del periodo
    // Validar que ambos campos tengan valores antes de continuar

    $.ajax({
        url: "buscarCronograma",
        type: "GET",
        data: { personal_id, periodo },
        success: function (response) {
            var diasActuales = $("#fdi").val(); // Obtener los días ingresados en el formulario (maneja NaN como 0)
            //let diasUs = parseInt(response.dias_usados_cv) || 0;
            let diasUs = parseInt(response.dias_usados) || 0;
            let diascv = parseInt(response.dias_cv) || 0;
            if (cronograma_act_valor !== '') {
                //diasUs = diasUs - parseInt(cronograma_act_valor, 10);
                diascv = diascv - parseInt(cronograma_act_valor, 10);
            }
            
            let nuevoValor = diasUs - valorActual;
            // Establecer el nuevo valor en el input
            $("#diasdperiodo").val(diasUs);
            $("#CR").val(diascv);
            $("#VC").val(response.dias_v);
            $("#LC").val(response.dias_l);
            $("#PC").val(response.dias_p);
            let sumarDias = false;

            ['lv', 'll', 'lp'].forEach((id, index) => {
                var diasAd = parseInt(response[`dias_ad_${['v', 'l', 'p'][index]}`], 10);
                if (diasAd > 0) {
                    $(`#${id}`).css('color', 'red');
                    document.getElementById('mensaje-adelantado').style.color = 'red';
                    sumarDias = true;
                } else {
                    $(`#${id}`).css('color', '');
                    document.getElementById('mensaje-adelantado').style.display = 'none';
                }
            });

            if (sumarDias) {
                document.getElementById('mensaje-adelantado').style.display = 'block';
                diascv += parseInt(response.s_dias_adelantado, 10);
            }
            // Calcular la suma total de días
            var sumaTotal = parseInt(diascv, 10) + parseInt(diasActuales, 10);
            if (sumaTotal > 30) {
                // Mostrar el mensaje de error y deshabilitar el botón
                $("#error-message").show().text("No puedes programar más de 30 días.");
                $("#btnrepo").prop("disabled", true);
            } else {
                // Ocultar el mensaje de error y habilitar el botón
                $("#error-message").hide();
                $("#btnrepo").prop("disabled", false);
            }
        },
        error: function () {
            // En caso de error en la petición
            $("#error-message").show().text("Error al obtener los días del cronograma.");
            $("#btnrepo").prop("disabled", true);
        },
    });
}


//DECLARAR ANTES QUE LAS TABLAS
function filterByRepo(id) {

    if (repoValue === id) {
        repoValue = '';
        activeTable.order(defaultOrder).draw(); 
        activeTable.columns(6).search(selectedMonth, true, false).draw();
        usingDefaultOrder = true;
    } else {
        repoValue = id;
        activeTable.order(alternateOrder).draw(); 
        activeTable.columns(6).search('', true, false).draw();
        usingDefaultOrder = false;
    }
}

//SUBIDA CRONOGRAMA
function submitForm(event) {
    event.preventDefault(); 
    
    var fileInput = document.getElementById('archivo_multiple');
    var file = fileInput.files[0];
    var formData = new FormData($('#uploadForm')[0]);
    console.log(file);

    if (file.size > 5242880) { 
        Swal.fire({
            icon: 'error',
            title: 'Archivo demasiado grande',
            text: 'El tamaño máximo permitido es de 5MB.'
        });
        return; // Detener la ejecución si el archivo es demasiado grande
    }else{
        $.ajax({
            url: 'importExcelCron', 
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                Swal.fire({
                    title: 'Subiendo...',
                    text: 'Por favor, espera',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: response.message,
                });
                activeTable.ajax.url(activeTable.ajax.url()).load();
                $('#uploadModal').modal('hide');
            },
            error: function(response) {
                var errorMessage = 'Hubo un problema al subir el archivo. Por favor, intenta nuevamente.';
                if (response.responseJSON && response.responseJSON.errors) {
                    errorMessage = response.responseJSON.message + '<br>' + response.responseJSON.errors.join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: errorMessage,
                });
            }
        });

    }

}


$('#uploadForm').on('submit', submitForm);
    var activeTable;
    var tables = {};
    var config;
    var selectedMonth;
    var titulorep;
    var searchper;
    var year;
    var regimenper;



/*
    $('#toggleDateRange').on('change', function() {
        if ($(this).is(':checked')) {
            $('#mes2').show();
            $('#fi2').show();
            $('#ff2').show();
            $('#di2').show();
            $('#diasColumn').removeClass('col-md-12').addClass('col-md-6');
            $('#fechamesColumn').removeClass('col-md-12').addClass('col-md-6');
            $('#fechaIniColumn').removeClass('col-md-12').addClass('col-md-6');
            $('#fechaFinColumn').removeClass('col-md-12').addClass('col-md-6');
        } else {
            $('#mes2').hide();
            $('#fi2').hide();
            $('#ff2').hide();
            $('#di2').hide();
            $('#diasColumn').removeClass('col-md-6').addClass('col-md-12');
            $('#fechamesColumn').removeClass('col-md-6').addClass('col-md-12');
            $('#fechaIniColumn').removeClass('col-md-6').addClass('col-md-12');
            $('#fechaFinColumn').removeClass('col-md-6').addClass('col-md-12');
        }
    });*/
    $(document).on('change', '.select-row', function() {
        $('.select-row').not(this).prop('checked', false); // Asegurar que solo un checkbox esté seleccionado

        if ($(this).is(':checked')) {
            var idcv = $(this).data('idcv');
            var dni = $(this).data('dni');
            var dias = $(this).data('dias');
            var periodo = $(this).data('periodo');
            $('#Eidvo').val(idcv);
            //OBTENER VALOR CRONOGRAMA
            $('#Ediasrep').val(dias);
            $('#dnipcv').val(dni);  
            $('#Cperiodo').val(periodo);  
            console.log(periodo);
            $('#btnReprogramar').prop('disabled', false);
        } else {
            $('#Eidvo').val('');
            $('#dnipcv').val('');
            $('#Cperiodo').val('');  
            $('#btnReprogramar').prop('disabled', true);
        }
    });

    // Mostrar modal de reprogramar al hacer clic en el botón
    $('#btnReprogramar').on('click', function() {
        $('#CronogramaModal').modal('show');
    });
    $('#CronogramaModal').on('hidden.bs.modal', function () {
        $('#Eidvo').val('');
        $('.select-row').prop('checked', false); 

    });

//VALIDADOR

function validarFormulario(formularioId) {
    var formulario = document.getElementById(formularioId);
    var camposRequeridos = formulario.querySelectorAll('input[required], select[required], textarea[required]');
    let hayErrores = false;
   camposRequeridos.forEach(campo => {
        if (campo.value === "") {
            hayErrores = true;
        }
    });

    return !hayErrores;
}


function guardartcron(){
    if (guardando) {
        return; // Salir si ya hay una solicitud en proceso
    }
    if (validarFormulario('cronogramaForm')) {
        event.preventDefault();
        guardando = true; // Activar la bandera

        var $boton = $('#btnrepo'); // Cambia #btnGuardar al ID correcto de tu botón
        $boton.prop('disabled', true).html('Enviando...'); // Deshabilitar el botón y cambiar el texto

        var formtra = $('#cronogramaForm')[0]; 
        var formData = new FormData(formtra);    
        var fechasIniArray = [];
        var fechasFinArray = [];
        var mesfiarray = [];
        var diasarray = [];

        // Solo agregar valores visibles a los arrays
        $('input[name="fecha_ini[]"]').each(function() {
            if ($(this).is(':visible')) {
                var value = $(this).val();
                if (value.trim() !== "") {
                    fechasIniArray.push(value);
                }
            }
        });

        $('input[name="fecha_fin[]"]').each(function() {
            if ($(this).is(':visible')) {
                var value = $(this).val();
                if (value.trim() !== "") {
                    fechasFinArray.push(value);
                }
            }
        });

        $('select[name="mes2[]"]').each(function() {
            if ($(this).is(':visible')) {
                var value = $(this).val();
                if (value.trim() !== "") {
                    mesfiarray.push(value);
                }
            }
        });

        $('input[name="dias2[]"]').each(function() {
            if ($(this).is(':visible')) {
                var value = $(this).val();
                if (value.trim() !== "") {
                    diasarray.push(value);
                }
            }
        });
        // Agregar solo si hay valores visibles
        if (fechasIniArray.length > 0) {
            formData.append('fecha_ini', JSON.stringify(fechasIniArray));
        } else {
            formData.delete('fecha_ini[]');
        }

        if (fechasFinArray.length > 0) {
            formData.append('fecha_fin', JSON.stringify(fechasFinArray));
        } else {
            formData.delete('fecha_fin[]');
        }

        if (mesfiarray.length > 0) {
            formData.append('mes', JSON.stringify(mesfiarray));
        } else {
            formData.delete('mes[]');
        }
        if (diasarray.length > 0) {
            formData.append('dias', JSON.stringify(diasarray));
        } else {
            formData.delete('dias2[]');
        }
            $.ajax({
                url: 'cronograma_crud',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('#CronogramaModal').modal('hide');
                    activeTable.ajax.url(activeTable.ajax.url()).load();
                    var formtra = $('#cronogramaForm')[0]; 
                    formtra.reset();
                    $boton.prop('disabled', false).html('Guardar');
                    guardando = false;
                    $('#Eidvo').val('');
                    Swal.fire({
                        icon: 'success',
                        title: 'Campo agregado!',
                        timer: 1000,
                    });
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un problema al cargar los datos del trabajo.',
                        timer: 1000,
                    });
                    $boton.prop('disabled', false).html('Guardar');
                    guardando = false;
                }
            });
     }

};
//CALCULO DE TIEMPO



function getMonthNumber(monthName) {
    var index = monthNames.indexOf(monthName.toUpperCase()) + 1;
    return index < 10 ? '0' + index : index;
}

// Referencias a elementos relevantes
var periodInput = document.getElementById('Cperiodo');
var monthSelect1 = document.getElementById('Emes1');
var monthSelect2 = document.getElementById('Emes2');
var startDateInput1 = document.getElementById('fecha_ini');
var endDateInput1 = document.getElementById('fecha_fin');
var daysInput1 = document.getElementById('fdi');
var startDateInput2 = document.getElementById('fecha_ini2');
var endDateInput2 = document.getElementById('fecha_fin2');
var daysInput2 = document.getElementById('fdi2');
var errorMessage = document.getElementById('error-message');
var toggleDateRange = document.getElementById('toggleDateRange');

function calculateDays() {
    var startDate1 = new Date(startDateInput1.value);
    var endDate1 = new Date(endDateInput1.value);
    var startDate2 = new Date(startDateInput2.value);
    var endDate2 = new Date(endDateInput2.value);
    
    var differenceInDays1 = (endDate1 - startDate1) / (1000 * 3600 * 24) + 1;
    var differenceInDays2 = (endDate2 - startDate2) / (1000 * 3600 * 24) + 1;

    var totalDays = differenceInDays1 + differenceInDays2;

    if (totalDays > 30) {
        errorMessage.style.display = 'block';
        btnrepo.style.display = 'none';
        daysInput1.value = '';
        daysInput2.value = '';
    } else  if (differenceInDays1 > 30) {
        errorMessage.style.display = 'block';
        btnrepo.style.display = 'none';
        daysInput1.value = '';
        daysInput2.value = '';
    }else {
        errorMessage.style.display = 'none';
        btnrepo.style.display = 'block';
        daysInput1.value = differenceInDays1 > 0 ? differenceInDays1 : '';
        daysInput2.value = differenceInDays2 > 0 ? differenceInDays2 : '';
    }
}

function setDefaultDates(year, monthNumber) {
    //startDateInput1.value = `${year}-${monthNumber}-01`;
    //endDateInput1.value = `${year}-${monthNumber}-15`;
    //startDateInput2.value = `${year}-${monthNumber}-16`;
   // endDateInput2.value = `${year}-${monthNumber}-30`;
   // daysInput1.value = 15;
    //daysInput2.value = 15;
}

periodInput.addEventListener('input', () => {
    var year = parseInt(periodInput.value) + 1;
    setDefaultDates(year.toString(), '01');
});

monthSelect1.addEventListener('change', () => {
    var selectedMonth = monthSelect1.value;
    var year = parseInt(periodInput.value) + 1;
    var monthNumber = getMonthNumber(selectedMonth);

    if (monthNumber) {
        if (toggleDateRange.checked) {
            setDefaultDates(year, monthNumber);
        } else {
            var startDate = new Date(year, monthNumber - 1, 1); // El primer día del mes seleccionado
            var endDate = new Date(startDate);
            endDate.setDate(startDate.getDate() + 29); // Sumar 30 días (0-index)

            startDateInput1.value = `${startDate.getFullYear()}-${String(startDate.getMonth() + 1).padStart(2, '0')}-${String(startDate.getDate()).padStart(2, '0')}`;
            endDateInput1.value = `${endDate.getFullYear()}-${String(endDate.getMonth() + 1).padStart(2, '0')}-${String(endDate.getDate()).padStart(2, '0')}`;

            startDateInput2.value = '';
            endDateInput2.value = '';
            daysInput1.value = 30;
            daysInput2.value = '';
        }
    }

    calculateDays();
});

// Actualizar las fechas basadas en el segundo mes seleccionado
monthSelect2.addEventListener('change', () => {
    var selectedMonth = monthSelect2.value;
    var year = parseInt(periodInput.value) + 1;
    var monthNumber = getMonthNumber(selectedMonth);

    if (monthNumber) {
        var lastDay = new Date(year, monthNumber, 0).getDate();
        startDateInput2.value = `${year}-${monthNumber}-01`;
        endDateInput2.value = `${year}-${monthNumber}-${lastDay}`;
    }
    calculateDays();
});

// Eventos para cambiar las fechas de inicio y fin manualmente
[startDateInput1, endDateInput1, startDateInput2, endDateInput2].forEach(input => {
    input.addEventListener('change', calculateDays);
});

// Evento para el checkbox
toggleDateRange.addEventListener('change', () => {
    var year = parseInt(periodInput.value) + 1
    var monthNumber1 = getMonthNumber(monthSelect1.value);

    if (toggleDateRange.checked) {
        setDefaultDates(year, monthNumber1);

        document.getElementById('mes2').style.display = 'block';
        document.getElementById('fi2').style.display = 'block';
        document.getElementById('ff2').style.display = 'block';
        document.getElementById('di2').style.display = 'block';
    } else {
        if (monthNumber1) {
            var lastDay = new Date(year, monthNumber1, 0).getDate();
            startDateInput1.value = `${year}-${monthNumber1}-01`;
            endDateInput1.value = `${year}-${monthNumber1}-${lastDay}`;
            startDateInput2.value = '';
            endDateInput2.value = '';
            daysInput1.value = 30;
            daysInput2.value = '';
        }

        document.getElementById('mes2').style.display = 'none';
        document.getElementById('fi2').style.display = 'none';
        document.getElementById('ff2').style.display = 'none';
        document.getElementById('di2').style.display = 'none';
    }

    calculateDays();
});


// Actualizar las fechas basadas en el segundo mes seleccionado
monthSelect2.addEventListener('change', () => {
    var selectedMonth = monthSelect2.value;
    var year = parseInt(periodInput.value) + 1

    
    var monthNumber = getMonthNumber(selectedMonth);

    if (monthNumber) {
        var lastDay = new Date(year, monthNumber, 0).getDate();
        startDateInput2.value = `${year}-${monthNumber}-01`;
        endDateInput2.value = `${year}-${monthNumber}-${lastDay}`;
    }

    calculateDays();
});

// Eventos para cambiar las fechas de inicio y fin manualmente
[startDateInput1, endDateInput1, startDateInput2, endDateInput2].forEach(input => {
    input.addEventListener('change', calculateDays);
});

// Evento para el checkbox
toggleDateRange.addEventListener('change', () => {
    var year = periodInput.value;
    var monthNumber1 = getMonthNumber(monthSelect1.value);

    if (toggleDateRange.checked) {
        setDefaultDates(year, monthNumber1);

        document.getElementById('mes2').style.display = 'block';
        document.getElementById('fi2').style.display = 'block';
        document.getElementById('ff2').style.display = 'block';
        document.getElementById('di2').style.display = 'block';
    } else {
        if (monthNumber1) {
            var lastDay = new Date(year, monthNumber1, 0).getDate();
            startDateInput1.value = `${year}-${monthNumber1}-01`;
            endDateInput1.value = `${year}-${monthNumber1}-${lastDay}`;
            startDateInput2.value = '';
            endDateInput2.value = '';
            daysInput1.value = 30;
            daysInput2.value = '';
        }

        document.getElementById('mes2').style.display = 'none';
        document.getElementById('fi2').style.display = 'none';
        document.getElementById('ff2').style.display = 'none';
        document.getElementById('di2').style.display = 'none';
    }

    calculateDays();
});


toggleDateRange.addEventListener('change', () => {
    var year = parseInt(periodInput.value) + 1;
    var monthNumber1 = getMonthNumber(monthSelect1.value);
    if (toggleDateRange.checked) {
        setDefaultDates(year, monthNumber1);

        document.getElementById('mes2').style.display = 'block';
        document.getElementById('fi2').style.display = 'block';
        document.getElementById('ff2').style.display = 'block';
        document.getElementById('di2').style.display = 'block';
    } else {
        if (monthNumber1) {
            startDateInput1.value = `${year}-${monthNumber1}-01`;
            endDateInput1.value = `${year}-${monthNumber1}-${new Date(year, monthNumber1, 0).getDate()}`;
            startDateInput2.value = '';
            endDateInput2.value = '';
            daysInput1.value = 30;
            daysInput2.value = '';
        }

        document.getElementById('mes2').style.display = 'none';
        document.getElementById('fi2').style.display = 'none';
        document.getElementById('ff2').style.display = 'none';
        document.getElementById('di2').style.display = 'none';
    }

    calculateDays();
});


//INSERTAR MESES
function populateSelect(selectElement) {
    selectElement.innerHTML = ''; // Limpia el select
    createOption(selectElement, "Seleccione un mes válido", ""); // Opción inicial
    monthNames.forEach(month => createOption(selectElement, month, month));
}

function createOption(selectElement, text, value) {
    var option = new Option(text, value);
    selectElement.add(option);
}

// Poblar los selects
['Emes1', 'Emes2'].forEach(id => populateSelect(document.getElementById(id)));

//MODAL REPOCV
function ModalRProgram() {
    //VALOR CRONOGRAMA PARA RESTAR EN CASO DE EDITAR CRONO
    cronograma_act_valor = $('#Ediasrep').val();
    $('#dnipcv').removeAttr('required');
    $("#cronogramaForm :input").not("#dnipcv").prop("disabled", false);
    $("#Cperiodo").prop("readonly", true);
    $('#CronogramaModalLabel').html('Reprogramar');   
    $("#error-message").hide(); 
    $('#personacv').hide();
    $('#motivo').show();
    $('#btnrepo').attr('onclick', 'guardartcron()');    
    $('#CronogramaModal').modal('show');
    var dnipValue = $('#dnipcv').val();
    $('#dnipcv').val(dnipValue);

}

//MODAL GUARDARCV
function ModalProgram() {
    cronograma_act_valor = ''; 
    //$('#dnipcv, #id_pvlp').val(null).trigger('change');
    $("#cronogramaForm :input").not("#dnipcv").prop("disabled", true);
    $("#Cperiodo").prop("readonly", false);
    $('#CronogramaModalLabel').html('Ingresar Cronograma');    
    $('#personacv').show();
    $('#motivo').hide();
    $('#btnrepo').attr('onclick', 'guardartcron()');   
    $("#error-message").hide(); 
    $('#CronogramaModal').modal('show');
}
$('#CronogramaModal').on('hidden.bs.modal', function () {
    var formtra = $('#cronogramaForm')[0];
    formtra.reset();
    $('#fechamesColumn').removeClass('col-md-6').addClass('col-md-12');
    $('#fechaIniColumn').removeClass('col-md-6').addClass('col-md-12');
    $('#fechaFinColumn').removeClass('col-md-6').addClass('col-md-12');
    $('#diasColumn').removeClass('col-md-6').addClass('col-md-12');
    $('#mes2').hide();
    $('#fi2').hide();
    $('#ff2').hide();
    $('#di2').hide();
    $('#lv, #ll, #lp').css('color', '');
    document.getElementById('mensaje-adelantado').style.display = 'none';
});
//RESETEAR

function reset_all(){
    vpl_act_valor = ''; 
    $('#camadicionales2').empty();
    $('#camadicionales').hide();
    $('#cronogramaWarning').hide();
    $('#diasWarning').hide();
    $('#Eperiodo').removeClass('is-invalid');
    $('#Eperiodo').removeClass('is-valid');
    $("#Eperiodo").prop("readonly", false);
    $('#btne').hide();
    $('#btnv').show();
    $('#dnipcv').val(null).trigger('change');
    var formtra = $('#guardarv')[0]; 
    var dnipvlpValue = $('#id_pvlp').val(); 
    formtra.reset(); 
    $('#id_pvlp').val(dnipvlpValue); 
}
//INICIO VLP
    $(document).ready(function() {
        initializeSelect2(['dnipcv','id_pvlp'], {
            url: '/getPersonal_list',
            placeholder: 'Seleccione un personal',
            minInputLength: 0,
            loadAllIfEmpty: true,
        });

        //ASIGNAR VALORES
        var periodInput = document.getElementById('Cperiodo');
        periodInput.value = new Date().getFullYear()- 1;
        var periodoSelect = document.getElementById('periodo_select');
        periodoSelect.value = new Date().getFullYear() - 1;
        year = new Date().getFullYear();
        searchper = periodoSelect.value;
        convertirAMayusculas();

      // FUNCION PARA DESABILITAR SELECT
      $("#cronogramaForm :input").not("#dnipcv").prop("disabled", true);
            // Escuchar el evento change en el select 'dnipcv'
        $("#dnipcv").on("change", function () {
            var selectedValue = $(this).val();
            if (selectedValue) {
                // Habilitar los campos si hay un valor seleccionado
                $("#cronogramaForm :input").not("#dnipcv").prop("disabled", false);
            } else {
                // Deshabilitar los campos si no hay valor seleccionado
                $("#cronogramaForm :input").not("#dnipcv").prop("disabled", true);
            }
        });

        //LIMPIAR FORM
        $('#uploadModal').on('hidden.bs.modal', function () { $('#uploadForm')[0].reset(); });

    function initializeDataTable(tableId, ajaxUrl, columns) {
        var table = $(tableId).DataTable({
            paging: true,
            searching: true,
            info: true,
            lengthChange: true,
            scrollX: true,
            scrollY: '600px',
            autoWidth: false,
            processing: true,
            serverSide: true,
            language: {
                search: "Buscar",
                zeroRecords: "No se encontraron resultados",
                info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                lengthMenu: "Mostrar _MENU_ entradas por página",
                emptyTable: "No hay datos disponibles en la tabla", 
                infoEmpty: "Mostrando 0 de 0 of 0 entradas",
                loadingRecords: "Cargando...",
            },

        ajax: {
                url: ajaxUrl,
                data: function (d) {
                    d.periodo = $('#periodo_select').val();
                    d.repo = repoValue; 
                }
            },
        columns: columns,
  
        columnDefs: [
        {
            targets: 6, 
            width: '10px'
        }
        ],
        order: defaultOrder, 

        });
        $(tableId + ' tbody').on('click', 'tr.parent', function() {
            var tr = $(this);
            var row = table.row(tr);

            var reprogramaciones = table.data().toArray().filter(function(item) {
                return item.idva === row.data().idva && item.estado === 'NP';
            });
            console.log(reprogramaciones);

            if (reprogramaciones.length > 0) {
                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    var childRows = '';
                    reprogramaciones.forEach(function(reprog, index) {
                        childRows += format(reprog, index + 1);
                    });
                    row.child(childRows).show();
                    tr.addClass('shown');
                }
            }
        })
        tables[tableId] = table;
            activeTable = table;
        
        return table;
    }

    initializeDataTable('#cronograma-table', 'getMovimientos', [
        {
            title: '',
            data: 'idcv',
            orderable: true,
            searchable: false,
            render: function(data, type, row) {
                var isDisabled = data === null ? 'disabled' : '';
                return `<input type='checkbox' class='select-row' data-dni='${row.id_personal}' data-dias='${row.dias}' data-periodo='${row.periodo_programacion}' data-idcv='${data}' ${isDisabled}>`;
            },
            className: 'dt-body-center'
        },
        { title: 'DNI',  data: 'nro_doc', name: 'nro_doc', orderable: false},
        { title: 'REGIMEN',  data: 'regimen_condicion', name: 'regimen_condicion', orderable: false},
        { title: 'PERSONAL',  data: 'personal', name: 'personal', orderable: false, searchable: true},
        { title: 'PERIODO',  data: 'periodo_programacion', name: 'periodo_programacion', orderable: false,
            select: {
            style: 'single',
            label: 'Seleccionar Periodo',
            options: [
                { value: '2023', label: '2023' },
                { value: '2024', label: '2024' },
                { value: 'Todos', label: 'Todos' }
            ]
        }
        },
        { title: 'DIAS', data: 'dias', name: 'dias' },
        { 
            title: 'MES', 
            data: 'mes', 
            name: 'mes', 
        },
        { 
            title: 'FECHA INICIO',
        data: 'fecha_ini',
        name: 'fecha_ini'
        },
        { title: 'FECHA FINAL',
            data: 'fecha_fin',
            name: 'fecha_fin',
        },           
        { title: 'V',  data: 'dias_vacaciones', name: 'dias_vacaciones', orderable: false},
        { title: 'L',  data: 'dias_licencias', name: 'dias_licencias', orderable: false},
        { title: 'P',  data: 'dias_permisos', name: 'dias_permisos', orderable: false},
        { title: 'DIAS R',  data: 'dias_restantes', name: 'dias_restantes', orderable: false},
        { title: 'ESTADO', 
            data: 'estado',
            name: 'estado',
        render: function(data, type, row) {
                var buttonClass = 'btn-info'; 
                if (row.estado === 'Sí') {
                    return '<button class="btn btn-success">PROGRAMADO</button>';
                }else if(row.estado === 'No'){
                    return '<button class="btn btn-danger">NO PROGRAMADO</button>';
                }else if(row.estado === 'r'){
                    return '<button class="btn btn-info">REPROGRAM</button>';
                }
            }
        },  
        { 
            title: 'REPR', 
            data: 'total', 
            name: 'total', 
            orderable: false, 
            render: function(data, type, row) {
                return row.idcv !== null && row.idvr === null ? `<button class='btn btn-primary' onclick='filterByRepo(${row.idvo})'>R: ${data-1}</button>` : '';
        
            }
        },        
        { title: 'MOT',  data: 'obs', name: 'obs'},

        { title: 'OBS',  data: 'idvr', name: 'idvr', visible:false},
        { title: 'DOC',  data: 'doc', name: 'doc'},
        {
        title: 'ARCH',
        data: 'archivo',
        name: 'archivo',
            render: function(data, type, row) {
                if (data) {
                    return '<button type="button" class="btn btn-primary btn-sm ver-pdf" data-pdf="' + data + '"><i class="fas fa-file-pdf"></i></button>';
                } else {
                    return '';
                }
            }
        },             
        { title: '',  data: 'idcv', name: 'idcv', orderable: false, searchable: false, render: function(data, type, row){
            return row.idcv !== null && row.idvr === null ? "@hasanyrole('ADMIN|COORDINADOR')<button onclick='borrarv(" + data + ", \"cronograma_crud\")' class='btn btn-danger btn-sm' title='Eliminar'><i class='fas fa-trash'></i></button> <button class='btn btn-warning btn-sm edit-btn' onclick='actualizarcv(" + data + ", \"cronograma_crud\", \"cronograma_crud\", \"" + row.personal_id + "\")'><i class='fas fa-edit'></i></button>@endhasanyrole": '';
            } 
        },
    ]);



//Filtro y reprograaciones
$('#periodo_select').change(function() {
    searchper = $(this).val();
    activeTable.ajax.url(activeTable.ajax.url()).load();
});

$('#mes_select').on('change', function() {
    usingDefaultOrder = true;
    selectedMonth = $(this).val();
    var regexSearch = selectedMonth ? '\\b' + selectedMonth + '\\b' : '';
    activeTable.columns(6).search(regexSearch, true, false).draw();

});

$('#regimen_select').on('change', function() {
    var regimenper = $(this).val();
    var regexSearch = regimenper ? '\\b' + regimenper + '\\b' : '';
    activeTable.columns(2).search(regexSearch, true, false).draw();
});


});

$(document).on('change', '.archivo', async function(event) {
    var file = event.target.files[0];
    if (file) {
        var arrayBuffer = await file.arrayBuffer();
        var pdfDoc = await PDFLib.PDFDocument.load(arrayBuffer);
        var numPages = pdfDoc.getPageCount();
        var nroFoliosInput = event.target.closest('.form-group').nextElementSibling.querySelector('.nro_folios');
        nroFoliosInput.value = numPages;
    }
});


// CRUD

var conid;
function actualizarcv(id, url, url2, personal_id) {
    $('#Eperiodo').attr('required', 'required'); 
    $('#CronogramaModalLabel').html('Editar');  
    $("#error-message").hide();

    $("#cronogramaForm :input").not("#dnipcv").prop("disabled", false);
    $("#Cperiodo").prop("readonly", true);
    $('#CronogramaModal').modal('show');
    $('#camadicionales2').empty();
    $('#personacv').hide();
    $('#cronogramaWarning').hide();
    $('#diasWarning').hide();
    $('#Eperiodo').removeClass('is-invalid').removeClass('is-valid');
    conid = id;
    $.ajax({
        url: url + "/" + id ,
        type: 'GET',
        success: function(data) {
            // Llenar los campos básicos
            $('#Cobservaciones').val(data.observaciones);
            $('#Cidtd').val(data.idtd);
            $('#Cnrodoc').val(data.nrodoc);
            $('#Cperiodo').val(data.periodo);
            $('#dnipcv').val(data.personal_id);
            $('#Cperiodo').val(data.periodo);

            var mesArray = JSON.parse(data.mes || '[]');
            var fechaIniArray = JSON.parse(data.fecha_ini || '[]');
            var fechaFinArray = JSON.parse(data.fecha_fin || '[]');
            var diasArray = JSON.parse(data.dias || '[]');

            // Manejar los arrays de fechas y selects
            if (mesArray.length > 1) {
                // Seleccionar el checkbox y mostrar los campos adicionales
                $('#toggleDateRange').prop('checked', true).trigger('change');

                $('#Emes1').val(mesArray[0] || '');
                $('#Emes2').val(mesArray[1] || '');
                $('#fecha_ini').val(fechaIniArray[0] || '');
                $('#fi2 input').val(fechaIniArray[1] || '');
                $('#fecha_fin').val(fechaFinArray[0] || '');
                $('#ff2 input').val(fechaFinArray[1] || '');
                $('#fdi').val(diasArray[0] || '');
                $('#fdi2').val(diasArray[1] || '');

            } else {
                // Deseleccionar el checkbox y ocultar los campos adicionales
                $('#toggleDateRange').prop('checked', false).trigger('change');

                $('#Emes1').val(mesArray[0] || '');
                $('#fecha_ini').val(fechaIniArray[0] || '');
                $('#fecha_fin').val(fechaFinArray[0] || '');
                $('#fdi').val(diasArray[0] || '');
                cronograma_act_valor = diasArray[0] ?? '';
                
            }
            // Mostrar el modal
            // Asignar la función para guardar los datos actualizados
            $('#btnrepo').attr('onclick', 'guardarActualizacionrepo("' + url2 + '")');
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al cargar los datos del campo.'
            });
        }
    });
}

//
function guardarActualizacionrepo(url) {
    if (validarFormulario('cronogramaForm')) {
        event.preventDefault();
        var formtra = $('#cronogramaForm')[0]; 
        var formData = new FormData(formtra);

        var fechasIniArray = [];
        var fechasFinArray = [];
        var mesfiarray = [];
        var diasarray = [];

        // Solo agregar valores visibles a los arrays
        $('input[name="fecha_ini[]"]').each(function() {
            if ($(this).is(':visible')) {
                var value = $(this).val();
                if (value.trim() !== "") {
                    fechasIniArray.push(value);
                }
            }
        });

        $('input[name="fecha_fin[]"]').each(function() {
            if ($(this).is(':visible')) {
                var value = $(this).val();
                if (value.trim() !== "") {
                    fechasFinArray.push(value);
                }
            }
        });

        $('select[name="mes2[]"]').each(function() {
            if ($(this).is(':visible')) {
                var value = $(this).val();
                if (value.trim() !== "") {
                    mesfiarray.push(value);
                }
            }
        });

        $('input[name="dias2[]"]').each(function() {
            if ($(this).is(':visible')) {
                var value = $(this).val();
                if (value.trim() !== "") {
                    diasarray.push(value);
                }
            }
        });
        // Agregar solo si hay valores visibles
        if (fechasIniArray.length > 0) {
            formData.append('fecha_ini', JSON.stringify(fechasIniArray));
        } else {
            formData.delete('fecha_ini[]');
        }

        if (fechasFinArray.length > 0) {
            formData.append('fecha_fin', JSON.stringify(fechasFinArray));
        } else {
            formData.delete('fecha_fin[]');
        }

        if (mesfiarray.length > 0) {
            formData.append('mes', JSON.stringify(mesfiarray));
        } else {
            formData.delete('mes[]');
        }
        if (diasarray.length > 0) {
            formData.append('dias', JSON.stringify(diasarray));
        } else {
            formData.delete('dias2[]');
        }
        
        formData.append('_method', 'PUT'); 
        fetch(url + "/" + conid, {
            method: 'POST', 
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())  // Convertir la respuesta en JSON
        .then(data => {
            // Si la solicitud es exitosa
            Swal.fire({
                icon: 'success',
                title: 'Campo actualizado!',
                timer: 1000,
                text: 'El campo se ha actualizado correctamente.'
            });
            $('#btne').hide();
            $('#btnv').show();
            $('#CronogramaModal').modal('hide');
            activeTable.ajax.url(activeTable.ajax.url()).load();
        })
        .catch(error => {
            // Si hay un error en la solicitud
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                timer: 1000,
                text: 'Hubo un problema al actualizar el campo.'
            });
        });
    }
}

function borrarv(id, url_b) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, bórralo!'
    }).then((result) => {
            if (result.isConfirmed) { 
                $.ajax({
                    url: url_b + "/" + id,
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'DELETE',       
                    cache: false,
                    success: function(response) {
                        activeTable.ajax.url(activeTable.ajax.url()).load();
                        Swal.fire({
                            icon: 'success',
                            title: 'Campo eliminado!',
                            text: response.success,
                            timer: 1000,
                        });
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un problema al cargar los datos del trabajo.',
                            timer: 1000,
                        });
                    }  
                });
            }
       });
}

</script>
