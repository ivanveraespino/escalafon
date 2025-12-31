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

<div class="container p-0">
    @php
    $estudiosArray = [];
    $cont=0;
    foreach ($estudios as $carrera) {
    $cont++;
    $estudiosArray[] = [
    'cont'=>$cont,
    'nivel'=>$carrera->nivel_educacion,
    'doc' => $carrera->nombredoc,
    'institucion' => $carrera->centroestudios,
    'especialidad' => $carrera->especialidad,
    'inicio' => date("Y-m-d", strtotime($carrera->fecha_ini)),
    'fin' => date("Y-m-d", strtotime($carrera->fecha_fin)),
    'archivo'=>$carrera->archivo
    ];
    }

    $estudiosJson = json_encode($estudiosArray);
    @endphp
    <input type="hidden" id="estudios-rea" name="estudios-rea" value='{{$estudiosJson}}'>

    <input type="hidden" id="id-estudio" name="id-estudio" value="">
    <div class="row">
        <div class="col col-sm-6">
            <div class="container p-0 border border-1">
                <div class="row m-1">
                    <h6 class="text-mplc">Datos de estudios</h6>
                    <div class="col col-sm-6">
                        <div class="form-floating">
                            <select id="nivel-educacion" name="nivel-educacion" class="form-control">
                                <option selected disabled></option>
                                <option value="BACHILLER">BACHILLER</option>
                                <option value="DOCTORADO COMPLETA">DOCTORADO COMPLETO</option>
                                <option value="DOCTORADO INCOMPLETA">DOCTORADO INCOMPLETO</option>
                                <option value="EGRESADO">EGRESADO</option>
                                <option value="ESTUDIANTE">ESTUDIANTE</option>
                                <option value="MAESTRIA COMPLETA">MAESTRIA COMPLETA</option>
                                <option value="MAESTRIA INCOMPLETA">MAESTRIA INCOMPLETA</option>
                                <option value="PRIMARIA COMPLETA">PRIMARIA COMPLETA</option>
                                <option value="PRIMARIA INCOMPLETA">PRIMARIA INCOMPLETA</option>
                                <option value="SECUNDARIA COMPLETA">SECUNDARIA COMPLETA</option>
                                <option value="SECUNDARIA INCOMPLETA">SECUNDARIA INCOMPLETA</option>
                                <option value="TECNICO COMPLETA">TECNICO COMPLETA</option>
                                <option value="TECNICO INCOMPLETA">TECNICO INCOMPLETA</option>
                                <option value="TITULADO">TITULADO</option>
                                <option value="UNIVERSITARIO COMPLETA">UNIVERSITARIA COMPLETA</option>
                                <option value="UNIVERSITARIO INCOMPLETA">UNIVERSITARIA INCOMPLETA</option>
                            </select>
                            <label for="nivel-educacion">Nivel de Educación<span class="required">*</span></label>
                        </div>
                    </div> 
                    <div class="col col-sm-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="centroestudios" name="centroestudios">
                            <label for="centroestudios">Centro de Estudios<span class="required">*</span></label>
                        </div>
                    </div>
                    <div class="col col-sm-6">
                        <div class="form-floating">
                        <input type="text" name="idtd-est" id="idtd-est" list="datalistOptions" class="form-control">
                            
                            <label for="idtd-est">Tipo documento</label>
                            <datalist id="datalistOptions">
                                @foreach($tiposdoc as $docs)
                                <option value="{{$docs->nombre}}">
                                    @endforeach
                            </datalist>
                        </div>
                    </div>
                    <div class="col col-sm-6">
                        <div class="form-floating especialidad-style">
                            <input type="text" class="form-control" id="especialidad" name="especialidad">
                            <label for="periodo">Profesión<span class="required">*</span></label>
                        </div>
                    </div>
                    <div class="col col-sm-6">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="fecha-ini-estudio" name="fecha-ini-estudio">
                            <label for="periodo">Fecha inicio<span class="required">*</span></label>
                        </div>
                    </div>
                    <div class="col col-sm-6">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="fecha-fin-estudio" name="fecha-fin-estudio">
                            <label for="periodo">Fecha fin<span class="required">*</span></label>
                        </div>
                    </div>
                    <div class="col">
                        <input type="file" id="subidor" accept=".pdf">
                        <input type="hidden" name="doc-est" id="doc-est">
                        <div id="mensaje"></div>
                    </div>
                </div>
                
            </div>
            
            <a onclick="guardarEstudios()" class="btn btn-success ml-auto mr-0" style="margin-right: 10px;">Agregar Estudios</a>

        </div>
        <div class="col col-sm-6">
            <div id="tabla-estudios">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Nivel</th>
                            <th scope="col">Centro de estudios</th>
                            <th scope="col">Especialidad</th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($estudiosArray as $estudio)
                        <tr>
                            <td>{{ $estudio['nivel'] }}</td>
                            <td>{{ $estudio['institucion'] }}</td>
                            <td>{{ $estudio['especialidad'] }}</td>
                            <td>
                            <a class="btn btn-outline-info btn-sm" onClick="editarEstudios({{$estudio['cont']}})">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a class="btn btn-outline-danger btn-sm" onClick="anularEstudios({{$estudio['cont']}})">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById("subidor").addEventListener("change", function() {
        let archivo = this.files[0]; // Capturar el archivo seleccionado

        if (!archivo) {
            document.getElementById("mensaje").innerHTML = "⚠ No se ha seleccionado ningún archivo.";
            return;
        }

        let formData = new FormData();
        formData.append("archivo", archivo);

        fetch("/subir-archivo", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById("mensaje").innerHTML = data.mensaje + ' <a href="../repositories/'+ data.name +'" target="_blank" class="text-success"><i class="fa fa-eye" ></i>Ver</a>';
                document.getElementById("doc-est").value = data.name;
            })
            .catch(error => console.error("Error al subir el archivo:", error));

    });
</script>