<?php require_once APPPATH . 'Views/include/header.php' ?>
<div class="card carga">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12"><h5><strong>COMBINACION DE OPCIONES</strong></h5><hr></div>
            <!-- Columna izquierda -->
            <div class="col-md-4">
                <!-- Tipo de persona -->
                <div class="mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h6>SELECCIONE OPCION</h6>
                        </div>
                        <div class="card-body">
                            <label for="tipopersona" class="form-label">TIPO DE PERSONA:</label>
                            <select class="form-select form-select-sm" id="tipopersona" name="tipopersona">
                                <option value="0">SELECCIONE---</option>
                                <?=$tipopersona?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Generar con -->
                <div class="mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h6>SELECCIONE OPCION</h6>
                        </div>
                        <div class="card-body">
                            <label class="form-label">GENERAR CON:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="generarcon" id="nombreapellido" value="1" checked>
                                <label class="form-check-label" for="nombreapellido">Nombres y Apellidos</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="generarcon" id="nombreapellidocodigo" value="2">
                                <label class="form-check-label" for="nombreapellidocodigo">Nombres, Apellido y Código</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="generarcon" id="sedecodigo" value="3">
                                <label class="form-check-label" for="sedecodigo">Sede y Código</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna derecha -->
            <div class="col-md-8">
                <div class="row">
                    <!-- Dominio -->
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h6>SELECCIONE OPCION</h6>
                            </div>
                            <div class="card-body">
                                <label for="dominio" class="form-label">DOMINIO:</label>
                                <select class="form-select form-select-sm" id="dominio" name="dominio">
                                    <option value="0">SELECCIONE---</option>
                                    <?=$dominio?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Tipo de archivo -->
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h6>SELECCIONE OPCION</h6>
                            </div>
                            <div class="card-body">
                                <label for="tipoarchivo" class="form-label">TIPO DE ARCHIVO:</label>
                                <select class="form-select form-select-sm" id="tipoarchivo" name="tipoarchivo">
                                    <option value="0">SELECCIONE---</option>
                                    <option value="1">EXCEL</option>
                                    <option value="2">CSV</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Subir archivo -->
                    <div class="card">
                        <div class="card-header">
                            <h6>SUBIR ARCHIVO</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ARCHIVO:</label>
                                    <input type="file" class="form-control form-control-sm" name="archivo" id="archivo" accept=".xlsx,.xls,.csv">
                                </div>
                                <div class="col-md-2 d-flex align-items-end mb-3">
                                    <button class="btn btn-primary btn-sm" id="subirarchivo">Validar</button>
                                </div>
                            </div>

                            <!-- Cuadro de instrucciones -->
                            <div class="col-md-12 mb-3">
                                <div class="alert alert-light border">
                                    <strong>IMPORTANTE:</strong><br>
                                    El archivo (Excel o Csv) debe tener la siguiente cabecera según sea el caso:
                                    <ul>
                                        <li><b>TIPO PERSONA ESTUDIANTE:</b> CODIGO, NOMBRES, APELLIDOS, DNI, CELULAR, CORREO PERSONAL, FACULTAD, ESCUELA, SEDE.</li>
                                        <li><b>TIPO PERSONA DOCENTE:</b> CODIGO, NOMBRES, APELLIDOS, DNI, CELULAR, CORREO PERSONAL, DEPARTAMENTO.</li>
                                        <li><b>TIPO PERSONA ADMINISTRATIVO:</b> CODIGO, NOMBRES, APELLIDOS, DNI, CELULAR, CORREO PERSONAL, UNIDAD/OFICINA.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resultados -->
            <div class="col-md-12"><hr></div>
            <div class="col-md-12" id="resultado"></div>

            <!-- Datos del archivo -->
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="mb-2 text-uppercase">DATOS DEL ARCHIVO</h6>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="tablapreview"></table>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-success btn-sm" hidden id="btnprocesar">Procesar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cuentas creadas -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-11">
                                <h6 class="mb-2 text-uppercase">CUENTAS CREADAS</h6>
                            </div>
                            <div class="col-md-1 text-end">
                                <button class="btn btn-success btn-sm" hidden id="btnconfirmar" onclick="guardarcuentas()">Confirmar</button>
                            </div>
                        </div>
                        <div class="table-responsive" id="datosprocesados"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= $app->baseURL ?>public/pagejs/generardata.js"></script>
<?php require_once APPPATH . 'Views/include/footer.php' ?>
