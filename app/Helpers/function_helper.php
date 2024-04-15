<?php

use App\Models\encuestaModelo;
use App\Models\matriculaModelo;
use App\Models\periodoModelo;
use App\Models\personaModelo;
use App\Models\secretariaModelo;

require_once APPPATH . 'Libraries/phpqrcode/qrlib.php';

    function encriptar($texto){
        $result = '';

        for($i = 0; $i < strlen($texto); $i++)
        {
            $char = substr($texto, $i, 1);
            $char = chr(ord($char));

            $result .= $char;
        }

        return base64_encode($result);
    }

    function desencriptar($texto){
        $result = '';
        $string = base64_decode($texto);

        for($i = 0; $i < strlen($string); $i++)
        {
            $char = substr($string, $i, 1);
            $char = chr(ord($char));

            $result .= $char;
        }

        return $result;
    }

    function idperiodo(){
        $object = new periodoModelo();
        $item = $object->Periodo(session('idperiodo'));
        return $item['ani_id'];
    }

    function anicodigo($idperiodo){
        $object = new periodoModelo();
        $item = $object->anio($idperiodo);
        return $item[0]['ani_codigo'];
    }

    function aniPeriodo($idperiodo){
        $object = new periodoModelo();
        $item = $object->anio($idperiodo);
        return $item[0]['ani_periodo'];
    }

    function Periodo($idperiodo){
        $object = new periodoModelo();
        $item = $object->Periodo($idperiodo);
        return $item[0]['ani_periodo'];
    }

    function ani_id($idperiodo){
        $object = new periodoModelo();
        $item = $object->idPeriodo($idperiodo);
        return $item[0]['ani_id'];
    }

    function nombreperiodo(){
        $object = new periodoModelo();
        $item = $object->Periodo(session('idperiodo'));
        return $item[0]['ani_periodo'];
    }

    function validarPersona($codigo){
        $object = new personaModelo();
        $item = $object->validarXcodigo($codigo);
        return $item;
    }

    function validarUbigeo($codigo){
        $object = new personaModelo();
        $item = $object->validarUbigeo($codigo);
        return $item;
    }

    function dia(){
        $dia = '';
        switch (strftime('%A')) {
            case 'Monday':$dia='Lunes';break;
            case 'Tuesday':$dia='Martes';break;
            case 'Wednesday':$dia='Miércoles';break;
            case 'Thursday':$dia='Jueves';break;
            case 'Friday':$dia='Viernes';break;
            case 'Saturday':$dia='Sábado';break;
            case 'Sunday':$dia='Domingo';break;
        }
        return $dia;
    }

    function mes(){
        $mes = '';
        switch(intval(date('m'))){
            case 1:$mes='Enero';break;
            case 2:$mes='Febrero';break;
            case 3:$mes='Marzo';break;
            case 4:$mes='Abril';break;
            case 5:$mes='Mayo';break;
            case 6:$mes='Junio';break;
            case 7:$mes='Julio';break;
            case 8:$mes='Agosto';break;
            case 9:$mes='Setiembre';break;
            case 10:$mes='Octubre';break;
            case 11:$mes='Noviembre';break;
            case 12:$mes='Diciembre';break;
        }
        return $mes;
    }

    function encuestaId(){
        $object = new encuestaModelo();
        $item = $object->abierta();
        return $item->enc_id;
    }

    function encuesta($periodo){
        $object = new encuestaModelo();
        if($periodo){
            $item = $object->encuesta($periodo);
            if($item){
                return $item->enc_nombre;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function itemsecretaria(){
        $object = new secretariaModelo();
        $item = $object->datos(session('per_id'));
        return $item;
    }

    function periodoactivo(){
        $object = new encuestaModelo();
        $item = $object->abierta();
        if($item){
            return $item->ani_periodo;
        }else{
            return false;
        }
    }

    function QrConstancia($periodo,$codigo,$datos){
        $ruta = "public/file/$periodo/QR";

        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }
        QRcode::png($datos, "$ruta/$codigo.png", QR_ECLEVEL_L, 10, 2);
    }

    function tieneCursos($codigo){
        $object = new matriculaModelo();
        $cursos = $object->cursosMatriculados($codigo);
        return count($cursos);
    }

    function getIp(){
        $ip_usuario = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
        return $ip_usuario;
    }

    function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }