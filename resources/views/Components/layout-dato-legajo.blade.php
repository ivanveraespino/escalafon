<style>

#estado {
    width: auto; /* Ajusta el tamaño del select */
    min-width: 120px; /* Tamaño mínimo para que no sea demasiado pequeño */
}
.align-left {
    text-align: left;
}

</style>
<div class="container card shadow">
    <div class="row justify-content-center">
        <!-- Título -->
        <div class="d-flex justify-content-between align-items-center pb-3 pt-3">
            <h5 id="layout-title" class="m-0 font-weight-bold text-munilc" id="titleg">{{ $title }}</h5>
            <!-- Botón de mostrar/ocultar -->
            <button id="toggleFormButton" class="btn btn-success">Ingresar Nuevo <i class="fa fa-plus-circle" aria-hidden="true"></i>
            </button>
        </div>
        <!-- Contenido dinámico -->
        <div>
            <div id="contpersonal" class="p-3 pestaña">
                <div class="d-flex h-100 align-items-center gap-1">
                    <label for="dnipcv" class="m-0 fw-bold text-white">Personal: </label>
                    <select name="personal_id" id="dnipcv" class="form-control" required>
                    </select>
                </div>
            </div>

            <div id="formContainer" class="box" style="display: none;"> <!-- Oculto por defecto -->
                <!-- Aquí se incluirá contenido dinámico -->
                {{ $slot }}
            </div>
        </div>

        <!-- Tabla dinámica -->
        <div>
            <div class="box p-3">
                <table class="table table-bordered table-striped" id="dataTable">
                    <thead>
                        <tr>
                            @foreach ($columns as $column)
                                <th>{{ $column }}</th>
                            @endforeach
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
