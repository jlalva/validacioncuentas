function tipodescarga(){
    const tipodescarga = document.querySelector('input[name="tipodescarga"]:checked');
    tipo = tipodescarga.value;
    if(tipo == 1){
       $("#modalCompartir").modal('show');
    }else{
        $("#modalSubirRepositorio").modal('show');
    }
}

function descargacompartir(){
    const descargar = document.querySelector('input[name="tipoarchivodescargar"]:checked');
    var idarchivo = $("#idarchivo").val();
    archivo = descargar.value;
    if(archivo == 1){
        var urli = url + "generardata/descargarcuentas/"+idarchivo;
        $(location).attr('href',urli)
        $("#modalCompartir").modal('hide');
    }else{
        var urli = url + "generardata/pdf/"+idarchivo;
        $(location).attr('href',urli)
    }
}