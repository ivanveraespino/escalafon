<div class="d-row gap-3">
    <div class="form-floating">
        <select id="tipodom" name="tipodom" class="form-select">
            <option value="" selected disabled></option>
            @if(!empty($domicilio))
            @foreach($dtv as $d)
            <option {{ $domicilio->tipodom==$d->nombre ? 'selected':''}} value="{{ $d->nombre ?? '' }}">{{ $d->nombre }}</option>
            @endforeach
            @else
            <option value="" selected disabled></option>
            @foreach($dtv as $d)
            <option value="{{ $d->nombre ?? '' }}">{{ $d->nombre }}</option>
            @endforeach
            @endif
            <option value="0" >Agregar más...</option>
        </select>
        <label for="tipodom">Tipo vía</label>
    </div>

    <div class="form-floating">
        <input type="text" class="form-control" id="Pdactual" name="dactual"
            value="{{ $domicilio->dactual ?? '' }}">
        <label for="Pdactual">Dirección de domicilio</label>
    </div>

    <div class="form-floating">
        <input type="text" class="form-control" id="numero" name="numero"
            value="{{ $domicilio->numero ?? '' }}" placeholder="Número">
        <label for="numero">Número</label>
    </div>
</div>

<div class="d-row gap-3">
    <div class="form-floating">
        <select id="iddep" name="iddep" class="form-select" onchange="mostrarProvincia(event)">
            @if(!empty($domicilio))
            <option value=""></option>
            @foreach($dep as $departamento)
            <option {{$domicilio->iddep==$departamento->id ? 'selected':''}} value="{{ $departamento->id}}">
                {{ $departamento->nombre }}
            </option>
            @endforeach
            @else
            <option value="" disabled selected></option>
            @foreach($dep as $departamento)
            <option value="{{ $departamento->id}}">
                {{ $departamento->nombre }}
            </option>
            @endforeach
            @endif
        </select>
        <label for="iddep">Departamento</label>
    </div>

    <div class="form-floating">
        <select id="idpro" name="idpro" class="form-select" onchange="mostrarDistrito(event)">
            @if(!empty($domicilio))
            @foreach($pro as $provincia)
            @if($provincia->departamento_id==$domicilio->iddep)
            <option {{$provincia->id==$domicilio->idpro ? 'selected':''}} value="{{$provincia->departamento_id==$domicilio->iddep ? $provincia->id:''}}">
                {{$provincia->departamento_id==$domicilio->iddep ? $provincia->nombre:''}}
            </option>
            @endif
            @endforeach
            @endif
        </select>
        <label for="idpro">Provincia</label>
    </div>

    <div class="form-floating">
        <select id="iddis" name="iddis" class="form-select">
            @if(!empty($domicilio))
            @foreach($dis as $distrito)
            @if($distrito->provincia_id==$domicilio->idpro)
            <option {{$distrito->id==$domicilio->iddis ? 'selected':''}} value="{{$distrito->provincia_id==$domicilio->idpro ? $distrito->id:''}}">
                {{$distrito->provincia_id==$domicilio->idpro ? $distrito->nombre:''}}
            </option>
            @endif
            @endforeach
            @endif


        </select>
        <label for="iddis">Distrito</label>
    </div>

    <div class="form-floating">
        <input type="text" class="form-control" id="Preferencia" name="referencia"
            value="{{ $domicilio->referencia ?? '' }}" placeholder="Referencia">
        <label for="Preferencia">Referencia</label>
    </div>
    <div class="form-floating">
        <input type="text" class="form-control" id="Pinterior" name="interior"
            value="{{ $domicilio->interior ?? ''}}" placeholder="Interior">
        <label for="Pinterior">Interior</label>
    </div>
</div>