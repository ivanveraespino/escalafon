
document.getElementById("fecha-ini-recon").addEventListener("change", function () {
    var dias = document.getElementById('dias-recon').value;
    var inicio = this.value;
    if (esFechaValida(inicio)) {
        var res = sumarDias(inicio, dias)
        document.getElementById('fecha-fin-recon').value = res.toISOString().split("T")[0];
    }
});


document.querySelectorAll('input[name="tipo-recon"]').forEach(radio => {
    radio.addEventListener('change', () => {
        const valor = document.querySelector('input[name="tipo-recon"]:checked').value;
        if (valor == 1) {
            document.getElementById('fecha-ini-recon').value = "";
            document.getElementById('fecha-fin-recon').value = "";
            document.getElementById('fecha-ini-recon').disabled = false;
            document.getElementById('fecha-fin-recon').disabled = false;
        } else {
            document.getElementById('fecha-ini-recon').disabled = true;
            document.getElementById('fecha-fin-recon').disabled = true;
            document.getElementById('fecha-ini-recon').value = "";
            document.getElementById('fecha-fin-recon').value = "";
        }
    });
});