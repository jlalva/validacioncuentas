<?php

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

    function validar_correo($correo) {
        $patron_correo = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        if (preg_match($patron_correo, $correo)) {
            return true;
        } else {
            return false;
        }
    }

    function generarCorreo($nombre, $apellido) {
        $partesNombre = explode(" ", $nombre);
        $primeraLetraNombre = substr($partesNombre[0], 0, 1);
        $segundaLetraNombre = isset($partesNombre[1]) ? substr($partesNombre[1], 0, 1) : "";
        $partesApellido = explode(" ", $apellido);
        if (isset($partesApellido[1]) && strlen($partesApellido[0]) == 2 && strlen($partesApellido[1]) == 2) {
            $apellidoCompuesto = strtolower(implode("", array_slice($partesApellido, 0, 3)));
            $correo = strtolower($primeraLetraNombre . $apellidoCompuesto);
        } else {
            $apellido1 = $partesApellido[0];
            $segundaLetraApellido = isset($partesApellido[1]) ? substr($partesApellido[1], 0, 1) : "";
            $correo = strtolower($primeraLetraNombre . $segundaLetraNombre . $apellido1 . $segundaLetraApellido);
        }
        return $correo;
    }

    function generarCorreo2($nombre, $apellido) {
        $partesNombre = explode(" ", $nombre);
        $primeraLetraNombre = substr($partesNombre[0], 0, 1);
        $segundaLetraNombre = isset($partesNombre[1]) ? substr($partesNombre[1], 0, 2) : "";
        $partesApellido = explode(" ", $apellido);

        if (isset($partesApellido[1]) && strlen($partesApellido[0]) == 2 && strlen($partesApellido[1]) == 2) {
            $segundaLetraApellido = isset($partesApellido[3]) ? substr($partesApellido[3], 0, 1) : "";
            $apellidoCompuesto = strtolower(implode("", array_slice($partesApellido, 0, 3)));
            $correo = strtolower($primeraLetraNombre.$segundaLetraNombre . $apellidoCompuesto.$segundaLetraApellido);
        } else {
            $apellido1 = $partesApellido[0];
            $segundaLetraApellido = isset($partesApellido[1]) ? substr($partesApellido[1], 0, 2) : "";
            $correo = strtolower($primeraLetraNombre . $segundaLetraNombre . $apellido1 . $segundaLetraApellido );
        }
        return $correo;
    }

    function generarCorreoCodigo($nombre, $apellido, $codigo) {
        $partesNombre = explode(" ", $nombre);
        $primeraLetraNombre = substr($partesNombre[0], 0, 1);
        $primeraLetraApellido = substr($apellido[0], 0, 1);
        $correo = strtolower($primeraLetraNombre . $primeraLetraApellido. $codigo);
        return $correo;
    }

    function generarCorreoSedeCodigo($sede, $codigo) {
        $primeraLetraSede = substr($sede[0], 0, 1);
        $correo = strtolower($primeraLetraSede . $codigo);
        return $correo;
    }

    function crearCarpetasPorFecha($rutaBase) {
        $anio = date("Y");
        $mes = date("m");
        switch($mes){
            case 1:$mes= '01-Enero';break;
            case 2:$mes= '02-Febrero';break;
            case 3:$mes= '03-Marzo';break;
            case 4:$mes= '04-Abril';break;
            case 5:$mes= '05-Mayo';break;
            case 6:$mes= '06-Junio';break;
            case 7:$mes= '07-Julio';break;
            case 8:$mes= '08-Agosto';break;
            case 9:$mes= '09-Setiembre';break;
            case 10:$mes= '10-Octubre';break;
            case 11:$mes= '11-Noviembre';break;
            case 11:$mes= '12-Diciembre';break;
        }
        $rutaAnio = $rutaBase . $anio;
        $rutaMes = $rutaAnio . '/' . $mes;
        if (!file_exists($rutaAnio)) {
            mkdir($rutaAnio, 0777, true);
        }
        if (!file_exists($rutaMes)) {
            mkdir($rutaMes, 0777, true);
        }
        return $rutaMes;
    }

    function meses($mesl) {
        $mesl = intval($mesl);
        switch($mesl){
            case 1:$mesl= 'Enero';break;
            case 2:$mesl= 'Febrero';break;
            case 3:$mesl= 'Marzo';break;
            case 4:$mesl= 'Abril';break;
            case 5:$mesl= 'Mayo';break;
            case 6:$mesl= 'Junio';break;
            case 7:$mesl= 'Julio';break;
            case 8:$mesl= 'Agosto';break;
            case 9:$mesl= 'Setiembre';break;
            case 10:$mesl= 'Octubre';break;
            case 11:$mesl= 'Noviembre';break;
            case 11:$mesl= 'Diciembre';break;
        }
        return $mesl;
    }

    function caracteres($texto) {
        $texto = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ñ'],
            ['A', 'E', 'I', 'O', 'U', 'Ñ'],
            $texto);
            return mb_strtoupper($texto, 'UTF-8');
    }