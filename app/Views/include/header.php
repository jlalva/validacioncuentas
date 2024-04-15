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
	<link href="<?=$app->baseURL?>public/assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
	<!-- loader-->
	<link href="<?=$app->baseURL?>public/assets/css/pace.min.css" rel="stylesheet" />
	<script src="<?=$app->baseURL?>public/assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="<?=$app->baseURL?>public/assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?=$app->baseURL?>public/assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
	<link href="<?=$app->baseURL?>public/assets/css/app.css" rel="stylesheet">
	<link href="<?=$app->baseURL?>public/assets/css/icons.css" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="<?=$app->baseURL?>public/assets/css/dark-theme.css" />
	<link rel="stylesheet" href="<?=$app->baseURL?>public/assets/css/semi-dark.css" />
	<link rel="stylesheet" href="<?=$app->baseURL?>public/assets/css/header-colors.css" />
	<title><?=$titulo?></title>
	<link rel="stylesheet" href="<?=$app->baseURL?>public/alertify/alertify.min.css"/>
    <link rel="stylesheet" href="<?=$app->baseURL?>public/alertify/default.min.css"/>
    <link rel="stylesheet" href="<?=$app->baseURL?>public/alertify/semantic.min.css"/>
    <link rel="stylesheet" href="<?=$app->baseURL?>public/alertify/bootstrap.min.css"/>
    <!--<script src="<?=$app->baseURL?>public/js/jquery.js"></script>-->
	<script src="<?=$app->baseURL?>public/assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="<?=$app->baseURL?>public/assets/js/jquery.min.js"></script>
    <script src = "<?=$app->baseURL?>public/query/jquery-1.10.2.js"></script>
    <script src = "<?=$app->baseURL?>public/query/jquery-ui.js"></script>
    <script src="<?=$app->baseURL?>public/pagejs/funciones.js"></script>
	<script src="<?=$app->baseURL?>public/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
	<script src="<?=$app->baseURL?>public/assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
    <link href="<?=$app->baseURL?>public/waitme/waitMe.css" rel="stylesheet">
    <script>
       url =  "<?=$app->baseURL?>";
    </script>
</head>

<body>
	<div class="wrapper">
		<!--sidebar wrapper -->
		<div class="sidebar-wrapper" data-simplebar="true">
			<div class="sidebar-header">
				<!--<div>
					<img src="<?=$app->baseURL?>public/assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
				</div>-->
				<div>
					<h4 class="logo-text">SISTEMA</h4>
				</div>
				<div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
				</div>
			</div>
			<!--navigation-->
			<ul class="metismenu" id="menu">
                <li>
					<a href="<?=$app->baseURL?>inicio">
						<div class="parent-icon"><i class='bx bx-home-circle'></i></div>
						<div class="menu-title">Inicio</div>
					</a>
				</li>
				<?=menu()?>
			</ul>
			<!--end navigation-->
		</div>

		<header>
			<div class="topbar d-flex align-items-center">
				<nav class="navbar navbar-expand">
					<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
					</div>
					<div class="top-menu ms-auto">
					</div>
					<div class="user-box dropdown">
						<a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<img src="<?=$app->baseURL?>public/assets/images/avatars/avatar-2.png" class="user-img" alt="user avatar">
							<div class="user-info ps-3">
								<p class="user-name mb-0"><?=session('nombre').' '. session('apellido_uno') ?></p>
								<p class="designattion mb-0"><?=session('rol')?></p>
							</div>
						</a>
						<ul class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="<?=$app->baseURL?>perfil"> <i class="bx bx-user"></i><span>Perfil</span></a>
                            <a class="dropdown-item" href="<?=$app->baseURL?>salir"><i class="bx bx-log-out-circle"></i><span>Salir</span></a>
						</ul>
					</div>
				</nav>
			</div>
		</header>

		<div class="page-wrapper">
			<div class="page-content">

<!--
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SISTEMA DE ENCUESTAS</title>

    <link rel="icon" href="<?=$app->baseURL?>public/images/favicon.ico">
    <link href="<?=$app->baseURL?>public/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=$app->baseURL?>public/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?=$app->baseURL?>public/vendors/nprogress/nprogress.css" rel="stylesheet">
    <link href="<?=$app->baseURL?>public/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <link href="<?=$app->baseURL?>public/build/css/custom.min.css" rel="stylesheet">
    <link href="<?=$app->baseURL?>public/alertify/alertify.min.css" rel="stylesheet">
    <link href="<?=$app->baseURL?>public/alertify/default.min.css" rel="stylesheet">
    <link href="<?=$app->baseURL?>public/waitme/waitMe.css" rel="stylesheet">
    <script src="<?=$app->baseURL?>public/alertify/alertify.min.js"></script>
    <script src="<?=$app->baseURL?>public/query/jquery-1.10.2.js"></script>
    <script src="<?=$app->baseURL?>public/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?=$app->baseURL?>public/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="<?=$app->baseURL?>public/build/js/funciones.js"></script>
    <script>
       url =  "<?=$app->baseURL?>";
    </script>
