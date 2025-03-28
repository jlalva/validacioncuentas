<?php require_once APPPATH . 'Views/include/header.php' ?>
<style>
    .container {
        text-align: center;
        margin: 20px;
    }

    .label {
        font-weight: bold;
        font-size: 16px;
        display: block;
        margin-bottom: 10px;
    }

    .drop-area {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        border: 2px dashed #ccc;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        font-size: 16px;
        color: #333;
        background: #f8f8f8;
        cursor: pointer;
        width: 500px;
        height: 250px;
        margin: auto;
        transition: background 0.3s ease;
        position: relative;
    }

    .drop-area:hover {
        background: #e0e0e0;
    }

    .drop-area.dragover {
        background: #d0ffd0; /* Cambio de color cuando el archivo está sobre el área */
        border-color: #00b300;
    }

    .hidden {
        display: none;
    }

    .preview-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 10px;
    }

    .preview-icon {
        font-size: 40px;
        color: #007bff;
    }

    .file-name {
        font-size: 14px;
        font-weight: bold;
    }
</style>
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
                                        <div class="d-flex justify-content-between mb-2">
                                            <div></div>
                                            <?php if(agregar()){?>
                                                <button class="btn btn-primary btn-sm" style="color: #000;" onclick="generar()">
                                                    <i class="fa fa-plus"></i> Generar Backup
                                                </button>
                                            <?php }?>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered" id="tablaBackup">
                                                <thead>
                                                    <tr class="headings">
                                                        <th class="column-title" style="text-align: center;">ITEM</th>
                                                        <th class="column-title" style="text-align: center;">NOMBRE</th>
                                                        <th class="column-title" style="text-align: center;">TAMAÑO</th>
                                                        <th class="column-title" style="text-align: center;">FECHA</th>
                                                        <th class="column-title" style="text-align: center;">USUARIO</th>
                                                        <th class="column-title" style="text-align: center;">EMPRESA</th>
                                                        <th class="column-title" style="text-align: center;">ACCION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $c = 0;
                                                        foreach ($items as $row){
                                                            $c ++;
                                                        ?>
                                                            <tr class="even pointer">
                                                                <td style="text-align: center;"><?=$c?></td>
                                                                <td style="text-align: center;"><?=$row->bac_nombre?></td>
                                                                <td style="text-align: center;"><?=$row->bac_tamanio?></td>
                                                                <td style="text-align: center;"><?=$row->fecha?></td>
                                                                <td style="text-align: center;"><?=$row->usu_usuario?></td>
                                                                <td style="text-align: center;"><?=$row->emp_razonsocial?></td>
                                                                <td style="text-align: center;">
                                                                    <a href="<?=$app->baseURL?>public/backups/<?=$row->bac_nombre?>" download class="btn btn-sm btn-info" title="Descargar"><i class="bx bx-arrow-to-bottom"></i></a>
                                                                    <button onclick="eliminar(<?=$row->bac_id?>)" class="btn btn-sm btn-danger" title="Eliminar"><i class="bx bx-trash"></i></i></button>
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
                                        <div class="container">
                                            <div class="card">
                                                <div class="card-body">
                                                    <span class="label">SUBIR ARCHIVO *</span>
                                                    <div class="drop-area" id="dropArea">
                                                        <p id="dropText">Hacer clic o arrastrar un archivo aquí</p>
                                                        <div id="previewContainer" class="hidden preview-container">
                                                            <i class="fa fa-database preview-icon"></i>
                                                            <span id="fileName" class="file-name"></span>
                                                        </div>
                                                    </div>

                                                    <form method="post" enctype="multipart/form-data" id="uploadForm" class="miarchivo" onsubmit="return false;">
                                                        <input type="file" id="fileInput" class="hidden" accept=".sql">
                                                    </form>

                                                    <button class="btn btn-danger btn-sm confirmaR hidden" onclick="confirmarRestaurar()">
                                                        <i class="fa fa-warning"></i> Iniciar Restauración
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
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

    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('fileInput');
    const confirmaR = document.querySelector('.confirmaR');
    const dropText = document.getElementById('dropText');
    const previewContainer = document.getElementById('previewContainer');
    const fileName = document.getElementById('fileName');

    function validarArchivo(file) {
        if (file && file.name.endsWith('.sql')) {
            // Mostrar icono con el nombre del archivo
            fileName.textContent = file.name;
            previewContainer.classList.remove('hidden');
            dropText.classList.add('hidden');
            confirmaR.classList.remove('hidden'); // Mostrar botón
        } else {
            alert('Solo se permiten archivos con extensión .sql');
            fileInput.value = ''; // Limpiar input
            previewContainer.classList.add('hidden');
            dropText.classList.remove('hidden');
            confirmaR.classList.add('hidden'); // Ocultar botón
        }
    }

    // Evento cuando se hace clic en el área
    dropArea.addEventListener('click', () => fileInput.click());

    // Evento cuando se selecciona un archivo desde el explorador
    fileInput.addEventListener('change', () => validarArchivo(fileInput.files[0]));

    // Eventos Drag & Drop
    dropArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropArea.classList.add('dragover');
    });

    dropArea.addEventListener('dragleave', () => {
        dropArea.classList.remove('dragover');
    });

    dropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        dropArea.classList.remove('dragover');

        let file = e.dataTransfer.files[0]; // Obtener archivo
        fileInput.files = e.dataTransfer.files; // Asignar al input
        validarArchivo(file);
    });

    /*function abrirExplorador() {
        document.getElementById('fileInput').click();
    }*/

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
                if(result == 1){
                    alertify.success("El BACKUP se restauro Correctamente");
                    location.reload();
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