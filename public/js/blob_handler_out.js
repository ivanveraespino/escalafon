$(document).on('click', '.ver-pdf', function() {
    console.log('asui1');
    var pdfData = $(this).data('pdf');
    var url = '/showfiles' + pdfData;
    $.ajax({
        url: url,
        method: 'GET',
        xhrFields: {
            responseType: 'blob'
        },
        success: function(data, status, xhr) {
            var blob = new Blob([data], { type: 'application/pdf' });
            var url = URL.createObjectURL(blob);
            var filename = xhr.getResponseHeader('Content-Disposition').split('filename="')[1].replace('"', '');
            var downloadLink = $('<a>')
                .attr('href', url)
                .attr('download', filename)
                .text('Descargar');

            $('#pdfModal .modal-header').html(downloadLink);

            $('#pdfIframe').attr('src', url);
            $('#pdfModal').modal('show');
        },
        error: function() {
            alert('Error al cargar el archivo.');
        }
    });
});

function formatDate(dateString) {
    if (!dateString) {
      return dateString;
    }
    const partesFecha = dateString.split('-');
    const dia = partesFecha[2];
    const mes = partesFecha[1];
    const año = partesFecha[0];
    const fechaOrdenada = dia + '-' + mes + '-' + año;
  
    return fechaOrdenada;
  }

document.querySelectorAll('.archivo').forEach(input => {
    input.addEventListener('change', async function(event) {
        const file = event.target.files[0];
        if (file) {
            const arrayBuffer = await file.arrayBuffer();
            const pdfDoc = await PDFLib.PDFDocument.load(arrayBuffer);
            const numPages = pdfDoc.getPageCount();
            const nroFoliosInput = event.target.closest('.form-group').nextElementSibling.querySelector('.nro_folios');
            nroFoliosInput.value = numPages;
        }
    });
});


