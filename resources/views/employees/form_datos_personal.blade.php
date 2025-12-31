
<style>
    .form-floating{
        flex:1;
    } 
    .drag-drop-area {
    border: 2px dashed #ccc;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.2s ease-in-out;
    }

    .drag-drop-area.dragover {
        background-color: #e0e0e0;
    }

    #cropperContainer {
        margin-top: 20px;
        text-align: center;
    }
</style>

<form id="Personal" action="{{ route('editarPersonal', $dp->id_personal ?? '') }}" method="POST" class="flex-1 d-flex flex-column gap-4" enctype="multipart/form-data">
    @csrf
    <input type="hidden" class="form-control" id="Pid_personal" name="" >

    <input type="hidden" class="form-control" value="FOTO PERFIL" name="nombrea">

    <div class="flex-1 d-row gap-3 w-100 ">
        <div class="flex-1 d-flex flex-column">
            <img id="profileImage_prev" class="card-img-top profile-image" style="height: 150px;" alt="La imagen no esta disponible" src="{{ $base64Image ?? asset('img/perfil.png') }}">   
        
            <button type="button" class="btn btn-primary btn-sm"
                onclick="document.getElementById('archivo_prev').click()">
                <i class="fas fa-upload"></i> Actualizar foto
                    <input type="file" accept="image/*" id="archivo_prev" class="form-control " name="archivo" style="display: none;" onchange="previewImage()">
            </button>
        </div>
        

            <div class="flex-1 d-col gap-3">
                <div class="form-floating">
                    <select class="form-select required-field" id="Pid_identificacion" name="id_identificacion" aria-label="Floating label select example">
                        <option hidden selected></option>
                        <option value="DNI">DNI</option>
                        <option value="CARNET DE EXTRANJERIA">CARNET DE EXTRANJERIA</option>
                        <option value="PASAPORTE">PASAPORTE</option>
                        <option value="PARTIDA DE NACIMIENTO">PARTIDA DE NACIMIENTO</option>
                        <option value="OTROS">OTROS</option>
                    </select>
                    <label for="Pid_identificacion" class="required-field-l">Tipo doc. ident.</label>
                </div>
            
                <div class="form-floating">
                    <input type="text" class="form-control required-field" id="Pnro_documento_id"  name="nro_documento_id" placeholder="Número de documento">
                    <label for="Pnro_documento_id" class="dynamic-label required-field-l">Nº documento. Identidad</label>
                </div>
            
                <div class="form-floating">
                    <select id="Pid_tipo_personal" name="id_tipo_personal" class="form-select required-field">
                        <option hidden selected></option>
                        @foreach($tpersonal as $tp)
                            <option value="{{ $tp->id ?? '' }}">{{ $tp->nombre }}</option>
                        @endforeach
                    </select>
                    <label for="Pid_tipo_personal" class="required-field-l">Tipo de Personal</label>
                </div>
            </div>

            <div class="flex-1 d-col gap-3">
                <div class="form-floating ">
                    <input type="text" class="form-control required-field" id="PApaterno" name="Apaterno" placeholder="Apellido Paterno">
                    <label for="PApaterno" class="required-field-l">Apellido Paterno</label>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control required-field" id="PAmaterno" name="Amaterno" placeholder="Apellido Materno">
                    <label for="PAmaterno" class="required-field-l">Apellido Materno</label>
                </div>
                
                <div class="form-floating">
                    <input type="text" class="form-control required-field" id="PNombres" name="Nombres" placeholder="Nombres">
                    <label for="PNombres" class="required-field-l">Nombres</label>
                </div>
            </div>

            <div class="flex-1 d-col gap-3">
                <div class="form-floating" >
                    <select id="Psexo" name="sexo" class="form-select">
                        <option hidden selected></option>
                        <option value="MASCULINO">MASCULINO</option>
                        <option value="FEMENINO">FEMENINO</option>
                    </select>
                    <label for="Psexo">Sexo</label>
                </div>
                
                <div class="form-floating" >
                    <input type="date" class="form-control" id="PFechaNacimiento" name="FechaNacimiento">
                    <label for="PFechaNacimiento">Fecha de nacimiento</label>
                </div>
                
                <div class="form-floating" >
                    <select id="PEstadoCivil" name="EstadoCivil" class="form-select">
                        <option hidden selected></option>
                        <option value="SOLTERO">SOLTERO(A)</option>
                        <option value="CASADO">CASADO(A)</option>
                        <option value="VIUDO">VIUDO(A)</option>
                        <option value="DIVORCIADO">DIVORCIADO(A)</option>
                        <option value="CONVIVIENTE">CONVIVIENTE</option>
                    </select>
                    <label for="PEstadoCivil">Estado Civil</label>
                </div>
            </div>
        
    </div>
    

    <div class="flex-1 d-flex d-row gap-3">
        
        <div class="form-floating" >
            <input type="text" class="form-control" id="Plprocedencia" name="lprocedencia" placeholder="Lugar de Procedencia">
            <label for="Plprocedencia">Lugar de Procedencia</label>
        </div>
        <div class="form-floating" >
            <input type="text" class="form-control" id="PNroTelefono" name="NroTelefono" placeholder="Nro Telefono">
            <label for="PNroTelefono">Nro Telefono</label>
        </div>
        
        <div class="form-floating" >
            <input type="text" class="form-control" id="PNroCelular" name="NroCelular" placeholder="Nro de Celular">
            <label for="PNroCelular">Nro de Celular</label>
        </div>
        
        <div class="form-floating" >
            <input type="email" class="form-control" id="PCorreo" name="Correo" placeholder="Correo Electronico">
            <label for="PCorreo">Correo Electronico</label>
        </div>
    
        <div class="form-floating">
            <input type="text" class="form-control" id="PNroRuc" name="NroRuc" placeholder="Nro de Ruc">
            <label for="PNroRuc">Nro de Ruc</label>
        </div>
    
        <div class="form-floating">
            <input type="text" class="form-control" id="PNroEssalud" name="NroEssalud" placeholder="Nro de Carne Essalud">
            <label for="PNroEssalud">Nro de Carne Essalud</label>
        </div>
    
        <div class="form-floating">
            <input type="text" class="form-control" id="PCentroEssalud" name="CentroEssalud" placeholder="Centro de Atencion Essalud">
            <label for="PCentroEssalud" class="dynamic-label" >
                Centro de Atencion Essalud
            </label>
        </div>
        <div class="col-md">
            <label for="afiliacion">Afiliación</label>
            <div class="dropdown w-100" id="afiliacion">
                <button class="btn btn-light dropdown-toggle w-100" type="button" id="PsampleDropdownMenu" data-bs-toggle="dropdown" aria-expanded="false">
                    Seleccionar afiliación
                </button>
                <div class="dropdown-menu">
                    <div class="dropdown-item">
                        <input type="checkbox" name="afiliacion_salud[]" value="Essalud" onchange="actualizarChips()"> Essalud
                    </div>
                    <div class="dropdown-item">
                        <input type="checkbox" name="afiliacion_salud[]" value="Seguro SIS" onchange="actualizarChips()"> Seguro SIS
                    </div>
                    <div class="dropdown-item">
                        <input type="checkbox" name="afiliacion_salud[]" value="SCTR" onchange="actualizarChips()"> SCTR
                    </div>
                </div>
            </div>
            <div id="chips-container" class="col-md"></div>
        </div>
    
        <div class="form-floating">
            <input type="text" class="form-control" id="PGrupoSanguineo" name="GrupoSanguineo" placeholder="Grupo Sanguineo">
            <label for="PGrupoSanguineo">Grupo Sanguineo</label>
    
        </div>
        
        <div class="form-floating">
            <select id="Pafp" name="afp" class="form-select">
                <option hidden selected></option>
                <option value="PROFUTURO">PROFUTURO</option>
                <option value="HABITA">HABITA</option>
                <option value="HORIZONTE">HORIZONTE</option>
                <option value="PRIMA">PRIMA</option>
                <option value="INTEGRA">INTEGRA</option>
                <option value="ONP">ONP</option>
            </select>
            <label for="Pafp">AFP</label>
    
        </div>
        
        <div class="form-floating">
            <select id="Pregimenp" name="regimenp" class="form-select">
                <option hidden selected></option>
                @foreach($rp as $t)
                <option value="{{ $t->nombre }}">{{ $t->nombre }}</option>
                @endforeach
            </select>
            <label for="Pregimenp">Regimen Pensionario</label>
        </div>
        
        <div class="form-floating">
            <select id="Pdiscapacidad" name="discapacidad" class="form-select">
                <option hidden selected></option>
                <option value="SI">SI</option>
                <option value="NO">NO</option>
            </select>
            <label for="Pdiscapacidad">Discapacidad</label>
        </div>
        
        <div class="form-floating">
            <select id="Pffaa" name="ffaa" class="form-select">
                <option hidden selected></option>
                <option value="SI">SI</option>
                <option value="NO">NO</option>
            </select>
            <label for="Pffaa">Licenciado FF. AA.</label>
        </div>
    </div>



    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapseOne">
                    Archivos Adicionales
                </button>
            </h2>
            <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div id="dynamicFileInputs" class="accordion-body d-flex gap-3">
                    
                </div>
            </div>
        </div>
    </div>
    
</form>
