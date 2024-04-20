<?php require_once APPPATH . 'Views/include/header.php' ?>
<a href="<?=$app->baseURL?>subirdata" style="color: #111AD3;"><u>Empresa</u></a>
<div class="card carga">
    <div class="card-body">
        <div class="row">
            <div class="col-md-11">
                <h6 class="mb-0 text-uppercase">Subir Data</h6>
            </div>
            <div class="col-md-1">
                <a href="<?=$app->baseURL?>subirdata" class="btn btn-warning btn-sm" style="color: #000;margin-top:-7px;"><i class="fa fa-remove"></i> Cancelar </a>
            </div>
            <div class="col-md-12"><hr></div>
            <div class="col-md-6">
                <h6 class="mb-0 text-uppercase">Archivo CSV</h6>
                <hr/>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-10">
                                <input type="file" name="csv" accept=".csv">
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-primary btn-sm" id="subircsv">Validar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h6 class="mb-0 text-uppercase">Archivo EXCEL</h6>
                <hr/>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-10">
                            <input type="file" name="excel" id="excel" accept=".xlsx, .xls">
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-primary btn-sm" id="subirexcel">Validar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12"><hr></div>
            <div class="col-md-12" id="resultado"></div>
        </div>
    </div>
</div>
</script>
<script src="<?=$app->baseURL?>public/pagejs/subirarchivo.js"></script>
<?php require_once APPPATH . 'Views/include/footer.php' ?>