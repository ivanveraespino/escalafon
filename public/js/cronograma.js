
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
tooltipTriggerList.forEach(function (tooltipTriggerEl) {
    new bootstrap.Tooltip(tooltipTriggerEl);
});



$(document).ready(function () {
    $('.select2').select2({
        placeholder: "Buscar nombre...",
        allowClear: true,
        width: '100%' // para que se adapte al contenedor Bootstrap
    });

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
    $('#guardarCronogramados').off('click').on('click', function () {
        let seleccionados = [];

        $('#tablaDestino tbody tr').each(function () {
            let checkbox = $(this).find('.checkDestino');
            if (checkbox.length) {
                seleccionados.push(checkbox.val());
            }
        });
        let periodo = $('#periodo').val();
        let mes = $('#mes-vacaciones').val();
        let tipodoc = $('#tipo-doc-vac').val();
        let nrodoc = $('#nro-doc-vac').val();
        let observaciones = $('#observaciones-vac').val();
        // Enviar por AJAX si hay seleccionados
        if (seleccionados.length > 0) {
            $.ajax({
                url: '/guardar-cronograma-masivo', // ← reemplaza con tu endpoint Laravel
                method: 'GET',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // ← si usas Laravel
                    ids: seleccionados,
                    periodo: periodo,
                    mes: mes,
                    tipodoc: tipodoc,
                    nrodoc: nrodoc,
                    observaciones: observaciones
                },
                success: function (respuesta) {
                    alert('Mensaje: ' + respuesta.mensaje);
                    location.reload();
                },
                error: function (error) {
                    console.error("Error al enviar:", error);
                }
            });
        }

    });

    // Evento al cambiar el select
    $('#individuo').on('change', function () {
        let idPersonal = $(this).val();

        if (idPersonal) {
            $.ajax({
                url: '/consultar-individuo/' + idPersonal, // Ruta en Laravel
                method: 'GET',
                dataType: 'json',
                success: function (respuesta) {
                    console.log('Datos recibidos:', respuesta);
                    // Aquí puedes llenar campos, mostrar info, etc.
                    $('#informe').html(respuesta.datos);
                },
                error: function (error) {
                    console.error('Error en la consulta:', error);
                }
            });
        }
    });
});

function guardarRegistro() {
    var periodo = document.getElementById('periodo').value;
    var mes = document.getElementById('mes-vacaciones').value;
    var tipodoc = document.getElementById('tipo-doc-vac').value;
    var nrodoc = document.getElementById('nro-doc-vac').value;
    var observacion = document.getElementById('observaciones-vac').value;
    var numero = '';
    switch (mes) {
        case "ENERO":
            numero = '01';
            break;
        case "FEBRERO":
            numero = '02';
            break;
        case "MARZO":
            numero = '03';
            break;
        case "ABRIL":
            numero = '04';
            break;
        case "MAYO":
            numero = '05';
            break;
        case "JUNIO":
            numero = '06';
            break;
        case "JULIO":
            numero = '07';
            break;
        case "AGOSTO":
            numero = '08';
            break;
        case "SETIEMBRE":
            numero = '09';
            break;
        case "OCTUBRE":
            numero = '10';
            break;
        case "NOVIEMBRE":
            numero = '11';
            break;
        case "DICIEMBRE":
            numero = '12';
            break;
    }

    var archivo = document.getElementById('doc-cron').value;
    var cont = 0;
    var cronogramas = document.getElementById("datos-individuo").value;
    var idcronograma = document.getElementById("idcronograma").value;
    if (cronogramas != "") {
        var recuperado = JSON.parse(cronogramas); // Convertimos de vuelta a un objeto
        if (recuperado.length > 0) {
            if (idcronograma != "") {
                recuperado = recuperado.map(obj => {
                    if (obj.id === idcronograma) {
                        obj.cambio = 1;
                        obj.periodo = periodo;
                        obj.inicio = periodo + '-' + numero + '-01'
                        obj.mes = mes;
                        obj.tipodoc = tipodoc;
                        obj.nrodoc = nrodoc;
                        obj.observacion = observacion;
                        obj.archivo = archivo;
                    }
                    return obj;
                });
            } else {
                cont = recuperado[recuperado.length - 1].cont + 1;
                var nuevoObjeto = {
                    cont: cont,
                    cambio: 1,
                    periodo: periodo,
                    mes: mes,
                    inicio: periodo + '-' + numero + '-01',
                    tipodoc: tipodoc,
                    nrodoc: nrodoc,
                    observacion: observacion,
                    archivo: archivo,
                };
                recuperado.push(nuevoObjeto);
            }

        } else {
            var nuevoObjeto = {
                cont: 1,
                cambio: 1,
                periodo: periodo,
                mes: mes,
                tipodoc: tipodoc,
                inicio: periodo + '-' + numero + '-01',
                nrodoc: nrodoc,
                observacion: observacion,
                archivo: archivo,
            };
            recuperado.push(nuevoObjeto);
        }
        document.getElementById('datos-individuo').value = JSON.stringify(recuperado);
    } else {
        document.getElementById('datos-individuo').value = JSON.stringify([{
            cont: 1,
            cambio: 1,
            periodo: periodo,
            mes: mes,
            tipodoc: tipodoc,
            inicio: periodo + '-' + numero + '-01',
            nrodoc: nrodoc,
            observacion: observacion,
            archivo: archivo,
        }]);
    }
    document.getElementById('periodo').value = "";
    document.getElementById('mes-vacaciones').value = "";
    document.getElementById('tipo-doc-vac').value = "";
    document.getElementById('nro-doc-vac').value = "";
    document.getElementById('observaciones-vac').value = "";
    document.getElementById('doc-cron').value = "";
    document.getElementById('idcronograma').value = "";
    construirTablaRegistro();
}

