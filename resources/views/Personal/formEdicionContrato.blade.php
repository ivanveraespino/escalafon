<style>
    .form-floating {
        flex: 1;
        min-width: 200px;
    }

    .form-group {
        flex: 1;
        min-width: 200px;
        margin: 0;
    }

    .list-group-item.active {
        z-index: 2;
        color: #fff;
        /* Texto blanco */
        background-color: #28a745 !important;
        /* Verde Bootstrap */
        border-color: #28a745 !important;
    }
</style>

<div class="d-flex flex-column">
    <input type="hidden" id="id-contrato" name="id-contrato" value="{{$ult_vin->id}}">

    <div class="form-row mb-2">
        @php
            $totalAnios = $totalMeses = $totalDias = 0;
        @endphp
        <div class="col ini_vinculo">
            <div class="list-group list-group-numbered">

                @if (!empty($historialVinculos))

                    <h6>Historial</h6>
                    @foreach($historialVinculos as $his)
                        @php
                            $totalAnios += $his->anos ?? 0;
                            $totalMeses += $his->meses ?? 0;
                            $totalDias += $his->dias ?? 0;
                        @endphp
                        <a class="list-group-item d-flex justify-content-between list-group-item-action align-items-start p-1 {{$his->id == $ult_vin->id ? 'active' : ''}}"
                            data-id="{{ $his->id }}">
                            <div class="ms-2 me-auto align-items-start">
                                <h6 class="fw-bold text-start m-0 ">{{$his->cargo}} ({{$his->regimen}})</h6>
                                <small class="text-start">{{Str::limit($his->area, 35) }} - Del
                                    {{\Carbon\Carbon::parse($his->fecha_ini)->format('d-m-Y')}} al
                                    {{\Carbon\Carbon::parse($his->fecha_fin)->format('d-m-Y') ?? "Sin Registro"}}</small>
                            </div>
                            <h5>
                                <span class="badge text-bg-light rounded-pill">{{floor($his->dias / 365)}}A
                                    {{floor(($his->dias % 365) / 30) }}M
                                    {{($his->dias % 365) % 30 }}D</span>
                            </h5>
                        </a>
                    @endforeach
                    @php
                        $totalMeses += intdiv($totalDias, 30);
                        $totalDias %= 30;

                        $totalAnios += intdiv($totalMeses, 12);
                        $totalMeses %= 12;
                    @endphp
                @else
                    <div class="alert alert-info" role="alert">
                        No se encontró registros anteriores
                    </div>
                @endif
            </div>
            <!--<p class="mt-1 mb-0 fw-bold">Total acumulado: {{ $totalAnios }}A {{ $totalMeses }}M {{ $totalDias }}D</p>-->

        </div>

    </div>
    <a href="#" onclick="nuevoVinculo()" class="text-end text-munilc mb-2"> <span class="badge text-bg-success"
            style="font-size: 14px;">Nuevo vínculo</span></a>
    <div class="form-row gap-4">
        <div class="d-flex flex-column gap-4 col border rounded p-3">
            <h6 class="text-center text-munilc pb-1" style="font-size: 1.15rem;">Ingreso</h6>
            <div class="form-row">
                <div class="form-floating">
                    <input type="text" name="tipodoc-vin" id="tipodoc-vin" list="datalistOptions" class="form-control"
                        value="{{ $ult_vin->nombredocvin ?? '' }}">

                    <label for="tipodoc-vin">Tipo Documento</label>
                    <datalist id="datalistOptions">
                        @foreach($tiposdoc as $docs)
                            <option value="{{$docs->nombre}}">
                        @endforeach
                    </datalist>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control" id="nrodoc-vin" name="nrodoc-vin"
                        value="{{ $ult_vin->nro_doc }}">
                    <label for="nrodoc-vin">Nro. Documento</label>
                </div>

            </div>
            <div class="col">
                <div class="pb-3">
                    <label for="id-area-vin">Unidad Orgánica<span class="required">*</span></label>
                    <select name="id-area-vin" id="id-area-vin" class="form-select" style="width: 100%;">
                        <option value="" selected disabled></option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id}}" {{ $ult_vin->id_unidad_organica == $area->id ? 'selected' : ''}}>
                                {{ $area->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-floating">
                    <select name="id-cargo-vinculo" id="id-cargo-vinculo" class="form-select">
                        <option value="" selected disabled></option>
                        @foreach($cargos as $cargo)
                            <option value="{{ $cargo->id }}" {{ $ult_vin->id_cargo == $cargo->id ? 'selected' : ''}}>
                                {{ $cargo->nombre }}
                            </option>
                        @endforeach
                        <option value="0">Agregar nuevo... </option>
                    </select>
                    <label for="id-cargo-vinculo">Cargo <span class="required">*</span> </label>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control" id="denominacion-cargo" name="denominacion-cargo"
                        value="{{$ult_vin->denominacion}}">
                    <label for="nrodoc-fin-vin">Denominación</label>
                </div>

            </div>

            <div class="form-row ">
                <div class="form-floating">
                    <input type="date" class="form-control" id="fecha-ini-vin" name="fecha-ini-vin"
                        value="{{$ult_vin->fecha_ini}}">
                    <label for="fecha-ini-vin">Fecha Inicio<span class="required">*</span></label>
                </div>
                <div class="form-floating">
                    <select id="id-regimen-vin" name="id-regimen-vin" class="form-select">
                        <option value="" selected disabled></option>
                        @foreach($reg as $regbd)
                            <option value="{{ $regbd->id ?? '' }}" {{ $ult_vin->id_regimen == $regbd->id ? 'selected' : ''}}>
                                {{ $regbd->nombre }}
                            </option>
                        @endforeach
                        <option value="0">Agregar más...</option>
                    </select>
                    <label for="id-regimen-vin">Régimen</label>
                </div>

                <div class="form-floating">
                    <select id="id-condicion-laboral-vin" name="id-condicion-laboral-vin" class="form-select">
                        <option value="" selected disabled></option>
                        @foreach($conlab as $conlab)
                            <option value="{{ $conlab->id ?? '' }}" {{ $ult_vin->id_condicion_laboral == $conlab->id ? 'selected' : ''}}>{{ $conlab->nombre }}</option>
                        @endforeach
                        <option value="0">Agregar más...</option>
                    </select>
                    <label for="id-condicion-laboral-vin">Condicion Laboral</label>
                </div>
            </div>


            <div class="form-row">
                <div class="form-floating mb-2">
                    <div class="input-group">
                        <span class="input-group-text">Legajo</span>
                        <input type="number" min=1990 name="periodo-file" id="periodo-file"
                            class="form-control form-control-lg" placeholder="Año" style="float: right;"
                            value="{{$ult_vin->filea}}">
                        <input type="number" name="num-file" id="num-file" min=0 placeholder="Nro. File"
                            class="form-control form-control-lg" value="{{$ult_vin->lomo}}">
                    </div>
                </div>
            </div>
            <div class="col">
                <input type="file" id="subidor-ingreso" name="subidor-ingreso" accept=".pdf">
                <input type="hidden" name="doc-ingreso" id="doc-ingreso" value="{{$ult_vin->archivo}}">
                <div id="mensaje-ingreso">
                    @if (!empty($ult_vin->archivo))
                        <a href="../repositories/{{$ult_vin->archivo}}" target="_blank" class="text-success"><i
                                class="fa fa-eye"></i>Ver</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="d-flex flex-column gap-4 col border rounded p-3">
            <h6 class="text-center text-munilc pb-1 " style="font-size: 1.15rem;">Cese</h6>
            <div class="form-row">
                <div class="form-floating">
                    <input type="date" class="form-control" id="fecha-fin-vinculo" name="fecha-fin-vinculo"
                        value="{{$ult_vin->fecha_fin}}">
                    <label for="fecha-fin-vinculo">Fecha Fin</label>
                </div>
                <div class="form-floating">
                    <select id="id-motivo-fin-vinculo" name="id-motivo-fin-vinculo" class="form-select">
                        <option value="" selected disabled></option>
                        @foreach($vin_fin as $vin_fin)
                            <option value="{{ $vin_fin->id ?? '' }}" {{$vin_fin->id == $ult_vin->id_motivo_fin_vinculo ? 'selected' : ''}}>{{ $vin_fin->nombre }}</option>
                        @endforeach
                        <option value="0">Agregar más...</option>
                    </select>
                    <label for="id-motivo-fin-vinculo">Motivo de Cese</label>
                </div>

                <div class="form-floating">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="motivo-cese" name="motivo-cese"
                            value="{{$ult_vin->motivocese}}">
                        <label for="motivo-cese">Causal</label>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-floating">
                    <input type="text" name="tipodoc-fin-vin" id="tipodoc-fin-vin" list="datalistOptions"
                        class="form-control" value="{{$ult_vin->nombredoccese}}">

                    <label for="tipodoc-fin-vin">Tipo Documento</label>
                    <datalist id="datalistOptions">
                        @foreach($tiposdoc as $docs)
                            <option value="{{$docs->nombre}}">
                        @endforeach
                    </datalist>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control" id="nrodoc-fin-vin" name="nrodoc-fin-vin"
                        value="{{$ult_vin->nro_doc_fin}}">
                    <label for="nrodoc-fin-vin">Nro. Documento</label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <input type="file" id="subidor-cese" name="subidor-cese" accept=".pdf" />
                    <input type="hidden" name="doc-cese" id="doc-cese" value="{{$ult_vin->archivo_cese}}">
                    <div id="mensaje-cese">
                        @if (!empty($ult_vin->archivo_cese))
                            <a href="../repositories/{{$ult_vin->archivo_cese }}" target="_blank" class="text-success"><i
                                    class="fa fa-eye"></i>Ver</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-bell"></i> Atención</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="confirmModalBody">
                Se perderán algunos datos temporales, ¿Deseas continuar?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="confirmModalBtn">Sí</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#id-area-vin').select2({
            placeholder: "Buscar...",
            allowClear: true,
            theme: "classic"
        });
        $(".js-example-theme-multiple").select2({
            theme: "classic"
        });
    });
    document.getElementById("subidor-ingreso").addEventListener("change", function () {
        let archivo = this.files[0]; // Capturar el archivo seleccionado

        if (!archivo) {
            document.getElementById("mensaje-ingreso").innerHTML = "⚠ No se ha seleccionado ningún archivo.";
            return;
        }

        let formData = new FormData();
        formData.append("archivo", archivo);

        fetch("/subir-archivo", {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            }
        })
            .then(response => response.json())
            .then(data => {
                //document.getElementById("mensaje-ingreso").innerHTML = data.mensaje+' <a';
                document.getElementById("mensaje-ingreso").innerHTML = data.mensaje + ' <a href="../repositories/' + data.name + '" target="_blank" class="text-success"><i class="fa fa-eye" ></i>Ver</a>';
                document.getElementById("doc-ingreso").value = data.name;
            })
            .catch(error => console.error("Error al subir el archivo:", error));

    });
    document.getElementById("subidor-cese").addEventListener("change", function () {
        let archivo = this.files[0]; // Capturar el archivo seleccionado

        if (!archivo) {
            document.getElementById("mensaje-cese").innerHTML = "⚠ No se ha seleccionado ningún archivo.";
            return;
        }

        let formData = new FormData();
        formData.append("archivo", archivo);

        fetch("/subir-archivo", {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            }
        })
            .then(response => response.json())
            .then(data => {
                //document.getElementById("mensaje-ingreso").innerHTML = data.mensaje+' <a';
                document.getElementById("mensaje-cese").innerHTML = data.mensaje + ' <a href="../repositories/' + data.name + '" target="_blank" class="text-success"><i class="fa fa-eye" ></i>Ver</a>';
                document.getElementById("doc-cese").value = data.name;
            })
            .catch(error => console.error("Error al subir el archivo:", error));

    });


    $(document).ready(function () {
        $('.list-group-item').on('click', function (e) {
            //e.preventDefault();
            let linea = $(this);
            let id = $(this).data('id');
            confirmModal("Se perderán algunos datos temporales, ¿Deseas continuar?", function (confirmado) {
                if (confirmado) {
                    e.preventDefault();

                    // 🔄 Quitar clase 'active' de todos
                    $('.list-group-item').removeClass('active');

                    // ✅ Agregar clase 'active' al seleccionado
                    linea.addClass('active');

                    // 📦 Obtener ID
                    let vinculoId = id;
                    limpiarCamposVinculo();
                    document.getElementById('id-contrato').value = vinculoId;

                    // 🚀 Llamar a la función AJAX personalizada
                    cargarDatosVinculo(id);
                }
            });


        });

    });
    // 📡 Función AJAX para cargar datos
    function cargarDatosVinculo(id) {
        $.ajax({
            url: "/datos-vinculo",
            method: "GET",
            data: {
                id: id
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            success: function (data) {
                data.vinculo.forEach(element => {
                    $('#tipodoc-vin').val(element.nombredocvin);
                    $('#nrodoc-vin').val(element.nro_doc);
                    $('#id-area-vin').val(element.id_unidad_organica);
                    $('#id-cargo-vinculo').val(element.id_cargo);
                    $('#denominacion-cargo').val(element.denominacion);
                    $('#fecha-ini-vin').val(element.fecha_ini);
                    $('#id-regimen-vin').val(element.id_regimen);
                    $('#id-condicion-laboral-vin').val(element.id_condicion_laboral);
                    $('#periodo-file').val(element.periodo);
                    $('#num-file').val(element.numerofile);
                    if (element.archivo) {
                        $('#doc-ingreso').val(element.archivo);
                        $("#mensaje-ingreso").html(
                            ' <a href="../repositories/' + element.archivo + '" target="_blank" class="text-success"><i class="fa fa-eye"></i> Ver</a>'
                        );
                    } else {
                        $('#doc-ingreso').val("");
                        $("#mensaje-ingreso").html("");
                    }

                    $('#tipodoc-fin-vin').val(element.nombredoccese);
                    $('#nrodoc-fin-vin').val(element.nro_doc_fin);
                    $('#fecha-fin-vinculo').val(element.fecha_fin);
                    $('#id-motivo-fin-vinculo').val(element.id_motivo_fin_vinculo);
                    $('#motivo-cese').val(element.motivocese);
                    if (element.archivo_cese) {
                        $("#mensaje-cese").html(
                            ' <a href="../repositories/' + element.archivo_cese + '" target="_blank" class="text-success"><i class="fa fa-eye"></i> Ver</a>'
                        );
                        $("#doc-cese").val(element.archivo_cese);
                    } else {
                        $("#mensaje-cese").html("");
                        $("#doc-cese").val("");
                    }

                });

                //cargar rotaciones
                var contador = 1;
                var rotaciones = [];
                data.rotaciones.forEach(element => {
                    var nuevoObjeto = {
                        cont: contador,
                        id: element.id,
                        cambio: 1,
                        destino: element.unidad_organica_destino,
                        nombredes: element.destino,
                        cargo: element.cargo,
                        nombrecar: element.nombrecar,
                        descripcion: element.descripcion,
                        inicio: element.fecha_ini,
                        docini: element.nombredoc,
                        nroini: element.nrodoc,
                        archivoini: element.archivo,
                        fin: element.fecha_fin,
                        docfin: element.nombredocfin,
                        nrofin: element.nrodocfin,
                        archivofin: element.archivofin,
                    };
                    rotaciones.push(nuevoObjeto);
                    contador++;
                });
                document.getElementById('rotaciones').value = JSON.stringify(rotaciones);
                construirTablaRotacion();

                //cargar encargaturas
                contador = 1;
                var encargaturas = [];
                data.encargaturas.forEach(element => {
                    var nuevoObjeto = {
                        cont: contador,
                        id: element.id,
                        cambio: 1,
                        destino: element.id_unidad_organica_destino,
                        nombredes: element.destino,
                        cargo: element.cargo,
                        nombrecar: element.nombrecar,
                        descripcion: element.descripcion,
                        docini: element.nombredoc,
                        nroini: element.nrodoc,
                        inicio: element.fecha_ini,
                        archivoini: element.archivo,
                        docfin: element.nombredocfin,
                        nrofin: element.nrodocfin,
                        fin: element.fecha_fin,
                        archivofin: element.archivofin
                    };
                    encargaturas.push(nuevoObjeto);
                    contador++;
                });
                document.getElementById('encargaturas').value = JSON.stringify(encargaturas);
                construirTablaEncargatura();

                //licencias
                contador = 1;
                var licencias = [];
                data.licencias.forEach(element => {
                    var nuevoObjeto = {
                        cont: contador,
                        id: element.id,
                        cambio: 1,
                        descripcion: element.descripcion,
                        tipodoc: element.nombredoc,
                        nrodoc: element.nrodoc,
                        observaciones: element.observaciones,
                        inicio: element.fecha_ini,
                        dias: element.dias,
                        fin: element.fecha_fin,
                        acuenta: element.acuentavac,
                        congoce: element.congoce,
                        archivo: element.archivo
                    };
                    licencias.push(nuevoObjeto);
                    contador++;

                });
                document.getElementById('licencias').value = JSON.stringify(licencias);
                construirTablaLicencia();



                //permisos
                contador = 1;
                var permisos = [];
                data.permisos.forEach(element => {
                    var nuevoObjeto = {
                        cont: contador,
                        id: element.id,
                        cambio: 1,
                        descripcion: element.descripcion,
                        tipodoc: element.nombredoc,
                        nrodoc: element.nrodoc,
                        observaciones: element.observaciones,
                        inicio: element.fecha_ini,
                        dias: element.dias,
                        fin: element.fecha_fin,
                        acuenta: element.acuentavac,
                        archivo: element.archivo
                    };
                    permisos.push(nuevoObjeto);
                    contador++;
                });
                document.getElementById('permisos').value = JSON.stringify(permisos);
                construirTablaPermiso();


                const tbody = document.getElementById('ll');
                tbody.innerHTML = ''; // Limpiar contenido previo

                let totalDias = 0;

                licencias.forEach(l => {
                    const tr = document.createElement('tr');
                    const td = document.createElement('td');
                    td.className = `p-0 ${l.acuentavac == 1 ? 'bg-warning-subtle' : ''}`;

                    const div = document.createElement('div');
                    div.className = 'blockquote-footer m-0';

                    const fwBold = document.createElement('div');
                    fwBold.className = 'fw-bold';

                    const descripcion = document.createTextNode(`${l.descripcion.substring(0, 40)} - `);
                    const badge = document.createElement('span');
                    badge.className = 'badge text-bg-warning rounded-pill';
                    badge.textContent = `${l.dias} días`;

                    fwBold.appendChild(descripcion);
                    fwBold.appendChild(badge);

                    const fechas = document.createTextNode(
                        `Inicio: ${formatDate(l.fecha_ini)} - Fin: ${formatDate(l.fecha_fin)}`
                    );

                    div.appendChild(fwBold);
                    div.appendChild(fechas);
                    td.appendChild(div);
                    tr.appendChild(td);
                    tbody.appendChild(tr);

                    if (l.acuentavac == 1) {
                        totalDias += l.dias;
                    }
                });

                // Agregar fila final con total
                const trTotal = document.createElement('tr');
                const tdTotal = document.createElement('td');
                tdTotal.className = 'table-active p-0';

                const footer = document.createElement('div');
                footer.className = 'card-footer text-body-secondary fw-bold';
                footer.id = 'totall';
                footer.textContent = `Total: ${totalDias} días`;

                tdTotal.appendChild(footer);
                trTotal.appendChild(tdTotal);
                tbody.appendChild(trTotal);

                const tbodyp = document.getElementById('lp');
                tbodyp.innerHTML = ''; // Limpiar contenido previo

                let totalDiasp = 0;

                permisos.forEach(p => {
                    const tr = document.createElement('tr');
                    const td = document.createElement('td');
                    td.className = `p-0 ${p.acuentavac == 1 ? 'bg-warning-subtle' : ''}`;

                    const figcaption = document.createElement('figcaption');
                    figcaption.className = 'blockquote-footer';

                    const fwBold = document.createElement('div');
                    fwBold.className = 'fw-bold';
                    fwBold.textContent = p.descripcion;

                    const fechas = document.createTextNode(
                        `Inicio: ${formatDate(p.fecha_ini)} - Fin: ${formatDate(p.fecha_fin)} `
                    );

                    const badge = document.createElement('span');
                    badge.className = 'badge text-bg-warning rounded-pill';
                    badge.textContent = `${p.dias} días`;

                    figcaption.appendChild(fwBold);
                    figcaption.appendChild(fechas);
                    figcaption.appendChild(badge);
                    td.appendChild(figcaption);
                    tr.appendChild(td);
                    tbodyp.appendChild(tr);

                    if (p.acuentavac == 1) {
                        totalDiasp += p.dias;
                    }
                });

                // Agregar fila final con total
                const trTotalp = document.createElement('tr');
                const tdTotalp = document.createElement('td');
                tdTotal.className = 'table-active p-0';

                //const footerp = document.createElement('div');
                //footerp.className = 'card-footer text-body-secondary fw-bold';
                //footerp.id = 'totalp';
                //footerp.textContent = `Total: ${totalDiasp} días`;

                //tdTotal.appendChild(footerp);
                trTotal.appendChild(tdTotalp);
                tbodyp.appendChild(trTotalp);

                //compensaciones
                contador = 1;
                var compensaciones = [];
                data.compensaciones.forEach(element => {
                    var nuevoObjeto = {
                        cont: contador,
                        id: element.id,
                        cambio: 1,
                        tipo: element.tipo_compensacion,
                        tipodoc: element.nombredoc,
                        nrodoc: element.nrodoc,
                        descripcion: element.descripcion,
                        inicio: element.fecha_ini,
                        dias: element.dias,
                        fin: element.fecha_fin,
                        archivo: element.archivo,
                    };
                    compensaciones.push(nuevoObjeto);
                    contador++;
                });
                document.getElementById('compensaciones').value = JSON.stringify(compensaciones);
                construirTablaCompensacion();

                //reconocimientos
                contador = 1;
                var reconocimientos = [];
                data.reconocimientos.forEach(element => {
                    var nuevoObjeto = {
                        cont: contador,
                        id: element.id,
                        cambio: 1,
                        descripcion: element.descripcion,
                        tipodoc: element.nombredoc,
                        nrodoc: element.nrodoc,
                        fecharecon: element.fecharecon,
                        archivo: element.archivo
                    };
                    reconocimientos.push(nuevoObjeto);
                    contador++;
                });
                document.getElementById('reconocimientos').value = JSON.stringify(reconocimientos);
                construirTablaReconocimiento();

                //sanciones
                contador = 1;
                var sanciones = [];
                data.sanciones.forEach(element => {
                    var nuevoObjeto = {
                        cont: contador,
                        id: element.id,
                        cambio: 1,
                        motivo: element.descripcion,
                        tipodoc: element.nombredoc,
                        nrodoc: element.nrodoc,
                        fechadoc: element.fechadoc,
                        dias: element.dias_san,
                        inicio: element.fecha_ini,
                        fin: element.fecha_fin,
                        tiposan: element.tiposancion,
                        archivo: element.archivo
                    };
                    sanciones.push(nuevoObjeto);
                    contador++;
                });
                document.getElementById('sanciones').value = JSON.stringify(sanciones);
                construirTablaSanciones();

                //vacaciones
                contador = 1;
                var vacaciones = [];
                data.vacaciones.forEach(element => {
                    var nuevoObjeto = {
                        cont: contador,
                        id: element.id,
                        cambio: 1,
                        periodo: element.periodo,
                        tipodoc: element.nombredoc,
                        nrodoc: element.nrodoc,
                        observaciones: element.observaciones,
                        suspension: element.suspension,
                        mes: element.mes,
                        inicio: element.fecha_ini,
                        fin: element.fin,
                        dias: element.dias,
                        archivo: element.archivo
                    };
                    vacaciones.push(nuevoObjeto);
                    contador++;
                });
                document.getElementById('vacaciones').value = JSON.stringify(vacaciones);
                construirTablaVacaciones();
            },
            error: function (xhr, status, error) {
                alert("Error al cargar vínculo: " + error);
            }
        });

    }

    function nuevoVinculo() {
        document.getElementById('id-contrato').value = "";
        document.getElementById('rotaciones').value = "";
        document.getElementById('encargaturas').value = "";
        document.getElementById('vacaciones').value = "";
        document.getElementById('licencias').value = "";
        document.getElementById('permisos').value = "";
        document.getElementById('compensaciones').value = "";
        document.getElementById('reconocimientos').value = "";
        document.getElementById('sanciones').value = "";
        limpiarCamposVinculo();
    }

    function limpiarCamposVinculo() {
        document.getElementById('tipodoc-vin').value = "";
        document.getElementById('nrodoc-vin').value = "";
        document.getElementById('id-cargo-vinculo').value = "";
        document.getElementById('denominacion-cargo').value = "";
        document.getElementById('fecha-ini-vin').value = "";
        document.getElementById('id-regimen-vin').value = "";
        document.getElementById('id-condicion-laboral-vin').value = "";
        document.getElementById('periodo-file').value = "";
        document.getElementById('num-file').value = "";
        document.getElementById('doc-ingreso').value = "";
        document.getElementById('mensaje-ingreso').value = "";
        document.getElementById('fecha-fin-vinculo').value = "";
        document.getElementById('id-motivo-fin-vinculo').value = "";
        document.getElementById('motivo-cese').value = "";
        document.getElementById('doc-cese').value = "";
        document.getElementById('mensaje-cese').value = "";
    }

    function confirmModal(mensaje, callback) {
        document.getElementById("confirmModalBody").textContent = mensaje;

        const modal = new bootstrap.Modal(document.getElementById("confirmModal"));
        modal.show();

        const btnConfirmar = document.getElementById("confirmModalBtn");

        // Eliminar listeners anteriores
        const nuevoBtn = btnConfirmar.cloneNode(true);
        btnConfirmar.parentNode.replaceChild(nuevoBtn, btnConfirmar);

        nuevoBtn.addEventListener("click", function () {
            callback(true);
            modal.hide();
        });
    }
    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('es-PE', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }
</script>