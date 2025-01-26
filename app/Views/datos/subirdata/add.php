<div class="card carga">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-0 text-uppercase">TIPO DE ARCHIVO</h6>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipoarchivo" id="excel" value="1" checked>
                            <label class="form-check-label" for="tipoarchivo">EXCEL</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipoarchivo" id="csv" value="2">
                            <label class="form-check-label" for="tipoarchivo">CSV</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12"><hr></div>
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-0 text-uppercase">SUBIR ARCHIVO</h6>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-10">
                            <input type="file" name="archivoec" id="archivoec" accept=".csv, .xlsx, .xls">
                        </div>
                    </div>
                </div>
                <div class="col-md-12"><hr></div>
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="mb-0 text-uppercase">DESCRIPCION</h6>
                    </div>
                    <div class="col-md-12">
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4"></textarea>
                    </div>
                </div>
                <div class="col-md-12"><hr></div>
                <div class="row justify-content-center">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-primary btn-sm" id="subirarchivo">Validar</button>
                    </div>
                </div>
            </div>
            <div class="col-md-12"><hr></div>
            <div class="col-md-12" id="resultado"></div>
            <div class="col-md-12"><hr></div>
            <div class="col-md-12">
                <div class="alert alert-warning border-0 bg-warning alert-dismissible fade show py-2">
                    <div class="d-flex align-items-center">
                        <div class="font-35 text-dark"><i class="bx bx-info-circle"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 text-dark">INSTRUCCIÓN</h6>
                            <div class="text-dark">Para que la información se registre correctamente, El archivo (csv o excel) (según lo que se seleccione en el radiobutton) 
                                debe contener la siguiente cabecera: NOMBRES, APELLIDOS, EMAIL, STATUS, ULTIMO ACCESO, ESPACIO USO.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?=$app->baseURL?>public/pagejs/subirarchivo.js"></script>