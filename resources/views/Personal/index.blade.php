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

<form id="personalNuevo" action="{{ route('guardarPersonalNuevo') }}" method="POST"
    class="flex-1 d-flex flex-column gap-4" enctype="multipart/form-data">
    <button type="submit" style="background-color: #1e8e3e; border-color: #1e8e3e;" class="btn btn-primary boton-flotante" id="guardar-nuevo"><i class="fas fa-save"></i>Guardar</button>
    @csrf
    <div class="card ">
        <div class="card-header text-munilc d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-munilc">
                Datos del Personal
            </h6>



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
                        <input type="hidden" class="form-control" value="FOTO PERFIL" name="nombres">
                        <input type="hidden" class="form-control"  name="foto-perfil" id="foto-perfil">
                        <input type="hidden" id="url-general" name="url-general" >

                        <div class="flex-1 d-row gap-3 w-100 ">
                            <div class="flex-1 d-flex flex-column">
                                <img id="profileImage_prev" class="card-img-top profile-image"
                                    style="height: 150px;" alt="La imagen no esta disponible"
                                    src="{{ $base64Image ?? asset('img/perfil.png') }}">

                                <button type="button" class="btn btn-primary btn-sm"
                                    onclick="document.getElementById('archivo_prev').click()">
                                    <i class="fas fa-upload"></i> Actualizar foto
                                    <input type="file" accept="image/*" id="archivo_prev" class="form-control "
                                        name="archivo" style="display: none;" onchange="previewImage()">
                                </button>
                            </div>

                            <div class="flex-1 d-col gap-3">
                                <div class="form-floating">
                                    <select class="form-select required-field" id="doc-identificacion"
                                        name="doc-identificacion" aria-label="Floating label select example">
                                        <option value="" selected disabled></option>
                                        <option value="DNI">DNI</option>
                                        <option value="CE">CARNET DE EXTRANJERIA</option>
                                        <option value="PTP">PERMISO TEMPORAL DE TRABAJO</option>
                                        <option value="S/D">SIN DOCUMENTO</option>
                                    </select>
                                    <label for="doc-identificacion">Tipo doc. ident.</label>
                                </div>

                                <div class="form-floating">
                                    <input type="number" class="form-control required-field" id="nro-identificacion"
                                        name="nro-identificacion">
                                    <label for="nro-identificacion" class="dynamic-label required-field-l">Nº
                                        Documento Identidad</label>
                                </div>

                                <div class="form-floating">
                                    <select id="tipo-personal" name="tipo-personal"
                                        class="form-select required-field">
                                        <option value="" selected disabled></option>
                                        @foreach($tpersonal as $tp)
                                        <option value="{{ $tp->id ?? '' }}">{{ $tp->nombre }}</option>
                                        @endforeach
                                        <option value="0">Agregar nuevo...</option>
                                    </select>
                                    <label for="tipo-personal" class="required-field-l">Tipo de Personal</label>
                                </div>
                            </div>

                            <div class="flex-1 d-col gap-3">
                                <div class="form-floating ">
                                    <input type="text" class="form-control required-field" id="apaterno"
                                        name="apaterno" required>
                                    <label for="apaterno" class="required-field-l">Apellido Paterno</label>
                                </div>

                                <div class="form-floating">
                                    <input type="text" class="form-control required-field" id="amaterno"
                                        name="amaterno" required>
                                    <label for="amaterno" class="required-field-l">Apellido Materno</label>
                                </div>

                                <div class="form-floating">
                                    <input type="text" class="form-control required-field" id="nombres"
                                        name="nombres" required>
                                    <label for="nombres">Nombres</label>
                                </div>
                            </div>
                            <div class="flex-1 d-col gap-3">
                                <div class="form-floating">
                                    <select id="sexo" name="sexo" class="form-select">
                                        <option value="" selected disabled></option>
                                        <option value="M">MASCULINO</option>
                                        <option value="F">FEMENINO</option>
                                    </select>
                                    <label for="Psexo">Sexo</label>
                                </div>

                                <div class="form-floating">
                                    <input type="date" class="form-control" id="fecha-nacimiento"
                                        name="fecha-nacimiento" required>
                                    <label for="fecha-nacimiento">Fecha de nacimiento</label>
                                </div>

                                <div class="form-floating">
                                    <select id="estadocivil" name="estadocivil" class="form-select">
                                        <option value="" selected disabled></option>
                                        <option value="SOLTERO">SOLTERO(A)</option>
                                        <option value="CASADO">CASADO(A)</option>
                                        <option value="VIUDO">VIUDO(A)</option>
                                        <option value="DIVORCIADO">DIVORCIADO(A)</option>
                                        <option value="CONVIVIENTE">CONVIVIENTE</option>
                                    </select>
                                    <label for="estadocivil">Estado Civil</label>
                                </div>
                            </div>

                        </div>

                        <div class="flex-1 d-flex d-row gap-3">

                            <div class="form-floating">
                                <input type="text" class="form-control" id="procedencia" name="procedencia"
                                    placeholder="Lugar de Procedencia">
                                <label for="procedencia">Lugar de Procedencia</label>
                            </div>

                            <div class="form-floating">
                                <input type="text" class="form-control" id="celular" name="celular">
                                <label for="PNroCelular">Nro de Celular</label>
                            </div>

                            <div class="form-floating">
                                <input type="email" class="form-control" id="correo" name="correo"
                                    placeholder="Correo Electronico">
                                <label for="correo">Correo Electrónico</label>
                            </div>

                            <div class="form-floating">
                                <input type="number" class="form-control" id="ruc" name="ruc">
                                <label for="ruc">Nro de Ruc</label>
                            </div>

                            <div class="form-floating">
                                <input type="text" class="form-control" id="nroessalud" name="nroessalud"
                                    placeholder="Nro de Carne Essalud">
                                <label for="PNroEssalud">Nro de Carne Essalud</label>
                            </div>

                            <div class="form-floating">
                                <input type="text" class="form-control" id="pcentroessalud" name="pcentroessalud"
                                    placeholder="Centro de Atencion Essalud">
                                <label for="PCentroEssalud" class="dynamic-label">
                                    Centro de Atencion Essalud
                                </label>
                            </div>

                            <div class="col-md">
                                <label for="afiliacion">Afiliación</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="radioDefault" id="Essalud" value="ESSALUD">
                                    <label class="form-check-label" for="Essalud">
                                        Essalud
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="radioDefault" id="SIS" value="SIS">
                                    <label class="form-check-label" for="SIS">
                                        Seguro SIS
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="radioDefault" id="SCTR" checked value="SCTR">
                                    <label class="form-check-label" for="SCTR">
                                        SCTR
                                    </label>
                                </div>
                            </div>

                            <div class="form-floating">
                                <input type="text" class="form-control" id="pgruposanguineo" name="pgruposanguineo"
                                    placeholder="Grupo Sanguineo">
                                <label for="PGrupoSanguineo">Grupo Sanguineo</label>

                            </div>

                            <div class="form-floating">
                                <select id="regimenp" name="regimenp" class="form-select">
                                    <option value="" selected disabled></option>
                                    @foreach($rp as $t)
                                    <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                                    @endforeach
                                    <option value="0">Agregar más..</option>
                                </select>
                                <label for="Pregimenp">Régimen Pensionario</label>
                            </div>

                            <div class="form-floating">
                                <select id="sistema-pensionario" name="sistema-pensionario" class="form-select">
                                    <option value="" selected disabled></option>
                                    <option value="PROFUTURO">PROFUTURO</option>
                                    <option value="HABITA">HABITAT</option>
                                    <option value="HORIZONTE">HORIZONTE</option>
                                    <option value="PRIMA">PRIMA</option>
                                    <option value="INTEGRA">INTEGRA</option>
                                    <option value="ONP">ONP</option>
                                </select>
                                <label for="sistema-pensionario">Sistema de pensionario</label>

                            </div>

                            <div class="form-floating">
                                <select id="discapacidad" name="discapacidad" class="form-select">
                                    <option value="" selected disabled></option>
                                    <option value="NO">NO</option>
                                    <option value="PARCIAL">PARCIAL</option>
                                    <option value="SI">SI</option>
                                </select>
                                <label for="Pdiscapacidad">Discapacidad</label>
                            </div>

                            <div class="form-floating">
                                <select id="ffaa" name="ffaa" class="form-select">
                                    <option value="" selected disabled></option>
                                    <option value="NO">NO</option>
                                    <option value="SI">SI</option>

                                </select>
                                <label for="ffaa">Licenciado FF. AA.</label>
                            </div>
                        </div>
                        @include('layouts.form_domicilio')
                        <div class="form-floating">
                            <div class="text-center">
                                <input type="file" name="archivo-dj" id="archivo-dj"  accept=".pdf">
                                 <div id="mensaje-dj"></div>
                                <input type="hidden" name="link-dj" id="link-dj">
                       
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
                            @include('datoslegajo.forms.vinculos.formContrato')
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
                            @include('datoslegajo.forms.formEstudios')
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
                            @include('datoslegajo.forms.formColegiatura')
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
                            @include('datoslegajo.forms.formEstudiosCom')
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
                        <input type="hidden" id="familiares" name="familiares" value="">
                        <input type="hidden" name="id-familiar" id="id-familiar">
                        <div class="accordion-body p-2">
                            <div class="container p-0">
                                <div class="row">
                                    <div class="col-md-6 col">
                                        <div class="text-center m-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" value="1" id="der-hab" name="der-hab" switch>
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
                                            <input type="number" id="nro-doc-fam" name="nro-doc-fam" class="form-control" placeholder="00000000" aria-label="Server">
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
                            @include('datoslegajo.forms.formIdiomas')
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
                            @include('datoslegajo.forms.formExpLab')
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
                            @include('datoslegajo.forms.movimientos.formRotaciones')
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

                            @include('datoslegajo.forms.movimientos.formEncargaturas')
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
                            @include('datoslegajo.forms.formLicencias')
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

                            @include('datoslegajo.forms.formPermisos')
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

                            @include('datoslegajo.forms.formCompensaciones')
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
                            @include('datoslegajo.forms.formReconocimientos')
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
                            @include('datoslegajo.forms.formSanciones')
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

                            @include('datoslegajo.forms.formVacaciones')
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
@include('Personal.modalNuevoTipo')
@stop
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="{{asset('js/personal.js')}}"></script>
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
