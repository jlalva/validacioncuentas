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
		<div class="sidebar-wrapper" data-simplebar="true">
			<div class="sidebar-header">
				<div>
					<h4 class="logo-text">SISTEMA</h4>
				</div>
				<div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
				</div>
			</div>
			<ul class="metismenu" id="menu">
                <li>
					<a href="<?=$app->baseURL?>inicio">
						<div class="parent-icon"><i class='bx bx-home-circle'></i></div>
						<div class="menu-title">Inicio</div>
					</a>
				</li>
				<?=menu()?>
			</ul>
		</div>
		<header>
			<div class="topbar d-flex align-items-center">
				<nav class="navbar navbar-expand">
					<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
					</div>
					<div class="top-menu ms-auto">
						<b style="font-size: 20px;">
						<?php
							$empresaActiva = empresaActiva();
							echo $empresaActiva->emp_razonsocial;
						?>
						</b>
					</div>
                    <div class="top-menu ms-auto">
						<ul class="navbar-nav align-items-center">
							<li class="nav-item text-center"></li>
							<li class="nav-item dropdown dropdown-large">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="alert-count"><?=count(selEmpresas())?></span>
									<i class='bx bx-buildings'></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<p class="msg-header-title">Empresas</p>
											<p class="msg-header-clear ms-auto">Seleccione una empresa</p>
										</div>
									</a>
									<div class="header-notifications-list">
                                        <?php $itemE = selEmpresas();
                                            foreach ($itemE as $rowE){?>
                                                <a class="dropdown-item text-start" href="javascript:;">
                                                    <div class="d-flex">
														<?php if($rowE->emp_id == $empresaActiva->emp_id){?>
                                                        	<input type="radio" name="empresa_seleccionada" checked id="empresa_<?=$rowE->emp_id?>" onclick="empresaactiva(<?=$rowE->emp_id?>)">
														<?php }else{?>
															<input type="radio" name="empresa_seleccionada" id="empresa_<?=$rowE->emp_id?>" onclick="empresaactiva(<?=$rowE->emp_id?>)">
														<?php }?>
                                                        <div class="flex-grow-1">
                                                            <h6 class="msg-name">&nbsp;<?=$rowE->emp_razonsocial?></h6>
                                                        </div>
                                                    </div>
                                                </a>
                                        <?php }?>
									</div>
								</div>
							</li>
						</ul>
					</div>
					<div class="user-box dropdown">
						<a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						<?php if(session('foto')){?>
                                <img src="<?=$app->baseURL?>public/images/FOTOS_OFICIAL/<?=strtoupper(session('rol')).'/'.session('foto')?>" class="user-img">
                            <?php }else{?>
                                <?php if(in_array(session('idrol'), [1, 2])){
                                    $foto = 'admin.jpeg';
                                ?>
                                    <img class="user-img" src="<?=$app->baseURL?>public/images/user.png">
                                <?php }else{
									$foto = 'user.png';
                                ?>
                                <img class="user-img" src="<?=$app->baseURL?>public/images/<?=$foto?>" alt="Foto de perfil" title="Foto de perfil">
                            <?php }}?>	
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