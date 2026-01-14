@extends('layouts.app')
@section('content')
<style>
    .accordion-button:not(.collapsed) {
        color: #1e8e3e;
        background-color: #f4fff7;
    }

    .boton-flotante {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        border-radius: 50px;
        padding: 15px 20px;
        font-size: 18px;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
@php
$vinculo = "";
$vencido=false;
date_default_timezone_set("America/Lima");
// Obtener la fecha actual
$fecha_actual = date("Y-m-d");
if(!is_null($ult_vin)){
if(!is_null($ult_vin->fecha_fin)){
if($ult_vin->fecha_fin > $fecha_actual)
$vinculo="";
else{
$vinculo="disabled";
$vencido=true;
}
}
}else{
$vinculo="";
$vencido=false;
}


@endphp
<form id="edicionPersonal" action="{{ route('guardarEdicion', ['id' => $personal_id]) }}" method="POST"
    class="flex-1 d-flex flex-column gap-4" enctype="multipart/form-data">
    <button type="submit" style="background-color: #1e8e3e; border-color: #1e8e3e;" class="btn btn-primary boton-flotante" id="guardar-edicion"><i class="fas fa-save"></i>Guardar</button>
    @csrf
    <div class="card ">
        <div class="card-header text-munilc d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-munilc">
                Datos del Personal
            </h6>
            <span class="">
                <input class="form-check-input" type="checkbox" id="verificar" name="verificar" value="1" {{$dp->verificador>0? 'checked disabled':''}}>
                <label class="form-check-label" for="verificar">Datos Verificados</label>
            </span>
            <span class=" font-weight-bold">
                {{ $dp->Nombres }} {{ $dp->Apaterno }} {{ $dp->Amaterno }}
            </span>
        </div>
        <div class="card-body text-center d-flex flex-row row pt-0">
            <div class="accordion accordion-flush container p-0" id="accordionLegajo">


                <div class="accordion-item col-12 p-0 pb-2 ">
                    <h2 class="accordion-header " id="flush-headingPersonales">
                        <button class="accordion-button collapsed text-munilc" type="button"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapsePersonales"
                            aria-expanded="true" aria-controls="flush-collapseFamiliares">
                            Datos Personales
                        </button>
                    </h2>
                    <div id="flush-collapsePersonales" class="accordion-collapse collapse show pt-1"
                        aria-labelledby="flush-headingPersonales" data-bs-parent="#accordionLegajo">
                        <input type="hidden" class="form-control" value="FOTO PERFIL" name="foto-perfil" id="foto-perfil">
                        <input type="hidden" id="idpersonal" name="idpersonal" value='{{$personal_id}}'>
                        <div class="flex-1 d-row gap-3 w-100 ">
                            <div class="flex-1 d-flex flex-column">
                                <img id="profileImage_prev" class="card-img-top profile-image"
                                    style="height: 150px; width: 120;" alt="La imagen no esta disponible"
                                    src="{{ !empty($dp->foto) ? asset('repositories/'.$dp->foto) : asset('img/perfil.png') }}">

                                <button type="button" class="btn btn-primary btn-sm"
                                    onclick="document.getElementById('archivo_prev').click()">
                                    <i class="fas fa-upload"></i> Actualizar foto
                                    <input type="file" accept="image/*" id="archivo_prev" class="form-control" name="archivo" style="display: none; " onchange="previewImage()">
                                </button>
                                <input type="hidden" id="url-general" name="url-general" value="{{ $dp->urlgeneral ?? ''}}">

                            </div>

                            <div class="flex-1 d-col gap-3">
                                <div class="form-floating">
                                    <select class="form-select required-field" id="doc-identificacion"
                                        name="doc-identificacion" aria-label="Floating label select example">
                                        @if ( optional($dp)->id_identificacion !=null )
                                        <option {{ $dp->id_identificacion=="DNI" ? 'selected':'' }} value="DNI">DNI</option>
                                        <option {{ $dp->id_identificacion=="CE" ? 'selected':'' }} value="CE">CARNET DE EXTRANJERIA</option>
                                        <option {{ $dp->id_identificacion=="PTP" ? 'selected':'' }} value="PTP">PERMISO TEMPORAL DE TRABAJO</option>
                                        <option {{ $dp->id_identificacion=="S/D" ? 'selected':'' }} value="S/D">SIN DOCUMENTO</option>
                                        @else
                                        <option value="" selected disabled></option>
                                        <option value="CE">CARNET DE EXTRANJERIA</option>
                                        <option value="PTP">PERMISO TEMPORAL DE TRABAJO</option>
                                        <option value="S/D">SIN DOCUMENTO</option>
                                        @endif
                                    </select>
                                    <label for="doc-identificacion">Tipo doc. ident.</label>
                                </div>

                                <div class="form-floating">
                                    <input type="number" class="form-control required-field" id="nro-identificacion"
                                        name="nro-identificacion" value="{{ optional($dp)->nro_documento_id }}">
                                    <label for="nro-identificacion" class="dynamic-label required-field-l">Nº
                                        Documento Identidad</label>
                                </div>

                                <div class="form-floating">
                                    <select id="tipo-personal" name="tipo-personal"
                                        class="form-select required-field">
                                        <option value="" selected disabled></option>
                                        @foreach($tpersonal as $tp)
                                        <option {{ optional($dp)->id_tipo_personal==$tp->id ? 'selected':'' }} value="{{ $tp->id ?? '' }}">{{ $tp->nombre }}</option>
                                        @endforeach
                                        <option value="0">Agregar nuevo...</option>
                                    </select>
                                    <label for="tipo-personal" class="required-field-l">Tipo de Personal</label>
                                </div>
                            </div>

                            <div class="flex-1 d-col gap-3">
                                <div class="form-floating ">
                                    <input type="text" class="form-control required-field" id="apaterno"
                                        name="apaterno" value="{{ $dp->Apaterno }}" required>
                                    <label for="apaterno" class="required-field-l">Apellido Paterno</label>
                                </div>

                                <div class="form-floating">
                                    <input type="text" class="form-control required-field" id="amaterno"
                                        name="amaterno" value="{{ $dp->Amaterno }}" required>
                                    <label for="amaterno" class="required-field-l">Apellido Materno</label>
                                </div>

                                <div class="form-floating">
                                    <input type="text" class="form-control required-field" id="nombres"
                                        name="nombres" value="{{ $dp->Nombres }}" required>
                                    <label for="nombres">Nombres</label>
                                </div>
                            </div>
                            <div class="flex-1 d-col gap-3">
                                <div class="form-floating">
                                    <select id="sexo" name="sexo" class="form-select">
                                        <option value="" selected disabled></option>
                                        <option {{ $dp->sexo=='M'?'selected':'' }} value="M">MASCULINO</option>
                                        <option {{ $dp->sexo=='F'?'selected':'' }} value="F">FEMENINO</option>
                                    </select>
                                    <label for="Psexo">Sexo</label>
                                </div>

                                <div class="form-floating">
                                    <input type="date" class="form-control" id="fecha-nacimiento"
                                        name="fecha-nacimiento" value="{{ $dp->FechaNacimiento }}" required>
                                    <label for="fecha-nacimiento">Fecha de nacimiento</label>
                                </div>

                                <div class="form-floating">
                                    <select id="estadocivil" name="estadocivil" class="form-select">
                                        <option value="" selected disabled></option>
                                        <option {{ $dp->EstadoCivil=='SOLTERO'?'selected':'' }} value="SOLTERO">SOLTERO(A)</option>
                                        <option {{ $dp->EstadoCivil=='CASADO'?'selected':'' }} value="CASADO">CASADO(A)</option>
                                        <option {{ $dp->EstadoCivil=='VIUDO'?'selected':'' }} value="VIUDO">VIUDO(A)</option>
                                        <option {{ $dp->EstadoCivil=='DIVORCIADO'?'selected':'' }} value="DIVORCIADO">DIVORCIADO(A)</option>
                                        <option {{ $dp->EstadoCivil=='CONVIVIENTE'?'selected':'' }} value="CONVIVIENTE">CONVIVIENTE</option>
                                    </select>
                                    <label for="PEstadoCivil">Estado Civil</label>
                                </div>
                            </div>

                        </div>

                        <div class="flex-1 d-flex d-row gap-3">

                            <div class="form-floating">
                                <input type="text" class="form-control" id="procedencia" name="procedencia"
                                    placeholder="Lugar de Procedencia" value="{{ $dp->lprocedencia }}">
                                <label for="procedencia">Lugar de Procedencia</label>
                            </div>

                            <div class="form-floating">
                                <input type="text" class="form-control" id="celular" name="celular" value="{{ $dp->NroCelular }}">
                                <label for="celular">Nro de Celular</label>
                            </div>

                            <div class="form-floating">
                                <input type="email" class="form-control" id="correo" name="correo"
                                    placeholder="Correo Electronico" value="{{ $dp->Correo }}">
                                <label for="correo">Correo Electrónico</label>
                            </div>

                            <div class="form-floating">
                                <input type="number" class="form-control" id="ruc" name="ruc" value="{{ $dp->NroRuc }}">
                                <label for="ruc">Nro de Ruc</label>
                            </div>

                            <div class="form-floating">
                                <input type="text" class="form-control" id="nroessalud" name="nroessalud" value="{{ $dp->NroEssalud }}"
                                    placeholder="Nro de Carne Essalud">
                                <label for="PNroEssalud">Nro de Carne Essalud</label>
                            </div>

                            <div class="form-floating">
                                <input type="text" class="form-control" id="pcentroessalud" name="pcentroessalud" value="{{ $dp->CentroEssalud }}"
                                    placeholder="Centro de Atencion Essalud">
                                <label for="PCentroEssalud" class="dynamic-label">
                                    Centro de Atencion Essalud
                                </label>
                            </div>

                            <div class="col-md">
                                <label for="afiliacion">Afiliación</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="radioDefault" id="Essalud" value="ESSALUD" {{$dp->afiliacion_salud =="ESSALUD"? 'checked':''}}>
                                    <label class="form-check-label" for="Essalud">
                                        Essalud
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="radioDefault" id="SIS" value="SIS" {{$dp->afiliacion_salud =="SIS"? 'checked':''}}>
                                    <label class="form-check-label" for="SIS">
                                        Seguro SIS
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="radioDefault" id="SCTR" value="SCTR" {{$dp->afiliacion_salud =="SCTR"? 'checked':''}}>
                                    <label class="form-check-label" for="SCTR">
                                        SCTR
                                    </label>
                                </div>
                            </div>

                            <div class="form-floating">
                                <input type="text" class="form-control" id="pgruposanguineo" name="pgruposanguineo" value="{{ $dp->GrupoSanguineo }}"
                                    placeholder="Grupo Sanguineo">
                                <label for="PGrupoSanguineo">Grupo Sanguineo</label>

                            </div>

                            <div class="form-floating">
                                <select id="regimenp" name="regimenp" class="form-select">
                                    <option value="" selected disabled></option>
                                    @foreach($rp as $t)
                                    <option {{$dp->id_regimenp==$t->id ? 'selected':''}} value="{{ $t->id }}">{{ $t->nombre }}</option>
                                    @endforeach
                                    <option value="0">Agregar más...</option>
                                </select>
                                <label for="Pregimenp">Régimen Pensionario</label>
                            </div>
                            <div class="form-floating">
                                <select id="sistema-pensionario" name="sistema-pensionario" class="form-select">
                                    <option value="" selected disabled></option>
                                    <option {{ $dp->afp=='PROFUTURO'? 'selected':'' }} value="PROFUTURO">PROFUTURO</option>
                                    <option {{ $dp->afp=='HABITAT'? 'selected':'' }} value="HABITAT">HABITAT</option>
                                    <option {{ $dp->afp=='HORIZONTE'? 'selected':'' }} value="HORIZONTE">HORIZONTE</option>
                                    <option {{ $dp->afp=='PRIMA'? 'selected':'' }} value="PRIMA">PRIMA</option>
                                    <option {{ $dp->afp=='INTEGRA'? 'selected':'' }} value="INTEGRA">INTEGRA</option>
                                    <option {{ $dp->afp=='ONP'? 'selected':'' }} value="ONP">ONP</option>
                                </select>
                                <label for="sistema-pensionario">Sistema pensionario</label>

                            </div>

                            <div class="form-floating">
                                <select id="discapacidad" name="discapacidad" class="form-select">
                                    <option value="" selected disabled></option>
                                    <option {{ $dp->discapacidad=='NO'? 'selected':'' }} value="NO">NO</option>
                                    <option {{ $dp->discapacidad=='PARCIAL'? 'selected':'' }} value="PARCIAL">PARCIAL</option>
                                    <option {{ $dp->discapacidad=='SI'? 'selected':'' }} value="SI">SI</option>
                                </select>
                                <label for="Pdiscapacidad">Discapacidad</label>
                            </div>

                            <div class="form-floating">
                                <select id="ffaa" name="ffaa" class="form-select">
                                    <option value="" selected disabled></option>
                                    <option {{ $dp->ffaa=='NO'? 'selected':'' }} value="NO">NO</option>
                                    <option {{ $dp->ffaa=='SI'? 'selected':'' }} value="SI">SI</option>

                                </select>
                                <label for="ffaa">Licenciado FF. AA.</label>
                            </div>
                        </div>
                        @include('Personal.formEdicionDomicilio')
                        <div class="form-floating">
                            <div class="text-center">
                                <input type="file" name="archivo-dj" id="archivo-dj"  accept=".pdf">
                                 <div id="mensaje-dj">
                                    @if ($dp->urldj)
                                    <a href="../repositories/{{ $dp->urldj }}" target="_blank" class="text-success"><i class="fa fa-eye" ></i>Ver</a>
                                    @endif
                                 </div>
                                <input type="hidden" name="link-dj" id="link-dj" value="{{ $dp->urldj ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item col-12 p-0 pb-2">
                    <h2 class="accordion-header" id="flush-headingVinculo">
                        <button class="accordion-button collapsed text-munilc" type="button"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseVinculo"
                            aria-expanded="false" aria-controls="flush-collapseOne">
                            Vínculo Laboral
                        </button>
                    </h2>
                    <div id="flush-collapseVinculo" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingVinculo" data-bs-parent="#accordionLegajo">
                        <div class="accordion-body p-2">
                            @if (empty($ult_vin))
                            @include('datoslegajo.forms.vinculos.formContrato')
                            @else
                            @include('Personal.formEdicionContrato')
                            @endif
                        </div>
                    </div>
                </div>

                <div class="accordion-item col-12 p-0 pb-2 ">
                    <h2 class="accordion-header" id="flush-headingEstudios">
                        <button class="accordion-button collapsed text-munilc" type="button"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseEstudios"
                            aria-expanded="false" aria-controls="flush-collapseTwo">
                            Estudios
                        </button>
                    </h2>
                    <div id="flush-collapseEstudios" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingEstudios" data-bs-parent="#accordionLegajo">
                        <div class="accordion-body p-2">
                            @include('Personal.formEdicionEstudios')
                        </div>
                    </div>
                </div>

                <div class="accordion-item col-12 p-0 pb-2 ">
                    <h2 class="accordion-header" id="flush-headingColegiatura">
                        <button class="accordion-button collapsed text-munilc" type="button"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseColegiatura"
                            aria-expanded="false" aria-controls="flush-collapseThree">
                            Colegiatura
                        </button>
                    </h2>
                    <div id="flush-collapseColegiatura" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingColegiatura" data-bs-parent="#accordionLegajo">
                        <div class="accordion-body p-2">
                            @include('Personal.formEdicionColegiatura')
                        </div>
                    </div>
                </div>

                <div class="accordion-item col-12 p-0 pb-2 ">
                    <h2 class="accordion-header" id="flush-headingComplementarios">
                        <button class="accordion-button collapsed text-munilc" type="button"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseComplementarios"
                            aria-expanded="false" aria-controls="flush-collapseThree">
                            Estudios Complementarios
                        </button>
                    </h2>
                    <div id="flush-collapseComplementarios" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingComplementarios" data-bs-parent="#accordionLegajo">
                        <div class="accordion-body p-2">
                            @include('Personal.formEdicionEstudiosCom')
                        </div>
                    </div>
                </div>



                <div class="accordion-item col-12 p-0 pb-2">
                    <h2 class="accordion-header" id="flush-headingFamiliares">
                        <button class="accordion-button collapsed text-munilc" type="button"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseFamiliares"
                            aria-expanded="false" aria-controls="flush-collapseFamiliares">
                            Familiares
                        </button>
                    </h2>
                    <div id="flush-collapseFamiliares" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingFamiliares" data-bs-parent="#accordionLegajo">
                        @php
                        $cont=1;
                        $familiaresArray = [];
                        foreach ($familiares as $familiar) {
                        $familiaresArray[] = [
                        'cont'=>$cont,
                        'derecho'=> $familiar->derecho_habiente,
                        'direccion'=>$familiar->direccion,
                        'tel'=>$familiar->telefono,
                        'doc'=> $familiar->docid,
                        'nro' => $familiar->nroid,
                        'nombre' => $familiar->nombres,
                        'paterno' => $familiar->apaterno,
                        'materno' => $familiar->amaterno,
                        'parentesco' => $familiar->parentesco
                        ];
                        $cont++;
                        }

                        $familiaresJson = json_encode($familiaresArray);
                        @endphp
                        <input type="hidden" id="familiares" name="familiares" value='{{ $familiaresJson }}'>
                        <input type="hidden" name="id-familiar" id="id-familiar">
                        <div class="accordion-body p-2">
                            <div class="container p-0">
                                <div class="row">
                                    <div class="col-md-6 col">
                                        <div class="text-center m-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" value="" id="der-hab" name="der-hab" switch>
                                                <label class="form-check-label" for="der-hab">
                                                    Derecho Habiente
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-floating p-1">
                                            <select id="parentesco" name="parentesco" class="form-select">
                                                <option value="" selected disabled></option>
                                                <option value="PADRE">PADRE</option>
                                                <option value="MADRE">MADRE</option>
                                                <option value="CONYUGE">CONYUGE</option>
                                                <option value="HIJO(A)">HIJO</option>
                                                <option value="HIJO(A)">HIJA</option>
                                                <option value="ABUELO">ABUELO(A)</option>
                                                <option value="HERMANO">HERMANO(A)</option>
                                                <option value="NIETO">NIETO(A)</option>
                                                <option value="TIO">TÍO(A)</option>
                                                <option value="SOBRINO">SOBRINO(A)</option>
                                            </select>
                                            <label for="parentesco">Parentesco</label>
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">Tipo doc.</span>
                                            <select class="form-select" id="tipo-doc-fam" name="tipo-doc-fam">
                                                <option disabled selected></option>
                                                <option value="DNI">DNI</option>
                                                <option value="CE">CARNET DE EXTRANJERIA</option>
                                                <option value="PTP">PERMISO TEMPORAL DE TRABAJO</option>
                                            </select>
                                            <span class="input-group-text">Numero</span>
                                            <input type="number" class="form-control" id="nro-doc-fam" name="nro-doc-fam" placeholder="00000000">
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" id="nombre-fam" name="nombre-fam" class="form-control" placeholder="Juan">
                                            <label for="nombre-fam">Nombres</label>
                                        </div>

                                        <div class="form-floating">

                                            <input type="text" id="paterno-fam" name="paterno-fam" class="form-control" placeholder="Perez">
                                            <label for="paterno-fam">Paterno</label>
                                        </div>
                                        <div class="form-floating">

                                            <input type="text" id="materno-fam" name="materno-fam" class="form-control" placeholder="Cruz">
                                            <label for="materno-fam">Materno</label>
                                        </div>

                                        <div class="form-floating">

                                            <input type="text" id="direccion-fam" name="direccion-fam" class="form-control" placeholder="Perez">
                                            <label for="direccion-fam">Dirección</label>
                                        </div>
                                        <div class="form-floating">

                                            <input type="tel" id="tel-fam" name="tel-fam" class="form-control" placeholder="99999999">
                                            <label for="tel-fam">Teléfono</label>
                                        </div>

                                        <a onclick="guardarFamiliar()" class="btn btn-outline-info"><i
                                                class="fas fa-user-plus mr-1"></i>Agregar Familiar
                                        </a>

                                    </div>
                                    <div class="col-md-6 col">
                                        <div id="tabla-familiares">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">DNI</th>
                                                        <th scope="col">Nombre</th>
                                                        <th scope="col">Parentesco</th>
                                                        <th scope="col">Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $cont=1; @endphp
                                                    @foreach($familiares as $familiar)
                                                    <tr>
                                                        <td>{{ $familiar->nro_documento_id }}</td>
                                                        <td>{{ $familiar->apaterno }} {{ $familiar->amaterno }} {{ $familiar->nombres }}</td>
                                                        <td>{{ $familiar->parentesco }}</td>
                                                        <td>
                                                            <a class="btn btn-outline-info btn-sm" onClick="editarFamiliar({{$cont}})">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                            <a class="btn btn-outline-danger btn-sm" onClick="anularFamiliar({{$cont}})">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="accordion-item col-12 p-0 pb-2 ">
                    <h2 class="accordion-header" id="flush-headingIdiomas">
                        <button class="accordion-button collapsed text-munilc" type="button"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseIdiomas"
                            aria-expanded="false" aria-controls="flush-collapseThree">
                            Idiomas
                        </button>
                    </h2>
                    <div id="flush-collapseIdiomas" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingIdiomas" data-bs-parent="#accordionLegajo">
                        <div class="accordion-body p-2">
                            @include('Personal.formEdicionIdiomas')
                        </div>
                    </div>
                </div>

                <div class="accordion-item col-12 p-0 pb-2 ">
                    <h2 class="accordion-header" id="flush-headingExperiencia">
                        <button class="accordion-button collapsed text-munilc" type="button"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseExperiencia"
                            aria-expanded="false" aria-controls="flush-collapseThree">
                            Experiencia Laboral
                        </button>
                    </h2>
                    <div id="flush-collapseExperiencia" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingExperiencia" data-bs-parent="#accordionLegajo">
                        <div class="accordion-body p-2">
                            @include('Personal.formEdicionExpLab')
                        </div>
                    </div>
                </div>



                <div class="accordion-item col-12 p-0 pb-2 ">
                    <h2 class="accordion-header" id="flush-headingRotaciones">
                        <button class="accordion-button collapsed text-munilc" type="button"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseRotaciones"
                            aria-expanded="false" aria-controls="flush-collapseThree">
                            Rotaciones
                        </button>
                    </h2>
                    <div id="flush-collapseRotaciones" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingRotaciones" data-bs-parent="#accordionLegajo">
                        <div class="accordion-body p-2">

                            @if(!empty($rotaciones))
                            @include('Personal.formEdicionRotaciones')
                            @else
                            @include('datoslegajo.forms.movimientos.formRotaciones')
                            @endif
                        </div>
                    </div>
                </div>

                <div class="accordion-item col-12 p-0 pb-2 ">
                    <h2 class="accordion-header" id="flush-headingEncargaturas">
                        <button class="accordion-button collapsed text-munilc" type="button"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseEncargaturas"
                            aria-expanded="false" aria-controls="flush-collapseThree">
                            Encargaturas
                        </button>
                    </h2>
                    <div id="flush-collapseEncargaturas" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingEncargaturas" data-bs-parent="#accordionLegajo">
                        <div class="accordion-body p-2">
                            @if(!empty($encargaturas))
                            @include('Personal.formEdicionEncargaturas')
                            @else
                            @include('datoslegajo.forms.movimientos.formEncargaturas')
                            @endif
                        </div>
                    </div>
                </div>



                <div class="accordion-item col-12 p-0 pb-2 ">
                    <h2 class="accordion-header" id="flush-headingLicencias">
                        <button class="accordion-button collapsed text-munilc" type="button"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseLicencias"
                            aria-expanded="false" aria-controls="flush-collapseThree">
                            Licencias
                        </button>
                    </h2>
                    <div id="flush-collapseLicencias" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingLicencias" data-bs-parent="#accordionLegajo">
                        <div class="accordion-body p-1">
                            @if(empty($licencias))
                            @include('datoslegajo.forms.formLicencias')
                            @else
                            @include('Personal.formEdicionLicencias')
                            @endif
                        </div>
                    </div>
                </div>

                <div class="accordion-item col-12 p-0 pb-2 ">
                    <h2 class="accordion-header" id="flush-headingPermisos">
                        <button class="accordion-button collapsed text-munilc" type="button"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapsePermisos"
                            aria-expanded="false" aria-controls="flush-collapseThree">
                            Permisos
                        </button>
                    </h2>
                    <div id="flush-collapsePermisos" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingPermisos" data-bs-parent="#accordionLegajo">
                        <div class="accordion-body p-2">
                            @if(empty($permisos))
                            @include('datoslegajo.forms.formPermisos')
                            @else
                            @include('Personal.formEdicionPermisos')
                            @endif
                        </div>
                    </div>
                </div>

                <div class="accordion-item col-12 p-0 pb-2 ">
                    <h2 class="accordion-header" id="flush-headingCompensaciones">
                        <button class="accordion-button collapsed text-munilc" type="button"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseCompensaciones"
                            aria-expanded="false" aria-controls="flush-collapseThree">
                            Compensaciones
                        </button>
                    </h2>
                    <div id="flush-collapseCompensaciones" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingCompensaciones" data-bs-parent="#accordionLegajo">
                        <div class="accordion-body p-2">
                            @if(empty($compensaciones))
                            @include('datoslegajo.forms.formCompensaciones')
                            @else
                            @include('Personal.formEdicionCompensaciones')
                            @endif
                        </div>
                    </div>
                </div>


                <div class="accordion-item col-12 p-0 pb-2 ">
                    <h2 class="accordion-header" id="flush-headingReconocimientos">
                        <button class="accordion-button collapsed text-munilc" type="button"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseReconocimientos"
                            aria-expanded="false" aria-controls="flush-collapseThree">
                            Reconocimientos
                        </button>
                    </h2>
                    <div id="flush-collapseReconocimientos" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingReconocimientos" data-bs-parent="#accordionLegajo">
                        <div class="accordion-body p-2">
                            @if(empty($reconocimientos))
                            @include('datoslegajo.forms.formReconocimientos')
                            @else
                            @include('Personal.formEdicionReconocimientos')
                            @endif
                        </div>
                    </div>
                </div>

                <div class="accordion-item col-12 p-0 pb-2 ">
                    <h2 class="accordion-header" id="flush-headingSanciones">
                        <button class="accordion-button collapsed text-munilc" type="button"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseSanciones"
                            aria-expanded="false" aria-controls="flush-collapseThree">
                            Sanciones
                        </button>
                    </h2>
                    <div id="flush-collapseSanciones" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingSanciones" data-bs-parent="#accordionLegajo">
                        <div class="accordion-body p-2">
                            @if(empty($sanciones))
                            @include('datoslegajo.forms.formSanciones')
                            @else
                            @include('Personal.formEdicionSanciones')
                            @endif
                        </div>
                    </div>
                </div>

                <div class="accordion-item col-12 p-0 pb-2 ">
                    <h2 class="accordion-header" id="flush-headingVacaciones">
                        <button class="accordion-button collapsed text-munilc" type="button"
                            data-bs-toggle="collapse" data-bs-target="#flush-collapseVacaciones"
                            aria-expanded="false" aria-controls="flush-collapseThree">
                            Vacaciones
                        </button>
                    </h2>
                    <div id="flush-collapseVacaciones" class="accordion-collapse collapse"
                        aria-labelledby="flush-headingVacaciones" data-bs-parent="#accordionLegajo">
                        <div class="accordion-body p-2">
                            @if(empty($vacaciones))
                            @include('datoslegajo.forms.formVacaciones')
                            @else
                            @include('Personal.formEdicionVacaciones')
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</form>
<form id="formArchivo" enctype="multipart/form-data" class="pb-3 pt-3" style="display:;">
    @csrf
    <div class="text-center">
        <input type="file" name="archivo-big" id="archivo-big" required>
        <button type="submit" class="disabled">Subir a S3</button>
    </div>


    <div class="progress mt-2" style="height: 20px;">
        <div id="barraProgreso" class="progress-bar" role="progressbar" style="width: 0%;">
            0%
        </div>
    </div>
    <div id="resultado" class="mt-2">
        @if(!empty( $dp->urlgeneral))
        <a href="/archivos/{{$dp->urlgeneral}}" target="_blank">Ver archivo subido</a>
        @endif
    </div>
</form>
<div class="alert alert-warning" role="alert">
    Si desea anular este registro haga click <a href="#" class="alert-link" data-bs-toggle="modal" data-bs-target="#deleteModal">aquí</a>.
</div>


<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header  text-warning">
                <h5 class="modal-title text-danger" id="deleteModalLabel">Comfirmar</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Estás seguro que deseas anular el registro de {{$dp->Nombres}} {{$dp->Apaterno}} {{$dp->Amaterno}}? Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <a class="btn btn-outline-danger" href="{{ url('/anular-personal',['id'=>$dp->id_personal]) }}" id="confirmDelete">Anular registro</a>
            </div>
        </div>
    </div>
</div>
@include('Personal.modalNuevoTipo')
@stop
@push('scripts')
<script src="{{asset('js/edicionpersonal.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.getElementById("archivo-big").addEventListener("change", function() {
        let archivo = this.files[0]; // Capturar el archivo seleccionado

        if (!archivo) {
            document.getElementById("mensaje-col").innerHTML = "⚠ No se ha seleccionado ningún archivo.";
            return;
        }
        const formData = new FormData();
        formData.append("archivo", archivo);

        axios.post("/subir-carga", formData, {
                headers: {
                    'Content-Type': 'multipart/form-data', // ✅ importante si hay archivos
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                onUploadProgress: function(progressEvent) {
                    const porcentaje = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    const barra = document.getElementById('barraProgreso');
                    barra.style.width = porcentaje + '%';
                    barra.textContent = porcentaje + '%';
                }
            })
            .then(response => {
                document.getElementById('url-general').value = response.data.url;
                document.getElementById('resultado').innerHTML =
                    `<a href="/archivos/${response.data.url}" target="_blank">Ver archivo subido</a>`;
            })
            .catch(error => {
                document.getElementById('resultado').textContent = 'Error al subir el archivo';
                console.error(error);
            });


    });
    //carga url dj
    document.getElementById("archivo-dj").addEventListener("change", function() {
        let archivo = this.files[0]; // Capturar el archivo seleccionado

        if (!archivo) {
            document.getElementById("mensaje-dj").innerHTML = "⚠ No se ha seleccionado ningún archivo.";
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
                document.getElementById("mensaje-dj").innerHTML = data.mensaje + ' <a href="../repositories/'+ data.name +'" target="_blank" class="text-success"><i class="fa fa-eye" ></i>Ver</a>';
                document.getElementById("link-dj").value = data.name;
            })
            .catch(error => console.error("Error al subir el archivo:", error));

    });
    ///CARGA  FOTO DE PERFIL
    function previewImage() {
        const file = document.getElementById('archivo_prev').files[0];
        const img = new Image();
        img.src = URL.createObjectURL(file);

        //
        let archivo = document.getElementById('archivo_prev').files[0]; // Capturar el archivo seleccionado

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
                //document.getElementById("mensaje-foto").innerHTML = data.mensaje + ' <a href="../repositories/' + data.name + '" target="_blank" class="text-success"><i class="fa fa-eye" ></i>Ver</a>';
                document.getElementById("foto-perfil").value = data.name;
            })
            .catch(error => console.error("Error al subir el archivo:", error));

        //
        img.onload = function() {
            const reader = new FileReader();
            reader.onloadend = function() {
                document.getElementById('profileImage_prev').src = reader.result;
            }
            reader.readAsDataURL(file);

        }

    }
</script>

@endpush