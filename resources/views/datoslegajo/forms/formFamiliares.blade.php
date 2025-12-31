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
    .form-group {
        flex:1;
        min-width: 200px;
        margin: 0;
    }
    .form-floating{
        flex:1;
        margin-bottom: 24px;
        min-width: 200px;
    }

    .linea-vertical {
        width: 1px; /* Grosor de la línea */
        height: 200px; /* Altura de la línea */
        background-color: #BBBBBB; /* Color de la línea */
        margin: 0 auto; /* Centrar horizontalmente */
    }

    .box2{
        min-width: 300px;
    }
        
</style>

    <div class="form-row gap-3 ">
        <input type="hidden" class="form-control" id="personal_id" name="personal_id" />
        
        <div class="col box2">

            <div class="form-row gap-3">
        
                <div class="form-floating">
                    <input type="text" class="form-control" id="Eapaterno" name="apaterno" required placeholder="x">
                    <label class="required">Apellido Paterno<span class="required">*</span></label>
                </div>

                <div class="form-floating">
                    <input type="text" class="form-control" id="Eamaterno" name="amaterno" required placeholder="x">
                    <label class="required">Apellido Materno<span class="required">*</span></label>
                </div>

                <div class="form-floating">
                    <input type="text" class="form-control" id="Enombres" name="nombres" required placeholder="x">    
                    <label class="required">Nombres<span class="required">*</span></label>
                </div>

            </div>


            <div class="form-row gap-3">
                <div class="form-floating">
                    <input type="date" class="form-control" id="Efechanacimiento" name="fechanacimiento" placeholder="x">
                    <label>Fecha de Nacimiento</label>
                </div>
                <div class="form-floating" >
                    <input type="text" class="form-control" id="Eocupacion" name="ocupacion" placeholder="x">
                    <label>Ocupacion</label>
                </div>  
  
            </div>

            <div class="form-row gap-3">
                <div class="form-floating">
                    <select id="Evive" name="vive" class="form-select">
                        <option value=""></option>
                        <option value="SI" >SI</option>
                        <option value="NO" >NO</option>
                    </select>                      
                    <label>Vive</label>
                </div>

                <div class="form-floating estadocv">
                    <select id="Eestadocivil" name="estadocivil" class="form-select" >
                        <option value=""></option>
                        <option value="SOLTERO">SOLTERO(A)
                        </option>
                        <option value="CASADO" >CASADO(A)
                        </option>
                        <option value="VIUDO" >VIUDO(A)</option>
                        <option value="DIVORCIADO">DIVORCIADO(A)</option>
                        <option value="CONVIVIENTE">CONVIVIENTE</option>
                    </select>
                    <label>Estado Civil</label>
                </div>
                <x-select-tipo-doc id="Eidtd" name="idtd" :tdoc="$tdoc" categoria="DAF" label="Tipo Documento"/>
                <x-file-upload id="Earchivo" name="archivo" label="Copia DNI"/>

            </div>
        </div>



        <div class="col box2">
            
            <div class="form-row flex-1 gap-3">
                <div class="form-floating parent-hab-s-style">
                    <select id="Eparentesco" name="parentesco" class="form-select" required>
                        <option value=""></option>
                        <option value="CONYUGUE">CONYUGUE</option>
                        <option value="PADRE">PADRE</option>
                        <option value="MADRE">MADRE</option>
                        <option value="HIJO">HIJO</option>
                    </select>     
                    <label class="required">Parentesco<span class="required">*</span></label>                   
                </div> 
                
                <div class="form-floating  parent-hab-style" >
                    <input type="text" class="form-control" id="Elugarlaboral" name="lugarlaboral" placeholder="x">
                    <label>Dirección Trabajo</label>
                </div>  
            </div>

            <div class="form-row flex-1 gap-3">
                <div class="form-floating derecho-hab-s-style">
                    <select id="Ederecho_habiente" name="derecho_habiente" class="form-select">
                        <option value="NO">NO</option>
                        <option value="SI">SI</option>
                    </select>    
                    <label>Derecho Hab.</label>                    
                </div> 
                <x-select-tipo-doc id="Eid_tipodocvin" name="id_tipodocvin" :tdoc="$tdoc" categoria="DER" label="Tipo Documento" divClass="derecho-hab-style"/>
                <div class="form-group derecho-hab-style">
                    <x-file-upload id="Earchivo_vinculo" name="archivo_vinculo" label="Vinculo"/>
                    <input type="hidden" class="form-control nro_folios" name="nro_folios2">
                </div>
                
            </div>
            <div class="form-row flex-1 gap-3">
                <div class="form-floating emergencia-s-style">
                    <select id="Eemergencia" name="emergencia" class="form-select">
                        <option value="NO" >NO</option>
                        <option value="SI" >SI</option>
                    </select>                      
                    <label>Emergencia</label>
                </div>

                <div class="form-floating emergencia-style">
                    <input type="text" class="form-control" id="Edireccion" name="direccion" placeholder="x">
                    <label>Dirección</label>
                </div>

                <div class="form-floating emergencia-style">
                    <input type="text" class="form-control" id="Etelefono" name="telefono" placeholder="x">
                    <label>Teléfono</label>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-success ml-auto mr-0"  style="margin-right: 10px;">Guardar</button>
