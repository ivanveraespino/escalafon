@extends('layouts.app')
<style>


    .container {
        display: flex;
    }
    .sidebar {
        width: 0%;
        padding: 15px;
    }
    .content {
        width: 70%;
        padding: 15px;
    }
    #sidebar li {
    position: relative; /* Necesario para posicionar el pseudo-elemento */
    margin: 5px 0; /* Reducir el margen superior e inferior */
    padding: 5px 5px 5px 30px; /* Añadir espacio para el número */
    background-color: white;
    border-radius: 0 5px 5px 0;
}

#sidebar li::before {
    content: counter(item, upper-roman) ".";
    counter-increment: item;
    position: absolute;
    left: 6px;
    top: 50%;
    transform: translateY(-50%);
    background-color: #28a745; /* Color de fondo circular */
    color: white;
    font-weight: bold;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
}

ul {
    list-style-type: none;
    padding: 0;
    counter-reset: item;
}
    #sidebar li a {
    display: block;
    text-indent: -20px; /* Ajusta este valor según sea necesario */
    padding-left: 50px; /* Debe ser igual o mayor que el padding-left de #sidebar li */
}

    #sidebar li.selected {
    background-color: #28a745; /* Cambia este color al que prefieras */
    color: white;
}

.contalt {
            border: 2px solid green; /* Borde verde limón */
            border-radius: 15px; /* Bordes redondeados */
            margin: 16px; /* Reducir padding */
            position: relative;
        }
        .contalt h5 {
            position: absolute;
            background-color: white;
            top: -10px;
            left: 10px;
            margin: 0;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(2, auto);
            gap: 10px;
            margin-top: 10px; /* Espacio para el título */
        }
        .grid-item {
            padding: 10px;
            border-radius: 10px;
            text-align: left;
            font-weight: bold;

        }
</style>


@section('content')
<div class="card m-3">
<div class="container-fluidbg-success">
    <div class="row" >
        <div class="col bg-success rounded rounded-bottom-0 " id="sidebar">
            <ul>
                <li class="p-2 rounded-3 hover-effect" data-content="personales" ><a>Datos Personales </a></li>
                <li class="p-2 rounded-3 hover-effect" data-content="estudios"><a>Formacion academica </a></li>
                <li class="p-2 rounded-3 hover-effect" data-content="explab"><a>Experiencia Laboral</a></li>
                <li class="p-2 rounded-3 hover-effect" data-content="vacaciones"><a>Vacaciones</a></li>
                <li class="p-2 rounded-3 hover-effect" data-content="licencias"><a>Licencias</a></li>
                <li class="p-2 rounded-3 hover-effect" data-content="permisos"><a>Permisos</a></li>

                <li class="p-2 rounded-3 hover-effect" data-content="compensaciones"><a>Compensaciones</a></li>
                <li class="p-2 rounded-3 hover-effect" data-content="reconocimientos"><a>Reconocimientos</a></li>
                <li class="p-2 rounded-3 hover-effect" data-content="sanciones"><a>Reconocimientos y Sanciones</a></li>

            </ul>
        </div>            

        <div class="col-md-10 ">

            <div class="contalt">
            @isset($name)
                <h5>Datos del Trabajador {{ $tipo ? ': ' . $tipo : '' }}</h5>
            @else
                <h5>Datos del Trabajador</h5>
            @endisset
                <div class="grid-container">
                    <div class="grid-item">Documento de Identidad
                    </br><h6>{{ $dp->id_personal ?? '' }} <h6>
                </div>
                    <div class="grid-item">Apellidos Y Nombres
                    <br><h6>{{ $dp->Apaterno ?? '' }} {{ $dp->Amaterno ?? '' }} {{ $dp->Nombres ?? '' }}</h6>
                </div>
                    <div class="grid-item">Estado
                    </br><h6>{{ $dcon->estado_vinculo ?? '' }} <h6>
                    </div>
                    <div class="grid-item">Vinculo Laboral

                    </br><h6>{{ $tireg[$dclact->idregimen ?? ''] ?? '' }}<h6>
                    </div>
                    <div class="grid-item">Oficina
                    </br><h6>{{ $dclact->Oficina ?? '' }} <h6>
                    </div>
                    <div class="grid-item">Cargo
                    </br><h6>{{ $dclact->CargoActual ?? ''  }} <h6>
                    </div>
                </div>
            </div>

                <div id="content2">
                </div>
        </div>
    
    </div>
</div>

@stop

@push('scripts')
<script src="{{asset('js/mayus.js')}}"></script>

<script>
        var dni = "{{ $dp->id_personal ?? '' }}";

        var tipo =  "{{ $tipo ?? '' }}";
        $(document).ready(function() {
            function loadContent(contentId) {
                var dni = $("#dni").val() || "{{ $dp->id_personal ?? '' }}";
                if (dni === '') {
                        $.ajax({
                            url: '../ncampo/' + "personales"+ '/'+tipo,
                            method: 'GET',
                            success: function(data) {
                                $('#content2').html(data);
                                if (contentId !== 'personales') {
                                    $('#dataTable').DataTable(); 
                                }
                            }
                        });        
                } else {
                        $.ajax({
                            url: '../campo/' + contentId + '/' + dni+ '/'+ tipo ,
                            method: 'GET',
                            success: function(data) {
                                $('#content2').html(data);
                                if (contentId !== 'personales') {
                                    $('#dataTable').DataTable(); 
                                }

                            }
                        });
                }
            }
           

        if (dni === '') {   
            loadContent("personales");
            saveSelectedContentId("personales");
            $('#sidebar li').removeClass('selected');
            $(this).addClass('selected');
            $('#sidebar a').on('click', function(e) {
            alert('Por favor, complete los campos personales antes de continuar.');  
        });
    } else {
        $('#sidebar li').on('click', function(e) {
            e.preventDefault();
            var contentId = $(this).data('content');
            loadContent(contentId);
            saveSelectedContentId(contentId);
            $('#sidebar li').removeClass('selected');
            $(this).addClass('selected');
        });
    }
        function saveSelectedContentId(contentId) {
        localStorage.setItem('selectedContentId', contentId);
        }

        function getSelectedContentId() {
            return localStorage.getItem('selectedContentId');
        }

        var selectedContentId = getSelectedContentId();
    if (selectedContentId) {
        loadContent(selectedContentId);
        $('#sidebar li').removeClass('selected'); 
        $('#sidebar li[data-content="' + selectedContentId + '"]').addClass('selected');
    } else {
        loadContent('personales'); 
    }
});
    </script>
@endpush



