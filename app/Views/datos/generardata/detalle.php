<?php require_once APPPATH . 'Views/include/header.php' ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <h6 class="mb-0 text-uppercase">Detalle</h6>
    <div class="ms-auto">
        <div class="btn-group">
            <?php if($ruta){?>
                <a href="<?=$app->baseURL?>public/<?=$ruta?>" class="btn btn-success btn-sm" style="color: #000;margin-top:-7px;margin-right:5px"> Descargar</a>
            <?php }?>
            <a href="<?=$app->baseURL?>generardata" class="btn btn-warning btn-sm" style="color: #000;margin-top:-7px;"> Regresar </a>
        </div>
    </div>
</div>
<hr/>
<div class="card">
    <div class="card-body">
        <div class="col-md-12 col-sm-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tabla">
                    <?=$table?>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    tabla("tabla");
</script>
<?php require_once APPPATH . 'Views/include/footer.php' ?>