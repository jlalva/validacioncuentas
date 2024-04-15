$(function(){
  $("#usuario").focus();
  $("#usuario").keypress(function(e) {
      var code = (e.keyCode ? e.keyCode : e.which);
      if(code==13){
        var usuario = $("#usuario").val();
        if(usuario!=''){
          $("#password").focus();
        }
      }
  });
  $("#password").keypress(function(e) {
      var code = (e.keyCode ? e.keyCode : e.which);
      if(code==13){
        var usuario = $("#usuario").val();
        var password = $("#password").val();
        if(usuario==''){
          $("#usuario").focus();
          return;
        }else{
          if(password!=''){
            ingresar();
          }
        }
      }
  });
})

function ingresar(){
  alertify.dismissAll();
  var usuario = $("#usuario").val();
  var password = $("#password").val();
  if(usuario==''){
    alertify.error('Ingrese su usario para continuar');
    $("#usuario").focus();
    return;
  }
  if(password==''){
    alertify.error('Ingrese su password para continuar');
    $("#password").focus();
    return;
  }
  $.ajax({
    url:'login',
    type:'post',
    data:{usuario:usuario,clave:password},
    dataType:'json',
    beforeSend:function(){
      bloquear("#btningresar",'Cargando...');
    },
    success:function(data){
      if(data.status=='ok'){
        alertify.success(data.message);
        var url = "inicio";
        window.setTimeout($(location).attr('href',url), 5000 );
      }else{
        alertify.error(data.message);
          $("#usuario").focus();
          desbloquear("#btningresar",'Iniciar');
      }
    }
  })
}

function bloquear(boton,mensaje){
    $(boton).attr('disabled',true);
    $(boton).html(mensaje);
  }

function desbloquear(boton,mensaje){
    $(boton).attr('disabled',false);
    $(boton).html(mensaje);
  }