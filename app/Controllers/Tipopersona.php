<?php

namespace App\Controllers;

use App\Models\tipopersonaModelo;
use CodeIgniter\Controller;

class Tipopersona extends Controller
{
    public function index()
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                $object = new tipopersonaModelo();
                $items = $object->reads();
                $datos = ['titulo' => 'Tipo Persona', 'items' => $items];
                return view('mantenedor/tipopersona/index', $datos);
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
                $datos = ['titulo' => 'Agregar Tipo de Persona'];
                return view('mantenedor/tipopersona/add', $datos);
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
                $object = new tipopersonaModelo();
                $tipo = $object->readTipo($id);
                $datos = ['titulo' => 'Editar Roles','item' => $tipo,'id'=>$id];
                return view('mantenedor/tipopersona/edit', $datos);
            }else{
                return view('denegado');
            }
        }else{
            return redirect()->to(base_url("/"));
        }
    }
    public function register(){
        $object = new tipopersonaModelo();
        $tipo_persona = $_POST['tipo_persona'];
        $descripcion = $_POST['descripcion'];
        $data = [
            'tip_nombre' => $tipo_persona,
            'tip_descripcion' => $descripcion
        ];
        if($object->save($data)){
            echo 1;
        }else{
            echo 'error';
        }
    }

    public function update(){
        $object = new tipopersonaModelo();

        $tip_nombre = $_POST['tipo_persona'];
        $descripcion = $_POST['descripcion'];
        $id = $_POST['id'];
        $data = [
            'tip_nombre' => $tip_nombre,
            'tip_descripcion' => $descripcion
        ];
        if($object->updateTipo($id, $data)){
            echo 1;
        }else{
            echo 'error';
        }
    }

    public function eliminar(){
        $object = new tipopersonaModelo();
        $id = $_POST['id'];
        $accion = $_POST['accion'];
        $data = [
            'tip_estado' => $accion
        ];
        if($object->updateTipo($id, $data)){
            echo 'ok';
        }else{
            echo 'error';
        }
    }
}