<?php require_once APPPATH . 'Views/include/header.php' ?>
    <a href="<?=$app->baseURL?>roles" style="color: #111AD3;">Roles</a>
    <div class="card">
        <div class="card-body">
            <?=form_open($app->baseURL.'roles/register');?>
            <?php
            function validate(string $key){
                if(session('_ci_validation_errors')){
                    $value = session('_ci_validation_errors');
                    if(isset($value[$key])){
                        return $value[$key];
                    }
                }
            }
            ?>
            <div class="row">
                <div class="col-md-10">
                    <h6 class="mb-0 text-uppercase">Agregar Rol</h6>
                </div>
                <div class="col-md-2" style="text-align: right;">
                    <a href="<?=$app->baseURL?>roles" class="btn btn-warning btn-sm" style="color: #000;"><i class="fa fa-remove"></i> Cancelar</a>
                    <?php if (agregar()) { ?>
                        <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-save"></i> Guardar</button>
                    <?php } ?>
                </div>
                <div class="col-md-12"><hr></div>
                <form>
                    <div class="col-md-6 col-sm-6">
                        <label>Rol</label>
                        <input type="text" class="form-control <?=validate("rol") ? 'is-invalid': null;?>" id="rol" name="rol" autocomplete="off" value="<?=old('rol')?>">
                        <div class="invalid"><?=validate("rol")?></div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label>Descripci&oacute;n</label>
                        <input type="text" class="form-control <?=validate("descripcion") ? 'is-invalid': null;?>" id="descripcion" name="descripcion" autocomplete="off" value="<?=old('descripcion')?>">
                        <div class="invalid"><?=validate("descripcion")?></div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php require_once APPPATH . 'Views/include/footer.php' ?>