
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">RECONOCIMIENTO</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body pr-4">
                <form id="formEdit"  class="d-flex flex-column gap-3"  enctype="multipart/form-data" >
                    @csrf
                        <input type="hidden" class="form-control" id="Edid" name="id"/>

                        
                            <div class="form-floating">
                                <input type="text" class="form-control" id="Eddescripcion" name="descripcion" placeholder="Ejm. FELICITACIONES, CONDECORACIONES">
                                <label for="entidad"class="required">Descripción<span class="required">*</span></label>
                            </div>
                            <x-select-tipo-doc id="Edidtd" name="idtd" :tdoc="$tdoc" categoria="REC" label="Tipo Documento"/>

                            <div class="form-floating">
                                <input type="text" class="form-control" id="Ednrodoc" name="nrodoc" placeholder="Ejm. 232-2023">
                                <label for="entidad">Nro Documento</label>
                            </div>

                            <div class="form-floating">
                                <input type="date" class="form-control" id="Edfechadoc" name="fechadoc" >
                                <label for="entidad">Fecha documento</label>
                            </div>
                            
                            <x-file-upload 
                                    id="Edarchivo" 
                                    name="archivo"
                                    label="Archivo"
                            />                     
                   
                    <button id="btnSubmit" type="submit" class="btn btn-success ml-auto mr-0 mt-2"  style="margin-right: 10px;">Guardar</button>

                </form>
            </div>
        </div>
    </div>
</div>