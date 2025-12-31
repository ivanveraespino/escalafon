document.getElementById('edicionPersonal').addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
        e.preventDefault(); // Evita que el form se envíe
    }
});

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
                alert(response.res);
                window.location.href = "/edicion-personal?id=" + response.id;
            }
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
});

function convertirAMayusculas(event) {
    event.target.value = event.target.value.toUpperCase();
}

// Agregar el evento a todos los inputs
document.addEventListener('DOMContentLoaded', function () {
    let inputs = document.querySelectorAll('input');

    inputs.forEach(function (input) {
        input.addEventListener('blur', function () {
            input.value = input.value.toUpperCase(); // Convierte el texto en mayúsculas
        });
    });
});

function diferenciaDiasFecha(inicio, fin) {
    const unDia = 1000 * 60 * 60 * 24;

    const fechaInicio = new Date(inicio);
    const fechaFin = new Date(fin);
    // Diferencia en milisegundos
    const diferenciaMs = fechaFin.getTime() - fechaInicio.getTime();
    // Convertir a días
    return Math.floor(diferenciaMs / unDia) + 1;
}

function diferenciaFechas(fechaInicio, fechaFin) {
    const inicio = new Date(fechaInicio);
    const fin = new Date(fechaFin);

    let años = fin.getFullYear() - inicio.getFullYear();
    let meses = fin.getMonth() - inicio.getMonth();
    let dias = fin.getDate() - inicio.getDate();

    if (dias < 0) {
        meses--;
        dias += new Date(fin.getFullYear(), fin.getMonth(), 0).getDate(); // Días del mes anterior
    }

    if (meses < 0) {
        años--;
        meses += 12;
    }

    return {
        años,
        meses,
        dias
    };
}

function sumarDias(fecha, dias) {
    let fechaBase = new Date(fecha);
    return new Date(fechaBase.getTime() + dias * 24 * 60 * 60 * 1000);
}

