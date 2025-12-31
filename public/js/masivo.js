// Agregar el evento a todos los inputs
document.addEventListener('DOMContentLoaded', function () {
    let inputs = document.querySelectorAll('input');

    inputs.forEach(function (input) {
        input.addEventListener('blur', function () {
            input.value = input.value.toUpperCase(); // Convierte el texto en mayúsculas
        });
    });
});
$(document).ready(function () {
    $('#nro-identificacion').on('blur', function () {
        var tipo = document.getElementById('doc-identificacion').value;
        var identificacion = document.getElementById('nro-identificacion').value;
        $.ajax({
            url: 'consulta-existencia',
            type: 'POST',
            data: {
                tipo: tipo,
                identificacion: identificacion
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.cod == 1) {
                    const confirmar = confirm(response.res + "\n\n¿Deseas continuar?");
                    if (confirmar) {
                        window.location.href = "/edicion-ingreso-masivo?id=" + response.id;
                    } else {
                        document.getElementById('nro-identificacion').value = "";
                    }
                }
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    });
    $('#tipo-personal').on('change', function () {
        if ($(this).val() === '0') {
            $('#nuevoTipoModal').modal('show');
        }
    });
    $('#nuevoTipoForm').on('submit', function (e) {
        e.preventDefault();
        let nombre = $('#nombreTipoNuevo').val();
        $.ajax({
            url: 'tipopersonal',
            type: 'POST',
            data: {
                nombre: nombre
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {

                let $select = $('#tipo-personal');
                $select.empty();
                $select.append('<option value="" selected disabled></option>');
                response.forEach(function (tipo) {
                    let option = new Option(tipo.nombre, tipo.id, false, false);
                    $select.append(option);
                });
                $select.append('<option value="0">Agregar nuevo...</option>');
                $select.trigger('change');
                $('#nuevoTipoModal').modal('hide');
                $('#nombreTipoNuevo').val('');
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });


    });

    $('#tipodom').on('change', function () {
        if ($(this).val() === '0') {
            $('#nuevaViaModal').modal('show');
        }
    });


    $('#nuevaViaForm').on('submit', function (e) {
        e.preventDefault();
        let nombre = $('#nombre-nueva-via').val();
        $.ajax({
            url: 'nueva-via',
            type: 'POST',
            data: {
                nombre: nombre
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {

                let $select = $('#tipodom');
                $select.empty();
                $select.append('<option value="" selected disabled></option>');
                response.forEach(function (tipo) {
                    let option = new Option(tipo.nombre, tipo.id, false, false);
                    $select.append(option);
                });
                $select.append('<option value="0">Agregar nuevo...</option>');
                $select.trigger('change');
                $('#nuevaViaModal').modal('hide');
                $('#nombre-nueva-via').val('');
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    });

    $('#id-cargo-vinculo').on('change', function () {
        if ($(this).val() === '0') {
            $('#nuevoCargoModal').modal('show');
        }
    });

    $('#nuevoCargoModal').on('submit', function (e) {
        e.preventDefault();
        let nombre = $('#nombre-nuevo-cargo').val();
        $.ajax({
            url: 'nuevo-cargo',
            type: 'POST',
            data: {
                nombre: nombre
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {

                let $select = $('#id-cargo-vinculo');
                $select.empty();
                $select.append('<option value="" selected disabled></option>');
                response.forEach(function (tipo) {
                    let option = new Option(tipo.nombre, tipo.id, false, false);
                    $select.append(option);
                });
                $select.append('<option value="0">Agregar nuevo...</option>');
                $select.trigger('change');
                $('#nuevoCargoModal').modal('hide');
                $('#id-cargo-vinculo').val('');
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    });

    $('#id-regimen-vin').on('change', function () {
        if ($(this).val() === '0') {
            $('#nuevoRegimenModal').modal('show');
        }
    });
    $('#nuevoRegimenForm').on('submit', function (e) {
        e.preventDefault();
        let nombre = $('#nombre-regimen').val();
        $.ajax({
            url: 'nuevo-regimen',
            type: 'POST',
            data: {
                nombre: nombre
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {

                let $select = $('#id-regimen-vin');
                $select.empty();
                $select.append('<option value="" selected disabled></option>');
                response.forEach(function (tipo) {
                    let option = new Option(tipo.nombre, tipo.id, false, false);
                    $select.append(option);
                });
                $select.append('<option value="0">Agregar nuevo...</option>');
                $select.trigger('change');
                $('#nuevoRegimenModal').modal('hide');
                $('#nombre-regimen').val('');
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    });



    $('#id-condicion-laboral-vin').on('change', function () {
        if ($(this).val() === '0') {
            $('#nuevoCondicionLabModal').modal('show');
        }
    });
    $('#nuevoCondicionLabForm').on('submit', function (e) {
        e.preventDefault();
        let nombre = $('#nombre-condicion').val();
        let descripcion = $('#descripcion-condicion').val();
        $.ajax({
            url: 'nueva-condicion-lab',
            type: 'POST',
            data: {
                nombre: nombre, descripcion: descripcion
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {

                let $select = $('#id-condicion-laboral-vin');
                $select.empty();
                $select.append('<option value="" selected disabled></option>');
                response.forEach(function (tipo) {
                    let option = new Option(tipo.nombre, tipo.id, false, false);
                    $select.append(option);
                });
                $select.append('<option value="0">Agregar nuevo...</option>');
                $select.trigger('change');
                $('#nuevoCondicionLabModal').modal('hide');
                $('#nombre-condicion').val('');
                $('#descripcion-condicion').val('');
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    });


});
function previewImage() {
    const fileInput = document.getElementById('archivo-prev');
    const imagePreview = document.getElementById('profileImage-prev');
    const hiddenInput = document.getElementById('perfil-base64');
    const file = fileInput.files[0];

    if (!file) return;

    // Validar tipo de archivo
    const validTypes = ['image/jpeg'];
    if (!validTypes.includes(file.type)) {
        alert('Solo se permiten imágenes JPG');
        fileInput.value = '';
        return;
    }

    // Validar tamaño (máximo 2MB)
    const maxSize = 2 * 1024 * 1024; // 2MB
    if (file.size > maxSize) {
        alert('La imagen no debe superar los 2MB.');
        fileInput.value = '';
        return;
    }

    // Mostrar vista previa y guardar base64
    const reader = new FileReader();
    reader.onload = function (e) {
        const base64URL = e.target.result;
        imagePreview.src = base64URL;
        //hiddenInput.value = base64URL;
    };
    reader.readAsDataURL(file);
}


//recargar items provincia
function mostrarProvincia(event) {
    const idDepartamento = event.target.value;
    const selectProvincia = document.getElementById('idpro');
    // Limpiar el contenido actual del select.
    selectProvincia.innerHTML = '<option selected disabled></option>';
    const selectDistrito = document.getElementById('iddis');

    // Limpiar el contenido actual del select.
    selectDistrito.innerHTML = '<option selected disabled></option>';

    // Asegúrate de que 'idDepartamento' esté definido antes de continuar.
    $.ajax({
        url: 'consultar-provincia',
        type: 'GET',
        data: {
            id: idDepartamento,
        },
        success: function (response) {
            //var recuperado = JSON.parse(response);
            if (response) {
                // Agregar las opciones dinámicamente.
                response.forEach(provincia => {
                    const option = document.createElement('option');
                    option.value = provincia.id; // Asignar el ID de la provincia.
                    option.textContent = provincia.nombre; // Mostrar el nombre de la provincia.
                    selectProvincia.appendChild(option);
                });

                // Habilitar el select una vez que se llenan las opciones.
                selectProvincia.disabled = false;
            }
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
}

//recargar items provincia
function mostrarDistrito(event) {
    const idprovincia = event.target.value;

    const selectDistrito = document.getElementById('iddis');
    // Limpiar el contenido actual del select.
    selectDistrito.innerHTML = '<option selected disabled></option>';

    // Asegúrate de que 'idDepartamento' esté definido antes de continuar.
    $.ajax({
        url: 'consultar-distritos',
        type: 'GET',
        data: {
            id: idprovincia,
        },
        success: function (response) {
            //var recuperado = JSON.parse(response);
            if (response) {
                // Agregar las opciones dinámicamente.

                response.forEach(distrito => {
                    const option = document.createElement('option');
                    option.value = distrito.id; // Asignar el ID de la provincia.
                    option.textContent = distrito.nombre; // Mostrar el nombre de la provincia.
                    selectDistrito.appendChild(option);
                });

                // Habilitar el select una vez que se llenan las opciones.
                selectDistrito.disabled = false;
            }
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
}
