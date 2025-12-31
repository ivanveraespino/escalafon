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
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEdit"  class="d-flex flex-column gap-3 px-3"  enctype="multipart/form-data" >
                    @csrf
                    <input type="hidden" class="form-control" id="Edid" name="id"/>

                    <div class="d-flex flex-column gap-4 w-100">
                        <div class="flex-1 form-row gap-4 w-100">
                            <div class="col ini_vinculo ">
                                <label class="required" >Cargo<span class="required">*</span></label>
                                <select name="id_cargo" id="Edcargo" class="form-select">
                                </select>
                            </div>

                            <div class="col ini_vinculo">
                                <label >Unidad Organica</label>
                                <select name="id_unidad_organica" id="Edunidad_organica" class="select-form">
                                </select>
                            </div>

                            <div class="col ini_vinculo">
                                <label class="required" >Dependencia<span class="required">*</span></label>
                                <select name="id_depens" id="Eddepens" class="form-select">
                                </select>
                            </div>
                        </div>

                        <div class="form-row gap-4 w-100">
                            <div class="d-flex flex-column gap-4 flex-1 w-100">
                                <div class="form-row gap-4">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="Edfecha_ini" name="fecha_ini" required>
                                        <label for="Efecha_ini">Fecha Inicio<span class="required">*</span></label>
                                    </div>
                                
                                    <div class="form-floating">
                                        <select id="Edid_regimen" name="id_regimen" class="form-select">
                                            <option value=""></option>
                                            @foreach($reg as $regbd)
                                                <option value="{{ $regbd->id ?? '' }}">{{ $regbd->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <label for="Eid_regimen">Régimen</label>
                                    </div>
                                    <div class="form-floating">
                                        <select id="Edid_condicion_laboral" name="id_condicion_laboral" class="form-select">
                                            <option value=""></option>
                                            @foreach($conlab as $conlab)
                                                <option value="{{ $conlab->id ?? '' }}">{{ $conlab->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <label for="id_condicion_laboral">Condicion Laboral</label>
                                    </div>
                                    <x-select-tipo-doc id="Edid_tipo_documento" name="id_tipo_documento" :tdoc="$tdoc" categoria="VIN" label="Tipo Documento"/>
                                </div>

                                <div class="form-row gap-4">

                                    <div class="ini_vinculo col">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="Ednro_doc" name="nro_doc" placeholder="Ejm. 232-2023">
                                            <label for="Enro_doc">Nro Documento</label>
                                        </div>
                                    </div>

                                    <x-file-upload 
                                        id="Edarchivo" 
                                        name="archivo"
                                        label="Vinculo"
                                        nameFolio="nro_folio"
                                    />
                                </div>
                            </div>

                            <div class="contrato flex-1 d-flex flex-column gap-4 w-100">
                                <div class="flex-1 form-row gap-4 contrato" >
                                    
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="Edfecha_fin" name="fecha_fin">
                                        <label for="Efecha_fin">Fecha Fin</label>
                                    </div>

                                    <div class="form-floating">
                                        <select id="Edid_motivo_fin_vinculo" name="id_motivo_fin_vinculo" class="form-select">
                                            <option value=""></option>
                                            @foreach($vin_fin as $vin_fin)
                                                <option value="{{ $vin_fin->id ?? '' }}">{{ $vin_fin->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <label for="Eid_motivo_fin_vinculo">Motivo de Cese</label>
                                    </div>
                       
                                    <x-select-tipo-doc id="Edid_tipo_documento_fin" name="id_tipo_documento_fin" :tdoc="$tdoc" categoria="DES" label="Tipo Documento"/>
                                </div>
                                <div class="flex-1 form-row gap-4 contrato">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="Ednro_doc_fin" name="nro_doc_fin" placeholder="Ejm. 232-2023">
                                        <label for="Enro_doc_fin">Nro Documento</label>
                                    </div>
                                    <x-file-upload 
                                        id="Edarchivo_cese" 
                                        name="archivo_cese"
                                        label="Vinculo"
                                        nameFolio="nro_folio"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <button id="btnSubmit" type="submit" class="btn btn-success ml-auto mr-0 mt-2"  style="margin-right: 10px;">Guardar</button>
                </form> 
            </div>
        </div>
    </div>
</div>