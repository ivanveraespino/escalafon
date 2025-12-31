<html lang="es">

<head>
    <title>MPLC009 - Escalafón</title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap5.min.css') }}">
    <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">

</head>

<body style="background-color: #f4fff7;">
    <div class="container mt-4">
        <div class="row">
            <div class="d-flex justify-content-center" role="alert">
                <div class="card" style="width: 50wh;">
                    <img src="{{public_path('repositories/' . $personal->foto)}}" class="rounded mx-auto d-block card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title text-success text-center">{{$personal->Nombres}} {{$personal->Apaterno}} {{$personal->Amaterno}}</h5>
                        <strong>{{$personal->id_identificacion}}: {{$personal->nro_documento_id}} </strong>
                        <p class="card-text">Inicio de vínculo: {{$vinculo->fecha_ini}}</p>
                        <p class="card-text">Inicio de vínculo: {{$vinculo->fecha_fin ?? 'Sin fecha'}}</p>
                        @php
                        $hoy = \Carbon\Carbon::today();
                        @endphp

                        @if (is_null($vinculo->fecha_fin) || $vinculo->fecha_fin > $hoy)
                        <div class="alert alert-success" role="alert">
                            <strong class=" text-center">Contrato Vigente</strong>
                        </div>
                        @else
                        <div class="alert alert-danger" role="alert">
                            <strong class=" text-center">Sin Vínculo</strong>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>