<?php
use Config\App;
$app = new App();
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="<?=$app->baseURL?>public/assets/images/favicon-32x32.png" type="image/png" />
    <!--plugins-->
    <link href="<?=$app->baseURL?>public/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="<?=$app->baseURL?>public/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="<?=$app->baseURL?>public/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="<?=$app->baseURL?>public/assets/css/pace.min.css" rel="stylesheet" />
    <script src="<?=$app->baseURL?>public/assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="<?=$app->baseURL?>public/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=$app->baseURL?>public/assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link href="<?=$app->baseURL?>public/assets/css/app.css" rel="stylesheet">
    <link href="<?=$app->baseURL?>public/assets/css/icons.css" rel="stylesheet">

    <link rel="stylesheet" href="<?=$app->baseURL?>public/alertify/alertify.min.css"/>
    <link rel="stylesheet" href="<?=$app->baseURL?>public/alertify/default.min.css"/>
    <link rel="stylesheet" href="<?=$app->baseURL?>public/alertify/semantic.min.css"/>
    <link rel="stylesheet" href="<?=$app->baseURL?>public/alertify/bootstrap.min.css"/>

    <title>Iniciar Sesión</title>
    <script src = "<?=$app->baseURL?>public/query/jquery-1.10.2.js"></script>
</head>

<body class="">
    <!--wrapper-->
    <div class="wrapper">
    <div class="section-authentication-cover">
    <div class="">
        <div class="row g-0">
            <div class="col-12 col-xl-7 col-xxl-8 auth-cover-left align-items-center justify-content-center d-none d-xl-flex">
                <div class="card shadow-none bg-transparent shadow-none rounded-0 mb-0">
                    <div class="card-body">
                        <img src="<?=$app->baseURL?>public/images/FOTO_EMPRESA/<?=fondo()?>" class="img-fluid" width="650" alt="" />
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-5 col-xxl-4 auth-cover-right align-items-center justify-content-center">
                <div class="card rounded-0 m-3 shadow-none bg-transparent mb-0">
                    <div class="card-body p-sm-5">
                        <div class="">
                            <div class="mb-3 text-center">
                                <img src="<?=$app->baseURL?>public/assets/images/logo-icon.png" width="60" alt="">
                            </div>
                            <div class="text-center mb-4">
                                <h5 class="">BIENVENIDO A FACTURACION PERU</h5>
                                <p class="mb-0">Ingresa tu cuenta</p>
                            </div>
                            <div class="form-body">
                                <form class="row g-3" onsubmit="return false;">
                                    <div class="col-12">
                                        <label for="usuario" class="form-label">Usuario</label>
                                        <input class="form-control" id="usuario" name="usuario">
                                    </div>
                                    <div class="col-12">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-group" id="show_hide_password">
                                            <input type="password" class="form-control border-end-0" id="password" name="password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class="bx bx-hide"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-end"> <a href="<?=$app->baseURL?>recuperar">¿Has olvidado tu contraseña?</a>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button onclick="ingresar()" id="btningresar" class="btn btn-primary">Iniciar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



    </div>
    <script src="<?=$app->baseURL?>public/alertify/alertify.min.js"></script>
    <script src="<?=$app->baseURL?>public/assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?=$app->baseURL?>public/assets/js/jquery.min.js"></script>
    <script src="<?=$app->baseURL?>public/assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="<?=$app->baseURL?>public/assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="<?=$app->baseURL?>public/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script>
        $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
        });
    </script>
    <script src="<?=$app->baseURL?>public/pagejs/login.js"></script>
</body>
</html>