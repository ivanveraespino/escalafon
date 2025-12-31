@php
$cont=1;
$arrayEncargaturas=[];
foreach($encargaturas as $encargatura){


$nomarea="";
foreach($areas as $area){
if($encargatura->unidad_organica_destino == $area->id)
{
$nomarea=$area->nombre;
}
}
$nomcargo="";
foreach($cargos as $cargo){
if($encargatura->cargo == $cargo->id)
{
$nomcargo=$cargo->nombre;
}
}
array_push($arrayEncargaturas, [
"cont"=> $cont, 
"id"=> $encargatura->id,
"cambio" => 0,
"destino" => $encargatura->unidad_organica_destino,
"nombredes" => $nomarea,
"cargo" => $encargatura->cargo,
"nombrecar" => $nomcargo,
"inicio" => $encargatura->fecha_ini,
"docini" => $encargatura->nombredoc,
"nroini" => $encargatura->nrodoc,
"archivoini" => $encargatura->archivo,
"fin" => $encargatura->fecha_fin,
"docfin" => $encargatura->nombredocfin,
"nrofin" => $encargatura->nrodocfin,
"archivofin" => $encargatura->archivofin
]
);
$cont++;
}
@endphp
<input type="hidden" id="encargaturas" name="encargaturas" value="{{ json_encode($arrayEncargaturas) }}" />
<input type="hidden" name="id-encargatura" id="id-encargatura">
<div class="row">
    <div class="col-sm-6 div-12">
        <div class="container pb-2 pl-0 pr-0">
            <div class="row">
                <div class="form-group col-12">
                    <label for="unidad-encargada">Unidad Orgánica<span class="required">*</span></label>
                    <select name="unidad-encargada" id="unidad-encargada" class="form-control" style="width: 100%;">
                        <option value="" selected disabled></option>
                        @foreach($areas as $area)
                        <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                        @endforeach
                    </select>

                </div>
                <div class="form-group col-12">
                    <label for="cargo-encargado">Cargo<span class="required">*</span></label>
                    <select name="cargo-encargado" id="cargo-encargado" class="form-control">
                        <option value="" selected disabled></option>
                        @foreach($cargos as $cargo)
                        <option value="{{ $cargo->id }}">{{ $cargo->nombre }}</option>
                        @endforeach
                        <option value="0">Agregar nuevo... </option>
                    </select>

                </div>
                <div class="form-floating col-12">
                    
                    <textarea name="descripcion-encargo" id="descripcion-encargo" class="form-control"></textarea>
                    <label for="descripcion-encargo">Actividades</label>
                </div>
            </div>
        </div>

        <div class="row border border-1 p-1">
            <h6 class="text-center text-munilc pb-1">Inicio</h6>
            <div class="form-floating">
                <input type="text" name="idtd-enc" id="idtd-enc" class="form-control" list="datalistOptions">
                <label for="idtd-enc">Tipo de Documento</label>
            </div>

            <div class="form-floating">
                <input type="text" class="form-control" id="nrodoc-enc" name="nrodoc-enc" placeholder="000-2025-MPLC">
                <label for="entidad">Nro Documento</label>
            </div>

            <div class="form-floating">
                <input type="date" class="form-control" id="ini-enc" name="ini-enc">
                <label for="periodo">Fecha Inicio</label>
            </div>
            <div class="form-floating">
                <input type="file" id="subidor-enc" name="subidor-enc" accept=".pdf">
                <input type="hidden" name="doc-ini-enc" id="doc-ini-enc">
                <div id="mensaje-enc"></div>
            </div>
        </div>
        <div class="row border border-1 p-1">
            <h6 class="text-center text-munilc pb-1">Fin</h6>
            <div class="form-floating">
                <input type="text" name="idtd-fin-enc" id="idtd-fin-enc" class="form-control" list="datalistOptions">

                <label for="idtd-fin-enc">Tipo de Documento</label>
            </div>

            <div class="form-floating">
                <input type="text" class="form-control" id="nrodoc-fin-enc" name="nrodoc-fin-enc" placeholder="000-2025-MPLC">
                <label for="entidad">Nro Documento</label>
            </div>

            <div class="form-floating">
                <input type="date" class="form-control" id="fin-enc" name="fin-enc">
                <label for="periodo">Fecha Fin</label>
            </div>
            <div class="form-floating">
                <input type="file" id="subidor-fin-enc" name="subidor-fin-enc" accept=".pdf">
                <input type="hidden" name="doc-fin-enc" id="doc-fin-enc">
                <div id="mensaje-fin-enc"> </div>
            </div>
        </div>
        <div class="text-center">
            <a class="btn btn-outline-info ml-auto mr-0 " onclick="guardarEncargatura()" style="margin-right: 10px;">Guardar Cambios</a>
        </div>
    </div>
    <div class="col-sm-6 col-12">
        <div id="tabla-encargatura">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Área</th>
                        <th scope="col">Cargo</th>
                        <th scope="col">Función</th>
                        <th scope="col">Inicio</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $cont = 1;
                    @endphp
                    @foreach($encargaturas as $encargatura)
                    <tr>
                        <th>
                            @foreach( $areas as $area )
                            {{$area->id == $encargatura->unidad_organica_destino ? $area->nombre : ''}}
                            @endforeach
                        </th>
                        <th>
                            @foreach($cargos as $cargo)
                            {{ $cargo->id == $encargatura->cargo ? $cargo->nombre : ''}}
                            @endforeach
                        </th>
                        <th>
                            {{$encargatura->descripcion}}
                        </th>
                        <th>
                            {{\Carbon\Carbon::parse($encargatura->fecha_ini)->format('d-m-Y') }}
                        </th>

                        <th>
                            <a class="btn btn-outline-info btn-sm" onClick="editarEncargatura({{ $cont }})">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a class="btn btn-outline-danger btn-sm" onClick="anularEncargatura({{ $cont}})">
                                <i class="fa fa-trash"></i>
                            </a>
                        </th>
                    </tr>

                    @php
                    $cont = $cont + 1;
                    @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#unidad-encargada').select2({
            placeholder: "Buscar unidad...",
            allowClear: true
        });
    });

    document.getElementById("subidor-enc").addEventListener("change", function() {
        let archivo = this.files[0]; // Capturar el archivo seleccionado

        if (!archivo) {
            document.getElementById("mensaje-enc").innerHTML = "⚠ No se ha seleccionado ningún archivo.";
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
                document.getElementById("mensaje-enc").innerHTML = data.mensaje + ' <a href="../repositories/'+ data.name +'" target="_blank" class="text-success"><i class="fa fa-eye" ></i>Ver</a>';
                document.getElementById("doc-ini-enc").value = data.name;
            })
            .catch(error => console.error("Error al subir el archivo:", error));
    });

    document.getElementById("subidor-fin-enc").addEventListener("change", function() {
        let archivo = this.files[0]; // Capturar el archivo seleccionado

        if (!archivo) {
            document.getElementById("mensaje-fin-enc").innerHTML = "⚠ No se ha seleccionado ningún archivo.";
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
                document.getElementById("mensaje-fin-enc").innerHTML = data.mensaje + ' <a href="../repositories/'+ data.name +'" target="_blank" class="text-success"><i class="fa fa-eye" ></i>Ver</a>';
                document.getElementById("doc-fin-enc").value = data.name;
            })
            .catch(error => console.error("Error al subir el archivo:", error));
    });
</script>