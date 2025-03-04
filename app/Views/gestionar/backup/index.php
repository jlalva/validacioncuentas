<?php require_once APPPATH . 'Views/include/header.php' ?>
<div class="col">
    <div class="x_panel">
        <div class="x_title">
            <h6 class="mb-0 text-uppercase"><?=$titulo?></h6>
            <hr/>
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-success" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#generar" role="tab" aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class='bx bx-list-ol font-18 me-1'></i>
                                    </div>
                                    <div class="tab-title"> Generar Backup</div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#restaurar" role="tab" aria-selected="false">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class='bx bx-arrow-to-top font-18 me-1'></i>
                                    </div>
                                    <div class="tab-title">Restaurar Backup</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content py-3">
                        <div class="tab-pane fade show active" id="generar" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <div class="col-md-12 col-sm-12">
                                        <?php if(agregar()){?>
                                            <button class="btn btn-primary btn-sm" style="color: #000;" onclick="generar()"><i class="fa fa-plus"></i> Generar Backup</button>
                                        <?php }?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered jambo_table bulk_action" id="tablaBackup">
                                                <thead>
                                                    <tr class="headings">
                                                        <th class="column-title" style="text-align: center;">ITEM</th>
                                                        <th class="column-title" style="text-align: center;">NOMBRE</th>
                                                        <th class="column-title" style="text-align: center;">TAMAÑO</th>
                                                        <th class="column-title" style="text-align: center;">FECHA</th>
                                                        <th class="column-title" style="text-align: center;">USUARIO</th>
                                                        <th class="column-title" style="text-align: center;">ACCION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $c = 0;
                                                        foreach ($items as $row){
                                                            $c ++;
                                                        ?>
                                                            <tr>
                                                                <td><?=$c?></td>
                                                                <td><?=$row->bac_nombre?></td>
                                                                <td><?=$row->bac_tamanio?> MB</td>
                                                                <td><?=$row->fecha?></td>
                                                                <td><?=$row->usu_usuario?></td>
                                                                <td>
                                                                    <a href="<?=$app->baseURL?>public/backups/<?=$row->bac_nombre?>" download class="btn btn-sm btn-info"><i class="fa fa-cloud-download"></i></a>
                                                                    <button onclick="eliminar(<?=$row->bac_id?>)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
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
                        <div class="tab-pane fade" id="restaurar" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <?php if(agregar()){?>
                                        <button class="btn btn-warning btn-sm" style="color: #000;" onclick="abrirExplorador()"><i class="fa fa-warning"></i> Restaurar Backup</button>
                                        <button class="btn btn-danger btn-sm confirmaR" style="color: #000;display:none" onclick="confirmarRestaurar()"><i class="fa fa-warning"></i> Iniciar Restauración</button>
                                        <form method="post" enctype="multipart/form-data" id="uploadForm" class="miarchivo" onsubmit="return false;">
                                            <input type="file" id="fileInput" style="display:none;" onchange="muestraConfirma()" accept=".sql">
                                        </form>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#tablaBackup').DataTable();

    function abrirExplorador() {
        document.getElementById('fileInput').click();
    }

    function muestraConfirma(){
        var input = document.getElementById('fileInput');
        if (input.files.length > 0) {
            $(".confirmaR").show();
        } else {
            $(".confirmaR").hide();
        }
    }

    function confirmarRestaurar(){
        var archivo = ($("#fileInput"))[0].files[0];
        var data = new FormData();
        $("#tablaBackup").waitMe();
        data.append("file", archivo);
        $.ajax({
            url: url + "backup/restaurar",
            type: "post",
            data: data,
            processData: false,
            contentType: false,
            error: function (e) {
                $("#tablaBackup").waitMe('hide');
                alertify.error("Ocurrio un error inesperado");
            },
            success: function(result){
                $("#tablaBackup").waitMe('hide');
                redireccionar();
                if(result == 1){
                   redireccionar();
                }else{
                    if(result == 2){
                        alertify.error("Ocurrio un error al subir la base de datos");
                    }else{
                        alertify.error("Ocurrio un error al registrar los datos");
                    }
                }
            }
        });
    }

    function generar(){
        $("#tablaBackup").waitMe();
        $.ajax({
            url: url + "backup/generarBackup",
            type: "post",
            error: function (e) {
                $("#tablaBackup").waitMe('hide');
                alertify.error("Ocurrio un error inesperado");
            },
            success: function(result){
                $("#tablaBackup").waitMe('hide');
                $(".table-responsive").html(result);
                $('#tablaBackup').DataTable();
                redireccionardos();
            }
        });
    }

    function eliminar(bac_id) {
        alertify.confirm('¡Cuidado!','Eliminarás el registro seleccionado. Estas seguro de hacerlo?', function() {
            $.post( url + 'backup/eliminar', {bac_id: bac_id}, function(response) {
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

    function redireccionar(url = false){
        alertify.success("El registro se elimino correctamente");
        setTimeout("location.reload()", 2500);
    }

    function redireccionardos(url = false){
        alertify.success("Se generó la BACKUP Correctamente");
        setTimeout("location.reload()", 2500);
    }
</script>
<?php require_once APPPATH . 'Views/include/footer.php' ?>