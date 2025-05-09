<?php

namespace App\Controllers;

use App\Models\archivosModelo;
use App\Models\datosModelo;
use CodeIgniter\Controller;
use PHPExcel_IOFactory;
use PDFSUBIR;

class Subirdata extends Controller
{
    public function index()
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                $object = new archivosModelo();
                $idempresa = empresaActiva();
                $emp_id = $idempresa->emp_id;
                $items = $object->registros(1,$emp_id);
                $datos = ['titulo' => 'Subir datos','items'=>$items];
                return view('datos/subirdata/index', $datos);
            }else{
                return view('denegado');
            }
        }else{
            return redirect()->to(base_url("/"));
        }
    }

    public function detalle($arc_id){
        if (!session('authenticated') || !accede()) {
            return redirect()->to(base_url("/"));
        }

        if (bloqueado()) {
            require_once APPPATH . 'Libraries/Excel/PHPExcel.php';
            $object = new archivosModelo();
            $objectD = new datosModelo();
            $item = $object->archivo($arc_id);
            $archivo = $item->arc_ruta;

            if (!file_exists($archivo)) {
                return view('datos/subirdata/detalle', [
                    'titulo' => 'Subir datos',
                    'table' => "<tr><td colspan='7'>El archivo fue eliminado o no se encuentra en la ruta especificada</td></tr>",
                    'ruta' => '',
                    'id_arch'=>$arc_id
                ]);
            }
            $idempresa = empresaActiva();
            $emp_id = $idempresa->emp_id;
            $ruta = $item->arc_ruta;
            $html = "";
            $nombresRegistrados = array_column($objectD->listarNombres($emp_id), 'dat_nombres_completos');
            $correosRegistrados = array_column($objectD->validarArchivo($arc_id), 'dat_email');
            if ($item->arc_tipo_archivo == 1) {
                $objPHPExcel = PHPExcel_IOFactory::load($archivo);
                $hoja = $objPHPExcel->getSheet(0);
                $ultimaFila = $hoja->getHighestRow();
                for ($i = 2; $i <= $ultimaFila; $i++) {
                    $nombre = caracteres(strtoupper($hoja->getCell("A$i")->getValue()));
                    $apellido = caracteres(strtoupper($hoja->getCell("B$i")->getValue()));
                    $email = $hoja->getCell("C$i")->getValue();
                    $status = strtoupper($hoja->getCell("D$i")->getValue());
                    $ultimoacceso = $hoja->getCell("E$i")->getValue();
                    $espacio = $hoja->getCell("F$i")->getValue();
                    $completo = $nombre . " " . $apellido;
                    if (is_numeric($ultimoacceso)) {
                        $timestamp = \PHPExcel_Shared_Date::ExcelToPHP($ultimoacceso);
                        $ultimoacceso = date('d/m/Y H:i:s', $timestamp);
                    }
                    $color = "";
                    if (in_array($email, $correosRegistrados)) {
                        $color = "style='background-color: red'";
                    }
                    $html .= "<tr $color>
                                <td style='text-align: center;'>" . ($i - 1) . "</td>
                                <td style='text-align: center;'>$nombre</td>
                                <td style='text-align: center;'>$apellido</td>
                                <td style='text-align: center;'>$email</td>
                                <td style='text-align: center;'>$status</td>
                                <td style='text-align: center;'>$ultimoacceso</td>
                                <td style='text-align: center;'>$espacio</td>
                            </tr>";
                }
            } else {
                $lineas = file($archivo);
                foreach ($lineas as $c => $linea) {
                    if ($c == 0) continue;
                    $datos = preg_split("/[;,]/", $linea);
                    $nombre = caracteres($datos[0]);
                    $apellido = caracteres($datos[1]);
                    $email = trim($datos[2]);
                    $status = strtoupper($datos[3]);
                    $ultimoacceso = trim($datos[4]);
                    $espacio = trim($datos[5]);
                    $completo = $nombre . " " . $apellido;
                    $color = "";
                    if (!in_array($completo, $nombresRegistrados)) {
                        $color = "style='background-color: red'";
                    } elseif (in_array($email, $correosRegistrados)) {
                        $color = "style='background-color: green'";
                    }
                    $html .= "<tr $color>
                                <td style='text-align: center;'>$c</td>
                                <td style='text-align: center;'>$nombre</td>
                                <td style='text-align: center;'>$apellido</td>
                                <td style='text-align: center;'>$email</td>
                                <td style='text-align: center;'>$status</td>
                                <td style='text-align: center;'>$ultimoacceso</td>
                                <td style='text-align: center;'>$espacio</td>
                            </tr>";
                }
            }
            return view('datos/subirdata/detalle', ['titulo' => 'Subir datos','table' => $html,'ruta' => $ruta, 'id_arch'=>$arc_id]);
        } else {
            return view('denegado');
        }
    }

    public function validar(){
        $object = new datosModelo();
        if(isset($_FILES["archivo"])) {
            $idempresa = empresaActiva();
            $emp_id = $idempresa->emp_id;
            $tipoarchivo = $_POST['tipoarchivo'];
            $resultado = '';
            $dupli = 0;
            $invalido = 0;
            $totalRegistros = 0;
            $archivo = $_FILES["archivo"]["tmp_name"];
            $archivoext = $_FILES["archivo"]["name"];
            $extension = pathinfo($archivoext, PATHINFO_EXTENSION);
            if (($extension == 'csv' && $tipoarchivo == 1) || (in_array($extension, ['xlsx', 'xls']) && $tipoarchivo == 2)) {
                echo "El tipo de archivo no coincide con el archivo seleccionado.";
                exit;
            }
            if (!in_array($extension, ['xlsx', 'xls', 'csv'])) {
                echo "El archivo seleccionado no es permitido.";
                exit;
            }
            $correosExistentes = array_column($object->query("SELECT dat_email FROM datos WHERE dat_emp_id = $emp_id")->getResultArray(), 'dat_email');
            $emailsProcesados = [];

            if($tipoarchivo == 1){
                require_once APPPATH . 'Libraries/Excel/PHPExcel.php';
                $objPHPExcel = PHPExcel_IOFactory::identify($archivo);
                $objPHPExcel = PHPExcel_IOFactory::createReader($objPHPExcel);
                $objPHPExcel = $objPHPExcel->load($archivo);
                $hoja = $objPHPExcel->getSheet(0);
                $ultimaFila = $hoja->getHighestRow();

                for($i = 2; $i <= $ultimaFila; $i++) {
                    $email = strtolower($hoja->getCell("C$i")->getValue());
                    if (!empty($email) && !in_array($email, $emailsProcesados)) {
                        $emailsProcesados[] = $email;
                        if (validar_correo($email)) {
                            if (in_array($email, $correosExistentes)) {
                                $dupli++;
                            }
                        } else {
                            $invalido++;
                        }
                    }
                }
                $totalRegistros = count($emailsProcesados);
            }else{
                $lineas = file($archivo);
                foreach ($lineas as $indice  => $linea){
                    if ($indice == 0) continue;
                    $datos = preg_split("/[;,]/", $linea);
                    $email = strtolower(trim($datos[2] ?? ''));
                    if (!empty($email) && !in_array($email, $emailsProcesados)) {
                        $emailsProcesados[] = $email;
                        if (validar_correo($email)) {
                            if (in_array($email, $correosExistentes)) {
                                $dupli++;
                            }
                        } else {
                            $invalido++;
                            echo $email.'<br>';
                        }
                    }
                }
                $totalRegistros = count($emailsProcesados);
            }

            $aregistrar = $totalRegistros - $dupli -$invalido;
            $resultado .= "Total registros ".$totalRegistros."<br>";
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
        $db = \Config\Database::connect();
        $object = new datosModelo();
        $objectArc = new archivosModelo();
        if (!isset($_FILES["archivo"])) {
            echo "No se ha seleccionado ningún archivo.";
            exit;
        }
        $inicio = microtime(true);
        $idempresa = empresaActiva();
        $emp_id = $idempresa->emp_id;
        $empresa = strtoupper($idempresa->emp_razonsocial);
        $tipoarchivo = $_POST['tipoarchivo'];
        $descripcion = $_POST['descripcion'];
        $archivo = $_FILES["archivo"]["tmp_name"];
        $nombrearchivo = $_FILES["archivo"]["name"];
        $extension = pathinfo($nombrearchivo, PATHINFO_EXTENSION);
        $nombreserver = 'ws_'.date("Ymd").'_'.date("His").'.'.strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));
        $rutaempresa = "public/empresas/$empresa";
        if (!file_exists($rutaempresa)) {
            mkdir($rutaempresa, 0777, true);
        }
        $ruta = crearCarpetasPorFecha("$rutaempresa/archivos/CUENTAS_EXISTENTES_GSUITE/");
        $rutaArchivo = $ruta."/".$nombreserver;
        if (($extension == 'csv' && $tipoarchivo == 1) || (in_array($extension, ['xlsx', 'xls']) && $tipoarchivo == 2)) {
            echo "El tipo de archivo no coincide con el archivo seleccionado.";
            exit;
        }
        if (!in_array($extension, ['xlsx', 'xls', 'csv'])) {
            echo "El archivo seleccionado no es permitido.";
            exit;
        }
        $correosExistentes = array_column($object->query("SELECT dat_email FROM datos WHERE dat_emp_id = $emp_id")->getResultArray(), 'dat_email');
        try {
            $data = [
                'arc_nombre' => $nombrearchivo,
                'arc_ruta' => $rutaArchivo,
                'arc_total' => 0,
                'arc_subido' => 0,
                'arc_usu_id' => session('idusuario'),
                'arc_tipo_archivo' => $tipoarchivo,
                'arc_descripcion' => $descripcion,
                'arc_emp_id'=>$emp_id
            ];
            if (!$objectArc->add($data)) {
                throw new Exception("Error al registrar el archivo en la base de datos.");
            }
            $arc_id = $objectArc->getInsertID();
            $dupli = 0;
            $invalido = 0;
            $totalRegistros = 0;
            $datosInsertar = [];
            $emailsProcesados = [];
            if ($tipoarchivo == 1) {
                require_once APPPATH . 'Libraries/Excel/PHPExcel.php';
                $objPHPExcel = PHPExcel_IOFactory::load($archivo);
                $hoja = $objPHPExcel->getSheet(0);
                $ultimaFila = $hoja->getHighestRow();
                for ($i = 2; $i <= $ultimaFila; $i++) {
                    $nombre = $db->escapeString(caracteres(strtoupper($hoja->getCell("A$i")->getValue())));
                    $apellido = $db->escapeString(caracteres(strtoupper($hoja->getCell("B$i")->getValue())));
                    $email = strtolower(trim($hoja->getCell("C$i")->getValue()));
                    $status = strtoupper($hoja->getCell("D$i")->getValue());
                    $ultimoacceso = $hoja->getCell("E$i")->getValue();
                    $nombre = Trim($nombre);
                    $apellido = Trim($apellido);
                    $email = Trim($email);
                    $status = Trim($status);
                    if (is_numeric($ultimoacceso)) {
                        $timestamp = \PHPExcel_Shared_Date::ExcelToPHP($ultimoacceso);
                        $ultimoacceso = date('d/m/Y H:i:s', $timestamp);
                    }
                    $espacio = $hoja->getCell("F$i")->getValue();
                    $completo = $nombre.' '.$apellido;
                    if (!empty($email) && !in_array($email, $emailsProcesados)) {
                        $emailsProcesados[] = $email;
                        if (validar_correo($email)) {
                            if (in_array($email, $correosExistentes)) {
                                $dupli++;
                            } else {
                                $datosInsertar[] = "('$nombre','$apellido','$completo','$email','$status','$ultimoacceso','$espacio',1,$arc_id,".session('idusuario').",$emp_id)";
                            }
                        } else {
                            $invalido++;
                        }
                    }
                }
                $totalRegistros = count($emailsProcesados);
            } else {
                $lineas = file($archivo);
                foreach ($lineas as $indice => $linea) {
                    if ($indice == 0) continue;
                    $item = preg_split("/[;,]/", $db->escapeString($linea));
                    $nombre = caracteres(strtoupper($item[0] ?? ''));
                    $apellido = caracteres(strtoupper($item[1] ?? ''));
                    $email = strtolower(trim($item[2] ?? ''));
                    $status = strtoupper($item[3] ?? '');
                    $ultimoacceso = $item[4] ?? '';
                    $espacio = $item[5] ?? '';
                    $nombre = Trim($nombre);
                    $apellido = Trim($apellido);
                    $email = Trim($email);
                    $status = Trim($status);
                    $completo = $nombre.' '.$apellido;
                    if (!empty($email) && !in_array($email, $emailsProcesados)) {
                        $emailsProcesados[] = $email;
                        if (validar_correo($email)) {
                            if (in_array($email, $correosExistentes)) {
                                $dupli++;
                            } else {
                                $datosInsertar[] = "('$nombre','$apellido','$completo','$email','$status','$ultimoacceso','$espacio',1,$arc_id,".session('idusuario').",$emp_id)";
                            }
                        } else {
                            $invalido++;
                        }
                    }
                }
                $totalRegistros = count($emailsProcesados);
            }
            $aregistrar = $totalRegistros - $dupli - $invalido;
            if ($aregistrar > 0) {
                if (!$object->insertarDatos($datosInsertar)) {
                    throw new Exception("Error al insertar los registros.");
                }
            }
            if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaArchivo)) {
                throw new Exception("Error al mover el archivo.");
            }
            $fin = microtime(true);

            $tiempoTotalSegundos = $fin - $inicio;
            $horas = floor($tiempoTotalSegundos / 3600);
            $minutos = floor(($tiempoTotalSegundos % 3600) / 60);
            $segundos = floor($tiempoTotalSegundos % 60);
            $tiempoFormateado = sprintf("%02d:%02d:%02d", $horas, $minutos, $segundos);
            $objectArc->upd($arc_id, [
                'arc_total' => $totalRegistros,
                'arc_subido' => $aregistrar,
                'arc_tiempo' => $tiempoFormateado
            ]);
            echo 'ok';
        } catch (Exception $e) {
            if (file_exists($rutaArchivo)) {
                unlink($rutaArchivo);
            }
            if (isset($arc_id)) {
                $objectArc->deleteArchivo($arc_id);
            }
            echo "Error: " . $e->getMessage();
        }
    }

    public function pdf($arc_id){
        if (!session('authenticated') || !accede()) {
            return redirect()->to(base_url("/"));
        }
        require_once APPPATH . 'Libraries/PDFSUBIR.php';
        if (bloqueado()) {
            require_once APPPATH . 'Libraries/Excel/PHPExcel.php';
            $object = new archivosModelo();
            $objectD = new datosModelo();
            $item = $object->archivo($arc_id);
            $archivo =  $item->arc_ruta;
            $nombreArchivo  =  $item->arc_nombre;
            $nombreArchivo = explode('.',$nombreArchivo);
            $titulo = $nombreArchivo[0];
            $nombreArchivo = $nombreArchivo[0].'.pdf';

            $idempresa = empresaActiva();
            $emp_id = $idempresa->emp_id;

            if (!file_exists($archivo)) {
                return view('datos/subirdata/detalle', [
                    'titulo' => 'Subir datos',
                    'table' => "<tr><td colspan='7'>El archivo fue eliminado o no se encuentra en la ruta especificada</td></tr>",
                    'ruta' => ''
                ]);
            }

            $pdf = new PDFSUBIR();
            $pdf->AddPage('L');
            $pdf->AliasNbPages();

            $x = [0=>11,1=>60,2=>60,3=>60,4=>20,5=>35,6=>30];
            $y = 5;
            $pdf->SetFont('Arial','B',8);

            $nombresRegistrados = array_column($objectD->listarNombres($emp_id), 'dat_nombres_completos');
            $correosRegistrados = array_column($objectD->validarArchivo($arc_id), 'dat_email');
            $pdf->SetFont('Arial','',8);
            if ($item->arc_tipo_archivo == 1) {
                $objPHPExcel = PHPExcel_IOFactory::load($archivo);
                $hoja = $objPHPExcel->getSheet(0);
                $ultimaFila = $hoja->getHighestRow();
                for ($i = 2; $i <= $ultimaFila; $i++) {
                    $nombre = caracteres($hoja->getCell("A$i")->getValue());
                    $apellido = caracteres($hoja->getCell("B$i")->getValue());
                    $email = $hoja->getCell("C$i")->getValue();
                    $status = strtoupper($hoja->getCell("D$i")->getValue());
                    $ultimoacceso = $hoja->getCell("E$i")->getValue();
                    $espacio = $hoja->getCell("F$i")->getValue();
                    if (is_numeric($ultimoacceso)) {
                        $timestamp = \PHPExcel_Shared_Date::ExcelToPHP($ultimoacceso);
                        $ultimoacceso = date('d/m/Y H:i:s', $timestamp);
                    }

                    $pdf->Cell($x[0],$y, ($i - 1),1,0,'C');
                    $pdf->Cell($x[1],$y, utf8_decode($nombre),1,0,'C');
                    $pdf->Cell($x[2],$y, utf8_decode($apellido),1,0,'C');
                    $pdf->Cell($x[3],$y, utf8_decode($email),1,0,'C');
                    $pdf->Cell($x[4],$y, utf8_decode($status),1,0,'C');
                    $pdf->Cell($x[5],$y, utf8_decode($ultimoacceso),1,0,'C');
                    $pdf->Cell($x[6],$y, utf8_decode($espacio),1,1,'C');

                }
            } else {
                $lineas = file($archivo);
                foreach ($lineas as $c => $linea) {
                    if ($c == 0) continue;
                    $datos = preg_split("/[;,]/", $linea);
                    $nombre = caracteres($datos[0]);
                    $apellido = caracteres($datos[1]);
                    $email = trim($datos[2]);
                    $status = strtoupper($datos[3]);
                    $ultimoacceso = trim($datos[4]);
                    $espacio = trim($datos[5]);
                    $pdf->Cell($x[0],$y, $c,1,0,'C');
                    $pdf->Cell($x[1],$y, utf8_decode($nombre),1,0,'C');
                    $pdf->Cell($x[2],$y, utf8_decode($apellido),1,0,'C');
                    $pdf->Cell($x[3],$y, utf8_decode($email),1,0,'C');
                    $pdf->Cell($x[4],$y, utf8_decode($status),1,0,'C');
                    $pdf->Cell($x[5],$y, utf8_decode($ultimoacceso),1,0,'C');
                    $pdf->Cell($x[6],$y, utf8_decode($espacio),1,1,'C');
                }
            }
            $pdf->Ln();
            $pdf->Cell(0,$y, 'USUARIO: '.utf8_decode(strtoupper(session('nombres').' '.session('apellidos'))),0,1,'R');
            if (headers_sent()) {
                die("¡Los encabezados ya fueron enviados, no se puede mostrar el PDF!");
            }

            header("Content-Type: application/pdf");
            header("Cache-Control: private");
            header("Pragma: no-cache");
            header("Expires: 0");
            $pdf->SetTitle($titulo);
            $pdf->Output($nombreArchivo, 'I');
            exit;
        } else {
            return view('denegado');
        }
    }
}