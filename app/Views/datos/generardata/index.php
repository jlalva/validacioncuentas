<?php require_once APPPATH . 'Views/include/header.php' ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <h6 class="mb-0 text-uppercase">Generar data</h6>
    <div class="ms-auto">
        <div class="btn-group">
        <?php if (agregar()) { ?>
                <a href="generardata/add" class="btn btn-primary btn-sm" style="color: #000;"><i class="fa fa-plus"></i> Nuevo</a>
            <?php } ?>
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
                            <th class="column-title" style="text-align: center;">ARCHIVO</th>
                            <th class="column-title" style="text-align: center;">RUTA</th>
                            <th class="column-title" style="text-align: center;">TOTAL</th>
                            <th class="column-title" style="text-align: center;">SUBIDOS</th>
                            <th class="column-title" style="text-align: center;">USUARIO</th>
                            <th class="column-title" style="text-align: center;">REGISTRADO</th>
                            <th class="column-title" style="text-align: center;">ACCI&Oacute;N</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $c = 0;
                            foreach ($items as $row) {
                                $c++;?>
                                <tr>
                                    <td><?=$c?></td>
                                    <td><?=$row->arc_nombre?></td>
                                    <td><?=$row->arc_ruta?></td>
                                    <td><?=$row->arc_total?></td>
                                    <td><?=$row->arc_subido?></td>
                                    <td><?=$row->usu_nombre?></td>
                                    <td><?=$row->arc_fecha_reg?></td>
                                    <td></td>
                                </tr>
                        <?php }?>
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