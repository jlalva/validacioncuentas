<?php

namespace App\Controllers;

use App\Models\backupModelo;
use CodeIgniter\Controller;
use Ifsnop\Mysqldump as IMysqldump;

class Backup extends Controller
{
    public function index()
    {
        if(session('authenticated') && accede()){
            if(bloqueado()){
                $object = new backupModelo();
                $items = $object->reads();
                $datos = ['titulo' => 'BACKUP','items' => $items];
                return view('gestionar/backup/index', $datos);
            }else{
                return view('denegado');
            }
        }else{
            return redirect()->to(base_url("/"));
        }
    }

    public function generarBackup(){
        $object = new backupModelo();
        $nombre_archivo_respaldo = 'bk_' . date('Ymd_His') . '.sql';
        $ruta_destino_respaldo = 'public/backups/';
        $tamanio = 0;
        $ruta = $ruta_destino_respaldo.$nombre_archivo_respaldo;
        if (!file_exists($ruta_destino_respaldo)) {
            mkdir($ruta_destino_respaldo, 0777, true); // 0777 otorga permisos completos
        }
        try {
            $host = hostname;
            $dbname = database;
            $dbuser = username;
            $dbpass = password;
            $dump = new IMysqldump\Mysqldump("mysql:host=$host;dbname=$dbname", "$dbuser", "$dbpass");
            $dump->start($ruta);
            $tamanio = filesize($ruta);
            if($tamanio>0){
                $data = [
                    'bac_nombre'=>$nombre_archivo_respaldo,
                    'bac_tamanio'=>formatBytes($tamanio),
                    'bac_usu_id'=>session('idusuario')
                ];
                $object->add($data);
            }
        } catch (\Exception $e) {
            echo 'mysqldump-php error: ' . $e->getMessage();
        }
    }

    public function eliminar(){
        $object = new backupModelo();
        $id = $_POST['bac_id'];
        $data = [
            'bac_estado' => 0
        ];
        if($object->updateBackup($id, $data)){
            echo 'ok';
        }else{
            echo 'error';
        }
    }

    public function restaurar(){
        $object = new backupModelo();
        if(!empty($_FILES['file']['name'])){
            $ruta = 'public/backups/restaurados';
            if (!file_exists($ruta)) {
                mkdir($ruta, 0777, true);
            }
            if(move_uploaded_file($_FILES['file']['tmp_name'],$ruta."/".$_FILES['file']['name'])){
                $talas = $object->tablas();
                if($talas){
                    foreach($talas as $row){
                        $object->borrarTabla($row->tabla);
                    }
                }

                $ruta = $ruta."/".$_FILES['file']['name'];
                $host = hostname;
                $dbname = database;
                $dbuser = username;
                $dbpass = password;
                $dump = new IMysqldump\Mysqldump("mysql:host=$host;dbname=$dbname", "$dbuser", "$dbpass");
                $dump->restore($ruta);
                return redirect()->to(base_url("/salir"));
            }else{
                $resp = 2;
            }
        }else{
            $resp = 2;
        }
        echo $resp;
    }
}