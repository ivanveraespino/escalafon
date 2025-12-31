<!-- resources/views/datosficha/arch_adicionales.blade.php -->
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
                <h5 class="modal-title" id="modalTitle">Ingresar Archivo</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEdit" class="d-flex flex-column"  enctype="multipart/form-data">
                    <input type="hidden" class="form-control" id="Edid" name="id"/>
                    <div class="form-row gap-3">

                  
                        <x-select-tipo-doc id="Edtipo_archivo" name="tipo_archivo" :tdoc="$tdoc" categoria="OTR" label="Tipo Archivo"/>

                        <div class="form-floating">             
                            <select id="Edcategoria_archivo" name="categoria_archivo" class="form-select">
                                <option value=""></option>
                                @foreach($cat as $t)
                                <option value="{{ $t->id }}" data-categoria="{{ $t->nombre }}">{{ $t->nombre }}</option>
                                @endforeach
                            </select>   
                            <label class="required">Categoria de Archivo<span class="required">*</span></label>            
                        </div>

                        <x-file-upload id="Edarchivo" name="archivo" label="Copia DNI"/>

                    </div>
                    <button type="submit" id="btnSubmit" class="btn btn-success ml-auto mr-0"  style="margin-right: 10px;">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>