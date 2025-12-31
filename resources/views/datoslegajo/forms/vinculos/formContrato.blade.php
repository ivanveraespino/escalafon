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

    .select2-search__field {
        width: 300px !important;
        /* Ajusta el ancho */
        font-size: 16px;
        /* Cambia el tamaño del texto */
        padding: 8px;
        /* Ajusta el espacio interno */
    }
</style>


<div class="d-flex flex-column">
    <div class="form-row">

        <div class="col ini_vinculo">


        </div>

    </div>

    <div class="form-row gap-4">
        <div class="d-flex flex-column gap-4 col border rounded p-3">
            <h6 class="text-center text-munilc pb-1" style="font-size: 1.15rem;">Ingreso</h6>
            <div class="form-row">
                <div class="form-floating mb-0">
                    <input type="text" class="form-control" list="datalistOptions" name="tipodoc-vin" id="tipodoc-vin">

                    <label for="tipodoc-vin">Tipo Documento</label>
                    <datalist id="datalistOptions">
                        @foreach($tiposdoc as $docs)
                        <option value="{{$docs->nombre}}">
                            @endforeach
                    </datalist>
                </div>
                <div class="ini_vinculo col">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="nrodoc-vin" name="nrodoc-vin">
                        <label for="nrodoc-vin">Nro. Documento</label>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group p-2">
                    <label for="id-area-vin">Unidad Orgánica<span class="required">*</span></label>
                    <select name="id-area-vin" id="id-area-vin" class="form-select js-example-theme-multiple" style="width: 100%;">
                        <option value="" selected disabled></option>
                        @foreach($areas as $area)
                        <option value="{{ $area->id}}">{{ $area->nombre }}</option>
                        @endforeach
                    </select>

                </div>
                <div class="form-floating">
                    <select name="id-cargo-vinculo" id="id-cargo-vinculo" class="form-select">
                        <option value="" selected disabled></option>
                        @foreach($cargos as $cargo)
                        <option value="{{ $cargo->id }}">{{ $cargo->nombre }}</option>
                        @endforeach
                        <option value="0">Agregar nuevo... </option>
                    </select>
                    <label for="id-cargo-vinculo">Cargo <span class="required">*</span> </label>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control" id="denominacion-cargo" name="denominacion-cargo">
                    <label for="nrodoc-fin-vin">Denominación</label>
                </div>
            </div>
            <div class="form-row ">
                <div class="form-floating">
                    <input type="date" class="form-control" id="fecha-ini-vin" name="fecha-ini-vin">
                    <label for="fecha-ini-vin">Fecha Inicio<span class="required">*</span></label>
                </div>
                <div class="form-floating">
                    <select id="id-regimen-vin" name="id-regimen-vin" class="form-select">
                        <option value="" selected disabled></option>
                        @foreach($reg as $regbd)
                        <option value="{{ $regbd->id ?? '' }}">{{ $regbd->nombre }}</option>
                        @endforeach
                        <option value="0">Agregar más...</option>
                    </select>
                    <label for="id-regimen-vin">Régimen</label>
                </div>
                <div class="form-floating">
                    <select id="id-condicion-laboral-vin" name="id-condicion-laboral-vin" class="form-select" style="width: auto;">
                        <option value="" selected disabled></option>
                        @foreach($conlab as $conlab)
                        <option value="{{ $conlab->id ?? '' }}">{{ $conlab->nombre }}</option>
                        @endforeach
                        <option value="0">Agregar más...</option>
                    </select>
                    <label for="id-condicion-laboral-vin">Condicion Laboral</label>
                </div>
                <div class="form-floating">
                    <div class="input-group">
                        <span class="input-group-text">Legajo</span>
                        <input type="number" min=1990 name="periodo-file" id="periodo-file" class="form-control form-control-lg" placeholder="Año" style="float: right;">
                        <input type="number" name="num-file" id="num-file" min=0 placeholder="Nro. File" class="form-control form-control-lg">
                    </div>
                </div>

            </div>


            <div class="col">
                <input type="file" id="subidor-ingreso" accept=".pdf">
                <input type="hidden" name="doc-ingreso" id="doc-ingreso">
                <div id="mensaje-ingreso"></div>
            </div>
        </div>

        <div class="d-flex flex-column gap-4 col border rounded p-3">
            <h6 class="text-center text-munilc pb-1" style="font-size: 1.15rem;">Cese</h6>
            <div class="form-row">
                <div class="form-floating">
                    <input type="date" class="form-control" id="fecha-fin-vinculo" name="fecha-fin-vinculo">
                    <label for="fecha-fin-vinculo">Fecha Fin</label>
                </div>
                <div class="form-floating">
                    <select id="id-motivo-fin-vinculo" name="id-motivo-fin-vinculo" class="form-select">
                        <option value="" selected disabled></option>
                        @foreach($vin_fin as $vin_fin)
                        <option value="{{ $vin_fin->id ?? '' }}">{{ $vin_fin->nombre }}</option>
                        @endforeach
                    </select>
                    <label for="id-motivo-fin-vinculo">Motivo de Cese</label>
                </div>

            </div>
            <div class="form-row">

                <div class="form-floating">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="motivo-cese" name="motivo-cese">
                        <label for="motivo-cese">Causal</label>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-floating">
                    <input type="text" name="tipodoc-fin-vin" list="datalistOptions" id="tipodoc-fin-vin" class="form-control">

                    <label for="tipodoc-fin-vin">Tipo Documento</label>
                    <datalist id="datalistOptions">
                        @foreach($tiposdoc as $docs)
                        <option value="{{$docs->nombre}}">
                            @endforeach
                    </datalist>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control" id="nrodoc-fin-vin" name="nrodoc-fin-vin" placeholder="Ejm. 232-2023-MPLC">
                    <label for="nrodoc-fin-vin">Nro. Documento</label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <input type="file" id="subidor-cese" name="subidor-cese" accept=".pdf" />
                    <input type="hidden" name="doc-cese" id="doc-cese" value="">
                    <div id="mensaje-cese">
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
<script>
    $(document).ready(function() {
        $('#id-area-vin').select2({
            placeholder: "Buscar...",
            allowClear: true,
            theme: "classic"
        });
        $(".js-example-theme-multiple").select2({
            theme: "classic"
        });
    });
</script>
<script>
    document.getElementById("subidor-ingreso").addEventListener("change", function() {
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
    document.getElementById("subidor-cese").addEventListener("change", function() {
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
</script>