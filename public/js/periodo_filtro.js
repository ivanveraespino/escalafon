$('#Eperiodo').on('input', function() {
    var personal_id = $('#personal_id').val();
    var periodo = $('#Eperiodo').val();
    var valorSelect = $('#Eacuentavac').val(); 
    if (valorSelect !== 'No' && periodo && config.url!=='{{ route('cronograma_crud') }}') { 
        $.ajax({
            url: '{{ route('buscarCronograma') }}',
            data: { personal_id: personal_id, periodo: periodo },
            success: function(response) {
                if (response.hasCronograma) {
                    $('#cronogramaWarning').hide();
                    $('#Eperiodo').removeClass('is-invalid').addClass('is-valid'); 
                } else {
                    $('#cronogramaWarning').show();
                    $('#Eperiodo').removeClass('is-valid').addClass('is-invalid'); 
                    $('#cronogramaWarning').text('No se encontró ningún cronograma para este periodo.');
                }
                $('#Ediasr').val(response.dias_restantes); 
            }
        });
    }
}); 