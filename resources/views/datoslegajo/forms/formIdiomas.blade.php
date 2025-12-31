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
<input type="hidden" id="idiomas" name="idiomas" value="">
<input type="hidden" id="id-idioma" name="id-idioma">
<div class="container">
    <div class="row">
        <div class="col-12 col-sm-6">
            <div class="container p-0">
                <div class="row ">
                    <div class="col-12 col-sm-5">
                        <div class="form-floating ">
                            <input type="text" class="form-control" id="idioma" name="idioma">
                            <label>Idiomas<span class="required">*</span></label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-floating">
                            <select id="lectura" name="lectura" class="form-select">
                                <option value="" selected disabled></option>
                                <option value="Con facilidad">Con facilidad</option>
                                <option value="Intermedio">Intermedio</option>
                                <option value="Sin facilidad">Sin facilidad</option>
                            </select>
                            <label>Lectura</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-floating">
                            <select id="habla" name="habla" class="form-select">
                                <option value="" selected disabled></option>
                                <option value="Con facilidad">Con facilidad</option>
                                <option value="Intermedio">Intermedio</option>
                                <option value="Sin facilidad">Sin facilidad</option>
                            </select>
                            <label>Habla</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-floating">
                            <select id="escritura" name="escritura" class="form-select">
                                <option value="" selected disabled></option>
                                <option value="Con facilidad">Con facilidad</option>
                                <option value="Intermedio">Intermedio</option>
                                <option value="Sin facilidad">Sin facilidad</option>
                            </select>
                            <label>Escritura</label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-floating ">
                            <input type="text" name="tipo-doc-idioma" id="tipo-doc-idioma" list="datalistOptions" class="form-control">
                            <label for="tipo-doc-idioma">Tipo Documento</label>
                            <datalist id="datalistOptions">
                                @foreach($tiposdoc as $docs)
                                <option value="{{$docs->nombre}}">
                                    @endforeach
                            </datalist>
                        </div>
                    </div>
                    <div class="col">
                        <input type="file" id="subidor-idioma" accept=".pdf">
                        <input type="hidden" name="doc-idioma" id="doc-idioma">
                        <div id="mensaje-idioma"></div>
                    </div>

                </div>
            </div>
            <a class="btn btn-success ml-auto mr-0 mt-2" onclick="guardarIdioma()" style="margin-right: 10px;">Agregar Idioma</a>
        </div>
        <div class="col-12 col-sm-6">
            <div id="tabla-idiomas">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Idioma</th>
                            <th scope="col">Nivel</th>
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
<script>
    document.getElementById("subidor-idioma").addEventListener("change", function() {
        let archivo = this.files[0]; // Capturar el archivo seleccionado

        if (!archivo) {
            document.getElementById("mensaje-idioma").innerHTML = "⚠ No se ha seleccionado ningún archivo.";
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
                //document.getElementById("mensaje-col").innerHTML = data.mensaje;
                document.getElementById("mensaje-idioma").innerHTML = data.mensaje + ' <a href="../repositories/'+ data.name +'" target="_blank" class="text-success"><i class="fa fa-eye" ></i>Ver</a>';
                document.getElementById("doc-idioma").value = data.name;
            })
            .catch(error => console.error("Error al subir el archivo:", error));

    });
</script>