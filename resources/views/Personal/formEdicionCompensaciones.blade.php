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
</style>
@php
$cont=1;
$arrayCompensaciones=[];
if (!empty($compensaciones)){
foreach($compensaciones as $compensacion){
array_push($arrayCompensaciones, [
"cont"=> $cont,
"id"=> $compensacion->id,
"cambio" => 0,
"tipo" => $compensacion->tipo_compensacion,
"descripcion" => $compensacion->descripcion,
"tipodoc" => $compensacion->nombredoc,
"nrodoc" => $compensacion->nrodoc,
"inicio" => $compensacion->fecha_ini,
"dias" => $compensacion->dias,
"fin" => $compensacion->fecha_fin,
"archivo"=>$compensacion->archivo
]);
$cont++;
}
}
@endphp
<input type="hidden" name="compensaciones" id="compensaciones" value="{{json_encode($arrayCompensaciones)}}">
<input type="hidden" name="id-compensacion" id="id-compensacion" value="">
<div class="row">
    <div class="col-sm-6 col-12">
        <div class="form-row gap-3">
            <div class="form-floating">
                <select id="tipo-compensacion" name="tipo-compensacion" class="form-select">
                    <option value="" disabled selected></option>
                    @foreach($tcomp as $t)
                    <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                    @endforeach
                    <option value="0">Agregar más...</option>
                </select>
                <label for="entidad">Tipo de compensacion<span class="required">*</span></label>
            </div>
            <div class="form-floating">
                <input type="text" name="tipodoc-com" id="tipodoc-com" class="form-control" list="datalistOptions">
                <label for="tipodoc-vac">Tipo Documento</label>
            </div>

            <div class="form-floating">
                <input type="text" class="form-control" id="nrodoc-com" name="nrodoc-com" placeholder="Ejm. 232-2023">
                <label for="entidad">Nro Documento</label>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control" id="descripcion-com" name="descripcion-com">
                <label for="entidad">Descripción</label>
            </div>
            <div class="form-floating" id="desde_vlp">
                <input type="date" class="form-control" id="fecha-ini-comp" name="fecha-ini-comp">
                <label for="entidad">Desde<span class="required">*</span></label>
            </div>
            <div class="form-floating" id="dias_vlp">
                <input type="number" class="form-control" min="1" value="1" id="dias-comp" name="dias-comp">
                <label for="dias-comp">Dias</label>
            </div>
            <div class="form-floating" id="hasta_vlp">
                <input type="date" class="form-control" id="fecha-fin-comp" name="fecha-fin-comp" readonly>
                <label for="entidad">Hasta</label>
            </div>
            <div class="col">
                <input type="file" id="subidor-comp" accept=".pdf">
                <input type="hidden" name="doc-comp" id="doc-comp">
                <div id="mensaje-comp"></div>
            </div>
        </div>
        <div class="col-12 text-center mt-2">
            <a class="btn btn-outline-info ml-auto mr-0 " onclick="guardarCompensacion()" style="margin-right: 10px;">Guardar Cambios</a>
        </div>

    </div>
    <div class="col-sm-6 col-12">
        <div id="tabla-compensaciones">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Descripción</th>
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
                    @if(!empty($compensaciones))
                    @foreach($compensaciones as $com)
                    <tr>
                        <th>
                            {{$com->descripcion}}
                        </th>
                        <th>
                            {{ \Carbon\Carbon::parse($com->fecha_ini)->format('d-m-Y') }}
                        </th>
                        <th>
                            {{ \Carbon\Carbon::parse($com->fecha_fin)->format('d-m-Y') }}
                        </th>
                        <th>
                            {{ $com->dias }}
                        </th>

                        <th>
                            <a class="btn btn-outline-info btn-sm" onClick="editarCompensacion({{ $cont }})">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a class="btn btn-outline-danger btn-sm" onClick="anularCompensacion({{ $cont}})">
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
<script src="{{asset('js/compensaciones.js')}}"></script>
@endpush
<script>
    document.getElementById("subidor-comp").addEventListener("change", function() {
        let archivo = this.files[0]; // Capturar el archivo seleccionado

        if (!archivo) {
            document.getElementById("mensaje-comp").innerHTML = "⚠ No se ha seleccionado ningún archivo.";
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
                document.getElementById("mensaje-comp").innerHTML = data.mensaje + ' <a href="../repositories/'+ data.name +'" target="_blank" class="text-success"><i class="fa fa-eye" ></i>Ver</a>';
                document.getElementById("doc-comp").value = data.name;
            })
            .catch(error => console.error("Error al subir el archivo:", error));
    });
</script>