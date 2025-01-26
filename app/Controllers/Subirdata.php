<?php

namespace App\Controllers;

use App\Models\archivosModelo;
use App\Models\datosModelo;
use CodeIgniter\Controller;
use PHPExcel_IOFactory;

class Subirdata extends Controller
{
    public function index()
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                $object = new archivosModelo();
                $items = $object->registros(1);
                $datos = ['titulo' => 'Subir datos','items'=>$items];
                return view('datos/subirdata/index', $datos);
            }else{
                return view('denegado');
            }
        }else{
            return redirect()->to(base_url("/"));
        }
    }

    public function detalle($arc_id)
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                require_once APPPATH . 'Libraries/Excel/PHPExcel.php';
                $object = new archivosModelo();
                $objectD = new datosModelo();
                $item = $object->archivo($arc_id);
                $archivo = "public/".$item->arc_ruta;
                $html = "";
                if (file_exists($archivo)) {
                    $ruta = $item->arc_ruta;
                    if($item->arc_tipo_archivo == 1){
                        $objPHPExcel = PHPExcel_IOFactory::identify($archivo);
                        $objPHPExcel = PHPExcel_IOFactory::createReader($objPHPExcel);
                        $objPHPExcel = $objPHPExcel->load($archivo);
                        $hoja = $objPHPExcel->getSheet(0);
                        $ultimaFila = $hoja->getHighestRow();
                        for($i = 2; $i <= $ultimaFila; $i++) {
                            $completo = $hoja->getCell("A$i")->getValue()." ".$hoja->getCell("B$i")->getValue();
                            $val = $objectD->validarNombres($completo);
                            $color = "";
                            if(!$val){
                                $color ="style='background-color: red'";
                            }

                            $val = $objectD->validarArchivo($arc_id);
                            foreach($val as $rowC){
                                if($hoja->getCell("C$i")->getValue() == $rowC->dat_email){
                                    $color ="style='background-color: green'";
                                    break;
                                }
                            }
                            $html .="<tr ".$color.">
                                        <td>".($i-1)."</td>
                                        <td>".strtoupper($hoja->getCell("A$i")->getValue())."</td>
                                        <td>".strtoupper($hoja->getCell("B$i")->getValue())."</td>
                                        <td>".$hoja->getCell("C$i")->getValue()."</td>
                                        <td>".strtoupper($hoja->getCell("D$i")->getValue())."</td>
                                        <td>".$hoja->getCell("E$i")->getValue()."</td>
                                        <td>".$hoja->getCell("F$i")->getValue()."</td>
                                    </tr>";
                        }
                    }else{
                        $lineas = file($archivo);
                        $c = 0;
                        foreach ($lineas as $linea_num => $linea){
                            if($c != 0){
                                $datos = preg_split("/[;,]/", $linea);
                                $completo = $datos[0]." ".$datos[1];
                                $val = $objectD->validarNombres($completo);
                                $color = "";
                                if(!$val){
                                    $color ="style='background-color: red'";
                                }
                                $val = $objectD->validarArchivo($arc_id);
                                foreach($val as $rowC){
                                    if($hoja->getCell("C$i")->getValue() == $rowC->dat_email){
                                        $color ="style='background-color: green'";
                                        break;
                                    }
                                }
                                $html .="<tr ".$color.">
                                        <td>".$c."</td>
                                        <td>".strtoupper($datos[0])."</td>
                                        <td>".strtoupper($datos[1])."</td>
                                        <td>".$datos[2]."</td>
                                        <td>".strtoupper($datos[3])."</td>
                                        <td>".$datos[4]."</td>
                                        <td>".$datos[5]."</td>
                                    </tr>";
                            }
                            $c++;
                        }
                    }
                } else {
                    $ruta = '';
                    $html = "<tr><td colspan='7'>El archivo fue eliminado o no se encuentra en la ruta especificada</td></tr>";
                }
                $datos = ['titulo' => 'Subir datos', 'table'=>$html, 'ruta'=>$ruta];
                return view('datos/subirdata/detalle', $datos);
            }else{
                return view('denegado');
            }
        }else{
            return redirect()->to(base_url("/"));
        }
    }

    public function validar(){
        $object = new datosModelo();
        if(isset($_FILES["archivo"])) {
            $tipoarchivo = $_POST['tipoarchivo'];
            $resultado = '';
            $dupli = 0;
            $invalido = 0;
            $archivo = $_FILES["archivo"]["tmp_name"];
            $archivoext = $_FILES["archivo"]["name"];
            $extension = pathinfo($archivoext, PATHINFO_EXTENSION);
            if($extension == 'csv' && $tipoarchivo == 1){
                echo "El tipo de archivo no coincide con el archivo seleccionado.";
                exit;
            }
            if(in_array($extension, ['xlsx','xls']) && $tipoarchivo == 2){
                echo "El tipo de archivo no coincide con el archivo seleccionado.";
                exit;
            }
            if(!in_array($extension, ['xlsx','xls','csv'])){
                echo "El archivo seleccionado no es permitido.";
                exit;
            }
            if($tipoarchivo == 1){
                require_once APPPATH . 'Libraries/Excel/PHPExcel.php';
                $objPHPExcel = PHPExcel_IOFactory::identify($archivo);
                $objPHPExcel = PHPExcel_IOFactory::createReader($objPHPExcel);
                $objPHPExcel = $objPHPExcel->load($archivo);
                $hoja = $objPHPExcel->getSheet(0);
                $ultimaFila = $hoja->getHighestRow();
                for($i = 2; $i <= $ultimaFila; $i++) {
                    $email = $hoja->getCell("C$i")->getValue();
                    if (validar_correo($email)){
                        $val = $object->validarCorreo($email);
                        if($val){
                            $dupli ++;
                        }
                    } else {
                        $invalido ++;
                    }
                }
            }else{
                $lineas = file($archivo);
                $c = 0;
                foreach ($lineas as $linea_num => $linea){
                    if($c != 0){
                        $datos = preg_split("/[;,]/", $linea);
                        $email = $datos[2];
                        if (validar_correo($email)){
                            $val = $object->validarCorreo($email);
                            if($val){
                                $dupli ++;
                            }
                        } else {
                            $invalido ++;
                        }
                    }
                    $c++;
                }
                $ultimaFila = $c;
            }

            $aregistrar = $ultimaFila-1 - $dupli -$invalido;
            $resultado .= "Total registros ".($ultimaFila-1)."<br>";
            $resultado .= "Valores dulpicado $dupli<br>";
            $resultado .= "Correos invalidos $invalido<br>";
            $resultado .= "Total a registrar $aregistrar<br>";
            if($aregistrar>0){
                $resultado .= "<button class='btn btn-success btn-sm' onclick='confirmarexcel()'>Guardar</button>";
            }
            echo $resultado;
        }
    }

    public function guardararchivo(){
        $object = new datosModelo();
        $objectArc = new archivosModelo();
        $tipoarchivo = $_POST['tipoarchivo'];
        $descripcion = $_POST['descripcion'];
        if(isset($_FILES["archivo"])) {
            $resultado = '';
            $dupli = 0;
            $invalido = 0;
            $archivo = $_FILES["archivo"]["tmp_name"];
            $nombrearchivo = $_FILES["archivo"]["name"];
            $nombreserver = 'ws_'.date("Ymd").'_'.date("His").'.'.strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));
            $data = [
                'arc_nombre'=>$nombrearchivo,
                'arc_ruta'=>"archivos/subirdatos/".$nombreserver,
                'arc_total'=>0,
                'arc_subido'=>0,
                'arc_usu_id'=>session('idusuario'),
                'arc_tipo_archivo'=>$tipoarchivo,
                'arc_descripcion'=>$descripcion
            ];
            if($objectArc->add($data)){
                $arc_id = $objectArc->getInsertID();
                $datos = '';
                if($tipoarchivo == 1){
                    require_once APPPATH . 'Libraries/Excel/PHPExcel.php';
                    $objPHPExcel = PHPExcel_IOFactory::identify($archivo);
                    $objPHPExcel = PHPExcel_IOFactory::createReader($objPHPExcel);
                    $objPHPExcel = $objPHPExcel->load($archivo);
                    $hoja = $objPHPExcel->getSheet(0);
                    $ultimaFila = $hoja->getHighestRow();
                    $total = $ultimaFila - 1;
                    for($i = 2; $i <= $ultimaFila; $i++) {
                        $nombre = strtoupper($hoja->getCell("A$i")->getValue());
                        $apellido = strtoupper($hoja->getCell("B$i")->getValue());
                        $email = $hoja->getCell("C$i")->getValue();
                        $status = strtoupper($hoja->getCell("D$i")->getValue());
                        $ultimoacceso = $hoja->getCell("E$i")->getValue();
                        $espacio = $hoja->getCell("F$i")->getValue();
                        $completo = $nombre.' '.$apellido;
                        if (validar_correo($email)){
                            $val = $object->validarCorreo($email);
                            if($val){
                                $dupli ++;
                            }else{
                                $datos .="('$nombre','$apellido','$completo','$email','$status','$ultimoacceso','$espacio',1,$arc_id,".session('idusuario')."),";
                            }
                        }else {
                            $invalido ++;
                        }
                    }
                }else{
                    $lineas = file($archivo);
                    $c = 0;
                    foreach ($lineas as $linea_num => $linea){
                        if($c != 0){
                            $item = preg_split("/[;,]/", $linea);
                            $nombre = $item[0];
                            $apellido = $item[1];
                            $email = $item[2];
                            $status = $item[3];
                            $ultimoacceso = $item[4];
                            $espacio = $item[5];
                            $completo = $nombre.' '.$apellido;
                            if (validar_correo($email)){
                                $val = $object->validarCorreo($email);
                                if($val){
                                    $dupli ++;
                                }else{
                                    $datos .="('$nombre','$apellido','$completo','$email','$status','$ultimoacceso','$espacio',1,$arc_id,".session('idusuario')."),";
                                }
                            }else {
                                $invalido ++;
                            }
                        }
                        $c++;
                    }
                    $total = $c-1;
                }
                $aregistrar = $total - $dupli -$invalido;
                $datos = substr($datos,0,-1);
                if(move_uploaded_file($_FILES['archivo']['tmp_name'],"public/archivos/subirdatos/".$nombreserver)){
                    if($object->insertarDatos($datos)){
                        $data = [
                            'arc_total'=>$total,
                            'arc_subido'=>$aregistrar
                        ];
                        $objectArc->upd($arc_id, $data);
                        $resultado = 'ok';
                    }else{
                        $objectArc->deleteArchivo($arc_id);
                        $resultado = 'Ocurrio un error al guardar el archivo';
                    }
                }else{
                    $objectArc->deleteArchivo($arc_id);
                    $resultado = 'Ocurrio un error al subir el archivo';
                }
            }else{
                $resultado = 'Ocurrio un error al subir el archivo';
            }
            echo $resultado;
        }
    }
}