$(document).ready(function () {
    $('.selectpicker').selectpicker();
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
            $select.append('<option value="" selected disabled>Selecciona un tipo</option>');
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
$('#cargo-destino').on('change', function () {
    if ($(this).val() === '0') {
        $('#nuevoCargoModal').modal('show');
    }
});
$('#cargo-encargado').on('change', function () {
    if ($(this).val() === '0') {
        $('#nuevoCargoModal').modal('show');
    }
});


$('#nuevoCargoForm').on('submit', function (e) {
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
            let $selectr = $('#cargo-destino');
            let $selecte = $('#cargo-encargado');
            $select.empty();
            $selectr.empty();
            $selecte.empty();
            $selectr.append('<option value="" selected disabled></option>');
            $selecte.append('<option value="" selected disabled></option>');
            $select.append('<option value="" selected disabled></option>');
            response.forEach(function (tipo) {
                let option = new Option(tipo.nombre, tipo.id, false, false);
                let optionr = new Option(tipo.nombre, tipo.id, false, false);
                let optione = new Option(tipo.nombre, tipo.id, false, false);
                $select.append(option);
                $selectr.append(optionr);
                $selecte.append(optione);

            });
            $select.append('<option value="0">Agregar nuevo...</option>');
            $selectr.append('<option value="0">Agregar nuevo...</option>');
            $selecte.append('<option value="0">Agregar nuevo...</option>');
            $select.trigger('change');
            $selectr.trigger('change');
            $selecte.trigger('change');
            $('#nuevoCargoModal').modal('hide');
            $('#nombre-nuevo-cargo').val('');
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

$('#regimenp').on('change', function () {
    if ($(this).val() === '0') {
        $('#nuevoRegimenPensionarioModal').modal('show');
    }
});
$('#nuevoRegimenPensionarioForm').on('submit', function (e) {
    e.preventDefault();
    let nombre = $('#nombre-regimen-pen').val();
    $.ajax({
        url: 'nuevo-regimen-pen',
        type: 'POST',
        data: {
            nombre: nombre
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {

            let $select = $('#regimenp');
            $select.empty();
            $select.append('<option value="" selected disabled></option>');
            response.forEach(function (tipo) {
                let option = new Option(tipo.nombre, tipo.id, false, false);
                $select.append(option);
            });
            $select.append('<option value="0">Agregar nuevo...</option>');
            $select.trigger('change');
            $('#nuevoRegimenPensionarioModal').modal('hide');
            $('#nombre-regimen-pen').val('');
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
});


$('#tipo-compensacion').on('change', function () {
    if ($(this).val() === '0') {
        $('#nuevoCompensacionModal').modal('show');
    }
});


$('#nuevoCompensacionForm').on('submit', function (e) {
    e.preventDefault();
    let nombre = $('#nombre-nuevo-compensacion').val();
    $.ajax({
        url: 'nueva-compensacion',
        type: 'POST',
        data: {
            nombre: nombre
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {

            let $select = $('#tipo-compensacion');
            $select.empty();
            $select.append('<option value="" selected disabled></option>');
            response.forEach(function (tipo) {
                let option = new Option(tipo.nombre, tipo.id, false, false);
                $select.append(option);

            });
            $select.append('<option value="0">Agregar nuevo...</option>');
            $select.trigger('change');
            $('#nuevoCompensacionModal').modal('hide');
            $('#nombre-nuevo-compensacion').val('');
        },
        error: function (xhr, status, error) {
            console.log(error);
        }
    });
});

///////////////////////////////////////////
/////////FAMILIARES//////////////
//////////////////////////////////////////
function guardarFamiliar() {
    var derecho = document.getElementById('der-hab').value;
    var doc = document.getElementById('tipo-doc-fam').value;
    var nro = document.getElementById('nro-doc-fam').value;
    var nombre = document.getElementById('nombre-fam').value;
    var paterno = document.getElementById('paterno-fam').value;
    var materno = document.getElementById('materno-fam').value;
    var direccion = document.getElementById('direccion-fam').value;
    var tel = document.getElementById('tel-fam').value;
    var parentesco = document.getElementById('parentesco').value;

    var cont = 0;

    var familiares = document.getElementById('familiares').value; // Obtenemos la cadena guardada
    var id = document.getElementById('id-familiar').value;
    if (familiares != "") {
        var recuperado = JSON.parse(familiares); // Convertimos de vuelta a un objeto
        if (id != "") {
            recuperado = recuperado.map(obj => {
                if (obj.cont === Number(id)) {
                    obj.derecho = derecho;
                    obj.doc = doc;
                    obj.nro = nro;
                    obj.nombre = nombre;
                    obj.paterno = paterno;
                    obj.materno = materno;
                    obj.direccion = direccion;
                    obj.tel = tel;
                    obj.parentesco = parentesco;
                }
                return obj;
            });
        } else {
            if (recuperado.length > 0)
                cont = recuperado[recuperado.length - 1].cont + 1;
            else
                cont = 1;
            var nuevoObjeto = {
                cont: cont,
                derecho: derecho,
                doc: doc,
                nro: nro,
                nombre: nombre,
                paterno: paterno,
                materno: materno,
                direccion: direccion,
                tel: tel,
                parentesco: parentesco
            };
            recuperado.push(nuevoObjeto);
        }
        document.getElementById('familiares').value = JSON.stringify(recuperado);


    } else {
        var nuevoObjeto = {
            cont: 1,
            derecho: derecho,
            doc: doc,
            nro: nro,
            nombre: nombre,
            paterno: paterno,
            materno: materno,
            direccion: direccion,
            tel: tel,
            parentesco: parentesco
        };
        document.getElementById('familiares').value = JSON.stringify([nuevoObjeto]);

    }
    document.getElementById('der-hab').value = 0;
    document.getElementById('nro-doc-fam').value = "";
    document.getElementById('nombre-fam').value = "";
    document.getElementById('paterno-fam').value = "";
    document.getElementById('materno-fam').value = "";
    document.getElementById('direccion-fam').value = "";
    document.getElementById('tel-fam').value = "";
    document.getElementById('id-familiar').value = "";
    construirTablaFamiliar();
}

function construirTablaFamiliar() {
    var familiares = document.getElementById('familiares').value;
    var datos = JSON.parse(familiares);
    let tabla = '<table class="table">';
    tabla += `
        <thead>
          <tr>
            <th>Identificación</th>
            <th>Nombre</th>
            <th>Parentesco</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
      `;

    // Recorrer los datos y generar filas
    datos.forEach(dato => {
        tabla += `
          <tr>
            <td>${dato.doc} ${dato.nro}</td>
            <td>${dato.nombre} ${dato.paterno} ${dato.materno}</td>
            <td>${dato.parentesco}</td>
            <td>
                <a class="btn btn-outline-info btn-sm" onClick="editarFamiliar(${dato.cont})" >
                  <i class="fa fa-edit"></i>
                </a>
                <a class="btn btn-outline-danger btn-sm" onClick="anularFamiliar(${dato.cont})" >
                  <i class="fa fa-trash"></i>
                </a>

            </td>
          </tr>
        `;
    });

    tabla += '</tbody></table>';

    // Insertar la tabla en el contenedor
    document.getElementById('tabla-familiares').innerHTML = tabla;
}

function anularFamiliar(cont) {
    var familiares = document.getElementById('familiares').value;
    if (familiares != "") {
        var recuperado = JSON.parse(familiares);
        recuperado = recuperado.filter(dato => dato.cont !== cont);
        document.getElementById('familiares').value = JSON.stringify(recuperado);
        construirTablaFamiliar(recuperado);
    } else {
        document.getElementById('tabla-familiares').innerHTML = '<div class="alert alert-light" role="alert">Sin datos</div>';
    }
}

function editarFamiliar(cont) {
    var familiares = document.getElementById('familiares').value; // Obtenemos la cadena guardada
    if (familiares != "") {
        var recuperado = JSON.parse(familiares); // Convertimos de vuelta a un objeto
        recuperado = recuperado.map(obj => {
            if (obj.cont === cont) {
                document.getElementById('id-familiar').value = cont;
                document.getElementById('der-hab').value = obj.derecho;
                document.getElementById('tipo-doc-fam').value = obj.doc;
                document.getElementById('nro-doc-fam').value = obj.nro;
                document.getElementById('nombre-fam').value = obj.nombre;
                document.getElementById('paterno-fam').value = obj.paterno;
                document.getElementById('materno-fam').value = obj.materno;
                document.getElementById('direccion-fam').value = obj.direccion;
                document.getElementById('tel-fam').value = obj.tel;
                document.getElementById('parentesco').value = obj.parentesco;
            }
            return obj;
        });


    }
}




//recargar items provincia
function mostrarProvincia(event) {
    const idDepartamento = event.target.value;
    const selectProvincia = document.getElementById('idpro');
    // Limpiar el contenido actual del select.
    selectProvincia.innerHTML = '<option value="" selected disabled></option>';
    const selectDistrito = document.getElementById('iddis');

    // Limpiar el contenido actual del select.
    selectDistrito.innerHTML = '<option value="" disabled>Seleccione un distrito</option>';

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
    selectDistrito.innerHTML = '<option value="" selected disabled></option>';

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



///////////////////////////////////////////
////ESTUDIOS COMPLEMENTARIOS//////////////
//////////////////////////////////////////
function guardarEstudiosCom() {
    var denominacion = document.getElementById('denominacion').value;
    var institucion = document.getElementById('centro-estudios-com').value;
    var inicio = document.getElementById('fecha-ini-com').value;
    var fin = document.getElementById('fecha-fin-com').value;
    var horas = document.getElementById('horas-com').value;
    var tipodoc = document.getElementById('tipo-doc-com').value;
    var id = document.getElementById('id-est-com').value;
    var archivo = document.getElementById('doc-est-com').value;
    var cont = 0
    var estudios = document.getElementById('estudios-com').value; // Obtenemos la cadena guardada
    if (estudios != "") {
        if (id != "") {
            var recuperado = JSON.parse(estudios); // Convertimos de vuelta a un objeto

            cont = id;



            recuperado = recuperado.map(obj => {
                if (obj.cont === Number(id)) {
                    obj.denominacion = denominacion;
                    obj.institucion = institucion;
                    obj.inicio = inicio;
                    obj.fin = fin;
                    obj.horas = horas;
                    obj.tipodoc = tipodoc;
                    obj.archivo = archivo;
                }
                return obj;
            });

            document.getElementById('estudios-rea').value = JSON.stringify(recuperado);
            construirTablaEstudiosCom(recuperado);
            document.getElementById('id-est-com').value = "";
            document.getElementById('denominacion').value = "";
            document.getElementById('centro-estudios-com').value = "";
            document.getElementById('fecha-ini-com').value = "";
            document.getElementById('fecha-fin-com').value = "";
            document.getElementById('horas-com').value = "";
            document.getElementById('tipo-doc-com').value = "";
            document.getElementById('subidor-com').value = "";
            document.getElementById("mensaje-com").innerHTML = "";
        } else {
            var recuperado = JSON.parse(estudios); // Convertimos de vuelta a un objeto
            if (recuperado.length > 0)
                cont = recuperado[recuperado.length - 1].cont + 1;
            else
                cont = 1;
            var nuevoObjeto = {
                cont: cont,
                denominacion: denominacion,
                institucion: institucion,
                inicio: inicio,
                fin: fin,
                horas: horas,
                tipodoc: tipodoc,
                archivo: archivo
            };
            recuperado.push(nuevoObjeto);
        }



        document.getElementById('estudios-com').value = JSON.stringify(recuperado);
        construirTablaEstudiosCom(recuperado);
        document.getElementById('denominacion').value = "";
        document.getElementById('centro-estudios-com').value = "";
        document.getElementById('fecha-ini-com').value = "";
        document.getElementById('fecha-fin-com').value = "";
        document.getElementById('horas-com').value = "1";
        document.getElementById('tipo-doc-com').value = "";
        document.getElementById('doc-est-com').value = "";
        document.getElementById('subidor-com').value = "";
        document.getElementById("mensaje-com").innerHTML = "";
    } else {
        document.getElementById('estudios-com').value = JSON.stringify([{
            cont: 1,
            denominacion: denominacion,
            institucion: institucion,
            inicio: inicio,
            fin: fin,
            horas: horas,
            tipodoc: tipodoc
        }]);
        construirTablaEstudiosCom([{
            cont: 1,
            denominacion: denominacion,
            institucion: institucion,
            inicio: inicio,
            fin: fin,
            horas: horas,
            tipodoc: tipodoc
        }]);
        document.getElementById('denominacion').value = "";
        document.getElementById('centro-estudios-com').value = "";
        document.getElementById('fecha-ini-com').value = "";
        document.getElementById('fecha-fin-com').value = "";
        document.getElementById('horas-com').value = "1";
        document.getElementById('tipo-doc-com').value = "";
        document.getElementById('doc-est-com').value = "";
        document.getElementById('subidor-com').value = "";
        document.getElementById("mensaje-com").innerHTML = "";
    }
}

function construirTablaEstudiosCom(datos) {
    let tabla = '<table class="table">';
    tabla += `
    <thead>
      <tr>
        <th>Denominación</th>
        <th>Institución</th>
        <th>Horas</th>
        <th>Acción</th>
      </tr>
    </thead>
    <tbody>
  `;

    // Recorrer los datos y generar filas
    datos.forEach(dato => {
        tabla += `
      <tr>
        <td>${dato.denominacion}</td>
        <td>${dato.institucion}</td>
        <td>${dato.horas}</td>
        <td>
            <a class="btn btn-outline-info btn-sm" onClick="editarEstudiosCom(${dato.cont})" >
              <i class="fa fa-edit"></i>
            </a>
            <a class="btn btn-outline-danger btn-sm" onClick="anularEstudiosCom(${dato.cont})" >
              <i class="fa fa-trash"></i>
            </a>

        </td>
      </tr>
    `;
    });

    tabla += '</tbody></table>';

    // Insertar la tabla en el contenedor
    document.getElementById('tabla-estudios-com').innerHTML = tabla;
}

function anularEstudiosCom(cont) {
    var estudios = document.getElementById('estudios-com').value;
    if (estudios) {
        var recuperado = JSON.parse(estudios);
        recuperado = recuperado.filter(dato => dato.cont !== cont);
        document.getElementById('estudios-com').value = JSON.stringify(recuperado);
        construirTablaEstudiosCom(recuperado);

    } else {
        document.getElementById('estudios-com').value = "";
        document.getElementById('tabla-estudios-com').innerHTML = '<div class="alert alert-light" role="alert">Sin datos</div>';
    }
}
function editarEstudiosCom(cont) {
    var estudios = document.getElementById('estudios-com').value; // Obtenemos la cadena guardada
    if (estudios != "") {
        var recuperado = JSON.parse(estudios); // Convertimos de vuelta a un objeto
        recuperado = recuperado.map(obj => {
            if (obj.cont === cont) {
                document.getElementById('id-est-com').value = cont;
                document.getElementById('denominacion').value = obj.denominacion;
                document.getElementById('centro-estudios-com').value = obj.institucion;
                document.getElementById('fecha-ini-com').value = obj.inicio;
                document.getElementById('fecha-fin-com').value = obj.fin;
                document.getElementById('horas-com').value = obj.horas;
                document.getElementById('tipo-doc-com').value = obj.tipodoc;

                //document.getElementById("mensaje-com").innerHTML = "";

                if (obj.archivo && obj.archivo.trim() !== '') {
                    document.getElementById("mensaje-com").innerHTML =
                        '<a href="../repositories/' + obj.archivo + '" class="text-success" target="_blank"><i class="fa fa-eye" ></i>Ver documento</a>';
                } else {
                    document.getElementById("mensaje").innerHTML =
                        '<span class="text-muted">No se encontró ningún archivo</span>';
                }
                document.getElementById("doc-est-com").value = obj.archivo;

                //obj.archivo = archivo;
            }
            return obj;
        });


    }
}
//////////////////////////////
/////COLEGIATURA/////////////
/////////////////////////////
////////////////////////////////
function guardarColegiatura() {
    var colegio = document.getElementById('nombre-colegio').value;
    var tipodoc = document.getElementById('tipo-doc-col').value;
    var nrocol = document.getElementById('nro-col').value;
    var estado = document.getElementById('estado').value;
    var fechacol = document.getElementById('fecha-col').value;
    var doccol = document.getElementById('doc-col').value;
    var cont = 0
    var colegiatura = document.getElementById('colegiatura').value; // Obtenemos la cadena guardada
    var idcolegio = document.getElementById('id-colegio').value;
    if (colegiatura != "") {

        var recuperado = JSON.parse(colegiatura); // Convertimos de vuelta a un objeto
        if (idcolegio != "") {
            recuperado = recuperado.map(obj => {
                if (obj.cont === Number(idcolegio)) {
                    obj.colegio = colegio;
                    obj.tipodoc = tipodoc;
                    obj.nrocol = nrocol;
                    obj.estado = estado;
                    obj.fechacol = fechacol;
                    obj.doccol = doccol;

                }
                return obj;
            });
        } else {
            if (recuperado.length > 0)
                cont = recuperado[recuperado.length - 1].cont + 1;
            else
                cont = 1;
            var nuevoObjeto = {
                cont: cont,
                colegio: colegio,
                tipodoc: tipodoc,
                nrocol: nrocol,
                estado: estado,
                fechacol: fechacol,
                doccol: doccol
            };
            recuperado.push(nuevoObjeto);
        }

        document.getElementById('colegiatura').value = JSON.stringify(recuperado);
    } else {
        document.getElementById('colegiatura').value = JSON.stringify([{
            cont: 1,
            colegio: colegio,
            tipodoc: tipodoc,
            nrocol: nrocol,
            estado: estado,
            fechacol: fechacol,
            doccol: doccol
        }]);

    }
    document.getElementById('nombre-colegio').value = "";
    document.getElementById('tipo-doc-col').value = "";
    document.getElementById('nro-col').value = "";
    document.getElementById('estado').value = "";
    document.getElementById('fecha-col').value = "";
    document.getElementById('doc-col').value = "";
    document.getElementById('id-colegio').value = "";
    document.getElementById("mensaje-col").innerHTML = "";
    construirTablaColegiatura();
}

function construirTablaColegiatura() {
    let tabla = '<table class="table">';
    tabla += `
        <thead>
          <tr>
            <th>Colegio</th>
            <th>Nro. Coleg.</th>
            <th>Desde</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
      `;
    var colegios = document.getElementById('colegiatura').value;
    if (colegios != "") {
        datos = JSON.parse(colegios);
        datos.forEach(dato => {
            tabla += `
          <tr>
            <td>${dato.colegio}</td>
            <td>${dato.nrocol}</td>
            <td>${dato.fechacol ? dato.fechacol.split('-').reverse().join('-') : ''}</td>
            <td>
                <a class="btn btn-outline-info btn-sm" onClick="editarColegiatura(${dato.cont})" >
                  <i class="fa fa-edit"></i>
                </a>
                <a class="btn btn-outline-danger btn-sm" onClick="anularColegiatura(${dato.cont})" >
                  <i class="fa fa-trash"></i>
                </a>

            </td>
          </tr>
        `;
        });

        tabla += '</tbody></table>';
        document.getElementById('tabla-colegiatura').innerHTML = tabla;
    }
    // Recorrer los datos y generar filas


    // Insertar la tabla en el contenedor

}

function anularColegiatura(cont) {
    var colegiatura = document.getElementById('colegiatura').value;
    if (colegiatura) {
        var recuperado = JSON.parse(colegiatura);
        recuperado = recuperado.filter(dato => dato.cont !== cont);
        document.getElementById('colegiatura').value = JSON.stringify(recuperado);
        construirTablaColegiatura();
    } else {
        document.getElementById('colegiatura').value = "";
        document.getElementById('tabla-colegiatura').innerHTML = '<div class="alert alert-light" role="alert">Sin datos</div>';
    }
}

function editarColegiatura(cont) {
    document.getElementById('nombre-colegio').value = "";
    document.getElementById('tipo-doc-col').value = "";
    document.getElementById('nro-col').value = "";
    document.getElementById('estado').value = "";
    document.getElementById('fecha-col').value = "";
    document.getElementById('doc-col').value = "";
    document.getElementById('id-colegio').value = "";

    var colegiatura = document.getElementById('colegiatura').value; // Obtenemos la cadena guardada
    if (colegiatura != "") {
        var recuperado = JSON.parse(colegiatura); // Convertimos de vuelta a un objeto
        recuperado = recuperado.map(obj => {
            if (obj.cont === cont) {
                document.getElementById('id-colegio').value = cont;
                document.getElementById('nombre-colegio').value = obj.colegio;
                document.getElementById('tipo-doc-col').value = obj.tipodoc;
                document.getElementById('nro-col').value = obj.nrocol;
                document.getElementById('estado').value = obj.estado;
                document.getElementById('fecha-col').value = obj.fechacol;
                //document.getElementById('doc-col').value = obj.doccol;
                if (obj.doccol && obj.doccol.trim() !== '') {
                    document.getElementById("mensaje-col").innerHTML =
                        '<a href="../repositories/' + obj.doccol + '" class="text-success" target="_blank"><i class="fa fa-eye" ></i>Ver documento</a>';
                } else {
                    document.getElementById("mensaje-col").innerHTML =
                        '<span class="text-muted">No se encontró ningún archivo</span>';
                }
                document.getElementById("doc-col").value = obj.doccol;
            }
            return obj;
        });
    }

}


//////////////////////////////////////
/////////IDIOMAS/////////////////////
//////////////////////////////////////
function guardarIdioma() {
    var idioma = document.getElementById('idioma').value;
    var lectura = document.getElementById('lectura').value;
    var habla = document.getElementById('habla').value;
    var escritura = document.getElementById('escritura').value;
    var tipodoc = document.getElementById('tipo-doc-idioma').value;
    var archivo = document.getElementById('doc-idioma').value;
    var cont = 0
    var idiomas = document.getElementById('idiomas').value; // Obtenemos la cadena guardada
    var ididioma = document.getElementById('id-idioma').value;
    if (idiomas != "") {
        var recuperado = JSON.parse(idiomas); // Convertimos de vuelta a un objeto
        if (recuperado.length > 0) {
            if (ididioma != "") {
                recuperado = recuperado.map(obj => {
                    if (obj.cont === Number(ididioma)) {
                        obj.idioma = idioma;
                        obj.lectura = lectura;
                        obj.habla = habla;
                        obj.escritura = escritura;
                        obj.tipodoc = tipodoc;
                        obj.archivo = archivo;
                    }
                    return obj;
                });
            } else {
                cont = recuperado[recuperado.length - 1].cont + 1;
                var nuevoObjeto = {
                    cont: cont,
                    idioma: idioma,
                    lectura: lectura,
                    habla: habla,
                    escritura: escritura,
                    tipodoc: tipodoc,
                    archivo: archivo
                };
                recuperado.push(nuevoObjeto);
            }
        } else {
            var nuevoObjeto = {
                cont: 1,
                idioma: idioma,
                lectura: lectura,
                habla: habla,
                escritura: escritura,
                tipodoc: tipodoc,
                archivo: archivo
            };
            recuperado.push(nuevoObjeto);
        }


        document.getElementById('idiomas').value = JSON.stringify(recuperado);

        construirTablaIdioma();
    } else {
        document.getElementById('idiomas').value = JSON.stringify([{
            cont: 1,
            idioma: idioma,
            lectura: lectura,
            habla: habla,
            escritura: escritura,
            tipodoc: tipodoc,
            archivo: archivo
        }]);
        construirTablaIdioma();
    }
    document.getElementById('idioma').value = "";
    document.getElementById('lectura').value = "";
    document.getElementById('habla').value = "";
    document.getElementById('escritura').value = "";
    document.getElementById('tipo-doc-idioma').value = "";
    document.getElementById('id-idioma').value = "";
    document.getElementById('doc-idioma').value = "";
    document.getElementById('mensaje-idioma').innerHTML = "";
}

function construirTablaIdioma() {
    let tabla = '<table class="table">';
    tabla += `
        <thead>
          <tr>
            <th>Idioma</th>
            <th>Nivel</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
      `;
    var recuperado = document.getElementById('idiomas').value;
    var datos = JSON.parse(recuperado);
    // Recorrer los datos y generar filas
    datos.forEach(dato => {
        tabla += `
          <tr>
            <td>${dato.idioma}</td>
            <td>${dato.nivel}</td>
            <td>
                <a class="btn btn-outline-info btn-sm" onClick="editarIdioma(${dato.cont})" >
                  <i class="fa fa-edit"></i>
                </a>
                <a class="btn btn-outline-danger btn-sm" onClick="anularIdioma(${dato.cont})" >
                  <i class="fa fa-trash"></i>
                </a>

            </td>
          </tr>
        `;
    });

    tabla += '</tbody></table>';
    //limpiar campos
    document.getElementById('idioma').value = "";
    // Insertar la tabla en el contenedor
    document.getElementById('tabla-idiomas').innerHTML = tabla;
}

function anularIdioma(cont) {
    var idiomas = document.getElementById('idiomas').value;
    if (idiomas) {
        var recuperado = JSON.parse(idiomas);
        recuperado = recuperado.filter(dato => dato.cont !== cont);
        document.getElementById('idiomas').value = JSON.stringify(recuperado);
        construirTablaIdioma(recuperado);
    } else {
        document.getElementById('idiomas').value = "";
        document.getElementById('tabla-idiomas').innerHTML = '<div class="alert alert-light" role="alert">Sin datos</div>';
    }
}

function editarIdioma(id) {
    var idiomas = document.getElementById('idiomas').value; // Obtenemos la cadena guardada
    if (idiomas != "") {
        var recuperado = JSON.parse(idiomas); // Convertimos de vuelta a un objeto
        recuperado = recuperado.map(obj => {
            if (obj.cont === id) {
                document.getElementById('id-idioma').value = id;
                document.getElementById('idioma').value = obj.idioma;
                document.getElementById('lectura').value = obj.lectura;
                document.getElementById('habla').value = obj.habla;
                document.getElementById('escritura').value = obj.escritura;
                document.getElementById('tipo-doc-idioma').value = obj.tipodoc;
                //document.getElementById("mensaje").innerHTML = "";
                if (obj.archivo && obj.archivo.trim() !== '') {
                    document.getElementById("mensaje-idioma").innerHTML =
                        '<a href="../repositories/' + obj.archivo + '" class="text-success" target="_blank"><i class="fa fa-eye" ></i>Ver documento</a>';
                } else {
                    document.getElementById("mensaje-idioma").innerHTML =
                        '<span class="text-muted">No se encontró ningún archivo</span>';
                }
                document.getElementById("doc-idioma").value = obj.archivo;
            }
            return obj;
        });
    }
}

/////////////////////////////////
//////EXPERIENCIA LABORAL//////
////////////////////////////////
function guardarExperiencia() {
    var tipo = document.getElementById('tipo-entidad').value;
    var entidad = document.getElementById('entidad').value;
    var area = document.getElementById('area-exp').value;
    var cargo = document.getElementById('cargo').value;
    var inicio = document.getElementById('fecha-ini-exp').value;
    var fin = document.getElementById('fecha-fin-exp').value;
    var tipodoc = document.getElementById('tipo-cert-exp').value;
    var nrodoc = document.getElementById('nrodoc').value;
    var archivo = document.getElementById('doc-exp').value;
    var cont = 0;
    var experiencia = document.getElementById("experiencia").value; // Obtenemos la cadena guardada
    var idexp = document.getElementById("id-experiencia").value;
    if (experiencia != "") {
        var recuperado = JSON.parse(experiencia); // Convertimos de vuelta a un objeto
        if (recuperado.length > 0) {
            if (idexp != "") {
                recuperado = recuperado.map(obj => {
                    if (obj.cont === Number(idexp)) {
                        obj.tipo = tipo;
                        obj.entidad = entidad;
                        obj.cargo = cargo;
                        obj.area = area;
                        obj.inicio = inicio;
                        obj.fin = fin;
                        obj.tipodoc = tipodoc;
                        obj.nrodoc = nrodoc;
                        obj.archivo = archivo;
                    }
                    return obj;
                });
            } else {
                cont = recuperado[recuperado.length - 1].cont + 1;
                var nuevoObjeto = {
                    cont: cont,
                    tipo: tipo,
                    entidad: entidad,
                    cargo: cargo,
                    area: area,
                    inicio: inicio,
                    fin: fin,
                    tipodoc: tipodoc,
                    nrodoc: nrodoc,
                    archivo: archivo
                };
                recuperado.push(nuevoObjeto);
            }

        } else {
            var nuevoObjeto = {
                cont: 1,
                tipo: tipo,
                entidad: entidad,
                cargo: cargo,
                area: area,
                inicio: inicio,
                fin: fin,
                tipodoc: tipodoc,
                nrodoc: nrodoc,
                archivo: archivo
            };
            recuperado.push(nuevoObjeto);
        }
        document.getElementById('experiencia').value = JSON.stringify(recuperado);
    } else {
        document.getElementById('experiencia').value = JSON.stringify([{
            cont: 1,
            tipo: tipo,
            entidad: entidad,
            cargo: cargo,
            area: area,
            inicio: inicio,
            fin: fin,
            tipodoc: tipodoc,
            nrodoc: nrodoc,
            archivo: archivo
        }]);
    }
    document.getElementById('tipo-entidad').value = "";
    document.getElementById('entidad').value = "";
    document.getElementById('cargo').value = "";
    document.getElementById('fecha-ini-exp').value = "";
    document.getElementById('fecha-fin-exp').value = "";
    document.getElementById('tipo-cert-exp').value = "";
    document.getElementById('nrodoc').value = "";
    document.getElementById('area-exp').value = "";
    document.getElementById('subidor-exp').value = "";
    document.getElementById("mensaje-exp").innerHTML = "";
    document.getElementById('id-experiencia').value = "";
    construirTablaExperiencia();
}

function construirTablaExperiencia() {
    var datos = JSON.parse(document.getElementById('experiencia').value);
    let tabla = '<table class="table">';
    tabla += `
    <thead>
      <tr>
        <th>Cargo</th>
        <th>Institución</th>
        <th>Tiempo</th>
        <th>Acción</th>
      </tr>
    </thead>
    <tbody>
  `;

    // Recorrer los datos y generar filas
    datos.forEach(dato => {
        var tiempo = diferenciaFechas(dato.inicio, dato.fin);
        tabla += `
      <tr>
        <td>${dato.cargo}</td>
        <td>${dato.entidad}</td>
        <td>${tiempo.años}A, ${tiempo.meses}M, ${tiempo.dias}D</td>
        <td>
            <a class="btn btn-outline-info btn-sm" onClick="editarExperiencia(${dato.cont})" >
              <i class="fa fa-edit"></i>
            </a>
            <a class="btn btn-outline-danger btn-sm" onClick="anularExperiencia(${dato.cont})" >
              <i class="fa fa-trash"></i>
            </a>

        </td>
      </tr>
    `;
    });

    tabla += '</tbody></table>';

    // Insertar la tabla en el contenedor
    document.getElementById('tabla-experiencia').innerHTML = tabla;
}

function anularExperiencia(cont) {
    var experiencia = document.getElementById("experiencia").value;
    if (experiencia) {
        var recuperado = JSON.parse(experiencia);
        recuperado = recuperado.filter(dato => dato.cont !== cont);
        document.getElementById("experiencia").value = JSON.stringify(recuperado);
        construirTablaExperiencia();
    } else {
        document.getElementById('tabla-experiencia').innerHTML = '<div class="alert alert-light" role="alert">Sin datos</div>';
    }
}

function editarExperiencia(cont) {
    var experiencia = document.getElementById('experiencia').value; // Obtenemos la cadena guardada
    if (experiencia != "") {
        var recuperado = JSON.parse(experiencia); // Convertimos de vuelta a un objeto
        recuperado = recuperado.map(obj => {
            if (obj.cont === cont) {
                document.getElementById('id-experiencia').value = cont;
                document.getElementById('tipo-entidad').value = obj.tipo;
                document.getElementById('entidad').value = obj.entidad;
                document.getElementById('cargo').value = obj.cargo;
                document.getElementById('area-exp').value = obj.area;
                document.getElementById('fecha-ini-exp').value = obj.inicio;
                document.getElementById('fecha-fin-exp').value = obj.fin;
                document.getElementById('tipo-cert-exp').value = obj.tipodoc;
                document.getElementById('nrodoc').value = obj.nrodoc;
                //document.getElementById("mensaje").innerHTML = "";
                if (obj.archivo && obj.archivo.trim() !== '') {
                    document.getElementById("mensaje-exp").innerHTML =
                        '<a href="../repositories/' + obj.archivo + '" class="text-success" target="_blank"><i class="fa fa-eye" ></i>Ver documento</a>';
                } else {
                    document.getElementById("mensaje-idioma").innerHTML =
                        '<span class="text-muted">No se encontró ningún archivo</span>';
                }
                document.getElementById("doc-exp").value = obj.archivo;
            }
            return obj;
        });
    }
}

/////////////////////////
////////ESTUDIOS/////////
//////////////////////////
function guardarEstudios() {
    var nivel = document.getElementById('nivel-educacion').value; // Obtiene el valor de la opción seleccionada
    var doc = document.getElementById('idtd-est').value; // Obtiene el valor de la opción seleccionada
    var institucion = document.getElementById('centroestudios').value;
    var especialidad = document.getElementById('especialidad').value;
    var fechaini = document.getElementById('fecha-ini-estudio').value;
    var fechafin = document.getElementById('fecha-fin-estudio').value;
    var archivo = document.getElementById('doc-est').value;
    var id = document.getElementById('id-estudio').value;
    var cont = 0
    var estudios = document.getElementById('estudios-rea').value; // Obtenemos la cadena guardada

    if (estudios != "") {
        if (id != "") {
            var recuperado = JSON.parse(estudios); // Convertimos de vuelta a un objeto
            recuperado = recuperado.map(obj => {
                if (obj.cont === Number(id)) {
                    obj.nivel = nivel;
                    obj.doc = doc;
                    obj.institucion = institucion;
                    obj.especialidad = especialidad;
                    obj.inicio = fechaini;
                    obj.fin = fechafin;
                    obj.archivo = archivo;
                }
                return obj;
            });

            document.getElementById('estudios-rea').value = JSON.stringify(recuperado);
            construirTablaEstudios(recuperado);
            document.getElementById('id-estudio').value = "";
            document.getElementById('nivel-educacion').value = "";
            document.getElementById('idtd-est').value = "";
            document.getElementById('centroestudios').value = "";
            document.getElementById('especialidad').value = "";
            document.getElementById('fecha-ini-estudio').value = "";
            document.getElementById('fecha-fin-estudio').value = "";
            document.getElementById("mensaje").innerHTML = "";
            document.getElementById("subidor").value = "";
        } else {
            var recuperado = JSON.parse(estudios); // Convertimos de vuelta a un objeto
            if (recuperado.length > 0)
                cont = recuperado[recuperado.length - 1].cont + 1;
            else
                cont = 1;

            var nuevoObjeto = { cont: cont, nivel: nivel, doc: doc, institucion: institucion, especialidad: especialidad, inicio: fechaini, fin: fechafin, archivo: archivo };
            recuperado.push(nuevoObjeto);

            document.getElementById('estudios-rea').value = JSON.stringify(recuperado);
            construirTablaEstudios(recuperado);
            document.getElementById('id-estudio').value = "";
            document.getElementById('nivel-educacion').value = "";
            document.getElementById('idtd-est').value = "";
            document.getElementById('centroestudios').value = "";
            document.getElementById('especialidad').value = "";
            document.getElementById('fecha-ini-estudio').value = "";
            document.getElementById('fecha-fin-estudio').value = "";
            document.getElementById("mensaje").innerHTML = "";
            document.getElementById("subidor").value = "";
        }

    } else {
        document.getElementById('estudios-rea').value = JSON.stringify([{ cont: 1, nivel: nivel, doc: doc, institucion: institucion, especialidad: especialidad, inicio: fechaini, fin: fechafin, archivo: archivo }]);
        construirTablaEstudios([{ cont: 1, nivel: nivel, doc: doc, institucion: institucion, especialidad: especialidad, inicio: fechaini, fin: fechafin, archivo: archivo }]);
        document.getElementById('tabla-estudios').innerHTML = tabla;

        document.getElementById('id-estudio').value = "";
        document.getElementById('nivel-educacion').value = "";
        document.getElementById('idtd-est').value = "";
        document.getElementById('centroestudios').value = "";
        document.getElementById('especialidad').value = "";
        document.getElementById('fecha-ini-estudio').value = "";
        document.getElementById('fecha-fin-estudio').value = "";
        document.getElementById("mensaje").innerHTML = "";
        document.getElementById("subidor").value = "";
    }
}

function construirTablaEstudios(datos) {
    let tabla = '<table class="table">';
    tabla += `
          <thead>
            <tr>
              <th>Nivel</th>
              <th>Institución</th>
              <th>Especialidad</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
        `;

    // Recorrer los datos y generar filas
    datos.forEach(dato => {
        tabla += `
            <tr>
              <td>${dato.nivel}</td>
              <td>${dato.institucion}</td>
              <td>${dato.especialidad}</td>
              <td>
                    <a class="btn btn-outline-info btn-sm" onClick="editarEstudios(${dato.cont})" >
                        <i class="fa fa-edit"></i>
                    </a>
                  <a class="btn btn-outline-danger btn-sm" onClick="anularEstudios(${dato.cont})" >
                    <i class="fa fa-trash"></i>
                  </a>

              </td>
            </tr>
          `;
    });

    tabla += '</tbody></table>';

    // Insertar la tabla en el contenedor
    document.getElementById('tabla-estudios').innerHTML = tabla;
}

function anularEstudios(cont) {
    var estudios = document.getElementById('estudios-rea').value;
    if (estudios) {
        var recuperado = JSON.parse(estudios);
        recuperado = recuperado.filter(dato => dato.cont !== cont);
        document.getElementById('estudios-rea').value = JSON.stringify(recuperado);
        construirTablaEstudios(recuperado);
    } else {
        document.getElementById('tabla-estudios').innerHTML = '<div class="alert alert-light" role="alert">Sin datos</div>';
    }
}

function editarEstudios(cont) {
    var estudios = document.getElementById('estudios-rea').value; // Obtenemos la cadena guardada
    if (estudios != "") {
        var recuperado = JSON.parse(estudios); // Convertimos de vuelta a un objeto
        recuperado = recuperado.map(obj => {
            if (obj.cont === cont) {
                document.getElementById('id-estudio').value = cont;
                document.getElementById('nivel-educacion').value = obj.nivel;
                document.getElementById('idtd-est').value = obj.doc;
                document.getElementById('centroestudios').value = obj.institucion;
                document.getElementById('especialidad').value = obj.especialidad;
                document.getElementById('fecha-ini-estudio').value = obj.inicio;
                document.getElementById('fecha-fin-estudio').value = obj.fin;
                //document.getElementById("mensaje").innerHTML = '<a href="../repositories/'+obj.archivo+'" target="_blank"> Ver</a>';
                if (obj.archivo && obj.archivo.trim() !== '') {
                    document.getElementById("mensaje").innerHTML =
                        '<a href="../repositories/' + obj.archivo + '" class="text-success" target="_blank"><i class="fa fa-eye" ></i>Ver documento</a>';
                } else {
                    document.getElementById("mensaje").innerHTML =
                        '<span class="text-muted">No se encontró ningún archivo</span>';
                }
                document.getElementById("doc-est").value = obj.archivo;
            }
            return obj;
        });
    }
}

//////edicion personal
function esFechaValida(fecha) {
    return !isNaN(Date.parse(fecha));
}

document.getElementById("subidor").addEventListener("change", function () {
    let archivo = this.files[0]; // Capturar el archivo seleccionado
    let formData = new FormData();
    formData.append("archivo", archivo.name);

    fetch("/subir-archivo", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            document.getElementById("mensaje").innerHTML = data.mensaje;
        })
        .catch(error => console.error("Error:", error));
});

/////////////////////////////////
//////ROTACIONES//////
////////////////////////////////
function guardarRotacion() {
    var destino = document.getElementById('unidad-destino').value;
    var nombredes = document.getElementById('unidad-destino').options[document.getElementById('unidad-destino').selectedIndex].text;
    var cargo = document.getElementById('cargo-destino').value;
    var nombrecar = document.getElementById('cargo-destino').options[document.getElementById('cargo-destino').selectedIndex].text;
    var descripcion = document.getElementById('descripcion-cargo').value;
    var inicio = document.getElementById('fecha-ini-rot').value;
    var docini = document.getElementById('idtd-ini-rot').value;
    var nroini = document.getElementById('nrodoc-ini-rot').value;
    var archivoini = document.getElementById('doc-ini-rot').value;
    var fin = document.getElementById('fecha-fin-rot').value;
    var docfin = document.getElementById('idtd-fin-rot').value;
    var nrofin = document.getElementById('nrodoc-fin-rot').value;
    var archivofin = document.getElementById('doc-fin-rot').value;
    var cont = 0;
    var rotaciones = document.getElementById("rotaciones").value; // Obtenemos la cadena guardada
    var idrot = document.getElementById("id-rotacion").value;
    if (rotaciones != "") {
        var recuperado = JSON.parse(rotaciones); // Convertimos de vuelta a un objeto
        if (recuperado.length > 0) {
            if (idrot != "") {
                recuperado = recuperado.map(obj => {
                    if (obj.cont === Number(idrot)) {
                        obj.cambio = 1;
                        obj.destino = destino;
                        obj.nombredes = nombredes;
                        obj.cargo = cargo;
                        obj.nombrecar = nombrecar;
                        obj.descripcion = descripcion;
                        obj.inicio = inicio;
                        obj.docini = docini;
                        obj.nroini = nroini;
                        obj.archivoini = archivoini;
                        obj.fin = fin;
                        obj.docfin = docfin;
                        obj.nrofin = nrofin;
                        obj.archivofin = archivofin;
                    }
                    return obj;
                });
            } else {
                if (recuperado.length > 0)
                    cont = recuperado[recuperado.length - 1].cont + 1;
                else
                    cont = 1;
                var nuevoObjeto = {
                    cont: cont,
                    id: 0,
                    cambio: 1,
                    destino: destino,
                    nombredes: nombredes,
                    cargo: cargo,
                    nombrecar: nombrecar,
                    descripcion: descripcion,
                    inicio: inicio,
                    docini: docini,
                    nroini: nroini,
                    archivoini: archivoini,
                    fin: fin,
                    docfin: docfin,
                    nrofin: nrofin,
                    archivofin: archivofin,
                };
                recuperado.push(nuevoObjeto);
            }

        } else {
            var nuevoObjeto = {
                cont: 1,
                id: 0,
                cambio: 1,
                destino: destino,
                nombredes: nombredes,
                cargo: cargo,
                nombrecar: nombrecar,
                descripcion: descripcion,
                inicio: inicio,
                docini: docini,
                nroini: nroini,
                archivoini: archivoini,
                fin: fin,
                docfin: docfin,
                nrofin: nrofin,
                archivofin: archivofin,
            };
            recuperado.push(nuevoObjeto);
        }
        document.getElementById('rotaciones').value = JSON.stringify(recuperado);
    } else {
        document.getElementById('rotaciones').value = JSON.stringify([{
            cont: 1,
            id: 0,
            cambio: 1,
            destino: destino,
            nombredes: nombredes,
            cargo: cargo,
            nombrecar: nombrecar,
            descripcion: descripcion,
            inicio: inicio,
            docini: docini,
            nroini: nroini,
            archivoini: archivoini,
            fin: fin,
            docfin: docfin,
            nrofin: nrofin,
            archivofin: archivofin,
        }]);
    }
    document.getElementById('unidad-destino').value = "";
    document.getElementById('cargo-destino').value = "";
    document.getElementById('fecha-ini-rot').value = "";
    document.getElementById('idtd-ini-rot').value = "";
    document.getElementById('nrodoc-ini-rot').value = "";
    document.getElementById('doc-ini-rot').value = "";
    document.getElementById('fecha-fin-rot').value = "";
    document.getElementById('idtd-fin-rot').value = "";
    document.getElementById('nrodoc-fin-rot').value = "";
    document.getElementById('doc-fin-rot').value = "";
    document.getElementById("id-rotacion").value = "";
    document.getElementById('descripcion-cargo').value = "";
    document.getElementById('mensaje-finrot').value = "";
    document.getElementById('mensaje-rot').value = "";
    construirTablaRotacion();
}

function construirTablaRotacion() {
    var datos = JSON.parse(document.getElementById('rotaciones').value);
    let tabla = '<table class="table">';
    tabla += `
    <thead>
      <tr>
        <th>Área</th>
        <th>Cargo</th>
        <th>Inicio</th>
        <th>Fin</th>
        <th>Acción</th>
      </tr>
    </thead>
    <tbody>
  `;

    // Recorrer los datos y generar filas
    datos.forEach(dato => {
        if (dato.cambio != 2) {
            tabla += `
                <tr>
                    <td>${dato.nombredes}</td>
                    <td>${dato.nombrecar}</td>
                    <td>${dato.inicio ? dato.inicio.split('-').reverse().join('-') : ''}</td>
                    <td>${dato.fin ? dato.fin.split('-').reverse().join('-') : ''}</td>
                    <td>
                        <a class="btn btn-outline-info btn-sm" onClick="editarRotacion(${dato.cont})" >
                        <i class="fa fa-edit"></i>
                        </a>
                        <a class="btn btn-outline-danger btn-sm" onClick="anularRotación(${dato.cont})" >
                        <i class="fa fa-trash"></i>
                        </a>

                    </td>
                </tr>
                `;
        }

    });

    tabla += '</tbody></table>';

    // Insertar la tabla en el contenedor
    document.getElementById('tabla-rotaciones').innerHTML = tabla;
}

function anularRotacion(cont) {
    var experiencia = document.getElementById("rotaciones").value;
    if (experiencia) {
        var recuperado = JSON.parse(experiencia);
        recuperado = recuperado.map(obj => {
            if (obj.cont === cont) {
                obj.cambio = 2;
            }
            return obj;
        });
        document.getElementById("rotaciones").value = JSON.stringify(recuperado);
        construirTablaRotacion();
    } else {
        document.getElementById('tabla-rotaciones').innerHTML = '<div class="alert alert-light" role="alert">Sin datos</div>';
    }
}

function editarRotacion(cont) {
    var experiencia = document.getElementById('rotaciones').value; // Obtenemos la cadena guardada
    if (experiencia != "") {
        var recuperado = JSON.parse(experiencia); // Convertimos de vuelta a un objeto
        recuperado = recuperado.map(obj => {
            if (obj.cont === cont) {
                document.getElementById('unidad-destino').value = obj.destino;
                document.getElementById('cargo-destino').value = obj.cargo;
                document.getElementById('fecha-ini-rot').value = obj.inicio;
                document.getElementById('idtd-ini-rot').value = obj.docini;
                document.getElementById('nrodoc-ini-rot').value = obj.nroini;
                document.getElementById('fecha-fin-rot').value = obj.fin;
                document.getElementById('idtd-fin-rot').value = obj.docfin;
                document.getElementById('nrodoc-fin-rot').value = obj.nrofin;
                document.getElementById('descripcion-cargo').value = obj.descripcion;
                document.getElementById("id-rotacion").value = cont;
                document.getElementById('doc-ini-rot').value = obj.archivoini;
                document.getElementById('doc-fin-rot').value = obj.archivofin;
                if (obj.archivoini && obj.archivoini.trim() !== '') {
                    document.getElementById("mensaje-rot").innerHTML =
                        '<a href="../repositories/' + obj.archivoini + '" class="text-success" target="_blank"><i class="fa fa-eye" ></i>Ver documento</a>';
                } else {
                    document.getElementById("mensaje-rot").innerHTML =
                        '<span class="text-muted">No se encontró ningún archivo</span>';
                }
                document.getElementById("doc-ini-rot").value = obj.archivoini;

                if (obj.archivofin && obj.archivofin.trim() !== '') {
                    document.getElementById("mensaje-finrot").innerHTML =
                        '<a href="../repositories/' + obj.archivofin + '" class="text-success" target="_blank"><i class="fa fa-eye" ></i>Ver documento</a>';
                } else {
                    document.getElementById("mensaje-finrot").innerHTML =
                        '<span class="text-muted">No se encontró ningún archivo</span>';
                }
                document.getElementById("doc-fin-rot").value = obj.archivofin;
            }
            return obj;
        });
    }
}


/////////////////////////////////
//////ENCARGATURAS//////
////////////////////////////////
function guardarEncargatura() {
    var destino = document.getElementById('unidad-encargada').value;
    var nombredes = document.getElementById('unidad-encargada').options[document.getElementById('unidad-encargada').selectedIndex].text;
    var cargo = document.getElementById('cargo-encargado').value;
    var nombrecar = document.getElementById('cargo-encargado').options[document.getElementById('cargo-encargado').selectedIndex].text;
    var descripcion = document.getElementById('descripcion-encargo').value;
    var docini = document.getElementById('idtd-enc').value;
    var nroini = document.getElementById('nrodoc-enc').value;
    var inicio = document.getElementById('ini-enc').value;
    var archivoini = document.getElementById('doc-ini-enc').value;
    var docfin = document.getElementById('idtd-fin-enc').value;
    var nrofin = document.getElementById('nrodoc-fin-enc').value;
    var fin = document.getElementById('fin-enc').value;
    var archivofin = document.getElementById('doc-fin-enc').value;
    var cont = 0;
    var encargaturas = document.getElementById("encargaturas").value; // Obtenemos la cadena guardada
    var idencargatura = document.getElementById("id-encargatura").value;
    if (encargaturas != "") {
        var recuperado = JSON.parse(encargaturas); // Convertimos de vuelta a un objeto
        if (recuperado.length > 0) {
            if (idencargatura != "") {
                recuperado = recuperado.map(obj => {
                    if (obj.cont === Number(idencargatura)) {
                        obj.cambio = 1;
                        obj.destino = destino;
                        obj.nombredes = nombredes;
                        obj.cargo = cargo;
                        obj.nombrecar = nombrecar;
                        obj.descripcion = descripcion;
                        obj.docini = docini;
                        obj.nroini = nroini;
                        obj.inicio = inicio;
                        obj.archivoini = archivoini;
                        obj.docfin = docfin;
                        obj.nrofin = nrofin;
                        obj.fin = fin;
                        obj.archivofin = archivofin;
                    }
                    return obj;
                });
            } else {
                if (recuperado.length > 0)
                    cont = recuperado[recuperado.length - 1].cont + 1;
                else
                    cont = 1;
                var nuevoObjeto = {
                    cont: cont,
                    id: 0,
                    cambio: 1,
                    destino: destino,
                    nombredes: nombredes,
                    cargo: cargo,
                    nombrecar: nombrecar,
                    descripcion: descripcion,
                    docini: docini,
                    nroini: nroini,
                    inicio: inicio,
                    archivoini: archivoini,
                    docfin: docfin,
                    nrofin: nrofin,
                    fin: fin,
                    archivofin: archivofin
                };
                recuperado.push(nuevoObjeto);
            }

        } else {
            var nuevoObjeto = {
                cont: 1,
                id: 0,
                cambio: 1,
                destino: destino,
                nombredes: nombredes,
                cargo: cargo,
                nombrecar: nombrecar,
                descripcion: descripcion,
                docini: docini,
                nroini: nroini,
                inicio: inicio,
                archivoini: archivoini,
                docfin: docfin,
                nrofin: nrofin,
                fin: fin,
                archivofin: archivofin
            };
            recuperado.push(nuevoObjeto);
        }
        document.getElementById('encargaturas').value = JSON.stringify(recuperado);
    } else {
        document.getElementById('encargaturas').value = JSON.stringify([{
            cont: 1,
            id: 0,
            cambio: 1,
            destino: destino,
            nombredes: nombredes,
            cargo: cargo,
            nombrecar: nombrecar,
            descripcion: descripcion,
            docini: docini,
            nroini: nroini,
            inicio: inicio,
            archivoini: archivoini,
            docfin: docfin,
            nrofin: nrofin,
            fin: fin,
            archivofin: archivofin
        }]);
    }
    document.getElementById('unidad-encargada').value = "";
    document.getElementById('cargo-encargado').value = "";
    document.getElementById('descripcion-encargo').value = "";
    document.getElementById('idtd-enc').value = "";
    document.getElementById('nrodoc-enc').value = "";
    document.getElementById('ini-enc').value = "";
    document.getElementById('doc-ini-enc').value = "";
    document.getElementById('idtd-fin-enc').value = "";
    document.getElementById('nrodoc-fin-enc').value = "";
    document.getElementById('fin-enc').value = "";
    document.getElementById('doc-fin-enc').value = "";
    document.getElementById("id-encargatura").value = "";
    construirTablaEncargatura();
}

function construirTablaEncargatura() {
    var datos = JSON.parse(document.getElementById('encargaturas').value);
    let tabla = '<table class="table">';
    tabla += `
    <thead>
      <tr>
        <th>Área</th>
        <th>Cargo</th>
        <th>Inicio</th>
        <th>Fin</th>
        <th>Acción</th>
      </tr>
    </thead>
    <tbody>
  `;

    // Recorrer los datos y generar filas
    datos.forEach(dato => {
        if (dato.cambio != 2) {
            tabla += `
                <tr>
                    <td>${dato.nombredes}</td>
                    <td>${dato.nombrecar}</td>
                    <td>${dato.inicio ? dato.inicio.split('-').reverse().join('-') : ''}</td>
                    <td>${dato.fin ? dato.fin.split('-').reverse().join('-') : ''}</td>
                    <td>
                        <a class="btn btn-outline-info btn-sm" onClick="editarEncargatura(${dato.cont})" >
                        <i class="fa fa-edit"></i>
                        </a>
                        <a class="btn btn-outline-danger btn-sm" onClick="anularEncargatura(${dato.cont})" >
                        <i class="fa fa-trash"></i>
                        </a>

                    </td>
                </tr>
                `;
        }

    });

    tabla += '</tbody></table>';

    // Insertar la tabla en el contenedor
    document.getElementById('tabla-encargatura').innerHTML = tabla;
}

function anularEncargatura(cont) {
    var experiencia = document.getElementById("encargaturas").value;
    if (experiencia) {
        var recuperado = JSON.parse(experiencia);
        recuperado = recuperado.map(obj => {
            if (obj.cont === cont) {
                obj.cambio = 2;
            }
            return obj;
        });
        document.getElementById("encargaturas").value = JSON.stringify(recuperado);
        construirTablaEncargatura();
    } else {
        document.getElementById('tabla-encargatura').innerHTML = '<div class="alert alert-light" role="alert">Sin datos</div>';
    }
}

function editarEncargatura(cont) {
    var experiencia = document.getElementById('encargaturas').value; // Obtenemos la cadena guardada
    if (experiencia != "") {
        var recuperado = JSON.parse(experiencia); // Convertimos de vuelta a un objeto
        recuperado = recuperado.map(obj => {
            if (obj.cont === cont) {
                document.getElementById('unidad-encargada').value = obj.destino;
                document.getElementById('cargo-encargado').value = obj.cargo;
                document.getElementById('descripcion-encargo').value = obj.descripcion;
                document.getElementById('idtd-enc').value = obj.docini;
                document.getElementById('nrodoc-enc').value = obj.nroini;
                document.getElementById('ini-enc').value = obj.inicio;
                //document.getElementById('archivo-enc').value = obj.archivoini;
                document.getElementById('idtd-fin-enc').value = obj.docfin;
                document.getElementById('nrodoc-fin-enc').value = obj.nrofin;
                document.getElementById('fin-enc').value = obj.fin;
                //document.getElementById('archivo-fin-enc').value = obj.archivofin;
                document.getElementById("id-encargatura").value = cont;

                if (obj.archivoini && obj.archivoini.trim() !== '') {
                    document.getElementById("mensaje-enc").innerHTML =
                        '<a href="../repositories/' + obj.archivoini + '" class="text-success" target="_blank"><i class="fa fa-eye" ></i>Ver documento</a>';
                } else {
                    document.getElementById("mensaje-enc").innerHTML =
                        '<span class="text-muted">No se encontró ningún archivo</span>';
                }
                document.getElementById("doc-ini-enc").value = obj.archivoini;

                if (obj.archivofin && obj.archivofin.trim() !== '') {
                    document.getElementById("mensaje-fin-enc").innerHTML =
                        '<a href="../repositories/' + obj.archivofin + '" class="text-success" target="_blank"><i class="fa fa-eye" ></i>Ver documento</a>';
                } else {
                    document.getElementById("mensaje-fin-enc").innerHTML =
                        '<span class="text-muted">No se encontró ningún archivo</span>';
                }
                document.getElementById("doc-fin-enc").value = obj.archivofin;
            }
            return obj;
        });
    }
}

/////////////////////////////////
//////VACACIONES//////
////////////////////////////////
function guardarVacaciones() {
    var periodo = document.getElementById('periodo-vac').value;
    var tipodoc = document.getElementById('tipodoc-vac').value;
    var nrodoc = document.getElementById('nrodoc-vac').value;
    var observaciones = document.getElementById('observaciones-vac').value;
    var suspension = document.getElementById('suspension-vac').value;
    var mes = document.getElementById('mes-vac').value;
    var inicio = document.getElementById('fecha-ini-vac').value;
    var fin = document.getElementById('fecha-fin-vac').value;
    var dias = document.getElementById('dias-vac').value;
    var archivo = document.getElementById('doc-vac').value;
    var cont = 0;
    var vacaciones = document.getElementById("vacaciones").value; // Obtenemos la cadena guardada
    var idvacacion = document.getElementById("id-vacacion").value;
    if (vacaciones != "") {
        var recuperado = JSON.parse(vacaciones); // Convertimos de vuelta a un objeto
        if (recuperado.length > 0) {
            if (idvacacion != "") {
                recuperado = recuperado.map(obj => {
                    if (obj.cont === Number(idvacacion)) {
                        obj.cambio = 1;
                        obj.periodo = periodo;
                        obj.tipodoc = tipodoc;
                        obj.nrodoc = nrodoc;
                        obj.observaciones = observaciones;
                        obj.suspension = suspension;
                        obj.mes = mes;
                        obj.inicio = inicio;
                        obj.fin = fin;
                        obj.dias = dias;
                        obj.archivo = archivo;
                    }
                    return obj;
                });
            } else {
                if (recuperado.length > 0)
                    cont = recuperado[recuperado.length - 1].cont + 1;
                else
                    cont = 1;
                var nuevoObjeto = {
                    cont: cont,
                    id: 0,
                    cambio: 1,
                    periodo: periodo,
                    tipodoc: tipodoc,
                    nrodoc: nrodoc,
                    observaciones: observaciones,
                    suspension: suspension,
                    mes: mes,
                    inicio: inicio,
                    fin: fin,
                    dias: dias,
                    archivo: archivo
                };
                recuperado.push(nuevoObjeto);
            }

        } else {
            var nuevoObjeto = {
                cont: 1,
                id: 0,
                cambio: 1,
                periodo: periodo,
                tipodoc: tipodoc,
                nrodoc: nrodoc,
                observaciones: observaciones,
                suspension: suspension,
                mes: mes,
                inicio: inicio,
                fin: fin,
                dias: dias,
                archivo: archivo
            };
            recuperado.push(nuevoObjeto);
        }
        document.getElementById('vacaciones').value = JSON.stringify(recuperado);
    } else {
        document.getElementById('vacaciones').value = JSON.stringify([{
            cont: 1,
            id: 0,
            cambio: 1,
            periodo: periodo,
            tipodoc: tipodoc,
            nrodoc: nrodoc,
            observaciones: observaciones,
            suspension: suspension,
            mes: mes,
            inicio: inicio,
            fin: fin,
            dias: dias,
            archivo: archivo
        }]);
    }

    document.getElementById('periodo-vac').value = "";
    document.getElementById('tipodoc-vac').value = "";
    document.getElementById('nrodoc-vac').value = "";
    document.getElementById('observaciones-vac').value = "";
    document.getElementById('suspension-vac').value = "";
    document.getElementById('mes-vac').value = "";
    document.getElementById('fecha-ini-vac').value = "";
    document.getElementById('fecha-fin-vac').value = "";
    document.getElementById('dias-vac').value = "";

    document.getElementById("id-vacacion").value = "";
    document.getElementById("doc-vac").value = "";
    document.getElementById("mensaje-vac").innerHTML = "";

    construirTablaVacaciones();
}

function construirTablaVacaciones() {
    var datos = JSON.parse(document.getElementById('vacaciones').value);
    let tabla = '<table class="table">';
    tabla += `
    <thead>
      <tr>
        <th>Periodo</th>
        <th>Inicio</th>
        <th>Fin</th>
        <th>Días</th>
        <th>Acción</th>
      </tr>
    </thead>
    <tbody>
  `;

    // Recorrer los datos y generar filas
    datos.forEach(dato => {
        if (dato.cambio != 2) {
            tabla += `
                <tr>
                    <td>${dato.periodo}</td>
                    <td>${dato.inicio ? dato.inicio.split('-').reverse().join('-') : ''}</td>
                    <td>${dato.fin ? dato.fin.split('-').reverse().join('-') : ''}</td>
                    <td>${dato.dias}</td>
                    <td>
                        <a class="btn btn-outline-info btn-sm" onClick="editarVacaciones(${dato.cont})" >
                        <i class="fa fa-edit"></i>
                        </a>
                        <a class="btn btn-outline-danger btn-sm" onClick="anularVacaciones(${dato.cont})" >
                        <i class="fa fa-trash"></i>
                        </a>

                    </td>
                </tr>
                `;
        }

    });

    tabla += '</tbody></table>';

    // Insertar la tabla en el contenedor
    document.getElementById('tabla-vacaciones').innerHTML = tabla;
}

function anularVacaciones(cont) {
    var experiencia = document.getElementById("vacaciones").value;
    if (experiencia) {
        var recuperado = JSON.parse(experiencia);
        recuperado = recuperado.map(obj => {
            if (obj.cont === cont) {
                obj.cambio = 2;
            }
            return obj;
        });
        document.getElementById("vacaciones").value = JSON.stringify(recuperado);
        construirTablaVacaciones();
    } else {
        document.getElementById('tabla-vacaciones').innerHTML = '<div class="alert alert-light" role="alert">Sin datos</div>';
    }
}

function editarVacaciones(cont) {
    var experiencia = document.getElementById('vacaciones').value; // Obtenemos la cadena guardada
    if (experiencia != "") {
        var recuperado = JSON.parse(experiencia); // Convertimos de vuelta a un objeto
        recuperado = recuperado.map(obj => {
            if (obj.cont === cont) {
                document.getElementById('periodo-vac').value = obj.periodo;
                document.getElementById('tipodoc-vac').value = obj.tipodoc;
                document.getElementById('nrodoc-vac').value = obj.nrodoc;
                document.getElementById('observaciones-vac').value = obj.observaciones;
                document.getElementById('suspension-vac').value = obj.suspension;
                document.getElementById('mes-vac').value = obj.mes;
                document.getElementById('fecha-ini-vac').value = obj.inicio;
                document.getElementById('fecha-fin-vac').value = obj.fin;
                document.getElementById('dias-vac').value = obj.dias;
                document.getElementById("id-vacacion").value = cont;
                if (obj.archivo && obj.archivo.trim() !== '') {
                    document.getElementById("mensaje-vac").innerHTML =
                        '<a href="../repositories/' + obj.archivo + '" class="text-success" target="_blank"><i class="fa fa-eye" ></i>Ver documento</a>';
                } else {
                    document.getElementById("mensaje-vac").innerHTML =
                        '<span class="text-muted">No se encontró ningún archivo</span>';
                }
                document.getElementById("doc-vac").value = obj.archivo;

            }
            return obj;
        });
    }
}

/////////////////////////////////
//////LICENCIAS//////
////////////////////////////////
function guardarLicencia() {
    var descripcion = document.getElementById('descripcion-licencia').value;
    var tipodoc = document.getElementById('tipodoc-lic').value;
    var nrodoc = document.getElementById('nrodoc-lic').value;
    var observaciones = document.getElementById('observaciones-lic').value;
    var inicio = document.getElementById('fecha-ini-lic').value;
    var dias = document.getElementById('dias-licencia').value;
    var meses = document.getElementById('meses-licencia').value;
    var agnos = document.getElementById('agnos-licencia').value;
    var fin = document.getElementById('fecha-fin-lic').value;
    var acuenta = document.getElementById('acuentavac').value;
    var congoce = document.getElementById('congoce').value;
    var archivo = document.getElementById('doc-lic').value;
    var cont = 0;
    var licencias = document.getElementById("licencias").value; // Obtenemos la cadena guardada
    var idlic = document.getElementById("id-licencia").value;
    if (licencias != "") {
        var recuperado = JSON.parse(licencias); // Convertimos de vuelta a un objeto
        if (recuperado.length > 0) {
            if (idlic != "") {
                recuperado = recuperado.map(obj => {
                    if (obj.cont === Number(idlic)) {
                        obj.cambio = 1;
                        obj.descripcion = descripcion;
                        obj.tipodoc = tipodoc;
                        obj.nrodoc = nrodoc;
                        obj.observaciones = observaciones;
                        obj.inicio = inicio;
                        obj.dias = dias;
                        obj.meses = meses;
                        obj.agnos = agnos;
                        obj.fin = fin;
                        obj.acuenta = acuenta;
                        obj.congoce = congoce;
                        obj.archivo = archivo;
                    }
                    return obj;
                });
            } else {
                if (recuperado.length > 0)
                    cont = recuperado[recuperado.length - 1].cont + 1;
                else
                    cont = 1;
                var nuevoObjeto = {
                    cont: cont,
                    id: 0,
                    cambio: 1,
                    descripcion: descripcion,
                    tipodoc: tipodoc,
                    nrodoc: nrodoc,
                    observaciones: observaciones,
                    inicio: inicio,
                    dias: dias,
                    meses: meses,
                    agnos: agnos,
                    fin: fin,
                    acuenta: acuenta,
                    congoce: congoce,
                    archivo: archivo
                };
                recuperado.push(nuevoObjeto);
            }

        } else {
            var nuevoObjeto = {
                cont: 1,
                id: 0,
                cambio: 1,
                descripcion: descripcion,
                tipodoc: tipodoc,
                nrodoc: nrodoc,
                observaciones: observaciones,
                inicio: inicio,
                dias: dias,
                meses: meses,
                agnos: agnos,
                fin: fin,
                acuenta: acuenta,
                congoce: congoce,
                archivo: archivo
            };
            recuperado.push(nuevoObjeto);
        }
        document.getElementById('licencias').value = JSON.stringify(recuperado);
    } else {
        document.getElementById('licencias').value = JSON.stringify([{
            cont: 1,
            id: 0,
            cambio: 1,
            descripcion: descripcion,
            tipodoc: tipodoc,
            nrodoc: nrodoc,
            observaciones: observaciones,
            inicio: inicio,
            dias: dias,
            meses: meses,
            agnos: agnos,
            fin: fin,
            acuenta: acuenta,
            congoce: congoce,
            archivo: archivo
        }]);
    }
    document.getElementById('descripcion-licencia').value = "";
    document.getElementById('tipodoc-lic').value = "";
    document.getElementById('nrodoc-lic').value = "";
    document.getElementById('observaciones-lic').value = "";
    document.getElementById('fecha-ini-lic').value = "";
    document.getElementById('dias-licencia').value = "";
    document.getElementById('meses-licencia').value = "";
    document.getElementById('agnos-licencia').value = "";
    document.getElementById('fecha-fin-lic').value = "";
    document.getElementById('acuentavac').value = "";
    document.getElementById('congoce').value = "";
    document.getElementById("id-licencia").value = "";
    document.getElementById("doc-lic").value = "";
    document.getElementById("mensaje-lic").innerHTML = "";
    construirTablaLicencia();
}

function construirTablaLicencia() {
    var datos = JSON.parse(document.getElementById('licencias').value);
    let tabla = '<table class="table"><caption class="text-start ">' +
        '<i class="fa fa-square me-2" style="color: #fff3cd;"></i>A cuenta de vacaciones</caption>';
    tabla += `
    <thead>
      <tr>
        <th>Descripción</th>

        <th>Inicio</th>
        <th>Días</th>
        <th>Fin</th>
        <th>Acción</th>
      </tr>
    </thead>
    <tbody>
  `;

    // Recorrer los datos y generar filas
    datos.forEach(dato => {
        if (dato.cambio != 2) {
            tabla += `
                 <tr class="${dato.acuenta == 1 ? 'table-warning' : ''}">
                    <td>${dato.descripcion}</td>
                    <td>${dato.inicio ? dato.inicio.split('-').reverse().join('-') : ''}</td>
                    <td>${dato.fin ? dato.fin.split('-').reverse().join('-') : ''}</td>
                    <td>${dato.dias}D ${dato.meses}M ${dato.agnos}A</td>
                    
                    <td>
                        <a class="btn btn-outline-info btn-sm" onClick="editarLicencia(${dato.cont})" >
                        <i class="fa fa-edit"></i>
                        </a>
                        <a class="btn btn-outline-danger btn-sm" onClick="anularLicencia(${dato.cont})" >
                        <i class="fa fa-trash"></i>
                        </a>

                    </td>
                </tr>
                `;
        }

    });

    tabla += '</tbody></table>';

    // Insertar la tabla en el contenedor
    document.getElementById('tabla-licencias').innerHTML = tabla;
}

function anularLicencia(cont) {
    var licencias = document.getElementById("licencias").value;
    if (licencias != "") {
        var recuperado = JSON.parse(licencias);
        recuperado = recuperado.map(obj => {
            if (obj.cont === cont) {
                obj.cambio = 2;
            }
            return obj;
        });
        document.getElementById("licencias").value = JSON.stringify(recuperado);
        construirTablaLicencia();
    } else {
        document.getElementById('tabla-licencias').innerHTML = '<div class="alert alert-light" role="alert">Sin datos</div>';
    }
}

function editarLicencia(cont) {
    var licencias = document.getElementById('licencias').value; // Obtenemos la cadena guardada
    if (licencias != "") {
        var recuperado = JSON.parse(licencias); // Convertimos de vuelta a un objeto
        recuperado = recuperado.map(obj => {
            if (obj.cont === cont) {
                document.getElementById('descripcion-licencia').value = obj.descripcion;
                document.getElementById('tipodoc-lic').value = obj.tipodoc;
                document.getElementById('nrodoc-lic').value = obj.nrodoc;
                document.getElementById('observaciones-lic').value = obj.observaciones;
                document.getElementById('fecha-ini-lic').value = obj.inicio;
                document.getElementById('dias-licencia').value = obj.dias;
                document.getElementById('meses-licencia').value = obj.meses;
                document.getElementById('agnos-licencia').value = obj.agnos;
                document.getElementById('fecha-fin-lic').value = obj.fin;
                document.getElementById('acuentavac').value = obj.acuenta;
                document.getElementById('congoce').value = obj.congoce;
                document.getElementById("id-licencia").value = cont;

                if (obj.archivo && obj.archivo.trim() !== '') {
                    document.getElementById("mensaje-lic").innerHTML =
                        '<a href="../repositories/' + obj.archivo + '" class="text-success" target="_blank"><i class="fa fa-eye" ></i>Ver documento</a>';
                } else {
                    document.getElementById("mensaje-lic").innerHTML =
                        '<span class="text-muted">No se encontró ningún archivo</span>';
                }
                document.getElementById("doc-lic").value = obj.archivo;

            }
            return obj;
        });
    }
}

/////////////////////////////////
//////PERMISOS//////
////////////////////////////////
function guardarPermiso() {
    var descripcion = document.getElementById('motivo-per').value;
    var tipodoc = document.getElementById('tipodoc-per').value;
    var nrodoc = document.getElementById('nrodoc-per').value;
    var observaciones = document.getElementById('observaciones-per').value;
    var inicio = document.getElementById('fecha-ini-per').value;
    var dias = document.getElementById('dias-permiso').value;
    var meses = document.getElementById('meses-permiso').value;
    var agnos = document.getElementById('agnos-permiso').value;
    var fin = document.getElementById('fecha-fin-per').value;
    var acuenta = document.getElementById('acuentavac-per').value;
    var congoce = document.getElementById('congoce-per').value;
    var periodo = document.getElementById('periodo-permiso').value;
    var archivo = document.getElementById('doc-per').value;
    var cont = 0;
    var permisos = document.getElementById("permisos").value; // Obtenemos la cadena guardada
    var idlic = document.getElementById("id-permiso").value;
    if (permisos != "") {
        var recuperado = JSON.parse(permisos); // Convertimos de vuelta a un objeto
        if (recuperado.length > 0) {
            if (idlic != "") {
                recuperado = recuperado.map(obj => {
                    if (obj.cont === Number(idlic)) {
                        obj.cambio = 1;
                        obj.descripcion = descripcion;
                        obj.tipodoc = tipodoc;
                        obj.nrodoc = nrodoc;
                        obj.observaciones = observaciones;
                        obj.inicio = inicio;
                        obj.dias = dias;
                        obj.meses = meses;
                        obj.agnos = agnos;
                        obj.fin = fin;
                        obj.acuenta = acuenta;
                        obj.congoce = congoce;
                        obj.periodo = periodo;
                        obj.archivo = archivo;
                    }
                    return obj;
                });
            } else {
                if (recuperado.length > 0)
                    cont = recuperado[recuperado.length - 1].cont + 1;
                else
                    cont = 1;
                var nuevoObjeto = {
                    cont: cont,
                    id: 0,
                    cambio: 1,
                    descripcion: descripcion,
                    tipodoc: tipodoc,
                    nrodoc: nrodoc,
                    observaciones: observaciones,
                    inicio: inicio,
                    dias: dias,
                    meses: meses,
                    agnos: agnos,
                    fin: fin,
                    acuenta: acuenta,
                    congoce: congoce,
                    periodo: periodo,
                    archivo: archivo
                };
                recuperado.push(nuevoObjeto);
            }

        } else {
            var nuevoObjeto = {
                cont: 1,
                id: 0,
                cambio: 1,
                descripcion: descripcion,
                tipodoc: tipodoc,
                nrodoc: nrodoc,
                observaciones: observaciones,
                inicio: inicio,
                dias: dias,
                meses: meses,
                agnos: agnos,
                fin: fin,
                acuenta: acuenta,
                congoce: congoce,
                periodo: periodo,
                archivo: archivo
            };
            recuperado.push(nuevoObjeto);
        }
        document.getElementById('permisos').value = JSON.stringify(recuperado);
    } else {
        document.getElementById('permisos').value = JSON.stringify([{
            cont: 1,
            id: 0,
            cambio: 1,
            descripcion: descripcion,
            tipodoc: tipodoc,
            nrodoc: nrodoc,
            observaciones: observaciones,
            inicio: inicio,
            dias: dias,
            meses: meses,
            agnos: agnos,
            fin: fin,
            acuenta: acuenta,
            congoce: congoce,
            periodo: periodo,
            archivo: archivo
        }]);
    }
    document.getElementById('motivo-per').value = "";
    document.getElementById('tipodoc-per').value = "";
    document.getElementById('nrodoc-per').value = "";
    document.getElementById('observaciones-per').value = "";
    document.getElementById('fecha-ini-per').value = "";
    document.getElementById('dias-permiso').value = "";
    document.getElementById('meses-permiso').value = "";
    document.getElementById('agnos-permiso').value = "";
    document.getElementById('fecha-fin-per').value = "";
    document.getElementById('acuentavac-per').value = "";
    document.getElementById('congoce-per').value = "";
    document.getElementById("periodo-permiso").value = "";
    document.getElementById("id-permiso").value = "";
    document.getElementById("doc-per").value = "";
    document.getElementById("mensaje-per").innerHTML = "";
    construirTablaPermiso();
}

function construirTablaPermiso() {
    var datos = JSON.parse(document.getElementById('permisos').value);
    let tabla = '<table class="table"><caption class="text-start ">' +
        '<i class="fa fa-square me-2" style="color: #fff3cd;"></i>A cuenta de vacaciones</caption>';
    tabla += `
    <thead>
      <tr>
        <th>Descripción</th>
        <th>Periodo</th>
        <th>Inicio</th>
        <th>Fin</th>
        <th>Días</th>
        <th>Acción</th>
      </tr>
    </thead>
    <tbody>
  `;

    // Recorrer los datos y generar filas
    datos.forEach(dato => {
        if (dato.cambio != 2) {
            tabla += `
                <tr class="${dato.acuenta == 1 ? 'table-warning' : ''}">
                    <td>${dato.descripcion}</td>
                    <td>${dato.periodo}</td>
                    <td>${dato.inicio ? dato.inicio.split('-').reverse().join('-') : ''}</td>
                    <td>${dato.fin ? dato.fin.split('-').reverse().join('-') : ''}</td>
                    <td>${dato.dias}</td>
                    <td>
                        <a class="btn btn-outline-info btn-sm" onClick="editarPermiso(${dato.cont})" >
                        <i class="fa fa-edit"></i>
                        </a>
                        <a class="btn btn-outline-danger btn-sm" onClick="anularPermiso(${dato.cont})" >
                        <i class="fa fa-trash"></i>
                        </a>

                    </td>
                </tr>
                `;
        }

    });

    tabla += '</tbody></table>';

    // Insertar la tabla en el contenedor
    document.getElementById('tabla-permisos').innerHTML = tabla;
}

function anularPermiso(cont) {
    var permisos = document.getElementById("permisos").value;
    if (permisos != "") {
        var recuperado = JSON.parse(permisos);
        recuperado = recuperado.map(obj => {
            if (obj.cont === cont) {
                obj.cambio = 2;
            }
            return obj;
        });
        document.getElementById("permisos").value = JSON.stringify(recuperado);
        construirTablaPermiso();
    } else {
        document.getElementById('tabla-permisos').innerHTML = '<div class="alert alert-light" role="alert">Sin datos</div>';
    }
}

function editarPermiso(cont) {
    var permisos = document.getElementById('permisos').value; // Obtenemos la cadena guardada
    if (permisos != "") {
        var recuperado = JSON.parse(permisos); // Convertimos de vuelta a un objeto
        recuperado = recuperado.map(obj => {
            if (obj.cont === cont) {
                document.getElementById('motivo-per').value = obj.descripcion;
                document.getElementById('tipodoc-per').value = obj.tipodoc;
                document.getElementById('nrodoc-per').value = obj.nrodoc;
                document.getElementById('observaciones-per').value = obj.observaciones;
                document.getElementById('fecha-ini-per').value = obj.inicio;
                document.getElementById('dias-permiso').value = obj.dias;
                document.getElementById('meses-permiso').value = obj.meses;
                document.getElementById('agnos-permiso').value = obj.agnos;
                document.getElementById('fecha-fin-per').value = obj.fin;
                document.getElementById('acuentavac-per').value = obj.acuenta;
                document.getElementById('congoce-per').value = obj.congoce;
                document.getElementById("periodo-permiso").value = obj.periodo;
                document.getElementById("id-permiso").value = cont;

                if (obj.archivo && obj.archivo.trim() !== '') {
                    document.getElementById("mensaje-per").innerHTML =
                        '<a href="../repositories/' + obj.archivo + '" class="text-success" target="_blank"><i class="fa fa-eye" ></i>Ver documento</a>';
                } else {
                    document.getElementById("mensaje-per").innerHTML =
                        '<span class="text-muted">No se encontró ningún archivo</span>';
                }
                document.getElementById("doc-per").value = obj.archivo;

            }
            return obj;
        });
    }
}

/////////////////////////////////
//////COMPENSACIONES//////
////////////////////////////////
function guardarCompensacion() {
    var tipo = document.getElementById('tipo-compensacion').value;
    var tipodoc = document.getElementById('tipodoc-com').value;
    var nrodoc = document.getElementById('nrodoc-com').value;
    var descripcion = document.getElementById('descripcion-com').value;
    var inicio = document.getElementById('fecha-ini-comp').value;
    var dias = document.getElementById('dias-comp').value;
    var fin = document.getElementById('fecha-fin-comp').value;
    var archivo = document.getElementById('doc-comp').value;
    var cont = 0;
    var compensaciones = document.getElementById("compensaciones").value; // Obtenemos la cadena guardada
    var idcom = document.getElementById("id-compensacion").value;
    if (compensaciones != "") {
        var recuperado = JSON.parse(compensaciones); // Convertimos de vuelta a un objeto
        if (recuperado.length > 0) {
            if (idcom != "") {
                recuperado = recuperado.map(obj => {
                    if (obj.cont === Number(idcom)) {
                        obj.cambio = 1;
                        obj.tipo = tipo;
                        obj.tipodoc = tipodoc;
                        obj.nrodoc = nrodoc;
                        obj.descripcion = descripcion;
                        obj.inicio = inicio;
                        obj.dias = dias;
                        obj.fin = fin;
                        obj.archivo = archivo;
                    }
                    return obj;
                });
            } else {
                if (recuperado.length > 0)
                    cont = recuperado[recuperado.length - 1].cont + 1;
                else
                    cont = 1;
                var nuevoObjeto = {
                    cont: cont,
                    id: 0,
                    cambio: 1,
                    tipo: tipo,
                    tipodoc: tipodoc,
                    nrodoc: nrodoc,
                    descripcion: descripcion,
                    inicio: inicio,
                    dias: dias,
                    fin: fin,
                    archivo: archivo,
                };
                recuperado.push(nuevoObjeto);
            }

        } else {
            var nuevoObjeto = {
                cont: 1,
                id: 0,
                cambio: 1,
                tipo: tipo,
                tipodoc: tipodoc,
                nrodoc: nrodoc,
                descripcion: descripcion,
                inicio: inicio,
                dias: dias,
                fin: fin,
                archivo: archivo,
            };
            recuperado.push(nuevoObjeto);
        }
        document.getElementById('compensaciones').value = JSON.stringify(recuperado);
    } else {
        document.getElementById('compensaciones').value = JSON.stringify([{
            cont: 1,
            id: 0,
            cambio: 1,
            tipo: tipo,
            tipodoc: tipodoc,
            nrodoc: nrodoc,
            descripcion: descripcion,
            inicio: inicio,
            dias: dias,
            fin: fin,
            archivo: archivo,
        }]);
    }
    document.getElementById('tipo-compensacion').value = "";
    document.getElementById('tipodoc-com').value = "";
    document.getElementById('nrodoc-com').value = "";
    document.getElementById('descripcion-com').value = "";
    document.getElementById('fecha-ini-comp').value = "";
    document.getElementById('dias-comp').value = "";
    document.getElementById('fecha-fin-comp').value = "";
    document.getElementById("id-compensacion").value = "";
    document.getElementById("doc-comp").value = "";
    document.getElementById("mensaje-comp").innerHTML = "";
    construirTablaCompensacion();
}

function construirTablaCompensacion() {
    var datos = JSON.parse(document.getElementById('compensaciones').value);
    let tabla = '<table class="table">';
    tabla += `
    <thead>
      <tr>
        <th>Descripción</th>

        <th>Inicio</th>
        <th>Fin</th>
        <th>Tiempo</th>
        <th>Acción</th>
      </tr>
    </thead>
    <tbody>
  `;

    // Recorrer los datos y generar filas
    datos.forEach(dato => {
        if (dato.cambio != 2) {
            tabla += `
                <tr>
                    <td>${dato.descripcion}</td>
                
                    <td>${dato.inicio ? dato.inicio.split('-').reverse().join('-') : ''}</td>
                    <td>${dato.fin ? dato.fin.split('-').reverse().join('-') : ''}</td>
                    <td>${dato.dias}</td>
                    <td>
                        <a class="btn btn-outline-info btn-sm" onClick="editarCompensacion(${dato.cont})" >
                        <i class="fa fa-edit"></i>
                        </a>
                        <a class="btn btn-outline-danger btn-sm" onClick="anularCompensacion(${dato.cont})" >
                        <i class="fa fa-trash"></i>
                        </a>

                    </td>
                </tr>
                `;
        }

    });

    tabla += '</tbody></table>';

    // Insertar la tabla en el contenedor
    document.getElementById('tabla-compensaciones').innerHTML = tabla;
}

function anularCompensacion(cont) {
    var permisos = document.getElementById("compensaciones").value;
    if (permisos != "") {
        var recuperado = JSON.parse(permisos);
        recuperado = recuperado.map(obj => {
            if (obj.cont === cont) {
                obj.cambio = 2;
            }
            return obj;
        });
        document.getElementById("compensaciones").value = JSON.stringify(recuperado);
        construirTablaCompensacion();
    } else {
        document.getElementById('tabla-compensaciones').innerHTML = '<div class="alert alert-light" role="alert">Sin datos</div>';
    }
}

function editarCompensacion(cont) {
    var permisos = document.getElementById('compensaciones').value; // Obtenemos la cadena guardada
    if (permisos != "") {
        var recuperado = JSON.parse(permisos); // Convertimos de vuelta a un objeto
        recuperado = recuperado.map(obj => {
            if (obj.cont === cont) {
                document.getElementById('tipo-compensacion').value = obj.tipo;
                document.getElementById('tipodoc-com').value = obj.tipodoc;
                document.getElementById('nrodoc-com').value = obj.nrodoc;
                document.getElementById('descripcion-com').value = obj.descripcion;
                document.getElementById('fecha-ini-comp').value = obj.inicio;
                document.getElementById('dias-comp').value = obj.dias;
                document.getElementById('fecha-fin-comp').value = obj.fin;

                document.getElementById('id-compensacion').value = cont;

                if (obj.archivo && obj.archivo.trim() !== '') {
                    document.getElementById("mensaje-comp").innerHTML =
                        '<a href="../repositories/' + obj.archivo + '" class="text-success" target="_blank"><i class="fa fa-eye" ></i>Ver documento</a>';
                } else {
                    document.getElementById("mensaje-comp").innerHTML =
                        '<span class="text-muted">No se encontró ningún archivo</span>';
                }
                document.getElementById("doc-comp").value = obj.archivo;

            }
            return obj;
        });
    }
}

/////////////////////////////////
//////RECONOCIMIENTOS//////
////////////////////////////////
function guardarReconocimiento() {
    var descripcion = document.getElementById('descripcion-recon').value;
    var tipodoc = document.getElementById('tipodoc-recon').value;
    var nrodoc = document.getElementById('nrodoc-recon').value;
    var forma = document.querySelector('input[name="tipo-recon"]:checked').value;
    var fecharecon = document.getElementById('fecha-recon').value;
    var inicio = document.getElementById('fecha-ini-recon').value;
    var fin = document.getElementById('fecha-fin-recon').value;
    var archivo = document.getElementById('doc-rec').value;
    var cont = 0;
    var reconocimientos = document.getElementById("reconocimientos").value; // Obtenemos la cadena guardada
    var idrecon = document.getElementById("id-reconocimiento").value;
    if (reconocimientos != "") {
        var recuperado = JSON.parse(reconocimientos); // Convertimos de vuelta a un objeto
        if (recuperado.length > 0) {
            if (idrecon != "") {
                recuperado = recuperado.map(obj => {
                    if (obj.cont === Number(idrecon)) {
                        obj.cambio = 1;
                        obj.forma = forma;
                        obj.descripcion = descripcion;
                        obj.tipodoc = tipodoc;
                        obj.nrodoc = nrodoc;
                        obj.inicio = inicio;
                        obj.fin = fin;
                        obj.fecharecon = fecharecon;
                        obj.archivo = archivo;
                    }
                    return obj;
                });
            } else {
                if (recuperado.length > 0)
                    cont = recuperado[recuperado.length - 1].cont + 1;
                else
                    cont = 1;
                var nuevoObjeto = {
                    cont: cont,
                    id: 0,
                    cambio: 1,
                    forma: forma,
                    descripcion: descripcion,
                    tipodoc: tipodoc,
                    nrodoc: nrodoc,
                    fecharecon: fecharecon,
                    inicio: inicio,
                    fin: fin,
                    archivo: archivo
                };
                recuperado.push(nuevoObjeto);
            }

        } else {
            var nuevoObjeto = {
                cont: 1,
                id: 0,
                cambio: 1,
                forma: forma,
                descripcion: descripcion,
                tipodoc: tipodoc,
                nrodoc: nrodoc,
                fecharecon: fecharecon,
                inicio: inicio,
                fin: fin,
                archivo: archivo
            };
            recuperado.push(nuevoObjeto);
        }
        document.getElementById('reconocimientos').value = JSON.stringify(recuperado);
    } else {
        document.getElementById('reconocimientos').value = JSON.stringify([{
            cont: 1,
            id: 0,
            cambio: 1,
            forma: forma,
            descripcion: descripcion,
            tipodoc: tipodoc,
            nrodoc: nrodoc,
            fecharecon: fecharecon,
            inicio: inicio,
            fin: fin,
            archivo: archivo
        }]);
    }
    document.getElementById('descripcion-recon').value = "";
    document.getElementById('tipodoc-recon').value = "";
    document.getElementById('nrodoc-recon').value = "";
    document.getElementById('fecha-recon').value = "";
    document.getElementById('fecha-ini-recon').value = "";
    document.getElementById('fecha-fin-recon').value = "";
    document.getElementById('doc-rec').value = "";
    document.getElementById('mensaje-rec').innerHTML = "";
    document.getElementById("id-reconocimiento").value = "";
    construirTablaReconocimiento();
}

function construirTablaReconocimiento() {
    var datos = JSON.parse(document.getElementById('reconocimientos').value);
    let tabla = '<table class="table">';
    tabla += `
    <thead>
      <tr>
        <th>Descripción</th>

        <th>Inicio</th>
        <th>Fin</th>
        <th>Acción</th>
      </tr>
    </thead>
    <tbody>
  `;

    // Recorrer los datos y generar filas
    datos.forEach(dato => {
        if (dato.cambio != 2) {
            tabla += `
                <tr>
                    <td>${dato.descripcion}</td>
                    <td>${dato.inicio ? dato.inicio.split('-').reverse().join('-') : ''}</td>
                    <td>${dato.fin ? dato.fin.split('-').reverse().join('-') : ''}</td>
                    
                    <td>
                        <a class="btn btn-outline-info btn-sm" onClick="editarReconocimiento(${dato.cont})" >
                        <i class="fa fa-edit"></i>
                        </a>
                        <a class="btn btn-outline-danger btn-sm" onClick="anularReconocimiento(${dato.cont})" >
                        <i class="fa fa-trash"></i>
                        </a>

                    </td>
                </tr>
                `;
        }

    });

    tabla += '</tbody></table>';

    // Insertar la tabla en el contenedor
    document.getElementById('tabla-reconocimientos').innerHTML = tabla;
}

function anularReconocimiento(cont) {
    var permisos = document.getElementById("reconocimientos").value;
    if (permisos != "") {
        var recuperado = JSON.parse(permisos);
        recuperado = recuperado.map(obj => {
            if (obj.cont === cont) {
                obj.cambio = 2;
            }
            return obj;
        });
        document.getElementById("reconocimientos").value = JSON.stringify(recuperado);
        construirTablaReconocimiento();
    } else {
        document.getElementById('tabla-reconocimientos').innerHTML = '<div class="alert alert-light" role="alert">Sin datos</div>';
    }
}

function editarReconocimiento(cont) {
    var permisos = document.getElementById('reconocimientos').value; // Obtenemos la cadena guardada
    if (permisos != "") {
        var recuperado = JSON.parse(permisos); // Convertimos de vuelta a un objeto
        recuperado = recuperado.map(obj => {
            if (obj.cont === cont) {
                document.getElementById('descripcion-recon').value = obj.descripcion;
                document.getElementById('tipodoc-recon').value = obj.tipodoc;
                document.getElementById('nrodoc-recon').value = obj.nrodoc;
                document.getElementById('fecha-recon').value = obj.fecharecon;
                document.getElementById('fecha-ini-recon').value = obj.inicio;
                document.getElementById('fecha-fin-recon').value = obj.fin;
                document.getElementById("id-reconocimiento").value = cont;
                if (obj.forma == 1) {
                    document.getElementById("radioDefault2").checked = true;
                    document.getElementById('fecha-ini-recon').disabled = false;
                    document.getElementById('fecha-fin-recon').disabled = false;
                } else {
                    document.getElementById("radioDefault1").checked = true;
                    document.getElementById('fecha-ini-recon').disabled = true;
                    document.getElementById('fecha-fin-recon').disabled = true;
                }

                if (obj.archivo && obj.archivo.trim() !== '') {
                    document.getElementById("mensaje-rec").innerHTML =
                        '<a href="../repositories/' + obj.archivo + '" class="text-success" target="_blank"><i class="fa fa-eye" ></i>Ver documento</a>';
                } else {
                    document.getElementById("mensaje-rec").innerHTML =
                        '<span class="text-muted">No se encontró ningún archivo</span>';
                }
                document.getElementById("doc-rec").value = obj.archivo;

            }
            return obj;
        });
    }
}

/////////////////////////////////
//////SANCIONES//////
////////////////////////////////
function guardarSanciones() {
    var motivo = document.getElementById('motivo-san').value;
    var tipodoc = document.getElementById('tipodoc-san').value;
    var nrodoc = document.getElementById('nrodoc-san').value;
    var fechadoc = document.getElementById('fechadoc-san').value;
    var dias = document.getElementById('dias-san').value;
    var inicio = document.getElementById('fecha-ini-san').value;
    var fin = document.getElementById('fecha-fin-san').value;
    var archivo = document.getElementById('doc-san').value;
    const radios = document.getElementsByName('tipo-sancion');
    let tiposan = null;

    radios.forEach(radio => {
        if (radio.checked) {
            tiposan = radio.value;
        }
    });
    var cont = 0;
    var sanciones = document.getElementById("sanciones").value; // Obtenemos la cadena guardada
    var idsancion = document.getElementById("id-sancion").value;
    if (sanciones != "") {
        var recuperado = JSON.parse(sanciones); // Convertimos de vuelta a un objeto
        if (recuperado.length > 0) {
            if (idsancion != "") {
                recuperado = recuperado.map(obj => {
                    if (obj.cont === Number(idsancion)) {
                        obj.cambio = 1;
                        obj.motivo = motivo;
                        obj.tipodoc = tipodoc;
                        obj.nrodoc = nrodoc;
                        obj.fechadoc = fechadoc;
                        obj.dias = dias;
                        obj.inicio = inicio;
                        obj.fin = fin;
                        obj.tiposan = tiposan;
                        obj.archivo = archivo;
                    }
                    return obj;
                });
            } else {
                if (recuperado.length > 0)
                    cont = recuperado[recuperado.length - 1].cont + 1;
                else
                    cont = 1;
                var nuevoObjeto = {
                    cont: cont,
                    id: 0,
                    cambio: 1,
                    motivo: motivo,
                    tipodoc: tipodoc,
                    nrodoc: nrodoc,
                    fechadoc: fechadoc,
                    dias: dias,
                    inicio: inicio,
                    fin: fin,
                    tiposan: tiposan,
                    archivo: archivo
                };
                recuperado.push(nuevoObjeto);
            }

        } else {
            var nuevoObjeto = {
                cont: 1,
                id: 0,
                cambio: 1,
                motivo: motivo,
                tipodoc: tipodoc,
                nrodoc: nrodoc,
                fechadoc: fechadoc,
                dias: dias,
                inicio: inicio,
                fin: fin,
                tiposan: tiposan,
                archivo: archivo
            };
            recuperado.push(nuevoObjeto);
        }
        document.getElementById('sanciones').value = JSON.stringify(recuperado);
    } else {
        document.getElementById('sanciones').value = JSON.stringify([{
            cont: 1,
            id: 0,
            cambio: 1,
            motivo: motivo,
            tipodoc: tipodoc,
            nrodoc: nrodoc,
            fechadoc: fechadoc,
            dias: dias,
            inicio: inicio,
            fin: fin,
            tiposan: tiposan,
            archivo: archivo
        }]);
    }
    document.getElementById('motivo-san').value = "";
    document.getElementById('tipodoc-san').value = "";
    document.getElementById('nrodoc-san').value = "";
    document.getElementById('fechadoc-san').value = "";
    document.getElementById('dias-san').value = "";
    document.getElementById('fecha-ini-san').value = "";
    document.getElementById('fecha-fin-san').value = "";
    document.getElementById('id-sancion').value = "";
    document.getElementById('doc-san').value = "";
    document.getElementById('mensaje-san').innerHTML = "";
    construirTablaSanciones();
}

function construirTablaSanciones() {
    var datos = JSON.parse(document.getElementById('sanciones').value);
    let tabla = '<table class="table">';
    tabla += `
    <thead>
      <tr>
        <th>Descripción</th>

        <th>Inicio</th>
        <th>Fin</th>
        <th>Tiempo</th>
        <th>Acción</th>
      </tr>
    </thead>
    <tbody>
  `;

    // Recorrer los datos y generar filas
    datos.forEach(dato => {
        if (dato.cambio != 2) {
            tabla += `
                <tr>
                    <td>${dato.motivo}</td>
                    <td>${dato.inicio ? dato.inicio.split('-').reverse().join('-') : ''}</td>
                    <td>${dato.fin ? dato.fin.split('-').reverse().join('-') : ''}</td>
                    <td>${dato.dias}</td>
                    <td>
                        <a class="btn btn-outline-info btn-sm" onClick="editarSanciones(${dato.cont})" >
                        <i class="fa fa-edit"></i>
                        </a>
                        <a class="btn btn-outline-danger btn-sm" onClick="anularSanciones(${dato.cont})" >
                        <i class="fa fa-trash"></i>
                        </a>

                    </td>
                </tr>
                `;
        }

    });

    tabla += '</tbody></table>';

    // Insertar la tabla en el contenedor
    document.getElementById('tabla-sanciones').innerHTML = tabla;
}

function anularSanciones(cont) {
    var sanciones = document.getElementById("sanciones").value;
    if (sanciones != "") {
        var recuperado = JSON.parse(sanciones);
        recuperado = recuperado.map(obj => {
            if (obj.cont === cont) {
                obj.cambio = 2;
            }
            return obj;
        });
        document.getElementById("sanciones").value = JSON.stringify(recuperado);
        construirTablaSanciones();
    } else {
        document.getElementById('tabla-sanciones').innerHTML = '<div class="alert alert-light" role="alert">Sin datos</div>';
    }
}

function editarSanciones(cont) {
    var sanciones = document.getElementById('sanciones').value; // Obtenemos la cadena guardada
    if (sanciones != "") {
        var recuperado = JSON.parse(sanciones); // Convertimos de vuelta a un objeto
        recuperado = recuperado.map(obj => {
            if (obj.cont === cont) {

                document.getElementById('motivo-san').value = obj.motivo;
                document.getElementById('tipodoc-san').value = obj.tipodoc;
                document.getElementById('nrodoc-san').value = obj.nrodoc;
                document.getElementById('fechadoc-san').value = obj.fechadoc;
                document.getElementById('dias-san').value = obj.dias;
                document.getElementById('fecha-ini-san').value = obj.inicio;
                document.getElementById('fecha-fin-san').value = obj.fin;
                document.getElementById('id-sancion').value = cont;
                const tipo = obj.tiposan; // Por ejemplo: "1" o "2"

                if (obj.archivo && obj.archivo.trim() !== '') {
                    document.getElementById("mensaje-san").innerHTML =
                        '<a href="../repositories/' + obj.archivo + '" class="text-success" target="_blank"><i class="fa fa-eye" ></i>Ver documento</a>';
                } else {
                    document.getElementById("mensaje-san").innerHTML =
                        '<span class="text-muted">No se encontró ningún archivo</span>';
                }
                document.getElementById("doc-san").value = obj.archivo;

                const radios = document.getElementsByName('tipo-sancion');
                radios.forEach(radio => {
                    if (radio.value === tipo.toString()) {
                        radio.checked = true;
                    }
                });
            }
            return obj;
        });
    }
}