<?php

namespace App\Controllers;

use App\Models\migracionModelo;
use CodeIgniter\Controller;
use App\Models\moduloModelo;
use Config\Services;

class Modulo extends Controller
{
        /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;
    protected $helpers = ["form"];

    public function index()
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                $object = new moduloModelo();
                $items = $object->readModulos();
                $datos = ['titulo' => 'Módulos','modulos' => $items];
                return view('seguridad/modulo/index', $datos);
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
                $object = new moduloModelo();
                $padre = $object->readPadre();
                $datos = ['titulo' => 'Agregar Módulo', 'padre' => $padre];
                return view('seguridad/modulo/add', $datos);
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
                $object = new moduloModelo();
                $padre = $object->readPadre();
                $modulo = $object->readModulo($id);
                $datos = ['titulo' => 'Editar módulo', 'padre' => $padre,'item' => $modulo,'id'=>$id];
                return view('seguridad/modulo/edit', $datos);
            }else{
                return view('denegado');
            }
        }else{
            return redirect()->to(base_url("/"));
        }
    }

    public function register(){
        $object = new moduloModelo();
        $validation = $this->validate([
            'modulo' => 'required',
            'padre' => 'required',
            'url' => 'required'
        ]);
        if(!$validation){
            return redirect()->to(base_url("modulo/add"))->withInput();
        }else{
            $data = [
                'men_nombre' => $this->request->getVar('modulo'),
                'men_descripcion' => $this->request->getVar('descripcion'),
                'men_url' => $this->request->getVar('url'),
                'men_padre' => $this->request->getVar('padre'),
                'men_icono' => $this->request->getVar('icono'),
                'men_orden' => $this->request->getVar('orden')
            ];
            $object->save($data);

            $session = Services::session();
            $session->setFlashdata("success", "Los datos se actualizaron correctamente");

            return redirect()->to(base_url("modulo"));
        }
    }

    public function update($id){
        $object = new moduloModelo();
        $validation = $this->validate([
            'modulo' => 'required',
            'padre' => 'required',
            'url' => 'required',
            'estado' => 'required'
        ]);
        if(!$validation){
            return redirect()->to(base_url("modulo/edit/".$id))->withInput();
        }else{
            $rol = $this->request->getVar('rol');
            $descripcion = $this->request->getVar('descripcion');
            $data = [
                'men_nombre' => $this->request->getVar('modulo'),
                'men_descripcion' => $this->request->getVar('descripcion'),
                'men_url' => $this->request->getVar('url'),
                'men_padre' => $this->request->getVar('padre'),
                'men_icono' => $this->request->getVar('icono'),
                'men_orden' => $this->request->getVar('orden'),
                'men_estado' => $this->request->getVar('estado')
            ];
            $object->updateModulo($id, $data);

            $session = Services::session();
            $session->setFlashdata("success", "Los datos se actualizaron correctamente");

            return redirect()->to(base_url("modulo"));
        }
    }

    public function delete(){
        $object = new moduloModelo();
        $id = $_POST['men_id'];
        $data = [
            'men_estado' => 0
        ];
        if($object->updateModulo($id, $data)){
            $resp = 'ok';
        }else{
            $resp = 'error';
        }
        echo $resp;
    }

}
