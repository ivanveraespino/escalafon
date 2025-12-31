
function campos(id=null,nombres,idr=null,ctrl) {
    $('#ModalCampos .btn-block').data('id', id);
    $('#ModalCampos .btn-block').data('idr', idr);
    $('#suggestions_out').empty().hide();
    $('#dnip_out').val("");
    if (ctrl === 'true') {
        $('#Pdni').val("");
    }
    if (id === null || id === '') {
        $('#ModalCampos .btn-block').prop('disabled', true);
    } else {
        $('#ModalCampos .btn-block').prop('disabled', false);
        $('#dnip_out').val(nombres);
    }
    $('#ModalCampos').modal('show');
}
// Evento para limpiar valores al cerrar el modal
$('#ModalCampos').on('hidden.bs.modal', function() {
    $('#ModalCampos .btn-block').data('id', '');
    $('#ModalCampos .btn-block').data('idr', '');
    $('#suggestions_out').empty().hide();
    $('#dnip2').val("");
    $('#ModalCampos .btn-block').prop('disabled', true);
    //reiniciar con valores desde 0
    $('#btnCampos').attr('onclick', 'campos()');
});


// cargar id_personal a otros campos
$(document).on('click', '.btn-outline-primary.btn-block', function(event) {
    var button = $(this);
    var recipient = button.data('id');
    var conlab = button.data('idr');
    var contentId = button.data('tipo');
    var id_personal = recipient;
    // Mostrar spinner de carga
    $('#loadingSpinner').show();
    $('#content2').hide();

    $.ajax({
        url: '../campo/' + contentId + '/' + id_personal + '/' + conlab,
        method: 'GET',
        success: function(data) {
            // Limpiar el contenido antes de agregar nuevo contenido
            $('#content2').empty();
            $('#content2').html(data);
            if (contentId !== 'personales') {
                if ($.fn.DataTable.isDataTable('#dataTable')) {
                    $('#dataTable').DataTable().clear().destroy(); // Limpiar y destruir la instancia anterior
                }
                $('#dataTable').DataTable(); // Inicializar nueva instancia
            }
            $('#loadingSpinner').hide();
            $('#content2').show();
        },
        error: function() {
            // Manejar error y mostrar mensaje de error
            $('#loadingSpinner').hide();
            $('#content2').show();
            $('#content2').html(`
                <div>
                    <button type="button" class="close" aria-label="Close" onclick="cerrarModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <p>Error al cargar el contenido.</p>
                </div>
            `);        
        }
    });
});
function mostrar_op() {
    $('#addopM').modal('show'); 
    $('#ModalCampos').modal('hide'); 
    $('#secondaryModal').modal('hide'); 
}

//buscar personal
$('#dnip_out').on('input', function() {
    $('#dnip_out').removeClass('input-seleccionado');

var query = $(this).val();
if (query.length > 1) {
    
    $.ajax({
        url: "buscarUsuarios",
        data: { query: query },
        success: function(data) {
            var suggestions = $('#suggestions_out');
            suggestions.empty();
            $.each(data, function(index, item) {
                suggestions.append('<a class="dropdown-item" data-personal_id="' + item.personal_id + '" data-rm="' + item.regimen_moda + '" data-nombre="' + item.nombre_completo + '">' + item.nombre_completo + '</a>');
            });
            suggestions.show();
        }
    });
} else {
    $('#suggestions_out').empty().hide();
}
});
$(document).click(function(e) {
if (!$(e.target).closest('#personal_id').length) {
    $('#suggestions_out').empty().hide();
}
});

function cerrarModal() {
    $('#ModalCampos').hide();

}


// Al hacer clic en una opción del dropdown
$('#suggestions_out').on('click', '.dropdown-item', function() {
    var dnip_outv = $(this).data('personal_id');
    var reg_mod = $(this).data('rm');
    reg_mod = reg_mod || "TODOS";
    var nombreCompleto = $(this).data('nombre');

    // Asignar el valor seleccionado al campo de búsqueda
    $('#dnip_out').val(nombreCompleto).addClass('input-seleccionado');
    
    // Pasar el valor seleccionado al ModalCampos y habilitar los botones
    $('#ModalCampos .btn-block').data('id', dnip_outv);
    $('#ModalCampos .btn-block').data('idr', reg_mod);
    $('#ModalCampos .btn-block').prop('disabled', false);

    // Esconder las sugerencias
    $('#suggestions_out').hide();
});
