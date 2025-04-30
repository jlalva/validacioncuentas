<?php require_once APPPATH . 'Views/include/header.php' ?>
    <a href="<?=$app->baseURL?>compuesto" style="color: #111AD3;">Peyorativo</a> / <a href="<?=$app->baseURL?>compuesto/add" style="color: #111AD3;">Nuevo</a>
    <div class="card">
        <div class="card-body">
            <div class="col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-md-10">
                        <h6 class="mb-0 text-uppercase">Editar No Compuesto</h6>
                    </div>
                    <div class="col-md-2" style="text-align: right;">
                        <a href="<?=$app->baseURL?>compuesto" class="btn btn-warning btn-sm" style="color: #000;"><i class="fa fa-remove"></i> Cancelar</a>
                        <?php if (editar()) { ?>
                            <button class="btn btn-success btn-sm" id="guardar"><i class="fa fa-edit"></i> Editar</button>
                        <?php } ?>
                    </div>
                    <div class="col-md-12"><hr></div>
                    <div class="col-md-6 col-sm-6">
                        <label>No Compuesto</label>
                        <input type="text" class="form-control" id="compuesto" name="compuesto" autocomplete="off" value="<?=$item['com_nombre']?>" oninput="this.value = this.value.toUpperCase()">
                        <input type="hidden" id="com_id" name="com_id" value="<?=$id?>">
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label>Descripci&oacute;n</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion" autocomplete="off" value="<?=$item['com_descripcion']?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
        $('#guardar').click(function(e) {
            e.preventDefault();
            var compuesto = $("#compuesto").val();
            var descripcion = $("#descripcion").val();
            var com_id = $("#com_id").val();
            var data = new FormData();
            data.append("compuesto", compuesto);
            data.append("descripcion", descripcion);
            data.append("id", com_id);
            if(compuesto == '' || descripcion == '') {
                alertify.warning("Llenar todos los campos para guardar.");
                return;
            }else{
                $.ajax({
                    url: url + "compuesto/update",
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
            }
        })

        function redireccionar(){
            alertify.success("Los datos se modificaron correctamente");
            link = url + 'compuesto',
            setTimeout("window.location.href = link", 4000);
        }
    })
    </script>

<?php require_once APPPATH . 'Views/include/footer.php' ?>