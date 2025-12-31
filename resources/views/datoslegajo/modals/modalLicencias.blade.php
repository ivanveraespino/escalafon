
<style>
    .required {
        color: red;
    }
    .modal-title {
        font-size: 20px;
        font-weight: bold;
        color: #333;
        text-align: center;
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }


</style>

<div class="modal fade" id="modalFormEdit" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Licencias</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEdit" class="d-flex flex-column"  enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" class="form-control" id="Edid" name="id"/>
                        <div class="form-row gap-3">
                            <div class="form-floating">
                                <textarea id="Eddescripcion" class="form-control" name="descripcion" 
                                style="text-align: left; direction: ltr;"></textarea>
                                <label >Motivo</label>
                            </div>
                            <x-select-tipo-doc id="Edidtd" name="idtd" :tdoc="$tdoc" categoria="VAC" label="Tipo Documento"/>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="Ednrodoc" name="nrodoc" placeholder="Ejm. 232-2023" required>
                                <label for="entidad" class="required"><span class="required">*</span>N° Documento</label>
                            </div>  
                            <x-file-upload id="Edarchivo" name="archivo" label="Archivo"/>

                            <div class="form-floating" id="obs_vac">
                                <input type="text" class="form-control" id="Edobservaciones" name="observaciones" placeholder="obs">
                                <label for="entidad">Observaciones</label>
                            </div>
     
                            <div id="acuenta" class="form-floating">
                                <select class="form-select" id="Edacuentavac" name="acuentavac" onchange="CambioPeriodo(this)">
                                    <option hidden selected></option>
                                    <option value="NO"selected>NO</option>
                                    <option value="SI">SI</option>
                                </select>     
                                <label class="required">A cuenta vac.<span class="required">*</span></label>   
                            </div>
                            <div class="form-floating" id="cong">                        
                                <select class="form-select" id="Edcongoce" name="congoce">
                                    <option hidden selected></option>
                                    <option value="NO"selected>NO</option>
                                    <option value="SI">SI</option>
                                </select>     
                                <label class="required">Con goce<span class="required">*</span></label>
                            </div>
                            <div class="form-floating" id="desde_vlp">
                                <input type="date" class="form-control" id="Edfecha_ini" name="fecha_ini" placeholder="Ejm. 232-2023" onchange="validar_vlp(validacion)" required>
                                <label for="entidad" class="required"><span class="required">*</span>Desde</label>
                            </div>  
                            <div class="form-floating" id="hasta_vlp">
                                <input type="date" class="form-control" id="Edfecha_fin" name="fecha_fin" placeholder="Ejm. 232-2023" onchange="validar_vlp(validacion)" required>
                                <label for="entidad" class="required"><span class="required">*</span>Hasta</label>
                            </div>  
                            <div class="form-floating" id="dias_vlp">
                                <input type="number" class="form-control" id="Eddias" name="dias"  readonly>
                                <label for="periodo">Dias</label>
                            </div>
                            <div class="form-floating acuenta_vac_cont">
                                <input type="number" class="form-control" id="Edperiodo" name="periodo" placeholder="periodo" onchange="validar_vlp(validacion)">
                                <label for="entidad" class="required">Periodo<span class="required">*</span></label>
                            </div>
                            <div class="form-floating acuenta_vac_cont" >
                                <select class="form-select" id="Edmes" name="mes"style="flex-grow: 1;" onchange="validar_vlp(validacion)">
                                    <option hidden selected></option>
                                </select> 
                                <label for="archivo" class="required">Mes<span class="required">*</span></label>
                            </div>
                            <!-- Contenedor VLP -->
                            <div class="col-6 mb-1 acuenta_vac_cont">
                                <!-- Contenedor con fondo y sin padding -->
                                <div class="row gx-0" style="background-color: #f5f5f5; border-radius: 5px;">
                                        <div class="col vlp-item">
                                        <label for="VC" class="d-block text-center">CR</label>
                                            <input type="number" class="form-control form-control-sm text-center" id="CR2" readonly>
                                        </div>
                                        <div class="col vlp-item">
                                        <label for="VC" class="d-block text-center" id="dlv_vlp" >V</label>
                                            <input type="number" class="form-control form-control-sm text-center" id="VC2" readonly>
                                        </div>
                                        <div class="col vlp-item">
                                            <label for="VC" class="d-block text-center" id="dll_vlp">L</label>
                                            <input type="number" class="form-control form-control-sm text-center" id="LC2" readonly>
                                        </div>
                                        <div class="col vlp-item">
                                            <label for="VC" class="d-block text-center" id="dlp_vlp" >P</label>
                                            <input type="number" class="form-control form-control-sm text-center" id="PC2" readonly>
                                        </div>
                                        <div id="drestantes" class="col">
                                            <label for="periodo">Usado</label>
                                            <input type="number" class="form-control form-control-sm text-center" id="Eddiasr" name="diasr" disabled>
                                        </div>
                                        <div id="mensaje-adelantado_vlp" class="text-left" style="display: none;">
                                            * adelantado
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div id="dcronogramaWarning" class="alert alert-danger mt-1" style="display: none;"></div>  
                        <div id="ddiasWarning" class="alert alert-danger" style="display: none;"></div>  
                    <button type="submit" id="btnSubmit" class="btn btn-success ml-auto mr-0 mt-2"  style="margin-right: 10px;">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>