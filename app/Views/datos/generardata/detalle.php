<?php require_once APPPATH . 'Views/include/header.php' ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <h6 class="mb-0 text-uppercase">Detalle</h6>
    <div class="ms-auto">
        <div class="btn-group">
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
                    <thead>
                        <tr class="headings">
                            <th class="column-title" style="text-align: center;">ITEM</th>
                            <th class="column-title" style="text-align: center;">NOMBRE</th>
                            <th class="column-title" style="text-align: center;">APELLIDO</th>
                            <th class="column-title" style="text-align: center;">CODIGO</th>
                            <th class="column-title" style="text-align: center;">DNI</th>
                            <th class="column-title" style="text-align: center;">CELULAR</th>
                            <th class="column-title" style="text-align: center;">CORREO PERSONAL</th>
                            <th class="column-title" style="text-align: center;">FACULTAD</th>
                            <th class="column-title" style="text-align: center;">ESCUELA</th>
                            <th class="column-title" style="text-align: center;">SEDE</th>
                            <th class="column-title" style="text-align: center;"></th>
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