<form id="formAdd"  class="d-flex flex-column" enctype="multipart/form-data">
    <input type="hidden" class="form-control" id="personal_id" name="personal_id" />
    @csrf
        <div id="contrato" class="tab-pane fade show active" role="tabpanel">
            @include('datoslegajo.forms.vinculos.formContrato')
        </div>
    <button type="submit" id="btnfam" class="btn btn-success ml-auto mt-4">Guardar</button>
</form>

