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
            $respobjectC = $objectI->totalcuentas();
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
            $respobjectGB = $objectI->graficabarra($emp_id);
            $labelmeses = '';
            $totalmeses = '';
            foreach($respobjectGB as $row){
                $labelmeses .= '"'.meses($row->mes).'",';
                $totalmeses .= $row->total.',';
            }
            if($labelmeses!=''){
                $labelmeses = substr($labelmeses,0,-1);
                $totalmeses = substr($totalmeses,0,-1);
            }
            $datos = ['titulo' => 'Inicio', 'tempresas' => $totalE, 'tusuarios' => $totalU, 'troles' => $totalR, 'tcuentas' => $totalC,'labeltorta'=>$labeltorta,
            'totaltorta'=>$totalestorta, 'labelmeses'=>$labelmeses, 'totalmeses'=>$totalmeses];
            return view('inicio/index', $datos);
        }else{
            return redirect()->to(base_url("/"));
        }
    }

}
