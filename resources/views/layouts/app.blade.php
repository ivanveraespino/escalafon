<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MPLC009 - Escalafón</title>
    
    <link rel="stylesheet" href="https://cdn.lineicons.com/5.0/lineicons.css" />
    <!-- Custom fonts for this template-->
    <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('/css/bootstrap5.min.css')}}" rel="stylesheet">
    <link href="{{asset('/css/sb-admin-2.min.css')}}" rel="stylesheet">
    
    <!-- Select 2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
    <!-- Select 2 -->


    <!--link href="{{asset('/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"-->
    <link href="//cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="{{ asset('css/modals_registro.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal_dp.css') }}">
    <link rel="stylesheet" href="{{ asset('css/general.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chosen.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">    
    <!-- Chosen css -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/css/bootstrap-select.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper" class="bg-teal-100">

        <!-- Sidebar -->
        <aside id="sidebar" class="expand">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                <i class="lni lni-menu-hamburger-1"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="#">ESCALAFON</a>
                </div>
            </div>
            
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link menu" onclick="loadContent('{{route('profile')}}')">
                        <i class="lni lni-user-4"></i>
                        <span>PERFIL</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('home') }}" class="sidebar-link menu">
                        <i class="lni lni-home-2"></i>
                        <span>INICIO</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="#" class="sidebar-link menu" onclick="loadContent('{{ route('employees.index') }}','/home')">
                        <i class="lni lni-user-multiple-4"></i>
                        <span>PERSONAL</span>
                    </a>
                </li>
                
                 <li class="sidebar-item" style="display: none;">
                    <a href="#" class="sidebar-link collapsed has-dropdown menu" data-bs-toggle="collapse"
                        data-bs-target="#legajo" aria-expanded="false" aria-controls="auth">
                        <i class="lni lni-book-1"></i>
                        <span>LEGAJO</span>
                    </a>
                    <ul id="legajo" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" onclick="loadContent('{{ route('legajo.familiares') }}')">
                            Familiares
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" onclick="loadContent('{{ route('legajo.vinculos') }}')">
                            Vinculo Laboral
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" onclick="loadContent('{{ route('legajo.formacion') }}')">
                            Estudios
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" onclick="loadContent('{{ route('legajo.estudiosCom') }}')">
                            Estudios Complementarios
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" onclick="loadContent('{{ route('legajo.colegiatura') }}')">
                            Colegiatura
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" onclick="loadContent('{{ route('legajo.idiomas') }}')">
                            Idiomas
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" onclick="loadContent('{{ route('legajo.experiencia') }}')">
                            Experiencia Laboral
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" onclick="loadContent('{{ route('legajo.movimientos') }}')">
                            Rotaciones
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" onclick="loadContent('{{ route('legajo.vacaciones') }}')">
                            Vacaciones
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" onclick="loadContent('{{ route('legajo.licencias') }}')">
                            Licencias
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" onclick="loadContent('{{ route('legajo.permisos') }}')">
                            Permisos
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" onclick="loadContent('{{ route('legajo.compensaciones') }}')">
                            Compensaciones
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" onclick="loadContent('{{ route('legajo.asignacion') }}')">
                                Asignación de Tiempo
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" onclick="loadContent('{{ route('legajo.reconocimientos') }}')">
                            Reconocimientos
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" onclick="loadContent('{{ route('legajo.sanciones') }}')">
                            Sanciones
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item" style="display: none;" >
                    <a onclick="modalIM()" class="sidebar-link menu">
                        <i class="lni lni-user-4"></i>
                        <span>INFORMES</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="#" class="sidebar-link menu" onclick="loadContent('{{ route('verReporte') }}','/home')">
                        <i class="lni lni-user-4"></i>
                        <span>INFORMES</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="{{route('general')}}" class="sidebar-link menu">
                    <i class="lni lni-file-multiple"></i>
                        <span>ARCHIVOS</span>
                    </a>
                </li>

                <li class="sidebar-item" style="display: none;">
                    <a href="#" class="sidebar-link menu" onclick="loadContent('{{ route('Movimiento.index') }}')">
                        <i class="lni lni-aeroplane-1"></i>
                        <span>MOVIMIENTOS</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed has-dropdown menu" data-bs-toggle="collapse"
                        data-bs-target="#vacaciones" aria-expanded="false" aria-controls="auth">
                        <i class="lni lni-pause"></i>
                        <span>PAUSA LABORAL</span>
                    </a>
                    <ul id="vacaciones" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="{{route('cronogramarIndividual')}}" class="sidebar-link">Individual</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('cronogramarMasivo') }}" class="sidebar-link">Masivo</a>
                        </li>
                        
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed has-dropdown menu" data-bs-toggle="collapse"
                        data-bs-target="#reportes" aria-expanded="false" aria-controls="auth">
                        <i class="lni lni-agenda"></i>
                        <span>REPORTES</span>
                    </a>
                    <ul id="reportes" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="{{route('ReporteTiempo.index')}}" class="sidebar-link">Años de Trabajadores</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('Operaciones.index') }}" class="sidebar-link">Reportes</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('fotocheckMasivo') }}" class="sidebar-link">Fotocheck Masivo</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed has-dropdown menu" data-bs-toggle="collapse"
                        data-bs-target="#ajustes" aria-expanded="false" aria-controls="auth">
                        <i class="lni lni-gear-1"></i>
                        <span>AJUSTES</span>
                    </a>
                    <ul id="ajustes" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="{{route('Regimen.index')}}" class="sidebar-link">Regimen</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('RegimenPen.index') }}" class="sidebar-link">Regimen Pensionario</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('Regimen_modalidad.index') }}" class="sidebar-link">Condición Laboral</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('Documento.index') }}" class="sidebar-link">Documentos</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('Area.index') }}" class="sidebar-link">Areas</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('Cargo.index') }}" class="sidebar-link">Cargos</a>
                        </li>
                    </ul>
                </li>
              
            </ul>
            <div class="sidebar-footer">
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="sidebar-link">
                    <i class="lni lni-exit"></i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </aside>

        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column" style="background-color: #f4fff7;">
            <div id="loader" style="display: none; text-align: center; padding: 20px;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
            </div>
            <!-- Main Content -->
            <div id="content" class="p-3 container-fluid" >
                @include('layouts.modal_seleccionar_campos')
                @include('layouts.modal_domicilio')


                    @yield('content')

            </div>
            <!-- End of Main Content -->

        
            <footer class="sticky-footer ">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Oficina de Tecnologías de la Información y Comunicaciones - <strong>Municipalidad Provincial de La Convención<strong></span>
                    </div>
                </div>
            </footer>


        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-success" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap core JavaScript-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <!-- <script src="{{ asset('bootstrap5/js/bootstrap.bundle.min.js') }}"></script>-->

    <!-- Core plugin JavaScript-->
    <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('js/sb-admin-2.min.js')}}"></script>
    <script src="{{asset('js/script.js')}}"></script>


    <!-- Page level plugins -->
    <!--script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></!--script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/dataTables.buttons.js')}}"></script>
    <script src="{{asset('vendor/datatables/buttons.bootstrap4.js')}}"></script>
    <script src="{{asset('vendor/datatables/buttons.print.min.js')}}"></script>
