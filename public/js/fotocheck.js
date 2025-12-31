$(document).ready(function () {
    let tablaOrigen = $('#tablaOrigen').DataTable();
    let tablaDestino = $('#tablaDestino').DataTable();

    // Seleccionar todos en origen
    $('#checkAllOrigen').on('click', function () {
        $('.checkOrigen').prop('checked', this.checked);
    });

    // Seleccionar todos en destino
    $('#checkAllDestino').on('click', function () {
        $('.checkDestino').prop('checked', this.checked);
    });

    // Pasar seleccionados
    $('#pasar').on('click', function () {
        $('#tablaOrigen tbody tr').each(function () {
            let checkbox = $(this).find('.checkOrigen');
            if (checkbox.is(':checked')) {
                let id = checkbox.val();
                let nombre = $(this).find('td').eq(1).text();
                let cargo = $(this).find('td').eq(2).text();
                let regimen = $(this).find('td').eq(3).text();

                tablaDestino.row.add([
                    `<input type="checkbox" id="dest-${id}" value="${id}" class="checkDestino">`,
                    nombre,
                    cargo,
                    regimen
                ]).draw();

                tablaOrigen.row(this).remove().draw();
            }
        });
    });

    // Devolver seleccionados
    $('#devolver').on('click', function () {
        $('#tablaDestino tbody tr').each(function () {
            let checkbox = $(this).find('.checkDestino');
            if (checkbox.is(':checked')) {
                let id = checkbox.val();
                let nombre = $(this).find('td').eq(1).text();
                let cargo = $(this).find('td').eq(2).text();
                let regimen = $(this).find('td').eq(3).text();

                tablaOrigen.row.add([
                    `<input type="checkbox" id="or-${id}" value="${id}" class="checkOrigen">`,
                    nombre,
                    cargo,
                    regimen
                ]).draw();

                tablaDestino.row(this).remove().draw();
            }
        });
    });
    $('#generarPDF').on('click', function () {
        let seleccionados = [];

        $('#tablaDestino tbody tr').each(function () {
            let checkbox = $(this).find('.checkDestino');
            if (checkbox.length) {
                seleccionados.push(checkbox.val());
            }
        });

        // Enviar por AJAX si hay seleccionados
        if (seleccionados.length > 0) {
            $.ajax({
                url: '/generar-fotocheck-masivo', // ← reemplaza con tu endpoint Laravel
                method: 'GET',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // ← si usas Laravel
                    ids: seleccionados
                },
                success: function (respuesta) {
                    document.getElementById('contenidoModal').innerHTML = respuesta.reporte
                    $('#modalPDF').modal('show');
                },
                error: function (error) {
                    console.error("Error al enviar:", error);
                }
            });
        }

    });
});
