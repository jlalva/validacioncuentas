var Usuario = function() {
	"use strict";

	var runUsuarioValidar = function() {
    var form = $('.form-usuario');
    var errorHandler = $('.errorHandler', form);
    form.validate({
      rules : {
        nombre : {
          minlength : 3,
          required : true
        },
        apellido : {
          minlength : 3,
          required : true
        },
        usuario : {
          minlength : 4,
          required : true
        },
        password : {
          minlength : 6,
          required : true
        }
      },
      submitHandler : function(form) {
        errorHandler.hide();
        //form.submit();
        var idusuario = $("#idusuario").val();
		var nombre = $("#nombre").val();
		var apellido = $("#apellido").val();
		var usuario = $("#usuario").val();
		var password = $("#password").val();
		var idrol = $("#idrol").val();
        if(idrol==0){
			notificacion('error','','Seleccione el rol del usuario');
			return;
		}
        $.ajax({
			url:url+'user/ingresar',
			type:'post',
			data:{idusuario:idusuario,nombre:nombre,apellido:apellido,usuario:usuario,password:password,idrol:idrol},
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
						if(data==3){
							notificacion('warning','','El usuario ingresado ya existe');
							$("#usuario").val('');
							$("#usuario").focus();
						}else{
							notificacion('error','','Error al registrar datos');
						}					
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
      runUsuarioValidar();
    }
  };
}();

$(function(){
	Usuario.init();
})

function abrirmodal(){
	limpiar();
	modal('modalGuardar','AGREGAR USUARIO','open');
}

function limpiar(){
	$("#idusuario").val(''),
	$("#nombre").val(''),
	$("#apellido").val(''),
	$("#usuario").val(''),
	$("#password").val(''),
	$("#idrol").val(0)
	$("#usuario").attr('readonly',false);
	$("#password").attr('readonly',false);
	$("#btnguardar").attr('disabled',false);
	$("#btnguardar").html('Guardar');
	$('.errorHandler').hide();
	$(".error").html('');
}


function editar(id){
	desbloquear("#btnguardar",'Guardar');
	$.ajax({
		url:url+'user/verdatos',
		type:'post',
		data:{id:id},
		dataType:'json',
		success:function(data){
			$("#idusuario").val(data.idusuario),
			$("#nombre").val(data.nombre),
			$("#apellido").val(data.apellido),
			$("#usuario").val(data.usuario),
			$("#password").val('xxxxxx'),
			$("#idrol").val(data.idrol)
			$("#usuario").attr('readonly',true);
			$("#password").attr('readonly',true);
			$('.errorHandler').hide();
			$(".error").html('');
			modal('modalGuardar','MODIFICAR USUARIO-'+data.usuario,'open');
		}
	})
}

function eliminar(id){
	$("#idusuario").val(id)
	modal('modalEliminar','CONFIRMAR!!','open');
}

function confirma_eliminar(){
	var id = $("#idusuario").val()
	$.ajax({
		url:url+'user/eliminar',
		type:'post',
		data:{id:id,opcion:0},
		success:function(data){
			if(data==3){
				notificacion('warning','','Registro desabilitado!!');
				modal('modalEliminar','','close');
				changePagination(0);
			}else{
				if(data==4){
					notificacion('warning','','Registro eliminado!!');
					modal('modalEliminar','','close');
					changePagination(0);
				}else{
					notificacion('error','','Error al eliminar!!');
				}				
			}
		}
	})
}

function restablecer(id){
	$("#idusuario").val(id)
	modal('modalRestablecer','CONFIRMAR!!','open');
}

function confirma_restablecer(){
	var id = $("#idusuario").val()
	$.ajax({
		url:url+'user/eliminar',
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

function borrar(id){
	$("#idusuario").val(id)
	modal('modalBorrar','CONFIRMAR!!','open');
}

function confirma_borrar(){
	var id = $("#idusuario").val()
	$.ajax({
		url:url+'user/eliminar',
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