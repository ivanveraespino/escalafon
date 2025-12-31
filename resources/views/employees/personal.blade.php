@extends('layouts.app')

@section('content')
<style>
.nav-pills {
  margin-left: -15px;
}

.nav-link {
  background-color: transparent;
  border-radius: 0;
  font-size: 0.9rem;
}

.nav-link:hover {
  text-decoration: underline;
  color: var(--bs-danger);
}

.nav-link.active {
  background-color: transparent;
  color: var(--bs-danger) !important;
  font-weight: bold;
}

.nav-link.active::after {
  content: "";
  position: absolute;
  right: -10px;
  top: 0;
  bottom: 0;
  border-left: 4px solid var(--bs-danger);
  border-radius: 0 5px 5px 0;
}
.card-body img {
  border: 2px solid #007bff; /* Bordes opcionales para la foto */
}

/* Estilo por defecto para campos */
.required-field {
    border: 2px solid #ddd; /* Color por defecto cuando no está habilitado */
    border-radius: 4px;
    transition: border-color 0.3s ease; /* Para la transición del color del borde */
}

.required-field.enabled {
    border-color: red; /* Color rojo cuando habilitado */
}

.form-floating{
    flex: 1;
}

.form-floating label{
    background-color: transparent;
}


.form-floating .dynamic-label {
    line-height: 1.5; /* Alineación vertical del texto */
    background-color: none;
    white-space: normal; 
    overflow: hidden;    /* Oculta el texto desbordado */
    display: block;      /* Asegura que el label ocupe su propia línea */
    font-size: 0.9rem; /* Tamaño del texto más pequeño para cuando el input está vacío */
    transition: font-size 0.3s ease; /* Para la transición de tamaño de fuente */
}

.form-floating input::placeholder {
    color: #6c757d; /* Color más tenue para el placeholder */
    font-size: 0.75rem; /* Tamaño de la fuente del placeholder */
    transition: font-size 0.3s ease; /* Para la transición de tamaño de fuente */
}

.form-floating input:focus + .dynamic-label,
.form-floating input:not(:placeholder-shown) + .dynamic-label {
    font-size: 0.75rem; /* Tamaño de fuente más pequeño cuando el input no está vacío */
    word-wrap: break-word; /* Activado solo cuando hay texto */
}

#loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000;
}

.none {
    display: none;
}
</style>

<div class="w-100">        
        @csrf
        <div class="d-flex flex-column gap-4">
        <!-- Foto de Perfil -->
            <div class="flex-1 d-flex flex-column"  > 

                <div class="card shadow" style="display: none;" id="personal_card_info">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-munilc">Datos del Personal</h6>
                    </div>
                    <div class="card-body text-center d-flex flex-row gap-3">
                        <div class="flex-1 d-flex flex-column">
                            <img id="profileImage" class="card-img-top profile-image" style="height: 150px; width: auto;" alt="La imagen no esta disponible" src="{{ $base64Image ?? asset('img/perfil.png') }}">   
                        </div>

                        <div class="flex-2 d-flex gap-3 w-100">
                            <div class="form-floating">
                                <input type="text" class="form-control" value="{{ $dp->Nombres ?? '' }} {{ $dp->Apaterno ?? '' }} {{ $dp->Amaterno ?? '' }}" readonly>
                                <label for="Pnro_documento_id" class="dynamic-label -field-l">NOMBRE COMPLETO</label>
                            </div>                              

                            <div class="form-floating">
                                <input type="text" class="form-control" value="{{ $dp->id_identificacion ?? '' }} - {{ $dp->nro_documento_id ?? '' }}" readonly>
                                <label for="Pnro_documento_id" class="dynamic-label -field-l">DOCUMENTO DE IDENTIDAD</label>
                            </div>   

                            <div class="form-floating">
                                <input type="text" class="form-control" value="{{ $ult_vin->nombre_regimen ?? '' }}"  readonly>
                                <label for="Pnro_documento_id" class="dynamic-label -field-l">REGIMEN</label>
                            </div>   

                            <div class="form-floating">
                                <input type="text" class="form-control" value="{{ $dp->nombre_tipo_personal ?? '' }}" readonly>
                                <label for="Pnro_documento_id" class="dynamic-label -field-l">TIPO DE PERSONAL</label>
                            </div>   

                            <div class="form-floating">
                                <input type="text" class="form-control" value="{{ $ult_vin->nombre_condicion ?? '' }}" readonly>
                                <label for="Pnro_documento_id" class="dynamic-label -field-l">CONDICION LABORAL</label>
                            </div>   
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <select id="section_select" class="form-select pestaña w-auto" style="color: white; font-size: 18px; font-weight: bold;"  onchange="loadSectionFromSelect(this)"></select>
                <div id="dynamic-content"></div>
            </div>
            
            <div id="loader" style="display: none; text-align: center; padding: 20px;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>          
        </div>

