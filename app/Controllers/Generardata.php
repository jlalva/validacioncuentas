<?php

namespace App\Controllers;

use App\Models\archivosModelo;
use App\Models\datosModelo;
use App\Models\dominioModelo;
use App\Models\peyorativosModelo;
use App\Models\tipopersonaModelo;
use CodeIgniter\Controller;
use PDFE;
use PDFA;
use PDFD;
use PDFS;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Border;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Fill;
use PHPExcel_Worksheet_Drawing;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Trim;

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
                $arc_id = 0;
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
                    $duplicados = $objectDa->validarDuplicados($items[$i]->arc_id);
                    $items[$i]->duplicados = count($duplicados);
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
                                <th class="column-title" style="text-align: center;">NOMBRES</th>
                                <th class="column-title" style="text-align: center;">APELLIDOS</th>
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
                                <td style='text-align: center;'>".($i-1)."</td>
                                <td style='text-align: center;'>".$codigo."</td>
                                <td style='text-align: center;'>".$nombres."</td>
                                <td style='text-align: center;'>".$apellido."</td>
                                <td style='text-align: center;'>".$dni."</td>
                                <td style='text-align: center;'>".$celular."</td>
                                <td style='text-align: center;'>".$correop."</td>";
                                if($tipopersona == 1){
                                    $html .="<td style='text-align: center;'>$unidad</td>";
                                }
                                if($tipopersona == 2){
                                    $html .="<td style='text-align: center;'>$departamento</td>";
                                }
                                if($tipopersona == 3){
                                    $html .="<td style='text-align: center;'>$facultad</td>
                                        <td style='text-align: center;'>$escuela</td>
                                        <td style='text-align: center;'>$sede</td>";
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
                    <tbody style='text-align: center'>";
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

    public function duplicados($arc_id)
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                $object = new datosModelo();
                $cduplicados = $object->validarDuplicados($arc_id);
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
                $c = 0;
                foreach($cduplicados as $row){
                    $items = $object->cuentasDuplicados($arc_id, $row->dat_email);
                    foreach($items as $rowC){
                            $acorreo = explode("@",$rowC->dat_email);
                            $correo = $acorreo[0];
                            $dominio = '@'.$acorreo[1];
                            $c++;
                            $html .="<tr>
                                <td style='text-align: center'>".$c."</td>
                                <td style='text-align: center'>".$rowC->dat_codigo."</td>
                                <td style='text-align: center'>".$rowC->dat_dni."</td>
                                <td style='text-align: center'>".strtoupper($rowC->dat_nombres)."</td>
                                <td style='text-align: center'>".strtoupper($rowC->dat_apellidos)."</td>
                                <td style='text-align: center'>".$rowC->dat_celular."</td>
                                <td style='text-align: center'>".$rowC->dat_correo_personal."</td>";
                            $html .="<td style='background:red'>".$rowC->dat_email."</td>
                                <td style='text-align: center'>".$rowC->dat_clave."</td>";
                            $html .='<td style="text-align: center"><button class="btn btn-success btn-sm" title="EDITAR" data-bs-toggle="modal" data-bs-target="#modalEditar" onclick="datoseditarduplicado('.$rowC->dat_id.',\''.$correo.'\',\''.$dominio.'\')"><i class="bx bx-edit"></i></button></td>
                            </tr>';
                    }
                }
                $datos = ['titulo' => 'Cuentas Duplicadas', 'tabla'=>$html, 'idarchivo' => $arc_id];
                return view('datos/generardata/duplicados', $datos);
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
                                <th class="column-title" style="text-align: center;">NOMBRES</th>
                                <th class="column-title" style="text-align: center;">APELLIDOS</th>
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
                        <tbody style="text-align: center;">';
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

            $html = '<table class="table table-striped table-bordered" id="procesados">
                        <thead>
                            <tr class="headings">
                                <th class="column-title" style="text-align: center;">ITEM</th>
                                <th class="column-title" style="text-align: center;">NOMBRES</th>
                                <th class="column-title" style="text-align: center;">APELLIDOS</th>
                                <th class="column-title" style="text-align: center;">CORREO CREADO/EXISTENTE</th>
                                <th class="column-title" style="text-align: center;">USUARIO</th>
                                <th class="column-title" style="text-align: center;">CLAVE</th>
                                <th class="column-title" style="text-align: center;">SITUACION</th>
                            </tr>
                        </thead>
                        <tbody>';
            $c = 0;

            $nombresBD = array_flip(array_column($object->listarNombres($emp_id,$dominio), 'dat_nombres_completos'));
            $correosBD = array_flip(array_column($object->listarCorreos($emp_id), 'dat_email'));
            $nocompuestosBD = array_flip(array_column($object->listarCompuestos($emp_id), 'com_nombre'));
            $nocompuestos = array_map('strtolower', array_keys($nocompuestosBD));
            $fl = 0;
            for ($i = 2; $i <= $ultimaFila; $i++) {
                $c++;
                $nombres = Trim($hoja->getCell("B$i")->getValue());
                $apellidos = Trim($hoja->getCell("C$i")->getValue());
                $codigo = $hoja->getCell("A$i")->getValue();
                $sede = $hoja->getCell("I$i")->getValue();

                $apellido_correo = str_replace(
                    ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú','ñ', 'Ñ','ü', 'Ü','à', 'è', 'ì', 'ò', 'ù', 'À', 'È', 'Ì', 'Ò', 'Ù','ä', 'ë', 'ï', 'ö', 'Ä', 'Ë', 'Ï', 'Ö'],
                    ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U','n', 'N','u', 'U','a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U','a', 'e', 'i', 'o', 'A', 'E', 'I', 'O'],
                    $apellidos);
                $nombres_correo = str_replace(
                    ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú','ñ', 'Ñ','ü', 'Ü','à', 'è', 'ì', 'ò', 'ù', 'À', 'È', 'Ì', 'Ò', 'Ù','ä', 'ë', 'ï', 'ö', 'Ä', 'Ë', 'Ï', 'Ö'],
                    ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U','n', 'N','u', 'U','a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U','a', 'e', 'i', 'o', 'A', 'E', 'I', 'O'],
                    $nombres);

                $apellido_limpio = str_replace(
                    ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú','ñ', 'Ñ','ü', 'Ü','à', 'è', 'ì', 'ò', 'ù', 'À', 'È', 'Ì', 'Ò', 'Ù','ä', 'ë', 'ï', 'ö', 'Ä', 'Ë', 'Ï', 'Ö'],
                    ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U','Ñ', 'Ñ','U', 'U','A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U','A', 'E', 'I', 'O', 'A', 'E', 'I', 'O'],
                    $apellidos);

                $nombres_limpio = str_replace(
                    ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú','ñ', 'Ñ','ü', 'Ü','à', 'è', 'ì', 'ò', 'ù', 'À', 'È', 'Ì', 'Ò', 'Ù','ä', 'ë', 'ï', 'ö', 'Ä', 'Ë', 'Ï', 'Ö'],
                    ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U','Ñ', 'Ñ','U', 'U','A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U','A', 'E', 'I', 'O', 'A', 'E', 'I', 'O'],
                    $nombres);

                $nombrecompleto = strtoupper(trim($nombres_limpio) . ' ' . trim($apellido_limpio));
                $observacion = isset($nombresBD[$nombrecompleto]) ? 'Usuario existente' : '';
                $correo = '';
                $usuario = '';
                $clave = strtoupper(substr($nombres_correo, 0, 1)) . strtolower(substr($apellido_correo, 0, 1)) . $codigo . '*@';
                if (!$observacion) {
                    $fl = 1;
                    switch($generarcon){
                        case 1:$correo = generarCorreo(trim($nombres_correo), trim($apellido_correo), $nocompuestos) . $dominio;
                            if (isset($correosBD[$correo])) {
                                $correo = generarCorreo2(trim($nombres_correo), trim($apellido_correo),$nocompuestos) . $dominio;
                            }
                            break;
                        case 2:$correo = generarCorreoCodigo(trim($nombres_correo), trim($apellido_correo), $codigo) . $dominio;break;
                        case 3:$correo = generarCorreoSedeCodigo($sede, $codigo) . $dominio;break;
                    }
                }else{
                    $consultacorreo = $object->traerCorreos($emp_id, $nombrecompleto);
                    $correo = $consultacorreo->dat_email;
                    $usuario =$nombrecompleto;
                }
                $nombres = strtoupper($nombres_limpio);
                $apellidos = strtoupper($apellido_limpio);
                $html .= "<tr>
                            <td style='text-align: center;'>$c</td>
                            <td style='text-align: center;'>$nombres</td>
                            <td style='text-align: center;'>$apellidos</td>
                            <td style='text-align: center;'>$correo</td>
                            <td style='text-align: center;'>$usuario</td>
                            <td style='text-align: center;'>$clave</td>
                            <td style='text-align: center;'>$observacion</td>
                          </tr>";
            }
            $html .= "</tbody>
                    </table>
                    <input type='hidden' id='varegistrar' name='varegistrar' value='$fl'>";
            echo $html;
        }
    }

    public function guardararchivo(){
        $object = new datosModelo();
        $objectArc = new archivosModelo();
        $db = \Config\Database::connect();
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
                $nocompuestosBD = array_flip(array_column($object->listarCompuestos($emp_id), 'com_nombre'));
                $nocompuestos = array_map('strtolower', array_keys($nocompuestosBD));
                for($i = 2; $i <= $ultimaFila; $i++) {
                    $nombres = $db->escapeString(strtoupper(trim($hoja->getCell("B$i")->getValue())));
                    $apellidos = $db->escapeString(strtoupper(trim($hoja->getCell("C$i")->getValue())));
                    $codigo = $hoja->getCell("A$i")->getValue();
                    $dni = $hoja->getCell("D$i")->getValue();
                    $celular = $hoja->getCell("E$i")->getValue();
                    $correopersonal = $hoja->getCell("F$i")->getValue();
                    if($tipopersona == 1){
                        $unidad = $db->escapeString($hoja->getCell("G$i")->getValue());
                        $unidad = str_replace(
                            ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                            ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'],
                            $unidad);
                        $unidad = strtoupper($unidad);
                    }
                    if($tipopersona == 2){
                        $departamento = $db->escapeString($hoja->getCell("G$i")->getValue());
                        $departamento = str_replace(
                            ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                            ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'],
                            $departamento);
                        $departamento = strtoupper($departamento);
                    }
                    if($tipopersona == 3){
                        $facultad = $db->escapeString($hoja->getCell("G$i")->getValue());
                        $facultad = str_replace(
                            ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                            ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'],
                            $facultad);
                        $escuela = $db->escapeString($hoja->getCell("H$i")->getValue());
                        $escuela = str_replace(
                            ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                            ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'],
                            $escuela);
                        $sede = $db->escapeString($hoja->getCell("I$i")->getValue());
                        $sede = str_replace(
                            ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                            ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'],
                            $sede);
                        $facultad = strtoupper($facultad);
                        $escuela = strtoupper($escuela);
                        $sede = strtoupper($sede);
                    }
                    $correo = '';
                    $apellido_correo = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú','ñ', 'Ñ','ü', 'Ü','à', 'è', 'ì', 'ò', 'ù', 'À', 'È', 'Ì', 'Ò', 'Ù','ä', 'ë', 'ï', 'ö', 'Ä', 'Ë', 'Ï', 'Ö'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U','n', 'N','u', 'U','a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U','a', 'e', 'i', 'o', 'A', 'E', 'I', 'O'],
                        $apellidos);
                    $nombres_correo = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú','ñ', 'Ñ','ü', 'Ü','à', 'è', 'ì', 'ò', 'ù', 'À', 'È', 'Ì', 'Ò', 'Ù','ä', 'ë', 'ï', 'ö', 'Ä', 'Ë', 'Ï', 'Ö'],
                        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U','n', 'N','u', 'U','a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U','a', 'e', 'i', 'o', 'A', 'E', 'I', 'O'],
                        $nombres);

                    $apellido_limpio = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú','ñ', 'Ñ','ü', 'Ü','à', 'è', 'ì', 'ò', 'ù', 'À', 'È', 'Ì', 'Ò', 'Ù','ä', 'ë', 'ï', 'ö', 'Ä', 'Ë', 'Ï', 'Ö'],
                        ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U','Ñ', 'Ñ','U', 'U','A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U','A', 'E', 'I', 'O', 'A', 'E', 'I', 'O'],
                        $apellidos);

                    $nombres_limpio = str_replace(
                        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú','ñ', 'Ñ','ü', 'Ü','à', 'è', 'ì', 'ò', 'ù', 'À', 'È', 'Ì', 'Ò', 'Ù','ä', 'ë', 'ï', 'ö', 'Ä', 'Ë', 'Ï', 'Ö'],
                        ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U','Ñ', 'Ñ','U', 'U','A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U','A', 'E', 'I', 'O', 'A', 'E', 'I', 'O'],
                        $nombres);
                    $completo = strtoupper(trim($nombres_limpio) . ' ' . trim($apellido_limpio));
                    $clave = strtoupper(substr($nombres_correo,0,1)).strtolower(substr($apellido_correo,0,1)).$codigo.'*@';
                    $val = $object->validarNombres($db->escapeString(trim($completo)),$emp_id,$dominio);
                    if(!$val){
                        switch($generarcon){
                            case 1:$correo = generarCorreo(trim($nombres_correo),trim($apellido_correo),$nocompuestos).$dominio;
                                    //$val = $object->validarCorreo($correo);
                                    if (isset($correosBD[$correo])) {
                                        $correo = generarCorreo2(trim($nombres_correo),trim($apellido_correo),$nocompuestos).$dominio;
                                    }
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
                    }else{
                        if($val->dat_origen == 1 && $val->dat_clave == ''){
                            $data = [
                                'dat_clave'=>$clave
                            ];
                            $object->upd($val->dat_id, $data);
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
                    $hoja->setCellValue("A$fila",$row->dat_codigo);
                    $hoja->setCellValue("B$fila",$row->dat_nombres);
                    $hoja->setCellValue("C$fila",$row->dat_apellidos);
                    $hoja->setCellValue("D$fila",$row->dat_nombres_completos);
                    $hoja->setCellValue("E$fila",$row->dat_email);
                    $hoja->setCellValue("F$fila",$row->dat_estado);
                    $hoja->setCellValue("G$fila",$row->dat_ultimo_acceso);
                    $hoja->setCellValue("H$fila",$row->dat_espacio_uso);
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
        require_once APPPATH . 'Libraries/Excel/PHPExcel.php';
        $quitarTildes = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y');
        $reemplazos = ['á'=>'A','é'=>'E','í'=>'I','ó'=>'O','ú'=>'U','Á'=>'A','É'=>'E','Í'=>'I','Ó'=>'O','Ú'=>'U','ñ'=>'Ñ','ü'=>'U','Ü'=>'U','à'=>'A','è'=>'E',
        'ì'=>'I','ò'=>'O','ù'=>'U','À'=>'A','È'=>'E','Ì'=>'I','Ò'=>'O','Ù'=>'U','ä'=>'A','ë'=>'E','ï'=>'I','ö'=>'O','Ä'=>'A','Ë'=>'E','Ï'=>'I','Ö'=>'O','Ö'=>'O',
        'à'=>'A'];
        $object = new datosModelo();
        $objectA = new archivosModelo();
        $itemA = $objectA->archivo($arc_id);
        $tipopersona = $itemA->arc_tipo_persona;
        $rutaArc = $itemA->arc_ruta;
        $emp_id = $itemA->arc_emp_id;
        if($tipopersona == 1){
            require_once APPPATH . 'Libraries/PDFA.php';
            $pdf = new PDFA();
            $nombreArchivo = 'Lista_CorreoInst_Tipo_Administrativo_'.date('dmY').'_'.date('Hi');
        }
        if($tipopersona == 2){
            require_once APPPATH . 'Libraries/PDFD.php';
            $pdf = new PDFD();
            $nombreArchivo = 'Lista_CorreoInst_Tipo_Docente_'.date('dmY').'_'.date('Hi');
        }
        if($tipopersona == 3){
            require_once APPPATH . 'Libraries/PDFE.php';
            $pdf = new PDFE();
            $nombreArchivo = 'Lista_CorreoInst_Tipo_Estudiante_'.date('dmY').'_'.date('Hi');
        }
        $pdf->AddPage('L');
        $pdf->AliasNbPages();

        $pdf->SetFont('Arial','B',7);
        $pdf->SetTextColor(0, 0, 0); // Texto negro
        if($tipopersona == 3){
            $x = [0=>10,1=>30,2=>30,3=>17,4=>14,5=>17,6=>30,7=>50,8=>34,9=>20,10=>50,11=>30,12=>28,13=>15];
        }else{
            $x = [0=>10,1=>45,2=>45,3=>17,4=>14,5=>17,6=>30,7=>45,8=>34,9=>20,10=>45,11=>30,12=>28,13=>15];
        }
        $objPHPExcel = PHPExcel_IOFactory::identify($rutaArc);
        $objPHPExcel = PHPExcel_IOFactory::createReader($objPHPExcel);
        $objPHPExcel = $objPHPExcel->load($rutaArc);
        $hoja = $objPHPExcel->getSheet(0);
        $ultimaFila = $hoja->getHighestRow();
        //$total = $ultimaFila - 1;
        $y = 5;
        $c = 0;
        $pdf->SetFont('Arial','',6);
        for($i = 2; $i <= $ultimaFila; $i++) {
            $codigo = $hoja->getCell("A$i")->getValue();
            $dni = $hoja->getCell("D$i")->getValue();
            $item = $object->validarRegistro($arc_id, $dni, $codigo);
            $c++;
            $pdf->Cell($x[0],$y,$c,1,0,'C');
            if($item){
                $pdf->Cell($x[3],$y,strtoupper(utf8_decode($item->dat_codigo)),1,0,'C');
                $pdf->Cell($x[4],$y,strtoupper(utf8_decode($item->dat_dni)),1,0,'C');
                $pdf->Cell($x[1],$y,strtoupper(utf8_decode($item->dat_nombres)),1,0,'C');
                $pdf->Cell($x[2],$y,strtoupper(utf8_decode($item->dat_apellidos)),1,0,'C');
                $pdf->Cell($x[5],$y,strtoupper(utf8_decode($item->dat_celular)),1,0,'C');
                $pdf->Cell($x[6],$y,utf8_decode($item->dat_correo_personal),1,0,'C');
                if($tipopersona == 1){
                    $pdf->Cell($x[7],$y,strtoupper(utf8_decode(strtr($item->dat_unidad, $quitarTildes))),1,0,'C');
                }
                if($tipopersona == 2){
                    $pdf->Cell($x[10],$y,strtoupper(utf8_decode(strtr($item->dat_departamento, $quitarTildes))),1,0,'C');
                }
                if($tipopersona == 3){
                    $pdf->Cell($x[11],$y,strtoupper(utf8_decode(strtr($item->dat_facultad, $quitarTildes))),1,0,'C');
                    $pdf->Cell($x[12],$y,strtoupper(utf8_decode($item->dat_escuela)),1,0,'C');
                    $pdf->Cell($x[13],$y,strtoupper(utf8_decode($item->dat_sede)),1,0,'C');
                }
                $pdf->SetFillColor(0, 102, 51); // Verde oscuro
                $pdf->Cell($x[8],$y,utf8_decode($item->dat_email),1,0,'C',true);
                $pdf->Cell($x[9],$y,utf8_decode($item->dat_clave),1,1,'C',true);
            }else{
                $nombres = strtoupper($hoja->getCell("B$i")->getValue());
                $apellidos = $hoja->getCell("C$i")->getValue();
                $celular = $hoja->getCell("E$i")->getValue();
                $correopersonal = $hoja->getCell("F$i")->getValue();
                $completo = $nombres.' '.$apellidos;
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
                $apellido_limpio = strtr($apellidos, $reemplazos);
                $nombres_limpio = strtr($nombres, $reemplazos);
                $completo = strtr($completo, $reemplazos);
                $pdf->Cell($x[3],$y,strtoupper(utf8_decode($codigo)),1,0,'C');
                $pdf->Cell($x[4],$y,strtoupper(utf8_decode($dni)),1,0,'C');
                $pdf->Cell($x[1],$y,strtoupper(utf8_decode($nombres_limpio)),1,0,'C');
                $pdf->Cell($x[2],$y,strtoupper(utf8_decode($apellido_limpio)),1,0,'C');
                $pdf->Cell($x[5],$y,strtoupper(utf8_decode($celular)),1,0,'C');
                $pdf->Cell($x[6],$y,utf8_decode($correopersonal),1,0,'C');
                if($tipopersona == 1){
                    $pdf->Cell($x[7],$y,strtoupper(utf8_decode(strtr($unidad, $quitarTildes))),1,0,'C');
                }
                if($tipopersona == 2){
                    $pdf->Cell($x[10],$y,strtoupper(utf8_decode(strtr($departamento, $quitarTildes))),1,0,'C');
                }
                if($tipopersona == 3){
                    $pdf->Cell($x[11],$y,strtoupper(utf8_decode(strtr($facultad, $quitarTildes))),1,0,'C');
                    $pdf->Cell($x[12],$y,strtoupper(utf8_decode($escuela)),1,0,'C');
                    $pdf->Cell($x[13],$y,strtoupper(utf8_decode($sede)),1,0,'C');
                }
                $dom = $object->soloDominio($arc_id);
                $val = $object->validarNombres(trim($completo),$emp_id,$dom->dominio);
                $pdf->SetFillColor(255, 0, 0); // Rojo oscuro
                if($val){
                    $pdf->Cell($x[8],$y,utf8_decode($val->dat_email),1,0,'C',true);
                    $pdf->Cell($x[9],$y,utf8_decode($val->dat_clave),1,1,'C',true);
                }else{
                    $pdf->Cell($x[8],$y,'-',1,0,'C',true);
                    $pdf->Cell($x[9],$y,'-',1,1,'C',true);
                }
            }
        }
        $pdf->Cell(0,$y, 'USUARIO: '.utf8_decode(strtoupper(session('nombres').' '.session('apellidos'))),0,1,'R');
        $pdf->SetTitle($nombreArchivo);
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
                $rutaArc = $itemA->arc_ruta;
                $emp_id = $itemA->arc_emp_id;
                $nombreArchivo = '';
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->setActiveSheetIndex(0);
                $hoja = $objPHPExcel->getActiveSheet();
                $style = array(
                    'font' => array('bold' => true),
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('rgb' => '008000'), 
                    ),
                    'borders' => [
                        'outline' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN, 
                            'color' => array('rgb' => '000000'), 
                        ],
                    ],
                );

                $style_rojo = array(
                    'font' => array('bold' => true),
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('rgb' => 'FF0000'), 
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
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('rgb' => '0883F8'),
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

                $style_borde_negrita = [
                    'font' => array('bold' => true),
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

                $style_borde_titulo = [
                    'font' => array('bold' => true,'size' => 14),
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
                $hoja->setCellValue("C2",utf8_decode(razonsocial()));
                if($tipopersona == 3){
                    $hoja->mergeCells('C2:I2');
                    $hoja->getStyle("C2:I2")->applyFromArray($style_borde_titulo);
                }else{
                    $hoja->mergeCells('C2:H2');
                    $hoja->getStyle("C2:H2")->applyFromArray($style_borde_titulo);
                }

                $hoja->setCellValue("A7",utf8_encode('ITEM'));
                $hoja->getStyle("A7")->applyFromArray($style_borde_negrita);
                $hoja->setCellValue("B7",utf8_decode('CODIGO'));
                $hoja->getStyle("B7")->applyFromArray($style_borde_negrita);
                $hoja->setCellValue("C7",utf8_decode('DNI'));
                $hoja->getStyle("C7")->applyFromArray($style_borde_negrita);
                $hoja->setCellValue("D7",utf8_decode('NOMBRES'));
                $hoja->getStyle("D7")->applyFromArray($style_borde_negrita);
                $hoja->setCellValue("E7",utf8_decode('APELLIDOS'));
                $hoja->getStyle("E7")->applyFromArray($style_borde_negrita);
                $hoja->setCellValue("F7",utf8_decode('CELULAR'));
                $hoja->getStyle("F7")->applyFromArray($style_borde_negrita);
                $hoja->setCellValue("G7",utf8_decode('CORREO PERSONAL'));
                $hoja->getStyle("G7")->applyFromArray($style_borde_negrita);
                $hoja->getStyle("C2")->applyFromArray($style_azul);
                $hoja->getStyle("C4")->applyFromArray($style_azul);
                if($tipopersona == 1){
                    $nombreArchivo = 'Lista_CorreoInst_Tipo_Administrativo_'.date('dmY').'_'.date('Hi');
                    $hoja->setCellValue("C4",'LISTA DE CORREOS INSTITUCIONALES DE NUEVOS ADMINISTRATIVOS');
                    $hoja->getStyle("C4:H4")->applyFromArray($style_borde_titulo);
                    $hoja->mergeCells('C4:H4');
                    $hoja->getStyle("I7:J7")->applyFromArray($style);
                    $hoja->getStyle("I7:J7")->applyFromArray($style_borde);
                    $hoja->setCellValue("I2",'SISTEMA');
                    $hoja->getStyle("I2")->applyFromArray($style_borde_negrita);
                    $hoja->setCellValue("I3",'FECHA/HORA');
                    $hoja->getStyle("I3")->applyFromArray($style_borde_negrita);
                    $hoja->setCellValue("J2", 'GENERADOR DE CUENTAS');
                    $hoja->getStyle("J2")->applyFromArray($style_borde);
                    $hoja->setCellValue("J3",date('d-m-Y H:i '));
                    $hoja->getStyle("J3")->applyFromArray($style_borde);
                    $hoja->setCellValue("H7",utf8_decode('UNIDAD/OFICINA'));
                    $hoja->getStyle("H7")->applyFromArray($style_borde_negrita);
                    $hoja->setCellValue("I7",utf8_decode('CORREO INSTITUCIONAL'));
                    $hoja->getStyle("I7")->applyFromArray($style_borde_negrita);
                    $hoja->setCellValue("J7",'CONTRASEÑA');
                    $hoja->getStyle("J7")->applyFromArray($style_borde_negrita);
                }
                if($tipopersona == 2){
                    $nombreArchivo = 'Lista_CorreoInst_Tipo_Docente_'.date('dmY').'_'.date('Hi');
                    $hoja->setCellValue("C4",'LISTA DE CORREOS INSTITUCIONALES DE NUEVOS DOCENTES');
                    $hoja->getStyle("C4:H4")->applyFromArray($style_borde_titulo);
                    $hoja->mergeCells('C4:H4');
                    $hoja->getStyle("I7:J7")->applyFromArray($style);
                    $hoja->getStyle("I7:J7")->applyFromArray($style_borde);
                    $hoja->setCellValue("I2",'SISTEMA');
                    $hoja->getStyle("I2")->applyFromArray($style_borde_negrita);
                    $hoja->setCellValue("I3",'FECHA/HORA');
                    $hoja->getStyle("I3")->applyFromArray($style_borde_negrita);
                    $hoja->setCellValue("J2", 'GENERADOR DE CUENTAS');
                    $hoja->getStyle("J2")->applyFromArray($style_borde);
                    $hoja->setCellValue("J3",date('d-m-Y H:i '));
                    $hoja->getStyle("J3")->applyFromArray($style_borde);
                    $hoja->setCellValue("H7",utf8_decode('DEPARTAMENTO'));
                    $hoja->getStyle("H7")->applyFromArray($style_borde_negrita);
                    $hoja->setCellValue("I7",utf8_decode('CORREO INSTITUCIONAL'));
                    $hoja->getStyle("I7")->applyFromArray($style_borde_negrita);
                    $hoja->setCellValue("J7",'CONTRASEÑA');
                    $hoja->getStyle("J7")->applyFromArray($style_borde_negrita);
                }
                if($tipopersona == 3){
                    $nombreArchivo = 'Lista_CorreoInst_Tipo_Estudiante_'.date('dmY').'_'.date('Hi');
                    $hoja->setCellValue("C4",'LISTA DE CORREOS INSTITUCIONALES DE NUEVOS ESTUDIANTES');
                    $hoja->getStyle("C4:I4")->applyFromArray($style_borde_titulo);
                    $hoja->mergeCells('C4:I4');
                    $hoja->getStyle("K7:L7")->applyFromArray($style);
                    $hoja->getStyle("K7:L7")->applyFromArray($style_borde);
                    $hoja->setCellValue("K2",'SISTEMA');
                    $hoja->getStyle("K2")->applyFromArray($style_borde_negrita);
                    $hoja->setCellValue("K3",'FECHA/HORA');
                    $hoja->getStyle("K3")->applyFromArray($style_borde_negrita);
                    $hoja->setCellValue("L2", 'GENERADOR DE CUENTAS');
                    $hoja->getStyle("L2")->applyFromArray($style_borde);
                    $hoja->setCellValue("L3",date('d-m-Y H:i '));
                    $hoja->getStyle("L3")->applyFromArray($style_borde);
                    $hoja->setCellValue("H7",utf8_decode('FACULTAD'));
                    $hoja->getStyle("H7")->applyFromArray($style_borde_negrita);
                    $hoja->setCellValue("I7",utf8_decode('ESCUELA'));
                    $hoja->getStyle("I7")->applyFromArray($style_borde_negrita);
                    $hoja->setCellValue("J7",utf8_decode('SEDE'));
                    $hoja->getStyle("J7")->applyFromArray($style_borde_negrita);
                    $hoja->setCellValue("K7",utf8_decode('CORREO INSTITUCIONAL'));
                    $hoja->getStyle("K7")->applyFromArray($style_borde_negrita);
                    $hoja->setCellValue("L7",'CONTRASEÑA');
                    $hoja->getStyle("L7")->applyFromArray($style_borde_negrita);
                }

                $objPHPExcelL = PHPExcel_IOFactory::identify($rutaArc);
                $objPHPExcelL = PHPExcel_IOFactory::createReader($objPHPExcelL);
                $objPHPExcelL = $objPHPExcelL->load($rutaArc);
                $hojaL = $objPHPExcelL->getSheet(0);
                $ultimaFila = $hojaL->getHighestRow();

                $c = 0;
                $fila = 7;
                for($i = 2; $i <= $ultimaFila; $i++) {
                    $c++;
                    $fila++;
                    $codigo = $hojaL->getCell("A$i")->getValue();
                    $dni = $hojaL->getCell("D$i")->getValue();
                    $row = $object->validarRegistro($arc_id, $dni, $codigo);
                    $hoja->setCellValue("A$fila","$c");
                    $hoja->getStyle("A$fila")->applyFromArray($style_borde);
                    if($row){
                        $hoja->setCellValue("B$fila",$row->dat_codigo);
                        $hoja->getStyle("B$fila")->applyFromArray($style_borde);
                        $hoja->setCellValue("C$fila",$row->dat_dni);
                        $hoja->getStyle("C$fila")->applyFromArray($style_borde);
                        $hoja->setCellValue("D$fila",$row->dat_nombres);
                        $hoja->getStyle("D$fila")->applyFromArray($style_borde);
                        $hoja->setCellValue("E$fila",$row->dat_apellidos);
                        $hoja->getStyle("E$fila")->applyFromArray($style_borde);
                        $hoja->setCellValue("F$fila",$row->dat_celular);
                        $hoja->getStyle("F$fila")->applyFromArray($style_borde);
                        $hoja->setCellValue("G$fila",$row->dat_correo_personal);
                        $hoja->getStyle("G$fila")->applyFromArray($style_borde);
                        if($tipopersona == 1){
                            $hoja->getStyle("I$fila:J$fila")->applyFromArray($style);
                            $hoja->setCellValue("H$fila",strtr($row->dat_unidad, $quitarTildes));
                            $hoja->getStyle("H$fila")->applyFromArray($style_borde);
                            $hoja->setCellValue("I$fila",$row->dat_email);
                            $hoja->getStyle("I$fila")->applyFromArray($style_borde);
                            $hoja->setCellValue("J$fila",utf8_decode($row->dat_clave));
                            $hoja->getStyle("J$fila")->applyFromArray($style_borde);
                        }
                        if($tipopersona == 2){
                            $hoja->getStyle("I$fila:J$fila")->applyFromArray($style);
                            $hoja->setCellValue("H$fila",strtr($row->dat_departamento, $quitarTildes));
                            $hoja->getStyle("H$fila")->applyFromArray($style_borde);
                            $hoja->setCellValue("I$fila",$row->dat_email);
                            $hoja->getStyle("I$fila")->applyFromArray($style_borde);
                            $hoja->setCellValue("J$fila",utf8_decode($row->dat_clave));
                            $hoja->getStyle("J$fila")->applyFromArray($style_borde);
                        }
                        if($tipopersona == 3){
                            $hoja->getStyle("K$fila:L$fila")->applyFromArray($style);
                            $hoja->setCellValue("H$fila",strtr($row->dat_facultad, $quitarTildes));
                            $hoja->getStyle("H$fila")->applyFromArray($style_borde);
                            $hoja->setCellValue("I$fila",$row->dat_escuela);
                            $hoja->getStyle("I$fila")->applyFromArray($style_borde);
                            $hoja->setCellValue("J$fila",$row->dat_sede);
                            $hoja->getStyle("J$fila")->applyFromArray($style_borde);
                            $hoja->setCellValue("K$fila",utf8_decode($row->dat_email));
                            $hoja->getStyle("K$fila")->applyFromArray($style_borde);
                            $hoja->setCellValue("L$fila",utf8_decode($row->dat_clave));
                            $hoja->getStyle("L$fila")->applyFromArray($style_borde);
                        }
                    }else{
                        $nombres = strtoupper($hojaL->getCell("B$i")->getValue());
                        $apellidos = strtoupper($hojaL->getCell("C$i")->getValue());
                        $celular = $hojaL->getCell("E$i")->getValue();
                        $correopersonal = $hojaL->getCell("F$i")->getValue();
                        $completo = $nombres.' '.$apellidos;
                        if($tipopersona == 1){
                            $unidad = $hojaL->getCell("G$i")->getValue();
                            $unidad = str_replace(
                                ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                                ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'],
                                $unidad);
                            $unidad = strtoupper($unidad);
                        }
                        if($tipopersona == 2){
                            $departamento = $hojaL->getCell("G$i")->getValue();
                            $departamento = str_replace(
                                ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                                ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'],
                                $departamento);
                            $departamento = strtoupper($departamento);
                        }
                        if($tipopersona == 3){
                            $facultad = $hojaL->getCell("G$i")->getValue();
                            $facultad = str_replace(
                                ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                                ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'],
                                $facultad);
                            $escuela = $hojaL->getCell("H$i")->getValue();
                            $escuela = str_replace(
                                ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                                ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'],
                                $escuela);
                            $sede = $hojaL->getCell("I$i")->getValue();
                            $sede = str_replace(
                                ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                                ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'],
                                $sede);
                            $facultad = strtoupper($facultad);
                            $escuela = strtoupper($escuela);
                            $sede = strtoupper($sede);
                        }
                        $apellido_limpio = str_replace(
                            ['Ü','ü','á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                            ['U','U','A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'],
                            $apellidos);

                        $nombres_limpio = str_replace(
                            ['Ü','ü','á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                            ['U','U','A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'],
                            $nombres);
                        $completo = str_replace(
                            ['Ü','ü','á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
                            ['U','U','A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'],
                            $completo);
                        $hoja->setCellValue("B$fila",$codigo);
                        $hoja->getStyle("B$fila")->applyFromArray($style_borde);
                        $hoja->setCellValue("C$fila",$dni);
                        $hoja->getStyle("C$fila")->applyFromArray($style_borde);
                        $hoja->setCellValue("D$fila",$nombres_limpio);
                        $hoja->getStyle("D$fila")->applyFromArray($style_borde);
                        $hoja->setCellValue("E$fila",$apellido_limpio);
                        $hoja->getStyle("E$fila")->applyFromArray($style_borde);
                        $hoja->setCellValue("F$fila",$celular);
                        $hoja->getStyle("F$fila")->applyFromArray($style_borde);
                        $hoja->setCellValue("G$fila",$correopersonal);
                        $hoja->getStyle("G$fila")->applyFromArray($style_borde);
                        $dom = $object->soloDominio($arc_id);
                        $val = $object->validarNombres(trim($completo),$emp_id,$dom->dominio);
                        if($tipopersona == 1){
                            $hoja->getStyle("I$fila:J$fila")->applyFromArray($style);
                            $hoja->setCellValue("H$fila",strtr($unidad, $quitarTildes));
                            $hoja->getStyle("H$fila")->applyFromArray($style_borde);
                            if($val){
                                $hoja->setCellValue("I$fila",$val->dat_email);
                                $hoja->getStyle("I$fila")->applyFromArray($style_borde);
                                $hoja->setCellValue("J$fila",utf8_decode($val->dat_clave));
                                $hoja->getStyle("J$fila")->applyFromArray($style_borde);
                            }else{
                                $hoja->setCellValue("I$fila",'');
                                $hoja->getStyle("I$fila")->applyFromArray($style_borde);
                                $hoja->setCellValue("J$fila",'');
                                $hoja->getStyle("J$fila")->applyFromArray($style_borde);
                            }
                            $hoja->getStyle("I$fila:J$fila")->applyFromArray($style_rojo);
                        }
                        if($tipopersona == 2){
                            $hoja->getStyle("I$fila:J$fila")->applyFromArray($style);
                            $hoja->setCellValue("H$fila",strtr($departamento, $quitarTildes));
                            $hoja->getStyle("H$fila")->applyFromArray($style_borde);
                            if($val){
                                $hoja->setCellValue("I$fila",$val->dat_email);
                                $hoja->getStyle("I$fila")->applyFromArray($style_borde);
                                $hoja->setCellValue("J$fila",utf8_decode($val->dat_clave));
                                $hoja->getStyle("J$fila")->applyFromArray($style_borde);
                            }else{
                                $hoja->setCellValue("I$fila",'');
                                $hoja->getStyle("I$fila")->applyFromArray($style_borde);
                                $hoja->setCellValue("J$fila",'');
                                $hoja->getStyle("J$fila")->applyFromArray($style_borde);
                            }
                            $hoja->getStyle("I$fila:J$fila")->applyFromArray($style_rojo);
                        }
                        if($tipopersona == 3){
                            $hoja->getStyle("K$fila:L$fila")->applyFromArray($style);
                            $hoja->setCellValue("H$fila",strtr($facultad, $quitarTildes));
                            $hoja->getStyle("H$fila")->applyFromArray($style_borde);
                            $hoja->setCellValue("I$fila",$escuela);
                            $hoja->getStyle("I$fila")->applyFromArray($style_borde);
                            $hoja->setCellValue("J$fila",$sede);
                            $hoja->getStyle("J$fila")->applyFromArray($style_borde);
                            if($val){
                                $hoja->setCellValue("K$fila",utf8_decode($val->dat_email));
                                $hoja->getStyle("K$fila")->applyFromArray($style_borde);
                                $hoja->setCellValue("L$fila",utf8_decode($val->dat_clave));
                                $hoja->getStyle("L$fila")->applyFromArray($style_borde);
                            }else{
                                $hoja->setCellValue("K$fila",'');
                                $hoja->getStyle("K$fila")->applyFromArray($style_borde);
                                $hoja->setCellValue("L$fila",'');
                                $hoja->getStyle("L$fila")->applyFromArray($style_borde);
                            }
                            $hoja->getStyle("K$fila:L$fila")->applyFromArray($style_rojo);
                        }
                    }
                }
                $fila = $fila + 2;
                switch($tipopersona){
                    case 1:
                        $hoja->setCellValue("I4",'USUARIO');
                        $hoja->getStyle("I4")->applyFromArray($style_borde_negrita);
                        $hoja->setCellValue("J4",strtoupper(session('nombres').' '.session('apellidos')));
                        $hoja->getStyle("J4")->applyFromArray($style_borde);
                        break;
                    case 2:
                        $hoja->setCellValue("I4",'USUARIO');
                        $hoja->getStyle("I4")->applyFromArray($style_borde_negrita);
                        $hoja->setCellValue("J4",strtoupper(session('nombres').' '.session('apellidos')));
                        $hoja->getStyle("J4")->applyFromArray($style_borde);
                        break;
                    case 3:
                        $hoja->setCellValue("K4",'USUARIO');
                        $hoja->getStyle("K4")->applyFromArray($style_borde_negrita);
                        $hoja->setCellValue("L4",strtoupper(session('nombres').' '.session('apellidos')));
                        $hoja->getStyle("L4")->applyFromArray($style_borde);
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
                    $hoja->setCellValue("A$fila",$row->dat_nombres);
                    $hoja->setCellValue("B$fila",$row->dat_apellidos);
                    $hoja->setCellValue("C$fila",$row->dat_email);
                    $hoja->setCellValue("D$fila",$row->dat_clave);
                    $hoja->setCellValue("E$fila",'/');
                    $hoja->setCellValue("F$fila",$row->dat_correo_personal);
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
                        $row->dat_nombres,
                        $row->dat_apellidos,
                        $row->dat_email,
                        $row->dat_clave,
                        '/',
                        $row->dat_correo_personal
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
                $tempNombreA = explode('/',$archivo);
                $ttt = count($tempNombreA) - 1;
                $tempNombreA = $tempNombreA[$ttt];
                $nombreArchivo  =  $tempNombreA;
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
        $idempresa = empresaActiva();
        $emp_id = $idempresa->emp_id;
        $id = $_POST['id'];
        $correo = strtolower($_POST['correo']);
        $val = $object->validarCorreo($correo,$emp_id);
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