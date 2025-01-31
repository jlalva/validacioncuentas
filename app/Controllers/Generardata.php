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
                $ruta = $item->arc_ruta;
                $objPHPExcel = PHPExcel_IOFactory::identify($archivo);
                $objPHPExcel = PHPExcel_IOFactory::createReader($objPHPExcel);
                $objPHPExcel = $objPHPExcel->load($archivo);
                $hoja = $objPHPExcel->getSheet(0);
                $ultimaFila = $hoja->getHighestRow();
                $html = "<thead>
                        <tr class='headings'>
                            <th class='column-title' style='text-align: center;'>ITEM</th>
                            <th class='column-title' style='text-align: center;'>".strtoupper($hoja->getCell("A1")->getValue())."</th>
                            <th class='column-title' style='text-align: center;'>".strtoupper($hoja->getCell("B1")->getValue())."</th>
                            <th class='column-title' style='text-align: center;'>".strtoupper($hoja->getCell("C1")->getValue())."</th>
                            <th class='column-title' style='text-align: center;'>".strtoupper($hoja->getCell("D1")->getValue())."</th>
                            <th class='column-title' style='text-align: center;'>".strtoupper($hoja->getCell("E1")->getValue())."</th>
                            <th class='column-title' style='text-align: center;'>".strtoupper($hoja->getCell("F1")->getValue())."</th>
                            <th class='column-title' style='text-align: center;'>".strtoupper($hoja->getCell("G1")->getValue())."</th>
                            <th class='column-title' style='text-align: center;'>".strtoupper($hoja->getCell("H1")->getValue())."</th>
                            <th class='column-title' style='text-align: center;'>".strtoupper($hoja->getCell("I1")->getValue())."</th>
                        </tr>
                    </thead>
                    <tbody>";
                for($i = 2; $i <= $ultimaFila; $i++) {
                    $completo = $hoja->getCell("A$i")->getValue(). ' '.$hoja->getCell("B$i")->getValue();
                    //$val = $objectD->validarNombres($completo);
                    $color = '';
                    /*if($val){
                        $color = "style='background:green'";
                    }*/
                    $html .="<tr $color>
                                <td>".($i-1)."</td>
                                <td>".$hoja->getCell("A$i")->getValue()."</td>
                                <td>".$hoja->getCell("B$i")->getValue()."</td>
                                <td>".$hoja->getCell("C$i")->getValue()."</td>
                                <td>".$hoja->getCell("D$i")->getValue()."</td>
                                <td>".$hoja->getCell("E$i")->getValue()."</td>
                                <td>".$hoja->getCell("F$i")->getValue()."</td>
                                <td>".$hoja->getCell("G$i")->getValue()."</td>
                                <td>".$hoja->getCell("H$i")->getValue()."</td>
                                <td>".$hoja->getCell("I$i")->getValue()."</td>
                            </tr>";
                }
                $html .="</tbody>";
                $datos = ['titulo' => 'Subir datos', 'table'=>$html, 'ruta' => $ruta];
                return view('datos/generardata/detalle', $datos);
            }else{
                return view('denegado');
            }
        }else{
            return redirect()->to(base_url("/"));
        }
    }

    public function cuentas($arc_id)
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                $object = new datosModelo();
                $objectA = new archivosModelo();
                $itemA = $objectA->archivo($arc_id);
                $tipopersona = $itemA->arc_tipo_persona;
                $html ="<thead>
                        <th>ITEM</th>
                        <th>NOMBRES</th>
                        <th>APELLIDOS</th>
                        <th>CODIGO</th>
                        <th>DNI</th>
                        <th>CELULAR</th>
                        <th>CORREO PERSONAL</th>";
                if($tipopersona == 1){
                    $html .="<th>UNIDAD/OFICINA</th>";
                }
                if($tipopersona == 3){
                    $html .="<th>FACULTAD</th>
                        <th>ESCUELA</th>
                        <th>SEDE</th>";
                }
                $html .="<th style='background:green'>CORREO INSTITUCIONAL</th>
                        <th style='background:green'>CONTRASEÑA</th>
                    </thead>
                    <tbody>";
                $items = $object->validarArchivo($arc_id);
                $c = 0;
                foreach($items as $row){
                    $c++;
                    $html .="<tr>
                        <td>".$c."</td>
                        <td>".$row->dat_nombres."</td>
                        <td>".$row->dat_apellidos."</td>
                        <td>".$row->dat_codigo."</td>
                        <td>".$row->dat_dni."</td>
                        <td>".$row->dat_celular."</td>
                        <td>".$row->dat_correo_personal."</td>";
                    if($tipopersona == 1){
                        $html .="<td>".$row->dat_unidad."</td>";
                    }
                    if($tipopersona == 3){
                        $html .="<td>".$row->dat_facultad."</td>
                        <td>".$row->dat_escuela."</td>
                        <td>".$row->dat_sede."</td>";
                    }
                    $html .="<td style='background:green'>".$row->dat_email."</td>
                        <td style='background:green'>".$row->dat_clave."</td>
                    </tr>";
                }
                $datos = ['titulo' => 'Cuentas Creadas', 'tabla'=>$html, 'idarchivo' => $arc_id];
                return view('datos/generardata/cuentas', $datos);
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
            $tipopersona = $_POST["tipopersona"];
            $hoja = $objPHPExcel->getSheet(0);
            $ultimaFila = $hoja->getHighestRow();
            $preview = '<thead>
                            <tr class="headings">
                                <th class="column-title" style="text-align: center;">ITEM</th>
                                <th class="column-title" style="text-align: center;">NOMBRE</th>
                                <th class="column-title" style="text-align: center;">APELLIDO</th>
                                <th class="column-title" style="text-align: center;">CODIGO</th>
                                <th class="column-title" style="text-align: center;">DNI</th>
                                <th class="column-title" style="text-align: center;">CELULAR</th>
                                <th class="column-title" style="text-align: center;">CORREO PERSONAL</th>';
                        if($tipopersona == 1){
                            $preview .= '<th class="column-title" style="text-align: center;">UNIDAD/OFICINA</th>';
                        }
                        if($tipopersona == 2){
                            $preview .= '<th class="column-title" style="text-align: center;">DEPARTAMENTO</th>';
                        }
                        if($tipopersona == 3){
                            $preview .= '<th class="column-title" style="text-align: center;">FACULTAD</th>
                                <th class="column-title" style="text-align: center;">ESCUELA</th>
                                <th class="column-title" style="text-align: center;">SEDE</th>';
                        }
                $preview .= '</tr>
                        </thead>
                        <tbody>';
            $c = 0;
            for($i = 2; $i <= $ultimaFila; $i++) {
                $c++;
                $nombres = $hoja->getCell("A$i")->getValue();
                $apellidos = $hoja->getCell("B$i")->getValue();
                $codigo = $hoja->getCell("C$i")->getValue();
                $dni     = $hoja->getCell("D$i")->getValue();
                $celular = $hoja->getCell("E$i")->getValue();
                $correop = $hoja->getCell("F$i")->getValue();
                if($tipopersona == 1){
                    $unidad = $hoja->getCell("G$i")->getValue();
                }
                if($tipopersona == 2){
                    $departamento = $hoja->getCell("G$i")->getValue();
                }
                if($tipopersona == 3){
                    $facultad = $hoja->getCell("G$i")->getValue();
                    $escuela = $hoja->getCell("H$i")->getValue();
                    $sede = $hoja->getCell("I$i")->getValue();
                }
                $preview .="<tr>
                            <td>$c</td>
                            <td>$nombres</td>
                            <td>$apellidos</td>
                            <td>$codigo</td>
                            <td>$dni</td>
                            <td>$celular</td>
                            <td>$correop</td>";
                        if($tipopersona == 1){
                            $preview .="<td>$unidad</td>";
                        }
                        if($tipopersona == 2){
                            $preview .="<td>$departamento</td>";
                        }
                        if($tipopersona == 3){
                            $preview .="<td>$facultad</td>
                                <td>$escuela</td>
                                <td>$sede</td>";
                        }
                        $preview .="</tr>";
            }
            $preview .="</tbody>";
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
            $tipopersona = $_POST['tipopersona'];
            $tipoarchivo = $_POST['tipoarchivo'];
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
                'arc_tipo_persona'=>$tipopersona,
                'arc_tipo_archivo'=>$tipoarchivo,
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
                    if($tipopersona == 1){
                        $unidad = $hoja->getCell("G$i")->getValue();
                    }
                    if($tipopersona == 2){
                        $departamento = $hoja->getCell("G$i")->getValue();
                    }
                    if($tipopersona == 3){
                        $facultad = $hoja->getCell("G$i")->getValue();
                        $escuela = $hoja->getCell("H$i")->getValue();
                        $sede = $hoja->getCell("I$i")->getValue();
                    }
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
                                if($tipopersona == 1){
                                    $datos .="('$codigo','$dni','$nombres_limpio','$apellido_limpio','$completo','$correo','$celular','$correopersonal','$unidad',
                                    '$clave','ACTIVO','".date('Y-m-d H:i:s')."','0GB',2,$arc_id,".session('idusuario')."),";
                                }
                                if($tipopersona == 2){
                                    $datos .="('$codigo','$dni','$nombres_limpio','$apellido_limpio','$completo','$correo','$celular','$correopersonal','$departamento',
                                    '$clave','ACTIVO','".date('Y-m-d H:i:s')."','0GB',2,$arc_id,".session('idusuario')."),";
                                }
                                if($tipopersona == 3){
                                    $datos .="('$codigo','$dni','$nombres_limpio','$apellido_limpio','$completo','$correo','$celular','$correopersonal','$facultad','$escuela',
                                    '$sede','$clave','ACTIVO','".date('Y-m-d H:i:s')."','0GB',2,$arc_id,".session('idusuario')."),";
                                }
                            }else{
                                $invalido++;
                            }
                        }
                    }
                }
                $aregistrar = $total -$invalido;
                $datos = substr($datos,0,-1);
                if(move_uploaded_file($_FILES['archivo']['tmp_name'],"public/archivos/generardatos/".$nombreserver)){
                    if($object->insertarDatosGenerados($datos,$tipopersona)){
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

    public function descargarcuentas($arc_id)
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                $quitarTildes = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                                'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                                'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                                'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                                'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
                require_once APPPATH . 'Libraries/Excel/PHPExcel.php';
                $object = new datosModelo();
                $objectA = new archivosModelo();
                $itemA = $objectA->archivo($arc_id);
                $tipopersona = $itemA->arc_tipo_persona;
                $nombreArchivo = '';
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->setActiveSheetIndex(0);
                $hoja = $objPHPExcel->getActiveSheet();
                $hoja->setCellValue("A1",utf8_encode('ITEM'));
                $hoja->setCellValue("B1",utf8_decode('NOMBRES'));
                $hoja->setCellValue("C1",utf8_decode('APELLIDOS'));
                $hoja->setCellValue("D1",utf8_decode('CODIGO'));
                $hoja->setCellValue("E1",utf8_decode('DNI'));
                $hoja->setCellValue("F1",utf8_decode('CELULAR'));
                $hoja->setCellValue("G1",utf8_decode('CORREO PERSONAL'));
                if($tipopersona == 1){
                    $nombreArchivo = 'excel_administrativo';
                    $hoja->setCellValue("H1",utf8_decode('UNIDAD/OFICINA'));
                    $hoja->setCellValue("I1",utf8_decode('CORREO INSTITUCIONAL'));
                    $hoja->setCellValue("J1",utf8_decode('CONTRASENIA'));
                }
                if($tipopersona == 2){
                    $nombreArchivo = 'excel_docente';
                    $hoja->setCellValue("H1",utf8_decode('DEPARTAMENTO'));
                    $hoja->setCellValue("I1",utf8_decode('CORREO INSTITUCIONAL'));
                    $hoja->setCellValue("J1",utf8_decode('CONTRASENIA'));
                }
                if($tipopersona == 3){
                    $nombreArchivo = 'excel_estudiante';
                    $hoja->setCellValue("H1",utf8_decode('FACULTAD'));
                    $hoja->setCellValue("I1",utf8_decode('ESCUELA'));
                    $hoja->setCellValue("J1",utf8_decode('SEDE'));
                    $hoja->setCellValue("K1",utf8_decode('CORREO INSTITUCIONAL'));
                    $hoja->setCellValue("L1",utf8_decode('CONTRASENIA'));
                }
                $items = $object->validarArchivo($arc_id);
                $c = 0;
                $fila = 1;
                foreach($items as $row){
                    $c++;
                    $fila++;
                    $hoja->setCellValue("A$fila",$c);
                    $hoja->setCellValue("B$fila",utf8_decode($row->dat_nombres));
                    $hoja->setCellValue("C$fila",utf8_decode($row->dat_apellidos));
                    $hoja->setCellValue("D$fila",utf8_decode($row->dat_codigo));
                    $hoja->setCellValue("E$fila",utf8_decode($row->dat_dni));
                    $hoja->setCellValue("F$fila",utf8_decode($row->dat_celular));
                    $hoja->setCellValue("G$fila",utf8_decode($row->dat_correo_personal));
                    if($tipopersona == 1){
                        $hoja->setCellValue("H$fila",utf8_decode(strtr($row->dat_unidad, $quitarTildes)));
                        $hoja->setCellValue("I$fila",utf8_decode($row->dat_email));
                        $hoja->setCellValue("J$fila",utf8_decode($row->dat_clave));
                    }
                    if($tipopersona == 2){
                        $hoja->setCellValue("H$fila",utf8_decode(strtr($row->dat_departamento, $quitarTildes)));
                        $hoja->setCellValue("I$fila",utf8_decode($row->dat_email));
                        $hoja->setCellValue("J$fila",utf8_decode($row->dat_clave));
                    }
                    if($tipopersona == 3){
                        $hoja->setCellValue("H$fila",utf8_decode(strtr($row->dat_facultad, $quitarTildes)));
                        $hoja->setCellValue("I$fila",utf8_decode($row->dat_escuela));
                        $hoja->setCellValue("J$fila",utf8_decode($row->dat_sede));
                        $hoja->setCellValue("K$fila",utf8_decode($row->dat_email));
                        $hoja->setCellValue("L$fila",utf8_decode($row->dat_clave));
                    }
                }
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$nombreArchivo.'.xls"');
                header('Cache-Control: max-age=0');
                header('Cache-Control: max-age=1');
                header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
                header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
                header ('Cache-Control: cache, must-revalidate');
                header ('Pragma: public'); 
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');
                exit;
            }
        }
    }
}