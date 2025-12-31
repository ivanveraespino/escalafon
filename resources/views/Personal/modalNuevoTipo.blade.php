<div class="modal fade" id="nuevoTipoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="nuevoTipoForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Agregar Nuevo Tipo de Personal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="nombre" id="nombreTipoNuevo" class="form-control" placeholder="Nombre del tipo" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="nuevaViaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="nuevaViaForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Agregar Vía</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="nombre-nueva-via" id="nombre-nueva-via" class="form-control" placeholder="Nombre del nuevo tipo de vía" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="nuevoCargoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="nuevoCargoForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Agregar Área</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="nombre-nuevo-cargo" id="nombre-nuevo-cargo" class="form-control" placeholder="Nombre del nuevo cargo" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="nuevoRegimenModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="nuevoRegimenForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Agregar Régimen</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="nombre-regimen" id="nombre-regimen" class="form-control" placeholder="Ej.: D. L. 0000" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="nuevoCondicionLabModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="nuevoCondicionLabForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Agregar Condición Laboral</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="form-floating">
            <input type="text" name="nombre-condicion" id="nombre-condicion" class="form-control" required>
            <label for="nombre-condicion">Condicion Laboral</label>
          </div>
          <textarea name="descripcion-condicion" id="descripcion-condicion" class="form-control" rows="2" required></textarea>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="nuevoRegimenPensionarioModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="nuevoRegimenPensionarioForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Agregar Régimen Pensionario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="form-floating">
            <input type="text" name="nombre-regimen-pen" id="nombre-regimen-pen" class="form-control" required>
            <label for="nombre-condicion">Nombre Régimen</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="nuevoCompensacionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="nuevoCompensacionForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Agregar Tipo Compensación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="form-floating">
            <input type="text" name="nombre-nuevo-compensacion" id="nombre-nuevo-compensacion" class="form-control" required>
            <label for="nombre-nuevo-compensacion">Nombre Compensación</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>