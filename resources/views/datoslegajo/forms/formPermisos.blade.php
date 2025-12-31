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
$arrayPermisos=[];
if(!empty($permisos)){
foreach($permisos as $permiso){

array_push($arrayPermisos, [
"cont"=> $cont,
"id"=> $permiso->id,
"cambio" => 0,
"descripcion" => $permiso->descripcion,
"tipodoc" => $permiso->nombredoc,
"nrodoc" => $permiso->nrodoc,
"observaciones" => $permiso->observaciones,
"inicio" => $permiso->fecha_ini,
"dias" => $permiso->dias,
"meses" => $permiso->mes,
"agnos" => $permiso->anio,
"fin" => $permiso->fecha_fin,
"acuenta" => $permiso->acuentavac,
"congoce"=>$permiso->congoce,
"periodo"=>$permiso->periodo,
"archivo" => $permiso->archivo
]);
$cont++;
}
}
@endphp
<input type="hidden" name="permisos" id="permisos" value="{{json_encode($arrayPermisos)}}">
<input type="hidden" name="id-permiso" id="id-permiso">
<div class="row">
    <div class="col-sm-6 col-12">
        <div class="form-row gap-3">
            <div class="form-floating">
                <textarea id="motivo-per" class="form-control" name="motivo-per" style="text-align: left; direction: ltr;"></textarea>
                <label>Motivo <span class="required">*</span></label>
            </div>

            <div class="form-floating">
                <input type="text" name="tipodoc-per" id="tipodoc-per" class="form-control" list="datalistOptions">
                <label for="tipodoc-per">Tipo Documento</label>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control" id="nrodoc-per" name="nrodoc-per">
                <label for="nrodoc-per">N° Documento<span class="required">*</span></label>
            </div>

            <div class="form-floating" id="obs_vac">
                <input type="text" class="form-control" id="observaciones-per" name="observaciones-per">
                <label for="entidad">Observaciones</label>
            </div>
            <div class="form-floating" id="desde_vlp">
                <input type="date" class="form-control" id="fecha-ini-per" name="fecha-ini-per">
                <label for="entidad" class="required"><span class="required">*</span>Desde</label>
            </div>
            <div class="form-floating" id="hasta_vlp">
                <input type="date" class="form-control" id="fecha-fin-per" name="fecha-fin-per">
                <label for="entidad" class="required"><span class="required">*</span>Hasta</label>
            </div>
            <div class="col-12 input-group form-floating mb-0">
                <span class="input-group-text form-control " for="">Duración:</span>
                <div class="form-floating mb-3">
                    <input type="number" min="0" id="dias-permiso" name="dias-permiso" class="form-control  pr-0" placeholder="D" aria-label="D">
                    <label for="dias-permiso">Días</label>
                </div>
                <div class="form-floating">
                    <input type="number" min="0" id="meses-permiso" name="meses-permiso" class="form-control  pr-0" placeholder="M" aria-label="M">
                    <label for="meses-permiso">Meses</label>
                </div>
                <div class="form-floating">
                    <input type="number" min="0" id="agnos-permiso" name="agnos-permiso" class="form-control  pr-0" placeholder="A" aria-label="A">
                    <label for="agnos-permiso">Años</label>
                </div>
            </div>
            <div id="acuenta" class="form-floating">

                <select class="form-select" id="acuentavac-per" name="acuentavac-per">
                    <option value="" selected></option>
                    <option value="0">NO</option>
                    <option value="1">SI</option>
                </select>
                <label class="required">A cuenta vac.<span class="required">*</span></label>

            </div>
            <div class="form-floating col-2">

                <input type="number" name="periodo-permiso" id="periodo-permiso" class="form-control" disabled>
                <label class="periodo-permiso">Periodo<span class="required">*</span></label>

            </div>
            <div class="form-floating ">
                <select class="form-select" id="congoce-per" name="congoce-per">
                    <option value="" selected></option>
                    <option value="0">NO</option>
                    <option value="1">SI</option>
                </select>
                <label class="required">Con goce<span class="required">*</span></label>
            </div>
            <div class="col-3">
                <input type="file" id="subidor-per" accept=".pdf">
                <input type="hidden" name="doc-per" id="doc-per">
                <div id="mensaje-per"></div>
            </div>
        </div>
        <div class="col-12 text-center">
            <a class="btn btn-outline-info ml-auto mr-0 " onclick="guardarPermiso()" style="margin-right: 10px;">Guardar Cambios</a>
        </div>
    </div>
    <div class="col-sm-6 col-12">
        <div id="tabla-permisos">
            <table class="table">
                <caption class="text-start ">
                    <i class="fa fa-square me-2" style="color: #fff3cd;"></i>
                    A cuenta de vacaciones
                </caption>
                <thead>
                    <tr>
                        <th scope="col">Motivo</th>
                        <th scope="col">Periodo</th>
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
                    @if(!empty($permisos))
                    @foreach($permisos as $per)
                    <tr class="{{$per->acuentavac==1 ? 'table-warning':''}}">
                        <th>
                            {{$per->descripcion}}
                        </th>
                        <th>
                            {{$per->periodo}}
                        </th>
                        <th>
                            {{\Carbon\Carbon::parse($per->fecha_ini)->format('d-m-Y') }}
                        </th>
                        <th>
                            {{\Carbon\Carbon::parse($per->fecha_fin)->format('d-m-Y') }}
                        </th>
                        <th>
                            {{ $per->dias }}
                        </th>

                        <th>
                            <a class="btn btn-outline-info btn-sm" onClick="editarPermiso({{ $cont }})">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a class="btn btn-outline-danger btn-sm" onClick="anularPermiso({{ $cont}})">
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


<div id="cronogramaWarning" class="alert alert-danger mt-1" style="display: none;"></div>
<div id="diasWarning" class="alert alert-danger" style="display: none; "></div>

<!--    <a id="btnfam" class="btn btn-success ml-auto mr-0 mt-2"  style="margin-right: 10px;">Guardar</a> -->
@push('scripts')
<script src="{{asset('js/permisos.js')}}"></script>
@endpush
<script>
    document.getElementById("subidor-per").addEventListener("change", function() {
        let archivo = this.files[0]; // Capturar el archivo seleccionado

        if (!archivo) {
            document.getElementById("mensaje-per").innerHTML = "⚠ No se ha seleccionado ningún archivo.";
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
                document.getElementById("mensaje-per").innerHTML = data.mensaje + ' <a href="../repositories/' + data.name + '" target="_blank" class="text-success"><i class="fa fa-eye" ></i>Ver</a>';
                document.getElementById("doc-per").value = data.name;
            })
            .catch(error => console.error("Error al subir el archivo:", error));
    });
</script>