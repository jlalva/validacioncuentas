<?php require_once APPPATH . 'Views/include/header.php' ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <h6 class="mb-0 text-uppercase">Usuarios</h6>
    <div class="ms-auto">
        <div class="btn-group">
        <?php if (agregar()) { ?>
                <a href="usuarios/add" class="btn btn-primary btn-sm" style="color: #000;"><i class="fa fa-plus"></i> Nuevo</a>
            <?php } ?>
        </div>
    </div>
</div>
<hr/>
<div class="card">
    <div class="card-body">
        <div class="col-md-12 col-sm-12">
        <div class="table-responsive">
                <table class="table table-striped table-bordered" style="width:100%"  id="tablaUser">
                    <thead>
                        <tr class="headings">
                            <th class="column-title" style="text-align: center;">ITEM</th>
                            <th class="column-title" style="text-align: center;">APELLIDO</th>
                            <th class="column-title" style="text-align: center;">NOMBRE</th>
                            <th class="column-title" style="text-align: center;">CORREO</th>
                            <th class="column-title" style="text-align: center;">USUARIO</th>
                            <th class="column-title" style="text-align: center;">ESTADO</th>
                            <th class="column-title" style="text-align: center;">ACCION</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $c = 0;
                        foreach($usuarios as $row){ $c++;?>
                        <tr class="even pointer">
                            <td style="text-align: center;"><?=$c?></td>
                            <td style="text-align: center;"><?=$row->usu_apellido?></td>
                            <td style="text-align: center;"><?=$row->usu_nombre?></td>
                            <td style="text-align: center;"><?=$row->usu_correo?></td>
                            <td style="text-align: center;"><?=$row->usu_usuario?></td>
                            <?php
                                switch($row->usu_estado){
                                    case 0: $estado = 'Inactivo';$badge='danger'; break;
                                    case 1: $estado = 'Activo';$badge='success'; break;
                                    default: $estado = 'Inactivo';$badge='danger'; break;
                                }
                            ?>
                            <td style="text-align: center;"><span class="badge bg-<?=$badge?>"><?=$estado?></span></td>
                            <td style="text-align: center;">
                            <?php $ff = 0;
                            if(editar()){
                                $ff = 1;
                                if($row->usu_rol_id == 1 && session('idrol')!=1){?>
                                    <a href="#" onclick="nopuede()" class="btn btn-success btn-sm"><i class="bx bx-edit"></i></a>
                            <?php }else{?>
                                <a href="<?=base_url('usuarios/edit/'.$row->usu_id)?>" class="btn btn-success btn-sm"><i class="bx bx-edit"></i></a>
                            <?php }
                            }?>
                            <?php if(eliminar()){
                                $ff = 1;
                                if($row->usu_rol_id == 1 && session('idrol')!=1){?>
                                    <a href="#" onclick="nopuede()" class="btn btn-danger btn-sm"><i class="bx bx-trash"></i></a>
                            <?php }else{?>
                                <a href="#" onclick="eliminar(<?=$row->usu_id?>)" class="btn btn-danger btn-sm"><i class="bx bx-trash"></i></a>
                            <?php }
                            }
                            if($ff == 0){
                                if($row->usu_rol_id == 1 && session('idrol')!=1){?>
                                    <a href="#" onclick="nopuede()"><span class="badge bg-warning">SIN PERMISOS</span></a>
                            <?php }else{?>
                                <a href="#" onclick="notificacionsinpermiso('<?=session('nombres')?>','<?=session('apellidos')?>')"><span class="badge bg-warning">SIN PERMISOS</span></a>
                            <?php }
                            }?>
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
    tabla("tablaUser");

    function nopuede(){
        alertify.alert('Usted no puede modificar este usario')
    .set('title', 'No Autorizado');
    }
    function eliminar(usu_id) {
        alertify.confirm('¡Cuidado!','Estas por eliminar un registro, esta acción no se puede cancelar', function() {
            $.post( 'usuarios/eliminar', {usu_id: usu_id}, function(response) {
                    if(response == 'ok'){
                        redireccionar();
                    }else{
                        alertify.error('Error en la solicitud');
                    }
                }).fail(function(xhr, status, error) {
                alertify.error('Error en la solicitud: '+ error);
            });
        }, function() {
            console.log('Cancelado');
        }).set('labels', {
            ok: 'Confirmar',
            cancel: 'Cancelar'
        }).set('buttonReverse', true).set('confirmButtonText', 'Aceptar').set('cancelButtonText', 'Cancelar').set('defaultFocus', 'ok');

        // Modificar el estilo del cuadro de diálogo de confirmación
        $('.ajs-header').css('background-color', '#f44336');
        $('.ajs-button.ajs-ok').css('background-color', '#4CAF50');
        $('.ajs-button.ajs-cancel').css('background-color', '#f44336');
        $('.ajs-button').css('color', 'white');
        $('.ajs-dialog').css('width', '320px');
        $('.ajs-header').css('padding', '10px');
        $('.ajs-content').css('padding', '10px');
    }

    function redireccionar(){
        alertify.success("El registro se elimino correctamente");
        setTimeout("location.reload()", 2500);
    }

    <?php if(session("success")){?>
        alertify.success('<?=session("success")?>');
    <?php }else{
        if(session("error")){?>
        alertify.error('<?=session("error")?>');
    <?php }}?>
</script>

<?php require_once APPPATH . 'Views/include/footer.php' ?>