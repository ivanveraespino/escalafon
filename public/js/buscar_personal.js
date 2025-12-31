$('#personal_id').on('input', function() {
    var query = $(this).val();
    if (query.length > 1) {
        $.ajax({
            url: "/buscarUsuarios",
            data: { query: query },
            success: function(data) {
                var suggestions = $('#suggestions');
                suggestions.empty();
                $.each(data, function(index, item) {
                    suggestions.append('<a class="dropdown-item" data-personal_id="' + item.personal_id + '" data-nombre2="' + item.nombre_completo + '">' + item.personal_id + ' - ' + item.nombre_completo + '</a>');
                });
                suggestions.show();
            }
        });
    } else {
        $('#suggestions').empty().hide();
    }
});

