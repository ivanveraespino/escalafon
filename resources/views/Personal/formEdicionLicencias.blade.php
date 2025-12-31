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
$arrayLicencias=[];
foreach($licencias as $licencia){

array_push($arrayLicencias, [
"cont"=> $cont,
"id"=> $licencia->id,
"cambio" => 0,
"descripcion" => $licencia->descripcion,
"tipodoc" => $licencia->nombredoc,
"nrodoc" => $licencia->nrodoc,
"observaciones" => $licencia->observaciones,
"inicio" => $licencia->fecha_ini,
"dias" => $licencia->dias,
"meses" => $licencia->mes,
"agnos" => $licencia->anio,
"fin" => $licencia->fecha_fin,
"acuenta" => $licencia->acuentavac,
"congoce" => $licencia->congoce,
"archivo"=> $licencia->archivo
]);
$cont++;
}
@endphp
<input type="hidden" name="licencias" id="licencias" value="{{json_encode($arrayLicencias)}}">
<input type="hidden" name="id-licencia" id="id-licencia">
<div class="row">
    <div class="col-sm-6 col-12">
        <div class="form-row gap-3">

            <div class="form-floating">
                <textarea id="descripcion-licencia" class="form-control" name="descripcion-licencia"
                    style="text-align: left; direction: ltr;"></textarea>
                <label>Motivo <span class="required">*</span></label>
            </div>

            <div class="form-floating">
                <input type="text" name="tipodoc-lic" id="tipodoc-lic" class="form-control" list="datalistOptions">
                <label for="tipodoc-lic">Tipo Documento</label>

            </div>
            <div class="form-floating">
                <input type="text" class="form-control" id="nrodoc-lic" name="nrodoc-lic">
                <label for="nrodoc-lic" class="required">N° Documento<span class="required">*</span></label>
            </div>

            <div class="form-floating" id="obs_vac">
                <input type="text" class="form-control" id="observaciones-lic" name="observaciones-lic"
                    placeholder="obs">
                <label for="observaciones-lic">Observaciones</label>
            </div>
            <div class="form-floating" id="desde_vlp">
                <input type="date" class="form-control" id="fecha-ini-lic" name="fecha-ini-lic">
                <label for="fecha-ini-lic">Desde<span class="required">*</span></label>
            </div>
            <div class="form-floating" id="hasta_vlp">
                <input type="date" class="form-control" id="fecha-fin-lic" name="fecha-fin-lic">
                <label for="fecha-fin-lic">Hasta<span class="required">*</span></label>
            </div>
            <div class="col-12 input-group form-floating mb-0">
                <span class="input-group-text form-control " for="">Duración:</span>
                <div class="form-floating mb-3">
                    <input type="number" min="0" id="dias-licencia" name="dias-licencia" class="form-control  pr-0" placeholder="D" aria-label="D">
                    <label for="dias-licencia">Días</label>
                </div>
                <div class="form-floating">
                    <input type="number" min="0" id="meses-licencia" name="meses-licencia" class="form-control  pr-0" placeholder="M" aria-label="M">
                    <label for="meses-licencia">Meses</label>
                </div>
                <div class="form-floating">
                    <input type="number" min="0" id="agnos-licencia" name="agnos-licencia" class="form-control  pr-0" placeholder="A" aria-label="A">
                    <label for="agnos-licencia">Años</label>
                </div>
            </div>

            <div id="acuenta" class="form-floating mb-0">
                <select class="form-select" id="acuentavac" name="acuentavac">
                    <option disabled selected></option>
                    <option value="0" selected>NO</option>
                    <option value="1">SI</option>
                </select>
                <label>A cuenta vac.<span class="required">*</span></label>
            </div>
            <div class="form-floating" id="cong">
                <select class="form-select" id="congoce" name="congoce">
                    <option hidden selected></option>
                    <option value="0" selected>NO</option>
                    <option value="1">SI</option>
                </select>
                <label>Con goce<span class="required">*</span></label>
            </div>
            <div class="col">
                <input type="file" id="subidor-lic" accept=".pdf">
                <input type="hidden" name="doc-lic" id="doc-lic">
                <div id="mensaje-lic"></div>
            </div>

        </div>
        <div class="text-center pt-2">
            <a class="btn btn-outline-info ml-auto mr-0 " onclick="guardarLicencia()" style="margin-right: 10px;">Guardar Cambios</a>
        </div>
    </div>
    <div class="col-sm-6 col-12">
        <div id="tabla-licencias">
            <table class="table">
                <caption class="text-start ">
                    <i class="fa fa-square me-2" style="color: #fff3cd;"></i>
                    A cuenta de vacaciones
                </caption>
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
                    @foreach($licencias as $lic)
                    <tr class="{{$lic->acuentavac==1 ? 'table-warning':''}}">
                        <th>
                            {{$lic->descripcion}}
                        </th>
                        <th>
                            {{\Carbon\Carbon::parse($lic->fecha_ini)->format('d-m-Y') }}
                        </th>
                        <th>
                            {{\Carbon\Carbon::parse($lic->fecha_fin)->format('d-m-Y') }}
                        </th>
                        <th>
                            {{ $lic->dias }}
                        </th>

                        <th>
                            <a class="btn btn-outline-info btn-sm" onClick="editarLicencia({{ $cont }})">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a class="btn btn-outline-danger btn-sm" onClick="anularLicencia({{ $cont}})">
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

<div id="cronogramaWarning" class="alert alert-danger mt-1" style="display: none;"></div>
<div id="diasWarning" class="alert alert-danger" style="display: none; "></div>

<!--    <a id="btnfam" class="btn btn-success ml-auto mr-0 mt-2"  style="margin-right: 10px;">Guardar</a> -->

@push('scripts')
<script src="{{asset('js/licencias.js')}}"></script>
@endpush
<script>
    document.getElementById("subidor-lic").addEventListener("change", function() {
        let archivo = this.files[0]; // Capturar el archivo seleccionado

        if (!archivo) {
            document.getElementById("mensaje-lic").innerHTML = "⚠ No se ha seleccionado ningún archivo.";
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
                document.getElementById("mensaje-lic").innerHTML = data.mensaje + ' <a href="../repositories/' + data.name + '" target="_blank" class="text-success"><i class="fa fa-eye" ></i>Ver</a>';
                document.getElementById("doc-lic").value = data.name;
            })
            .catch(error => console.error("Error al subir el archivo:", error));
    });
</script>