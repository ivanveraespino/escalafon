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
<input type="hidden" id="colegiatura" name="colegiatura" value="">
<input type="hidden" id="id-colegio" name="id-colegio">
<div class="container">
    <div class="row">

        <div class="col-12 col-sm-6">
            <div class="container p-0">
                <div class="row ">
                    <div class="col-12 col-sm-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="nombre-colegio" name="nombre-colegio">
                            <label for="entidad" class="required">Nombre Colegio<span class="required">*</span></label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-floating">
                            <input type="text" name="tipo-doc-col" id="tipo-doc-col" class="form-control" list="datalistOptions">
                            <label for="tipo-doc-col" >Tipo Documento<span class="required">*</span></label>
                            <datalist id="datalistOptions">
                                @foreach($tiposdoc as $docs)
                                <option value="{{$docs->nombre}}">
                                @endforeach 
                            </datalist>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="nro-col" name="nro-col" placeholder="Ejm. 232-2023">
                            <label for="nro-col">N° Colegiatura<span class="required">*</span></label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-floating">
                            <select class="form-select" id="estado" name="estado">
                                <option value="" disabled selected></option>
                                <option value=1>Habilitado</option>
                                <option value=0>No Habilitado</option>
                                <option value="2">Suspendido</option>
                            </select>
                            <label for="estado" class="required">Estado<span class="required">*</span></label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="fecha-col" name="fecha-col">
                            <label for="fecha-col">Fecha documento</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <input type="file" id="subidor-col" accept=".pdf" >
                        <input type="hidden" name="doc-col" id="doc-col">
                        <div id="mensaje-col"></div>
                    </div>
                </div>
            </div>
            <a class="btn btn-success ml-auto mr-0 mt-2" onclick="guardarColegiatura()" style="margin-right: 10px;">Agregar</a>
        </div>
        <div class="col-12 col-sm-6">
            <div id="tabla-colegiatura">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Colegio</th>
                            <th scope="col">Nro. Colegiatura</th>
                            <th scope="col">Desde</th>
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
    document.getElementById("subidor-col").addEventListener("change", function() {
        let archivo = this.files[0]; // Capturar el archivo seleccionado

        if (!archivo) {
            document.getElementById("mensaje-col").innerHTML = "⚠ No se ha seleccionado ningún archivo.";
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
                document.getElementById("mensaje-col").innerHTML = data.mensaje + ' <a href="../repositories/'+ data.name +'" target="_blank" class="text-success"><i class="fa fa-eye" ></i>Ver</a>';
                document.getElementById("doc-col").value = data.name;
            })
            .catch(error => console.error("Error al subir el archivo:", error));

    });
</script>