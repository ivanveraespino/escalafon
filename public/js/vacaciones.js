document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("periodo-vac").value = new Date().getFullYear();
});

document.addEventListener("DOMContentLoaded", function () {
    var fechaIni = document.getElementById("fecha-ini-vac");
    var fechaFin = document.getElementById("fecha-fin-vac");


    fechaIni.addEventListener("change", function () {
        var fechaSeleccionada = new Date(fechaIni.value);
        if (!isNaN(fechaSeleccionada.getTime())) {
            fechaFin.min = fechaIni.value;
        }
    });
    fechaFin.addEventListener("change", function () {
        document.getElementById('dias-vac').value = diferenciaDiasFecha(fechaIni.value, fechaFin.value);
    });
});

$(document).ready(function () {
    $("#periodo-vac").on("change", function () {
        var periodo = $(this).val();
        var idvinculo = document.getElementById('id-contrato').value;
        var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Obtener el token 
        var cont = 1;
        let dp = 0;
        let dl = 0;
        let dv = 0;
        datos = [];
        $.ajax({
            url: "/consulta-vacaciones", // Cambia esto a la ruta correcta en tu backend
            type: "POST",
            dataType: "json",
            contentType: "application/json",
            headers: {
                "X-CSRF-TOKEN": csrfToken // Incluir el token en los encabezados
            },
            data: JSON.stringify({ periodo: periodo, vinculo: idvinculo }),

            success: function (response) {
                console.log("Response:", response); // Verifica la estructura en la consola
                if (response.length > 0) {
                    response.forEach(element => {
                        if (!element.periodo || !element.fecha_ini || !element.fecha_fin) {
                            console.warn("Dato inválido detectado:", element);
                        }
                        dv = dv + Number(element.dias);
                        datos.push({
                            cont: cont,
                            id: element.id,
                            cambio: 1,
                            periodo: element.periodo,
                            tipodoc: element.idtd,
                            nrodoc: element.nrodoc,
                            observaciones: element.observaciones,
                            suspension: element.suspension,
                            mes: element.mes,
                            inicio: element.fecha_ini,
                            fin: element.fecha_fin,
                            dias: element.dias,
                            archivo: element.archivo
                        });

                        cont++;
                    });
                    document.getElementById('vacaciones').value = JSON.stringify(datos);
                    let total = dp + dl + dv;
                    total = 30 - total;
                    if (total > 0) {
                        document.getElementById('resumen-dias').innerHTML = `<div class="alert alert-success" role="alert">
                        Disponible: ${total} días</div>`;
                    }

                    else {
                        document.getElementById('resumen-dias').innerHTML = `<div class="alert alert-warning" role="alert">
                        Disponible: 0 días</div>`;
                    }
                    construirTablaVacaciones();

                } else {
                    document.getElementById('vacaciones').value = "[]";
                    construirTablaVacaciones();

                }

            },
            error: function (xhr, status, error) {
                alert('sin respuesta');
            }
        });
        /////cosulta licencias 
        $.ajax({
            url: "/consulta-licencias", // Cambia esto a la ruta correcta en tu backend
            type: "POST",
            dataType: "json",
            contentType: "application/json",
            headers: {
                "X-CSRF-TOKEN": csrfToken // Incluir el token en los encabezados
            },
            data: JSON.stringify({ periodo: periodo, vinculo: idvinculo }),

            success: function (response) {
                console.log("Response:", response); // Verifica la estructura en la consola

                let resumenLicencias = document.getElementById("ll");

                resumenLicencias.innerHTML = ""; // Limpia el contenido anterior

                if (response.length > 0) {
                    response.forEach(element => {
                        if (!element.periodo || !element.fecha_ini || !element.fecha_fin) {
                            console.warn("Dato inválido detectado:", element);
                        }

                        // Crear nuevo elemento dinámico
                        let tr = document.createElement('tr');
                        let nuevoItem = document.createElement("td");
                        nuevoItem.className = "p-0";
                        dl = dl + Number(element.dias);
                        nuevoItem.innerHTML = `
                    <figcaption class="blockquote-footer m-0">
                        <div class="fw-bold">
                            ${element.descripcion} - ${element.periodo}
                            <span class="badge text-bg-info rounded-pill">${element.dias} días</span>
                        </div>
                        Inicio: ${element.fecha_ini} - Fin: ${element.fecha_fin}
                    </figcaption>
                `;
                        tr.appendChild(nuevoItem)
                        resumenLicencias.appendChild(tr);
                    });

                } else {
                    resumenLicencias.innerHTML = `<tr><td class=" p-0"><p>No hay licencias disponibles.</p></td></tr>`;
                }
                let total = dp + dl;
                total = 30 - total;
                let ult = document.createElement('tr');
                ult.innerHTML = `<td class="table-active p-0">`
                    + `<div class="card-footer text-body-secondary fw-bold" id="totalp">`
                    + `Total: ${dl} días</div></td>`;
                resumenLicencias.appendChild(ult);
                if (total > 0) {
                    document.getElementById('resumen-dias').innerHTML = `<div class="alert alert-success" role="alert">
                    Disponible: ${total} días</div>`;
                }

                else {
                    document.getElementById('resumen-dias').innerHTML = `<div class="alert alert-warning" role="alert">
                    Disponible: 0 días</div>`;
                }
            },
            error: function (xhr, status, error) {
                alert('sin respuesta');
            }
        });
        //////consulta permisos
        $.ajax({
            url: "/consulta-permisos", // Cambia esto a la ruta correcta en tu backend
            type: "POST",
            dataType: "json",
            contentType: "application/json",
            headers: {
                "X-CSRF-TOKEN": csrfToken // Incluir el token en los encabezados
            },
            data: JSON.stringify({ periodo: periodo, vinculo: idvinculo }),

            success: function (response) {
                console.log("Response:", response); // Verifica la estructura en la consola

                let resumenPermisos = document.getElementById("lp");
                resumenPermisos.innerHTML = ""; // Limpia el contenido anterior

                if (response.length > 0) {
                    response.forEach(element => {
                        if (!element.periodo || !element.fecha_ini || !element.fecha_fin) {
                            console.warn("Dato inválido detectado:", element);
                        }
                        let tr = document.createElement("tr");
                        // Crear nuevo elemento dinámico
                        let nuevoItem = document.createElement("td");
                        nuevoItem.className = " p-0";
                        dp = dp + Number(element.dias);
                        nuevoItem.innerHTML = `
                    <figcaption class="blockquote-footer m-0">
                        <div class="fw-bold">
                            ${element.descripcion} - ${element.periodo}
                            <span class="badge text-bg-info rounded-pill">${element.dias} días</span>
                        </div>
                        Inicio: ${element.fecha_ini} - Fin: ${element.fecha_fin}
                    </figcaption>
                `;

                        tr.appendChild(nuevoItem);
                        resumenPermisos.appendChild(tr);
                    });

                } else {
                    resumenPermisos.innerHTML = `<tr><td class="p-0"><p>No hay licencias disponibles.</p></td></tr>`;

                }
                let total = dp + dl;
                total = 30 - total;
                let ult = document.createElement('tr');
                ult.innerHTML = `<td class="table-active p-0">`
                    + `<div class="card-footer text-body-secondary fw-bold" id="totalp">`
                    + `Total: ${dp} días</div></td>`;
                resumenPermisos.appendChild(ult);
                if (total > 0) {
                    document.getElementById('resumen-dias').innerHTML = `<div class="alert alert-success" role="alert">
                    Disponible: ${total} días</div>`;
                }

                else {
                    document.getElementById('resumen-dias').innerHTML = `<div class="alert alert-warning" role="alert">
                    Disponible: 0 días</div>`;
                }
            },
            error: function (xhr, status, error) {
                alert('sin respuesta');
            }
        });


    });
});