function construirTablaRegistro() {
    var datos = JSON.parse(document.getElementById('datos-individuo').value);
    let tabla = '<table class="table">';
    tabla += `
    <thead>
      <tr>
        <th>Periodo</th>
        <th>Mes</th>
        <th>Inicio</th>
        <th>Documento</th>
        <th>Días</th>
        <th>Acción</th>
      </tr>
    </thead>
    <tbody>
  `;

    // Recorrer los datos y generar filas
    datos.forEach(dato => {

        tabla += `
      <tr>
        <td>${dato.periodo}</td>
        <td>${dato.mes}</td>
        <td>${dato.inicio}</td>
        <td>${dato.tipodoc}: ${dato.nrodoc}</td>
        <td>30 días</td>
        <td>
            <button class="btn btn-outline-info btn-sm" onClick="editarRegistro(${dato.cont})" >
              <i class="fa fa-edit"></i>
            </button>
            <button class="btn btn-outline-danger btn-sm" onClick="anularRegistro(${dato.cont})" >
              <i class="fa fa-trash"></i>
            </button>

        </td>
      </tr>
    `;
    });

    tabla += '</tbody></table>';

    // Insertar la tabla en el contenedor
    document.getElementById('tabla-registro').innerHTML = tabla;
}

function anularRegistro(cont) {
    var experiencia = document.getElementById("datos-individuo").value;
    if (experiencia) {
        var recuperado = JSON.parse(experiencia);
        recuperado = recuperado.filter(dato => dato.cont !== cont);
        document.getElementById("datos-individuo").value = JSON.stringify(recuperado);
        construirTablaRegistro();
    } else {
        document.getElementById('tabla-regisro').innerHTML = '<div class="alert alert-light" role="alert">Sin datos</div>';
    }
}

function editarRegistro(cont) {
    var experiencia = document.getElementById('datos-individuo').value; // Obtenemos la cadena guardada
    if (experiencia != "") {
        var recuperado = JSON.parse(experiencia); // Convertimos de vuelta a un objeto
        recuperado = recuperado.map(obj => {
            if (obj.cont === cont) {

                document.getElementById('periodo').value = obj.periodo;
                document.getElementById('mes-vacaciones').value = obj.mes;
                document.getElementById('tipo-doc-vac').value = obj.tipodoc;
                document.getElementById('nro-doc-vac').value = obj.nrodoc;
                document.getElementById('observaciones-vac').value = obj.observaciones;
                document.getElementById('doc-cron').value = obj.archivo;
                document.getElementById('idcronograma').value = obj.id;
            }
            return obj;
        });
    }
}

function guardarTodo() {
    var datos = document.getElementById('datos-individuo').value;
    if (datos != "") {
        var recuperado = JSON.parse(datos);

        $.ajax({
            url: '/guardar-individual', // ← reemplaza con tu ruta real
            method: 'POST',
            data: {
                datos: datos
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (respuesta) {
                console.log('Respuesta del servidor:', respuesta);
            },
            error: function (error) {
                console.error('Error al enviar:', error);
            }

        });


    }
}