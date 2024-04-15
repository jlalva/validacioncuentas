<?php

namespace App\Controllers;

use App\Models\moduloModelo;
use App\Models\permisoModelo;
use CodeIgniter\Controller;
use App\Models\rolesModelo;
use Config\Services;

class Roles extends Controller
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
                $rolModel = new rolesModelo();
                $roles = $rolModel->readRoles();
                $datos = ['titulo' => 'Roles','roles' => $roles];
                return view('seguridad/roles/index', $datos);
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
                $datos = ['titulo' => 'Agragar Roles'];
                return view('seguridad/roles/add', $datos);
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
                $rolModel = new rolesModelo();
                $rol = $rolModel->readRol($id);
                $datos = ['titulo' => 'Editar Roles','item' => $rol,'id'=>$id];
                return view('seguridad/roles/edit', $datos);
            }else{
                return view('denegado');
            }
        }else{
            return redirect()->to(base_url("/"));
        }
    }

    public function access($id)
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                $object = new moduloModelo();
                $objectPermiso = new PermisoModelo();
                $html ='<div class="col-md-12 col-sm-12">
                                    <div class="accordion accordion-flush" id="accordion" role="tablist" aria-multiselectable="true">';
                $padre = $object->readPadre();
                foreach($padre as $row){
                    if($row['men_id'] > 1){
                        $html .='<div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne-'.$row['men_nombre'].'">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#'.$row['men_nombre'].'" aria-expanded="false"
                                      aria-controls="'.$row['men_nombre'].'">
                                      '.$row['men_nombre'].'
                                    </button>
                                  </h2>
                                  <div id="'.$row['men_nombre'].'" class="accordion-collapse collapse" aria-labelledby="'.$row['men_nombre'].'One" data-bs-parent="#accordionFlush">
                            <div class="accordion-body">
                                <table class="table table-bordered">
                                <thead>
                                    <tr>
                                    <th>#</th>
                                    <th>Módulo</th>
                                    <th>Acceso</th>
                                    <th>Ver</th>
                                    <th>Agregar</th>
                                    <th>Editar</th>
                                    <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody>';
                            $menu = $object->menPadre($row['men_id']);
                            $c=0;
                            foreach ($menu as $Rmenu){
                                $pso_id = 0;
                                $acceso = '';
                                $ver = '';
                                $agregar = '';
                                $editar = '';
                                $eliminar = '';
                                $permiso = $objectPermiso->getPermiso($id, $Rmenu['men_id']);
                                if(!empty($permiso)){
                                    $pso_id = $permiso[0]['pso_id'];
                                    $acceso = 'checked';
                                    //echo '<pre>';
                                    //print_r($permiso);exit;
                                    if($permiso[0]['pso_ver'] == 1){
                                        $ver = 'checked';
                                    }
                                    if($permiso[0]['pso_agregar'] == 1){
                                        $agregar = 'checked';
                                    }
                                    if($permiso[0]['pso_editar'] == 1){
                                        $editar = 'checked';
                                    }
                                    if($permiso[0]['pso_eliminar'] == 1){
                                        $eliminar = 'checked';
                                    }
                                }
                                $c++;
                                $html .='<tr>
                                    <th scope="row">'.$c.'</th>
                                    <td>'.$Rmenu['men_nombre'].'<input type="hidden" id="permiso_id_'.$Rmenu['men_id'].'" value="'.$pso_id.'"></td>
                                    <td><input onclick="permiso(\''.$Rmenu['men_id'].'\',\''.$id.'\',\'0\')" type="checkbox" class="checkPermiso_'.$Rmenu['men_id'].'_0" '.$acceso.'></td>
                                    <td><input onclick="permiso(\''.$Rmenu['men_id'].'\',\''.$id.'\',\'1\')" type="checkbox" class="checkPermiso_'.$Rmenu['men_id'].'_1" '.$ver.'></td>
                                    <td><input onclick="permiso(\''.$Rmenu['men_id'].'\',\''.$id.'\',\'2\')" type="checkbox" class="checkPermiso_'.$Rmenu['men_id'].'_2" '.$agregar.'></td>
                                    <td><input onclick="permiso(\''.$Rmenu['men_id'].'\',\''.$id.'\',\'3\')" type="checkbox" class="checkPermiso_'.$Rmenu['men_id'].'_3" '.$editar.'></td>
                                    <td><input onclick="permiso(\''.$Rmenu['men_id'].'\',\''.$id.'\',\'4\')" type="checkbox" class="checkPermiso_'.$Rmenu['men_id'].'_4" '.$eliminar.'></td>
                                    </tr>';
                            }
                            $html .='</tbody>
                                </table>
                            </div>
                            </div>
                        </div>';
                    }
                }
                $html .='</div></div>';
                $datos = ['titulo' => 'Control de accesos','id'=>$id, 'menus' => $html];
                return view('seguridad/roles/access', $datos);
            }else{
                return view('denegado');
            }
        }else{
            return redirect()->to(base_url("/"));
        }
    }

    public function register(){
        $rolModel = new rolesModelo();
        $validation = $this->validate([
            'rol' => 'required',
            'descripcion' => 'required'
        ]);
        if(!$validation){
            return redirect()->to(base_url("roles/add"))->withInput();
        }else{
            $rol = $this->request->getVar('rol');
            $descripcion = $this->request->getVar('descripcion');
            $data = [
                'rol_nombre' => $rol,
                'rol_descripcion' => $descripcion
            ];
            $rolModel->save($data);

            $session = Services::session();
            $session->setFlashdata("success", "Los datos se actualizaron correctamente");

            return redirect()->to(base_url("roles"));
        }
    }

    public function update($id){
        $rolModel = new rolesModelo();
        $validation = $this->validate([
            'rol' => 'required',
            'descripcion' => 'required'
        ]);
        if(!$validation){
            return redirect()->to(base_url("roles/edit/".$id))->withInput();
        }else{
            $rol = $this->request->getVar('rol');
            $descripcion = $this->request->getVar('descripcion');
            $data = [
                'rol_nombre' => $rol,
                'rol_descripcion' => $descripcion
            ];
            $rolModel->updateRoles($id, $data);

            $session = Services::session();
            $session->setFlashdata("success", "Los datos se actualizaron correctamente");

            return redirect()->to(base_url("roles"));
        }
    }

    public function delete($id){
        $rolModel = new rolesModelo();
        $data = [
            'rol_estado' => 0
        ];
        $rolModel->updateRoles($id, $data);

        $session = Services::session();
        $session->setFlashdata("success", "El registro se eliminó correctamente");

        return redirect()->to(base_url("roles"));
    }

    public function permiso(){
        $men_id = $_POST["menu"];
        $rol_id = $_POST["rol"];
        $opcion = $_POST["opcion"];
        $pso_id = $_POST["pso_id"];
        $estado = $_POST["estado"];
        $object = new permisoModelo();
        if($pso_id == 0){
            switch($opcion){
                case 0:
                    $data = [
                        'pso_rol_id' => $rol_id,
                        'pso_men_id' => $men_id
                    ];
                    break;
                case 1:
                    $data = [
                        'pso_rol_id' => $rol_id,
                        'pso_men_id' => $men_id,
                        'pso_ver' => 1
                    ];
                    break;
                case 2:
                    $data = [
                        'pso_rol_id' => $rol_id,
                        'pso_men_id' => $men_id,
                        'pso_agregar' => 1
                    ];
                    break;
                case 3:
                    $data = [
                        'pso_rol_id' => $rol_id,
                        'pso_men_id' => $men_id,
                        'pso_editar' => 1
                    ];
                    break;
                case 4:
                    $data = [
                        'pso_rol_id' => $rol_id,
                        'pso_men_id' => $men_id,
                        'pso_eliminar' => 1
                    ];
                    break;
            }
            $object->addModulo($data);
            echo $object->getInsertID();
        }else{
            if($opcion == 0){
                $object->deletePermiso($pso_id);
                echo 0;
            }else{
                switch($opcion){
                    case 1:$data = ['pso_ver' => $estado];break;
                    case 2:$data = ['pso_agregar' => $estado];break;
                    case 3:$data = ['pso_editar' => $estado];break;
                    case 4:$data = ['pso_eliminar' => $estado];break;
                }
                $object->updatePermiso($pso_id, $data);
                echo $pso_id;
            }
        }
    }

}
