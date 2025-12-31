<!-- resources/views/datosficha/condicionlab.blade.php -->

       
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

<!-- Modal de actualización -->
<div class="modal fade" id="modalFormEdit" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Experiencia Laboral</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEdit" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" class="form-control" id="Edid" name="id"/>
                        <div class="form-group ">
                            <div class="form-floating">
                                <select id="Edtipo_entidad" name="tipo_entidad" class="form-control" required>
                                    <option value=""></option>
                                    <option value="PUBLICA" >PUBLICA</option>
                                    <option value="PRIVADA" >PRIVADA</option>
                                </select> 
                                <label for="entidad" class="required">Tipo de Entidad<span class="required">*</span></label>
                            </div>
                            <div class="form-floating">
                                <input type="text" class="form-control" id="Edentidad" name="entidad" required placeholder="x">
                                <label for="update_entidad" class="required">Entidad<span class="required">*</span></label>
                            </div>
                            <div class="form-floating ">
                                <input type="text" class="form-control" id="Edcargo" name="cargo" required placeholder="x">
                                <label for="update_periodo" class="required">Cargo<span class="required">*</span></label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-floating ">
                                <input type="date" class="form-control" id="Edfecha_ini" name="fecha_ini" placeholder="x">
                                <label for="periodo">Desde</label>
                            </div>
                            <div class="form-floating ">
                                <input type="date" class="form-control" id="Edfecha_fin" name="fecha_fin" placeholder="x">
                                <label for="periodo">Hasta</label>
                            </div>
                        </div>
                        <x-select-tipo-doc id="Edidtd" name="idtd" :tdoc="$tdoc" categoria="EXP" label="Tipo Documento" />

                        <div class="form-group">
                            <x-file-upload 
                                id="Edarchivo" 
                                name="archivo"
                            />
                        </div>
                        
                    <button id="btnSubmit" type="submit" class="btn btn-success ml-auto mr-0 mt-2"  style="margin-right: 10px;">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

@include('datoslegajo.modal_files')