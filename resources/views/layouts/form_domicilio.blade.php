<div class="d-row gap-3">
    <div class="form-floating">
        <select id="tipodom" name="tipodom" class="form-select">
            <option selected disbled></option>
            @foreach($dtv as $d)
            <option value="{{ $d->nombre ?? '' }}">{{ $d->nombre }}</option>
            @endforeach
            <option value="0" >Agregar más...</option>
        </select>
        <label for="tipodom">Tipo vía</label>
    </div>

    <div class="form-floating">
        <input type="text" class="form-control" id="Pdactual" name="dactual"
            value="{{ $dd->dactual ?? '' }}">
        <label for="Pdactual">Dirección de domicilio</label>
    </div>

    <div class="form-floating">
        <input type="text" class="form-control" id="numero" name="numero"
            value="{{ $dd->numero ?? '' }}" placeholder="Número">
        <label for="numero">Número</label>
    </div>
</div>

<div class="d-row gap-3">
    <div class="form-floating">
        <select id="iddep" name="iddep" class="form-select" onchange="mostrarProvincia(event)">
            <option disabled selected></option>
            @foreach($dep as $departamento)
            <option value="{{ $departamento->id}}">
                {{ $departamento->nombre }}
            </option>
            @endforeach
        </select>
        <label for="iddep">Departamento</label>
    </div>

    <div class="form-floating">
        <select id="idpro" name="idpro" class="form-select" onchange="mostrarDistrito(event)" disabled>
            <option value=""></option>

        </select>
        <label for="idpro">Provincia</label>
    </div>

    <div class="form-floating">
        <select id="iddis" name="iddis" class="form-select" disabled>
            <option value=""></option>

        </select>
        <label for="iddis">Distrito</label>
    </div>

    <div class="form-floating">
        <input type="text" class="form-control" id="referencia" name="referencia"
            value="{{ $dd->referencia ?? '' }}" placeholder="Referencia">
        <label for="Preferencia">Referencia</label>
    </div>
    <div class="form-floating">
        <input type="text" class="form-control" id="interior" name="interior"
            value="{{ $dd->interior ?? ''}}" placeholder="Interior">
        <label for="Pinterior">Interior</label>
    </div>
</div>