<style>

.file-upload {
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px dashed #4caf50; /* Borde punteado */
  border-radius: 10px;
  padding: 20px;
  cursor: pointer;
  transition: border-color 0.3s ease;
  background-color: #f9f9f9;
}

/* Cambia el color del borde al pasar el mouse */
.file-upload:hover {
  border-color: #45a049;
}

/* Ocultar el input original */
.file-upload input[type="file"] {
  display: none;
}

/* Estilo del label */
.file-upload label {
  font-size: 16px;
  font-weight: bold;
  color: #4caf50;
  padding: 10px 20px;
  border: 2px solid #4caf50;
  border-radius: 5px;
  background-color: white;
  cursor: pointer;
  transition: background-color 0.3s ease, color 0.3s ease;
}

/* Cambia el estilo del label al pasar el mouse */
.file-upload label:hover {
  background-color: #4caf50;
  color: white;
}

.card-body .row {
    display: flex;
    justify-content: space-between; 
    align-items: center; 
}
.required {
        color: red;
      }
.modal-title {
    font-size: 20px;
    font-weight: bold;
    color: #333;
    text-align: center;
    }
    .smaller-font {
    font-size: 11px;
}
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
     /*CHOSEN*/

/* Contenedor de Chosen */
.chosen-container {
  font-size: 1rem; /* Tamaño de fuente similar a Bootstrap */
  border: 1px solid #ddd; /* Borde gris claro */
  border-radius: 0.25rem; /* Bordes redondeados */
  height: auto;
}

/* Estilo para el campo seleccionado */
.chosen-container .chosen-single {
  display: flex; /* Utiliza Flexbox para el alineamiento */
  align-items: center; /* Centra el texto verticalmente */
  justify-content: space-between; /* Alinea los elementos al principio y al final */
  background-color: #fff; /* Fondo blanco */
  color: #495057; /* Color de texto neutro */
  border: 1px solid #ddd; /* Borde gris claro */
  height: calc(2.25rem + 2px); /* Altura similar a un select estándar de Bootstrap */
  padding-left: 0.75rem; /* Añade espacio a la izquierda */
  padding-right: 0.75rem; /* Añade espacio a la derecha */
}

/* Sin hover en el campo seleccionado */
.chosen-container .chosen-single:hover {
  border-color: #ddd; /* Sin cambio de color en hover */
}

/* Contenedor de las opciones del dropdown */
.chosen-container .chosen-drop {
  border: 1px solid #ddd; /* Borde gris claro */
  border-radius: 0.25rem; /* Bordes redondeados */
  background-color: #fff; /* Fondo blanco */
}

/* Opciones dentro del dropdown */
.chosen-container .chosen-results li {
  padding: 0.375rem 0.75rem; /* Espaciado de las opciones */
  color: #495057; /* Color neutro para las opciones */
}

/* Opciones seleccionadas dentro del dropdown */
.chosen-container .chosen-results li.highlighted {
  background-color: #f8f9fa; /* Fondo gris muy claro para las opciones seleccionadas */
  color: #212529; /* Color oscuro para el texto */
}

/* Placeholder (texto por defecto) */
.chosen-container .chosen-single.chosen-default {
  color: #6c757d; /* Color gris para el texto del placeholder */
}

.form-group {
    margin-left: 20px;
    flex:1;
    min-width: 200px;
}

</style>
<form id="formAdd" class="d-flex flex-column" enctype="multipart/form-data">
    @csrf    
    <!-- 

    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="tab3" data-bs-toggle="tab" href="#rotaciones" role="tab" aria-controls="rotaciones" aria-selected="true">
                Rotaciones
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab4" data-bs-toggle="tab" href="#destaque" role="tab" aria-controls="destaque" aria-selected="false">
                Destaque
            </a>
        </li>
    </ul>
     -->
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="rotaciones" role="tabpanel" aria-labelledby="tab3">
            @include('datoslegajo.forms.movimientos.formRotaciones')
        </div>
    </div>
    <button type="submit" id="btnfam" class="btn btn-success ml-auto mr-0 mt-2" style="margin-right: 10px;">Guardar</button>
</form>
