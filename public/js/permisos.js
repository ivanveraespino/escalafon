


document.getElementById("fecha-fin-per").addEventListener("change", function () {
    var inicio = document.getElementById('fecha-ini-per');
    var fin = this.value;
    // Si ya hay fecha al cargar
    if (inicio.value) {
        fin.min = inicio.value;
    }

    // Actualiza la mínima cuando cambie
    inicio.addEventListener("change", function () {
        fin.min = this.value;

        // ⚠️ Si fecha fin es menor que la nueva fecha de inicio, reinicia
        if (fin.value && fin.value < this.value) {
            fin.value = this.value;
        }
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const selectCuentaVac = document.getElementById('acuentavac-per');
    const inputPeriodo = document.getElementById('periodo-permiso');

    selectCuentaVac.addEventListener('change', function () {
        if (this.value === '1') {
            inputPeriodo.disabled = false;
            inputPeriodo.value = new Date().getFullYear(); // Año actual
        } else {
            inputPeriodo.disabled = true;
            inputPeriodo.value = ''; // Limpia si selecciona "NO" o vacío
        }
    });
});