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
<div class="modal fade" id="modalFormEdit" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Agregar Idioma</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEdit"  class="d-flex flex-column gap-3"  enctype="multipart/form-data" >
                    @csrf
                    <input type="hidden" class="form-control" id="Edid" name="id"/>

                    <div class="form-floating">
                        <input type="text" class="form-control" id="Edidioma" name="idioma" > 
                        <label class="required">Idioma<span class="required">*</span></label>
                    </div>

                    <div class="form-floating">
                        <select id="Edlectura" name="lectura" class="form-control">
                            <option value=""></option>
                            <option value="Con facilidad" >Con facilidad</option>
                            <option value="Sin facilidad" >Sin facilidad</option>
                        </select>  
                        <label >Lectura</label>
                    </div>

                    <div class="form-floating">
                        <select id="Edhabla" name="habla" class="form-control">
                            <option value=""></option>
                            <option value="Con facilidad" >Con facilidad</option>
                            <option value="Sin facilidad" >Sin facilidad</option>
                        </select>                      
                        <label >Habla</label>
                    </div>

                    <div class="form-floating">
                        <select id="Edescritura" name="escritura" class="form-control">
                            <option value=""></option>
                            <option value="Con facilidad" >Con facilidad</option>
                            <option value="Sin facilidad" >Sin facilidad</option>
                        </select>                      
                        <label >Escritura</label>
                    </div>
                    <x-select-tipo-doc id="Edidtd" name="idtd" :tdoc="$tdoc" categoria="IDI" label="Tipo Documento" />

                    <x-file-upload 
                        id="Edarchivo" 
                        name="archivo"
                    />

                    <button id="btnSubmit" type="submit" class="btn btn-success ml-auto mr-0 mt-2"  style="margin-right: 10px;">Guardar</button>
                </form> 
            </div>
        </div>
    </div>
</div>