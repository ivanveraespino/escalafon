
document.getElementById("fecha-ini-san").addEventListener("change",function(){
    var dias=document.getElementById('dias-san').value;
    var inicio=this.value;
    if (esFechaValida(inicio)){
        var res=sumarDias(inicio,dias)
        document.getElementById('fecha-fin-san').value=res.toISOString().split("T")[0];
    }
});



document.getElementById("dias-san").addEventListener("change",function(){
    var inicio=document.getElementById('fecha-ini-san').value;
    var dias=this.value;
    if (esFechaValida(inicio)){
        var res=sumarDias(inicio,dias)
        document.getElementById('fecha-fin-san').value=res.toISOString().split("T")[0];
    }
});
