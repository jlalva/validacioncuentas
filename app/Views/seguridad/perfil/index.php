<?php require_once APPPATH . 'Views/include/header.php' ?>
<div class="page-title">
    <div class="title_left">
        <h3>Perfil de Usuario</h3>
    </div>
</div>

<div class="clearfix"></div>

<style>
    .contenedor-imagen {
        position: relative;
        display: inline-block;
    }

    .icono {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .contenedor-imagen:hover .icono {
        opacity: 1;
        cursor: pointer;
    }

    .icono i {
        font-size: 24px;
        color: white;
        background-color: black;
        padding: 8px;
        border-radius: 50%;
    }
</style>

<div class="card">
    <div class="card-body">
        <div class="container">
            <div class="main-body">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <div class="profile_img">
                                    <div id="crop-avatar" class="contenedor-imagen">
                                        <?php if ($datos->usu_foto) { ?>
                                            <img width="250" height="220" class="img-responsive avatar-view" id="foto" name="foto" src="<?= $app->baseURL ?>public/images/FOTOS_OFICIAL/<?= strtoupper(session('rol')) . '/' . $datos->usu_foto ?>" alt="Foto de perfil" title="Foto de perfil">
                                        <?php } else { ?>
                                            <img width="250" height="220" class="img-responsive avatar-view" id="foto" name="foto" src="<?= $app->baseURL ?>public/images/user.png" alt="Foto de perfil" title="Foto de perfil">
                                        <?php } ?>
                                        <span class="icono" onclick="abrirBuscadorArchivos()"><i class="fa fa-camera"></i></span>
                                        <form method="post" enctype="multipart/form-data" id="uploadForm" class="miarchivo">
                                            <input type="file" id="archivo" style="display: none;" accept=".jpg,.png" />
                                        </form>
                                    </div>
                                </div>
                                <?php
                                $nombres = explode(" ", $datos->usu_nombre);
                                $apellidos = explode(" ", $datos->usu_apellido);
                                $nombre = $nombres[0];
                                $apellido_uno = $apellidos[0];
                                ?>
                                <h3><?= strtoupper($nombre . ' ' . $apellido_uno) ?></h3>
                                <ul class="list-unstyled user_data">
                                    <li><i class="fa fa-envelope user-profile-icon"></i> <?= $datos->usu_correo ?></li>
                                    <li><i class="fa fa-briefcase user-profile-icon"></i> <?= $datos->rol_nombre ?></li>
                                </ul>
                                <button class="btn btn-info" id="btnguardar" style="display: none;"><i class="fa fa-camera m-right-xs"></i> Cambiar Foto</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                <?php echo form_open($app->baseURL . 'usuarios/updateperfil');
                                function validate(string $key)
                                {
                                    if (session('_ci_validation_errors')) {
                                        $value = session('_ci_validation_errors');
                                        if (isset($value[$key])) {
                                            return $value[$key];
                                        }
                                    }
                                }
                                ?>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <ul class="nav nav-tabs nav-success" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#tab_content1" role="tab" aria-selected="true">
                                                    <div class="d-flex align-items-center">
                                                        <div class="tab-icon"><i class='bx bx-list-ol font-18 me-1'></i>
                                                        </div>
                                                        <div class="tab-title">Datos Personales</div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" data-bs-toggle="tab" href="#tab_content2" role="tab" aria-selected="false">
                                                    <div class="d-flex align-items-center">
                                                        <div class="tab-icon"><i class='bx bx-arrow-to-top font-18 me-1'></i>
                                                        </div>
                                                        <div class="tab-title">Acceso</div>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!--<div class="col-md-3 col-sm-3">
                                        <button type="submit" style="float: right;" class="btn btn-success"><i class="fa fa-edit m-right-xs"></i> Editar Datos</button>
                                    </div>-->
                                </div>
                                <div id="myTabContent" class="tab-content">
                                    <div role="tabpanel" class="tab-pane active " id="tab_content1" aria-labelledby="home-tab">
                                        <div class="col-md-12">
                                            <div class="x_panel">
                                                <div class="x_content">
                                                    <div class="col-md-6 col-sm-6">
                                                        <label>Nombres</label>
                                                        <input type="text" class="form-control" id="nombres" readonly="readonly" value="<?= strtoupper($datos->usu_nombre) ?>">
                                                        <input type="hidden" id="usu_id" name="usu_id" value="<?=$datos->usu_id?>">
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <label>Apellidos</label>
                                                        <input type="text" class="form-control" id="apellidos" readonly="readonly" value="<?= strtoupper($datos->usu_apellido) ?>">
                                                    </div>
                                                    <div class="col-md-4 col-sm-4">
                                                        <label>Genero</label>
                                                        <?php
                                                        $genero = 'Femenino';
                                                        if ($datos->usu_genero == 'M') {
                                                            $genero = 'Masculino';
                                                        }
                                                        ?>
                                                        <input type="text" class="form-control" id="genero" readonly="readonly" value="<?= strtoupper($genero) ?>">
                                                    </div>
                                                    <div class="col-md-4 col-sm-4">
                                                        <label>Correo</label>
                                                        <input type="text" class="form-control" id="correo" readonly="readonly" value="<?= $datos->usu_correo ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                                        <div class="col-md-12">
                                            <div class="x_panel">
                                                <div class="x_content">
                                                    <div class="col-md-4 col-sm-4">
                                                        <label>Rol</label>
                                                        <input type="text" class="form-control" id="rol" readonly="readonly" value="<?= strtoupper($datos->rol_nombre) ?>">
                                                    </div>
                                                    <div class="col-md-4 col-sm-4">
                                                        <label>Usuario</label>
                                                        <input type="text" class="form-control" id="usuario" readonly="readonly" value="<?= $datos->usu_usuario ?>">
                                                    </div>
                                                    <div class="col-md-4 col-sm-4">
                                                        <label>Clave</label>
                                                        <input type="password" class="form-control" id="clave" readonly="readonly" value="<?= base64_decode($datos->usu_clave) ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#archivo').change(function() {
            var file = this.files[0];
            if (file && file.type.match(/^image\//)) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $("#foto").attr('src', e.target.result);
                    $("#btnguardar").removeAttr("style");
                }
                reader.readAsDataURL(file);
            }
        });

        $('#telefono').on('input', function() {
            var inputValue = $(this).val();
            $(this).val(inputValue.replace(/[^0-9]/g, '').substring(0, 9));
        });

        $('#dni').on('input', function() {
            var inputValue = $(this).val();
            $(this).val(inputValue.replace(/[^0-9]/g, '').substring(0, 8));
        });

        $('#btnguardar').click(function(e) {
            e.preventDefault();
            alertify.dismissAll();
            var archivo = ($("#archivo"))[0].files[0];
            var usu_id = $("#usu_id").val();
            var data = new FormData();
            data.append("file", archivo);
            data.append("usu_id", usu_id);
            $.ajax({
                url: url + "usuarios/updatefoto",
                type: "post",
                data: data,
                processData: false,
                contentType: false,
                error: function(e) {
                    alertify.error("Ocurrio un error inesperado");
                },
                success: function(result) {
                    if (result == 1) {
                        redireccionar();
                    } else {
                        if (result == 2) {
                            alertify.error("Ocurrio un error al subir la imagen");
                        } else {
                            alertify.error("Ocurrio un error al actualizar los datos");
                        }
                    }
                }
            });
        });

        $('#departamento').change(function() {
            var departamento = $("#departamento").val();
            if (departamento != 0) {
                $.ajax({
                    url: url + "usuarios/provincia",
                    method: 'POST',
                    data: {
                        departamento: departamento
                    },
                    success: function(response) {
                        $("#provincia").html(response)
                        $("#distrito").html('<option value="0">Seleccione</option>')
                    },
                    error: function(xhr, status, error) {
                        // Manejar errores de la solicitud
                        console.error('Error en la solicitud:', error);
                    }
                });
            }
        });

        $('#provincia').change(function() {
            var departamento = $("#departamento").val();
            var provincia = $("#provincia").val();
            if (departamento != 0 && provincia != 0) {
                alertShown = false;
                $.ajax({
                    url: url + "usuarios/distrito",
                    method: 'POST',
                    data: {
                        departamento: departamento,
                        provincia: provincia
                    },
                    success: function(response) {
                        $("#distrito").html(response)
                    },
                    error: function(xhr, status, error) {
                        // Manejar errores de la solicitud
                        console.error('Error en la solicitud:', error);
                    }
                });
            }
        });

    });

    function redireccionar() {
        alertify.success("La imágen se actualizó correctamente");
        setTimeout("location.reload()", 2500);
    }

    function abrirBuscadorArchivos() {
        document.getElementById('archivo').click();
    }

    <?php if (session("success")) { ?>
        alertify.success('<?= session("success") ?>');
        <?php } else {
        if (session("error")) { ?>
            alertify.error('<?= session("error") ?>');
        <?php }
    }
    if (session("llenar")) { ?>
        alertify.confirm("IMPORTANTE", "<?= session("llenar") ?>", function() {}, function() {
            console.log('Cancelado');
        }).set('labels', {
            ok: 'Ok',
        }).set('buttonReverse', true).set('confirmButtonText', 'Aceptar');

        // Modificar el estilo del cuadro de diálogo de confirmación
        $('.ajs-header').css('background-color', '#f44336');
        $('.ajs-button.ajs-ok').css('background-color', '#4CAF50');
        $('.ajs-button').css('color', 'white');
        $('.ajs-dialog').css('width', '320px');
        $('.ajs-header').css('padding', '10px');
        $('.ajs-content').css('padding', '10px');
    <?php
    }
    ?>
</script>

<?php require_once APPPATH . 'Views/include/footer.php' ?>