<!-- resources/views/datosficha/familiares.blade.php -->
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

<div class="modal fade" id="modalFormEdit" tabindex="-1" role="dialog" aria-labelledby="FormFamLabel">
    <div class="modal-dialog" style="max-width: 1600px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Editar Familiar</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body ">
                <form id="formEdit" class="d-flex flex-column"  enctype="multipart/form-data" >
                    @csrf
                    
                    <div class="form-row gap-3 ">
                        <input type="hidden" class="form-control" id="Edid" name="id"/>
                        
                        <div class="col box2">

                            <div class="form-row gap-3">
                        
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="Edapaterno" name="apaterno" required placeholder="x">
                                    <label class="required">Apellido Paterno<span class="required">*</span></label>
                                </div>

                                <div class="form-floating">
                                    <input type="text" class="form-control" id="Edamaterno" name="amaterno" required placeholder="x">
                                    <label class="required">Apellido Materno<span class="required">*</span></label>
                                </div>

                                <div class="form-floating">
                                    <input type="text" class="form-control" id="Ednombres" name="nombres" required placeholder="x">    
                                    <label class="required">Nombres<span class="required">*</span></label>
                                </div>

                            </div>

                            <div class="form-row gap-3">

                                <div class="form-floating">
                                    <input type="date" class="form-control" id="Edfechanacimiento" name="fechanacimiento" placeholder="x">
                                    <label>Fecha de Nacimiento</label>
                                </div>

                                <div class="form-floating" >
                                    <input type="text" class="form-control" id="Edocupacion" name="ocupacion" placeholder="x">
                                    <label>Ocupacion</label>
                                </div>  
                
                            </div>

                            <div class="form-row gap-3">
                                <div class="form-floating">
                                    <select id="Edvive" name="vive" class="form-control">
                                        <option value=""></option>
                                        <option value="SI" >SI</option>
                                        <option value="NO" >NO</option>
                                    </select>                      
                                    <label>Vive</label>
                                </div>

                                <div class="form-floating destadocv">
                                    <select id="Edestadocivil" name="estadocivil" class="form-select" >
                                        <option value=""></option>
                                        <option value="SOLTERO" >SOLTERO(A)</option>
                                        <option value="CASADO">CASADO(A)</option>
                                        <option value="VIUDO" >VIUDO(A)</option>
                                        <option value="DIVORCIADO">DIVORCIADO(A)</option>
                                        <option value="CONVIVIENTE">CONVIVIENTE</option>
                                    </select>
                                    <label>Estado Civil</label>
                                </div>
                                <x-select-tipo-doc id="Edidtd" name="idtd" :tdoc="$tdoc" categoria="DAF" label="Tipo Documento" />

                                <x-file-upload id="Edarchivo" name="archivo" label="Copia DNI" modal=true  modal=true/>
                            </div>
                        </div>



                        <div class="col box2">
                            
                            <div class="form-row flex-1 gap-3">
                                <div class="form-floating ">
                                    <select id="Edparentesco" name="parentesco" class="form-select" required>
                                        <option value=""></option>
                                        <option value="CONYUGUE">CONYUGUE</option>
                                        <option value="PADRE">PADRE</option>
                                        <option value="MADRE">MADRE</option>
                                        <option value="HIJO">HIJO</option>
                                    </select>     
                                    <label class="required">Parentesco<span class="required">*</span></label>                   
                                </div> 
                                
                                <div class="form-floating dparent-hab-style" >
                                    <input type="text" class="form-control" id="Edlugarlaboral" name="lugarlaboral" placeholder="x">
                                    <label>Dirección Trabajo</label>
                                </div>  
                            </div>

                            <div class="form-row flex-1 gap-3">
                                <div class="form-floating">
                                    <select id="Edderecho_habiente" name="derecho_habiente" class="form-select">
                                        <option value="NO">NO</option>
                                        <option value="SI">SI</option>
                                    </select>    
                                    <label>Derecho Hab.</label>                    
                                </div> 
                                <x-select-tipo-doc id="Edid_tipodocvin" name="id_tipodocvin" :tdoc="$tdoc" categoria="DER" label="Tipo Documento" divClass="dderecho-hab-style"/>
                                <div class="form-group dderecho-hab-style">
                                    <x-file-upload id="Edarchivo_vinculo" name="archivo_vinculo" label="Vinculo"/>
                                    <input type="hidden" class="form-control nro_folios" name="nro_folios2">
                                    
                                </div>
                            </div>

                            <div class="form-row flex-1 gap-3">
                                <div class="form-floating ">
                                    <select id="Edemergencia" name="emergencia" class="form-select">
                                        <option value="NO" >NO</option>
                                        <option value="SI" >SI</option>
                                    </select>                      
                                    <label>Emergencia</label>
                                </div>

                                <div class="form-floating demergencia-style">
                                    <input type="text" class="form-control" id="Eddireccion" name="direccion" placeholder="x">
                                    <label>Dirección</label>
                                </div>

                                <div class="form-floating demergencia-style">
                                    <input type="text" class="form-control" id="Edtelefono" name="telefono" placeholder="x">
                                    <label>Teléfono</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success ml-auto mr-0" id="btnSubmit"  style="margin-right: 10px;">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>