<?php

namespace App\Controllers;

use App\Models\usuariosModelo;
use CodeIgniter\Controller;

class Perfil extends Controller
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
        if(session('authenticated')){
            $object = new usuariosModelo();
            $item = $object->getUsuario(session('idusuario'));
            $datos = ['titulo' => 'Perfil', 'datos' => $item];
            return view('seguridad/perfil/index', $datos);
        }else{
            return redirect()->to(base_url("/"));
        }
    }
}
