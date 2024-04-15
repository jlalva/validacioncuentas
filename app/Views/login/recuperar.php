<?php

use Config\App;

$app = new App();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Sistema de Encuentas </title>

  <link href="public/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="public/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="public/vendors/nprogress/nprogress.css" rel="stylesheet">
  <link href="public/vendors/animate.css/animate.min.css" rel="stylesheet">
  <link href="public/build/css/custom.min.css" rel="stylesheet">
  <link href="<?= $app->baseURL ?>public/alertify/alertify.min.css" rel="stylesheet">
  <link href="<?= $app->baseURL ?>public/waitme/waitMe.css" rel="stylesheet">
  <link href="<?= $app->baseURL ?>public/alertify/default.min.css" rel="stylesheet">
  <script src="<?= $app->baseURL ?>public/alertify/alertify.min.js"></script>
  <script src="<?= $app->baseURL ?>public/query/jquery-1.10.2.js"></script>
</head>

<body class="login">
  <div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>

    <div class="login_wrapper">
      <div class="animate form login_form">
        <section class="login_content">
          <form class="formvalida" onsubmit="return false">
            <h1>Valida tu identidad</h1>
            <div class="col-md-10 col-sm-10">
              <input type="text" class="form-control" placeholder="Correo" name="correo" id="correo" autocomplete="off"/>
            </div>
            <div class="col-md-1 col-sm-1">
              <button class="btn btn-success" id="enviar"><i class="fa fa-send"></i></button>
            </div>
            <div class="col-md-12 col-sm-12">
              <input class="form-control" placeholder="Código" name="codigo" id="codigo" />
            </div>
            <div class="clearfix"></div>
            <br />
            <div>
              <button class="btn btn-success" onclick="validarCodigo()">Validar</button>
              <a class="reset_pass" href="<?= $app->baseURL ?>">Iniciar Sesión</a>
            </div>
            <div class="clearfix"></div>
            <div class="separator">
              <div class="clearfix"></div>
              <br />
              <div>
                <h1><i class="fa fa-paw"></i> Sistema de encuestas</h1>
              </div>
            </div>
          </form>
          <form class="formnclave" onsubmit="return false">
            <h1>Nueva Contraseña</h1>
            <div class="col-md-12 col-sm-12 form-group has-feedback">
              <input type="text" class="form-control" placeholder="Correo" name="ccorreo" id="ccorreo" autocomplete="of" readonly="true" />
              <input type="hidden" id="idusuario" name="idusuario">
            </div>
            <div class="col-md-12 col-sm-12 form-group has-feedback" id="show_hide_password">
              <input type="password" class="form-control" placeholder="Nueva Contraseña" name="clave" id="clave" autocomplete="of
              "/>
              <span class="fa fa-eye-slash form-control-feedback right icono" aria-hidden="true"  style="cursor: pointer;"></span>
            </div>
            <div class="col-md-12 col-sm-12 form-group has-feedback" id="show_hide_password_dos">
              <input type="password" class="form-control" placeholder="Repite tu contraseña" name="repiteclave" id="repiteclave" autocomplete="of
              "/>
              <span class="fa fa-eye-slash form-control-feedback right icono" aria-hidden="true"  style="cursor: pointer;"></span>
            </div>
            <!--<div>
              <input type="password" class="form-control" placeholder="Nueva Contraseña" name="clave" id="clave" autocomplete="of
                " />
            </div>-->
            <!--<div>
              <input type="password" class="form-control" placeholder="Repite tu contraseña" name="repiteclave" id="repiteclave" />
            </div>-->
            <div class="clearfix"></div>
            <br />
            <div>
              <button class="btn btn-success" onclick="cambiarClave()">Guardar</button>
              <a class="reset_pass" href="<?= $app->baseURL ?>">Iniciar Sesión</a>
            </div>
            <div class="clearfix"></div>
            <div class="separator">
              <div class="clearfix"></div>
              <br />
              <div>
                <h1><i class="fa fa-paw"></i> Sistema de encuestas</h1>
              </div>
            </div>
          </form>
        </section>
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function() {
      $('#correo').on('input', function() {
        $(this).val(function(_, val) {
          return val.toLowerCase();
        });
      });
    });

    $(".formnclave").hide();

    $("#show_hide_password .icono").on('click', function(event) {
        event.preventDefault();
        if ($('#show_hide_password input').attr("type") == "text") {
          $('#show_hide_password input').attr('type', 'password');
          $('#show_hide_password span').addClass("fa-eye-slash");
          $('#show_hide_password span').removeClass("fa-eye");
        } else if ($('#show_hide_password input').attr("type") == "password") {
          $('#show_hide_password input').attr('type', 'text');
          $('#show_hide_password span').removeClass("fa-eye-slash");
          $('#show_hide_password span').addClass("fa-eye");
        }
      });

      $("#show_hide_password_dos .icono").on('click', function(event) {
        event.preventDefault();
        if ($('#show_hide_password_dos input').attr("type") == "text") {
          $('#show_hide_password_dos input').attr('type', 'password');
          $('#show_hide_password_dos span').addClass("fa-eye-slash");
          $('#show_hide_password_dos span').removeClass("fa-eye");
        } else if ($('#show_hide_password_dos input').attr("type") == "password") {
          $('#show_hide_password_dos input').attr('type', 'text');
          $('#show_hide_password_dos span').removeClass("fa-eye-slash");
          $('#show_hide_password_dos span').addClass("fa-eye");
        }
      });

    $("#enviar").click(function() {
      var correo = $.trim($('#correo').val());
      wrapper = $(".formvalida");
      var validaCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (correo != '') {
        if (!validaCorreo.test(correo)) {
          Swal.fire({
                  icon: 'error',
                  title: 'Error...',
                  text: 'El correo ingresado no es válido',
                });
                return;
        }
        wrapper.waitMe();
        $.ajax({
          url: '<?=$app->baseURL?>validarcorreo',
          type: 'POST',
          data: {
            correo: correo
          },
          cache: false,
        }).done(function(res) {
          wrapper.waitMe('hide');
          if (res == 'vacio') {
            $("#correo").val('');
            $("#codigo").val('');
            $("#correo").focus();
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'El correo ingresado no fue encontrado',
            })
          } else {
            if (res == 'ok') {
              $("#correo").attr("readonly", true);
              Swal.fire({
                icon: 'success',
                title: 'Validado!',
                text: 'El código de validación fue enviado a su correo',
              })
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error...',
                text: 'Ocurrio un error al enviar el código de validación',
              })
            }
          }
        }).fail(function(err) {
          wrapper.waitMe('hide');
          alertify.error('Ocurrio un errror al conectarse con el servidor');
        }).always(function() {
          //Cuando termina
          //wrapper.waitMe('hide');
        })
      }else{
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'No ha ingresado ningún correo',
        })
      }
    });

    function validarCodigo() {
      var correo = $("#correo").val();
      var codigo = $("#codigo").val();
      if (correo != '' && codigo != '') {
        $.ajax({
          url: '<?=$app->baseURL?>validarcodigo',
          type: 'POST',
          data: {
            correo: correo,
            codigo: codigo
          },
          cache: false,
          /*beforeSend: function() {
              wrapper.waitMe();
          }*/
        }).done(function(res) {
          if (res == 'vacio') {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'El código ingresado no es válido',
            })
          } else {
            if (res == 'expiro') {
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'El código ingresado ya expiró',
              })
            } else {
              if (res != 'error') {
                $("#ccorreo").val(correo);
                $("#idusuario").val(res);
                $(".formvalida").hide();
                $(".formnclave").show();
                alertify.success("Código de validado");
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Error...',
                  text: 'Ocurrio un error al enviar el código de validación',
                })
              }
            }
          }
        }).fail(function(err) {
          //wrapper.waitMe('hide');
          alertify.error('Ocurrio un errror al conectarse con el servidor');
        }).always(function() {
          //Cuando termina
        })
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Ingrese todos los campos para continuar',
        })
      }
    }

    function cambiarClave() {
      var correo = $("#ccorreo").val();
      var idusuario = $("#idusuario").val();
      var clave = $("#clave").val();
      var repiteclave = $("#repiteclave").val();
      if (idusuario == '' || idusuario == 0) {
        alertify.error('Hay un error en la validación, solicite un nuevo código de validación');
        return false;
      }
      if (clave == '') {
        alertify.error('Ingrese su nueva clave');
        return false;
      }
      if (repiteclave == '') {
        alertify.error('Repita su nueva clave');
        return false;
      }
      if (clave == repiteclave) {
        $.ajax({
          url: '<?=$app->baseURL?>actualizarclave',
          type: 'POST',
          data: {
            correo: correo,
            clave: clave,
            idusuario: idusuario
          },
          cache: false,
          /*beforeSend: function() {
              wrapper.waitMe();
          }*/
        }).done(function(res) {
          if (res == 'ok') {
            Swal.fire({
              icon: 'success',
              title: 'Ok...',
              text: 'Clave actualizada correctamente',
            })
            setTimeout("redireccionar()", 3500);
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error...',
              text: 'Ocurrio un error al actualizar su clave',
            })
          }
        }).fail(function(err) {
          //wrapper.waitMe('hide');
          alertify.error('Ocurrio un errror al conectarse con el servidor');
        }).always(function() {
          //Cuando termina
        })
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Sus claves no coinciden, vuelva a ingresar',
        })
      }
    }

    function redireccionar() {
      window.location.href = "<?= $app->baseURL ?>";
    }

    <?php if (session("success")) { ?>
      alertify.success('<?= session("success") ?>');
      <?php } else {
      if (session("error")) { ?>
        alertify.error('<?= session("error") ?>');
    <?php }
    } ?>
  </script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="<?= $app->baseURL ?>public/waitme/waitMe.min.js"></script>
</body>

</html>