-->

    <script src="//cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="//cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="//cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
    <script src="//cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="//cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/gijgo/1.9.13/combined/js/gijgo.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.17.1/pdf-lib.min.js"></script>
   <!--  <script src="{{ mix('js/app.js') }}"></script>-->

    <!-- MANEJAR ARCHIVOS MAYUSCULAS Y FOLIOS-->
    <script src="{{asset('js/files_handler.js')}}"></script>


    <script src="{{asset('js/informe.js')}}"></script>
    <script src="{{asset('js/cropper.js')}}"></script>

    <!-- FUNCIONES Y VALIDACIONES VACACIONES,LICENCIAS,PERMISOS -->
    <script src="{{asset('js/funciones_vlp.js')}}"></script>

    <!-- CRONOGRAMA -->
    <script src="{{asset('js/cronograma.js')}}"></script>

    
    <script>
        function loadContent(route, urlPath, event) {
            const loader = document.getElementById("loader");
            const dynamicContent = document.getElementById("content");

            // Mostrar loader y ocultar contenido dinámico
            loader.style.display = "block";
            dynamicContent.style.opacity = "0.5";
            if (urlPath) {
                history.pushState(null, '', urlPath);
            }
            if (event) event.preventDefault(); 
            $.ajax({
                url: route,
                method: 'GET',
                success: function(data) {
                    $('#content').html(data);
                    loader.style.display = "none";
                    dynamicContent.style.opacity = "1";
                    initializeToggle();
                },
                error: function(error) {
                    alert('Hubo un error al cargar los datos');
                    loader.style.display = "none"; 
                    dynamicContent.style.opacity = "1";
                }
            });
        }

        //ARREGLAR
        function initializeToggle() {
        const toggleButton = document.getElementById("toggleFormButton");
        const formContainer = document.getElementById("formContainer");

        if (toggleButton && formContainer) {
            toggleButton.addEventListener("click", function () {
                console.log("Botón toggle clickeado");
                if (formContainer.classList.contains("show")) {
                    formContainer.classList.remove("show");
                    setTimeout(() => {
                        formContainer.style.display = "none";
                    }, 300);
                } else {
                    formContainer.style.display = "block";
                    setTimeout(() => {
                        formContainer.classList.add("show");
                    }, 8);
                }
            });
        }
    }
    </script>
    <!-- FUNCIONES TRANSICION LEGAJOS-->
    <script src="{{asset('js/funciones_modal_legajo.js')}}"></script>

    <!-- FUNCIONES MODAL DATOS PERSONALES-->
    <script src="{{asset('js/funciones_datos_p.js')}}"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- FUNCIONES EXPORTAR EXCEL-->

    <script src="{{asset('js/excel_export.js')}}"></script>

    <script src="{{asset('js/busquedas.js')}}"></script>
    <script src="{{asset('js/toolsCRUD.js')}}"></script>

    <script src="{{asset('js/main.js')}}"></script>

    <!-- Chosen JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/js/bootstrap-select.min.js"></script>
    <!-- App scripts -->
    @stack('scripts')


</body>

</html>

