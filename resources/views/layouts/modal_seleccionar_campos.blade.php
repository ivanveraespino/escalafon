
<!-- Modal para seleccionar personal y campos del informe -->
<form id="formSeleccionarPersonalCampos" action="/generate-word" method="GET">
    <div class="modal fade" id="modalSeleccionarPersonalCampos" tabindex="-1" role="dialog" aria-labelledby="modalSeleccionarPersonalCamposLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSeleccionarPersonalCamposLabel">Seleccionar Personal y Campos del Informe</h5>
                    <x-boton-salir type="danger" text="Salir" />
                </div>
                <div class="modal-body">
                    <div class="row">
                    
                            <div class="col-12 mb-3">
                        
                                        <label for="personal_id">Personal</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Buscar empleado" id="dnip_informe" name="personal_id">
                                            <div class="input-group-append">
                                                <div class="btn btn-primary"><i class="fas fa-search"></i></div>
                                            </div>
                                        </div>
                                        <div id="suggestionsinf"></div>
                        
                                <div id="selected-employees" class="mt-2"></div>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="personal_id">Tipo de informe</label>
                                <br>
                                  <button type="button" class="btn btn-secondary btn-sm" onclick="seleccionarGeneral([0, 2, 3, 10,11,12])">Informe General</button>
                                  <button type="button" class="btn btn-secondary btn-sm" onclick="seleccionarTodos()">Seleccionar Todos</button>
                                  <button type="button" class="btn btn-secondary btn-sm" onclick="deseleccionarTodos()">Deseleccionar Todos</button>
                                  <br>
                                  <br>
                            </div>

                            <div class="col-12 mb-3" style="max-height: 200px; overflow-y: auto;">
                                <label for="personal_id">Campos</label>
                                @include('employees.informe_form')
                            </div>
                    
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="generarInforme2(1)">Generar Informe con Archivos</button> 
                    <button type="button" class="btn btn-primary" onclick="generarInforme2(0)">Generar Informe en PDF</button>
                    <button type="button" class="btn btn-primary" onclick="generarInformeEnWord()">Generar Informe en Word</button>
                    <a href="{{ route('generate.word') }}" class="btn btn-primary">Generar Informe en Word</a>
                </div>
            </div>
        </div>
    </div>
</form>

