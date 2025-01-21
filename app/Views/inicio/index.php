<?php require_once APPPATH . 'Views/include/header.php' ?>
<h6 class="mb-0 text-uppercase">Bienvenido</h6>
<hr />
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
    <div class="col">
        <div class="card radius-10 bg-gradient-cosmic">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-white">EMPRESAS</p>
                        <h4 class="my-1 text-white"><?=$tempresas?></h4>
                        <p class="mb-0 font-13 text-white"><a href="<?=base_url('empresa')?>" style="color: #fff;">Ver empresas</a></p>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i class='lni lni-apartment'></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10 bg-gradient-ibiza">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-white">USUARIOS</p>
                        <h4 class="my-1 text-white"><?=$tusuarios?></h4>
                        <p class="mb-0 font-13 text-white"><a href="<?=base_url('usuarios')?>" style="color: #fff;">Ver usuarios</a></p>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto"><i class='lni lni-users'></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10 bg-gradient-kyoto">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-white">ROLES</p>
                        <h4 class="my-1 text-white"><?=$troles?></h4>
                        <p class="mb-0 font-13 text-white"><a href="<?=base_url('roles')?>" style="color: #fff;">Ver roles</a></p>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto"><i class='lni lni-user'></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10 bg-gradient-ohhappiness">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-white">CUENTAS CREADAS</p>
                        <h4 class="my-1 text-white"><?=$tcuentas?></h4>
                        <p class="mb-0 font-13 text-white"><a href="<?=base_url('generardata')?>" style="color: #fff;">Ver cuentas creadas</a></p>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='lni lni-envelope'></i></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once APPPATH . 'Views/include/footer.php' ?>