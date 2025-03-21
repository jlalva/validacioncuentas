<?php require_once APPPATH . 'Views/include/header.php' ?>
    <a href="<?=$app->baseURL?>usuarios" style="color: #111AD3;">Usuarios</a> / <a href="<?=$app->baseURL?>usuarios/add" style="color: #111AD3;">Nuevo</a>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-10">
                    <h6 class="mb-0 text-uppercase">Editar Usuario</h6>
                </div>
                <div class="col-md-2" style="text-align: right;">
                    <a href="<?=$app->baseURL?>usuarios" class="btn btn-warning btn-sm" style="color: #000;"><i class="fa fa-remove"></i> Cancelar</a>
                    <?php if(editar()){?>
                        <button class="btn btn-success btn-sm" type="submit" id="guardar"><i class="fa fa-edit"></i> Editar</button>
                    <?php }?>
                </div>
                <form>
                    <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <label>Nombres</label>
                        <input type="text" class="form-control" id="nombres" name="nombres" autocomplete="off" value="<?=$item->usu_nombre?>">
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label>Apellidos</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" autocomplete="off" value="<?=$item->usu_apellido?>">
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <label>Rol</label>
                        <select class="form-control" id="rol_id" name="rol_id" disabled="disabled">
                            <option value="0">Seleccione</option>
                            <?php foreach($roles as $row){
                                if($item->usu_rol_id == $row['rol_id']){?>
                                    <option value="<?=$row['rol_id']?>" selected><?=strtoupper($row['rol_nombre'])?></option>
                                <?php }else{?>
                                    <option value="<?=$row['rol_id']?>"><?=strtoupper($row['rol_nombre'])?></option>
                                <?php }}?>
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <label>Genero</label>
                        <select class="form-control" id="genero" name="genero">
                            <option value="0">Seleccione</option>
                            <?php if($item->usu_genero == 'M'){?>
                                <option value="M" selected>MASCULINO</option>
                            <?php }else{?>
                                <option value="M">MASCULINO</option>
                            <?php }?>
                            <?php if($item->usu_genero == 'F'){?>
                                <option value="F" selected>FEMENINO</option>
                            <?php }else{?>
                                <option value="F">FEMENINO</option>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <label>Correo</label>
                        <input type="text" class="form-control" id="correo" name="correo" autocomplete="off" value="<?=$item->usu_correo?>">
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <label>Usuario</label>
                        <input type="text" class="form-control" onkeyup="convertirMinusculas('usuario')" id="usuario" name="usuario" autocomplete="off" value="<?=$item->usu_usuario?>">
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <label>Clave</label>
                        <?php if (in_array(session('idrol'), [1, 2])) { ?>
                            <div class="col-md-12 col-sm-12 form-group has-feedback position-relative" id="show_hide_password">
                                <input type="password" class="form-control" id="clave" name="clave" autocomplete="off" value="<?= desencriptar($item->usu_clave) ?>">
                                <span class="bx bx-low-vision icono" aria-hidden="true" style="cursor: pointer; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);" onclick="togglePassword()"></span>
                            </div>
                        <?php } else { ?>
                            <input type="password" class="form-control" id="clave" readonly="readonly" name="clave" autocomplete="off" value="<?= desencriptar($item->usu_clave) ?>">
                        <?php } ?>
                    </div>
                    <div class="col-md-2 col-sm-2">
                        <label>Estado</label>
                        <select class="form-control" id="usu_estado" name="usu_estado">
                            <option value="">Seleccione</option>
                            <?php if($item->usu_estado == 0){?>
                                <option value="0" selected>INACTIVO</option>
                                <option value="1">ACTIVO</option>
                            <?php }else{?>
                                <option value="0">INACTIVO</option>
                                <option value="1" selected>ACTIVO</option>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-md-12 col-sm-12"><hr></div>
                    <form method="post" enctype="multipart/form-data" id="uploadForm" class="miarchivo">
                        <div class="col-md-3 col-sm-3">
                            <div class="profile_img">
                                <div id="crop-avatar">
                                    <?php if($item->usu_foto){?>
                                        <img width="220" class="img-responsive avatar-view" id="foto" name="foto" src="<?=$app->baseURL?>public/images/FOTOS_OFICIAL/<?=strtoupper($item->rol_nombre).'/'.$item->usu_foto?>" alt="Foto de perfil" title="Foto de perfil">
                                    <?php }else{?>
                                        <img width="220" class="img-responsive avatar-view" id="foto" name="foto" src="<?= $app->baseURL ?>public/images/user.png" alt="Foto de perfil" title="Foto de perfil">
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12"><hr></div>
                        <div class="col-md-12 col-sm-12">
                            <input type="file" id="imagenperfil" name="imagenperfil" accept=".jpg,.png">
                        </div>
                    </form>
                    </div>
                </form>
            </div>
        </div>
    </div>


<script>
    $(document).ready(function() {
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


        $('#correo').on('blur', function() {
            var inputValue = $(this).val();
            $("#usuario").val(inputValue)
        });

        var alertShown = false;
        $('#imagenperfil').change(function() {
            var file = this.files[0];
            if (file && file.type.match(/^image\//)) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $("#foto").attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
            }
        });

        $('#guardar').click(function(e) {
            alertify.dismissAll();
            var validaCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            e.preventDefault();
            var archivo = ($("#imagenperfil"))[0].files[0];
            var nombres = $("#nombres").val();
            var apellidos = $("#apellidos").val();
            var genero = $("#genero").val();
            var correo = $("#correo").val();
            var rol_id = $("#rol_id").val();
            var rol = $('#rol_id option:selected').text();
            var usuario = $("#usuario").val();
            var clave = $("#clave").val();

            if (nombres == '' || apellidos == '' || genero == 0 || correo == '' || rol_id == 0 || usuario == '' || clave == '') {
                alertify.error("Todos los campos son obligatorios");
                return;
            }

            if (!validaCorreo.test(correo)) {
                alertify.error("El correo no es válido");
                return;
            }

            var data = new FormData();
            data.append("file", archivo);
            data.append("nombres", nombres);
            data.append("apellidos", apellidos);
            data.append("genero", genero);
            data.append("correo", correo);
            data.append("rol_id", rol_id);
            data.append("rol", rol);
            data.append("usuario", usuario);
            data.append("clave", clave);
            $(".datosusuario").waitMe();
            $.ajax({
                url: url + "usuarios/update/<?=$id?>",
                type: "post",
                data: data,
                processData: false,
                contentType: false,
                error: function (e) {
                    $(".datosusuario").waitMe('hide');
                    alertify.error("Ocurrio un error inesperado");
                },
                success: function(result){
                    if(result == 1){
                        redireccionar();
                    }else{
                        $(".datosusuario").waitMe('hide');
                        if(result == 2){
                            alertify.error("Ocurrio un error al subir la imagen");
                        }else{
                            alertify.error("Ocurrio un error al actualizar los datos");
                        }
                    }
                }
            });
        });

    });

    function redireccionar(){
        alertify.success("Los datos se actualizaron correctamente");
        link = url + 'usuarios',
        setTimeout("window.location.href = link", 2500);
    }

    <?php if(session("success")){?>
        alertify.success('<?=session("success")?>');
    <?php }else{
        if(session("error")){?>
        alertify.error('<?=session("error")?>');
    <?php }}?>
</script>

<?php require_once APPPATH . 'Views/include/footer.php' ?>