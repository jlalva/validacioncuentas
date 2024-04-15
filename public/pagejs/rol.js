var Rol = function() {
	"use strict";

	var runRolValidar = function() {
    var form = $('.form-rol');
    var errorHandler = $('.errorHandler', form);
    form.validate({
      rules : {
        rol : {
          minlength : 3,
          required : true
        }
      },
      submitHandler : function(form) {
        errorHandler.hide();
        //form.submit();
        var idrol = $("#idrol").val();
		var rol = $("#rol").val();
		var rolvalidar = $("#rolvalidar").val();
		if(rolvalidar==rol){
			notificacion('info','','No realizó ninguna modificación');
			$("#rol").focus();
			return;
		}
		$.ajax({
			url:url+'rol/ingresar',
			type:'post',
			data:{idrol:idrol,rol:rol},
			beforeSend:function(){
				bloquear("#btnguardar",'Cargando...');
			},
			success:function(data){
				if(data==1){
					modal('modalGuardar','','close');
					changePagination(0);
					notificacion('success','','Datos ingresados correctamente');
				}else{
					if(data==2){
						modal('modalGuardar','','close');
						changePagination(0);
						notificacion('success','','Datos actualizados correctamente');
					}else{
						notificacion('error','','Error al registrar información');
					}				
				}
				desbloquear("#btnguardar",'Guardar');
			}
		})
      },
      invalidHandler : function(event, validator) {
        $(".mensaje").html("Ingrese todos sus datos para poder continuar.");
        errorHandler.show();
      }
    });
  };

return {
    init : function() {
      runRolValidar();
    }
  };
}();

$(function(){
	Rol.init();

	$('input.checkbox-callback').on('ifChecked', function(event) {
			alert('Checked');
	});
	$('input.checkbox-callback').on('ifUnchecked', function(event) {
		alert('Unchecked');
	});
})

function abrirmodal(){
	limpiar();
	modal('modalGuardar','AGREGAR ROL','open');
}

function editar(id){
	desbloquear("#btnguardar",'Guardar');
	$.ajax({
		url:url+'rol/verdatos',
		type:'post',
		data:{id:id},
		dataType:'json',
		success:function(data){
			$("#idrol").val(data.idrol),
			$("#rol").val(data.rol),
			$("#rolvalidar").val(data.rol)
			$('.errorHandler').hide();
			$(".error").html('');
			modal('modalGuardar','MODIFICAR ROL','open');
		}
	})
}

function restablecer(id){
	$("#idrol").val(id)
	modal('modalRestablecer','CONFIRMAR!!','open');
}

function confirma_restablecer(){
	var id = $("#idrol").val()
	$.ajax({
		url:url+'rol/eliminar',
		type:'post',
		data:{id:id,opcion:2},
		success:function(data){
			if(data==4){
				notificacion('warning','','Registro habilitado!!');
				modal('modalRestablecer','','close');
				changePagination(0);
			}else{
				notificacion('error','','Error al habilitar!!');			
			}
		}
	})
}

function eliminar(id){
	$("#idrol").val(id)
	modal('modalEliminar','CONFIRMAR!!','open');
}

function confirma_eliminar(){
	var id = $("#idrol").val()
	$.ajax({
		url:url+'rol/eliminar',
		type:'post',
		data:{id:id,opcion:0},
		success:function(data){
			if(data==3){
				notificacion('warning','','Registro desabilitado!!');
				modal('modalEliminar','','close');
				changePagination(0);
			}else{
				if(data==4){
					notificacion('error','','Registro eliminado!!');
					modal('modalEliminar','','close');
					changePagination(0);
				}else{
					alertify.error('Error al eliminar!!');
				}				
			}
		}
	})
}

function limpiar(){
	$("#idrol").val(''),
	$("#rol").val(''),
	$("#rolvalidar").val('')
	$("#btnguardar").attr('disabled',false);
	$("#btnguardar").html('Guardar');
	$('.errorHandler').hide();
	$(".error").html('');
}

function acceso(id){
	$("#idrol").val(id)
	$.ajax({
		url:url+'rol/listaacceso',
		type:'post',
		data:{idrol:id},
		dataType:'json',
		success:function(data){
			$("#listaacceso").html(data.cad);
			modal('modalAcceso','ACTUALIZAR ACCESOS-'+data.nombre,'open');
		}
	})
}

function daracceso(idmodulo){
	var idrol = $("#idrol").val();
	if( $('#modulo'+idmodulo).prop('checked') ) {
	    $.post(url+"rol/daracceso",{idmodulo:idmodulo,idrol:idrol}, function(data){});
	}else{
		$.post(url+"rol/quitaracceso",{idmodulo:idmodulo,idrol:idrol}, function(data){});
	}
}

function actualizar(){
	location.reload();
}

function opcioncrud(idpermiso,opcion){
	var crud = '';
	var est = 0;
	switch(opcion){
		case 1:crud='ver';break;
		case 2:crud='editar';break;
		case 3:crud='crear';break;
		case 4:crud='eliminar';break;
	}
	if( $('#'+crud+idpermiso).prop('checked') ) {
		est = 1;
	}
	$.post(url+"rol/opcioncrud",{idpermiso:idpermiso,opcion:opcion,est:est}, function(data){});
}

function borrar(id){
	$("#idrol").val(id)
	modal('modalBorrar','CONFIRMAR!!','open');
}

function confirma_borrar(){
	var id = $("#idrol").val()
	$.ajax({
		url:url+'rol/eliminar',
		type:'post',
		data:{id:id,opcion:1},
		success:function(data){
			if(data==4){
				notificacion('error','','Registro eliminado!!');
				modal('modalBorrar','','close');
				changePagination(0);
			}else{
				notificacion('error','','Error al eliminar!!');			
			}
		}
	})
}