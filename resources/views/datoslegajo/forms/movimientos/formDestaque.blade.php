<div class="d-flex flex-column gap-4">

    <div class="form-row gap-3">
        <input type="hidden" class="form-control" id="tipo_movimiento" name="tipo_movimiento" value="2"/>
        <div class="col">
            <label for="entidad"class="required">Unidad Organica Origen<span class="required">*</span></label>
            <select name="unidad_organica" id="Eunidad_organica2" class="form-control" >
            </select>
                
        </div>

        <div class="col">
            <label for="entidad"class="required">Unidad Organica Destino<span class="required">*</span></label>
            <select name="unidad_organica_destino" id="Eunidad_organica_destino2" class="form-control" >
            </select>
        </div>

        <div class="col">
            <label for="entidad"class="required">Cargo<span class="required">*</span></label>
            <select name="cargo" id="Ecargo2" class="form-control" >
            </select>
        </div>

    </div>

    <div class="form-row gap-3">

        <div class="form-floating">
            <input type="date" class="form-control" id="Efecha_ini" name="fecha_ini" >
            <label for="periodo">Fecha Inicio</label>
        </div>

        <div class="form-floating">
            <input type="date" class="form-control" id="Efecha_fin" name="fecha_fin" >
            <label for="periodo">Fecha Fin</label>
        </div>
        <x-select-tipo-doc id="Eidtd" name="idtd" :tdoc="$tdoc" categoria="MOV" label="Tipo Documento"/>
        <div class="form-floating">
            <input type="text" class="form-control" id="Enrodoc" name="nrodoc" placeholder="Ejm. 232-2023">
            <label for="entidad">Nro Documento</label>
        </div>

        <div class="form-floating">
            <input type="date" class="form-control" id="Efechadoc" name="fechadoc" >
            <label for="periodo">Fecha Doc</label>
        </div>

        <x-file-upload 
            id="Earchivo1" 
            name="archivo"
            label="Archivo"
        />
    </div>
</div>