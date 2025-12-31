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
$arraySanciones=[];
if (!empty($sanciones)){
foreach($sanciones as $sanc){
array_push($arraySanciones, [
"cont"=> $cont,
"id"=> $sanc->id,
"cambio" => 0,
"tipodoc" => $sanc->nombredoc,
"nrodoc" => $sanc->nrodoc,
"descripcion" => $sanc->descripcion,
"dias" => $sanc->dias_san,
"fechadoc" => $sanc->fechadoc,
"inicio" => $sanc->fecha_ini,
"fin" => $sanc->fecha_fin,
"tiposan"=>$sanc->tiposancion,
"archivo"=>$sanc->archivo
]);
$cont++;
}
}
@endphp


<input type="hidden" name="sanciones" id="sanciones" value="{{json_encode($arraySanciones)}}">
<input type="hidden" name="id-sancion" id="id-sancion">
<div class="row">
    <div class="col-sm-6 col-12">
        <div class="form-row gap-3 ">
            <div class="form-floating">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo-sancion" id="tiempo-servicio" value="1">
                    <label class="form-check-label" for="tiempo-servicio">
                        Al tiempo de servicio
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tipo-sancion" id="solo-remuneracion" checked value="2">
                    <label class="form-check-label" for="solo-remuneracion">
                        Sólo a la remuneración.
                    </label>
                </div>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control" id="motivo-san" name="motivo-san">
                <label for="entidad">Motivo de la sanción<span class="required">*</span></label>
            </div>

            <div class="form-floating">
                <input type="text" name="tipodoc-san" id="tipodoc-san" class="form-control" list="datalistOptions">
                <label for="tipodoc-vac">Tipo Documento</label>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control" id="nrodoc-san" name="nrodoc-san">
                <label for="nrodoc-sancion">Nro Documento</label>
            </div>

            <div class="form-floating">
                <input type="date" class="form-control" id="fechadoc-san" name="fechadoc-san">
                <label for="entidad">Fecha documento</label>
            </div>

            <div class="form-floating">
                <input type="number" class="form-control" id="dias-san" name="dias-san" min="1" value="1">
                <label for="entidad">Dias Sancionados</label>
            </div>

            <div class="form-floating" id="desde_vlp">
                <input type="date" class="form-control" id="fecha-ini-san" name="fecha-ini-san">
                <label for="entidad">Desde<span class="required">*</span></label>
            </div>

            <div class="form-floating" id="hasta_vlp">
                <input type="date" class="form-control" id="fecha-fin-san" name="fecha-fin-san" readonly>
                <label for="entidad">Hasta</label>
            </div>
            <div class="col">
                <input type="file" id="subidor-san" accept=".pdf">
                <input type="hidden" name="doc-san" id="doc-san">
                <div id="mensaje-san"></div>
            </div>

        </div>
        <div class="col-12 text-center">
            <a class="btn btn-outline-info ml-auto mr-0 " onclick="guardarSanciones()" style="margin-right: 10px;">Guardar Cambios</a>
        </div>
    </div>
    <div class="col-sm-6 col-12">
        <div id="tabla-sanciones">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Motivo</th>
                        <th scope="col">Inicio</th>
                        <th scope="col">Fin</th>
                        <th scope="col">Días</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $cont = 1;
                    @endphp
                    @if(!empty($sanciones))
                    @foreach($sanciones as $san)
                    <tr>
                        <th>
                            {{$san->descripcion}}
                        </th>
                        <th>
                            {{ \Carbon\Carbon::parse($san->fecha_ini)->format('d-m-Y') }}
                        </th>
                        <th>
                            {{ \Carbon\Carbon::parse($san->fecha_fin)->format('d-m-Y') }}
                        </th>
                        <th>
                            <a class="btn btn-outline-info btn-sm" onClick="editarSancion({{ $cont }})">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a class="btn btn-outline-danger btn-sm" onClick="anularSanción({{ $cont}})">
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

@push('scripts')
<script src="{{asset('js/sanciones.js')}}"></script>
@endpush
<script>
    document.getElementById("subidor-san").addEventListener("change", function() {
        let archivo = this.files[0]; // Capturar el archivo seleccionado

        if (!archivo) {
            document.getElementById("mensaje-san").innerHTML = "⚠ No se ha seleccionado ningún archivo.";
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
                document.getElementById("mensaje-san").innerHTML = data.mensaje + ' <a href="../repositories/' + data.name + '" target="_blank" class="text-success"><i class="fa fa-eye" ></i>Ver</a>';
                document.getElementById("doc-san").value = data.name;
            })
            .catch(error => console.error("Error al subir el archivo:", error));
    });
</script>