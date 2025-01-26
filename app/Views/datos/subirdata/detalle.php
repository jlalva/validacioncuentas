<?php require_once APPPATH . 'Views/include/header.php' ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <h6 class="mb-0 text-uppercase">Detalle</h6>
    <div class="ms-auto">
        <div class="btn-group">
            <?php if($ruta){?>
                <a href="<?=$app->baseURL?>public/<?=$ruta?>" class="btn btn-success btn-sm" style="color: #000;margin-top:-7px;margin-right:5px"> Descargar</a>
            <?php }?>
            <a href="<?=$app->baseURL?>subirdata" class="btn btn-warning btn-sm" style="color: #000;margin-top:-7px;"> Regresar </a>
        </div>
    </div>
</div>
<hr/>
<div class="card">
    <div class="card-body">
        <div class="col-md-12 col-sm-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tabla">
                    <thead>
                        <tr class="headings">
                            <th class="column-title" style="text-align: center;">ITEM</th>
                            <th class="column-title" style="text-align: center;">NOMBRES</th>
                            <th class="column-title" style="text-align: center;">APELLIDOS</th>
                            <th class="column-title" style="text-align: center;">EMAIL</th>
                            <th class="column-title" style="text-align: center;">STATUS</th>
                            <th class="column-title" style="text-align: center;">ULTIMO ACCESO</th>
                            <th class="column-title" style="text-align: center;">ESPACIO USO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?=$table?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    tabla("tabla");
</script>
<?php require_once APPPATH . 'Views/include/footer.php' ?>