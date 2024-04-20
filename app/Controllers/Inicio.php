<?php

namespace App\Controllers;

use App\Models\configuracionModelo;
use App\Models\inicioModelo;
use App\Models\usuariosModelo;
use CodeIgniter\Controller;

class Inicio extends Controller
{

    public function index()
    {
        if(session('authenticated')){
            $datos = ['titulo' => 'Inicio'];
            return view('inicio/index', $datos);
        }else{
            return redirect()->to(base_url("/"));
        }
    }

}
