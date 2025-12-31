<form method="POST" action="guardarJustificacion" id="formJustificacion">
    @csrf
    <div class="row">
        <div class="col-12">
            <label for="dni">Empleado</label>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="DNI" id="dni">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button" id="buscarEmpleado"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div id="nombres_apellidos"></br></div>
            <input type="hidden" id="id_employee" name="id_employee"/>    
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <label for="fecha">Fecha</label>
            <input type="date" class="form-control" placeholder="Fecha" id="fecha" name="fecha" max="{{date('Y-m-d')}}">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-6">
            <label for="desde">Hora Inicio</label>
            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio">
        </div>
        <div class="col-6">
            <label for="desde">Hora Final</label>
            <input type="time" class="form-control" id="hora_final" name="hora_final">
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="justificacion">Justificación</label>
                <textarea type="text" class="form-control" id="justificacion" name="justificacion" placeholder="e.g. Comisión de Servicio"></textarea>
            </div>
        </div>
    </div>
</form>