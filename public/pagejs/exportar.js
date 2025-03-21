function consultar(){
    var tipo = $("#tipo").val();
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    $.ajax({
		url:url+'exportar/filtrado',
		type:'post',
		data:{tipo:tipo,fecha_inicio:fecha_inicio,fecha_fin:fecha_fin},
		success:function(data){
			$("#datosExportar").html(data);
		}
	})
}