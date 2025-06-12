<?php require_once APPPATH . 'Views/include/header.php' ?>
    <a href="<?=$app->baseURL?>tipopersona" style="color: #111AD3;">Tipo Persona</a> / <a href="<?=$app->baseURL?>tipopersona/add" style="color: #111AD3;">Nuevo</a>
    <div class="card">
        <div class="card-body">
            <div class="col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-md-10">
                        <h6 class="mb-0 text-uppercase">Editar Tipo Persona</h6>
                    </div>
                    <div class="col-md-2" style="text-align: right;">
                        <a href="<?=$app->baseURL?>tipopersona" class="btn btn-warning btn-sm" style="color: #000;"><i class="fa fa-remove"></i> Cancelar</a>
                        <?php if (editar()) { ?>
                            <button class="btn btn-success btn-sm" id="guardar"><i class="fa fa-edit"></i> Editar</button>
                        <?php } ?>
                    </div>
                    <div class="col-md-12"><hr></div>
                    <div class="col-md-6 col-sm-6">
                        <label>Tipo Persona</label>
                        <input type="text" class="form-control" id="tipo_persona" name="tipo_persona" autocomplete="off" value="<?=$item['tip_nombre']?>">
                        <input type="hidden" id="tip_id" name="tip_id" value="<?=$id?>">
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label>Descripci&oacute;n</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion" autocomplete="off" value="<?=$item['tip_descripcion']?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
        $('#guardar').click(function(e) {
            e.preventDefault();
            alertify.dismissAll();
            var tipo_persona = $("#tipo_persona").val();
            var descripcion = $("#descripcion").val();
            var tip_id = $("#tip_id").val();
            var data = new FormData();
            data.append("tipo_persona", tipo_persona);
            data.append("descripcion", descripcion);
            data.append("id", tip_id);
            if(tipo_persona == '' || descripcion == '') {
                alertify.warning("Llenar todos los campos para guardar.");
                return;
            }else{
                $.ajax({
                    url: url + "tipopersona/update",
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
            link = url + 'tipopersona',
            setTimeout("window.location.href = link", 4000);
        }
    })
    </script>

<?php require_once APPPATH . 'Views/include/footer.php' ?>