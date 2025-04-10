<?php require_once APPPATH . 'Views/include/header.php' ?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-10">
                <h6 class="mb-0 text-uppercase">Agregar Peyorativo</h6>
            </div>
            <div class="col-md-2" style="text-align: right;">
                <a href="<?=base_url('peyorativos')?>" class="btn btn-warning btn-sm" style="color: #000;margin-top:-7px;"><i class="fa fa-remove"></i> Cancelar </a>
                <?php if (agregar()) { ?>
                    <button class="btn btn-primary btn-sm" id="guardar" style="margin-top:-7px;"><i class="fa fa-save"></i> Guardar</button>
                <?php } ?>
            </div>
            <div class="col-md-12"><hr></div>
            <div class="col-md-4 col-sm-4">
                <label>Peyorativo</label>
                <input type="text" class="form-control" id="peyorativo" name="peyorativo" autocomplete="off" oninput="this.value = this.value.toLowerCase()">
            </div>
            <div class="col-md-8 col-sm-8">
                <label>Descripci&oacute;n</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" autocomplete="off">
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#guardar').click(function(e) {
            alertify.dismissAll();
            e.preventDefault();
            var peyorativo = $("#peyorativo").val();
            var descripcion = $("#descripcion").val();
            var data = new FormData();
            data.append("peyorativo", peyorativo);
            data.append("descripcion", descripcion);
            if(peyorativo == '' || descripcion == '') {
                alertify.warning("Llenar todos los campos para guardar.");
                return;
            }else{
                $.ajax({
                    url: url + "peyorativos/register",
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
                            if(result = 'duplicado'){
                                alertify.error("El peyorativo ingresado ya existe");
                            }else{
                                alertify.error("Ocurrio un error al guardar la información");
                            }
                        }
                    }
                });
            }
        });
    });
    function redireccionar(){
        alertify.success("Los datos se guardaron correctamente");
        link = url + 'peyorativos',
        setTimeout("window.location.href = link", 4000);
    }
</script>
<?php require_once APPPATH . 'Views/include/footer.php' ?>