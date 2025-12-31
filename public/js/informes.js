document.getElementById('personal').addEventListener('input', function () {
    let selectedValue = this.value;
    let options = document.getElementById('datalistOptions').options;
    let selectedId = null;
    let selectedName = null;

    for (let option of options) {
        if (option.value === selectedValue) {
            selectedId = option.dataset.id;
            selectedName = option.value;
            break;
        }
    }

    let badgeContainer = document.getElementById('badgeContainer');
    let hiddenInput = document.getElementById('selectedIds');
    if (selectedId) {
        let newBadge = document.createElement('button');
        newBadge.className = 'btn btn-secondary badge-btn';
        newBadge.textContent = selectedName;
        newBadge.setAttribute('data-id', selectedId);

        // Agregar evento para eliminar el badge y actualizar el input hidden
        newBadge.addEventListener('click', function () {
            this.remove();
            updateHiddenInput();
        });

        badgeContainer.appendChild(newBadge);
        updateHiddenInput();
    }
});

function updateHiddenInput() {
    let badges = document.querySelectorAll('.badge-btn');

    let ids = Array.from(badges).map(badge => badge.getAttribute('data-id'));
    document.getElementById('selectedIds').value = ids.join(',');
}
document.getElementById('submitButton').addEventListener('click', function () {
    let badges = document.querySelectorAll('.badge-btn');

    let ids = Array.from(badges).map(badge => badge.getAttribute('data-id'));
    alert(ids);
    fetch('/generar-informe', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ selected_ids: ids, badges: badges })
    })
        .then(response => response.json())
        .then(data => console.log('Respuesta del servidor:', data))
        .catch(error => console.error('Error:', error));
});
document.getElementById("generatePdf").addEventListener("click", function (event) {
    let personal = document.getElementById('selectedIds').value;

    let selectedData = [];
    document.querySelectorAll("input[name='datos[]']:checked").forEach((checkbox) => {
        selectedData.push(checkbox.id.match(/\d+/));
    });

    if (personal != '' && selectedData.length > 0) {
        fetch('/generar-informe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                personal: personal,
                items: selectedData
            })
        })
            .then(response => response.blob()) // Convertir respuesta en archivo Blob
            .then(blob => {
                if (blob.size === 0) {
                    alert("El archivo está vacío o corrupto.");
                    return;
                }

                let url = window.URL.createObjectURL(blob);
                let a = document.createElement('a');
                a.href = url;
                a.download = 'documento_ejemplo.docx';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            })
            .catch(error => console.error('Error:', error));
    } else {
        alert('Ingrese datos correctamente!');
    }
});

document.getElementById("downloadWord").addEventListener("click", function (event) {
    let selectedIds = document.getElementById("selectedIds").value;
    let selectedData = [];
    document.querySelectorAll("input[name='datos[]']:checked").forEach((checkbox) => {
        selectedData.push(checkbox.id.match(/\d+/));
    });
    // Verificar que haya valores antes de generar la URL
    if (selectedIds != null && selectedData != null) {
        // Generar la URL dinámica
        let url = `/descargar-word?personal=${encodeURIComponent(selectedIds)}&datos=${encodeURIComponent(selectedData)}`;
        // Redirigir al usuario
        window.location.href = url;

    } else {
        alert("No hay datos seleccionados.");
        //return;
    }

});

document.getElementById("descargarArchivos").addEventListener("click", function (event) {
    let selectedIds = document.getElementById("selectedIds").value;
    let selectedData = [];
    document.querySelectorAll("input[name='datos[]']:checked").forEach((checkbox) => {
        selectedData.push(checkbox.id.match(/\d+/));
    });
    // Verificar que haya valores antes de generar la URL
    
    if (selectedIds !== "" && selectedData.length>0) {
        // Generar la URL dinámica
        //let url = `/descargar-archivos?personal=${encodeURIComponent(selectedIds)}&datos=${encodeURIComponent(selectedData)}`;
        // Redirigir al usuario
        //window.location.href = url;
        let url = `/descargar-archivos?personal=${encodeURIComponent(selectedIds)}&datos=${encodeURIComponent(selectedData)}`;

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    return response.json().then(error => {
                        throw new Error(error.error || 'Error desconocido');
                    });
                }
                return response.blob(); // ZIP file
            })
            .then(blob => {
                // Crear enlace de descarga
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = 'archivos.zip';
                document.body.appendChild(link);
                link.click();
                link.remove();
            })
            .catch(error => {
                alert(error.message); // Mostrar el mensaje de Laravel
            });


    } else {
        alert("No hay datos seleccionados.");
        //return;
    }

});