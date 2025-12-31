//informe escalafonario
$('#dnip_informe').on('input', function() {
    var query = $(this).val();
    if (query.length > 1) {
        $.ajax({
            url: "/buscarUsuarios",
            data: { query: query },
            success: function(data) {
                var suggestions = $('#suggestionsinf');
                suggestions.empty();

                $.each(data, function(index, item) {
                    suggestions.append('<a class="dropdown-item" data-personal_id="' + item.personal_id + '" data-nombre="' + item.nombre_completo + '">' + item.nombre_completo + '</a>');
                });
                suggestions.show();
            }
        });
    } else {
        $('#suggestionsinf').empty().hide();
    }
});

function generarInforme2(adjuntarArchivos) {
    console.log(adjuntarArchivos);
    var seleccionados = [];
    $('#formSeleccionarPersonalCampos input[name="personal[]"]').each(function() {
        seleccionados.push($(this).val());
    });
    var camposSeleccionados = [];
    $('#formSeleccionarCampos input:checked').each(function() {
        camposSeleccionados.push($(this).val());
    });

    var years = $('#years').val();

    $.ajax({
        url: "/InformeEsc",
        type: 'POST',
        data: {
            personal: seleccionados,
            campos: camposSeleccionados,
            years: years,
            adjuntarArchivos: adjuntarArchivos,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        xhrFields: {
            responseType: 'blob'
        },
        success: function(data) {
            var blob = new Blob([data], { type: 'application/pdf' });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.target = '_blank';
            link.click();
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al generar el informe.'
            });
        }
    });
}

function generarInformeEnWord() {
    console.log(adjuntarArchivos);
    var seleccionados = [];
    $('#formSeleccionarPersonalCampos input[name="personal[]"]').each(function() {
        seleccionados.push($(this).val());
    });
    var camposSeleccionados = [];
    $('#formSeleccionarCampos input:checked').each(function() {
        camposSeleccionados.push($(this).val());
    });

    var years = $('#years').val();

    $.ajax({
        url: "/generate-word",
        type: 'GET',
        data: {
            personal: seleccionados,
            campos: camposSeleccionados,
            years: years,
            adjuntarArchivos: adjuntarArchivos,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        /*xhrFields: {
            responseType: 'blob'
        },*/
        success: function(data) {
            /*var blob = new Blob([data], { type: 'application/pdf' });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.target = '_blank';
            link.click();*/
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al generar el informe.'
            });
        }
    });
}


function seleccionarGeneral(indices) {
    deseleccionarTodos();
    var checkboxes = document.querySelectorAll('#formSeleccionarCampos .form-check-input');
    indices.forEach(index => {
        if (checkboxes[index]) {
            checkboxes[index].checked = true;
        }
    });
}

function seleccionarTodos() {
    var checkboxes = document.querySelectorAll('#formSeleccionarCampos .form-check-input');
    checkboxes.forEach(checkbox => checkbox.checked = true);
}
function deseleccionarTodos() {
    var checkboxes = document.querySelectorAll('#formSeleccionarCampos .form-check-input');
    checkboxes.forEach(checkbox => checkbox.checked = false);
}

function modalIM() {
    var checkboxes = document.querySelectorAll('#formSeleccionarCampos .form-check-input');
    var formtra = $('#formSeleccionarPersonalCampos')[0];
    formtra.reset();
    checkboxes.forEach(checkbox => checkbox.checked = false);
    $('[data-personal_id]').remove();
    $('input[name="personal[]"]').remove();
    $('#personal_id').val('');
    $('#modalSeleccionarPersonalCampos').modal('show');
}



$(document).on('click', '.dropdown-item', function() {
    var personal_id = $(this).data('personal_id');
    var nombre_completo = $(this).text();
    //$('#personal_id').val('');
    $('#suggestionsinf').empty().hide();

    // Añadir empleado al pillbox
    if (!$('#selected-employees').find(`[data-personal_id="${personal_id}"]`).length) {
        $('#selected-employees').append(
            `<div class="pillbox-item badge badge-pill badge-primary mx-1 my-1" data-personal_id="${personal_id}">
                ${nombre_completo}
                <span class="pillbox-item-remove" onclick="removePillboxItem('${personal_id}')">&times;</span>
            </div>`
        );
        // Añadir input oculto al formulario
        $('#formSeleccionarPersonalCampos').append(
            `<input type="hidden" name="personal[]" value="${personal_id}" data-personal_id="${personal_id}">`
        );
    }
});


function removePillboxItem(personal_id) {
    $(`[data-personal_id="${personal_id}"]`).remove();
    $(`input[name="personal[]"][data-personal_id="${personal_id}"]`).remove();
}

function eliminarPill(element) {
    $(element).closest('.badge').remove();
}

$('#checkTrayectoriaLaboral').change(function() {
    if ($(this).is(':checked')) {
        $('#year-selection').show();
    } else {
        $('#year-selection').hide();
    }
});
