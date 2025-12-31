<h5 class="text-munilc">
    {{$personal->Nombres}} {{$personal->Apaterno}} {{$personal->Amaterno}}
</h5>
<div class="container">
    <div class="col">
        <div class="col-12">
            <iframe src="{{ asset('img/fotocheck_' . $personal->id_personal . '.pdf') }}" width="100%" height="500px"></iframe>

        </div>
        <div class="col-12">
        </div>
    </div>
</div>