</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title text-center" style="border: 0;">
                        <a href="#" class="site_title"><i class="fa fa-mortar-board"></i><span> ENCUESTAS</span></a>
                    </div>
                    <div class="clearfix"></div>
                    <div class="profile clearfix">
                        <div class="profile_pic">
                            <?php if(session('foto')){?>
                                <img src="<?=$app->baseURL?>public/images/FOTOS_OFICIAL/<?=strtoupper(session('rol')).'/'.session('foto')?>" class="img-circle profile_img">
                            <?php }else{?>
                                <?php if(in_array(session('idrol'), [1, 2])){
                                    $foto = 'admin.jpeg';
                                ?>
                                    <img class="img-circle profile_img" src="<?=$app->baseURL?>public/images/admin.jpeg">
                                <?php }else{
                                    if(session('idrol') == 3){
                                        $foto = 'docente_femenino.jpeg';
                                        if (session('genero') == 'M') {
                                            $foto = 'docente_masculino.jpeg';
                                        }
                                    }else{
                                        if(session('idrol') == 4){
                                            $foto = 'estudiante_femenino.jpeg';
                                            if (session('genero') == 'M') {
                                                $foto = 'estudiante_masculino.jpeg';
                                            }
                                        }else{
                                            if(session('idrol') == 6){
                                                $foto = 'secretaria.jpeg';
                                            }
                                        }
                                    }
                                ?>
                                <img class="img-circle profile_img" src="<?=$app->baseURL?>public/images/<?=$foto?>" alt="Foto de perfil" title="Foto de perfil">
                            <?php }}?>
                        </div>
                        <div class="profile_info">
                            <span>Bienvenido,</span>
                            <h2 style="background-color: #1A73E8;"><?=session('rol')?></h2>
                            <h2><?=strtoupper(session('nombre')).' '.strtoupper(session('apellido_uno'))?></h2>
                        </div>
                    </div>
                    <hr style="background: #EDEDED;">
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <h3>General</h3>
                            <ul class="nav side-menu">
                                <li><a href="<?=$app->baseURL?>inicio"><i class="fa fa-home"></i> Inicio</a></li>
                                <?=menu()?>
                                <li><a href="<?=$app->baseURL?>perfil"><i class="fa fa-user"></i> Perfil</a></li>
                                <li><a href="<?=$app->baseURL?>salir"><i class="fa fa-sign-out"></i> Salir</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="top_nav">
                <div class="nav_menu">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>
                    <nav class="nav navbar-nav" style="position: relative;">
                        <div class="bloquelogo" style="position: absolute;top: 5px;">
                            <img class="img-responsive avatar-view" src="<?=$app->baseURL?>public/images/FOTO_EMPRESA/<?=logo()?>" alt="Foto de logo" title="Foto de logo">
                        </div>
                        <div class="vertical-line" style="position: absolute;margin-left: -12px;"></div>
                        <ul class=" navbar-right" style="margin-top: 0;">
                            <li class="nav-item dropdown open" style="padding-left: 15px;margin-top: 5px;">
                                <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                                <?php if(session('foto')){?>
                                    <img src="<?=$app->baseURL?>public/images/FOTOS_OFICIAL/<?=strtoupper(session('rol')).'/'.session('foto')?>">
                                <?php }else{?>
                                    <img src="<?=$app->baseURL?>public/images/<?=$foto?>">
                                <?php }?>
                                <?=strtoupper(session('nombre').' '.session('apellido_uno'))?>
                                </a>
                                <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="<?=$app->baseURL?>perfil"> Perfil</a>
                                    <a class="dropdown-item" href="<?=$app->baseURL?>salir"><i class="fa fa-sign-out pull-right"></i> Salir</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown open vertical-line"></li>
                            <li class="nav-item dropdown open" style="color:#1A73E8;cursor:pointer">
                                <i class="fa fa-globe fa-3x" onclick="webempresa('<?=web()?>')"></i>
                            </li>
                            <li class="nav-item dropdown open" style="margin-top: 12px;float: left;margin-left: 200px;font-size: 18px;">
                                <b><?=razonsocial().' - '.siglas()?></b>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="right_col" role="main">
                <div class="">
                    <div class="page-title">
                        <div class="title_left">
                        </div>
                    </div>-->