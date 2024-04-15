<?php require_once APPPATH . 'Views/include/header.php' ?>
    <a href="<?=$app->baseURL?>roles" style="color: #111AD3;">Roles</a> / <a href="<?=$app->baseURL?>roles/add" style="color: #111AD3;">Nuevo</a>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11">
                    <h6 class="mb-0 text-uppercase">Permisos</h6>
                </div>
                <div class="col-md-1">
                    <a href="<?= $app->baseURL ?>roles" class="btn btn-warning btn-sm" style="color: #000;"> Volver</a>
                </div>
                <div class="col-md-12"><hr></div>
                <div class="col-md-12">
                    <?=$menus?>
                </div>
            </div>
        </div>
    </div>

<script>
    <?php if (session("success")) { ?>
        alertify.success('<?= session("success") ?>');
    <?php } ?>

    function permiso(menu,rol,opcion){
        var pso_id = $("#permiso_id_"+menu).val();
        var estado = 0;
        if( $('.checkPermiso_'+menu+'_'+opcion).prop('checked') ) {
            estado = 1;
        }
        $.post( "<?=$app->baseURL?>roles/permiso",
            {menu:menu, rol:rol, opcion:opcion, pso_id: pso_id, estado:estado},
            function(data) {
                $("#permiso_id_"+menu).val(data);
            });
    }
</script>

<?php require_once APPPATH . 'Views/include/footer.php' ?>