<div class="d-flex flex-column gap-4">
    <input type="hidden" class="form-control" id="id_condicion_laboral" name="id_condicion_laboral" value="3"/>

    <div class="form-row gap-4">
        <div class="col ini_vinculo ">
            <label class="required" >Cargo<span class="required">*</span></label>
            <select name="id_unidad_organica" id="Eid_cargo2"  >
            </select>
        </div>

        <div class="col ini_vinculo">
            <label >Unidad Organica</label>
            <select name="id_unidad_organica" id="Eid_oficina2"  >
            </select>
        </div>

        <div class="col ini_vinculo">
            <label class="required" >Dependencia<span class="required">*</span></label>
            <select name="id_depens" id="Eid_depens2" >
            </select>
        </div>
    </div>

    <div class="form-row gap-4">
    
            <div class="form-floating">
                <input type="date" class="form-control" id="Efecha_ini" name="fecha_ini">
                <label for="Efecha_ini">Fecha Inicio<span class="required">*</span></label>
            </div>

            <div class="form-floating">
                <select id="Eid_regimen" name="id_regimen" class="form-select">
                    <option value=""></option>
                    @foreach($reg as $regbd)
                        <option value="{{ $regbd->id ?? '' }}">{{ $regbd->nombre }}</option>
                    @endforeach
                </select>
                <label for="Eid_regimen">Régimen</label>
            </div>

            <x-select-tipo-doc id="Eid_tipo_documento" name="id_tipo_documento" :tdoc="$tdoc" categoria="VIN" label="Tipo Documento" />
            <div class="form-floating">
                <input type="text" class="form-control" id="Enro_doc" name="nro_doc" placeholder="Ejm. 232-2023">
                <label for="Enro_doc">Nro Documento</label>
            </div>

            <x-file-upload 
                id="Earchivo2" 
                name="archivo"
                label="Archivo"
            />
    </div>    
</div>
