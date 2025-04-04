<?php require_once APPPATH . 'Views/include/header.php' ?>
    <a href="<?=$app->baseURL?>dominio" style="color: #111AD3;">Dominios</a> / <a href="<?=$app->baseURL?>peyorativos/add" style="color: #111AD3;">Nuevo</a>
    <div class="card">
        <div class="card-body">
            <div class="col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-md-10">
                        <h6 class="mb-0 text-uppercase">Editar Peyorativo</h6>
                    </div>
                    <div class="col-md-2" style="text-align: right;">
                        <a href="<?=$app->baseURL?>dominio" class="btn btn-warning btn-sm" style="color: #000;"><i class="fa fa-remove"></i> Cancelar</a>
                        <?php if (editar()) { ?>
                            <button class="btn btn-success btn-sm" id="guardar"><i class="fa fa-edit"></i> Editar</button>
                        <?php } ?>
                    </div>
                    <div class="col-md-12"><hr></div>
                    <div class="col-md-6 col-sm-6">
                        <label>Dominio</label>
                        <input type="text" class="form-control" id="dominio" name="dominio" autocomplete="off" value="<?=$item['dom_nombre']?>" oninput="this.value = this.value.toLowerCase()">
                        <input type="hidden" id="id" name="id" value="<?=$id?>">
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label>Descripci&oacute;n</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion" autocomplete="off" value="<?=$item['dom_descripcion']?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
        $('#guardar').click(function(e) {
            e.preventDefault();
            var dominio = $("#dominio").val();
            var descripcion = $("#descripcion").val();
            var id = $("#id").val();
            var data = new FormData();
            data.append("dominio", dominio);
            data.append("descripcion", descripcion);
            data.append("id", id);
            if(dominio == '' || descripcion == '') {
                alertify.warning("Llenar todos los campos para guardar.");
                return;
            }else{
                if(dominio.includes('@')){
                    $.ajax({
                        url: url + "dominio/update",
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
                                alertify.error("Ocurrio un error al guardar la información");
                            }
                        }
                    });
                }else{
                    alertify.error("El dominio ingresado no es válido");
                }
            }
        })

        function redireccionar(){
            alertify.success("Los datos se modificaron correctamente");
            link = url + 'dominio',
            setTimeout("window.location.href = link", 4000);
        }
    })
    </script>

<?php require_once APPPATH . 'Views/include/footer.php' ?>