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
$cont=1;
$arrayReconocimientos=[];
if (!empty($reconocimientos)){
foreach($reconocimientos as $recon){
array_push($arrayReconocimientos, [
"cont"=> $cont,
"id"=> $recon->id,
"cambio" => 0,
"forma"=>$recon->forma,
"tipodoc" => $recon->nombredoc,
"nrodoc" => $recon->nrodoc,
"descripcion" => $recon->descripcion,
"fecharecon" => $recon->fecharecon,
"inicio" => $recon->fecha_ini,
"fin" => $recon->fecha_fin,
"archivo"=>$recon->archivo
]);
$cont++;
}
}
@endphp

<input type="hidden" name="id-reconocimiento" id="id-reconocimiento">
<input type="hidden" name="reconocimientos" id="reconocimientos" value="{{json_encode($arrayReconocimientos)}}">
<div class="row">
    <div class="col-sm-6 col-12">
        <div class="form-row gap-3 ">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="tipo-recon" id="radioDefault1" value="0" checked>
                <label class="form-check-label" for="radioDefault1">
                    Por labores realizadas
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="tipo-recon" id="radioDefault2" value="1">
                <label class="form-check-label" for="radioDefault2">
                    Por tiempo de servicio
                </label>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control" id="descripcion-recon" name="descripcion-recon">
                <label for="entidad">Descripción<span class="required">*</span></label>
            </div>
            <div class="form-floating">
                <input type="text" name="tipodoc-recon" id="tipodoc-recon" class="form-control" list="datalistOptions">
                <label for="tipodoc-vac">Tipo Documento</label>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control" id="nrodoc-recon" name="nrodoc-recon">
                <label for="entidad">Nro. Documento</label>
            </div>
            <div class="form-floating" id="desde_vlp">
                <input type="date" class="form-control" id="fecha-recon" name="fecha-recon">
                <label for="entidad">Fecha de reconocimiento<span class="required">*</span></label>
            </div>
            <div class="form-floating" >
                <input type="date" class="form-control" id="fecha-ini-recon" name="fecha-ini-recon" disabled>
                <label for="entidad" class="required"><span class="required">*</span>Desde</label>
            </div>
            <div class="form-floating" >
                <input type="date" class="form-control" id="fecha-fin-recon" name="fecha-fin-recon" disabled>
                <label for="entidad" class="required"><span class="required">*</span>Hasta</label>
            </div>
            <div class="col">
                <input type="file" id="subidor-rec" accept=".pdf">
                <input type="hidden" name="doc-rec" id="doc-rec">
                <div id="mensaje-rec"></div>
            </div>
        </div>
        <div class="col-12 text-center">
            <a class="btn btn-outline-info ml-auto mr-0 " onclick="guardarReconocimiento()" style="margin-right: 10px;">Guardar Cambios</a>
        </div>
    </div>
    <div class="col-sm-6 col-12">
        <div id="tabla-reconocimientos">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Descripción</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $cont = 1;
                    @endphp
                    @if(!empty($reconocimientos))
                    @foreach($reconocimientos as $recon)
                    <tr>
                        <th>
                            {{$recon->descripcion}}
                        </th>
                        <th>
                            {{$recon->fecharecon}}
                        </th>

                        <th>
                            <a class="btn btn-outline-info btn-sm" onClick="editarReconocimiento({{ $cont }})">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a class="btn btn-outline-danger btn-sm" onClick="anularReconocimiento({{ $cont}})">
                                <i class="fa fa-trash"></i>
                            </a>
                        </th>
                    </tr>

                    @php
                    $cont = $cont + 1;
                    @endphp
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>



<!-- <a  class="btn btn-success ml-auto mr-0" style="margin-right: 10px;">Guardar</a> -->
@push('scripts')
<script src="{{asset('js/reconocimientos.js')}}"></script>
@endpush
<script>
    document.getElementById("subidor-rec").addEventListener("change", function() {
        let archivo = this.files[0]; // Capturar el archivo seleccionado

        if (!archivo) {
            document.getElementById("mensaje-rec").innerHTML = "⚠ No se ha seleccionado ningún archivo.";
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
                document.getElementById("mensaje-rec").innerHTML = data.mensaje + ' <a href="../repositories/'+ data.name +'" target="_blank" class="text-success"><i class="fa fa-eye" ></i>Ver</a>';
                document.getElementById("doc-rec").value = data.name;
            })
            .catch(error => console.error("Error al subir el archivo:", error));
    });
</script>