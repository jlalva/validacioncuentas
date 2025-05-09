<?php

namespace App\Controllers;

use App\Models\dominioModelo;
use CodeIgniter\Controller;

class Dominio extends Controller
{
    public function index()
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                $object = new dominioModelo();
                $idempresa = empresaActiva();
                $emp_id = $idempresa->emp_id;
                $items = $object->reads($emp_id);
                $datos = ['titulo' => 'Dominio', 'items' => $items];
                return view('mantenedor/dominio/index', $datos);
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
                $datos = ['titulo' => 'Agregar Dominio'];
                return view('mantenedor/dominio/add', $datos);
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
                $object = new dominioModelo();
                $dominio = $object->readDominio($id);
                $datos = ['titulo' => 'Editar Dominio','item' => $dominio,'id'=>$id];
                return view('mantenedor/dominio/edit', $datos);
            }else{
                return view('denegado');
            }
        }else{
            return redirect()->to(base_url("/"));
        }
    }
    public function register(){
        $object = new dominioModelo();
        $idempresa = empresaActiva();
        $emp_id = $idempresa->emp_id;
        $dominio = strtolower($_POST['dominio']);
        $descripcion = $_POST['descripcion'];
        $val = $object->validarDuplicado($emp_id, $dominio);
        if(empty($val)){
            $data = [
                'dom_nombre' => $dominio,
                'dom_descripcion' => $descripcion,
                'dom_emp_id' => $emp_id
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
        $object = new dominioModelo();
        $dominio = strtolower($_POST['dominio']);
        $descripcion = $_POST['descripcion'];
        $id = $_POST['id'];
        $data = [
            'dom_nombre' => $dominio,
            'dom_descripcion' => $descripcion
        ];
        if($object->updateDominio($id, $data)){
            echo 1;
        }else{
            echo 'error';
        }
    }

    public function eliminar(){
        $object = new dominioModelo();
        $id = $_POST['id'];
        $accion = $_POST['accion'];
        $data = [
            'dom_estado' => $accion 
        ];
        if($object->updateDominio($id, $data)){
            echo 'ok';
        }else{
            echo 'error';
        }
    }
}