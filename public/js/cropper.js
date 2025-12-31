import Cropper from 'cropperjs';

let cropper;

// Selección de elementos
const dragAndDropArea = document.getElementById('dragAndDropArea');
const fileInput = document.getElementById('fileInput');
const cropperContainer = document.getElementById('cropperContainer');
const imagePreview = document.getElementById('imagePreview');
const cropButton = document.getElementById('cropButton');

// Eventos de arrastrar y soltar
dragAndDropArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    dragAndDropArea.classList.add('dragover');
});

dragAndDropArea.addEventListener('dragleave', () => {
    dragAndDropArea.classList.remove('dragover');
});

dragAndDropArea.addEventListener('drop', (e) => {
    e.preventDefault();
    dragAndDropArea.classList.remove('dragover');

    const file = e.dataTransfer.files[0];
    if (file) {
        handleFile(file);
    }
});

// Clic para seleccionar archivo
dragAndDropArea.addEventListener('click', () => {
    fileInput.click();
});

fileInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
        handleFile(file);
    }
});

// Procesar archivo seleccionado
function handleFile(file) {
    const reader = new FileReader();
    reader.onload = (e) => {
        imagePreview.src = e.target.result;
        cropperContainer.style.display = 'block';

        // Destruir el cropper anterior si existe
        if (cropper) {
            cropper.destroy();
        }

        // Inicializar Cropper.js
        cropper = new Cropper(imagePreview, {
            aspectRatio: 1, // Relación de aspecto 1:1 para fotos de perfil
            viewMode: 2,    // Limita el recorte dentro del contenedor
        });
    };
    reader.readAsDataURL(file);
}

// Recortar y subir la imagen al servidor
cropButton.addEventListener('click', () => {
    cropper.getCroppedCanvas().toBlob((blob) => {
        const formData = new FormData();
        formData.append('avatar', blob);

        // Enviar la imagen recortada al servidor
        fetch('/upload-avatar', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Error al subir la imagen');
                }
                return response.json();
            })
            .then((data) => {
                alert('Imagen subida con éxito: ' + data.url);
                cropperContainer.style.display = 'none'; // Oculta el contenedor de recorte
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('Hubo un problema al subir la imagen.');
            });
    });
});
