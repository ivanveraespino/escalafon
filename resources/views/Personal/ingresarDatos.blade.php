<html lang="es">

<head>
    <title>MPLC009 - Escalafón</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap5.min.css') }}">
    <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">

    <style>
        .boton-flotante {
            width: 80px;
            position: fixed;
            bottom: 40px;
            right: 30px;
            z-index: 999;
        }
    </style>
    <script src="{{ asset('js/jquery-3.6.0.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{asset('js/masivo.js')}}"></script>
</head>

<body style="background-color: #f4fff7;">
    <div class="container text-center">
        <form id="personalNuevo" action="{{ route('guardarIngresoMasivo') }}" method="POST"
            enctype="multipart/form-data" class="row">
            @csrf
            <h2 class="text-center text-success">
                Datos Personales
            </h2>

            <input type="hidden" class="form-control" value="FOTO PERFIL" name="nombres">
            <div class="col-6 col-lg-2 text-center">
                <div class="flex-1 d-flex flex-column">
                    <!-- Imagen de perfil -->
                    <img id="profileImage-prev" class="card-img-top profile-image" style="height: 150px; width: 180px;"
                        alt="La imagen no está disponible" src="{{ asset('img/perfil.png') }}">

                    <!-- Botón para cargar imagen -->
                    <button type="button" class="btn btn-success btn-sm" style="width: 180px;"
                        onclick="document.getElementById('archivo-prev').click()">
                        <i class="fas fa-upload"></i> Actualizar foto
                    </button>
                    <!-- Input oculto para subir archivo -->
                    <input type="file" accept="image/jpeg" id="archivo-prev" class="form-control" name="archivo-prev"
                        style="display: none;" onchange="previewImage()">

                    <!-- Input hidden para guardar la URL base64 -->
                    <input type="hidden" id="perfil-base64" name="perfil-base64">
                    <div id="mensaje"></div>
                </div>
            </div>

            <div class="col-10 col-lg-3">
                <div class="form-floating pb-1">
                    <select class="form-select required-field" id="doc-identificacion" name="doc-identificacion"
                        required>
                        <option value="" selected disabled></option>
                        <option value="DNI">DNI</option>
                        <option value="CE">CARNET DE EXTRANJERIA</option>
                        <option value="PTP">PERMISO TEMPORAL DE TRABAJO</option>
                        <option value="S/D">SIN DOCUMENTO</option>
                    </select>
                    <label for="doc-identificacion">Tipo documento Ident. <span
                            class="required text-danger">*</span></label>
                </div>

                <div class="form-floating pb-1">
                    <input type="number" class="form-control required-field" id="nro-identificacion"
                        name="nro-identificacion">
                    <label for="nro-identificacion" class="dynamic-label required-field-l">Nº
                        Documento Identidad<span class="required text-danger">*</span></label>
                </div>

                <div class="form-floating pb-1">
                    <select id="tipo-personal" name="tipo-personal" class="form-select required-field" required>
                        <option value="" selected disabled></option>
                        @foreach($tpersonal as $tp)
                        <option value="{{ $tp->id ?? '' }}">{{ $tp->nombre }}</option>
                        @endforeach
                        <option value="0">Agregar nuevo...</option>
                    </select>
                    <label for="tipo-personal" class="required-field-l">Tipo de Personal<span
                            class="required text-danger">*</span></label>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <div class="form-floating  pb-1">
                    <input type="text" class="form-control required-field" id="apaterno" name="apaterno" required>
                    <label for="apaterno" class="required-field-l">Apellido Paterno<span
                            class="required text-danger">*</span></label>
                </div>

                <div class="form-floating pb-1">
                    <input type="text" class="form-control required-field" id="amaterno" name="amaterno" required>
                    <label for="amaterno" class="required-field-l">Apellido Materno<span
                            class="required text-danger">*</span></label>
                </div>

                <div class="form-floating pb-1">
                    <input type="text" class="form-control required-field" id="nombres" name="nombres" required>
                    <label for="nombres">Nombres<span class="required text-danger">*</span></label>
                </div>
            </div>
            <div class="col-6 col-lg-4">
                <div class="form-floating pb-1">
                    <select id="sexo" name="sexo" class="form-select" required>
                        <option value="" selected disabled></option>
                        <option value="M">MASCULINO</option>
                        <option value="F">FEMENINO</option>
                    </select>
                    <label for="Psexo">Sexo</label>
                </div>

                <div class="form-floating pb-1">
                    <input type="date" class="form-control" id="fecha-nacimiento" name="fecha-nacimiento" required>
                    <label for="fecha-nacimiento">Fecha de nacimiento</label>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="form-floating pb-1">
                    <input type="text" class="form-control" id="celular" name="celular" required>
                    <label for="PNroCelular">Nro de Celular</label>
                </div>

                <div class="form-floating pb-1">
                    <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo Electronico">
                    <label for="correo">Correo Electrónico</label>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="form-floating pb-1">
                    <select id="discapacidad" name="discapacidad" class="form-select" required>
                        <option value="" selected disabled></option>
                        <option value="NO">NO</option>
                        <option value="PARCIAL">PARCIAL</option>
                        <option value="SI">SI</option>
                    </select>
                    <label for="Pdiscapacidad">Discapacidad</label>
                </div>
                <div class="form-floating pb-1">
                    <select id="estadocivil" name="estadocivil" class="form-select" required>
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
            <hr style="margin-top: 10px;">
            @include('layouts.formDomicilio')
            <hr style="margin-top: 10px;">
            @include('datoslegajo.forms.vinculos.formVinculo')
            <button type="submit" class="btn btn-success boton-flotante"><i class="fas fa-save"> </i> Guardar
                Datos</button>
        </form>

    </div>
    @include('Personal.modalNuevoTipo')
    <script>
        document.getElementById("archivo-prev").addEventListener("change", function() {
            let archivo = this.files[0]; // Capturar el archivo seleccionado

            if (!archivo) {
                document.getElementById("mensaje").innerHTML = "⚠ No se ha seleccionado ningún archivo.";
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
                    document.getElementById("mensaje").innerHTML = data.mensaje;
                    document.getElementById("perfil-base64").value = data.name;
                })
                .catch(error => console.error("Error al subir el archivo:", error));
        });
    </script>
</body>

</html>