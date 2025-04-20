<?php require_once APPPATH . 'Views/include/header.php' ?>
    <a href="<?=$app->baseURL?>roles" style="color: #111AD3;">Roles</a> / <a href="<?=$app->baseURL?>roles/add" style="color: #111AD3;">Nuevo</a>
    <div class="card">
        <div class="card-body">
            <div class="col-md-12 col-sm-12">
                <?=form_open($app->baseURL.'roles/update/'.$id);?>
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
                        <h6 class="mb-0 text-uppercase">Agregar rol</h6>
                    </div>
                    <div class="col-md-2" style="text-align: right;">
                        <a href="<?=$app->baseURL?>roles" class="btn btn-warning btn-sm" style="color: #000;"><i class="fa fa-remove"></i> Cancelar</a>
                        <?php if (editar()) { ?>
                            <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-edit"></i> Editar</button>
                        <?php } ?>
                    </div>
                    <div class="col-md-12"><hr></div>
                    <form>
                        <div class="col-md-6 col-sm-6">
                            <label>Rol</label>
                            <input type="text" class="form-control <?=validate("rol") ? 'is-invalid': null;?>" id="rol" name="rol" autocomplete="off" value="<?=$item['rol_nombre']?$item['rol_nombre']:''?>">
                            <div class="invalid"><?=validate("rol")?></div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <label>Descripci&oacute;n</label>
                            <input type="text" class="form-control <?=validate("descripcion") ? 'is-invalid': null;?>" id="descripcion" name="descripcion" autocomplete="off" value="<?=$item['rol_descripcion']?$item['rol_descripcion']:''?>">
                            <div class="invalid"><?=validate("descripcion")?></div>
                        </div>
                        <div class="col-md-2 col-sm-2">
                            <label>Estado</label>
                            <select class="form-control" id="estado" name="estado">
                                <option value="">Seleccione</option>
                                <?php if($item['rol_estado'] == 0){?>
                                    <option value="0" selected>INACTIVO</option>
                                    <option value="1">ACTIVO</option>
                                <?php }else{?>
                                    <option value="0">INACTIVO</option>
                                    <option value="1" selected>ACTIVO</option>
                                <?php }?>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php require_once APPPATH . 'Views/include/footer.php' ?>