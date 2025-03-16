<?php

namespace App\Controllers;

use App\Models\archivosModelo;
use App\Models\datosModelo;
use App\Models\dominioModelo;
use App\Models\peyorativosModelo;
use App\Models\tipopersonaModelo;
use CodeIgniter\Controller;
use PDF;
use PDFS;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Border;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Fill;
use PHPExcel_Worksheet_Drawing;

class Generardata extends Controller
{
    public function index()
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                $idempresa = empresaActiva();
                $emp_id = $idempresa->emp_id;
                $object = new archivosModelo();
                $items = $object->registros(2,$emp_id);
                $objectTP = new tipopersonaModelo();
                $objectD = new dominioModelo();
                $objectP = new peyorativosModelo();
                $objectDa = new datosModelo();
                $tipo = $objectTP->reads();
                $dominio = $objectD->reads($emp_id);
                $peyorativo = $objectP->validarPeyorativo($emp_id);
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
                for($i = 0; $i < count($items); $i++){
                    $items[$i]->peyorativo = 'no';
                    $itemsD = $objectDa->validarArchivo($items[$i]->arc_id);
                    foreach($itemsD as $row){
                        foreach($peyorativo as $rowP){
                            if (str_contains($row->dat_email, $rowP->pey_nombre)) {
                                $items[$i]->peyorativo = 'si';
                                break;
                            }
                        }
                    }
                }
                $datos = ['titulo' => 'Generar datos','items'=>$items, 'tipopersona' => $selTP, 'dominio' => $selDom, 'peyorativo' => $peyorativo];
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
                        $selTP .= "<option value='".$row['tip_id']."'>".strtoupper($row['tip_nombre'])."</option>";
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
                $archivo = $item->arc_ruta;
                $ruta = $item->arc_ruta;
                $tipopersona = $item->arc_tipo_persona;
                $objPHPExcel = PHPExcel_IOFactory::identify($archivo);
                $objPHPExcel = PHPExcel_IOFactory::createReader($objPHPExcel);
                $objPHPExcel = $objPHPExcel->load($archivo);
                $hoja = $objPHPExcel->getSheet(0);
                $ultimaFila = $hoja->getHighestRow();
                $html = '<thead>
                            <tr class="headings">
                                <th class="column-title" style="text-align: center;">ITEM</th>
                                <th class="column-title" style="text-align: center;">CODIGO</th>
                                <th class="column-title" style="text-align: center;">NOMBRE</th>
                                <th class="column-title" style="text-align: center;">APELLIDO</th>
                                <th class="column-title" style="text-align: center;">DNI</th>
                                <th class="column-title" style="text-align: center;">CELULAR</th>
                                <th class="column-title" style="text-align: center;">CORREO PERSONAL</th>';
                        if($tipopersona == 1){
                            $html .= '<th class="column-title" style="text-align: center;">UNIDAD/OFICINA</th>';
                        }
                        if($tipopersona == 2){
                            $html .= '<th class="column-title" style="text-align: center;">DEPARTAMENTO</th>';
                        }
                        if($tipopersona == 3){
                            $html .= '<th class="column-title" style="text-align: center;">FACULTAD</th>
                                <th class="column-title" style="text-align: center;">ESCUELA</th>
                                <th class="column-title" style="text-align: center;">SEDE</th>';
                        }
                $html .= '</tr>
                        </thead>
                        <tbody>';
                for($i = 2; $i <= $ultimaFila; $i++) {
                    $nombres = $hoja->getCell("B$i")->getValue();
                $apellidos = $hoja->getCell("C$i")->getValue();
                $codigo = $hoja->getCell("A$i")->getValue();
                $dni     = $hoja->getCell("D$i")->getValue();
                $celular = $hoja->getCell("E$i")->getValue();
                $correop = $hoja->getCell("F$i")->getValue();
                $apellido = str_replace(
                    ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                    ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                    $apellidos);
                $nombres = str_replace(
                    ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                    ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                    $nombres);
                if($tipopersona == 1){
                    $unidad = $hoja->getCell("G$i")->getValue();
                    $unidad = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                        $unidad);
                    $unidad = strtoupper($unidad);
                }
                if($tipopersona == 2){
                    $departamento = $hoja->getCell("G$i")->getValue();
                    $departamento = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                        $departamento);
                    $departamento = strtoupper($departamento);
                }
                if($tipopersona == 3){
                    $facultad = $hoja->getCell("G$i")->getValue();
                    $facultad = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                        $facultad);
                    $escuela = $hoja->getCell("H$i")->getValue();
                    $escuela = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                        $escuela);
                    $sede = $hoja->getCell("I$i")->getValue();
                    $sede = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                        $sede);
                    $facultad = strtoupper($facultad);
                    $escuela = strtoupper($escuela);
                    $sede = strtoupper($sede);
                }
                $nombres = strtoupper($nombres);
                $apellido = strtoupper($apellido);
                    $html .="<tr>
                                <td>".($i-1)."</td>
                                <td>".$codigo."</td>
                                <td>".$nombres."</td>
                                <td>".$apellido."</td>
                                <td>".$dni."</td>
                                <td>".$celular."</td>
                                <td>".$correop."</td>";
                                if($tipopersona == 1){
                                    $html .="<td>$unidad</td>";
                                }
                                if($tipopersona == 2){
                                    $html .="<td>$departamento</td>";
                                }
                                if($tipopersona == 3){
                                    $html .="<td>$facultad</td>
                                        <td>$escuela</td>
                                        <td>$sede</td>";
                                }
                            $html .="</tr>";
                }
                $html .="</tbody>";
                $datos = ['titulo' => 'Subir datos', 'table'=>$html, 'ruta' => $ruta, 'id_arch'=>$arc_id];
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
                $html ="<thead style='text-align: center'>
                        <th>ITEM</th>
                        <th>CODIGO</th>
                        <th>DNI</th>
                        <th>NOMBRES</th>
                        <th>APELLIDOS</th>
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
                        <td>".$row->dat_codigo."</td>
                        <td>".$row->dat_dni."</td>
                        <td>".strtoupper($row->dat_nombres)."</td>
                        <td>".strtoupper($row->dat_apellidos)."</td>
                        <td>".$row->dat_celular."</td>
                        <td>".$row->dat_correo_personal."</td>";
                    if($tipopersona == 1){
                        $html .="<td>".strtoupper($row->dat_unidad)."</td>";
                    }
                    if($tipopersona == 3){
                        $html .="<td>".strtoupper($row->dat_facultad)."</td>
                        <td>".strtoupper($row->dat_escuela)."</td>
                        <td>".strtoupper($row->dat_sede)."</td>";
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

    public function cacafonias($arc_id)
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                $object = new datosModelo();
                $objectA = new archivosModelo();
                $objectP = new peyorativosModelo();
                $idempresa = empresaActiva();
                $emp_id = $idempresa->emp_id;
                $peyorativo = $objectP->validarPeyorativo($emp_id);
                $html ="<thead style='text-align: center'>
                        <th>ITEM</th>
                        <th>CODIGO</th>
                        <th>DNI</th>
                        <th>NOMBRES</th>
                        <th>APELLIDOS</th>
                        <th>CELULAR</th>
                        <th>CORREO PERSONAL</th>
                        <th style='background:red'>CORREO INSTITUCIONAL</th>
                        <th>CONTRASEÑA</th>
                        <th>ACCION</th>
                    </thead>
                    <tbody>";
                $items = $object->validarArchivo($arc_id);
                $c = 0;
                foreach($items as $row){
                    foreach($peyorativo as $rowP){
                        if (str_contains($row->dat_email, $rowP->pey_nombre)) {
                            $acorreo = explode("@",$row->dat_email);
                            $correo = $acorreo[0];
                            $dominio = '@'.$acorreo[1];
                            $c++;
                            $html .="<tr>
                                <td style='text-align: center'>".$c."</td>
                                <td style='text-align: center'>".$row->dat_codigo."</td>
                                <td style='text-align: center'>".$row->dat_dni."</td>
                                <td style='text-align: center'>".strtoupper($row->dat_nombres)."</td>
                                <td style='text-align: center'>".strtoupper($row->dat_apellidos)."</td>
                                <td style='text-align: center'>".$row->dat_celular."</td>
                                <td style='text-align: center'>".$row->dat_correo_personal."</td>";
                            $html .="<td style='background:red'>".$row->dat_email."</td>
                                <td style='text-align: center'>".$row->dat_clave."</td>";
                            $html .='<td style="text-align: center"><button class="btn btn-success btn-sm" title="EDITAR" data-bs-toggle="modal" data-bs-target="#modalEditar" onclick="datoseditarcacafonia('.$row->dat_id.',\''.$correo.'\',\''.$dominio.'\')"><i class="bx bx-edit"></i></button></td>
                            </tr>';
                            break;
                        }
                    }
                }
                $datos = ['titulo' => 'Cacafonías', 'tabla'=>$html, 'idarchivo' => $arc_id];
                return view('datos/generardata/cacafonias', $datos);
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
                                <th class="column-title" style="text-align: center;">CODIGO</th>
                                <th class="column-title" style="text-align: center;">NOMBRE</th>
                                <th class="column-title" style="text-align: center;">APELLIDO</th>
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
                $nombres = $hoja->getCell("B$i")->getValue();
                $apellidos = $hoja->getCell("C$i")->getValue();
                $codigo = $hoja->getCell("A$i")->getValue();
                $dni     = $hoja->getCell("D$i")->getValue();
                $celular = $hoja->getCell("E$i")->getValue();
                $correop = $hoja->getCell("F$i")->getValue();
                $apellido = str_replace(
                    ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                    ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                    $apellidos);
                $nombres = str_replace(
                    ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                    ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                    $nombres);
                if($tipopersona == 1){
                    $unidad = $hoja->getCell("G$i")->getValue();
                    $unidad = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                        $unidad);
                    $unidad = strtoupper($unidad);
                }
                if($tipopersona == 2){
                    $departamento = $hoja->getCell("G$i")->getValue();
                    $departamento = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                        $departamento);
                    $departamento = strtoupper($departamento);
                }
                if($tipopersona == 3){
                    $facultad = $hoja->getCell("G$i")->getValue();
                    $facultad = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                        $facultad);
                    $escuela = $hoja->getCell("H$i")->getValue();
                    $escuela = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                        $escuela);
                    $sede = $hoja->getCell("I$i")->getValue();
                    $sede = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                        $sede);
                    $facultad = strtoupper($facultad);
                    $escuela = strtoupper($escuela);
                    $sede = strtoupper($sede);
                }
                $nombres = strtoupper($nombres);
                $apellido = strtoupper($apellido);
                $preview .="<tr>
                            <td>$c</td>
                            <td>$codigo</td>
                            <td>$nombres</td>
                            <td>$apellido</td>
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

    public function procesar() {
        if (isset($_FILES["archivo"])) {
            $idempresa = empresaActiva();
            $emp_id = $idempresa->emp_id;
            $object = new datosModelo();
            $objectD = new dominioModelo();
            $archivo = $_FILES["archivo"]["tmp_name"];
            $dom_id = $_POST['dominio'];
            $generarcon = $_POST['generarcon'];
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

            $nombresBD = array_flip(array_column($object->listarNombres($emp_id), 'dat_nombres_completos'));
            $correosBD = array_flip(array_column($object->listarCorreos($emp_id), 'dat_email'));

            for ($i = 2; $i <= $ultimaFila; $i++) {
                $c++;
                $nombres = $hoja->getCell("B$i")->getValue();
                $apellidos = $hoja->getCell("C$i")->getValue();
                $codigo = $hoja->getCell("A$i")->getValue();
                $sede = $hoja->getCell("I$i")->getValue();

                $apellido_correo = str_replace(
                    ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ'],
                    ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N'],
                    $apellidos);
                $nombres_correo = str_replace(
                    ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ'],
                    ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N'],
                    $nombres);

                $apellido_limpio = str_replace(
                    ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                    ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                    $apellidos);

                $nombres_limpio = str_replace(
                    ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                    ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                    $nombres);

                $nombrecompleto = trim($nombres_limpio) . ' ' . trim($apellido_limpio);
                $observacion = isset($nombresBD[$nombrecompleto]) ? 'Usuario existente' : '';
                $correo = '';
                $clave = strtoupper(substr($nombres_correo, 0, 1)) . strtolower(substr($apellido_correo, 0, 1)) . $codigo . '*@';
                if (!$observacion) {
                    switch($generarcon){
                        case 1:$correo = generarCorreo(trim($nombres_correo), trim($apellido_correo)) . $dominio;
                            if (isset($correosBD[$correo])) {
                                $correo = generarCorreo2(trim($nombres_correo), trim($apellido_correo)) . $dominio;
                            }
                            break;
                        case 2:$correo = generarCorreoCodigo(trim($nombres_correo), trim($apellido_correo), $codigo) . $dominio;break;
                        case 3:$correo = generarCorreoSedeCodigo($sede, $codigo) . $dominio;break;
                    }
                }
                $nombres = strtoupper($nombres_limpio);
                $apellidos = strtoupper($apellido_limpio);
                $html .= "<tr>
                            <td>$c</td>
                            <td>$nombres</td>
                            <td>$apellidos</td>
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
            $inicio = microtime(true);
            $idempresa = empresaActiva();
            $emp_id = $idempresa->emp_id;
            $empresa = strtoupper($idempresa->emp_razonsocial);
            $objectD = new dominioModelo();
            $dom_id = $_POST['dominio'];
            $tipopersona = $_POST['tipopersona'];
            $tipoarchivo = $_POST['tipoarchivo'];
            $generarcon = $_POST['generarcon'];
            $dom = $objectD->readDominio($dom_id);
            $dominio = $dom['dom_nombre'];
            $resultado = '';
            $dupli = 0;
            $invalido = 0;
            $archivo = $_FILES["archivo"]["tmp_name"];
            $nombrearchivo = $_FILES["archivo"]["name"];
            switch($tipopersona){
                case 1:$nombreserver = 'g_Administrativo_'.date("Ymd").'_'.date("His").'.'.strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));break;
                case 2:$nombreserver = 'g_Docente_'.date("Ymd").'_'.date("His").'.'.strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));break;
                case 3:$nombreserver = 'g_Estudiante_'.date("Ymd").'_'.date("His").'.'.strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));break;
                default: $nombreserver = 'g_NoDefinido_'.date("Ymd").'_'.date("His").'.'.strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));break;
            }
            $data = [
                'arc_nombre'=>$nombrearchivo,
                'arc_total'=>0,
                'arc_subido'=>0,
                'arc_usu_id'=>session('idusuario'),
                'arc_tipo_persona'=>$tipopersona,
                'arc_tipo_archivo'=>$tipoarchivo,
                'arc_origen'=>2,
                'arc_emp_id'=>$emp_id
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
                $correosBD = array_flip(array_column($object->listarCorreos($emp_id), 'dat_email'));
                for($i = 2; $i <= $ultimaFila; $i++) {
                    $nombres = strtoupper($hoja->getCell("B$i")->getValue());
                    $apellidos = strtoupper($hoja->getCell("C$i")->getValue());
                    $codigo = $hoja->getCell("A$i")->getValue();
                    $dni = $hoja->getCell("D$i")->getValue();
                    $completo = $nombres.' '.$apellidos;
                    $celular = $hoja->getCell("E$i")->getValue();
                    $correopersonal = $hoja->getCell("F$i")->getValue();
                    if($tipopersona == 1){
                        $unidad = $hoja->getCell("G$i")->getValue();
                        $unidad = str_replace(
                            ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                            ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'],
                            $unidad);
                        $unidad = strtoupper($unidad);
                    }
                    if($tipopersona == 2){
                        $departamento = $hoja->getCell("G$i")->getValue();
                        $departamento = str_replace(
                            ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                            ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'],
                            $departamento);
                        $departamento = strtoupper($departamento);
                    }
                    if($tipopersona == 3){
                        $facultad = $hoja->getCell("G$i")->getValue();
                        $facultad = str_replace(
                            ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                            ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'],
                            $facultad);
                        $escuela = $hoja->getCell("H$i")->getValue();
                        $escuela = str_replace(
                            ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                            ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'],
                            $escuela);
                        $sede = $hoja->getCell("I$i")->getValue();
                        $sede = str_replace(
                            ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                            ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'],
                            $sede);
                        $facultad = strtoupper($facultad);
                        $escuela = strtoupper($escuela);
                        $sede = strtoupper($sede);
                    }
                    $completo = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                        ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'],
                        $completo);
                    $val = $object->validarNombres(trim($completo),$emp_id);
                    $correo = '';
                    $apellido_correo = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N'],
                        $apellidos);
                    $nombres_correo = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N'],
                        $nombres);

                    $apellido_limpio = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                        ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'],
                        $apellidos);

                    $nombres_limpio = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                        ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'],
                        $nombres);
                        $clave = strtoupper(substr($nombres_correo,0,1)).strtolower(substr($apellido_correo,0,1)).$codigo.'*@';
                    if(!$val){
                        switch($generarcon){
                            case 1:$correo = generarCorreo(trim($nombres_correo),trim($apellido_correo)).$dominio;
                                    //$val = $object->validarCorreo($correo);
                                    if (isset($correosBD[$correo])) {
                                        $correo = generarCorreo2(trim($nombres_correo),trim($apellido_correo)).$dominio;
                                    }else{
                                        if (validar_correo($correo)){
                                            if($tipopersona == 1){
                                                $datos .="('$codigo','$dni','$nombres_limpio','$apellido_limpio','$completo','$correo','$celular','$correopersonal','$unidad',
                                                '$clave','ACTIVO','".date('Y-m-d H:i:s')."','0GB',2,$arc_id,".session('idusuario').",$emp_id),";
                                            }
                                            if($tipopersona == 2){
                                                $datos .="('$codigo','$dni','$nombres_limpio','$apellido_limpio','$completo','$correo','$celular','$correopersonal','$departamento',
                                                '$clave','ACTIVO','".date('Y-m-d H:i:s')."','0GB',2,$arc_id,".session('idusuario').",$emp_id),";
                                            }
                                            if($tipopersona == 3){
                                                $datos .="('$codigo','$dni','$nombres_limpio','$apellido_limpio','$completo','$correo','$celular','$correopersonal','$facultad','$escuela',
                                                '$sede','$clave','ACTIVO','".date('Y-m-d H:i:s')."','0GB',2,$arc_id,".session('idusuario').",$emp_id),";
                                            }
                                        }else{
                                            $invalido++;
                                        }
                                    }
                                break;
                            case 2:$correo = generarCorreoCodigo(trim($nombres_correo), trim($apellido_correo), $codigo) . $dominio;
                                    if (validar_correo($correo)){
                                        if($tipopersona == 1){
                                            $datos .="('$codigo','$dni','$nombres_limpio','$apellido_limpio','$completo','$correo','$celular','$correopersonal','$unidad',
                                            '$clave','ACTIVO','".date('Y-m-d H:i:s')."','0GB',2,$arc_id,".session('idusuario').",$emp_id),";
                                        }
                                        if($tipopersona == 2){
                                            $datos .="('$codigo','$dni','$nombres_limpio','$apellido_limpio','$completo','$correo','$celular','$correopersonal','$departamento',
                                            '$clave','ACTIVO','".date('Y-m-d H:i:s')."','0GB',2,$arc_id,".session('idusuario').",$emp_id),";
                                        }
                                        if($tipopersona == 3){
                                            $datos .="('$codigo','$dni','$nombres_limpio','$apellido_limpio','$completo','$correo','$celular','$correopersonal','$facultad','$escuela',
                                            '$sede','$clave','ACTIVO','".date('Y-m-d H:i:s')."','0GB',2,$arc_id,".session('idusuario').",$emp_id),";
                                        }
                                    }else{
                                        $invalido++;
                                    }
                            break;
                            case 3:$correo = generarCorreoSedeCodigo($sede, $codigo) . $dominio.'';
                                if (validar_correo($correo)){
                                    if($tipopersona == 1){
                                        $datos .="('$codigo','$dni','$nombres_limpio','$apellido_limpio','$completo','$correo','$celular','$correopersonal','$unidad',
                                        '$clave','ACTIVO','".date('Y-m-d H:i:s')."','0GB',2,$arc_id,".session('idusuario').",$emp_id),";
                                    }
                                    if($tipopersona == 2){
                                        $datos .="('$codigo','$dni','$nombres_limpio','$apellido_limpio','$completo','$correo','$celular','$correopersonal','$departamento',
                                        '$clave','ACTIVO','".date('Y-m-d H:i:s')."','0GB',2,$arc_id,".session('idusuario').",$emp_id),";
                                    }
                                    if($tipopersona == 3){
                                        $datos .="('$codigo','$dni','$nombres_limpio','$apellido_limpio','$completo','$correo','$celular','$correopersonal','$facultad','$escuela',
                                        '$sede','$clave','ACTIVO','".date('Y-m-d H:i:s')."','0GB',2,$arc_id,".session('idusuario').",$emp_id),";
                                    }
                                }else{
                                    $invalido++;
                                }
                            break;
                        }
                    }
                }
                $aregistrar = $total -$invalido;
                $datos = substr($datos,0,-1);
                $rutaempresa = "public/empresas/$empresa";
                if (!file_exists($rutaempresa)) {
                    mkdir($rutaempresa, 0777, true);
                }
                $ruta = crearCarpetasPorFecha("$rutaempresa/archivos/FUENTE_DATOS_NUEVAS_CUENTAS/");
                if(move_uploaded_file($_FILES['archivo']['tmp_name'],$ruta.'/'.$nombreserver)){
                    if($object->insertarDatosGenerados($datos,$tipopersona)){
                        $fin = microtime(true);
                        $tiempoTotalSegundos = $fin - $inicio;
                        $horas = floor($tiempoTotalSegundos / 3600);
                        $minutos = floor(($tiempoTotalSegundos % 3600) / 60);
                        $segundos = floor($tiempoTotalSegundos % 60);
                        $tiempoFormateado = sprintf("%02d:%02d:%02d", $horas, $minutos, $segundos);
                        $data = [
                            'arc_total'=>$total,
                            'arc_subido'=>$aregistrar,
                            'arc_ruta'=>$ruta.'/'.$nombreserver,
                            'arc_tiempo' => $tiempoFormateado
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

    public function pdf($arc_id)
    {
        require_once APPPATH . 'Libraries/PDF.php';
        $quitarTildes = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                                'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                                'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                                'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                                'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
        $object = new datosModelo();
        $objectA = new archivosModelo();
        $itemA = $objectA->archivo($arc_id);
        $tipopersona = $itemA->arc_tipo_persona;
        $nombreArchivo  =  $itemA->arc_nombre;
        $nombreArchivo = explode('.',$nombreArchivo);
        $titulo = $nombreArchivo[0];
        $nombreArchivo = $nombreArchivo[0].'.pdf';
        $pdf = new PDF();
        $pdf->AddPage('L');
        $pdf->AliasNbPages();
        $pdf->SetXY(50, 30);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(0, 51, 102); // Azul oscuro
        $pdf->SetTextColor(255, 255, 255); // Texto blanco
        $pdf->Cell(190, 10, 'LISTA DE CORREOS INSTITUCIONALES DE NUEVOS '.$itemA->tip_nombre.'S', 1, 1, 'C', true);
        $pdf->Ln();
        $pdf->SetFont('Arial','B',7);
        $pdf->SetTextColor(0, 0, 0); // Texto negro
        if($tipopersona == 3){
            $x = [0=>10,1=>30,2=>30,3=>17,4=>14,5=>17,6=>30,7=>50,8=>34,9=>20,10=>50,11=>30,12=>28,13=>15];
        }else{
            $x = [0=>10,1=>45,2=>45,3=>17,4=>14,5=>17,6=>30,7=>45,8=>34,9=>20,10=>45,11=>30,12=>28,13=>15];
        }
        $y = 5;
        $pdf->Cell($x[0],$y, utf8_encode('ITEM'),1,0,'C');
        $pdf->Cell($x[3],$y, utf8_decode('CODIGO'),1,0,'C');
        $pdf->Cell($x[4],$y, utf8_decode('DNI'),1,0,'C');
        $pdf->Cell($x[1],$y, utf8_decode('NOMBRES'),1,0,'C');
        $pdf->Cell($x[2],$y, utf8_decode('APELLIDOS'),1,0,'C');
        $pdf->Cell($x[5],$y, utf8_decode('CELULAR'),1,0,'C');
        $pdf->Cell($x[6],$y, utf8_decode('CORREO PERSONAL'),1,0,'C');
        if($tipopersona == 1){
            $pdf->Cell($x[7],$y,utf8_decode('UNIDAD/OFICINA'),1,0,'C');
        }
        if($tipopersona == 2){
            $pdf->Cell($x[10],$y,utf8_decode('DEPARTAMENTO'),1,0,'C');
        }
        if($tipopersona == 3){
            $pdf->Cell($x[11],$y,utf8_decode('FACULTAD'),1,0,'C');
            $pdf->Cell($x[12],$y,utf8_decode('ESCUELA'),1,0,'C');
            $pdf->Cell($x[13],$y,utf8_decode('SEDE'),1,0,'C');
        }
        $pdf->Cell($x[8],$y,utf8_decode('CORREO INSTITUCIONAL'),1,0,'C');
        $pdf->Cell($x[9],$y,utf8_decode('CONTRASEÑA'),1,1,'C');
        $items = $object->validarArchivo($arc_id);
        $c = 0;
        $pdf->SetFont('Arial','',6);
        foreach($items as $row){
            $c++;
            $pdf->Cell($x[0],$y,$c,1);
            $pdf->Cell($x[3],$y,strtoupper(utf8_decode($row->dat_codigo)),1,0,'C');
            $pdf->Cell($x[4],$y,strtoupper(utf8_decode($row->dat_dni)),1,0,'C');
            $pdf->Cell($x[1],$y,strtoupper(utf8_decode($row->dat_nombres)),1,0,'C');
            $pdf->Cell($x[2],$y,strtoupper(utf8_decode($row->dat_apellidos)),1,0,'C');
            $pdf->Cell($x[5],$y,strtoupper(utf8_decode($row->dat_celular)),1,0,'C');
            $pdf->Cell($x[6],$y,utf8_decode($row->dat_correo_personal),1,0,'C');
            if($tipopersona == 1){
                $pdf->Cell($x[7],$y,strtoupper(utf8_decode(strtr($row->dat_unidad, $quitarTildes))),1,0,'C');
            }
            if($tipopersona == 2){
                $pdf->Cell($x[10],$y,strtoupper(utf8_decode(strtr($row->dat_departamento, $quitarTildes))),1,0,'C');
            }
            if($tipopersona == 3){
                $pdf->Cell($x[11],$y,strtoupper(utf8_decode(strtr($row->dat_facultad, $quitarTildes))),1,0,'C');
                $pdf->Cell($x[12],$y,strtoupper(utf8_decode($row->dat_escuela)),1,0,'C');
                $pdf->Cell($x[13],$y,strtoupper(utf8_decode($row->dat_sede)),1,0,'C');
            }
            $pdf->SetFillColor(0, 102, 51); // Verde oscuro
            $pdf->Cell($x[8],$y,utf8_decode($row->dat_email),1,0,'C',true);
            $pdf->Cell($x[9],$y,utf8_decode($row->dat_clave),1,1,'C',true);
        }
        $pdf->Cell(0,$y, 'USUARIO: '.utf8_decode(strtoupper(session('nombres').' '.session('apellidos'))),0,1,'R');
        $pdf->SetTitle($titulo);
        $pdf->Output($nombreArchivo, 'I');
        exit;
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
                $style = array(
                    'font' => array('bold' => true),  // Hacer la letra en negrita
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,  // Tipo de relleno sólido
                        'startcolor' => array('rgb' => '008000'),  // Color de fondo (en este caso, verde)
                    ),
                    'borders' => [
                        'outline' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN, 
                            'color' => array('rgb' => '000000'), 
                        ],
                    ],
                );

                $style_azul = [
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,  // Tipo de relleno sólido
                        'startcolor' => array('rgb' => '0883F8'),  // Color de fondo (en este caso, Azul)
                    ),
                    'font' => [
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                ];

                $style_borde = [
                    'borders' => [
                        'outline' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN, 
                            'color' => array('rgb' => '000000'), 
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    ],
                ];
                $objDrawing = new PHPExcel_Worksheet_Drawing();

                $objDrawing->setName('Logo');
                $objDrawing->setDescription('Logo');
                $objDrawing->setPath('public/images/FOTO_EMPRESA/'.logo());
                $objDrawing->setHeight(80);
                $objDrawing->setCoordinates('A1');
                $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

                $hoja->mergeCells('C2:H2');
                $hoja->getStyle("C2:H2")->applyFromArray($style_borde);
                $hoja->setCellValue("C2",utf8_decode(razonsocial()));
                $hoja->mergeCells('C4:H4');
                $hoja->getStyle("C4:H4")->applyFromArray($style_borde);

                $hoja->setCellValue("A7",utf8_encode('ITEM'));
                $hoja->getStyle("A7")->applyFromArray($style_borde);
                $hoja->setCellValue("B7",utf8_decode('CODIGO'));
                $hoja->getStyle("B7")->applyFromArray($style_borde);
                $hoja->setCellValue("C7",utf8_decode('DNI'));
                $hoja->getStyle("C7")->applyFromArray($style_borde);
                $hoja->setCellValue("D7",utf8_decode('NOMBRES'));
                $hoja->getStyle("D7")->applyFromArray($style_borde);
                $hoja->setCellValue("E7",utf8_decode('APELLIDOS'));
                $hoja->getStyle("E7")->applyFromArray($style_borde);
                $hoja->setCellValue("F7",utf8_decode('CELULAR'));
                $hoja->getStyle("F7")->applyFromArray($style_borde);
                $hoja->setCellValue("G7",utf8_decode('CORREO PERSONAL'));
                $hoja->getStyle("G7")->applyFromArray($style_borde);
                $hoja->getStyle("C4")->applyFromArray($style_azul);
                if($tipopersona == 1){
                    $nombreArchivo = 'Lista_CorreoInst_Tipo_Administrativo_'.date('dmY').'_'.date('Hi');
                    $hoja->setCellValue("C4",'LISTA DE CORREOS INSTITUCIONALES DE NUEVOS ADMINISTRATIVOS');
                    $hoja->getStyle("I7:J7")->applyFromArray($style);
                    $hoja->getStyle("I7:J7")->applyFromArray($style_borde);
                    $hoja->setCellValue("I2",'SISTEMA');
                    $hoja->getStyle("I2")->applyFromArray($style_borde);
                    $hoja->setCellValue("I3",'FECHA/HORA');
                    $hoja->getStyle("I3")->applyFromArray($style_borde);
                    $hoja->setCellValue("J2", 'GENERADOR DE CUENTAS');
                    $hoja->getStyle("J2")->applyFromArray($style_borde);
                    $hoja->setCellValue("J3",date('d-m-Y H:i '));
                    $hoja->getStyle("J3")->applyFromArray($style_borde);
                    $hoja->setCellValue("H7",utf8_decode('UNIDAD/OFICINA'));
                    $hoja->getStyle("H7")->applyFromArray($style_borde);
                    $hoja->setCellValue("I7",utf8_decode('CORREO INSTITUCIONAL'));
                    $hoja->getStyle("I7")->applyFromArray($style_borde);
                    $hoja->setCellValue("J7",utf8_decode('CONTRASENIA'));
                    $hoja->getStyle("J7")->applyFromArray($style_borde);
                }
                if($tipopersona == 2){
                    $nombreArchivo = 'Lista_CorreoInst_Tipo_Docente_'.date('dmY').'_'.date('Hi');
                    $hoja->setCellValue("C4",'LISTA DE CORREOS INSTITUCIONALES DE NUEVOS DOCENTES');
                    $hoja->getStyle("I7:J7")->applyFromArray($style);
                    $hoja->getStyle("I7:J7")->applyFromArray($style_borde);
                    $hoja->setCellValue("I2",'SISTEMA');
                    $hoja->getStyle("I2")->applyFromArray($style_borde);
                    $hoja->setCellValue("I3",'FECHA/HORA');
                    $hoja->getStyle("I3")->applyFromArray($style_borde);
                    $hoja->setCellValue("J2", 'GENERADOR DE CUENTAS');
                    $hoja->getStyle("J2")->applyFromArray($style_borde);
                    $hoja->setCellValue("J3",date('d-m-Y H:i '));
                    $hoja->getStyle("J3")->applyFromArray($style_borde);
                    $hoja->setCellValue("H7",utf8_decode('DEPARTAMENTO'));
                    $hoja->getStyle("H7")->applyFromArray($style_borde);
                    $hoja->setCellValue("I7",utf8_decode('CORREO INSTITUCIONAL'));
                    $hoja->getStyle("I7")->applyFromArray($style_borde);
                    $hoja->setCellValue("J7",utf8_decode('CONTRASENIA'));
                    $hoja->getStyle("J7")->applyFromArray($style_borde);
                }
                if($tipopersona == 3){
                    $nombreArchivo = 'Lista_CorreoInst_Tipo_Estudiante_'.date('dmY').'_'.date('Hi');
                    $hoja->setCellValue("C4",'LISTA DE CORREOS INSTITUCIONALES DE NUEVOS ESTUDIANTES');
                    $hoja->getStyle("K7:L7")->applyFromArray($style);
                    $hoja->getStyle("K7:L7")->applyFromArray($style_borde);
                    $hoja->setCellValue("K2",'SISTEMA');
                    $hoja->getStyle("K2")->applyFromArray($style_borde);
                    $hoja->setCellValue("K3",'FECHA/HORA');
                    $hoja->getStyle("K3")->applyFromArray($style_borde);
                    $hoja->setCellValue("L2", 'GENERADOR DE CUENTAS');
                    $hoja->getStyle("L2")->applyFromArray($style_borde);
                    $hoja->setCellValue("L3",date('d-m-Y H:i '));
                    $hoja->getStyle("L3")->applyFromArray($style_borde);
                    $hoja->setCellValue("H7",utf8_decode('FACULTAD'));
                    $hoja->getStyle("H7")->applyFromArray($style_borde);
                    $hoja->setCellValue("I7",utf8_decode('ESCUELA'));
                    $hoja->getStyle("I7")->applyFromArray($style_borde);
                    $hoja->setCellValue("J7",utf8_decode('SEDE'));
                    $hoja->getStyle("J7")->applyFromArray($style_borde);
                    $hoja->setCellValue("K7",utf8_decode('CORREO INSTITUCIONAL'));
                    $hoja->getStyle("K7")->applyFromArray($style_borde);
                    $hoja->setCellValue("L7",utf8_decode('CONTRASENIA'));
                    $hoja->getStyle("L7")->applyFromArray($style_borde);
                }
                $items = $object->validarArchivo($arc_id);
                $c = 0;
                $fila = 7;
                foreach($items as $row){
                    $c++;
                    $fila++;
                    $hoja->setCellValue("A$fila",$c);
                    $hoja->getStyle("A$fila")->applyFromArray($style_borde);
                    $hoja->setCellValue("B$fila",utf8_decode($row->dat_codigo));
                    $hoja->getStyle("B$fila")->applyFromArray($style_borde);
                    $hoja->setCellValue("C$fila",utf8_decode($row->dat_dni));
                    $hoja->getStyle("C$fila")->applyFromArray($style_borde);
                    $hoja->setCellValue("D$fila",utf8_decode($row->dat_nombres));
                    $hoja->getStyle("D$fila")->applyFromArray($style_borde);
                    $hoja->setCellValue("E$fila",utf8_decode($row->dat_apellidos));
                    $hoja->getStyle("E$fila")->applyFromArray($style_borde);
                    $hoja->setCellValue("F$fila",utf8_decode($row->dat_celular));
                    $hoja->getStyle("F$fila")->applyFromArray($style_borde);
                    $hoja->setCellValue("G$fila",utf8_decode($row->dat_correo_personal));
                    $hoja->getStyle("G$fila")->applyFromArray($style_borde);
                    if($tipopersona == 1){
                        $hoja->getStyle("I$fila:J$fila")->applyFromArray($style);
                        $hoja->setCellValue("H$fila",utf8_decode(strtr($row->dat_unidad, $quitarTildes)));
                        $hoja->getStyle("H$fila")->applyFromArray($style_borde);
                        $hoja->setCellValue("I$fila",utf8_decode($row->dat_email));
                        $hoja->getStyle("I$fila")->applyFromArray($style_borde);
                        $hoja->setCellValue("J$fila",utf8_decode($row->dat_clave));
                        $hoja->getStyle("J$fila")->applyFromArray($style_borde);
                    }
                    if($tipopersona == 2){
                        $hoja->getStyle("I$fila:J$fila")->applyFromArray($style);
                        $hoja->setCellValue("H$fila",utf8_decode(strtr($row->dat_departamento, $quitarTildes)));
                        $hoja->getStyle("H$fila")->applyFromArray($style_borde);
                        $hoja->setCellValue("I$fila",utf8_decode($row->dat_email));
                        $hoja->getStyle("I$fila")->applyFromArray($style_borde);
                        $hoja->setCellValue("J$fila",utf8_decode($row->dat_clave));
                        $hoja->getStyle("J$fila")->applyFromArray($style_borde);
                    }
                    if($tipopersona == 3){
                        $hoja->getStyle("K$fila:L$fila")->applyFromArray($style);
                        $hoja->setCellValue("H$fila",utf8_decode(strtr($row->dat_facultad, $quitarTildes)));
                        $hoja->getStyle("H$fila")->applyFromArray($style_borde);
                        $hoja->setCellValue("I$fila",utf8_decode($row->dat_escuela));
                        $hoja->getStyle("I$fila")->applyFromArray($style_borde);
                        $hoja->setCellValue("J$fila",utf8_decode($row->dat_sede));
                        $hoja->getStyle("J$fila")->applyFromArray($style_borde);
                        $hoja->setCellValue("K$fila",utf8_decode($row->dat_email));
                        $hoja->getStyle("K$fila")->applyFromArray($style_borde);
                        $hoja->setCellValue("L$fila",utf8_decode($row->dat_clave));
                        $hoja->getStyle("L$fila")->applyFromArray($style_borde);
                    }
                }
                $fila = $fila + 2;
                switch($tipopersona){
                    case 1:
                        $hoja->setCellValue("I$fila",'USUARIO');
                        $hoja->getStyle("I$fila")->applyFromArray($style_borde);
                        $hoja->setCellValue("J$fila",utf8_decode(strtoupper(session('nombres').' '.session('apellidos'))));
                        $hoja->getStyle("J$fila")->applyFromArray($style_borde);
                        break;
                    case 2:
                        $hoja->setCellValue("I$fila",'USUARIO');
                        $hoja->getStyle("I$fila")->applyFromArray($style_borde);
                        $hoja->setCellValue("J$fila",utf8_decode(strtoupper(session('nombres').' '.session('apellidos'))));
                        $hoja->getStyle("J$fila")->applyFromArray($style_borde);
                        break;
                    case 3:
                        $hoja->setCellValue("K$fila",'USUARIO');
                        $hoja->getStyle("K$fila")->applyFromArray($style_borde);
                        $hoja->setCellValue("L$fila",utf8_decode(strtoupper(session('nombres').' '.session('apellidos'))));
                        $hoja->getStyle("L$fila")->applyFromArray($style_borde);
                        break;
                }
                $hoja->getColumnDimension('A')->setAutoSize(true);
                $hoja->getColumnDimension('B')->setAutoSize(true);
                $hoja->getColumnDimension('C')->setAutoSize(true);
                $hoja->getColumnDimension('D')->setAutoSize(true);
                $hoja->getColumnDimension('E')->setAutoSize(true);
                $hoja->getColumnDimension('F')->setAutoSize(true);
                $hoja->getColumnDimension('G')->setAutoSize(true);
                $hoja->getColumnDimension('H')->setAutoSize(true);
                $hoja->getColumnDimension('I')->setAutoSize(true);
                $hoja->getColumnDimension('J')->setAutoSize(true);
                $hoja->getColumnDimension('K')->setAutoSize(true);
                $hoja->getColumnDimension('L')->setAutoSize(true);
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

    public function descargarrepoexcel($arc_id)
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
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->setActiveSheetIndex(0);
                $hoja = $objPHPExcel->getActiveSheet();
                $hoja->setCellValue("A1",utf8_encode('First Name'));
                $hoja->setCellValue("B1",utf8_decode('Last Name'));
                $hoja->setCellValue("C1",utf8_decode('Email Address'));
                $hoja->setCellValue("D1",utf8_decode('Password'));
                $hoja->setCellValue("E1",utf8_decode('Org Unit Path'));
                $hoja->setCellValue("F1",utf8_decode('Recovery Email'));
                $items = $object->validarArchivo($arc_id);
                $fila = 1;
                foreach($items as $row){
                    $fila++;
                    $hoja->setCellValue("A$fila",utf8_decode($row->dat_nombres));
                    $hoja->setCellValue("B$fila",utf8_decode($row->dat_apellidos));
                    $hoja->setCellValue("C$fila",utf8_decode($row->dat_email));
                    $hoja->setCellValue("D$fila",utf8_decode($row->dat_clave));
                    $hoja->setCellValue("E$fila",'/');
                    $hoja->setCellValue("F$fila",utf8_decode($row->dat_correo_personal));
                }
                if($tipopersona == 1){
                    $nombreArchivo = 'Nuevos_CorreoInst_Tipo_Administrativo_'.date('dmY').'_'.date('Hi');
                }
                if($tipopersona == 2){
                    $nombreArchivo = 'Nuevos_CorreoInst_Tipo_Docente_'.date('dmY').'_'.date('Hi');
                }
                if($tipopersona == 3){
                    $nombreArchivo = 'Nuevos_CorreoInst_Tipo_Estudiante_'.date('dmY').'_'.date('Hi');
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

    public function descargarrepocsv($arc_id)
    {
        if (session('authenticated') && accede()) {
            if (bloqueado()) {
                $quitarTildes = array(
                    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C',
                    'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
                    'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a',
                    'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i',
                    'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u',
                    'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y'
                );
                $object   = new datosModelo();
                $objectA  = new archivosModelo();
                $itemA    = $objectA->archivo($arc_id);
                $items    = $object->validarArchivo($arc_id);
                $tipopersona = $itemA->arc_tipo_persona;
                $nombreArchivo = '';
                if($tipopersona == 1){
                    $nombreArchivo = 'Nuevos_CorreoInst_Tipo_Administrativo_'.date('dmY').'_'.date('Hi');
                }
                if($tipopersona == 2){
                    $nombreArchivo = 'Nuevos_CorreoInst_Tipo_Docente_'.date('dmY').'_'.date('Hi');
                }
                if($tipopersona == 3){
                    $nombreArchivo = 'Nuevos_CorreoInst_Tipo_Estudiante_'.date('dmY').'_'.date('Hi');
                }
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename="'.$nombreArchivo.'.csv"');
                header('Pragma: no-cache');
                header('Expires: 0');
                $output = fopen('php://output', 'w');
                fputs($output, "\xEF\xBB\xBF");
                fputcsv($output, array(
                    'First Name',
                    'Last Name',
                    'Email Address',
                    'Password',
                    'Org Unit Path',
                    'Recovery Email'
                ));
                foreach ($items as $row) {
                    fputcsv($output, array(
                        utf8_decode($row->dat_nombres),
                        utf8_decode($row->dat_apellidos),
                        utf8_decode($row->dat_email),
                        utf8_decode($row->dat_clave),
                        '/',
                        utf8_decode($row->dat_correo_personal)
                    ));
                }
                fclose($output);
                exit;
            }
        }
    }

    public function pdfdescargar($arc_id)
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                require_once APPPATH . 'Libraries/Excel/PHPExcel.php';
                require_once APPPATH . 'Libraries/PDFS.php';
                $object = new archivosModelo();
                $item = $object->archivo($arc_id);
                $archivo = $item->arc_ruta;
                $tipopersona = $item->arc_tipo_persona;
                $nombreArchivo  =  $item->arc_nombre;
                $nombreArchivo = explode('.',$nombreArchivo);
                $titulo = $nombreArchivo[0];
                $nombreArchivo = $nombreArchivo[0].'.pdf';
                $objPHPExcel = PHPExcel_IOFactory::identify($archivo);
                $objPHPExcel = PHPExcel_IOFactory::createReader($objPHPExcel);
                $objPHPExcel = $objPHPExcel->load($archivo);
                $hoja = $objPHPExcel->getSheet(0);
                $ultimaFila = $hoja->getHighestRow();
                $pdf = new PDFS();
                $pdf->AddPage('L');
                $pdf->AliasNbPages();
                   // Título principal
                $pdf->SetXY(50, 30);
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->SetFillColor(0, 51, 102); // Azul oscuro
                $pdf->SetTextColor(255, 255, 255); // Texto blanco
                $pdf->Cell(220, 10, utf8_decode('LISTA DE '.$item->tip_nombre.'S PARA LA CREACIÓN DE CORREOS INSTITUCIONALES'), 1, 1, 'C', true);
                $pdf->Ln(5);
                $pdf->SetFont('Arial','B',8);
                $x = [0=>10,1=>25,2=>45,3=>45,4=>20,5=>30,6=>55,7=>45];
                if($tipopersona == 3){
                    $pdf->SetFont('Arial','B',6);
                    $x = [0=>9,1=>20,2=>35,3=>35,4=>15,5=>25,6=>40,7=>33];
                }
                $y = 5;
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell($x[0],$y, utf8_encode('ITEM'),1,0,'C');
                $pdf->Cell($x[1],$y, 'CODIGO',1,0,'C');
                $pdf->Cell($x[2],$y, 'NOMBRES',1,0,'C');
                $pdf->Cell($x[3],$y, 'APELLIDOS',1,0,'C');
                $pdf->Cell($x[4],$y, 'DNI',1,0,'C');
                $pdf->Cell($x[5],$y, 'CELULAR',1,0,'C');
                $pdf->Cell($x[6],$y, 'CORREO PERSONAL',1,0,'C');
                if($tipopersona == 1){
                    $pdf->Cell($x[7],$y, 'UNIDAD/OFICINA',1,1,'C');
                    $pdf->SetFont('Arial','',8);
                }
                if($tipopersona == 2){
                    $pdf->Cell($x[7],$y, 'DEPARTAMENTO',1,1,'C');
                }
                if($tipopersona == 3){
                    $pdf->Cell($x[7],$y, 'FACULTAD',1,0,'C');
                    $pdf->Cell($x[7],$y, 'ESCUELA',1,0,'C');
                    $pdf->Cell($x[7],$y, 'SEDE',1,1,'C');
                    $pdf->SetFont('Arial','',6);
                }
                $c = 0;
                for($i = 2; $i <= $ultimaFila; $i++) {
                    $c++;
                $nombres = $hoja->getCell("B$i")->getValue();
                $apellidos = $hoja->getCell("C$i")->getValue();
                $codigo = $hoja->getCell("A$i")->getValue();
                $dni     = $hoja->getCell("D$i")->getValue();
                $celular = $hoja->getCell("E$i")->getValue();
                $correop = $hoja->getCell("F$i")->getValue();
                $apellido = str_replace(
                    ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                    ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                    $apellidos);
                $nombres = str_replace(
                    ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                    ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                    $nombres);
                if($tipopersona == 1){
                    $unidad = $hoja->getCell("G$i")->getValue();
                    $unidad = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                        $unidad);
                    $unidad = strtoupper($unidad);
                }
                if($tipopersona == 2){
                    $departamento = $hoja->getCell("G$i")->getValue();
                    $departamento = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                        $departamento);
                    $departamento = strtoupper($departamento);
                }
                if($tipopersona == 3){
                    $facultad = $hoja->getCell("G$i")->getValue();
                    $facultad = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                        $facultad);
                    $escuela = $hoja->getCell("H$i")->getValue();
                    $escuela = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                        $escuela);
                    $sede = $hoja->getCell("I$i")->getValue();
                    $sede = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
                        $sede);
                    $facultad = strtoupper($facultad);
                    $escuela = strtoupper($escuela);
                    $sede = strtoupper($sede);
                }
                $nombres = utf8_decode(strtoupper($nombres));
                $apellido = utf8_decode(strtoupper($apellido));
                    $pdf->Cell($x[0],$y, $c,1,0,'C');
                    $pdf->Cell($x[1],$y, $codigo,1,0,'C');
                    $pdf->Cell($x[2],$y, $nombres,1,0,'C');
                    $pdf->Cell($x[3],$y, $apellido,1,0,'C');
                    $pdf->Cell($x[4],$y, $dni,1,0,'C');
                    $pdf->Cell($x[5],$y, $celular,1,0,'C');
                    $pdf->Cell($x[6],$y, $correop,1,0,'C');
                    if($tipopersona == 1){
                        $pdf->Cell($x[7],$y, $unidad,1,1,'C');
                    }
                    if($tipopersona == 2){
                        $pdf->Cell($x[7],$y, $departamento,1,1,'C');
                    }
                    if($tipopersona == 3){
                            $pdf->Cell($x[7],$y, $facultad,1,0,'C');
                            $pdf->Cell($x[7],$y, $escuela,1,0,'C');
                            $pdf->Cell($x[7],$y, $sede,1,1,'C');
                    }
                }
                $pdf->Ln();
                $pdf->Cell(0,$y, 'USUARIO: '.utf8_decode(strtoupper(session('nombres').' '.session('apellidos'))),0,1,'R');
                $pdf->SetTitle($titulo);
                $pdf->Output($nombreArchivo, 'I');
                exit;
            }else{
                return view('denegado');
            }
        }else{
            return redirect()->to(base_url("/"));
        }
    }

    public function meditarcacafonia(){
        $object = new datosModelo();
        $id = $_POST['id'];
        $correo = strtolower($_POST['correo']);
        $val = $object->validarCorreo($correo);
        if(empty($val)){
            $data = [
                'dat_email' => $correo
            ];
            if($object->updateCacafonias($id, $data)){
                echo 'ok';
            }else{
                echo 'error';
            }
        }else{
            echo 'existe';
        }
    }

}