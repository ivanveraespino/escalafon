$(document).on('click', '.ver-pdf', function() {
    var pdfData = $(this).data('pdf');
    var url = '/showfiles/' + pdfData;
    $.ajax({
        url: url,
        method: 'GET',
        xhrFields: {
            responseType: 'blob'
        },
        success: function(data, status, xhr) {
            var blob = new Blob([data], { type: 'application/pdf' });
            var url = URL.createObjectURL(blob);
            var contentDisposition = xhr.getResponseHeader('Content-Disposition');
            //var filename = contentDisposition ? contentDisposition.split('filename="')[1].replace('"', '') : 'default.pdf';
            //var newTab = window.open();
            //newTab.document.write('<iframe src="' + url + '" style="width: 100%; height: 100%;"></iframe>');
            //newTab.document.title = filename;
            window.open(url, '_blank');
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: '¡Hubo un error al abrir el archivo!',
            });
        }
    });
});




function formatDate(dateString) {
    if (!dateString) {
        return dateString;
    }
    
    const fechas = dateString.split(',');
    const fechasOrdenadas = fechas.map(fecha => {
        const partesFecha = fecha.trim().split('-');
        if (partesFecha.length === 3) {
            const dia = partesFecha[2];
            const mes = partesFecha[1];
            const año = partesFecha[0];
            return dia + '/' + mes + '/' + año;
        }
        return fecha;
    });

    return fechasOrdenadas.join('<br>');
}

$(document).on('change', '.archivo', async function(event) {
    const file = event.target.files[0];
    if (file) {
        const arrayBuffer = await file.arrayBuffer();
        const pdfDoc = await PDFLib.PDFDocument.load(arrayBuffer);
        const numPages = pdfDoc.getPageCount();
        const nroFoliosInput = $('.nro_folios')[0]
        //const nroFoliosInput = event.target.closest('.form-group').nextElementSibling.querySelector('.nro_folios');
        if (nroFoliosInput){
            nroFoliosInput.value = numPages;
        }
        
    }
});


function convertirAMayusculas() {
    const inputs = document.querySelectorAll('input:not([type="file"])');
    inputs.forEach(input => {
        input.addEventListener('input', () => {
            input.value = input.value.toUpperCase();
        });
    });
}



$(document).ready(function() {
    const form = document.getElementById('formAdd');
    const inputs = form.querySelectorAll('input[required]');
    const button = form.querySelector('button[type="submit"]');

    function verificarCampos() {
        let todosLlenos = true;
        inputs.forEach(input => {
            if (!input.value.trim()) {
                todosLlenos = false;
            }
        });
        button.disabled = !todosLlenos;
    }

    inputs.forEach(input => {
        input.addEventListener('input', verificarCampos);
    });

    //verificarCampos();

    $('.modal.fade').attr({
        'data-keyboard': 'false',
        'data-backdrop': 'static'
    });
});

//CONTROLAR VARIAS PETICIONES
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

//BUSQUEDA DE OFICINAS
