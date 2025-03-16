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
                        <p class="mb-0 text-white">ARCHIVOS PROCESADOS</p>
                        <h4 class="my-1 text-white"><?=$tcuentas?></h4>
                        <p class="mb-0 font-13 text-white"><a href="<?=base_url('generardata')?>" style="color: #fff;">Ver cuentas creadas</a></p>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='lni lni-envelope'></i></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="col-md-9">
                                <h6 class="mb-0">CUENTAS CREADAS POR AÑO Y PERSONA</h6>
                            </div>
                            <div class="col-md-1">
                                <label>Año:</label>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control"><?=$selectAnio?></select>
                            </div>
                        </div>
                        <div class="d-flex align-items-center ms-auto font-13 gap-2 my-3">
                            <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1" style="color: #14abef"></i>Estudiantes</span>
                            <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1" style="color: #ffc107"></i>Docentes</span>
                            <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1" style="color: #4caf50"></i>Administrativos</span>
                        </div>
                        <div class="chart-container-1">
                            <canvas id="barrasxtipo"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-header bg-transparent">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">CANTIDAD DE USUARIOS POR ROLES</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container-1">
                        <canvas id="usuarioxroles"></canvas>
                        </div>
                </div>
                <ul class="list-group list-group-flush">
                    <?php
                        $resumenlabel = explode(',',$labeltorta);
                        $resumentorta = explode(',',$totaltorta);
                        for($i=0;$i<count($resumenlabel);$i++) {
                            $label = substr($resumenlabel[$i], 1, -1);
                            echo '<li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">'.$label.' <span class="badge" style="color: black;">'.$resumentorta[$i].'</span></li>';
                        }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
    var ctx = document.getElementById("usuarioxroles").getContext('2d');
var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke1.addColorStop(0, '#ee0979');
    gradientStroke1.addColorStop(1, '#ff6a00');
var gradientStroke2 = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke2.addColorStop(0, '#283c86');
    gradientStroke2.addColorStop(1, '#39bd3c');
var gradientStroke3 = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke3.addColorStop(0, '#7f00ff');
    gradientStroke3.addColorStop(1, '#e100ff');
var gradientStroke4 = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke4.addColorStop(0, '#ff6a00');
    gradientStroke4.addColorStop(1, '#39bd3c');

var myChart = new Chart(ctx, {
  type: 'pie',
  data: {
    labels: [<?=$labeltorta?>],
    datasets: [{
      backgroundColor: [
        gradientStroke1,
        gradientStroke2,
        gradientStroke3,
        gradientStroke4
      ],
      hoverBackgroundColor: [
        gradientStroke1,
        gradientStroke2,
        gradientStroke3,
        gradientStroke4
      ],
      data: [<?=$totaltorta?>],
    }]
  },
  options: {
    maintainAspectRatio: false,
    cutoutPercentage: 0,
    plugins: {
      legend: {
        position: 'bottom',
        display: false,
        labels: {
          boxWidth: 8
        }
      },
      tooltip: {
        displayColors: false
      },
      datalabels: {
        color: '#fff',         // Color del texto
        font: { weight: 'bold', size: 14 },
        anchor: 'center',       // Asegura que el texto esté dentro
        align: 'center',        // Centra el texto en cada sección
        formatter: (value) => value // Muestra los valores directamente
      }
    }
  },
  plugins: [ChartDataLabels]
});

///////////////////////////////////////////////
</script>
<?=$jsonBarra?>
<?php require_once APPPATH . 'Views/include/footer.php' ?>