<?php require_once APPPATH . 'Views/include/header.php' ?>
<div class="col">
    <h6 class="mb-0 text-uppercase">Carga de Datos</h6>
    <hr/>
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
                            <div class="tab-title">Subir</div>
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
                                                    <tr style="text-align: center;">
                                                        <td><?=$c?></td>
                                                        <td><?=$row->arc_nombre?></td>
                                                        <td><?=strtolower(substr($row->arc_ruta, 16))?></td>
                                                        <td><?=$row->arc_total?></td>
                                                        <td><?=$row->arc_subido?></td>
                                                        <td><?=$row->usu_nombre.' '.$row->usu_apellido?></td>
                                                        <td><?=$row->arc_tiempo?></td>
                                                        <td><?=$row->arc_fecha_reg?></td>
                                                        <td style="text-align: center;">
                                                            <a href="<?=$app->baseURL?>subirdata/detalle/<?=$row->arc_id?>" class="btn btn-info btn-sm" title="Ver detalle"><i class="bx bx-list-ul"></i></a>
                                                        </td>
                                                    </tr>
                                            <?php }?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="subir" role="tabpanel">
                    <?php require_once APPPATH . 'Views/datos/subirdata/add.php' ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    tabla("tabla");
</script>
<?php require_once APPPATH . 'Views/include/footer.php' ?>