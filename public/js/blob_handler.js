
$(document).on('change', '#archivo', async function(event) {
    const file = event.target.files[0];
    if (file) {
        const arrayBuffer = await file.arrayBuffer();
        const pdfDoc = await PDFLib.PDFDocument.load(arrayBuffer);
        const numPages = pdfDoc.getPageCount();
        $('#Enro_folios').val(numPages); // Actualizar el valor del input con id Enro_folios
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

