<?php require_once APPPATH . 'Views/include/header.php' ?>
<div class="card carga">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12"><hr></div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-0 text-uppercase">Tipo de persona</h6>
                    </div>
                    <div class="col-md-6">
                        <select class="form-select form-select-sm mb-3" id="tipopersona" name="tipopersona">
                            <option value="0">SELECCIONE---</option>
                            <?=$tipopersona?>
                        </select>
                    </div>
                    <div class="col-md-12"><hr></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-0 text-uppercase">Dominio</h6>
                    </div>
                    <div class="col-md-6">
                        <select class="form-select form-select-sm mb-3" id="dominio" name="dominio">
                            <option value="0">SELECCIONE---</option>
                            <?=$dominio?>
                        </select>
                    </div>
                    <div class="col-md-12"><hr></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-0 text-uppercase">Tipo de archivo</h6>
                    </div>
                    <div class="col-md-6">
                        <select class="form-select form-select-sm mb-3" id="tipoarchivo" name="tipoarchivo">
                            <option value="0">SELECCIONE---</option>
                            <option value="1">EXCEL</option>
                            <option value="2">CSV</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="mb-0 text-uppercase">Generar con</h6>
                    </div>
                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="generarcon" id="nombreapellido" value="1" checked>
                            <label class="form-check-label" for="nombreapellido">Nombres y Apellidos</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="generarcon" id="nombreapellidocodigo" value="2">
                            <label class="form-check-label" for="nombreapellidocodigo">Nombres, Apellido y C&oacute;digo</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="generarcon" id="sedecodigo" value="3">
                            <label class="form-check-label" for="sedecodigo">Sede y C&oacute;digo</label>
                        </div>
                    </div>
                    <div class="col-md-12"><hr></div>
                </div>
            </div>
            <div class="col-md-6">
                <h6 class="mb-0 text-uppercase">Subir Archivo</h6>
            </div>
            <div class="col-md-4">
                <input type="file" name="archivo" id="archivo" accept=".xlsx,.xls,.csv">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary btn-sm" id="subirarchivo">Validar</button>
            </div>
            <div class="col-md-12"><hr></div>
            <div class="col-md-12" id="resultado"></div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="mb-0 text-uppercase">Datos del archivo</h6>
                            </div>
                        </div>
                        <hr />
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="tablapreview">
                            </table>
                        </div>
                        <div class="col-md-12 text-center">
                            <button class="btn btn-success btn-sm" hidden id="btnprocesar">Procesar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="col-md-12">
                        <b>IMPORTANTE</b>
                    </div>
                    <div class="col-md-12">
                    Para que la información se PROCESE CORRECTAMENTE, El archivo (csv o excel) (según lo que se seleccione en el radiobutton) debe contener la siguiente cabecera: Para
                    <ul>
                        <li><b>TIPO PERSONA ADMINISTRATIVO:</b> CODIGO, NOMBRES, APELLIDOS, DNI, CELULAR, CORREO PERSONAL, UNIDAD/OFICINA.</li>
                        <li><b>TIPO PERSONA DOCENTE:</b> CODIGO, NOMBRES, APELLIDOS, DNI, CELULAR, CORREO PERSONAL, DEPARTAMENTO.</li>
                        <li><b>TIPO PERSONA ESTUDIANTE:</b> CODIGO, NOMBRES, APELLIDOS, DNI, CELULAR, CORREO PERSONAL, FACULTAD, ESCUELA, SEDE.</li>
                    </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <hr>
            </div>
            <div class="col-md-12" id="resultado"></div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-11">
                                <h6 class="mb-0 text-uppercase">Cuentas creadas</h6>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-success btn-sm" hidden id="btnconfirmar">Confirmar</button>
                            </div>
                        </div>
                        <hr />
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="procesados">
                                <thead>
                                    <tr class="headings">
                                        <th class="column-title" style="text-align: center;">ITEM</th>
                                        <th class="column-title" style="text-align: center;">NOMBRES</th>
                                        <th class="column-title" style="text-align: center;">APELLIDOS</th>
                                        <th class="column-title" style="text-align: center;">CORREO CREADO/EXISTENTE</th>
                                        <th class="column-title" style="text-align: center;">USUARIO</th>
                                        <th class="column-title" style="text-align: center;">CLAVE</th>
                                        <th class="column-title" style="text-align: center;">SITUACION</th>
                                    </tr>
                                </thead>
                                <tbody id="datosprocesados">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= $app->baseURL ?>public/pagejs/generardata.js"></script>
<?php require_once APPPATH . 'Views/include/footer.php' ?>