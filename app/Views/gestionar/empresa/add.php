<?php require_once APPPATH . 'Views/include/header.php' ?>
<style>
    .imageContainer {
        width: 100%;
        height: 200px;
        overflow: hidden; /* Oculta las partes de las imágenes que excedan el tamaño del div */
    }
    .imageContainer img {
      max-width: 100%; /* Máximo ancho de la imagen */
      max-height: 100%; /* Máximo alto de la imagen */
      object-fit: cover; /* Ajusta la imagen proporcionalmente dentro del div */
    }
</style>
        <a href="<?=$app->baseURL?>empresa" style="color: #111AD3;"><u>Empresa</u></a>
        <div class="card">
            <div class="card-body">
                <?php
                    //form_open($app->baseURL.'usuarios/register');
                    function validate(string $key){
                        if(session('_ci_validation_errors')){
                            $value = session('_ci_validation_errors');
                            if(isset($value[$key])){
                                return $value[$key];
                            }
                        }
                    }
                ?>
                <div class="row">
                    <div class="col-md-10">
                        <h6 class="mb-0 text-uppercase">Agregar Empresa</h6>
                    </div>
                    <div class="col-md-1">
                        <a href="<?=$app->baseURL?>empresa" class="btn btn-warning btn-sm" style="color: #000;margin-top:-7px;"><i class="fa fa-remove"></i> Cancelar </a>
                    </div>
                    <div class="col-md-1">
                        <?php if (agregar()) { ?>
                            <button class="btn btn-primary btn-sm" type="submit" id="guardar" style="margin-top:-7px;"><i class="fa fa-save"></i> Guardar</button>
                        <?php } ?>
                    </div>
                    <div class="col-md-12"><hr></div>
                    <form>
                        <div class="row">
                        <div class="col-md-2 col-sm-2">
                            <label>RUC</label>
                            <input type="text" class="form-control" id="emp_ruc" name="emp_ruc" autocomplete="off">
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <label>Razón Social</label>
                            <input type="text" class="form-control" onkeyup="convertirMayusculas('emp_razonsocial')" id="emp_razonsocial" name="emp_razonsocial" autocomplete="off">
                        </div>
                        <div class="col-md-2 col-sm-2">
                            <label>Siglas</label>
                            <input type="text" class="form-control" onkeyup="convertirMayusculas('emp_siglas')" id="emp_siglas" name="emp_siglas" autocomplete="off">
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <label>Teléfono</label>
                            <input type="text" class="form-control" id="emp_telefono" name="emp_telefono" autocomplete="off">
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <label>Departamento</label>
                            <select class="form-control" id="departamento" name="departamento">
                            <option value="0">SELECCIONE</option>
                                <?php foreach($departamentos as $row){?>
                                    <option value="<?=$row->dpto?>"><?=strtoupper($row->dpto)?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <label>Provincia</label>
                            <select class="form-control" id="provincia" name="provincia">
                                <option value="0">SELECCIONE</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <label>Distrito</label>
                            <select class="form-control" id="distrito" name="distrito">
                            <option value="0">SELECCIONE</option>
                            </select>
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <label>Dirección</label>
                            <input type="text" class="form-control" onkeyup="convertirMayusculas('emp_direccion')" id="emp_direccion" name="emp_direccion" autocomplete="off">
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <label>Fecha Fundación</label>
                            <input type="date" class="form-control" id="emp_fechafundacion" name="emp_fechafundacion" autocomplete="off">
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <label>Sitio Web</label>
                            <input type="text" class="form-control" id="emp_sitioweb" name="emp_sitioweb" autocomplete="off">
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <label>Facebook</label>
                            <input type="text" class="form-control" id="emp_facebook" name="emp_facebook" autocomplete="off">
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <label>Youtube</label>
                            <input type="text" class="form-control" id="emp_youtube" name="emp_youtube" autocomplete="off">
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <label>Instagram</label>
                            <input type="text" class="form-control" id="emp_instagram" name="emp_instagram" autocomplete="off">
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <label>Twitter</label>
                            <input type="text" class="form-control" id="emp_twitter" name="emp_twitter" autocomplete="off">
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <label>Correo</label>
                            <input type="text" class="form-control" id="emp_correo" name="emp_correo" autocomplete="off">
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <label>Descripción</label>
                            <input type="text" class="form-control" onkeyup="convertirMayusculas('emp_descripcion')" id="emp_descripcion" name="emp_descripcion" autocomplete="off">
                        </div>
                        <div class="col-md-12 col-sm-12"><hr></div>
                        <div class="col-md-6 col-sm-6">
                            <h4>Imágen de logo</h4>
                            <form method="post" enctype="multipart/form-data" id="logo" class="logo">
                                <div class="imageContainer">
                                    <img class="img-responsive avatar-view" id="previewlogo" name="previewlogo" src="<?=$app->baseURL?>public/images/empresa.png">
                                </div>
                                <div class="col-md-12 col-sm-12"><hr></div>
                                <div class="col-md-12 col-sm-12">
                                    <input type="file" id="imglogo" name="imglogo" accept=".jpg,.png">
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <h4>Imágen de marca de agua</h4>
                            <form method="post" enctype="multipart/form-data" id="marcaagua" class="marcaagua">
                                <div class="imageContainer">
                                    <img class="img-responsive avatar-view" id="previewmarcaagua" name="previewmarcaagua" src="<?=$app->baseURL?>public/images/empresa.png">
                                </div>
                                <div class="col-md-12 col-sm-12"><hr></div>
                                <div class="col-md-12 col-sm-12">
                                    <input type="file" id="imgmarcaagua" name="imgmarcaagua" accept=".jpg,.png">
                                </div>
                            </form>
                        </div>
                        <div class="col-md-12 col-sm-12"><hr></div>
                        <div class="col-md-6 col-sm-6">
                            <h4>Imágen de fondo</h4>
                            <form method="post" enctype="multipart/form-data" id="fondo" class="fondo">
                                <div class="imageContainer">
                                    <img class="img-responsive avatar-view" id="previewfondo" name="previewfondo" src="<?=$app->baseURL?>public/images/empresa.png">
                                </div>
                                <div class="col-md-12 col-sm-12"><hr></div>
                                <div class="col-md-12 col-sm-12">
                                    <input type="file" id="imgfondo" name="imgfondo" accept=".jpg,.png">
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <h4>Imágen de banner</h4>
                            <form method="post" enctype="multipart/form-data" id="banner" class="banner">
                                <div class="imageContainer">
                                    <img class="img-responsive avatar-view" id="previewbanner" name="previewbanner" src="<?=$app->baseURL?>public/images/empresa.png">
                                </div>
                                <div class="col-md-12 col-sm-12"><hr></div>
                                <div class="col-md-12 col-sm-12">
                                    <input type="file" id="imgbanner" name="imgbanner" accept=".jpg,.png">
                                </div>
                            </form>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<script>
$(document).ready(function() {

    $('#emp_ruc').on('input', function() {
        var inputValue = $(this).val();
        $(this).val(inputValue.replace(/[^0-9]/g, '').substring(0, 11));
    });

    $('#emp_telefono').on('input', function() {
        var inputValue = $(this).val();
        $(this).val(inputValue.replace(/[^0-9]/g, '').substring(0, 9));
    });

    $('#imglogo').change(function() {
        var file = this.files[0];
        if (file && file.type.match(/^image\//)) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $("#previewlogo").attr('src', e.target.result);
        }
        reader.readAsDataURL(file);
        }
    });

    $('#imgmarcaagua').change(function() {
        var file = this.files[0];
        if (file && file.type.match(/^image\//)) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $("#previewmarcaagua").attr('src', e.target.result);
        }
        reader.readAsDataURL(file);
        }
    });

    $('#imgfondo').change(function() {
        var file = this.files[0];
        if (file && file.type.match(/^image\//)) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $("#previewfondo").attr('src', e.target.result);
        }
        reader.readAsDataURL(file);
        }
    });

    $('#imgbanner').change(function() {
        var file = this.files[0];
        if (file && file.type.match(/^image\//)) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $("#previewbanner").attr('src', e.target.result);
        }
        reader.readAsDataURL(file);
        }
    });

    $('#departamento').change(function() {
        var departamento = $("#departamento").val();
        if(departamento != 0){
            $.ajax({
                url: url + "usuarios/provincia",
                method: 'POST',
                data: {departamento:departamento},
                success: function(response) {
                    $("#provincia").html(response)
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
        if(departamento != 0 && provincia != 0){
            $.ajax({
                url: url + "usuarios/distrito",
                method: 'POST',
                data: {departamento:departamento, provincia: provincia},
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

    $('#guardar').click(function(e) {
        alertify.dismissAll();
        var validaCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        e.preventDefault();
        var logo = ($("#imglogo"))[0].files[0];
        var fondo = ($("#imgfondo"))[0].files[0];
        var banner = ($("#imgbanner"))[0].files[0];
        var marcaagua = ($("#imgmarcaagua"))[0].files[0];
        var emp_ruc = $("#emp_ruc").val();
        var emp_razonsocial = $("#emp_razonsocial").val();
        var emp_siglas = $("#emp_siglas").val();
        var emp_telefono = $("#emp_telefono").val();
        var departamento = $("#departamento").val();
        var provincia = $("#provincia").val();
        var distrito = $("#distrito").val();
        var emp_direccion = $("#emp_direccion").val();
        var emp_fechafundacion = $("#emp_fechafundacion").val();
        var emp_sitioweb = $("#emp_sitioweb").val();
        var emp_facebook = $("#emp_facebook").val();
        var emp_youtube = $("#emp_youtube").val();
        var emp_instagram = $("#emp_instagram").val();
        var emp_twitter = $("#emp_twitter").val();
        var emp_correo = $("#emp_correo").val();
        var emp_descripcion = $("#emp_descripcion").val();
        var data = new FormData();
        data.append("logo", logo);
        data.append("fondo", fondo);
        data.append("banner", banner);
        data.append("marcaagua", marcaagua);
        data.append("emp_ruc", emp_ruc);
        data.append("emp_razonsocial", emp_razonsocial);
        data.append("emp_siglas", emp_siglas);
        data.append("emp_telefono", emp_telefono);
        data.append("distrito", distrito);
        data.append("emp_direccion", emp_direccion);
        data.append("emp_fechafundacion", emp_fechafundacion);
        data.append("emp_descripcion", emp_descripcion);
        data.append("emp_sitioweb", emp_sitioweb);
        data.append("emp_facebook", emp_facebook);
        data.append("emp_youtube", emp_youtube);
        data.append("emp_instagram", emp_instagram);
        data.append("emp_twitter", emp_twitter);
        data.append("emp_correo", emp_correo);
        if(emp_ruc == '' || emp_razonsocial == '' || emp_siglas == '' || emp_telefono == '' || distrito == 0 || emp_descripcion == '' || emp_fechafundacion == '' || emp_descripcion == '') {
            alertify.warning("Llenar todos los campos para guardar.");
            return;
        }else{
            if(logo == undefined || fondo == undefined || banner == undefined || logo == '' || fondo == '' || banner == '' || marcaagua == ''){
                alertify.warning("Debe subir todas las imagenes para continuar.");
            }else{
                if (!validaCorreo.test(emp_correo)) {
                    alertify.error("El correo no es válido");
                    return;
                }
                $.ajax({
                    url: url + "empresa/register",
                    type: "post",
                    data: data,
                    processData: false,
                    contentType: false,
                    error: function (e) {
                        alertify.error("Ocurrio un error inesperado");
                    },
                    success: function(result){
                        if(result == 1){
                            redireccionar();
                        }else{
                            if(result == 2){
                                alertify.error("Ocurrio un error al subir las imagen");
                            }else{
                                alertify.error("Ocurrio un error al registrar los datos");
                            }
                        }
                    }
                });
            }
        }
    });
});

    function redireccionar(){
        alertify.success("Los datos se guardaron correctamente");
        link = url + 'empresa',
        setTimeout("window.location.href = link", 4000);
    }

    <?php if(session("success")){?>
        alertify.success('<?=session("success")?>');
    <?php }else{
        if(session("error")){?>
        alertify.error('<?=session("error")?>');
    <?php }}?>
</script>

<?php require_once APPPATH . 'Views/include/footer.php' ?>