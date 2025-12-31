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
$arrayVacaciones=[];
if (!empty($Vacaciones)){
foreach($Vacaciones as $vacacion){
array_push($arrayVacaciones, [
"cont"=> $cont,
"id"=> $vacacion->id,
"cambio" => 0,
"periodo" => $vacacion->periodo,
"tipodoc" => $vacacion->nombredoc,
"nrodoc" => $vacacion->nrodoc,

"observaciones" => $vacacion->observaciones,
"suspension" => $vacacion->suspension,
"dias" => $vacacion->dias,

"mes" => $vacacion->mes,
"inicio" => $vacacion->fecha_ini,
"fin" => $vacacion->fecha_fin,
"archivo"=>$vacacion->archivo
]);
$cont++;
}
}
@endphp
<input type="hidden" name="id-vacacion" id="id-vacacion">
<input type="hidden" name="vacaciones" id="vacaciones" value="{{json_encode($arrayVacaciones)}}">
<div class="container p-0">
    <div class="row">
        <div class="col-12 col-sm-6">
            <div class="form-floating ">
                <input type="number" class="form-control" id="periodo-vac" name="periodo-vac" min="1990" value="2025">
                <label for="entidad">Periodo<span class="required">*</span></label>
            </div>
            <div class="row">
                <div class="form-floating">
                    <input type="text" name="tipodoc-vac" id="tipodoc-vac" class="form-control" list="datalistOptions">
                    <label for="tipodoc-vac">Tipo Documento</label>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control" id="nrodoc-vac" name="nrodoc-vac">
                    <label for="nrodoc-vac">N° Documento<span class="required">*</span></label>
                </div>


                <div class="form-floating" id="obs_vac">
                    <input type="text" class="form-control" id="observaciones-vac" name="observaciones-vac">
                    <label for="entidad">Observaciones</label>
                </div>
                <div class="form-floating" id="sus_vac">
                    <select class="form-select" id="suspension-vac" name="suspencion-vac">
                        <option value="NO">NO</option>
                        <option value="SI">SI</option>
                    </select>
                    <label for="archivo">Suspender</label>
                </div>


                <div class="form-floating">
                    <select class="form-select" id="mes-vac" name="mes-vac" style="flex-grow: 1;">
                        <option value="" selected disabled></option>
                        <option value="1">ENERO</option>
                        <option value="2">FEBRERO</option>
                        <option value="3">MARZO</option>
                        <option value="4">ABRIL</option>
                        <option value="5">MAYO</option>
                        <option value="6">JUNIO</option>
                        <option value="7">JULIO</option>
                        <option value="8">AGOSTO</option>
                        <option value="9">SETIEMBRE</option>
                        <option value="10">OCTUBRE</option>
                        <option value="11">NOVIEMBRE</option>
                        <option value="12">DICIEMBRE</option>
                    </select>
                    <label for="archivo" class="required">Mes<span class="required">*</span></label>
                </div>



                <div class="form-floating " id="desde_vlp">
                    <input type="date" class="form-control" id="fecha-ini-vac" name="fecha-ini-vac">
                    <label for="entidad">Desde<span class="required">*</span></label>
                </div>
                <div class="form-floating " id="hasta_vlp">
                    <input type="date" class="form-control" id="fecha-fin-vac" name="fecha-fin-vac">
                    <label for="entidad">Hasta<span class="required">*</span></label>
                </div>
                <div class="form-floating" id="dias_vlp">
                    <input type="number" class="form-control" id="dias-vac" name="dias-vac" readonly>
                    <label for="periodo">Dias</label>
                </div>
                <div class="col">
                    <input type="file" id="subidor-vac" accept=".pdf">
                    <input type="hidden" name="doc-vac" id="doc-vac">
                    <div id="mensaje-vac"></div>
                </div>
            </div>
            <div class="text-center">
                <a class="btn btn-outline-info ml-auto mr-0 " onclick="guardarVacaciones()" style="margin-right: 10px;">Guardar Cambios</a>
            </div>
        </div>
        <div class="col-sm-6 col-12">

            <div id="resumen-vacaciones">
                <div class="row">
                    <div class="col-sm-6 col-12">
                        <div class="card m-1">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Licencias</th>
                                    </tr>
                                </thead>
                                <tbody id="ll">
                                    @php $td=0 @endphp
                                    @php $dl=0 @endphp
                                    @if(!empty($historiall))
                                    @foreach($historiall as $l)
                                    <tr>
                                        <td class="p-0 {{$l->acuentavac == 1? 'list-group-item-warning':''}}">
                                            <div class="blockquote-footer m-0">
                                                <div class="fw-bold">
                                                    {{ Str::limit($l->descripcion, 40) }} -
                                                    <span class="badge text-bg-warning rounded-pill ">{{$l->dias}} días</span>
                                                </div>
                                                Inicio:{{ \Carbon\Carbon::parse($l->fecha_ini)->format('d-m-Y') }} - Fin: {{ \Carbon\Carbon::parse($l->fecha_fin)->format('d-m-Y')}}

                                            </div>
                                        </td>
                                    </tr>
                                    @php
                                    if($l->acuentavac==1)
                                    $dl=$dl+$l->dias
                                    @endphp
                                    @endforeach
                                    @endif
                                    <tr>
                                        <td class="table-active p-0">
                                            <div class="card-footer text-body-secondary fw-bold" id="totall">
                                                Total: {{$dl}} días
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-6 col-12">
                        <div class="card m-1">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Permisos</th>
                                    </tr>
                                </thead>
                                <tbody id="lp">
                                    @php $dp=0 @endphp
                                    @if(!empty($historialp) )
                                    @foreach($historialp as $p)
                                    <tr>
                                        <td class=" p-0 {{$p->acuentavac == 1? 'list-group-item-warning':''}}">
                                            <figcaption class="blockquote-footer">
                                                <div class="fw-bold">{{$p->descripcion}}</div>
                                                Inicio:{{\Carbon\Carbon::parse($p->fecha_ini)->format('d-m-Y') }} - Fin{{\Carbon\Carbon::parse($p->fecha_fin)->format('d-m-Y')}}
                                                <span class="badge text-bg-warning rounded-pill">{{$p->dias}} días</span>
                                            </figcaption>

                                        </td>
                                    </tr>

                                    @php if($p->acuentavac==1)
                                    $dp=$dp+$p->dias
                                    @endphp
                                    @endforeach
                                    @endif
                                    <tr>
                                        <td class="table-active p-0">
                                            <div class="card-footer text-body-secondary fw-bold" id="totalp">
                                                Total: {{$dp}} días
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>


            </div>
            <div id="tabla-vacaciones">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Periodo</th>
                            <th scope="col">Inicio</th>
                            <th scope="col">fin</th>
                            <th scope="col">Días</th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $cont=1 @endphp
                        @if (!empty($vacaciones))
                        @foreach($vacaciones as $vc)
                        <tr>
                            <th>
                                {{$vc->periodo}}
                            </th>
                            <th>
                                {{\Carbon\Carbon::parse($vc->fecha_ini)->format('d-m-Y') }}
                            </th>
                            <th>
                                {{\Carbon\Carbon::parse($vc->fecha_fin)->format('d-m-Y') }}
                            </th>
                            <th>
                                {{ $vc->dias }}
                                @php $td=$td+$vc->dias @endphp
                            </th>

                            <th>
                                <a class="btn btn-outline-info btn-sm" onClick="editarVacaciones({{ $cont }})">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a class="btn btn-outline-danger btn-sm" onClick="anularVacaciones({{ $cont}})">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </th>
                        </tr>
                        @php $cont ++ @endphp
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div id="resumen-dias">
                @php $td=$td+$dl+$dp @endphp
                @if($td < 30)
                    <div class="alert alert-success" role="alert">
                    Disponible: {{30-$td}} días
            </div>
            @else
            <div class="alert alert-warning" role="alert">
                No disponible
            </div>
            @endif

        </div>
    </div>
</div>

</div>

<div id="cronogramaWarning" class="alert alert-danger mt-1" style="display: none;"></div>
<div id="diasWarning" class="alert alert-danger" style="display: none; "></div>

<!--<a id="btnfam" class="btn btn-success ml-auto mr-0 mt-2" style="margin-right: 10px;">Guardar</a>-->

@push('scripts')

<script src="{{asset('js/vacaciones.js')}}"></script>

@endpush
<script>
    document.getElementById("subidor-vac").addEventListener("change", function() {
        let archivo = this.files[0]; // Capturar el archivo seleccionado

        if (!archivo) {
            document.getElementById("mensaje-vac").innerHTML = "⚠ No se ha seleccionado ningún archivo.";
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
                document.getElementById("mensaje-vac").innerHTML = data.mensaje + ' <a href="../repositories/' + data.name + '" target="_blank" class="text-success"><i class="fa fa-eye" ></i>Ver</a>';
                document.getElementById("doc-vac").value = data.name;
            })
            .catch(error => console.error("Error al subir el archivo:", error));
    });
</script>