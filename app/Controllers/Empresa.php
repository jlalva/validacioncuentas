<?php

namespace App\Controllers;

use App\Models\empresaModelo;
use App\Models\usuariosModelo;
use CodeIgniter\Controller;

class Empresa extends Controller
{
    public function index()
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                $object = new empresaModelo();
                $items = $object->reads();
                $datos = ['titulo' => 'Empresa', 'items' => $items];
                return view('gestionar/empresa/index', $datos);
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
                $objectUser = new usuariosModelo();
                $dpto = $objectUser->ubigeoDepartamento();
                $datos = ['titulo' => 'Agregar Empresa', 'departamentos' => $dpto];
                return view('gestionar/empresa/add', $datos);
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
                $object = new empresaModelo();
                $objectUser = new usuariosModelo();
                $dpto = $objectUser->ubigeoDepartamento();
                $item = $object->read($id);
                $ubigeo = '';
                $provincia = '';
                $distrito = '';
                if($item['emp_ubi_id']){
                    $ubigeo = $objectUser->ubigeo($item['emp_ubi_id']);
                    $provincia = $objectUser->ubigeoProvincia($ubigeo->dpto);
                    $distrito = $objectUser->ubigeoDistrito($ubigeo->dpto, $ubigeo->prov);
                }
                $datos = ['titulo' => 'Editar empresa','item' => $item, 'id'=>$id, 'departamentos' => $dpto, 'provincia'=>$provincia, 'distrito'=>$distrito, 'ubigeo' => $ubigeo];
                return view('gestionar/empresa/edit', $datos);
            }else{
                return view('denegado');
            }
        }else{
            return redirect()->to(base_url("/"));
        }
    }

    public function register(){
        $object = new empresaModelo();
        $emp_ruc = $_POST['emp_ruc'];
        $emp_razonsocial = $_POST['emp_razonsocial'];
        $emp_siglas = $_POST['emp_siglas'];
        $emp_telefono = $_POST['emp_telefono'];
        $distrito = $_POST['distrito'];
        $emp_direccion = $_POST['emp_direccion'];
        $emp_fechafundacion = $_POST['emp_fechafundacion'];
        $emp_descripcion = $_POST['emp_descripcion'];
        $emp_sitioweb = $_POST['emp_sitioweb'];
        $emp_facebook = $_POST['emp_facebook'];
        $emp_youtube = $_POST['emp_youtube'];
        $emp_instagram = $_POST['emp_instagram'];
        $emp_twitter = $_POST['emp_twitter'];
        $emp_correo = $_POST['emp_correo'];
        $logo = $emp_ruc.'-logo.'.strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $fondo = $emp_ruc.'-fondo.'.strtolower(pathinfo($_FILES['fondo']['name'], PATHINFO_EXTENSION));
        $banner = $emp_ruc.'-banner.'.strtolower(pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION));
        $marcaagua = $emp_ruc.'-marcaagua.'.strtolower(pathinfo($_FILES['marcaagua']['name'], PATHINFO_EXTENSION));
        $fcarga = 0;
        if(move_uploaded_file($_FILES['logo']['tmp_name'],"public/images/FOTO_EMPRESA/".$logo)){
            if(move_uploaded_file($_FILES['fondo']['tmp_name'],"public/images/FOTO_EMPRESA/".$fondo)){
                if(move_uploaded_file($_FILES['banner']['tmp_name'],"public/images/FOTO_EMPRESA/".$banner)){
                    if(move_uploaded_file($_FILES['marcaagua']['tmp_name'],"public/images/FOTO_EMPRESA/".$marcaagua)){
                        $fcarga = 1;
                        $object->cerrar();
                    }
                }
            }
        }
        if($fcarga == 1){
            $data = [
                'emp_razonsocial' => $emp_razonsocial,
                'emp_ruc' => $emp_ruc,
                'emp_siglas' => $emp_siglas,
                'emp_ubi_id' => $distrito,
                'emp_telefono' => $emp_telefono,
                'emp_direccion' => $emp_direccion,
                'emp_fechafundacion' => $emp_fechafundacion,
                'emp_descripcion' => $emp_descripcion,
                'emp_sitioweb' => $emp_sitioweb,
                'emp_facebook' => $emp_facebook,
                'emp_youtube' => $emp_youtube,
                'emp_instagram' => $emp_instagram,
                'emp_twitter' => $emp_twitter,
                'emp_correo' => $emp_correo,
                'emp_logo' => $logo,
                'emp_imgfondo' => $fondo,
                'emp_banner' => $banner,
                'emp_imgmarcaagua' => $marcaagua
            ];
            if($object->add($data)){
                $res = 1;
            }else{
                $res = 0;
            }
        }else{
            $res = 2;
        }
        echo $res;
    }

    public function update(){
        $object = new empresaModelo();
        $emp_id = $_POST['emp_id'];
        $emp_ruc = $_POST['emp_ruc'];
        $emp_razonsocial = $_POST['emp_razonsocial'];
        $emp_siglas = $_POST['emp_siglas'];
        $emp_telefono = $_POST['emp_telefono'];
        $distrito = $_POST['distrito'];
        $emp_direccion = $_POST['emp_direccion'];
        $emp_fechafundacion = $_POST['emp_fechafundacion'];
        $emp_descripcion = $_POST['emp_descripcion'];
        $emp_estado = $_POST['emp_estado'];
        $emp_sitioweb = $_POST['emp_sitioweb'];
        $emp_facebook = $_POST['emp_facebook'];
        $emp_youtube = $_POST['emp_youtube'];
        $emp_instagram = $_POST['emp_instagram'];
        $emp_twitter = $_POST['emp_twitter'];
        $emp_correo = $_POST['emp_correo'];
        $fcarga = 1;
        $data = [
            'emp_razonsocial' => $emp_razonsocial,
            'emp_ruc' => $emp_ruc,
            'emp_siglas' => $emp_siglas,
            'emp_ubi_id' => $distrito,
            'emp_telefono' => $emp_telefono,
            'emp_direccion' => $emp_direccion,
            'emp_fechafundacion' => $emp_fechafundacion,
            'emp_descripcion' => $emp_descripcion,
            'emp_sitioweb' => $emp_sitioweb,
            'emp_facebook' => $emp_facebook,
            'emp_youtube' => $emp_youtube,
            'emp_instagram' => $emp_instagram,
            'emp_twitter' => $emp_twitter,
            'emp_correo' => $emp_correo,
            'emp_estado' => $emp_estado
        ];
        if(isset($_FILES['logo'])){
            $logo = $emp_ruc.'-logo.'.strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
            if(!move_uploaded_file($_FILES['logo']['tmp_name'],"public/images/FOTO_EMPRESA/".$logo)){
                $fcarga = 0;
            }else{
                $data['emp_logo'] = $logo;
            }
        }
        if(isset($_FILES['fondo'])){
            $fondo = $emp_ruc.'-fondo.'.strtolower(pathinfo($_FILES['fondo']['name'], PATHINFO_EXTENSION));
            if(!move_uploaded_file($_FILES['fondo']['tmp_name'],"public/images/FOTO_EMPRESA/".$fondo)){
                $fcarga = 0;
            }else{
                $data['emp_imgfondo'] = $fondo;
            }
        }
        if(isset($_FILES['banner'])){
            $banner = $emp_ruc.'-banner.'.strtolower(pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION));
            if(!move_uploaded_file($_FILES['banner']['tmp_name'],"public/images/FOTO_EMPRESA/".$banner)){
                $fcarga = 0;
            }else{
                $data['emp_banner'] = $banner;
            }
        }
        if(isset($_FILES['marcaagua'])){
            $marcaagua = $emp_ruc.'-marcaagua.'.strtolower(pathinfo($_FILES['marcaagua']['name'], PATHINFO_EXTENSION));
            if(!move_uploaded_file($_FILES['marcaagua']['tmp_name'],"public/images/FOTO_EMPRESA/".$marcaagua)){
                $fcarga = 0;
            }else{
                $data['emp_imgmarcaagua'] = $marcaagua;
            }
        }

        if($fcarga == 1){
            if($object->upd($emp_id, $data)){
                $res = 1;
            }else{
                $res = 0;
            }
        }else{
            $res = 2;
        }
        echo $res;
    }

    public function eliminar(){
        $object = new empresaModelo();
        $id = $_POST['emp_id'];
        $data = [
            'emp_estado' => 0
        ];
        if($object->upd($id, $data)){
            echo 'ok';
        }else{
            echo 'error';
        }
    }
}