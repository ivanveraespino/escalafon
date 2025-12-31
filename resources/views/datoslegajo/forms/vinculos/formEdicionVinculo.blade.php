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
        width: 100% !important;
        /* Ajusta el ancho */
        font-size: 16px;
        /* Cambia el tamaño del texto */
        padding: 8px;
        /* Ajusta el espacio interno */
    }
</style>

<h6 class="text-center text-success pb-0 mb-0 " style="font-size: 1.15rem;">Sobre el vínculo laboral</h6>
<small class="text-center pb-0 text-secondary">Area donde actualmente está laborando</small>
<div class="col-6 col-lg-3">
    <div class="form-floating  pb-1">
        <input type="text" class="form-control" list="datalistOptions" name="tipodoc-vin" id="tipodoc-vin" value="{{$vinculo->nombredocvin ?? ''}}" required>

        <label for="tipodoc-vin">Tipo Documento</label>
        <datalist id="datalistOptions">
            @foreach($tiposdoc as $docs)
            <option value="{{$docs->nombre}}">
                @endforeach
        </datalist>
    </div>
    <div class="form-floating pb-1">
        <input type="text" class="form-control" id="nrodoc-vin" name="nrodoc-vin" value="{{ isset($vinculo) ? $vinculo->nro_doc : '' }}" required>
        <label for="nrodoc-vin">Nro. Documento</label>
    </div>
</div>
<div class="col-6 col-lg-9">
    <div class="form-floating pb-1">
        <label for="id-area-vin" style="padding-top: 0px;">Unidad Orgánica:<span class="required text-danger">*</span></label>
        <select name="id-area-vin" id="id-area-vin" class="select2 form-select js-example-theme-multiple" style="width: 100%;" required>
            <option value="" selected disabled></option>
            @foreach($areas as $area)
            <option value="{{ $area->id}}" {{isset($vinculo) ? ($vinculo->id_unidad_organica==$area->id ? 'selected':''):''}}>{{ $area->nombre  }}</option>
            @endforeach
        </select>

    </div>
    <div class="form-floating pb-1">
        <select name="id-cargo-vinculo" id="id-cargo-vinculo" class="form-select" required>
            <option value="" selected disabled></option>
            @foreach($cargos as $cargo)
            <option value="{{ $cargo->id }}" {{isset($vinculo) ? ($vinculo->id_cargo==$cargo->id ? 'selected':''):''}}>{{ $cargo->nombre }}</option>
            @endforeach
            <option value="0">Agregar nuevo... </option>
        </select>
        <label for="id-cargo-vinculo">Categoría del Cargo <span class="required text-danger">*</span> </label>
    </div>
    <div class="form-floating pb-1">
        <input type="text" class="form-control" id="denominacion-cargo" name="denominacion-cargo" value="{{ isset($vinculo) ? $vinculo->denominacion : '' }}">
        <label for="nrodoc-fin-vin">Denominación</label>
    </div>
</div>

<div class="col-6 col-lg-3">
    <div class="form-floating pb-1">
        <input type="date" class="form-control" id="fecha-ini-vin" name="fecha-ini-vin" value="{{ isset($vinculo) && isset($vinculo->fecha_ini) ? $vinculo->fecha_ini->format('Y-m-d') : '' }}" required>
        <label for="fecha-ini-vin">Fecha Inicio<span class="required text-danger">*</span></label>
    </div>
</div>
<div class="col-6 col-lg-3">
    <div class="form-floating pb-1">
        <select id="id-regimen-vin" name="id-regimen-vin" class="form-select" required>
            <option value="" selected disabled></option>
            @foreach($reg as $regbd)
            <option value="{{ $regbd->id ?? '' }}" {{isset($vinculo) ? ($regbd->id==$vinculo->id_regimen ?'selected':''):'' }}>{{ $regbd->nombre }}</option>
            @endforeach
            <option value="0">Agregar más...</option>
        </select>
        <label for="id-regimen-vin">Régimen</label>
    </div>
</div>
<div class="col-6 col-lg-3">
    <div class="form-floating pb-1">
        <select id="id-condicion-laboral-vin" name="id-condicion-laboral-vin" class="form-select" required>
            <option value="" selected disabled></option>
            @foreach($conlab as $conlab)
            <option value="{{ $conlab->id ?? '' }}" {{ isset($vinculo) ? ($conlab->id==$vinculo->id_condicion_laboral ? 'selected':''):'' }}>{{ $conlab->nombre }}</option>
            @endforeach
            <option value="0">Agregar más...</option>
        </select>
        <label for="id-condicion-laboral-vin">Condicion Laboral</label>
    </div>
</div>

<div class="col" style="display: none">
    <input type="file" id="subidor-ingreso" accept=".pdf">
    <input type="hidden" name="doc-ingreso" id="doc-ingreso">
    <div id="mensaje-ingreso"></div>
</div>

<script>
    $(document).ready(function() {
        $('#id-area-vin').select2({
            placeholder: "Buscar...",
            allowClear: true,
            theme: "classic",
        });
        $(".js-example-theme-multiple").select2({
            theme: "classic",
            width: '100%'
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
</script>