<?php require_once APPPATH . 'Views/include/header.php' ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <h6 class="mb-0 text-uppercase">Cuentas Creadas</h6>
    <div class="ms-auto">
        <div class="btn-group">
            <button data-bs-toggle="modal" data-bs-target="#modalTipo" class="btn btn-success btn-sm" style="color: #000;margin-top:-7px;margin-right:5px"> Descargar</button>
            <a href="<?=$app->baseURL?>generardata" class="btn btn-warning btn-sm" style="color: #000;margin-top:-7px;"> Regresar </a>
            <input type="hidden" id="idarchivo" name="idarchivo" value="<?=$idarchivo?>">
        </div>
    </div>
</div>
<hr/>
<div class="card">
    <div class="card-body">
        <div class="col-md-12 col-sm-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tablaCuentas">
                    <?=$tabla?>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalTipo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Descargar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipodescarga" id="compartir" value="1">
                        <label class="form-check-label" for="compartir">Para compartir</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipodescarga" id="subirrepo" value="2">
                        <label class="form-check-label" for="subirrepo">Para subir a Repos.</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="tipodescarga()">OK</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCompartir" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Formato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipoarchivodescargar" id="excel" value="1">
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
                <button type="button" class="btn btn-primary" onclick="descargacompartir()">Exportar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSubirRepositorio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">FORMATO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipoarchivosubir" id="excelrepo" value="1">
                        <label class="form-check-label" for="excelrepo">EXCEL</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tipoarchivosubir" id="csvrepo" value="2">
                        <label class="form-check-label" for="csvrepo">CSV</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="descargarepositorio()">OK</button>
            </div>
        </div>
    </div>
</div>
<script>
    tabla("tablaCuentas");
</script>
<script src="<?= $app->baseURL ?>public/pagejs/cuentas.js"></script>
<?php require_once APPPATH . 'Views/include/footer.php' ?>