<?php

use App\Models\empresaModelo;

    function razonsocial(){
        $object = new empresaModelo();
        $item = $object->datosEmpresa();
        if($item){
            return $item['emp_razonsocial'];
        }else{
            return '';
        }
    }

    function siglas(){
        $object = new empresaModelo();
        $item = $object->datosEmpresa();
        if($item){
            return $item['emp_siglas'];
        }else{
            return '';
        }
    }

    function web(){
        $object = new empresaModelo();
        $item = $object->datosEmpresa();
        if($item){
            return $item['emp_sitioweb'];
        }else{
            return '';
        }
    }

    function facebook(){
        $object = new empresaModelo();
        $item = $object->datosEmpresa();
        if($item){
            return $item['emp_facebook'];
        }else{
            return '';
        }
    }

    function twitter(){
        $object = new empresaModelo();
        $item = $object->datosEmpresa();
        if($item){
            return $item['emp_twitter'];
        }else{
            return '';
        }
    }

    function instagram(){
        $object = new empresaModelo();
        $item = $object->datosEmpresa();
        if($item){
            return $item['emp_instagram'];
        }else{
            return '';
        }
    }

    function youtube(){
        $object = new empresaModelo();
        $item = $object->datosEmpresa();
        if($item){
            return $item['emp_youtube'];
        }else{
            return '';
        }
    }

    function telefono(){
        $object = new empresaModelo();
        $item = $object->datosEmpresa();
        if($item){
            return $item['emp_telefono'];
        }else{
            return '';
        }
    }

    function direccion(){
        $object = new empresaModelo();
        $item = $object->datosEmpresa();
        if($item){
            return $item['emp_direccion'];
        }else{
            return '';
        }
    }

    function logo(){
        $object = new empresaModelo();
        $item = $object->datosEmpresa();
        if($item){
            return $item['emp_logo'];
        }else{
            return '';
        }
    }

    function fondo(){
        $object = new empresaModelo();
        $item = $object->datosEmpresa();
        if($item){
            return $item['emp_imgfondo'];
        }else{
            return '';
        }
    }

    function marcaagua(){
        $object = new empresaModelo();
        $item = $object->datosEmpresa();
        if($item){
            return $item['emp_imgmarcaagua'];
        }else{
            return '';
        }
    }

    function ubigeo(){
        $object = new empresaModelo();
        $item = $object->datosEmpresa();
        if($item){
            return $item['emp_ubi_id'];
        }else{
            return '';
        }
    }

    function distrito(){
        $object = new empresaModelo();
        $ubigeo = ubigeo();
        $item = $object->ubigeo($ubigeo);
        return $item->distrito;
    }