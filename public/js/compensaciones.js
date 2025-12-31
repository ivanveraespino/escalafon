
document.getElementById("fecha-ini-comp").addEventListener("change",function(){
    var dias=document.getElementById('dias-comp').value;
    var inicio=this.value;
    if (esFechaValida(inicio)){
        var res=sumarDias(inicio,dias-1)
        document.getElementById('fecha-fin-comp').value=res.toISOString().split("T")[0];
    }
});



document.getElementById("dias-comp").addEventListener("change",function(){
    var inicio=document.getElementById('fecha-ini-comp').value;
    var dias=this.value;
    if (esFechaValida(inicio)){
        var res=sumarDias(inicio,dias-1)
        document.getElementById('fecha-fin-comp').value=res.toISOString().split("T")[0];
    }
});
