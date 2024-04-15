<?php require_once APPPATH . 'Views/include/header.php' ?>
<a href="<?= $app->baseURL ?>modulo" style="color: #111AD3;">Módulo</a>
<div class="card">
    <div class="card-body">
        <div class="col-md-12 col-sm-12">
            <?= form_open($app->baseURL . 'modulo/register'); ?>
            <?php
            function validate(string $key)
            {
                if (session('_ci_validation_errors')) {
                    $value = session('_ci_validation_errors');
                    if (isset($value[$key])) {
                        return $value[$key];
                    }
                }
            }
            ?>
            <div class="row">
                <div class="col-md-10">
                    <h6 class="mb-0 text-uppercase">Agregar m&oacute;dulo</h6>
                </div>
                <div class="col-md-1">
                    <a href="<?= $app->baseURL ?>modulo" class="btn btn-warning btn-sm" style="color: #000;margin-top:-7px;"><i class="fa fa-remove"></i> Cancelar </a>
                </div>
                <div class="col-md-1">
                    <?php if (agregar()) { ?>
                        <button class="btn btn-primary btn-sm" type="submit" style="margin-top:-7px;"><i class="fa fa-save"></i> Guardar</button>
                    <?php } ?>
                </div>
                <div class="col-md-12">
                    <hr>
                </div>
                <form>
                    <div class="col-md-6 col-sm-6">
                        <label>M&oacute;dulo</label>
                        <input type="text" class="form-control <?= validate("modulo") ? 'is-invalid' : null; ?>" id="modulo" name="modulo" autocomplete="off" value="<?= old('modulo') ?>">
                        <div class="invalid"><?= validate("modulo") ?></div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label>Padre</label>
                        <select class="form-control <?= validate("padre") ? 'is-invalid' : null; ?>" id="padre" name="padre">
                            <option value="0">Seleccione</option>
                            <?php foreach ($padre as $row) { ?>
                                <option value="<?= $row['men_id'] ?>"><?= $row['men_nombre'] ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid"><?= validate("padre") ?></div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <label>URL</label>
                        <input type="text" class="form-control <?= validate("url") ? 'is-invalid' : null; ?>" id="url" name="url" autocomplete="off" value="<?= old('url') ?>">
                        <div class="invalid"><?= validate("url") ?></div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <label>Icono</label>
                        <input type="text" class="form-control" id="icono" name="icono" autocomplete="off" value="<?= old('icono') ?>">
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <label>Orden</label>
                        <input type="number" class="form-control" id="orden" name="orden" autocomplete="off" value="<?= old('orden') ?>">
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <label>Descripci&oacute;n</label>
                        <input type="text" class="form-control <?= validate("descripcion") ? 'is-invalid' : null; ?>" id="descripcion" name="descripcion" autocomplete="off" value="<?= old('descripcion') ?>">
                        <div class="invalid"><?= validate("descripcion") ?></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once APPPATH . 'Views/include/footer.php' ?>