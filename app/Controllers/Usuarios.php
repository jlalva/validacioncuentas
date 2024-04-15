<?php

namespace App\Controllers;

use App\Models\rolesModelo;
use App\Models\usuariosModelo;
use CodeIgniter\Controller;
use Config\Services;

set_time_limit(0);
ini_set('memory_limit', '2048M');

class Usuarios extends Controller
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
                $object = new usuariosModelo();
                $items = $object->getUsuarios();
                $datos = ['titulo' => 'Usuarios', 'usuarios' => $items];
                return view('seguridad/usuarios/index', $datos);
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
                $object = new rolesModelo();
                $rol = $object->readRoles();
                $datos = ['titulo' => 'Agregar Usuarios','roles' => $rol];
                return view('seguridad/usuarios/add', $datos);
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
                $object = new usuariosModelo();
                $objectRol = new rolesModelo();
                $item = $object->getUsuario($id);
                $rol = $objectRol->readRoles();
                $datos = ['titulo' => 'Editar Usuario','item' => $item,'id'=>$id,'roles' => $rol];
                return view('seguridad/usuarios/edit', $datos);
            }else{
                return view('denegado');
            }
        }else{
            return redirect()->to(base_url("/"));
        }
    }

    public function register(){
        $object = new usuariosModelo();
        $usu_usuario = $this->request->getVar('usuario');
        $foto = $usu_usuario;

        $validarUsuario = $object->usuarioLogin($usu_usuario);
        if($validarUsuario){
            echo 'usuario';exit;
        }
        $validarCorreo = $object->validarCorreo($this->request->getVar('correo'));
        if($validarCorreo){
            echo 'correo';exit;
        }

        if(!empty($_FILES['file']['name'])){
            $carpeta = $this->request->getVar('rol');
            $ruta = "public/images/FOTOS_OFICIAL/$carpeta";

            if (!file_exists($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $foto = $foto.'.'.strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            if(move_uploaded_file($_FILES['file']['tmp_name'],"public/images/FOTOS_OFICIAL/$carpeta/".$foto)){
                $data = [
                    'usu_nombre' => $this->request->getVar('nombres'),
                    'usu_apellido' => $this->request->getVar('apellidos'),
                    'usu_rol_id' =>$this->request->getVar('rol_id'),
                    'usu_genero' => $this->request->getVar('genero'),
                    'usu_correo' => $this->request->getVar('correo'),
                    'usu_usuario' => $this->request->getVar('usuario'),
                    'usu_clave' => encriptar(trim($this->request->getVar('clave'))),
                    'usu_foto' => $foto
                ];
                if($object->addUsuario($data)){
                    $resp = 1;
                }else{
                    $resp = 0;
                }
            }else{
                $resp = 2;
            }
        }else{
            $data = [
                'usu_nombre' => $this->request->getVar('nombres'),
                'usu_apellido' => $this->request->getVar('apellidos'),
                'usu_rol_id' =>$this->request->getVar('rol_id'),
                'usu_genero' => $this->request->getVar('genero'),
                'usu_correo' => $this->request->getVar('correo'),
                'usu_usuario' => $this->request->getVar('usuario'),
                'usu_clave' => encriptar(trim($this->request->getVar('clave'))),
            ];
            if($object->addUsuario($data)){
                $resp = 1;
            }else{
                $resp = 0;
            }
        }
        echo $resp;
    }

    public function update($id){
        $object = new usuariosModelo();
        $usu_usuario = $this->request->getVar('usuario');
        $foto = $usu_usuario;

        if(!empty($_FILES['file']['name'])){
            $carpeta = $this->request->getVar('rol');
            $ruta = "public/images/FOTOS_OFICIAL/$carpeta";

            if (!file_exists($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $foto = $foto.'.'.strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            if(move_uploaded_file($_FILES['file']['tmp_name'],"public/images/FOTOS_OFICIAL/$carpeta/".$foto)){
                $data = [
                    'usu_nombre' => $this->request->getVar('nombres'),
                    'usu_apellido' => $this->request->getVar('apellidos'),
                    'usu_rol_id' =>$this->request->getVar('rol_id'),
                    'usu_genero' => $this->request->getVar('genero'),
                    'usu_correo' => $this->request->getVar('correo'),
                    'usu_usuario' => $this->request->getVar('usuario'),
                    'usu_clave' => encriptar(trim($this->request->getVar('clave'))),
                    'usu_foto' => $foto
                ];
                if($object->updateUsuario($id,$data)){
                    $resp = 1;
                }else{
                    $resp = 0;
                }
            }else{
                $resp = 2;
            }
        }else{
            $data = [
                'usu_nombre' => $this->request->getVar('nombres'),
                'usu_apellido' => $this->request->getVar('apellidos'),
                'usu_rol_id' =>$this->request->getVar('rol_id'),
                'usu_genero' => $this->request->getVar('genero'),
                'usu_correo' => $this->request->getVar('correo'),
                'usu_usuario' => $this->request->getVar('usuario'),
                'usu_clave' => encriptar(trim($this->request->getVar('clave')))
            ];
            if($object->updateUsuario($id, $data)){
                $resp = 1;
            }else{
                $resp = 0;
            }
        }
        echo $resp;
    }

    public function tipousuario(){
        $object = new usuariosModelo();
        $tipousuario = $_POST['tipousuario'];
        $items = $object->tipoUsuario($tipousuario);
        $sel = "<option value='0'>Usuario</option>";
        foreach ($items as $row) {
            $sel .="<option value='$row->usu_id'>".strtoupper($row->per_nombres.' '.$row->per_apellidos)."</option>";
        }
        echo $sel;
    }

    public function updateFoto(){
        $objectPerson = new personaModelo();
        $per_id = $_POST['per_id'];
        $codigo = $_POST['codigo'];
        $foto = '';
        $foto = $codigo;
        if(!empty($_FILES['file']['name'])){
            $carpeta = strtoupper(session('rol'));
            $ruta = "public/images/FOTOS_OFICIAL/$carpeta";

            if (!file_exists($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $foto = $foto.'.'.strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            if(move_uploaded_file($_FILES['file']['tmp_name'],"public/images/FOTOS_OFICIAL/$carpeta/".$foto)){
                $data = [
                    'per_foto' => $foto
                ];
            if($objectPerson->updatePersona($per_id, $data)){
                $session = session();
                $session->set('foto', $foto);
                $resp = 1;
            }else{
                $resp = 0;
            }
            }else{
                $resp = 2;
            }
        }else{
            $resp = 2;
        }
        echo $resp;
    }

    public function eliminar(){
        $object = new usuariosModelo();
        $id = $_POST['usu_id'];
        $data = [
            'usu_estado' => 0
        ];
        if($object->updateUsuario($id, $data)){
            echo 'ok';
        }else{
            echo 'error';
        }
    }

    public function updateperfil(){
        $objectPerson = new personaModelo();
        $validation = $this->validate([
            'telefono' => 'required',
            'departamento' => 'required',
            'provincia' => 'required',
            'distrito' => 'required',
            'direccion' => 'required',
            'dni' => 'required'
        ]);

        if(!$validation){
            return redirect()->to(base_url("perfil"))->withInput();
        }else{
            $per_id =  $_POST['per_id'];
            $data = [
                'per_telefono' => $_POST['telefono'],
                'per_ubigeo' => $_POST['distrito'],
                'per_direccion' => $_POST['direccion'],
                'per_dni' => $_POST['dni']
            ];
            if($objectPerson->updatePersona($per_id, $data)){
                $session = Services::session();
                $session->setFlashdata("success", "Los datos se actualizaron correctamente");
                return redirect()->to(base_url("perfil"));
            }
        }
    }
}
