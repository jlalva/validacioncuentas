<?php

namespace App\Controllers;

use App\Models\archivosModelo;
use App\Models\datosModelo;
use CodeIgniter\Controller;
use PHPExcel_IOFactory;

class Generardata extends Controller
{
    public function index()
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                $object = new archivosModelo();
                $items = $object->registros(2);
                $datos = ['titulo' => 'Generar datos','items'=>$items];
                return view('datos/generardata/index', $datos);
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
                $datos = ['titulo' => 'Generar datos'];
                return view('datos/generardata/add', $datos);
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
            $html = '';
            $c = 0;
            for($i = 2; $i <= $ultimaFila; $i++) {
                $c++;
                $codigo = $hoja->getCell("A$i")->getValue();
                $dni     = $hoja->getCell("B$i")->getValue();
                $nombres = $hoja->getCell("C$i")->getValue();
                $apellidos = $hoja->getCell("D$i")->getValue();
                $celular = $hoja->getCell("F$i")->getValue();
                $correop = $hoja->getCell("G$i")->getValue();
                $facultad = $hoja->getCell("H$i")->getValue();
                $escuela = $hoja->getCell("I$i")->getValue();
                $sede = $hoja->getCell("J$i")->getValue();
                /*if (validar_correo($email)){
                    $val = $object->validarCorreo($email);
                    if($val){
                        $dupli ++;
                    }
                } else {
                    $invalido ++;
                }*/
                $html .="<tr>
                            <td>$c</td>
                            <td>$nombres</td>
                            <td>$apellidos</td>
                            <td>$codigo</td>
                            <td>$dni</td>
                            <td>$celular</td>
                            <td>$correop</td>
                            <td>$facultad</td>
                            <td>$escuela</td>
                            <td>$sede</td>
                        </tr>";
            }
            $resultado = $html;

            /*if($aregistrar>0){
                $resultado .= "<button class='btn btn-success btn-sm' onclick='confirmarexcel()'>Confirmar validación</button>";
            }*/
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
                    //$ultimoacceso = strtotime($ultimoacceso);
                    //$ultimoacceso = date('Y-m-d H:i:s', $ultimoacceso);
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