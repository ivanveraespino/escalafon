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

<div class="modal fade" id="modalFormEdit" tabindex="-1" role="dialog" aria-labelledby="ComModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Ingresar Compensación</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            <form id="formEdit"  class="d-flex flex-column gap-3"  enctype="multipart/form-data">
                
                        <input type="hidden" class="form-control" id="Edid" name="id"/>

                        <div class="form-floating">                        
                            <select id="Edtipo_compensacion" name="tipo_compensacion" class="form-select">
                                <option value=""></option>
                                @foreach($tcomp as $t)
                                <option value="{{ $t->id }}" >{{ $t->nombre }}</option>
                                @endforeach
                            </select>   
                            <label for="entidad" class="required">Tipo de compensacion<span class="required">*</span></label>
                        </div>
                        <x-select-tipo-doc id="Edidtd" name="idtd" :tdoc="$tdoc" categoria="COM" label="Tipo Documento"/>
                        <div class="form-floating">
                            <input type="text" class="form-control" id="Ednrodoc" name="nrodoc" placeholder="Ejm. 232-2023">
                            <label for="entidad">Nro Documento</label>
                        </div>

                        <div class="form-floating">
                            <input type="date" class="form-control" id="Edfecha_documento" name="fecha_documento" >
                            <label for="periodo">Fecha Documento</label>
                        </div>

                        <x-file-upload 
                                id="Edarchivo" 
                                name="archivo"
                                label="Archivo"
                        />
                    
                <button type="submit" id="btnSubmit" class="btn btn-success ml-auto mr-0 mt-2"  style="margin-right: 10px;">Guardar</button>
            </form>
            
            </div>
        </div>
    </div>
</div>