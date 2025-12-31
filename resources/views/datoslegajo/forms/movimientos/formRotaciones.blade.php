@php
$cont=1;
$arrayRotaciones=[];
if (!empty($rotaciones)){
foreach($rotaciones as $rotacion){
$nomarea="";
foreach($areas as $area){
if($rotacion->unidad_organica_destino == $area->id)
{
$nomarea=$area->nombre;
}
}
$nomcargo="";
foreach($cargos as $cargo){
if($rotacion->cargo == $cargo->id)
{
$nomcargo=$cargo->nombre;
}
}
array_push($arrayRotaciones, [
"cont"=> $cont,
"id"=> $rotacion->id,
"cambio" => 1,
"destino" => $rotacion->unidad_organica_destino,
"nombredes" => $nomarea,
"cargo" => $rotacion->cargo,
"nombrecar" => $nomcargo,
"descripcion"=> $rotacion->descripcion,
"inicio" => $rotacion->fecha_ini,
"docini" => $rotacion->nombredoc,
"nroini" => $rotacion->nrodoc,
"archivoini" => $rotacion->archivo,
"fin" => $rotacion->fecha_fin,
"docfin" => $rotacion->nombredocfin,
"nrofin" => $rotacion->nrodocfin,
"archivofin" => $rotacion->archivofin
]
);
$cont++;
}
}

@endphp
<input type="hidden" name="id-rotacion" id="id-rotacion">
<input type="hidden" name="rotaciones" id="rotaciones" value="{{ json_encode($arrayRotaciones) }}">
<div class="row">
    <div class="col-sm-6 col-12">
        <div class="d-flex flex-column gap-4">
            <div class="form-row gap-3">
                <input type="hidden" class="form-control" id="tipo_movimiento" name="tipo_movimiento" value="1" />
                <div class="col-12">
                    <label for="unidad-origen">Un. de Origen según Vínculo Lab.<span class="required">*</span></label>
                    <select name="unidad-origen" id="unidad-origen" class="form-select">
                        <option value="{{$ult_vin->id_unidad_organica ?? ''}}">{{$ult_vin->area ?? ''}}</option>
                    </select>
                </div>
                <div class="col-12">
                    <label for="unidad5-destino">Unidad de Destino<span class="required">*</span></label>
                    <select name="unidad-destino" id="unidad-destino" style="width: 100%;">
                        <option value="" selected disabled></option>
                        @foreach($areas as $area)
                        <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label for="cargo-destino">Cargo<span class="required">*</span></label>
                    <select name="cargo-destino" id="cargo-destino" class="form-control">
                        <option selected disabled></option>
                        @foreach($cargos as $cargo)
                        <option value="{{ $cargo->id }}">{{ $cargo->nombre }}</option>
                        @endforeach
                        <option value="0">Agregar nuevo... </option>
                    </select>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                        <input type="text" name="descripcion-cargo" id="descripcion-cargo" class="form-control">
                        <label for="descripcion-cargo">Actividades</label>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-floating">
                    <input type="date" class="form-control" id="fecha-ini-rot" name="fecha-ini-rot">
                    <label for="fecha-ini-rot">Fecha Inicio</label>
                </div>
                <div class="form-floating">
                    <input type="text" name="idtd-ini-rot" id="idtd-ini-rot" class="form-control" list="datalistOptions">
                    <label for="idtd-ini-rot">Tipo Doc. Inicio</label>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control" id="nrodoc-ini-rot" name="nrodoc-ini-rot">
                    <label for="nrodoc-ini-rot">Nro. Documento</label>
                </div>
                <div class="form-floating">
                    <input type="file" id="subidor-rot" name="subidor-rot" accept=".pdf">
                    <input type="hidden" name="doc-ini-rot" id="doc-ini-rot">
                    <div id="mensaje-rot"></div>
                </div>
            </div>

            <div class="form-row ">
                <div class="form-floating">
                    <input type="date" class="form-control" id="fecha-fin-rot" name="fecha-fin-rot">
                    <label for="fecha-fin-rot">Fecha Fin</label>
                </div>
                <div class="form-floating">
                    <input type="text" name="idtd-fin-rot" id="idtd-fin-rot" list="datalistOptions" class="form-control">
                    <label for="idtd-fin-rot" class="form-label">Tipo Documento Fin</label>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control" id="nrodoc-fin-rot" name="nrodoc-fin-rot">
                    <label for="nrodoc-rot">Nro. Documento</label>
                </div>
                <div class="form-floating">
                    <input type="file" id="subidor-fin-rot" name="subidor-fin-rot" accept=".pdf">
                    <input type="hidden" name="doc-fin-rot" id="doc-fin-rot">
                    <div id="mensaje-finrot"> </div>
                </div>
            </div>
            <div class="text center">
                <a class="btn btn-outline-info ml-auto mr-0 " onclick="guardarRotacion()" style="margin-right: 10px;">Guardar Cambios</a>
            </div>

        </div>
    </div>
    <div class="col-12 col-sm-6">
        <div id="tabla-rotaciones">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Un. Destino</th>
                        <th scope="col">Cargo</th>
                        <th scope="col">Inicio</th>
                        <th scope="col">Fin</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $cont = 1;
                    @endphp
                    @if(!empty($rotaciones))
                    @foreach($rotaciones as $rotacion)
                    <tr>
                        <th>
                            @foreach( $areas as $area )
                            {{$area->id == $rotacion->unidad_organica_destino ? $area->nombre : ''}}
                            @endforeach
                        </th>
                        <th>
                            @foreach($cargos as $cargo)
                            {{ $cargo->id == $rotacion->cargo ? $cargo->nombre : ''}}
                            @endforeach
                        </th>
                        <th>
                            {{$rotacion->fecha_ini}}
                        </th>
                        <th>
                            {{$rotacion->fecha_fin}}
                        </th>
                        <th>
                            <a class="btn btn-outline-info btn-sm" onClick="editarRotacion({{ $cont }})">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a class="btn btn-outline-danger btn-sm" onClick="anularRotacion({{ $cont}})">
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


<script>
    $(document).ready(function() {
        $('#unidad-destino').select2({
            placeholder: "Buscar unidad...",
            allowClear: true
        });
    });
    document.getElementById("subidor-rot").addEventListener("change", function() {
        let archivo = this.files[0]; // Capturar el archivo seleccionado

        if (!archivo) {
            document.getElementById("mensaje-rot").innerHTML = "⚠ No se ha seleccionado ningún archivo.";
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
                document.getElementById("mensaje-rot").innerHTML = data.mensaje + ' <a href="../repositories/'+ data.name +'" target="_blank" class="text-success"><i class="fa fa-eye" ></i>Ver</a>';
                document.getElementById("doc-ini-rot").value = data.name;
            })
            .catch(error => console.error("Error al subir el archivo:", error));

    });
    document.getElementById("subidor-fin-rot").addEventListener("change", function() {
        let archivo = this.files[0]; // Capturar el archivo seleccionado

        if (!archivo) {
            document.getElementById("mensaje-finrot").innerHTML = "⚠ No se ha seleccionado ningún archivo.";
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
                document.getElementById("mensaje-finrot").innerHTML = data.mensaje + ' <a href="../repositories/'+ data.name +'" target="_blank" class="text-success"><i class="fa fa-eye" ></i>Ver</a>';
                document.getElementById("doc-fin-rot").value = data.name;
            })
            .catch(error => console.error("Error al subir el archivo:", error));

    });
    
</script>