<?php require_once APPPATH . 'Views/include/header.php' ?>
<a href="<?=$app->baseURL?>generardata" style="color: #111AD3;"><u>Generar datos</u></a>
<div class="card carga">
    <div class="card-body">
        <div class="row">
            <div class="col-md-11">
                <h6 class="mb-0 text-uppercase">Generar Data</h6>
            </div>
            <div class="col-md-1">
                <a href="<?=$app->baseURL?>generardata" class="btn btn-warning btn-sm" style="color: #000;margin-top:-7px;"><i class="fa fa-remove"></i> Cancelar </a>
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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-0 text-uppercase">Datos del archivo</h6>
                        <hr/>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="tabla">
                                <thead>
                                    <tr class="headings">
                                        <th class="column-title" style="text-align: center;">ITEM</th>
                                        <th class="column-title" style="text-align: center;">NOMBRE</th>
                                        <th class="column-title" style="text-align: center;">APELLIDO</th>
                                        <th class="column-title" style="text-align: center;">CODIGO</th>
                                        <th class="column-title" style="text-align: center;">DNI</th>
                                        <th class="column-title" style="text-align: center;">CELULAR</th>
                                        <th class="column-title" style="text-align: center;">CORREO PERSONAL</th>
                                        <th class="column-title" style="text-align: center;">FACULTAD</th>
                                        <th class="column-title" style="text-align: center;">ESCUELA</th>
                                        <th class="column-title" style="text-align: center;">SEDE</th>
                                    </tr>
                                </thead>
                                <tbody id="datos">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-0 text-uppercase">Datos Observados</h6>
                        <hr/>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="observados">
                                <thead>
                                    <tr class="headings">
                                        <th class="column-title" style="text-align: center;">ITEM</th>
                                        <th class="column-title" style="text-align: center;">NOMBRE</th>
                                        <th class="column-title" style="text-align: center;">APELLIDO</th>
                                        <th class="column-title" style="text-align: center;">CODIGO</th>
                                        <th class="column-title" style="text-align: center;">DNI</th>
                                        <th class="column-title" style="text-align: center;">CELULAR</th>
                                        <th class="column-title" style="text-align: center;">CORREO PERSONAL</th>
                                        <th class="column-title" style="text-align: center;">OBSERVACION</th>
                                    </tr>
                                </thead>
                                <tbody id="datosobservados">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</script>
<script src="<?=$app->baseURL?>public/pagejs/generardata.js"></script>
<?php require_once APPPATH . 'Views/include/footer.php' ?>