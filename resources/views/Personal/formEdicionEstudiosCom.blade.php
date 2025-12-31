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
@php
$especialidadArray = [];
$cont=0;
foreach ($especialidad as $tema) {
$cont++;
$especialidadArray[] = [
'cont'=>$cont,
'denominacion'=>$tema->nombre,
'nivel'=>$tema->nivel_educacion,
'institucion' => $tema->centroestudios,
'inicio' => date("Y-m-d", strtotime($tema->fecha_ini)),
'fin' => date("Y-m-d", strtotime($tema->fecha_fin)),
'horas' => $tema->horas,
'tipodoc' => $tema->nombredoc,
'archivo'=>$tema->archivo
];
}

$estudiosJson = json_encode($especialidadArray);
@endphp
<div class="form-row">
    <input type="hidden" id="estudios-com" name="estudios-com" value='{{$estudiosJson}}'>
    <input type="hidden" id="id-est-com" name="id-est-com">
    <div class="container p-0">
        <div class="row">
            <div class="col-12 col-sm-6">
                <div class="container p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="denominacion" name="denominacion" placeholder="Ejm. CURSOS, DIPLOMADOS, OTROS">
                                <label for="denominacion">Denominación<span class="required">*</span></label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="centro-estudios-com" name="centro-estudios-com">
                                <label for="centro-estudios-com">Centro de Estudios <span class="required"></span></label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="fecha-ini-com" name="fecha-ini-com">
                                <label for="fecha-ini-com">Fecha inicio <span class="required">*</span> </label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="fecha-fin-com" name="fecha-fin-com">
                                <label for="fecha-fin-com">Fecha fin<span class="required">*</span></label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="horas-com" name="horas-com" min="1" value="1">
                                <label for="horas-com">Horas</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-floating">
                                <input type="text" name="tipo-doc-com" id="tipo-doc-com" list="datalistOptions" class="form-control">
                                <label for="tipo-doc-com" class="required">Tipo Documento<span class="required">*</span></label>
                                <datalist id="datalistOptions">
                                    @foreach($tiposdoc as $docs)
                                    <option value="{{$docs->nombre}}">
                                        @endforeach
                                </datalist>
                            </div>
                        </div>
                        <div class="col">
                            <input type="file" id="subidor-com" accept=".pdf">
                            <input type="hidden" name="doc-est-com" id="doc-est-com">
                            <div id="mensaje-com"></div>
                        </div>
                    </div>
                </div>
                <a class="btn btn-success ml-auto mr-0 mt-2" onclick="guardarEstudiosCom()" style="margin-right: 10px;">Agregar Estudio</a>
            </div>
            <div class="col-12 col-sm-6">
                <div id="tabla-estudios-com">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Denominación</th>
                                <th scope="col">Centro de estudios</th>
                                <th scope="col">Horas</th>
                                <th scope="col">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($especialidadArray as $estudio)
                            <tr>
                                <td>{{ $estudio['denominacion'] }}</td>
                                <td>{{ $estudio['institucion'] }}</td>
                                <td>{{ $estudio['horas'] }}</td>
                                <td>
                                    <a class="btn btn-outline-info btn-sm" onClick="editarEstudiosCom({{$estudio['cont']}})">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a class="btn btn-outline-danger btn-sm" onClick="anularEstudiosCom({{$estudio['cont']}})">
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

</div>

<script>
    document.getElementById("subidor-com").addEventListener("change", function() {
        let archivo = this.files[0]; // Capturar el archivo seleccionado

        if (!archivo) {
            document.getElementById("mensaje-com").innerHTML = "⚠ No se ha seleccionado ningún archivo.";
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
                //document.getElementById("mensaje-com").innerHTML = data.mensaje;
                document.getElementById("mensaje-com").innerHTML = data.mensaje + ' <a href="../repositories/'+ data.name +'" target="_blank" class="text-success"><i class="fa fa-eye" ></i>Ver</a>';
                document.getElementById("doc-est-com").value = data.name;
            })
            .catch(error => console.error("Error al subir el archivo:", error));

    });
</script>