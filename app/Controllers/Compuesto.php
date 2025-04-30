<?php

namespace App\Controllers;

use App\Models\compuestoModelo;
use CodeIgniter\Controller;

class Compuesto extends Controller
{
    public function index()
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                $object = new compuestoModelo();
                $idempresa = empresaActiva();
                $emp_id = $idempresa->emp_id;
                $items = $object->reads($emp_id);
                $datos = ['titulo' => 'Compuesto', 'items' => $items];
                return view('mantenedor/compuesto/index', $datos);
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
                $datos = ['titulo' => 'Agregar Compuesto'];
                return view('mantenedor/compuesto/add', $datos);
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
                $object = new compuestoModelo();
                $compuesto = $object->readCompuesto($id);
                $datos = ['titulo' => 'Editar Compuesto','item' => $compuesto,'id'=>$id];
                return view('mantenedor/compuesto/edit', $datos);
            }else{
                return view('denegado');
            }
        }else{
            return redirect()->to(base_url("/"));
        }
    }
    public function register(){
        $object = new compuestoModelo();
        $idempresa = empresaActiva();
        $emp_id = $idempresa->emp_id;
        $compuesto = $_POST['compuesto'];
        $descripcion = $_POST['descripcion'];
        $val = $object->validarDuplicado($emp_id, $compuesto);
        if(empty($val)){
            $data = [
                'com_nombre' => $compuesto,
                'com_descripcion' => $descripcion,
                'com_emp_id' => $emp_id
            ];
            if($object->save($data)){
                echo 1;
            }else{
                echo 'error';
            }
        }else{
            echo 'duplicado';
        }
    }

    public function update(){
        $object = new compuestoModelo();
        $compuesto = $_POST['compuesto'];
        $descripcion = $_POST['descripcion'];
        $id = $_POST['id'];
        $data = [
            'com_nombre' => $compuesto,
            'com_descripcion' => $descripcion
        ];
        if($object->updateCompuesto($id, $data)){
            echo 1;
        }else{
            echo 'error';
        }
    }

    public function eliminar(){
        $object = new compuestoModelo();
        $id = $_POST['id'];
        $accion = $_POST['accion'];
        $data = [
            'com_estado' => $accion
        ];
        if($object->updateCompuesto($id, $data)){
            echo 'ok';
        }else{
            echo 'error';
        }
    }
}