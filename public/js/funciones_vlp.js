function reset_all_vlp(){
    $("#formAdd :input").not("#dnipcv").prop("disabled", false);
    $('#cronogramaWarning, #dcronogramaWarning').hide();
    $('#diasWarning, #ddiasWarning').hide();
    $('#Eperiodo, #Edperiodo').removeClass('is-invalid');
    $('#Eperiodo, #Edperiodo').removeClass('is-valid');
    adelantado = 0;
    vpl_act_valor = ''; 
    var formtra = $('#formAdd')[0]; 
    formtra.reset();
}

//DECLARACION DE MESES
var monthNames = [
    'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 
    'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'
];


//CONVERSION DE MES A INT PARA CALCULOS DIAS Y FECHAS PRESELECCIONADAS
function getMonthNumber(monthName) {
    const index = monthNames.indexOf(monthName.toUpperCase()) + 1;
    return index < 10 ? '0' + index : index;
}
function setmonth(){
        // ASIGNANDO MESES 
    monthNames.forEach((monthName) => {
        const option = document.createElement('option');
        option.value = monthName;
        option.text = monthName;
        mesSelect2.appendChild(option);
    });
    monthNames.forEach((monthName) => {
        const option = document.createElement('option');
        option.value = monthName;
        option.text = monthName;
        mesSelect2d.appendChild(option);
    });
}

//ACTUALIZAR IDS PARA BUSQUEDA DE PERIODOS EN VLP
function actualizarIds() {
    fechaIniInput2 = document.getElementById('Edfecha_ini');
    fechaFinInput2 = document.getElementById('Edfecha_fin');
    mesSelect2 = document.getElementById('Edmes');
    diasInput2 = document.getElementById('Eddias');
    diasRestntesInput2 = document.getElementById('Eddiasr');
    periodo2 = document.getElementById('Edperiodo');
    CR2 = document.getElementById('dCR2');
    VC2 = document.getElementById('dVC2');
    LC2 = document.getElementById('dLC2');
    PC2 = document.getElementById('dPC2');
    btnfam = document.getElementById('btnSubmit');
    diasWarning = document.getElementById('ddiasWarning');
    cronogramaWarning = document.getElementById('dcronogramaWarning');
    elementos = [
        document.getElementById('lv_dvlp'),
        document.getElementById('ll_dvlp'),
        document.getElementById('lp_dvlp')
    ];
    cronvlp = document.getElementById('dcronvlp');    
    setupCronograma();
}
function VolverIds() {
    fechaIniInput2 = document.getElementById('Efecha_ini');
    fechaFinInput2 = document.getElementById('Efecha_fin');
    mesSelect2 = document.getElementById('Emes');
    diasInput2 = document.getElementById('Edias');
    diasRestntesInput2 = document.getElementById('Ediasr');
    periodo2 = document.getElementById('Eperiodo');
    CR2 = document.getElementById('CR2');
    VC2 = document.getElementById('VC2');
    LC2 = document.getElementById('LC2');
    PC2 = document.getElementById('PC2');
    btnfam = document.getElementById('btnfam');
    diasWarning = document.getElementById('diasWarning');
    cronogramaWarning = document.getElementById('cronogramaWarning');
    elementos = [
        document.getElementById('lv_vlp'),
        document.getElementById('ll_vlp'),
        document.getElementById('lp_vlp')
    ];
    setupCronograma();
    cronvlp = document.getElementById('cronvlp');    
}


//FUNCIONES CALCULO DE TIEMPO Y DIAS
function setupCronograma() {
    //ASIGNAR FECHAS EN BASE A MES
    mesSelect2.addEventListener('change', function() {
        const month = getMonthNumber(this.value);
        if (!isNaN(month) && month >= 1 && month <= 12) {
            const year = new Date().getFullYear();
            const firstDay2 = new Date(year, month - 1, 1);
            const lastDay2 = new Date(year, month, 0);

            fechaIniInput2.value = firstDay2.toISOString().split('T')[0];
            fechaFinInput2.value = lastDay2.toISOString().split('T')[0];

            const totalDays2 = lastDay2.getDate();
            diasInput2.value = totalDays2;

        } else {
            console.error('Invalid month input');
        }
    });

    fechaIniInput2.addEventListener('change', function() {
        const fechaIni = new Date(this.value);
        if (!isNaN(fechaIni.getTime())) {
            const month = fechaIni.getMonth(); // Obtener el mes de la fecha de inicio (0-11)
            mesSelect2.value = monthNames[month]; // Ajustar el select de mes al nuevo mes
        } 
    });

}

