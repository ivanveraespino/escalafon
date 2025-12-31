document.addEventListener("DOMContentLoaded", function () {
    var fechaIniLic = document.getElementById("fecha-ini-lic");
    
    var fechaFinLic = document.getElementById("fecha-fin-lic");

    // Si ya hay fecha al cargar
    if (fechaIniLic.value) {
        fechaFinLic.min = fechaIniLic.value;
    }

    // Actualiza la mínima cuando cambie
    fechaIniLic.addEventListener("change", function () {
        fechaFinLic.min = this.value;

        // ⚠️ Si fecha fin es menor que la nueva fecha de inicio, reinicia
        if (fechaFinLic.value && fechaFinLic.value < this.value) {
            fechaFinLic.value = this.value;
        }
    });

});
