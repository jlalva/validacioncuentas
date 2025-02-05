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
    if (!session('authenticated') || !accede()) {
        return redirect()->to(base_url("/"));
    }

    if (bloqueado()) {
        require_once APPPATH . 'Libraries/Excel/PHPExcel.php';
        $object = new archivosModelo();
        $objectD = new datosModelo();
        $item = $object->archivo($arc_id);
        $archivo = "public/" . $item->arc_ruta;

        if (!file_exists($archivo)) {
            return view('datos/subirdata/detalle', [
                'titulo' => 'Subir datos',
                'table' => "<tr><td colspan='7'>El archivo fue eliminado o no se encuentra en la ruta especificada</td></tr>",
                'ruta' => ''
            ]);
        }

        $ruta = $item->arc_ruta;
        $html = "";
        // Obtener todos los nombres registrados
        $nombresRegistrados = array_column($objectD->listarNombres(), 'dat_nombres_completos');
        // Obtener todos los correos registrados con este archivo
        $correosRegistrados = array_column($objectD->validarArchivo($arc_id), 'dat_email');
        if ($item->arc_tipo_archivo == 1) {
            // Procesar archivo Excel
            $objPHPExcel = PHPExcel_IOFactory::load($archivo);
            $hoja = $objPHPExcel->getSheet(0);
            $ultimaFila = $hoja->getHighestRow();
            for ($i = 2; $i <= $ultimaFila; $i++) {
                $nombre = strtoupper($hoja->getCell("A$i")->getValue());
                $apellido = strtoupper($hoja->getCell("B$i")->getValue());
                $email = $hoja->getCell("C$i")->getValue();
                $status = strtoupper($hoja->getCell("D$i")->getValue());
                $ultimoacceso = $hoja->getCell("E$i")->getValue();
                $espacio = $hoja->getCell("F$i")->getValue();
                $completo = $nombre . " " . $apellido;
                // Validar formato de fecha
                if (is_numeric($ultimoacceso)) {
                    $timestamp = \PHPExcel_Shared_Date::ExcelToPHP($ultimoacceso);
                    $ultimoacceso = date('d/m/Y H:i:s', $timestamp);
                }
                // Determinar color
                $color = "";
                if (!in_array($completo, $nombresRegistrados)) {
                    $color = "style='background-color: red'";
                } elseif (in_array($email, $correosRegistrados)) {
                    $color = "style='background-color: green'";
                }
                $html .= "<tr $color>
                            <td>" . ($i - 1) . "</td>
                            <td>$nombre</td>
                            <td>$apellido</td>
                            <td>$email</td>
                            <td>$status</td>
                            <td>$ultimoacceso</td>
                            <td>$espacio</td>
                          </tr>";
            }
        } else {
            // Procesar archivo CSV
            $lineas = file($archivo);
            foreach ($lineas as $c => $linea) {
                if ($c == 0) continue; // Saltar encabezado
                $datos = preg_split("/[;,]/", $linea);
                $nombre = strtoupper($datos[0]);
                $apellido = strtoupper($datos[1]);
                $email = trim($datos[2]);
                $status = strtoupper($datos[3]);
                $ultimoacceso = trim($datos[4]);
                $espacio = trim($datos[5]);
                $completo = $nombre . " " . $apellido;
                // Determinar color
                $color = "";
                if (!in_array($completo, $nombresRegistrados)) {
                    $color = "style='background-color: red'";
                } elseif (in_array($email, $correosRegistrados)) {
                    $color = "style='background-color: green'";
                }

                $html .= "<tr $color>
                            <td>$c</td>
                            <td>$nombre</td>
                            <td>$apellido</td>
                            <td>$email</td>
                            <td>$status</td>
                            <td>$ultimoacceso</td>
                            <td>$espacio</td>
                          </tr>";
            }
        }

        return view('datos/subirdata/detalle', ['titulo' => 'Subir datos','table' => $html,'ruta' => $ruta
        ]);
    } else {
        return view('denegado');
    }
}


    public function validar(){
        $object = new datosModelo();
        if(isset($_FILES["archivo"])) {
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

             // Obtener todos los correos de la base de datos en una sola consulta
            $correosExistentes = array_column($object->query("SELECT dat_email FROM datos")->getResultArray(), 'dat_email');
            $emailsProcesados = [];

            if($tipoarchivo == 1){
                require_once APPPATH . 'Libraries/Excel/PHPExcel.php';
                $objPHPExcel = PHPExcel_IOFactory::identify($archivo);
                $objPHPExcel = PHPExcel_IOFactory::createReader($objPHPExcel);
                $objPHPExcel = $objPHPExcel->load($archivo);
                $hoja = $objPHPExcel->getSheet(0);
                $ultimaFila = $hoja->getHighestRow();

                for($i = 2; $i <= $ultimaFila; $i++) {
                    $email = $hoja->getCell("C$i")->getValue();
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
        $object = new datosModelo();
        $objectArc = new archivosModelo();
        if (!isset($_FILES["archivo"])) {
            echo "No se ha seleccionado ningún archivo.";
            exit;
        }
        $tipoarchivo = $_POST['tipoarchivo'];
        $descripcion = $_POST['descripcion'];
        $archivo = $_FILES["archivo"]["tmp_name"];
        $nombrearchivo = $_FILES["archivo"]["name"];
        $extension = pathinfo($nombrearchivo, PATHINFO_EXTENSION);
        $nombreserver = 'ws_'.date("Ymd").'_'.date("His").'.'.strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));
        $rutaArchivo = "public/archivos/subirdatos/".$nombreserver;
        // Validar tipo de archivo
        if (($extension == 'csv' && $tipoarchivo == 1) || (in_array($extension, ['xlsx', 'xls']) && $tipoarchivo == 2)) {
            echo "El tipo de archivo no coincide con el archivo seleccionado.";
            exit;
        }
        if (!in_array($extension, ['xlsx', 'xls', 'csv'])) {
            echo "El archivo seleccionado no es permitido.";
            exit;
        }
        // Obtener todos los correos de la base de datos en una sola consulta
        $correosExistentes = array_column($object->query("SELECT dat_email FROM datos")->getResultArray(), 'dat_email');
        try {
            // Guardar metadatos del archivo
            $data = [
                'arc_nombre' => $nombrearchivo,
                'arc_ruta' => "archivos/subirdatos/".$nombreserver,
                'arc_total' => 0,
                'arc_subido' => 0,
                'arc_usu_id' => session('idusuario'),
                'arc_tipo_archivo' => $tipoarchivo,
                'arc_descripcion' => $descripcion
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
            // Procesar archivo
            if ($tipoarchivo == 1) {
                require_once APPPATH . 'Libraries/Excel/PHPExcel.php';
                $objPHPExcel = PHPExcel_IOFactory::load($archivo);
                $hoja = $objPHPExcel->getSheet(0);
                $ultimaFila = $hoja->getHighestRow();
                for ($i = 2; $i <= $ultimaFila; $i++) {
                    $nombre = strtoupper($hoja->getCell("A$i")->getValue());
                    $apellido = strtoupper($hoja->getCell("B$i")->getValue());
                    $email = strtolower(trim($hoja->getCell("C$i")->getValue()));
                    $status = strtoupper($hoja->getCell("D$i")->getValue());
                    $ultimoacceso = $hoja->getCell("E$i")->getValue();
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
                                $datosInsertar[] = "('$nombre','$apellido','$completo','$email','$status','$ultimoacceso','$espacio',1,$arc_id,".session('idusuario').")";
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
                    if ($indice == 0) continue; // Saltar la cabecera
                    $item = preg_split("/[;,]/", $linea);
                    $nombre = strtoupper($item[0] ?? '');
                    $apellido = strtoupper($item[1] ?? '');
                    $email = strtolower(trim($item[2] ?? ''));
                    $status = strtoupper($item[3] ?? '');
                    $ultimoacceso = $item[4] ?? '';
                    $espacio = $item[5] ?? '';
                    $completo = $nombre.' '.$apellido;
                    if (!empty($email) && !in_array($email, $emailsProcesados)) {
                        $emailsProcesados[] = $email;
                        if (validar_correo($email)) {
                            if (in_array($email, $correosExistentes)) {
                                $dupli++;
                            } else {
                                $datosInsertar[] = "('$nombre','$apellido','$completo','$email','$status','$ultimoacceso','$espacio',1,$arc_id,".session('idusuario').")";
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
                //$query = "INSERT INTO datos (dat_nombre, dat_apellido, dat_completo, dat_email, dat_status, dat_ultimoacceso, dat_espacio, dat_estado, dat_arc_id, dat_usu_id) VALUES " . implode(',', $datosInsertar);
                if (!$object->insertarDatos($datosInsertar)) {
                    throw new Exception("Error al insertar los registros.");
                }
            }
            // Mover el archivo a su ubicación final
            if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaArchivo)) {
                throw new Exception("Error al mover el archivo.");
            }
            // Actualizar información del archivo en la base de datos
            $objectArc->upd($arc_id, [
                'arc_total' => $totalRegistros,
                'arc_subido' => $aregistrar
            ]);
            echo 'ok';
        } catch (Exception $e) {
            // Eliminar archivo subido si ocurrió un error
            if (file_exists($rutaArchivo)) {
                unlink($rutaArchivo);
            }
            // Eliminar el registro del archivo en la base de datos
            if (isset($arc_id)) {
                $objectArc->deleteArchivo($arc_id);
            }
            echo "Error: " . $e->getMessage();
        }
    }
}