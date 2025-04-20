<?php require_once APPPATH . 'Views/include/header.php' ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <h6 class="mb-0 text-uppercase">Empresa</h6>
    <div class="ms-auto">
        <div class="btn-group">
        <?php if (agregar()) { ?>
                <a href="empresa/add" class="btn btn-primary btn-sm" style="color: #000;"><i class="fa fa-plus"></i> Nuevo</a>
            <?php } ?>
        </div>
    </div>
</div>
<hr/>
<div class="card">
    <div class="card-body">
        <div class="col-md-12 col-sm-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tablaUser">
                    <thead>
                        <tr class="headings">
                            <th class="column-title" style="text-align: center;">ITEM</th>
                            <th class="column-title" style="text-align: center;">RUC</th>
                            <th class="column-title" style="text-align: center;">RAZÓN SOCIAL</th>
                            <th class="column-title" style="text-align: center;">SIGLAS</th>
                            <th class="column-title" style="text-align: center;">DIRECCIÓN</th>
                            <th class="column-title" style="text-align: center;">TELÉFONO</th>
                            <th class="column-title" style="text-align: center;">AÑO FUNDACIÓN</th>
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
                            <td style="text-align: center;"><?=$row['emp_ruc']?></td>
                            <td style="text-align: center;"><?=$row['emp_razonsocial']?></td>
                            <td style="text-align: center;"><?=$row['emp_siglas']?></td>
                            <td style="text-align: center;"><?=$row['emp_direccion']?></td>
                            <td style="text-align: center;"><?=$row['emp_telefono']?></td>
                            <td style="text-align: center;"><?=strftime('%d-%m-%Y', strtotime($row['emp_fechafundacion']))?></td>
                            <?php
                                switch($row['emp_estado']){
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
                                <a href="<?=base_url('empresa/edit/'.$row['emp_id'])?>" class="btn btn-success btn-sm"><i class="bx bx-edit"></i></a>
                            <?php }?>
                            <?php if(eliminar()){
                                $ff = 1;?>
                                <a href="#" onclick="eliminar(<?=$row['emp_id']?>)" class="btn btn-danger btn-sm"><i class="bx bx-trash"></i></a>
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
    tabla("tablaUser");
    function eliminar(emp_id) {
        alertify.confirm('¡Cuidado!','Estas por eliminar un registro, esta acción no se puede cancelar', function() {
            $.post( url + 'empresa/eliminar', {emp_id: emp_id}, function(response) {
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