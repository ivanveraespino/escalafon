<form id="cronogramaForm" enctype="multipart/form-data">
    <input type="hidden" class="form-control" id="Eidvo" name="idvo" >
    <input type="hidden" class="form-control" id="Ediasrep">
    <div class="row" id="personacv">
        <div class="col-12" >
            <label class="required">Personal<span class="required">*</span></label>
            <div class="input-group mb-3">
                <select name="personal_id" id="dnipcv" class="form-control" onchange="validarDias()">
                                        </select>
            </div>
            <div class="dropdown-menu" id="suggestions2"></div>
        </div>
        
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-12 mb-3" id="motivo">                        
                <label >Motivo</label>
                <input type="text" class="form-control" id="Cobservaciones" name="observaciones" >
            </div>

            <div class="col-3 mb-3">
                <label for="regimen">Tipo Doc</label>
                <select id="Cidtd" name="idtd" class="form-control" required>
                            <option hidden selected></option>
                    @foreach($tdoc as $t)
                        @if($t->categoria && in_array("CRO", json_decode($t->categoria, true))) 
                            <option value="{{ $t->id }}" data-categoria="{{ $t->categoria }}">{{ $t->nombre }}</option>
                        @endif
                    @endforeach
                </select> 
            </div>

            <div class="col-7 mb-3">
                <label for="entidad">N° Documento</label>
                <input type="text" class="form-control" id="Cnrodoc" name="nrodoc" placeholder="Ejm. 232-2023" required>
            </div>
            <div class="form-group col-md-2">
                <label for="archivo">Archivo</label>
                <input type="file" accept="application/pdf" class="form-control archivo" name="archivo">
            </div>
            <div class="form-group col-md-2 d-none">
                <input type="number" class="form-control nro_folios" name="nro_folios">
            </div>
        </div>
    </div>
    <div class="row align-items-center">
        <!-- Periodo -->
        <div class="col-3 mb-1">
            <label for="Cperiodo" class="required">Periodo<span class="required">*</span></label>
            <input type="number" class="form-control form-control-sm" id="Cperiodo" onchange="validarDias()" name="periodo" required>
        </div>

        <!-- Contenedor VLP -->
        <div class="col-7 mb-1">
            <!-- Contenedor con fondo y sin padding -->
            <div class="row gx-0 py-2" style="background-color: #f5f5f5; border-radius: 5px;">
                <div class="col">
                    <label for="VC" class="d-block text-center">Cr</label>
                    <input type="number" class="form-control form-control-sm text-center" id="CR" readonly>
                </div>
                <div class="col">
                    <label for="VC" class="d-block text-center" id="lv">V</label>
                    <input type="number" class="form-control form-control-sm text-center" id="VC" readonly>
                </div>
                <div class="col">
                    <label for="VC" class="d-block text-center" id="ll">L</label>
                    <input type="number" class="form-control form-control-sm text-center" id="LC" readonly>
                </div>
                <div class="col">
                    <label for="VC" class="d-block text-center" id="lp">P</label>
                    <input type="number" class="form-control form-control-sm text-center" id="PC" readonly>
                </div>
                <div id="mensaje-adelantado" class="text-left " style="display: none;">
                    * adelantado
                </div>

            </div>
        </div>
        <!-- Total -->
        <div class="col-2 mb-1">
            <label for="diasdperiodo">Usado</label>
            <input type="number" class="form-control form-control-sm" id="diasdperiodo" readonly>
        </div>
    </div>
    <div class="col-3 mt-4 d-none">
            <input type="checkbox" class="form-check-input" id="toggleDateRange">
            <label class="form-check-label" for="toggleDateRange">Agregar otra fecha</label>
        </div>
    <div id="dateInputs" class="row">
        <div class="col-md-12" id="fechamesColumn">
            <label class="required">Mes<span class="required">*</span></label>
            <select id="Emes1" name="mes2[]" class="form-control" style="flex-grow: 1;" onchange="validarDias()" required>
            </select>                          
        </div>
        <div class="col-md-6 "style="display: none;" id="mes2">
            <label for="mes">Mes 2</label>
            <select id="Emes2" name="mes2[]" class="form-control" style="flex-grow: 1;">
            </select>                         
        </div>
        <div class="col-md-12" id="fechaIniColumn">
            <label class="required">Fecha Inicio<span class="required">*</span></label>
            <input type="date" class="form-control" id="fecha_ini" name="fecha_ini[]" onchange="validarDias()" required>
        </div>
        <div class="col-md-6 "style="display: none;" id="fi2">
            <label>Fecha Inicio</label>
            <input type="date" class="form-control" id="fecha_ini2" name="fecha_ini[]" >

        </div>
        <div class="col-md-12" id="fechaFinColumn">
            <label class="required">Fecha Fin<span class="required">*</span></label>
            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin[]" onchange="validarDias()" required>
        </div>
        <div class="col-md-6 "style="display: none;" id="ff2">
            <label for="fecha_fin">Fecha Fin</label>
            <input type="date" class="form-control" id="fecha_fin2" name="fecha_fin[]">
        </div>
        <div class="col-md-12" id="diasColumn">
            <label>Dias</label>
            <input type="int" class="form-control" id="fdi" name="dias2[]" onchange="validarDias()" readonly>
        </div>
        <div class="col-md-6 "style="display: none;" id="di2">
            <label>Dias</label>
            <input type="int" class="form-control" id="fdi2" name="dias2[]" >
        </div>
    </div>
    <div id="error-message" class="alert alert-danger text-center mt-2" role="alert" style="display:none;">
        <strong>¡Advertencia!</strong> No puedes programar más de 30 días.
    </div>

    <button id="btnrepo" onclick="guardartcron()" class="btn btn-primary mt-3">Guardar</button>
</form>