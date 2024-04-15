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

    public function fijarperiodo(){
        $object = new periodoModelo();
        $objectUsu = new usuariosModelo();
        $idperiodo = $_POST['idperiodo'];
        $periodo = $_POST['periodo'];
        $session = session();
        if(in_array(session('idrol'),[1,2])){
            if($object->desmarcarSeleccion()){
                $object->marcarSeleccion($idperiodo);
                $upd = ['usu_periodo_seleccionado'=>$idperiodo];
                $objectUsu->updateUsuario(session('idusuario'),$upd);
                $data = [
                    'idperiodo' => $idperiodo,
                    'periodo' => $periodo
                ];
            }
        }else{
            $upd = ['usu_periodo_seleccionado'=>$idperiodo];
            $objectUsu->updateUsuario(session('idusuario'),$upd);
            $data = [
                'idperiodo' => $idperiodo,
                'periodo' => $periodo
            ];
        }
        $session->set($data);
    }
}
