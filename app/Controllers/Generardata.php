<?php

namespace App\Controllers;

use App\Models\archivosModelo;
use App\Models\datosModelo;
use App\Models\dominioModelo;
use App\Models\tipopersonaModelo;
use CodeIgniter\Controller;
use PHPExcel;
use PHPExcel_IOFactory;

class Generardata extends Controller
{
    public function index()
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                $object = new archivosModelo();
                $items = $object->registros(2);
                $objectTP = new tipopersonaModelo();
                $objectD = new dominioModelo();
                $tipo = $objectTP->reads();
                $dominio = $objectD->reads();
                $selTP = '';
                $selDom = '';
                foreach ($tipo as $row){
                    if($row['tip_estado'] == 1){
                        $selTP .= "<option value='".$row['tip_id']."'>".$row['tip_nombre']."</option>";
                    }
                }
                foreach ($dominio as $row){
                    if($row['dom_estado'] == 1){
                        $selDom .= "<option value='".$row['dom_id']."'>".$row['dom_nombre']."</option>";
                    }
                }
                $datos = ['titulo' => 'Generar datos','items'=>$items, 'tipopersona' => $selTP, 'dominio' => $selDom];
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
                $objectTP = new tipopersonaModelo();
                $objectD = new dominioModelo();
                $tipo = $objectTP->reads();
                $dominio = $objectD->reads();
                $selTP = '';
                $selDom = '';
                foreach ($tipo as $row){
                    if($row['tip_estado'] == 1){
                        $selTP .= "<option value='".$row['tip_id']."'>".$row['tip_nombre']."</option>";
                    }
                }
                foreach ($dominio as $row){
                    if($row['dom_estado'] == 1){
                        $selDom .= "<option value='".$row['dom_id']."'>".$row['dom_nombre']."</option>";
                    }
                }
                $datos = ['titulo' => 'Generar datos', 'tipopersona' => $selTP, 'dominio' => $selDom];
                return view('datos/generardata/add', $datos);
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
                    $completo = $hoja->getCell("E$i")->getValue();
                    $val = $objectD->validarNombres($completo);
                    if($val){
                        $icono ="<i class='bx bx-message-square-check' style='color:green'></i>";
                    }else{
                        $icono = "<i class='bx bx-message-square-x' style='color:red'></i>";
                    }
                    $html .="<tr>
                                <td>".($i-1)."</td>
                                <td>".$hoja->getCell("C$i")->getValue()."</td>
                                <td>".$hoja->getCell("D$i")->getValue()."</td>
                                <td>".$hoja->getCell("A$i")->getValue()."</td>
                                <td>".$hoja->getCell("B$i")->getValue()."</td>
                                <td>".$hoja->getCell("F$i")->getValue()."</td>
                                <td>".$hoja->getCell("G$i")->getValue()."</td>
                                <td>".$hoja->getCell("H$i")->getValue()."</td>
                                <td>".$hoja->getCell("I$i")->getValue()."</td>
                                <td>".$hoja->getCell("J$i")->getValue()."</td>
                                <td>$icono</td>
                            </tr>";
                }
                $datos = ['titulo' => 'Subir datos', 'table'=>$html];
                return view('datos/generardata/detalle', $datos);
            }else{
                return view('denegado');
            }
        }else{
            return redirect()->to(base_url("/"));
        }
    }

    public function preview(){
        if(isset($_FILES["archivo"])) {
            $archivo = $_FILES["archivo"]["tmp_name"];
            require_once APPPATH . 'Libraries/Excel/PHPExcel.php'; 
            $objPHPExcel = PHPExcel_IOFactory::identify($archivo);
            $objPHPExcel = PHPExcel_IOFactory::createReader($objPHPExcel);
            $objPHPExcel = $objPHPExcel->load($archivo);
            $hoja = $objPHPExcel->getSheet(0);
            $ultimaFila = $hoja->getHighestRow();
            $preview = '';
            $c = 0;
            for($i = 2; $i <= $ultimaFila; $i++) {
                $c++;
                $nombres = $hoja->getCell("A$i")->getValue();
                $apellidos = $hoja->getCell("B$i")->getValue();
                $codigo = $hoja->getCell("C$i")->getValue();
                $dni     = $hoja->getCell("D$i")->getValue();
                $celular = $hoja->getCell("E$i")->getValue();
                $correop = $hoja->getCell("F$i")->getValue();
                $facultad = $hoja->getCell("G$i")->getValue();
                $escuela = $hoja->getCell("H$i")->getValue();
                $sede = $hoja->getCell("I$i")->getValue();
                $preview .="<tr>
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
            echo $preview;
        }
    }

    public function procesar(){
        if(isset($_FILES["archivo"])) {
            $object = new datosModelo();
            $objectD = new dominioModelo();
            $archivo = $_FILES["archivo"]["tmp_name"];
            $dom_id = $_POST['dominio'];
            $dom = $objectD->readDominio($dom_id);
            $dominio = $dom['dom_nombre'];
            require_once APPPATH . 'Libraries/Excel/PHPExcel.php';
            $objPHPExcel = PHPExcel_IOFactory::identify($archivo);
            $objPHPExcel = PHPExcel_IOFactory::createReader($objPHPExcel);
            $objPHPExcel = $objPHPExcel->load($archivo);
            $hoja = $objPHPExcel->getSheet(0);
            $ultimaFila = $hoja->getHighestRow();
            $html = '';
            $c = 0;
            for($i = 2; $i <= $ultimaFila; $i++) {
                $c++;
                $nombres = $hoja->getCell("A$i")->getValue();
                $apellidos = $hoja->getCell("B$i")->getValue();
                $codigo = $hoja->getCell("C$i")->getValue();
                $apellido_limpio = preg_replace('/\b\w{1,2}\b/', '', $apellidos);
                $apellido_limpio = str_replace(
                    ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ'],
                    ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N'],
                    $apellido_limpio);
                $nombres_limpio = preg_replace('/\b\w{1,2}\b/', '', $nombres);
                $nombres_limpio = str_replace(
                    ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ'],
                    ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N'],
                    $nombres_limpio);
                $nombrecompleto = $nombres_limpio. ' '. $apellido_limpio;
                $val = $object->validarNombres(trim($nombrecompleto));
                $correo = '';
                $clave = strtoupper(substr($nombres,0,1)).strtolower(substr($apellidos,0,1)).$codigo.'*';
                $observacion = '';
                if($val){
                    $observacion = 'Usuario existente';
                }else{
                    $correo = generarCorreo(trim($nombres_limpio),trim($apellido_limpio)).$dominio;
                    $val = $object->validarCorreo($correo);
                    if($val){
                        $correo = generarCorreo2(trim($nombres_limpio),trim($apellido_limpio)).$dominio;
                    }
                }
                $html .="<tr>
                            <td>$c</td>
                            <td>$nombres_limpio</td>
                            <td>$apellido_limpio</td>
                            <td>$correo</td>
                            <td>$clave</td>
                            <td>$observacion</td>
                        </tr>";
            }
            echo $html;
        }
    }

    public function guardararchivo(){
        $object = new datosModelo();
        $objectArc = new archivosModelo();
        if(isset($_FILES["archivo"])) {
            $objectD = new dominioModelo();
            $dom_id = $_POST['dominio'];
            $dom = $objectD->readDominio($dom_id);
            $dominio = $dom['dom_nombre'];
            $resultado = '';
            $dupli = 0;
            $invalido = 0;
            $archivo = $_FILES["archivo"]["tmp_name"];
            $nombrearchivo = $_FILES["archivo"]["name"];
            $nombreserver = 'g'.date("YmdHis").'.'.strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));
            $data = [
                'arc_nombre'=>$nombrearchivo,
                'arc_ruta'=>"archivos/generardatos/".$nombreserver,
                'arc_total'=>0,
                'arc_subido'=>0,
                'arc_usu_id'=>session('idusuario'),
                'arc_origen'=>2
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
                $invalido = 0;
                for($i = 2; $i <= $ultimaFila; $i++) {
                    $nombres = $hoja->getCell("A$i")->getValue();
                    $apellidos = $hoja->getCell("B$i")->getValue();
                    $codigo = $hoja->getCell("C$i")->getValue();
                    $dni = $hoja->getCell("D$i")->getValue();
                    $completo = $nombres.' '.$apellidos;
                    $celular = $hoja->getCell("E$i")->getValue();
                    $correopersonal = $hoja->getCell("F$i")->getValue();
                    $facultad = $hoja->getCell("G$i")->getValue();
                    $escuela = $hoja->getCell("H$i")->getValue();
                    $sede = $hoja->getCell("I$i")->getValue();
                    $completo = preg_replace('/\b\w{1,2}\b/', '', $completo);
                    $completo = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N'],
                        $completo);
                    $val = $object->validarNombres(trim($completo));
                    $correo = '';
                    $clave = strtoupper(substr($nombres,0,1)).strtolower(substr($apellidos,0,1)).$codigo.'*';
                    $apellido_limpio = preg_replace('/\b\w{1,2}\b/', '', $apellidos);
                    $apellido_limpio = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N'],
                        $apellido_limpio);
                    $nombres_limpio = preg_replace('/\b\w{1,2}\b/', '', $nombres);
                    $nombres_limpio = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N'],
                        $nombres_limpio);
                    if(!$val){
                        $correo = generarCorreo(trim($nombres_limpio),trim($apellido_limpio)).$dominio;
                        $val = $object->validarCorreo($correo);
                        if($val){
                            $correo = generarCorreo2(trim($nombres_limpio),trim($apellido_limpio)).$dominio;
                        }else{
                            if (validar_correo($correo)){
                                $datos .="('$codigo','$dni','$nombres_limpio','$apellido_limpio','$completo','$correo','$celular','$correopersonal','$facultad','$escuela',
                                '$sede','$clave','ACTIVO','".date('Y-m-d H:i:s')."','0GB',2,$arc_id,".session('idusuario')."),";
                            }else{
                                $invalido++;
                            }
                        }
                    }
                }
                $aregistrar = $total -$invalido;
                $datos = substr($datos,0,-1);
                if(move_uploaded_file($_FILES['archivo']['tmp_name'],"public/archivos/generardatos/".$nombreserver)){
                    if($object->insertarDatosGenerados($datos)){
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

    public function exportar($arc_id)
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                require_once APPPATH . 'Libraries/Excel/PHPExcel.php';
                $object = new datosModelo();
                $items = $object->exportar($arc_id);
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->setActiveSheetIndex(0);
                $hoja = $objPHPExcel->getActiveSheet();
                $hoja->setCellValue("A1",utf8_encode('CODIGO'));
                $hoja->setCellValue("B1",utf8_decode('NOMBRES'));
                $hoja->setCellValue("C1",utf8_decode('APELLIDOS'));
                $hoja->setCellValue("D1",utf8_decode('NOMBRES COMPLESTOS'));
                $hoja->setCellValue("E1",utf8_decode('EMAIL GENERADO'));
                $hoja->setCellValue("F1",utf8_decode('ESTADO'));
                $hoja->setCellValue("G1",utf8_decode('ULTIMO ACCESO'));
                $hoja->setCellValue("H1",utf8_decode('ESPACIO USO'));
                $c=0;
                $fila = 1;
                foreach ($items as $row) {
                    $c++;
                    $fila++;
                    $hoja->setCellValue("A$fila",utf8_decode($row->dat_codigo));
                    $hoja->setCellValue("B$fila",utf8_decode($row->dat_nombres));
                    $hoja->setCellValue("C$fila",utf8_decode($row->dat_apellidos));
                    $hoja->setCellValue("D$fila",utf8_decode($row->dat_nombres_completos));
                    $hoja->setCellValue("E$fila",utf8_decode($row->dat_email));
                    $hoja->setCellValue("F$fila",utf8_decode($row->dat_estado));
                    $hoja->setCellValue("G$fila",utf8_decode($row->dat_ultimo_acceso));
                    $hoja->setCellValue("H$fila",utf8_decode($row->dat_espacio_uso));
                }
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="Archivos Exportados.xls"');
                header('Cache-Control: max-age=0');
                header('Cache-Control: max-age=1');
                header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
                header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
                header ('Cache-Control: cache, must-revalidate');
                header ('Pragma: public'); 
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');
                exit;
            }else{
                return view('denegado');
            }
        }else{
            return redirect()->to(base_url("/"));
        }
    }
}