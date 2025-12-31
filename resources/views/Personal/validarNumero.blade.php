<html lang="es">

<head>
    <title>MPLC009 - Escalafón</title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap5.min.css') }}">
    <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">

</head>

<body style="background-color: #f4fff7;">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <form action="/validar-numero" method="get" class="text-center">
            
            <label for="validationDefaultUsername" class="form-label">Te enviamos un código de validación al WhatsApp</label>
            <label for="validationDefaultUsername" class="form-label">Ingrese aquí</label>
            <div class="input-group">
                <span class="input-group-text text-success" id="inputGroupPrepend2"><i class="fas fa-phone"></i></span>
                <input type="tel" class="form-control" name="num-wpp" id="num-wpp" placeholder="Ej. 0000" required>
            </div>
            <button type="submit" class="btn btn-outline-success mt-3">Validar</button>
        </form>
    </div>
</body>
</html>