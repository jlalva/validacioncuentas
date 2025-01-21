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
            $respobjectE = $objectI->totalempresas();
            $totalE = $respobjectE->total;
            $respobjectU = $objectI->totalusuarios();
            $totalU = $respobjectU->total;
            $respobjectR = $objectI->totalroles();
            $totalR = $respobjectR->total;
            $respobjectC = $objectI->totalcuentas();
            $totalC = $respobjectC->total;
            $datos = ['titulo' => 'Inicio', 'tempresas' => $totalE, 'tusuarios' => $totalU, 'troles' => $totalR, 'tcuentas' => $totalC];
            return view('inicio/index', $datos);
        }else{
            return redirect()->to(base_url("/"));
        }
    }

}
