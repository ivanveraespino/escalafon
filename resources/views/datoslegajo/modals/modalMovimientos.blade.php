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

    .form-floating{
        flex: 1;
    }


</style>
    <!-- Modal de actualización -->
<div class="modal fade " id="modalFormEdit" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Agregar Idioma</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEdit"  class="d-flex flex-column gap-3 px-3"  enctype="multipart/form-data" >
                    @csrf
                    <input type="hidden" class="form-control" id="Edid" name="id"/>

                    <div class="d-flex flex-column gap-4">
                        <div class="form-row gap-3">
                            <input type="hidden" class="form-control" id="tipo_movimiento" name="tipo_movimiento" value="2"/>
                            <div class="col">
                                <label for="entidad"class="required">Unidad Organica Origen<span class="required">*</span></label>
                                <select name="unidad_organica" id="Edunidad_organica" class="form-control select2" >
                                </select>
                                    
                            </div>

                            <div class="col">
                                <label for="entidad"class="required">Unidad Organica Destino<span class="required">*</span></label>
                                <select name="unidad_organica_destino" id="Edunidad_organica_destino" class="form-control" >
                                </select>
                            </div>

                            <div class="col">
                                <label for="entidad"class="required">Cargo<span class="required">*</span></label>
                                <select name="cargo" id="Edcargo" class="form-control" >
                                </select>
                            </div>

                        </div>

                        <div class="form-row gap-3">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="Edfecha_ini" name="fecha_ini" >
                                <label for="periodo">Fecha Inicio</label>
                            </div>

                            <div class="form-floating">
                                <input type="date" class="form-control" id="Edfecha_fin" name="fecha_fin" >
                                <label for="periodo">Fecha Fin</label>
                            </div>

            
                            <x-select-tipo-doc id="Edidtd" name="idtd" :tdoc="$tdoc" categoria="MOV" label="Tipo Documento"/>

                            <div class="form-floating">
                                <input type="text" class="form-control" id="Ednrodoc" name="nrodoc" placeholder="Ejm. 232-2023">
                                <label for="entidad">Nro Documento</label>
                            </div>

                            <div class="form-floating">
                                <input type="date" class="form-control" id="Edfechadoc" name="fechadoc" >
                                <label for="periodo">Fecha Doc</label>
                            </div>

                            <x-file-upload 
                                id="Edarchivo" 
                                name="archivo"
                                label="Archivo"
                            />
                        </div>
                    </div>

                   
                    <button id="btnSubmit" type="submit" class="btn btn-success ml-auto mr-0 mt-2"  style="margin-right: 10px;">Guardar</button>
                </form> 
            </div>
        </div>
    </div>
</div>