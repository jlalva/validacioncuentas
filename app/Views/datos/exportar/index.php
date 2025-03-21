<?php require_once APPPATH . 'Views/include/header.php' ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <h6 class="mb-0 text-uppercase">EXPORTAR</h6>
</div>
<hr />
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 col-sm-3">
                <label>Tipo</label>
                <select class="form-control" id="tipo" name="tipo">
                    <option value="0">Todos</option>
                    <option value="1">Data Subida</option>
                    <option value="2">Data Generada</option>
                </select>
            </div>
            <div class="col-md-3 col-sm-3">
                <label>Fecha Inicio</label>
                <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio">
            </div>
            <div class="col-md-3 col-sm-3">
                <label>Fecha Fin</label>
                <input type="date" class="form-control" name="fecha_fin" id="fecha_fin">
            </div>
            <div class="col-md-3 col-sm-3 d-flex align-items-end">
                <button class="btn btn-info" id="consultar" onclick="consultar()"><i class="bx bx-search"></i></button>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="col-md-12 col-sm-12 table-responsive">
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
                <tbody id="datosExportar">
                    <?php
                    $c = 0;
                    foreach ($items as $row) {
                        $c++;?>
                        <tr>
                            <td  style="text-align: center;"><?= $c ?></td>
                            <td  style="text-align: center;"><?= $row->arc_nombre ?></td>
                            <td  style="text-align: center;"><?= $row->arc_ruta ?></td>
                            <td  style="text-align: center;"><?= $row->arc_total ?></td>
                            <td  style="text-align: center;"><?= $row->arc_subido ?></td>
                            <td  style="text-align: center;"><?= $row->usu_nombre.' '.$row->usu_apellido ?></td>
                            <td  style="text-align: center;"><?= $row->arc_fecha_reg ?></td>
                            <td style="text-align: center;">
                                <?php if($row->arc_origen == 1){?>
                                    <a href="<?= $app->baseURL.$row->arc_ruta ?>" class="btn btn-info btn-sm" title="DESCARGAR DATA"><i class="bx bx-arrow-to-bottom"></i></a>
                                <?php }else{?>
                                    <a href="<?= $app->baseURL?>generardata/descargarcuentas/<?=$row->arc_id?>" class="btn btn-info btn-sm" title="DESCARGAR DATA"><i class="bx bx-arrow-to-bottom"></i></a>
                                <?php }?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    tabla("tabla");
</script>
<script src="<?= $app->baseURL ?>public/pagejs/exportar.js"></script>
<?php require_once APPPATH . 'Views/include/footer.php' ?>