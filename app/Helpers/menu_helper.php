<?php

use App\Models\moduloModelo;

    function menu(){
        $object = new moduloModelo();
        $menu = $object->readPadre();
        $html = '';
        foreach($menu as $item){
            $hijos = $object->menPadre($item['men_id']);
            $flag = true;
            foreach($hijos as $itemH){
                $permiso = $object->permiso(session('idrol'), $itemH['men_id']);
                if($permiso){
                    if($flag){
                        $html .='<li>
                                    <a href="javascript:;" class="has-arrow">
                                        <div class="parent-icon"><i class="'.$item['men_icono'].'"></i></div>
                                        <div class="menu-title">'. $item['men_nombre'] .'</div>
                                    </a>
                        <ul>';
                        $flag = false;
                    }
                    $html .= '<li><a href="'.base_url($itemH['men_url']).'"><i class="bx bx-right-arrow-alt"></i>'.$itemH['men_nombre'].'</a></li>';
                }
            }
            if(!$flag){
                $html .='</ul></li>';
            }
        }
        return $html;
    }

    function accede(){
        $url = url();
        $object = new moduloModelo();
        $pasa = $object->accede($url, session('idrol'));
        if($pasa){
            return true;
        }else{
            return false;
        }
    }

    function bloqueado(){
        $url = url();
        $object = new moduloModelo();
        $pasa = $object->accede($url, session('idrol'));
        if(!$pasa->pso_ver){
            if(!$pasa->pso_agregar){
                if(!$pasa->pso_editar){
                    if(!$pasa->pso_eliminar){
                        return false;
                    }else{
                        return true;
                    }
                }else{
                    return true;
                }
            }else{
                return true;
            }
        }else{
            return true;
        }
    }

    function ver(){
        $url = url();
        $object = new moduloModelo();
        $pasa = $object->accede($url, session('idrol'));
        if($pasa->pso_ver){
            return true;
        }else{
            return false;
        }
    }

    function agregar(){
        $url = url();
        $object = new moduloModelo();
        $pasa = $object->accede($url, session('idrol'));
        if($pasa->pso_agregar){
            return true;
        }else{
            return false;
        }
    }

    function editar(){
        $url = url();
        $object = new moduloModelo();
        $pasa = $object->accede($url, session('idrol'));
        if($pasa->pso_editar){
            return true;
        }else{
            return false;
        }
    }

    function eliminar(){
        $url = url();
        $object = new moduloModelo();
        $pasa = $object->accede($url, session('idrol'));
        if($pasa->pso_eliminar){
            return true;
        }else{
            return false;
        }
    }