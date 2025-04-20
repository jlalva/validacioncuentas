<?php require_once APPPATH . 'Views/include/header.php' ?>
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <h6 class="mb-0 text-uppercase">Roles</h6>
        <div class="ms-auto">
            <div class="btn-group">
                <?php if (agregar()) { ?>
                    <a href="roles/add" class="btn btn-primary btn-sm" style="color: #000;"><i class="fa fa-plus"></i> Nuevo</a>
                <?php } ?>
            </div>
        </div>
    </div>
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="col-md-12 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" style="width:100%" id="tablaRol">
                        <thead>
                        <tr class="headings">
                            <th class="column-title" style="text-align: center;width: 10%;">ITEM</th>
                            <th class="column-title" style="text-align: center;width: 25%;">ROL</th>
                            <th class="column-title" style="text-align: center;width: 30%;">DESCRIPCION</th>
                            <th class="column-title" style="text-align: center;width: 20%;">ESTADO</th>
                            <th class="column-title" style="text-align: center;width: 15%;">ACCION</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $c = 0;
                        foreach($roles as $row){ $c++;?>
                            <tr class="even pointer">
                                <td style="text-align: center;"><?=$c?></td>
                                <td style="text-align: center;"><?=$row['rol_nombre']?></td>
                                <td style="text-align: center;"><?=$row['rol_descripcion']?></td>
                                <?php
                                    switch($row['rol_estado']){
                                        case 0: $estado = 'Inactivo';$badge='danger'; break;
                                        case 1: $estado = 'Activo';$badge='success'; break;
                                        default: $estado = 'Inactivo';$badge='danger'; break;
                                    }
                                ?>
                                <td style="text-align: center;"><span class="badge bg-<?=$badge?>"><?=$estado?></span></td>
                                <td style="text-align: center;">
                                    <?php $ff = 0;
                                    if(editar()){
                                        $ff = 1;?>
                                        <a href="<?=base_url('roles/edit/'.$row['rol_id'])?>" class="btn btn-success btn-sm"><i class="bx bx-edit"></i></a>
                                        <a href="<?=base_url('roles/access/'.$row['rol_id'])?>" class="btn btn-warning btn-sm"><i class="bx bx-lock"></i></a>
                                    <?php }?>
                                    <?php if(eliminar()){
                                        $ff = 1;?>
                                        <a href="<?=base_url('roles/delete/'.$row['rol_id'])?>" class="btn btn-danger btn-sm"><i class="bx bx-trash"></i></a>
                                    <?php }
                                    if($ff == 0){?>
                                        <a href="#" onclick="notificacionsinpermiso('<?=session('nombres')?>','<?=session('apellidos')?>')"><span class="badge bg-warning">SIN PERMISOS</span></a>
                                    <?php }?>
                                </td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<script>
    tabla("tablaRol");
    <?php if(session("success")){?>
        alertify.success('<?=session("success")?>');
    <?php }?>
</script>

<?php require_once APPPATH . 'Views/include/footer.php' ?>