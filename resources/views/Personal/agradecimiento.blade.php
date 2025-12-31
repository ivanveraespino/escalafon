<html lang="es">

<head>
    <title>MPLC009 - Escalafón</title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap5.min.css') }}">
    <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">

</head>

<body style="background-color: #f4fff7;">
    <div class="container mt-4">
        <div class="row">
            <div class="alert alert-success" role="alert">
                <h1>Exitoso!!</h1>
                <p>Gracias!! </p>
                <p>Se ha registrado sus datos exitosamente</p>
                <a href="{{ route('setPersonal') }}" class="alert-link">Ingresar Nuevo</a>

            </div>
        </div>
    </div>
</body>

</html>