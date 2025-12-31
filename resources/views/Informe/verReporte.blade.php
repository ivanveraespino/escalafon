<!-- Modal para seleccionar personal y campos del informe -->
<div class="card">
    <div class="card-header">
        <h6 class="font-weight-bold text-munilc">GENERAR INFORME DE PERSONAL</h6>
    </div>
    <div class="card-body">
        <form id="formSeleccionarPersonalCampos" action="/generate-word" method="GET">
            @csrf

            <div class="col-12 mb-3">

                <label for="personal_id">Personal</label>
                <div class="input-group mb-3">
                    <input class="form-control" list="datalistOptions" id="personal" placeholder="Escriba nombre o DNI">
                    <datalist id="datalistOptions">
                        @forelse($personal as $persona)
                        <option value="{{$persona->nro_documento_id}} {{$persona->nombre}}" data-id="{{$persona->id_personal}}">
                            @empty
                        <option value="No hay registros disponibles">
                            @endforelse
                    </datalist>
                </div>

                <!-- Contenedor de los badges -->
                <div id="badgeContainer"></div>
                <input type="hidden" name="selected_ids" id="selectedIds">
            </div>

            <label>Seleccione los datos a incluir en el informe:</label>
            <div class="row pl-4 pr-4">
                <!-- Primera columna -->
                <div class="col-md-6">
                    @foreach([
                    'Datos Personales', 'Datos Familiares', 'Periodos Laborados',
                    'Estudios', 'Estudios Complementarios', 'Idiomas',
                    'Experiencia Laboral'
                    ] as $index => $dato)
                    <div>
                        <input class="form-check-input" type="checkbox" name="datos[]" value="{{ $dato }}" id="dato{{ $index + 1 }}">
                        <label class="form-check-label" for="dato{{ $index + 1 }}">{{ $index + 1 }}. {{ $dato }}</label>
                    </div>
                    @endforeach
                </div>
                <!-- Línea divisoria -->
                <div class="col-md-1 d-flex justify-content-center align-items-center">
                    <div style="border-left: 1px solid #adb5bd; height: 100%;"></div>
                </div>

                <!-- Segunda columna -->
                <div class="col-md-5">
                    @foreach([
                    'Vinculos','Rotaciones', 'Encargaturas', 'Licencias', 'Permisos',
                    'Compensaciones', 'Reconocimientos', 'Sanciones', 'Vacaciones'
                    ] as $index => $dato)
                    <div>
                        <input class="form-check-input" type="checkbox" name="datos[]" value="{{ $dato }}" id="dato{{ $index + 8 }}">
                        <label class="form-check-label" for="dato{{ $index + 8 }}">{{ $index + 8 }}. {{ $dato }}</label>
                    </div>
                    @endforeach
                </div>

                <hr>

            </div>
            <div class="text-center pb-1">
                <a href="#" id="generatePdf" class="btn btn-primary" style="display: none;">Generar PDF</a>
                <a href="#" id="descargarArchivos" class="btn btn-success">Descargar Archivos</a>
                <a href="#" id="downloadWord" class="btn btn-success">Generar WORD</a>
            </div>
            <div class="card">
                <div class="card-header ">
                    Otros Informes
                </div>
                <div class="card-body pt-0 ">
                    <ol class="list-group list-group-flush list-group-numbered">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">
                                    Trabajador que cumple 70 años
                                </div>
                                Cumple en el mes en curso
                            </div>
                            <h5><span class="badge text bg primary rounded-pill"><a href="#" id="informe70" class="text-munilc">Descargar</a></span></h5>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">
                                    Trabajador que cumple 30 años
                                </div>
                                Cumple en el mes en curso
                            </div>
                            <h5><span class="badge text bg primary rounded-pill"><a href="#" id="informe30" class="text-munilc">Descargar</a></span></h5>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">
                                    Trabajador que cumple 25 años
                                </div>
                                Cumple en el mes en curso
                            </div>
                            <h5><span class="badge text bg primary rounded-pill"><a href="#" id="informe25" class="text-munilc">Descargar</a></span></h5>
                        </li>
                    </ol>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="{{asset('js/informes.js')}}"></script>
