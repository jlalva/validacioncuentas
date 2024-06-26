<?php
namespace App\Models;
use CodeIgniter\Model;
class datosModelo extends Model{
    protected $table = 'datos';
    protected $primaryKey = 'dat_id';
    protected $allowedFields = ['dat_nombres', 'dat_apellidos', 'dat_nombres_completos', 'dat_email', 'dat_estado', 'dat_ultimo_acceso',
    'dat_espacio_uso', 'dat_origen', 'dat_arc_id', 'dat_fecha_reg', 'dat_usu_id', 'dat_activo','dat_codigo','dat_dni','dat_celular',
    'dat_correo_personal','dat_facultad','dat_escuela','dat_sede','dat_clave'];

    public function validarCorreo($correo){
        $query = $this->query("SELECT * FROM datos WHERE dat_email = '$correo'");
        return $query->getRow();
    }

    public function insertarDatos($data){
        $query = $this->query("INSERT INTO datos(dat_nombres,dat_apellidos,dat_nombres_completos,dat_email,dat_estado,dat_ultimo_acceso,dat_espacio_uso,dat_origen,
                            dat_arc_id,dat_usu_id) VALUES $data");
        return $query;
    }

    public function insertarDatosGenerados($data){
        $query = $this->query("INSERT INTO datos(dat_codigo,dat_dni,dat_nombres,dat_apellidos,dat_nombres_completos,dat_email,dat_celular,dat_correo_personal,
        dat_facultad,dat_escuela,dat_sede,dat_clave,dat_estado,dat_ultimo_acceso,dat_espacio_uso,dat_origen,dat_arc_id,dat_usu_id) VALUES $data");
        return $query;
    }

    public function validarNombres($nombrecompleto){
        $query = $this->query("SELECT * FROM datos WHERE dat_nombres_completos = '$nombrecompleto'");
        return $query->getRow();
    }

    public function exportar($arc_id){
        $query = $this->query("SELECT * FROM datos WHERE dat_arc_id = $arc_id");
        return $query->getResult();
    }

}