//CALCULO DIAS ENTRE FECHAS
function calculardiasvlp() {
  var fechaIni = new Date(fechaIniInput2.value);
  var fechaFin = new Date(fechaFinInput2.value);
  var diferenciaMilisegundos = fechaFin - fechaIni;
  var dias = Math.round(diferenciaMilisegundos / (1000 * 60 * 60 * 24))+1;
  diasInput2.value = dias;
}

// LLAMANDO A LAS FUNCIONES


function validar_vlp(validacion) {
    var personal_id = $('#dnipcv').val();
    calculardiasvlp();
    if (validacion.value === 'SI') {
        validarCronograma(personal_id, periodo2.value);
    } else {
        $('#Eperiodo').removeClass('is-invalid is-valid');
    }
}


// Función para actualizar los días restantes basado en la respuesta
function actualizarDiasUsados(diasUsados) {
    let remainingDays = parseInt(diasUsados, 10) || 0;
    const usedDays = parseInt(diasInput2.value, 10) || 0;
    // Ajustar los días restantes con un valor actual si existe
    if (typeof vpl_act_valor !== 'undefined' && vpl_act_valor !== '' && Number.isInteger(vpl_act_valor) && vpl_act_valor < 30) {
        remainingDays -= parseInt(vpl_act_valor, 10);
    }
        diasRestntesInput2.value = remainingDays;
        diasRestntesInput2.setAttribute('data-initial-value', remainingDays);
        // Validar valores
        bloquearBotones(true);
        if (remainingDays + usedDays > 30) {
            diasWarning.style.display = 'block';
            diasWarning.textContent = 'No se puede asignar más días.';
        } else if (usedDays < 0) {
            diasWarning.style.display = 'block';
            diasWarning.textContent = 'Valor inválido.';
        } else {
            diasWarning.style.display = 'none'; // Ocultar el mensaje de advertencia
            bloquearBotones(false); // Permitir interacción con los botones
        }
}

// Función para mostrar advertencias
function mostrarAdvertencia(mensaje) {
    cronogramaWarning.style.display = 'block';
    cronogramaWarning.textContent = mensaje;
}
function bloquearBotones(disabled) {
    btnfam.disabled = disabled;
}

// Función para validar el cronograma
function validarCronograma(personal_id, periodo) {
    $.ajax({
        url: '/buscarCronograma',
        data: { personal_id: personal_id, periodo: periodo },
        success: function(response) {
            if (response.hasCronograma > 0) {
                cronogramaWarning.style.display = 'none';
                periodo2.classList.remove('is-invalid');
                periodo2.classList.add('is-valid');
                adelantado = 0;
            } else {
                mostrarAdvertencia('No se encontró ningún cronograma para este periodo.');
                var anioReferencia = new Date().getFullYear() - 1; 
                if (periodo > anioReferencia) {
                    adelantado = 1; 
                } else {
                    adelantado = 0; 
                }
                periodo2.classList.remove('is-valid');
                periodo2.classList.add('is-invalid');
            }
            actualizarDiasUsados(response.dias_usados);
       
            CR2.value = response.dias_cv;
            VC2.value = response.dias_v;
            LC2.value = response.dias_l;
            PC2.value = response.dias_p;


            const mensajeAdelantado = document.getElementById('mensaje-adelantado_vlp');
            let sumarDias = false;
            // Recorrer las referencias y aplicar la lógica
            elementos.forEach((elemento, index) => {
                const diasAd = parseInt(response[`dias_ad_${['v', 'l', 'p'][index]}`], 10);
                if (diasAd > 0) {
                    elemento.style.color = 'red'; // Cambiar el color del texto
                    mensajeAdelantado.style.color = 'red'; // Cambiar el color del mensaje
                    sumarDias = true;
                } else {
                    elemento.style.color = ''; // Restablecer al color predeterminado
                    mensajeAdelantado.style.display = 'none'; // Ocultar el mensaje
                }
            });
            if (sumarDias) {
                console.log(sumarDias);
                document.getElementById('mensaje-adelantado_vlp').style.display = 'block';
            }

        }
    });
}