</div> 

@stop
@push('scripts')

<script>

const personalId = {!! json_encode($personal_id ?? null) !!};

const sections = [
  { name: "Datos Personales", route: `/legajo/datosPersonales/${personalId}` },  
  { name: "Familiares / Derecho Habiente", route: `/legajo/familiares/${personalId}` },
  { name: "Vinculo Laboral", route: `/legajo/vinculo-laboral/${personalId}` },
  { name: "Estudios", route: `/legajo/formacion-academica/${personalId}` },
  { name: "Estudios Complementarios", route: `/legajo/estudios-complementarios/${personalId}` },
  { name: "Colegiatura", route: `/legajo/colegiatura/${personalId}` },
  { name: "Idiomas", route: `/legajo/idiomas/${personalId}` },
  { name: "Experiencia Laboral", route: `/legajo/experiencia-laboral/${personalId}` },
  { name: "Rotaciones", route: `/legajo/movimientos/${personalId}` },
  { name: "Vacaciones", route: `/legajo/vacaciones/${personalId}` },
  { name: "Licencias", route: `/legajo/licencias/${personalId}` },
  { name: "Permisos", route: `/legajo/permisos/${personalId}` },
  { name: "Compensaciones", route: `/legajo/compensaciones/${personalId}` },
  { name: "Asignaciones de Tiempo", route: `/legajo/asignacion-tiempos/${personalId}` },
  { name: "Reconocimientos", route: `/legajo/reconocimientos/${personalId}` },
  { name: "Sanciones", route: `/legajo/sanciones/${personalId}` },
];


$(document).ready(function () {
    //AGREGAR ADJUNTOS
    // Agregar las opciones del select dinámicamente
    sections.forEach(function(section) {
        $('#section_select').append(`<option value="${section.route}">${section.name}</option>`);
    });

    if (personalId) {
        $('#personal_card_info').show();
        $('#toggleEditingButton').show();
    } else {
        $('#personal_card_info').hide();
        $('#toggleEditingButton').hide();
    }
    $('#contpersonal').hide();
    //$('#section_select').val('valor_opcion');
    $('#section_select').trigger('change');
});

// Función para cargar la sección seleccionada por el usuario
function loadSectionFromSelect(select) {
    const route = select.value;
    if (route) {
        loadSection(route);
    }
}

// Función para cargar contenido mediante AJAX
function loadSection(route) {
    const loader = document.getElementById("loader");
    const dynamicContent = document.getElementById("dynamic-content");

    // Mostrar loader y ocultar contenido dinámico
    loader.style.display = "block";
    dynamicContent.style.opacity = "0.5";

    $.ajax({
        url: route,
        method: 'GET',
        success: function(data) {
            // Reemplazar contenido y ocultar loader
            $('#dynamic-content').html(data);
            loader.style.display = "none";
            dynamicContent.style.opacity = "1";

            // Inicializar el toggle después de cargar el contenido
            initializeToggle();
            $('#contpersonal').hide();
            $('#layout-title').html("");
            
        },
        error: function(error) {
            alert('Hubo un error al cargar los datos');
            loader.style.display = "none"; 
            dynamicContent.style.opacity = "1";
        }
    });
}

