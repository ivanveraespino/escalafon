<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>MPLC008
    </title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
    .body {
      background-color: green; /* Change background color to green */
    
    }
    .text-munilc {
        color: #1e8e3e;
    }
    #center-panel{
        height:100vh; display:flex; justify-content: center; align-items: center;
    }

  </style>

</head>

<body style="background-color: #f4fff7;">

        <!-- Outer Row -->
        <div id="center-panel">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 col-12" style="display: flex; justify-content: center;">
                                <img src="img/logo.png" width="100%" class="p-5"/>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 mb-4 text-munilc" style="font-weight: bold;">ESCALAFÓN</h1>
                                    </div>
                                    <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="email" name="email" aria-describedby="emailHelp"
                                                placeholder="Usuario">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="password" name="password" placeholder="Contraseña">
                                        </div>
                                       
                                        
                                        <button type="submit" class="btn btn-success btn-user btn-block">
                                            Iniciar sesión
                                        </button>
                                        <hr>
                                        
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>


    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
