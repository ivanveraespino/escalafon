<!-- resources/views/datosficha/condicionlab.blade.php -->


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
        flex: 1;
        min-width: 200px;
        margin: 0;
    }

    .form-floating {
        margin-bottom: 24px;
    }
</style>
<input type="hidden" id="experiencia" name="experiencia" value="">
<input type="hidden" name="id-experiencia" id="id-experiencia">
<div class="container p-0">
    <div class="row">
        <div class="col-12 col-sm-6">
            <div class="form-row gap-3">
                <div class="container p-0">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="form-floating">
                                <select id="tipo-entidad" name="tipo-entidad" class="form-select">
                                    <option value="1">PUBLICA</option>
                                    <option value="2">PRIVADA</option>
                                </select>
                                <label for="entidad" class="required">Tipo de Entidad<span class="required">*</span></label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="entidad" name="entidad">
                                <label for="entidad">Entidad<span class="required">*</span></label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-floating ">
                                <input type="text" class="form-control" id="cargo" name="cargo">
                                <label for="cargo">Cargo<span class="required">*</span></label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-floating ">
                                <input type="date" class="form-control" id="fecha-ini-exp" name="fecha-ini">
                                <label for="periodo">Desde</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-floating ">
                                <input type="date" class="form-control" id="fecha-fin-exp" name="fecha-fin">
                                <label for="fecha-fin-exp">Hasta</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-floating">
                                <input type="text" name="tipo-cert-exp" id="tipo-cert-exp" class="form-control" list="datalistOptions">
                                <label for="tipo-cert-exp">Tipo Documento</label>
                                <datalist id="datalistOptions">
                                    @foreach($tiposdoc as $docs)
                                    <option value="{{$docs->nombre}}">
                                        @endforeach
                                </datalist>
                            </div>

                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-floating ">
                                <input type="text" class="form-control" id="nrodoc" name="nrodoc">
                                <label for="nrodoc">Nro. Documento<span class="required">*</span></label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <input type="file" id="subidor-exp" accept=".pdf" >
                            <input type="hidden" name="doc-exp" id="doc-exp">
                            <div id="mensaje-exp"></div>
                        </div>
                    </div>
                </div>
            </div>
            <a onclick="guardarExperiencia()" class="btn btn-success ml-auto mr-0" style="margin-right: 10px;">Guardar experiencia</a>
        </div>
        <div class="col-12 col-sm-6">
            <div id="tabla-experiencia">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Cargo</th>
                            <th scope="col">Entidad</th>
                            <th scope="col">Tiempo</th>
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
    document.getElementById("subidor-exp").addEventListener("change", function() {
        let archivo = this.files[0]; // Capturar el archivo seleccionado

        if (!archivo) {
            document.getElementById("mensaje-exp").innerHTML = "⚠ No se ha seleccionado ningún archivo.";
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
                //document.getElementById("mensaje").innerHTML = data.mensaje;
                document.getElementById("mensaje-exp").innerHTML = data.mensaje + ' <a href="../repositories/'+ data.name +'" target="_blank" class="text-success"><i class="fa fa-eye" ></i>Ver</a>';
                document.getElementById("doc-exp").value = data.name;
            })
            .catch(error => console.error("Error al subir el archivo:", error));

    });
</script>

@push('scripts')
<script>
    ////////////////////////////////
    //funciones de estudios

    function diferenciaFechas(fechaInicio, fechaFin) {
        const inicio = new Date(fechaInicio);
        const fin = new Date(fechaFin);

        let años = fin.getFullYear() - inicio.getFullYear();
        let meses = fin.getMonth() - inicio.getMonth();
        let días = fin.getDate() - inicio.getDate();

        if (días < 0) {
            meses--;
            días += new Date(fin.getFullYear(), fin.getMonth(), 0).getDate(); // Días del mes anterior
        }

        if (meses < 0) {
            años--;
            meses += 12;
        }

        return {
            años,
            meses,
            días
        };
    }
    ////////////////////////////////
</script>

@endpush