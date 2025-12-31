<!-- resources/views/datosficha/estudios.blade.php -->
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
   
<div class="modal fade" id="modalFormEdit" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Ingresar Estudios</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            <form id="formEdit" class="d-flex flex-column  gap-3"  enctype="multipart/form-data">
                @csrf
                <input type="hidden" class="form-control" id="Edid" name="id"/>
                <div class="d-flex flex-column gap-3">
                    <div class="form-row gap-3">
                        <div class="form-floating">
                            <select id="Ednivel_educacion" name="nivel_educacion" class="form-control" required>
                                <option value=""></option>
                                <option value="PRIMARIA COMPLETA" >PRIMARIA COMPLETA</option>
                                <option value="PRIMARIA INCOMPLETA" >PRIMARIA INCOMPLETA</option>
                                <option value="SECUNDARIA COMPLETA" >SECUNDARIA COMPLETA</option>
                                <option value="SECUNDARIA INCOMPLETA" >SECUNDARIA INCOMPLETA</option>
                                <option value="UNIVERSITARIO COMPLETA" >UNIVERSITARIO COMPLETA</option>
                                <option value="UNIVERSITARIO INCOMPLETA" >UNIVERSITARIO INCOMPLETA</option>
                                <option value="EGRESADO" >EGRESADO</option>
                                <option value="ESTUDIANTE" >ESTUDIANTE</option>
                                <option value="BACHILLER" >BACHILLER</option>
                                <option value="TECNICO COMPLETA">TECNICO COMPLETA</option>
                                <option value="TECNICO INCOMPLETA">TECNICO INCOMPLETA</option>
                                <option value="TITULADO" >TITULADO</option>
                                <option value="MAESTRIA COMPLETA" >MAESTRIA COMPLETA</option>
                                <option value="MAESTRIA INCOMPLETA" >MAESTRIA INCOMPLETA</option>
                                <option value="DOCTORADO COMPLETA" >DOCTORADO COMPLETA</option>
                                <option value="DOCTORADO INCOMPLETA" >DOCTORADO INCOMPLETA</option>
                            </select>                           
                            <label for="entidad" class="required">Educación<span class="required">*</span></label>
                        </div>
    
                        <div class="form-floating">
                            <input type="text" class="form-control" id="Edcentroestudios" name="centroestudios" placeholder="x">
                            <label for="periodo" class="required">Centro de Estudios<span class="required">*</span></label>
                        </div>
    
                        <div class="form-floating" hidden>
                            <select id="Edestado" name="estado" class="form-select">
                                <option value=""></option>
                                <option value="COMPLETA" >COMPLETA</option>
                                <option value="INCOMPLETA" >INCOMPLETA</option>
                            </select> 
                            <label for="periodo">Estado</label>
                        </div>
    
                        <div class="form-floating">
                            <input type="date" class="form-control" id="Edfecha_ini" name="fecha_ini" >
                            <label for="periodo">Fecha inicio</label>
                        </div>
    
                        <div class="form-floating">
                            <input type="date" class="form-control" id="Edfecha_fin" name="fecha_fin" >
                            <label for="periodo">Fecha fin</label>
                        </div>
                    </div>
    
                    <div class="form-row gap-3">
                        <div class="form-floating despecialidad-style">
                            <input type="text" class="form-control" id="Edespecialidad" name="especialidad">                         
                            <label for="periodo">Especialidad</label>
                        </div>
                        <x-select-tipo-doc id="Edidtd" name="idtd" :tdoc="$tdoc" categoria="FOR" label="Tipo Documento"/>

                        <x-file-upload id="Edarchivo" name="archivo" label="Archivo"/>

                    </div>
                </div>
                <button id="btnSubmit" type="submit" class="btn btn-success ml-auto mr-0 "  style="margin-right: 10px;">Guardar</button>
            </form>
            </div>
        </div>
    </div>
</div>
