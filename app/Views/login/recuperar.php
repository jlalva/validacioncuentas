<?php
use Config\App;
$app = new App();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?=$app->baseURL?>public/assets/images/favicon-32x32.png" type="image/png" />
	<link href="<?= $app->baseURL ?>public/assets/css/pace.min.css" rel="stylesheet" />
	<script src="<?= $app->baseURL ?>public/assets/js/pace.min.js"></script>
	<link href="<?= $app->baseURL ?>public/assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= $app->baseURL ?>public/assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
	<link href="<?= $app->baseURL ?>public/assets/css/app.css" rel="stylesheet">
	<link href="<?= $app->baseURL ?>public/assets/css/icons.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= $app->baseURL ?>public/alertify/alertify.min.css" />
  <link rel="stylesheet" href="<?= $app->baseURL ?>public/alertify/default.min.css" />
  <link rel="stylesheet" href="<?= $app->baseURL ?>public/alertify/semantic.min.css" />
  <link rel="stylesheet" href="<?= $app->baseURL ?>public/alertify/bootstrap.min.css" />
	<title>Recuperar Contraseña</title>
  <script src="<?= $app->baseURL ?>public/query/jquery-1.10.2.js"></script>
</head>

<body>
	<div class="wrapper">
		<div class="authentication-reset-password d-flex align-items-center justify-content-center">
		 <div class="container">
			<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
				<div class="col mx-auto">
					<div class="card">
						<div class="card-body formvalida">
							<div class="p-4">
								<div class="text-start mb-4">
									<h5 class="">Restablecer Contraseña</h5>
									<p class="mb-0">Ingrese su correo institucional o correo personal para generar un código de validación</p>
								</div>
								<div class="mb-3 mt-4">
                  <label class="form-label">Correo Electrónico</label>
                  <div class="input-group">
                      <input type="email" class="form-control" placeholder="Ingrese su correo" name="correo" id="correo" autocomplete="off">
                      <button class="btn btn-success" id="enviar"><i class="bx bx-send"></i></button>
                  </div>
                </div>
								<div class="mb-4">
									<label class="form-label">Código de Validación</label>
									<input class="form-control" placeholder="Ingrese código" name="codigo" id="codigo"/>
								</div>
                <div class="d-grid gap-2">
									<button type="button" class="btn btn-primary" onclick="validarCodigo()">Validar</button>
                  <a href="<?=$app->baseURL?>" class="btn btn-light"><i class='bx bx-arrow-back mr-1'></i>Iniciar Sesión</a>
								</div>
							</div>
						</div>

            <div class="card-body formnclave">
              <h1>Nueva Contraseña</h1>
              <div class="col-md-12 col-sm-12 form-group has-feedback">
                <input type="text" class="form-control" placeholder="Correo" name="ccorreo" id="ccorreo" autocomplete="of" readonly="true" />
                <input type="hidden" id="idusuario" name="idusuario">
              </div>
              <div class="col-md-12 col-sm-12 form-group has-feedback" id="show_hide_password">
                <input type="password" class="form-control" placeholder="Nueva Contraseña" name="clave" id="clave" autocomplete="of
                  " />
                <span class="fa fa-eye-slash form-control-feedback right icono" aria-hidden="true" style="cursor: pointer;"></span>
              </div>
              <div class="col-md-12 col-sm-12 form-group has-feedback" id="show_hide_password_dos">
                <input type="password" class="form-control" placeholder="Repite tu contraseña" name="repiteclave" id="repiteclave" autocomplete="of
                  " />
                <span class="fa fa-eye-slash form-control-feedback right icono" aria-hidden="true" style="cursor: pointer;"></span>
              </div>
              <div class="clearfix"></div>
              <br />
              <div>
                <button class="btn btn-success" onclick="cambiarClave()">Guardar</button>
                <a class="reset_pass" href="<?= $app->baseURL ?>">Iniciar Sesión</a>
              </div>
            </div>
					</div>
				</div>
			</div>
		  </div>
		</div>
	</div>
</body>
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
          url: '<?= $app->baseURL ?>validarcorreo',
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
      } else {
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
          url: '<?= $app->baseURL ?>validarcodigo',
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
          url: '<?= $app->baseURL ?>actualizarclave',
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
</html>