function initializeToggle() {
    const toggleButton = document.getElementById("toggleFormButton");
    const formContainer = document.getElementById("formContainer");

    if (toggleButton && formContainer) {
        toggleButton.addEventListener("click", function () {
            console.log("Botón toggle clickeado");
            if (formContainer.classList.contains("show")) {
                formContainer.classList.remove("show");
                setTimeout(() => {
                    formContainer.style.display = "none";
                }, 300);
            } else {
                formContainer.style.display = "block";
                setTimeout(() => {
                    formContainer.classList.add("show");
                }, 8);
            }
        });
    }
}




// Seleccionar el contenedor de enlaces

//ENLACES OTROS CAMPOS
/*
if(personalId){
    sections.forEach(section => {
        const listItem = document.createElement("li");
        listItem.classList.add("nav-item", "mb-2");

        const link = document.createElement("a");
        link.classList.add("nav-link", "text-primary", "fw-semibold");
        link.href = section.route; // Ruta
        link.textContent = section.name; // Nombre de la sección

        listItem.appendChild(link);
        linksContainer.appendChild(listItem);
    });
}*/

//CRUD

const tiposDocs = @json($tipo_docs);

function guardarp() {
    if ($('#PApaterno').val() == "" || $('#PAmaterno').val() == "" || $('#PNombres').val() == "" || $('#Pnro_documento_id').val() == "" || $('#Pid_identificacion').val() == "") {
        Swal.fire({
            icon: 'error',
            title: '¡Debe llenar los campos requeridos!',
        });
    } else {
        var formulario = $('#Personal')[0]; 
        var formulario2 = $('#formdomicilio')[0];
        var formData = new FormData(formulario); // Añadir todos los campos de 'Personal' automáticamente
        var elementsForm2 = formulario2.elements; // Obtener los elementos del formulario 'formdomicilio'
    
        for (var i = 0; i < elementsForm2.length; i++) {
            var element = elementsForm2[i];
            if (element.name && element.value) {
                // Si es 'afiliacion_salud', convierte a cadena antes de añadir
                if (element.name === 'afiliacion_salud[]') {
                    var afiliacionSaludArray = $('input[name="afiliacion_salud[]"]:checked').map(function () {
                        return this.value;
                    }).get();
                    formData.append('afiliacion_salud', afiliacionSaludArray.join(','));
                } else {
                    formData.append(element.name, element.value);
                }
            }
        }
        console.log(formulario);
        $.ajax({
            url: "{{ route('guardarPersonal') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Personal Registrado!',
                    text: 'Los datos se han guardado correctamente.',
                    timer: 1000,
                });
                //Habilitar moal campos
                $('#ModalCampos .btn-block').data('id', response.id);
                $('#ModalCampos .btn-block').data('idr', response.idr);
                $('#ModalCampos .btn-block').prop('disabled', false);
                $('#dnip_out').val(response.nombre_completo);
                $('#modal_personal').modal('hide');
                $('#ModalDomi').modal('hide');
                //actualizardp(response.id,false);
                window.location.href = `{{ route('datos_personal', ':id') }}`.replace(':id', response.id);
                $(':input','#formJustificacion')
                    .not(':button, :submit, :reset')
                    .val('');
                $('#message').html('');
            },
            
            error: function(error) {
                if (error.responseJSON && error.responseJSON.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.responseJSON.error,
                    });
                } 
            }
        });
    }
}

//FUNCIONES CARGA DATOS PERSONALES


//CAPTURA DE VALORES DE FORM PERSONAL A CAMPOS
function ficha_p() {
    var id = $('#ModalCampos .btn-block').data('id');
    actualizardp(id, false);
}

//MOSTRAR IMAGEN
function fetchImageData(id) {
    return fetch(`/api/obtenerImagen/${id}`)
        .then(response => response.blob()) // Obtener la respuesta como un blob
        .then(blob => {
            const reader = new FileReader();
            reader.readAsDataURL(blob);
            return new Promise((resolve, reject) => {
                reader.onloadend = () => resolve(reader.result);
                reader.onerror = reject;
            });
        });
}

