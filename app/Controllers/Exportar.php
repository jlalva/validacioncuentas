<?php

namespace App\Controllers;

use App\Models\archivosModelo;
use App\Models\datosModelo;
use CodeIgniter\Controller;
use PHPExcel_IOFactory;
use PDFSUBIR;

class Exportar extends Controller
{
    public function index()
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                $object = new archivosModelo();
                $idempresa = empresaActiva();
                $emp_id = $idempresa->emp_id;
                $items = $object->todo($emp_id);
                $datos = ['titulo' => 'Exportar','items'=>$items];
                return view('datos/exportar/index', $datos);
            }else{
                return view('denegado');
            }
        }else{
            return redirect()->to(base_url("/"));
        }
    }

    public function filtrado(){
        $object = new archivosModelo();
        $idempresa = empresaActiva();
        $emp_id = $idempresa->emp_id;
        $tipo = $_POST['tipo'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        $items = $object->filtrado($emp_id,$tipo,$fecha_inicio,$fecha_fin);
        $c = 0;
        $html = '';
        foreach ($items as $row) {
            $c++;
            $html .="<tr>
                <td  style='text-align: center;'>$c</td>
                <td  style='text-align: center;'>$row->arc_nombre</td>
                <td  style='text-align: center;'>$row->arc_ruta</td>
                <td  style='text-align: center;'>$row->arc_total</td>
                <td  style='text-align: center;'>$row->arc_subido</td>
                <td  style='text-align: center;'>$row->usu_nombre $row->usu_apellido</td>
                <td  style='text-align: center;'>$row->arc_fecha_reg</td>
                <td style='text-align: center;'>";
                    if($row->arc_origen == 1)
                        $html .="<a href='".base_url($row->arc_ruta)."' class='btn btn-info btn-sm' title='DESCARGAR DATA'><i class='bx bx-arrow-to-bottom'></i></a>";
                    else{
                        $html .="<a href='".base_url('generardata/descargarcuentas/$row->arc_id')."' class='btn btn-info btn-sm' title='DESCARGAR DATA'><i class='bx bx-arrow-to-bottom'></i></a>";
                    }
            $html .="</td>
            </tr>";
                }
                echo $html;
    }
}