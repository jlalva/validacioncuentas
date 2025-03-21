<?php

namespace App\Controllers;

use App\Models\inicioModelo;
use CodeIgniter\Controller;

class Inicio extends Controller
{

    public function index()
    {
        if(session('authenticated')){
            $objectI = new inicioModelo();
            $idempresa = empresaActiva();
            $emp_id = $idempresa->emp_id;
            $respobjectE = $objectI->totalempresas();
            $totalE = $respobjectE->total;
            $respobjectU = $objectI->totalusuarios();
            $totalU = $respobjectU->total;
            $respobjectR = $objectI->totalroles();
            $totalR = $respobjectR->total;
            $respobjectC = $objectI->totalarchivos($emp_id);
            $totalC = $respobjectC->total;
            $respobjectUxR = $objectI->usurioxrol();
            $labeltorta = '';
            $totalestorta = '';
            foreach($respobjectUxR as $row){
                $labeltorta .= '"'.$row->rol_nombre.'",';
                $totalestorta .= $row->total.',';
            }
            if($labeltorta!=''){
                $labeltorta = substr($labeltorta,0,-1);
                $totalestorta = substr($totalestorta,0,-1);
            }
            $respobjectAn = $objectI->anio($emp_id);
            $selA = '';
            $primerAnio = 0;
            if(!empty($respobjectAn)){
                foreach($respobjectAn as $row){
                    if($primerAnio == 0){
                        $primerAnio = $row->anio;
                    }
                    $selA .="<option valuie='$row->anio'>$row->anio</option>";
                }
            }else{
                $selA .="<option valuie='0'>----</option>";
            }
            $respobjectGB = $objectI->graficabarra($emp_id, $primerAnio);
            $labels = [];
            $data = [];
            $EstudianteData = [];
            $DocenteData = [];
            $AdministrativoData = [];
            foreach($respobjectGB as $row){
                $mes = $row->mes;
                $mes_nombre = meses($row->mes);
                $tipoPersona = $row->arc_tipo_persona;
                $total = (int) $row->total;
                // Si el mes aún no está en labels, agregarlo
                if (!in_array($mes_nombre, $labels)) {
                    $labels[] = $mes_nombre;
                    $EstudianteData[$mes] = 0;
                    $DocenteData[$mes] = 0;
                    $AdministrativoData[$mes] = 0;
                }
                // Asignar valores al dataset correspondiente
                switch($tipoPersona){
                    case 1:$AdministrativoData[$mes] = $total;break;
                    case 2:$DocenteData[$mes] = $total;break;
                    case 3:$EstudianteData[$mes] = $total;break;
                }
            }
            ksort($AdministrativoData);
            ksort($DocenteData);
            ksort($EstudianteData);
            $chartData = "<script> var ctx = document.getElementById('barrasxtipo').getContext('2d');
                            var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
                                gradientStroke1.addColorStop(0, '#6078ea');
                                gradientStroke1.addColorStop(1, '#17c5ea');
                            var gradientStroke2 = ctx.createLinearGradient(0, 0, 0, 300);
                                gradientStroke2.addColorStop(0, '#ff8359');
                                gradientStroke2.addColorStop(1, '#ffdf40');
                            var gradientStroke3 = ctx.createLinearGradient(0, 0, 0, 300);
                                gradientStroke3.addColorStop(0, '#4caf50');
                                gradientStroke3.addColorStop(1, '#66bb6a');
                                var myChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                    labels: " . json_encode($labels) . ",
                                    datasets: [{
                                        label: 'Estudiantes',
                                        data: ".json_encode(array_values($EstudianteData)) . ",
                                        borderColor: gradientStroke1,
                                        backgroundColor: gradientStroke1,
                                        hoverBackgroundColor: gradientStroke1,
                                        pointRadius: 0,
                                        fill: false,
                                        borderWidth: 0
                                    }, {
                                        label: 'Docentes',
                                        data: ".json_encode(array_values($DocenteData)) . ",
                                        borderColor: gradientStroke2,
                                        backgroundColor: gradientStroke2,
                                        hoverBackgroundColor: gradientStroke2,
                                        pointRadius: 0,
                                        fill: false,
                                        borderWidth: 0
                                    },
                                    {
                                        label: 'Administrativos',
                                        data: ".json_encode(array_values($AdministrativoData)) . ",
                                        borderColor: gradientStroke3,
                                        backgroundColor: gradientStroke3,
                                        hoverBackgroundColor: gradientStroke3,
                                        pointRadius: 0,
                                        fill: false,
                                        borderWidth: 0
                                    }
                                        ]
                                    },
                                    options:{
                                    maintainAspectRatio: false,
                                    legend: {
                                        position: 'bottom',
                                        display: false,
                                        labels: {
                                            boxWidth:8
                                        }
                                        },
                                        tooltips: {
                                        displayColors:false,
                                        },
                                    scales: {
                                        xAxes: [{
                                            barPercentage: .5
                                        }]
                                        }
                                    }
                                });</script>";
            $datos = ['titulo' => 'Inicio', 'tempresas' => $totalE, 'tusuarios' => $totalU, 'troles' => $totalR, 'tcuentas' => $totalC,'labeltorta'=>$labeltorta,
            'totaltorta'=>$totalestorta, 'selectAnio'=>$selA, 'jsonBarra'=>$chartData];
            return view('inicio/index', $datos);
        }else{
            return redirect()->to(base_url("/"));
        }
    }

}
