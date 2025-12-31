
<div class="card shadow p-3 d-flex flex-column">
    <div class="flex-1 d-flex flex-column gap-3">
    
        <div class="d-flex justify-content-between align-items-center">
            <h5 id="personal_header" class="text-center"></h5>
            <button id="toggleEditingButton" class="btn btn-primary float-right" onclick="toggleEditing()">
                <i id="toggleIcon" class="fas fa-edit"></i> Editar
            </button>
        </div>

        @include('employees.form_datos_personal')

        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        Domicilio Actual
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body d-flex gap-3">
                        @include('layouts.form_domicilio')
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="mb-3">
        @hasanyrole('ADMIN|COORDINADOR')
            <label style="color: transparent;">.</label>
            <div style="text-align: right;">
                <button type="button" class="btn btn-success" id="guardar_empleado" onclick="guardarp()">GUARDAR DATOS <i class="fas fa-save"></i></button>
            </div>
        @endhasanyrole
    </div>
</div>

<script>


    //VALIDACON INGRESO DATOS PERSONAL
    // Validar para solo permitir números
    $('#Pnro_documento_id, #PNroTelefono, #PNroCelular, #PNroRuc').on('input', function() {
        // Remover cualquier carácter no numérico
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });
    //VALIDAR EXISTENCIA DE PERSONAL
    $('#Pnro_documento_id').on('input', function() {
        var query = $(this).val();
        if (query.length > 4) {
            $.ajax({
                url: "ValidarPersonal",
                method: "GET",
                data: { query: query },
                success: function(data) {
                    if (data.existe) {
                        Swal.fire({
                            title: 'Personal ya registrado',
                            text: `El personal ${data.nombreCompleto}, ya está registrado . ¿Deseas ver su información?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Sí, mostrar',
                            cancelButtonText: 'Cancelar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                actualizardp(data.personal_id,false);
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                }
            });
        } 
    });


    $(document).ready(function () {
        archivos_adjuntos();
        const estado = {!! json_encode($estado ?? null) !!};    
            if (personalId) {
                    actualizardp(personalId);
                if (estado) {
                        $('#personal_header').html('Editar datos del Personal');
                        enableEditing(true); 
                    }else{
                        //$('#personal_header').html('Datos del Personal');
                        enableEditing(false); 
                    }
            }else{
                enableEditing(true); 
                $('#personal_header').html('Registrar datos Personal');
            }
    });

    function archivos_adjuntos(){
        const container = document.getElementById('dynamicFileInputs');
        container.innerHTML = '';
        tiposDocs.forEach(tipo => {
            // Crear un contenedor para cada archivo
            const fileGroup = document.createElement('div');
            fileGroup.classList.add('m-2');
            fileGroup.innerHTML = `
                <label>${tipo.nombre}:</label>
                <input type="file" class="form-control" name="archivos[${tipo.id}]" accept="application/pdf">
            `;
            container.appendChild(fileGroup);
        });
    }
</script>