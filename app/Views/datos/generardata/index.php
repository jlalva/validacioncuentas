<?php require_once APPPATH . 'Views/include/header.php' ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <h6 class="mb-0 text-uppercase">Generar data</h6>
</div>
<hr />
<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs nav-success" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#listar" role="tab" aria-selected="true">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-list-ol font-18 me-1'></i>
                        </div>
                        <div class="tab-title">Listar</div>
                    </div>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#subir" role="tab" aria-selected="false">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-arrow-to-top font-18 me-1'></i>
                        </div>
                        <div class="tab-title">Cargar y Procesar Data</div>
                    </div>
                </a>
            </li>
        </ul>
        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="listar" role="tabpanel">
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
                                            <th class="column-title" style="text-align: center;">TIPO CUENTA</th>
                                            <th class="column-title" style="text-align: center;">TOTAL</th>
                                            <th class="column-title" style="text-align: center;">SUBIDOS</th>
                                            <th class="column-title" style="text-align: center;">USUARIO</th>
                                            <th class="column-title" style="text-align: center;">TIEMPO</th>
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
                                                <td  style="text-align: center;"><?= $c ?></td>
                                                <td  style="text-align: center;"><?= $row->arc_nombre ?></td>
                                                <td  style="text-align: center;"><?= strtolower(substr($row->arc_ruta, 16)) ?></td>
                                                <td  style="text-align: center;"><?= $row->tip_nombre ?></td>
                                                <td  style="text-align: center;"><?= $row->arc_total ?></td>
                                                <td  style="text-align: center;"><?= $row->arc_subido ?></td>
                                                <td  style="text-align: center;"><?= $row->usu_nombre.' '.$row->usu_apellido ?></td>
                                                <td  style="text-align: center;"><?= $row->arc_tiempo?></td>
                                                <td  style="text-align: center;"><?= $row->arc_fecha_reg ?></td>
                                                <td style="text-align: center;">
                                                    <a href="<?= $app->baseURL ?>generardata/detalle/<?= $row->arc_id ?>" class="btn btn-info btn-sm" title="DATA"><i class="bx bx-list-ul"></i></a>
                                                    <?php if($row->peyorativo == 'si'){?>
                                                        <a href="<?= $app->baseURL ?>generardata/peyorativos/<?= $row->arc_id ?>" class="btn btn-warning btn-sm" title="CACAFONIAS"><i class="bx bx-error"></i></a>
                                                    <?php }else{
                                                        if($row->duplicados>0){?>
                                                            <a href="<?= $app->baseURL ?>generardata/duplicados/<?= $row->arc_id ?>" class="btn btn-danger btn-sm" title="DUPLICADOS"><i class="bx bx-error"></i></a>
                                                    <?php }else{?>
                                                        <a href="<?= $app->baseURL ?>generardata/cuentas/<?= $row->arc_id ?>" class="btn btn-success btn-sm" title="DATA PROCESADA"><i class="bx bx-data"></i></a>
                                                    <?php }}?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="subir" role="tabpanel">
                <?php require_once APPPATH . 'Views/datos/generardata/add.php' ?>
            </div>
        </div>
    </div>
</div>
<script>
    tabla("tabla");
</script>
<?php require_once APPPATH . 'Views/include/footer.php' ?>