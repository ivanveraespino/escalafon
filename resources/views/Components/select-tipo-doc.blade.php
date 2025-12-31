


<div class="form-floating {{ $divClass }}">

    <select id="{{ $id }}" name="{{ $name }}" class="form-select">
        <option value="" selected></option>
        @foreach($tdoc as $t)
            @if($t->categoria && in_array($categoria, json_decode($t->categoria, true)))
                <option value="{{ $t->id }}" data-categoria="{{ $t->categoria }}">{{ $t->nombre }}</option>
            @endif
        @endforeach
    </select>
    <label for="{{ $id }}">{{ $label}}</label>
</div>