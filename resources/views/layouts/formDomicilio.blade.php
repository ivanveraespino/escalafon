<h6 class="text-center text-success pb-0 mb-0 " style="font-size: 1.15rem;">Sobre su domicilio actual</h6>
<small class="text-center pb-0 text-secondary">La direccion ingresada será validado segun su declaración jurada.</small>
<div class="col-4 col-lg-3">
    <div class="form-floating pb-1">
        <select id="tipodom" name="tipodom" class="form-select" required>
            <option selected disbled></option>
            @foreach($dtv as $d)
            <option value="{{ $d->nombre ?? '' }}">{{ $d->nombre }}</option>
            @endforeach
            <option value="0">Agregar más...</option>
        </select>
        <label for="tipodom">Tipo vía</label>
    </div>

    <div class="form-floating pb-1">
        <input type="text" class="form-control" id="Pdactual" name="dactual"
            value="{{ $dd->dactual ?? '' }}" required>
        <label for="Pdactual">Dirección de domicilio</label>
    </div>
    <div class="form-floating pb-1">
        <input type="text" class="form-control" id="numero" name="numero"
            value="{{ $dd->numero ?? '' }}" placeholder="Número">
        <label for="numero">Número</label>
    </div>
</div>

<div class="col-6 col-lg-4">
    <div class="form-floating pb-1">
        <select id="iddep" name="iddep" class="form-select" onchange="mostrarProvincia(event)" required>
            <option disabled selected></option>
            @foreach($dep as $departamento)
            <option value="{{ $departamento->id}}">
                {{ $departamento->nombre }}
            </option>
            @endforeach
        </select>
        <label for="iddep">Departamento</label>
    </div>

    <div class="form-floating pb-1">
        <select id="idpro" name="idpro" class="form-select" onchange="mostrarDistrito(event)" required>
            <option value=""></option>

        </select>
        <label for="idpro">Provincia</label>
    </div>

    <div class="form-floating pb-1">
        <select id="iddis" name="iddis" class="form-select" required>
            <option value=""></option>

        </select>
        <label for="iddis">Distrito</label>
    </div>
</div>

<div class="col-6 col-lg-3">
    <div class="form-floating pb-1">
        <input type="text" class="form-control" id="referencia" name="referencia"
            value="{{ $dd->referencia ?? '' }}" placeholder="Referencia">
        <label for="Preferencia">Referencia</label>
    </div>
    <div class="form-floating pb-1">
        <input type="text" class="form-control" id="interior" name="interior"
            value="{{ $dd->interior ?? ''}}" placeholder="Interior">
        <label for="Pinterior">Interior</label>
    </div>
</div>
