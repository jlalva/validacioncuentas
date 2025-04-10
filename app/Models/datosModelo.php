<?php
namespace App\Models;
use CodeIgniter\Model;
class datosModelo extends Model{
    protected $table = 'datos';
    protected $primaryKey = 'dat_id';
    protected $allowedFields = ['dat_nombres', 'dat_apellidos', 'dat_nombres_completos', 'dat_email', 'dat_estado', 'dat_ultimo_acceso',
    'dat_espacio_uso', 'dat_origen', 'dat_arc_id', 'dat_fecha_reg', 'dat_usu_id', 'dat_activo','dat_codigo','dat_dni','dat_celular',
    'dat_correo_personal','dat_facultad','dat_escuela','dat_sede','dat_clave', 'dat_unidad','dat_departamento','dat_emp_id'];

    public function validarCorreo($correo,$emp_id){
        $query = $this->query("SELECT * FROM datos WHERE dat_email = '$correo' AND dat_emp_id = $emp_id");
        return $query->getRow();
    }

    public function insertarDatos($data){
        $query = $this->query("INSERT INTO datos(dat_nombres,dat_apellidos,dat_nombres_completos,dat_email,dat_estado,dat_ultimo_acceso,dat_espacio_uso,dat_origen,
                            dat_arc_id,dat_usu_id,dat_emp_id) VALUES " . implode(',', $data));
        return $query;
    }

    public function listarNombres($emp_id,$dominio = false)
    {
        if($dominio){
            return $this->query("SELECT dat_nombres_completos FROM datos WHERE dat_emp_id = $emp_id AND dat_email LIKE '%$dominio%' ORDER BY dat_nombres_completos ASC")
                    ->getResult();
        }else{
            return $this->query("SELECT dat_nombres_completos FROM datos WHERE dat_emp_id = $emp_id ORDER BY dat_nombres_completos ASC")
                    ->getResult();
        }
    }

    public function listarCorreos($emp_id)
    {
        return $this->query("SELECT dat_email FROM datos WHERE dat_emp_id = $emp_id")
                    ->getResult();
    }

    public function insertarDatosGenerados($data,$tipopersona){
        if($tipopersona == 1){
            $campos = "dat_codigo,dat_dni,dat_nombres,dat_apellidos,dat_nombres_completos,dat_email,dat_celular,dat_correo_personal,dat_unidad,dat_clave,dat_estado,
            dat_ultimo_acceso,dat_espacio_uso,dat_origen,dat_arc_id,dat_usu_id,dat_emp_id";
        }
        if($tipopersona == 2){
            $campos = "dat_codigo,dat_dni,dat_nombres,dat_apellidos,dat_nombres_completos,dat_email,dat_celular,dat_correo_personal,dat_departamento,dat_clave,dat_estado,
            dat_ultimo_acceso,dat_espacio_uso,dat_origen,dat_arc_id,dat_usu_id,dat_emp_id";
        }
        if($tipopersona == 3){
            $campos = "dat_codigo,dat_dni,dat_nombres,dat_apellidos,dat_nombres_completos,dat_email,dat_celular,dat_correo_personal,dat_facultad,dat_escuela,dat_sede,
            dat_clave,dat_estado,dat_ultimo_acceso,dat_espacio_uso,dat_origen,dat_arc_id,dat_usu_id,dat_emp_id";
        }
        $query = $this->query("INSERT INTO datos($campos) VALUES $data");
        return $query;
    }

    public function validarNombres($nombrecompleto, $emp_id, $dominio){
        $query = $this->query("SELECT * FROM datos WHERE dat_nombres_completos = '$nombrecompleto' AND dat_emp_id = $emp_id AND dat_email LIKE '%$dominio%'");
        return $query->getRow();
    }

    public function validarArchivo($arc_id){
        $query = $this->query("SELECT * FROM datos WHERE dat_arc_id = $arc_id");
        return $query->getResult();
    }

    public function validarRegistro($arc_id, $dni, $codigo){
        error_log("SELECT * FROM datos WHERE dat_arc_id = $arc_id AND dat_dni = '$dni' AND dat_codigo = '$codigo'");
        $query = $this->query("SELECT * FROM datos WHERE dat_arc_id = $arc_id AND dat_dni = '$dni' AND dat_codigo = '$codigo'");
        return $query->getRow();
    }

    public function soloDominio($arc_id){
        $query = $this->query("SELECT DISTINCT SUBSTRING_INDEX(dat_email, '@', -1) as dominio FROM datos WHERE dat_arc_id = $arc_id");
        return $query->getRow();
    }

    public function exportar($arc_id){
        $query = $this->query("SELECT * FROM datos WHERE dat_arc_id = $arc_id");
        return $query->getResult();
    }

    public function updateCacafonias($id, $data){
        return $this->update($id, $data);
    }

    public function validarDuplicados($arc_id){
        $query = $this->query("SELECT dat_email,COUNT(dat_email) FROM datos WHERE dat_arc_id = $arc_id GROUP BY dat_email HAVING COUNT(dat_email) > 1 ORDER BY dat_email ASC");
        return $query->getResult();
    }

    public function cuentasDuplicados($arc_id,$email){
        $query = $this->query("SELECT * FROM datos WHERE dat_arc_id = $arc_id AND dat_email = '$email'");
        return $query->getResult();
    }

}