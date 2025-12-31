// resources/js/pdfUtils.js
async function updatePageCount(event) {
    const file = event.target.files[0];
    if (file) {
        const arrayBuffer = await file.arrayBuffer();
        const pdfDoc = await PDFLib.PDFDocument.load(arrayBuffer);
        const numPages = pdfDoc.getPageCount();
        document.getElementById('nrofolio').value = numPages;
    }
}

document.getElementById('id_archivo').addEventListener('change', updatePageCount);

