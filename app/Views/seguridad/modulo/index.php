<?php require_once APPPATH . 'Views/include/header.php' ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
<h6 class="mb-0 text-uppercase">Módulos</h6>
    <div class="ms-auto">
        <div class="btn-group">
        <?php if (agregar()) { ?>
                <a href="modulo/add" class="btn btn-primary btn-sm" style="color: #000;"><i class="fa fa-plus"></i> Nuevo</a>
            <?php } ?>
        </div>
    </div>
</div>
<hr/>
<div class="card">
    <div class="card-body">
        <div class="col-md-12 col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" style="width:100%" id="tablaModulo">
                            <thead>
                                <tr class="headings">
                                    <th class="column-title" style="text-align: center;width: 10%;">ITEM</th>
                                    <th class="column-title" style="text-align: center;width: 15%;">MENU/SUB MENU</th>
                                    <th class="column-title" style="text-align: center;width: 45%;">DESCRIPCION</th>
                                    <th class="column-title" style="text-align: center;width: 15%;">URL</th>
                                    <th class="column-title" style="text-align: center;width: 15%;">ESTADO</th>
                                    <th class="column-title" style="text-align: center;width: 15%;">ACCION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $c = 0;
                                foreach ($modulos as $row) {
                                    $c++; ?>
                                    <tr class="even pointer" style="height: 5px;">
                                        <td style="height: 5px;"><?= $c ?></td>
                                        <td style="height: 5px;"><?= $row['men_nombre'] ?></td>
                                        <td style="height: 5px;"><?= $row['men_descripcion'] ?></td>
                                        <td style="height: 5px;"><?= $row['men_url'] ?></td>
                                        <?php
                                        switch ($row['men_estado']) {
                                            case 0:
                                                $estado = 'Inactivo';
                                                $badge = 'danger';
                                                break;
                                            case 1:
                                                $estado = 'Activo';
                                                $badge = 'success';
                                                break;
                                            default:
                                                $estado = 'Inactivo';
                                                $badge = 'danger';
                                                break;
                                        }
                                        ?>
                                        <td><span class="badge bg-<?= $badge ?>"><?= $estado ?></span></td>
                                        <td style="height: 5px;">
                                            <?php if (editar()) { ?>
                                                <a href="<?= base_url('modulo/edit/' . $row['men_id']) ?>" class="btn btn-success btn-sm"><i class="bx bx-edit"></i></a>
                                            <?php } ?>
                                            <?php if (eliminar()) { ?>
                                                <a href="#" onclick="eliminar(<?= $row['men_id'] ?>)" class="btn btn-danger btn-sm"><i class="bx bx-trash"></i></a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
        </div>
    </div>
</div>
<script>
    tabla("tablaModulo");

    function eliminar(men_id) {
        alertify.confirm('¡Cuidado!', 'Estas por eliminar un registro, esta acción no se puede cancelar', function() {
            $.post('modulo/delete', {
                men_id: men_id
            }, function(response) {
                if (response == 'ok') {
                    redireccionar();
                } else {
                    alertify.error('Error en la solicitud');
                }
            }).fail(function(xhr, status, error) {
                alertify.error('Error en la solicitud: ' + error);
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

    function redireccionar() {
        alertify.success("El registro se elimino correctamente");
        setTimeout("location.reload()", 2500);
    }
    <?php if (session("success")) { ?>
        alertify.success('<?= session("success") ?>');
    <?php } ?>
</script>

<?php require_once APPPATH . 'Views/include/footer.php' ?>