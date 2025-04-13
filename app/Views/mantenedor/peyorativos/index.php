<?php require_once APPPATH . 'Views/include/header.php' ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <h6 class="mb-0 text-uppercase">Peyorativos</h6>
    <div class="ms-auto">
        <div class="btn-group">
        <?php if (agregar()) { ?>
                <a href="peyorativos/add" class="btn btn-primary btn-sm" style="color: #000;"><i class="fa fa-plus"></i> Nuevo</a>
            <?php } ?>
        </div>
    </div>
</div>
<hr/>
<div class="card">
    <div class="card-body">
        <div class="col-md-12 col-sm-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tablaPeyorativo">
                    <thead>
                        <tr class="headings">
                            <th class="column-title" style="text-align: center;">ITEM</th>
                            <th class="column-title" style="text-align: center;">PEYORATIVO</th>
                            <th class="column-title" style="text-align: center;">DESCIPCION</th>
                            <th class="column-title" style="text-align: center;">ESTADO</th>
                            <th class="column-title" style="text-align: center;">ACCI&Oacute;N</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $c = 0;
                        foreach($items as $row){ $c++;?>
                        <tr class="even pointer">
                            <td style="text-align: center;"><?=$c?></td>
                            <td style="text-align: center;"><?=$row['pey_nombre']?></td>
                            <td style="text-align: center;"><?=$row['pey_descripcion']?></td>
                            <?php
                                switch($row['pey_estado']){
                                    case 0: $estado = 'Inactivo';$badge='danger'; break;
                                    case 1: $estado = 'Activo';$badge='success'; break;
                                    default: $estado = 'Inactivo';$badge='danger'; break;
                                }
                            ?>
                            <td style="text-align: center;"><span class="badge bg-<?=$badge?>"><?=$estado?></span></td>
                            <td style="text-align: center;">
                            <?php if($row['pey_estado'] == 1){
                                if(editar()){?>
                                    <a href="<?=base_url('peyorativos/edit/'.$row['pey_id'])?>" class="btn btn-success btn-sm"><i class="bx bx-edit"></i></a>
                            <?php }?>
                            <?php if(eliminar()){?>
                                <a href="#" onclick="eliminar(<?=$row['pey_id']?>)" class="btn btn-danger btn-sm"><i class="bx bx-trash"></i></a>
                            <?php }
                            }else{
                                if(in_array(session('idrol'),[1,2])){?>
                                    <button onclick="restablecer(<?=$row['pey_id']?>)" class="btn btn-warning btn-sm" title="Activar"><i class="bx bx-rotate-left"></i></button>
                            <?php }else{?>
                                    <button class="btn btn-warning btn-sm" title="Sin permiso para activar" disabled><i class="bx bx-rotate-left"></i></button>
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
    tabla("tablaPeyorativo");
    function eliminar(id) {
        alertify.confirm('¡Cuidado!','Estas por eliminar un registro, esta acción no se puede cancelar', function() {
            $.post( url + 'peyorativos/eliminar', {id: id, accion: 0}, function(response) {
                    if(response == 'ok'){
                        redireccionar("El registro se elimino correctamente");
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

    function restablecer(id) {
        alertify.confirm('¡Cuidado!','Estas por activar un registro que fue desactivado', function() {
            $.post( url + 'peyorativos/eliminar', {id: id, accion: 1}, function(response) {
                    if(response == 'ok'){
                        redireccionar("El registro se activo correctamente");
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
        $('.ajs-header').css('background-color', '#E2943A');
        $('.ajs-button.ajs-ok').css('background-color', '#4CAF50');
        $('.ajs-button.ajs-cancel').css('background-color', '#E2943A');
        $('.ajs-button').css('color', 'white');
        $('.ajs-dialog').css('width', '320px');
        $('.ajs-header').css('padding', '10px');
        $('.ajs-content').css('padding', '10px');
    }

    function redireccionar(mensaje){
        alertify.success(mensaje);
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