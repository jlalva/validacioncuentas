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

    public function add()
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                $datos = ['titulo' => 'Subir datos'];
                return view('datos/subirdata/add', $datos);
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
                $objPHPExcel = PHPExcel_IOFactory::identify($archivo);
                $objPHPExcel = PHPExcel_IOFactory::createReader($objPHPExcel);
                $objPHPExcel = $objPHPExcel->load($archivo);
                $hoja = $objPHPExcel->getSheet(0);
                $ultimaFila = $hoja->getHighestRow();
                $html = "";
                for($i = 2; $i <= $ultimaFila; $i++) {
                    $completo = $hoja->getCell("A$i")->getValue()." ".$hoja->getCell("B$i")->getValue();
                    $val = $objectD->validarNombres($completo);
                    if($val){
                        $icono ="<i class='bx bx-message-square-check' style='color:green'></i>";
                    }else{
                        $icono = "<i class='bx bx-message-square-x' style='color:red'></i>";
                    }
                    $html .="<tr>
                                <td>".($i-1)."</td>
                                <td>".$hoja->getCell("A$i")->getValue()."</td>
                                <td>".$hoja->getCell("B$i")->getValue()."</td>
                                <td>".$hoja->getCell("C$i")->getValue()."</td>
                                <td>".$hoja->getCell("D$i")->getValue()."</td>
                                <td>".$hoja->getCell("E$i")->getValue()."</td>
                                <td>".$hoja->getCell("F$i")->getValue()."</td>
                                <td>$icono</td>
                            </tr>";
                }
                $datos = ['titulo' => 'Subir datos', 'table'=>$html];
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
            $archivo = $_FILES["archivo"]["tmp_name"];
            require_once APPPATH . 'Libraries/Excel/PHPExcel.php';
            $objPHPExcel = PHPExcel_IOFactory::identify($archivo);
            $objPHPExcel = PHPExcel_IOFactory::createReader($objPHPExcel);
            $objPHPExcel = $objPHPExcel->load($archivo);
            $hoja = $objPHPExcel->getSheet(0);
            $ultimaFila = $hoja->getHighestRow();
            $resultado = '';
            $dupli = 0;
            $invalido = 0;
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
            $aregistrar = $ultimaFila-1 - $dupli -$invalido;
            $resultado .= "Total registros ".($ultimaFila-1)."<br>";
            $resultado .= "Valores dulpicado $dupli<br>";
            $resultado .= "Correos invalidos $invalido<br>";
            $resultado .= "Total a registrar $aregistrar<br>";
            if($aregistrar>0){
                $resultado .= "<button class='btn btn-success btn-sm' onclick='confirmarexcel()'>Confirmar validación</button>";
            }
            echo $resultado;
        }
    }

    public function guardararchivoexcel(){
        $object = new datosModelo();
        $objectArc = new archivosModelo();
        if(isset($_FILES["archivo"])) {
            $resultado = '';
            $dupli = 0;
            $invalido = 0;
            $archivo = $_FILES["archivo"]["tmp_name"];
            $nombrearchivo = $_FILES["archivo"]["name"];
            $nombreserver = 's'.date("YmdHis").'.'.strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));
            $data = [
                'arc_nombre'=>$nombrearchivo,
                'arc_ruta'=>"archivos/subirdatos/".$nombreserver,
                'arc_total'=>0,
                'arc_subido'=>0,
                'arc_usu_id'=>session('idusuario')
            ];
            if($objectArc->add($data)){
                $arc_id = $objectArc->getInsertID();
                require_once APPPATH . 'Libraries/Excel/PHPExcel.php';
                $objPHPExcel = PHPExcel_IOFactory::identify($archivo);
                $objPHPExcel = PHPExcel_IOFactory::createReader($objPHPExcel);
                $objPHPExcel = $objPHPExcel->load($archivo);
                $hoja = $objPHPExcel->getSheet(0);
                $ultimaFila = $hoja->getHighestRow();
                $total = $ultimaFila - 1;
                $datos = '';
                for($i = 2; $i <= $ultimaFila; $i++) {
                    $nombre = $hoja->getCell("A$i")->getValue();
                    $apellido = $hoja->getCell("B$i")->getValue();
                    $email = $hoja->getCell("C$i")->getValue();
                    $status = $hoja->getCell("D$i")->getValue();
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