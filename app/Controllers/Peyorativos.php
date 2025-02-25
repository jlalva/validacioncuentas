<?php

namespace App\Controllers;

use App\Models\peyorativosModelo;
use CodeIgniter\Controller;

class Peyorativos extends Controller
{
    public function index()
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                $object = new peyorativosModelo();
                $items = $object->reads();
                $datos = ['titulo' => 'Peyorativos', 'items' => $items];
                return view('mantenedor/peyorativos/index', $datos);
            }else{
                return view('denegado');
            }
        }else{
            return redirect()->to(base_url("/"));
        }
    }

    public function add()
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                $datos = ['titulo' => 'Agregar Peyorativo'];
                return view('mantenedor/peyorativos/add', $datos);
            }else{
                return view('denegado');
            }
        }else{
            return redirect()->to(base_url("/"));
        }
    }
    public function edit($id)
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                $object = new peyorativosModelo();
                $peyorativo = $object->readPeyorativo($id);
                $datos = ['titulo' => 'Editar Peyorativo','item' => $peyorativo,'id'=>$id];
                return view('mantenedor/peyorativos/edit', $datos);
            }else{
                return view('denegado');
            }
        }else{
            return redirect()->to(base_url("/"));
        }
    }
    public function register(){
        $object = new peyorativosModelo();
        $peyorativo = $_POST['peyorativo'];
        $descripcion = $_POST['descripcion'];
        $data = [
            'pey_nombre' => $peyorativo,
            'pey_descripcion' => $descripcion
        ];
        if($object->save($data)){
            echo 1;
        }else{
            echo 'error';
        }
    }

    public function update(){
        $object = new peyorativosModelo();
        $peyorativo = $_POST['peyorativo'];
        $descripcion = $_POST['descripcion'];
        $id = $_POST['id'];
        $data = [
            'pey_nombre' => $peyorativo,
            'dom_descripcion' => $descripcion
        ];
        if($object->updatePeyorativo($id, $data)){
            echo 1;
        }else{
            echo 'error';
        }
    }

    public function eliminar(){
        $object = new peyorativosModelo();
        $id = $_POST['id'];
        $data = [
            'pey_estado' => 0
        ];
        if($object->updatePeyorativo($id, $data)){
            echo 'ok';
        }else{
            echo 'error';
        }
    }
}