//ARCHIVOS ADJUNTOS
// Función para cargar archivos asociados al `id_personal_seleccionado`
function cargarArchivosAdjuntos() {
    const container = document.getElementById('dynamicFileInputs');
    if (!id_personal_seleccionado) {
        console.error("No se ha seleccionado ningún personal.");
        return;
    }
    // Realizar una solicitud al servidor para obtener los archivos adjuntos
    fetch(`/archivos-adjuntos/${id_personal_seleccionado}`)
        .then(response => {
            console.log("Respuesta del servidor recibida:", response);
            if (!response.ok) {
                throw new Error(`Error en la respuesta: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Limpiar el contenedor antes de actualizar
            container.innerHTML = '';
            tiposDocs.forEach(tipo => {
                // Crear un contenedor para cada archivo
                const fileGroup = document.createElement('div');
                const archivoExistente = data.find(archivo => parseInt(archivo.idtd) === parseInt(tipo.id));
                fileGroup.classList.add('mb-3'); 
                fileGroup.classList.add('mt-1'); 
                const label = document.createElement('label');
                label.classList.add('form-label'); 
                label.innerText = tipo.nombre; 
                const inputFile = document.createElement('input');
                inputFile.type = "file";
                inputFile.classList.add('form-control'); // Input de archivo con estilo
                inputFile.name = `archivos[${tipo.id}]`;
                inputFile.accept = "application/pdf";
            
                // Crear el botón de "ver archivo" si existe archivo
                const botonVerArchivo = archivoExistente
                    ? `<button type="button" class="btn btn-primary btn-sm ver-pdf ml-2" data-pdf="${archivoExistente.archivo}">
                           <i class="fas fa-file-pdf"></i>
                       </button>`
                    : '';
            
                // Crear la fila con los elementos (input y boton)
                const row = document.createElement('div');
                row.classList.add('d-flex', 'align-items-center'); // Fila flex con alineación centrada
            
                // Crear contenedores para los elementos (input y botón)
                const inputContainer = document.createElement('div');
                inputContainer.classList.add('flex-fill', 'me-2'); // Toma la mitad del espacio disponible
            
                const botonContainer = document.createElement('div');
                botonContainer.classList.add('d-flex', 'justify-content-start'); // Botón alineado a la izquierda
            
                // Añadir el input y el botón a sus contenedores
                inputContainer.appendChild(inputFile);
                if (botonVerArchivo) {
                    botonContainer.innerHTML = botonVerArchivo; // Solo si existe el archivo
                }
            
                // Añadir los contenedores a la fila
                row.appendChild(inputContainer);
                row.appendChild(botonContainer);
            
                // Añadir label y fila al contenedor de archivo
                fileGroup.appendChild(label);
                fileGroup.appendChild(row);
            
                // Añadir el grupo al contenedor principal
                container.appendChild(fileGroup);
            });

        })
        .catch(error => {
            console.error('Error al cargar archivos adjuntos:', error);
        });
}

//ACTUALIZAR INFORMACIONP
var conid;
function actualizardp(id,enable = true) {
    id_personal_seleccionado = id;
    cargarArchivosAdjuntos();
    enableEditing(enable); 
    const chipsContainer = document.getElementById('chips-container');
    chipsContainer.innerHTML = ''; 
    $('#guardar_empleado').attr('onclick', 'guardarActualizacion_p()');
    $('#guardar_empleado_d').attr('onclick', 'guardarActualizacion_p()');
    var formtra = $('#Personal')[0]; 
    formtra.reset();
    conid = id;
$.ajax({
    url: '{{ route("mostrardp", ":id") }}'.replace(':id', id),
    type: 'GET',
    success: function(data) {
    $.each(data, function(key, value) {
        //ADAPTARLO FORMATO SQL SERVER
        $('#P' + key).val(value);

        if (key === 'FechaNacimiento') {
            value = value ? new Date(value).toISOString().split('T')[0] : '';
        } 
        if (key === 'afiliacion_salud' && value) {
            var afiliaciones = JSON.parse(value); 
            mostrarChipsDeAfiliaciones(afiliaciones);
        }
        $('#btncrear_usuarios').prop('disabled',false);
        //$('#modal_personal').modal('show');
    });
    $('#copiadni').empty();
    if (data.archivo) {
            var pdfButton = '<button type="button" class="btn btn-primary btn-sm ver-pdf" data-pdf="' + data.archivo + '">Ver Documento</button>';
            $('#copiadni').append(pdfButton);
        }

},

error: function(xhr) {
    console.error(xhr.responseText);
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un problema al cargar los datos.'
    });
}
});
}

//EVALUAR

//CARGA MODAL INGRESO DATOS
function campos_personal_nuevo(event) {
    event.preventDefault();
    var apaterno = $('#PApaterno').val();
    var amaterno = $('#PAmaterno').val();
    var nombres = $('#PNombres').val();
    var personal = apaterno + ' ' + amaterno + ' '+ nombres;
    var id_regimen = $('#Pid_regimen_modalidad option:selected').text().toString().trim(); //OBTENER CONDICION LABORAL   
    var pdni = $('#Pid_personal').val();
    console.log("ID del Régimen:", pdni);    

    //control abrir cerrar modal
    var ctrl = "true";

    campos(pdni, personal, id_regimen,ctrl);
    $('#modal_personal').modal('hide');
//$('#ModalCampos').modal('show');
}

//GUARDAR ACTUALIZACION
function guardarActualizacion_p() {
    var formulario = $('#Personal')[0]; 
    var formulario2 = $('#formdomicilio')[0];
    var formData = new FormData(formulario); 
    var elementsForm2 = formulario2.elements; 
    for (var i = 0; i < elementsForm2.length; i++) {
        var element = elementsForm2[i];
        if (element.name && element.value) {
            formData.append(element.name, element.value);
        }
    }
    $.ajax({
        url: '{{ route("editarPersonal", ":conid") }}'.replace(':conid', conid),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            Swal.fire({
                icon: 'success',
                title: 'Campo actualizado!',
                text: 'El campo se ha actualizado correctamente.',
                timer: 1000,
            });
            cargarArchivosAdjuntos();

                //$('#modal_personal').modal('hide');
                $('#ModalDomi').modal('hide'); 
                $(':input','#formJustificacion')
                .not(':button, :submit, :reset')
                .val('');
                $('#message').html('');              

        },
        error: function(xhr) {
            console.error(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al actualizar los datos del trabajador.'
            });
        }
    });
}


// FUNCION CONTROL EDICION
function enableEditing(enable) {
    document.querySelectorAll('form#Personal input, form#Personal select, form#Personal button[type="submit"], form#formdomicilio input, form#formdomicilio select, form#formdomicilio button[type="submit"]').forEach(element => {
        element.disabled = !enable;
    });

    document.getElementById('guardar_empleado').style.display = enable ? 'block' : 'none';
    
    const labelIds = document.querySelectorAll('.required-field-l');
    
    labelIds.forEach(label => {
        if (enable) {
                if (!label.textContent.includes('(*)')) {
                    label.textContent += ' (*)';
                    
                }
                label.style.color = 'red';
            } else {
                label.textContent = label.textContent.replace(' (*)', '');
                label.style.color = '';
            }
    });
    const actfotoDiv = document.getElementById('actfoto');
    const labelpersonal = document.getElementById('modal_personalLabel');
    if (labelpersonal) {
        labelpersonal.textContent = enable ? 'Editar Datos Personales' : 'Datos Personales';
    }
    if (actfotoDiv) {
        actfotoDiv.style.display = enable ? 'block' : 'none';
    }
    const camposavi = document.getElementById('camposavi');
    if (camposavi) {
        camposavi.style.display = enable ? 'block' : 'none';
    }
    const afiliacion = document.getElementById('afiliacion');
    if (camposavi) {
        afiliacion.style.display = enable ? 'block' : 'none';
    }
    
    const icon = document.getElementById('toggleIcon');
    if (icon) {
        afiliacion.style.display = enable ? 'block' : 'none';
        if (enable) {
            icon.classList.remove('fa-pencil-alt');
            icon.classList.add('fa-eye');
        } else {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-pencil-alt');

        }
    }


    const elements = document.querySelectorAll('.required-field');
    
    elements.forEach(element => {
        if (enable) {
            element.classList.add('enabled'); // Aplica la clase con borde rojo
        } else {
            element.classList.remove('enabled'); // Quita la clase, devolviendo el borde por defecto
        }
    });

}
function toggleEditing() {
    const icon = document.getElementById('toggleIcon');
    const button = document.getElementById('toggleEditingButton');

    if (!icon || !button) {
        console.error('Elementos no encontrados en el DOM.');
        return;
    }

    const isEditing = icon.classList.contains('fa-edit');

    // Cambiar las clases del ícono
    if (isEditing) {
        icon.classList.remove('fa-edit');
        icon.classList.add('fa-eye');
        button.lastChild.textContent = ' Ver'; // Actualiza solo el texto
    } else {
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-edit');
        button.lastChild.textContent = ' Editar'; // Actualiza solo el texto
    }

    enableEditing(isEditing);
}


// Mostrar chips de afiliaciones
function mostrarChipsDeAfiliaciones(afiliaciones) {
    const chipsContainer = document.getElementById('chips-container');
    chipsContainer.innerHTML = ''; // Limpiar contenedor de chips

    afiliaciones.forEach(function(afiliacion) {
        const chip = document.createElement('div');
        chip.className = 'chip';
        chip.textContent = afiliacion;
        chipsContainer.appendChild(chip);
    });
}
function actualizarChips() {
    const chipsContainer = document.getElementById('chips-container');
    chipsContainer.innerHTML = ''; // Limpiar contenedor de chips

    document.querySelectorAll('input[name="afiliacion_salud[]"]:checked').forEach(checkbox => {
        const chip = document.createElement('div');
        chip.className = 'chip';
        chip.textContent = checkbox.value;
        chipsContainer.appendChild(chip);
    });
}




///CARGA  FOTO DE PERFIL
function previewImage() {
    const file = document.getElementById('archivo_prev').files[0];
        const img = new Image();
        img.src = URL.createObjectURL(file);

        img.onload = function() {
                const reader = new FileReader();
                reader.onloadend = function() {
                    document.getElementById('profileImage_prev').src = reader.result;
                }
                reader.readAsDataURL(file);
            
        }
    
}

/*
$('#Piddep').change(function() {
    const initialDepartamentoId = $('#Piddep').data('initial-value');
    var departamentoId = $(this).val();
    $.ajax({
        url: 'obtener_provincias_por_departamento/', 
        type: "GET",
        data: {
            departamento_id: departamentoId
        },
        success: function(data) {
            $('#Pidpro').empty();
            $('#Pidpro').append('<option value=""></option>');

            $.each(data, function(index, provincia) {
                $('#Pidpro').append('<option value="' + provincia.id + '">' +
                    provincia.nombre + '</option>');
            });

            $('#Pidpro').prop('disabled', false);
            $('#Piddis').prop('disabled', true);
        }
    });
});
$('#Pidpro').change(function() {
    var provinciaId = $(this).val();
    $.ajax({
        url: 'obtener_distritos_por_provincia/', 
        type: "GET",
        data: {
            provincia_id: provinciaId
        },
        success: function(data) {
            $('#Piddis').empty();
            $('#Piddis').append('<option value=""></option>');

            $.each(data, function(index, distrito) {
                $('#Piddis').append('<option value="' + distrito.id + '">' +
                    distrito.nombre + '</option>');
            });

            $('#Piddis').prop('disabled', false);
        }
    });
});*/


</script>


@endpush

