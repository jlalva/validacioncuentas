<?php require_once APPPATH . 'Views/include/header.php' ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <h6 class="mb-0 text-uppercase">Cuentas Creadas</h6>
    <div class="ms-auto">
        <div class="btn-group">
            <a href="<?=$app->baseURL?>generardata" class="btn btn-warning btn-sm" style="color: #000;margin-top:-7px;"> Regresar </a>
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
<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check">
                            <label>Correo Generado</label>
                            <input class="form-control" name="correogenerado" id="correogenerado" oninput="this.value = this.value.toLowerCase()">
                            <input type="hidden" id="iddato" name="iddato">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <label>Dominio</label>
                            <input class="form-control" name="dominio" id="dominio" oninput="this.value = this.value.toLowerCase()" readonly="readonly">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="editarcacafonia()">Editar</button>
            </div>
        </div>
    </div>
</div>

<script>
    tabla("tablaCuentas");

    function datoseditarcacafonia(id,correo,dominio){
        $("#correogenerado").val(correo)
        $("#dominio").val(dominio)
        $("#iddato").val(id)
    }

    function editarcacafonia(){
        alertify.dismissAll();
        var correo = $("#correogenerado").val();
        var dominio = $("#dominio").val();
        correo = correo + dominio;
        if(correo == ''){
            alertify.error('Ingrese un correo válido');
            return;
        }
        var id = $("#iddato").val();
        $.ajax({
		url:url+'generardata/meditarcacafonia',
		type:'post',
		data:{id:id, correo:correo},
		success:function(data){
            if(data == 'ok'){
                alertify.success('Correo modificado correctamente');
                location.reload();
            }else{
                if(data == 'existe'){
                    alertify.error('El correo ingresado ya existe');
                }else{
                    alertify.error('Ocurrio un error al editar la información');
                }
            }
		}
	})
    }
</script>
<script src="<?= $app->baseURL ?>public/pagejs/cuentas.js"></script>
<?php require_once APPPATH . 'Views/include/footer.php' ?>