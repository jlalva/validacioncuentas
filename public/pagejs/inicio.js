$(function(){
	var idedificio = $("#edificiogeneral").val();
	if(idedificio==0){
		$(".divinforme").hide();
	}
	datosedificio();
	totales();
})
function datosedificio(){
	var idedificio = $("#edificiogeneral").val();
	$.ajax({
		url:url+'nuevoregistro/verdatos',
		type:'post',
		data:{id:idedificio},
		dataType:'json',
		success:function(data){
			$(".nombre_edificio").html(data.nombre_edificio);
			$(".direccion").html(data.direccion);
			$(".partida_registral").html(data.partida_registral);
			$(".ppto_mensual").html(data.ppto_mensual);
			if(data.tipo_ppto=='F'){
				$(".tipo").html('FIJO');
			}else{
				if(data.tipo_ppto=='V'){
					$(".tipo").html('VARIABLE');
				}				
			}
			
			$(".area_total").html(data.area_total);
			switch(parseFloat(data.condicion)) {
			  case 1:$(".condicion").html('OCUPADA');break;
			  case 2:$(".condicion").html('TECHADA');break;
			  case 3:$(".condicion").html('MIXTA');break;
			  case 4:$(".condicion").html('FLAT');break;
			}
			
			$(".nro_departamento").html(data.nro_departamento);
			$(".nro_estacionamiento").html(data.nro_estacionamiento);
			$(".nro_depositos").html(data.nro_depositos);
			$(".siglas").html(data.siglas);			
		}
	})
}

function totales(){
	var idedificio = $("#edificiogeneral").val();
	$.ajax({
		url:url+'informaciongeneral/resumentotal',
		type:'post',
		data:{idedificio:idedificio},
		dataType:'json',
		success:function(data){
			$(".tclientes").html(data.tcliente);
			$(".tdepartamento").html(data.tdepartamento);
			$(".testacionamiento").html(data.testacionamiento);
			$(".tdeposito").html(data.tdeposito);
		}
	})
}
 /*function new_clock(){ 

	momentoActual = new Date() 
    hora = momentoActual.getHours() 
    minuto = momentoActual.getMinutes() 
    segundo = momentoActual.getSeconds() 

    horaImprimible = hora + " : " + minuto + " : " + segundo 

    $(".hora").html(horaImprimible) 
    setTimeout(new_clock,1000)
 }

 setTimeout(new_clock, 1000)*/