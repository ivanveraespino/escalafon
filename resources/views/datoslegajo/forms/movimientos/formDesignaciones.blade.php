<div class="row">
    <input type="hidden" class="form-control" id="tipo_movimiento" name="tipo_movimiento" value="3"/>
    <div class="form-group">
        <label class="required">Trabajador<span class="required">*</span></label>
        <select name="personal_id" id="dnipcv" class="form-control"  >
        </select>
    </div>

    <div class="form-group">
        <label for="entidad"class="required">Unidad Organica<span class="required">*</span></label>
        <select name="oficina_d" id="Eoficina_d" class="form-control" >
        <option value="">Seleccione una opción...</option>
        </select>
    </div>

    <div class="form-group">
        <label for="entidad"class="required">Cargo<span class="required">*</span></label>
        <select name="cargo" id="Ecargo" class="form-control" >
        <option value="">Seleccione una opción...</option>
        </select>
    </div>

    <div class="form-group">
        <label for="periodo">Fecha Inicio</label>
        <input type="date" class="form-control" id="Efecha_ini" name="fecha_ini" >
    </div>

    <div class="form-group">
        <label for="periodo">Fecha Fin</label>
        <input type="date" class="form-control" id="Efecha_fin" name="fecha_fin" >
    </div>

</div>

<div class="row">
    <div class="form-group">
        <label for="regimen">Tipo de Documento</label>
        <select id="Eidtd" name="idtd" class="form-control">
            <option value="" selected></option>
            @foreach($tdoc as $t)
                @if($t->categoria && in_array("09", json_decode($t->categoria, true))) 
                <option value="{{ $t->id ?? '' }}">{{ $t->nombre }}</option>
                @endif
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="entidad">Nro Documento</label>
        <input type="text" class="form-control" id="Enrodoc" name="nrodoc" placeholder="Ejm. 232-2023">
    </div>

    <div class="form-group">
        <label for="periodo">Fecha Doc</label>
        <input type="date" class="form-control" id="Efechadoc" name="fechadoc" >
    </div>

    <div class="form-group">
        <label for="archivo">Archivo</label>
        <input type="file" accept="application/pdf" class="form-control archivo" name="archivo">
    </div>
    
    <div class="form-group">
        <label for="archivo">Nro folios</label>
        <input type="number" class="form-control nro_folios" name="nro_folios">
    </div>
</div>