<script>
    document.getElementById("generatePdf").addEventListener("click", function(event) {
        let personal = document.getElementById('selectedIds').value;

        let selectedData = [];
        document.querySelectorAll("input[name='datos[]']:checked").forEach((checkbox) => {
            selectedData.push(checkbox.id.match(/\d+/));
        });

        if (personal != '' && selectedData.length > 0) {
            fetch('/generar-informe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        personal: personal,
                        items: selectedData
                    })
                })
                .then(response => response.blob()) // Convertir respuesta en archivo Blob
                .then(blob => {
                    if (blob.size === 0) {
                        alert("El archivo está vacío o corrupto.");
                        return;
                    }

                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');
                    a.href = url;
                    a.download = 'documento_ejemplo.docx';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                })
                .catch(error => console.error('Error:', error));
        } else {
            alert('Ingrese datos correctamente!');
        }
    });

    document.getElementById("downloadWord").addEventListener("click", function(event) {
        let selectedIds = document.getElementById("selectedIds").value;
        let selectedData = [];
        document.querySelectorAll("input[name='datos[]']:checked").forEach((checkbox) => {
            selectedData.push(checkbox.id.match(/\d+/));
        });
        // Verificar que haya valores antes de generar la URL
        if (selectedIds !== "" && selectedData.length>0) {
            // Generar la URL dinámica
            let url = `/descargar-word?personal=${encodeURIComponent(selectedIds)}&datos=${encodeURIComponent(selectedData)}`;
            // Redirigir al usuario
            window.location.href = url;

        } else {
            alert("No hay datos seleccionados.");
            //return;
        }

    });

    document.getElementById("informe70").addEventListener("click", function(event) {
        let selectedIds = document.getElementById("selectedIds").value;
       
        // Verificar que haya valores antes de generar la URL
        if (selectedIds !== "" ) {
            // Generar la URL dinámica
            let url = `/descargar-informe70?personal=${encodeURIComponent(selectedIds)}`;
            // Redirigir al usuario
            window.location.href = url;

        } else {
            alert("No hay personal seleccionado.");
            //return;
        }

    });

    document.getElementById("informe30").addEventListener("click", function(event) {
        let selectedIds = document.getElementById("selectedIds").value;
       
        // Verificar que haya valores antes de generar la URL
        if (selectedIds !== "" ) {
            // Generar la URL dinámica
            let url = `/descargar-informe30?personal=${encodeURIComponent(selectedIds)}`;
            // Redirigir al usuario
            window.location.href = url;

        } else {
            alert("No hay personal seleccionado.");
            //return;
        }

    });

    document.getElementById("informe25").addEventListener("click", function(event) {
        let selectedIds = document.getElementById("selectedIds").value;
       
        // Verificar que haya valores antes de generar la URL
        if (selectedIds !== "" ) {
            // Generar la URL dinámica
            let url = `/descargar-word?personal=${encodeURIComponent(selectedIds)}`;
            // Redirigir al usuario
            window.location.href = url;

        } else {
            alert("No hay personal seleccionado.");
            //return;
        }

    });

    document.getElementById("descargarArchivos").addEventListener("click", function(event) {
        let selectedIds = document.getElementById("selectedIds").value;
        let selectedData = [];
        document.querySelectorAll("input[name='datos[]']:checked").forEach((checkbox) => {
            selectedData.push(checkbox.id.match(/\d+/));
        });
        // Verificar que haya valores antes de generar la URL
        if (selectedIds !== "" && selectedData.length>0) {
            // Generar la URL dinámica
            //let url = `/descargar-archivos?personal=${encodeURIComponent(selectedIds)}&datos=${encodeURIComponent(selectedData)}`;
            // Redirigir al usuario
            //window.location.href = url;
            let url = `/descargar-archivos?personal=${encodeURIComponent(selectedIds)}&datos=${encodeURIComponent(selectedData)}`;

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(error => {
                            throw new Error(error.error || 'Error desconocido');
                        });
                    }
                    return response.blob(); // ZIP file
                })
                .then(blob => {
                    // Crear enlace de descarga
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'archivos.zip';
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                })
                .catch(error => {
                    alert(error.message); // Mostrar el mensaje de Laravel
                });


        } else {
            alert("No hay datos seleccionados.");
            //return;
        }

    });
</script>