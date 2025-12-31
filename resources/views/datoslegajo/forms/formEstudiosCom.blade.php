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

<div class="form-row">
    <input type="hidden" id="estudios-com" name="estudios-com" value="">
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
                                <label for="centro-estudios-com">Centro de Estudios</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="fecha-ini-com" name="fecha-ini-com">
                                <label for="fecha-ini-com">Fecha inicio</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="fecha-fin-com" name="fecha-fin-com">
                                <label for="fecha-fin-com">Fecha fin</label>
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
                <a class="btn btn-success ml-auto mr-0" onclick="guardarEstudiosCom()" style="margin-right: 10px;">Grabar Estudio</a>
            </div>
            <div class="col-12 col-sm-6">
                <div id="tabla-estudios-com">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Denominación</th>
                                <th scope="col">Centro de estudios</th>
                                <th scope="col">Hora</th>
                                <th scope="col">Acción</th>
                            </tr>
                        </thead>
                        <tbody>

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
                document.getElementById("mensaje-com").innerHTML = data.mensaje + ' <a href="../repositories/' + data.name + '" target="_blank" class="text-success"><i class="fa fa-eye" ></i>Ver</a>';
                document.getElementById("doc-est-com").value = data.name;
            })
            .catch(error => console.error("Error al subir el archivo:", error));

    });
</script>