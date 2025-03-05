<?php require_once APPPATH . 'Views/include/header.php' ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <h6 class="mb-0 text-uppercase">Detalle</h6>
    <div class="ms-auto">
        <div class="btn-group">
            <?php if($ruta){?>
                <button data-bs-toggle="modal" data-bs-target="#modalDescargar" class="btn btn-success btn-sm" style="color: #000;margin-top:-7px;margin-right:5px"> Descargar</button>
            <?php }?>
            <a href="<?=$app->baseURL?>subirdata" class="btn btn-warning btn-sm" style="color: #000;margin-top:-7px;"> Regresar </a>
        </div>
    </div>
</div>
<hr/>
<div class="card">
    <div class="card-body">
        <div class="col-md-12 col-sm-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tabla">
                    <thead>
                        <tr class="headings">
                            <th class="column-title" style="text-align: center;">ITEM</th>
                            <th class="column-title" style="text-align: center;">NOMBRES</th>
                            <th class="column-title" style="text-align: center;">APELLIDOS</th>
                            <th class="column-title" style="text-align: center;">EMAIL</th>
                            <th class="column-title" style="text-align: center;">STATUS</th>
                            <th class="column-title" style="text-align: center;">ULTIMO ACCESO</th>
                            <th class="column-title" style="text-align: center;">ESPACIO USO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?=$table?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalDescargar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Descargar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <input type="hidden" id="idarchivo" name="idarachivo" value="<?=$id_arch?>">
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipoarchivodescargar" id="excel" value="1" checked>
                        <label class="form-check-label" for="excel">EXCEL</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipoarchivodescargar" id="pdf" value="2">
                        <label class="form-check-label" for="pdf">PDF</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="descargar()" data-bs-dismiss="modal">Exportar</button>
            </div>
        </div>
    </div>
</div>
<script>
    tabla("tabla");

    function descargar(){
        const descargar = document.querySelector('input[name="tipoarchivodescargar"]:checked');
        var idarchivo = $("#idarchivo").val();
        archivo = descargar.value;
        if(archivo == 1){
            var urli = "<?=$app->baseURL.$ruta?>";
            $(location).attr('href',urli)
        }else{
            var urli = url + "subirdata/pdf/"+idarchivo;
            window.open(urli, '_blank');
        }
    }
</script>
<?php require_once APPPATH . 'Views/include/footer.php' ?>