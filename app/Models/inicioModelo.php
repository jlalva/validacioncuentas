<?php
namespace App\Models;
use CodeIgniter\Model;
class inicioModelo extends Model{
    protected $table = 'empresa';
    protected $primaryKey = 'emp_id';
    protected $allowedFields = [];

    public function totalempresas(){
        $query = $this->query("SELECT COUNT(*) as total FROM empresa");
        return $query->getRow();
    }

    public function totalusuarios(){
        $query = $this->query("SELECT COUNT(*) as total FROM usuario");
        return $query->getRow();
    }

    public function totalroles(){
        $query = $this->query("SELECT COUNT(*) as total FROM rol");
        return $query->getRow();
    }

    public function totalcuentas($emp_id){
        $query = $this->query("SELECT COUNT(*) as total FROM datos WHERE dat_origen=2 AND dat_emp_id = $emp_id");
        return $query->getRow();
    }

    public function usurioxrol(){
        $query = $this->query("SELECT rol_nombre,COUNT(usu.usu_rol_id) as total
                                FROM usuario usu
                                INNER JOIN rol rol ON rol.rol_id = usu.usu_rol_id
                                WHERE usu_estado = 1
                                GROUP BY rol_nombre ORDER BY rol_id ASC");
        return $query->getResult();
    }

    public function graficabarra($emp_id, $anio){
        $query = $this->query("SELECT DATE_FORMAT(dat_fecha_reg, '%m') AS mes, arc_tipo_persona, COUNT(*) AS total 
                                FROM datos
                                INNER JOIN archivos ON arc_id=dat_arc_id
                                WHERE DATE_FORMAT(dat_fecha_reg, '%Y') = $anio AND dat_origen = 2 AND dat_emp_id = $emp_id
                                GROUP BY mes,arc_tipo_persona ORDER BY mes;");
        return $query->getResult();
    }

    public function anio($emp_id){
        $query = $this->query("SELECT DISTINCT DATE_FORMAT(dat_fecha_reg,'%Y') as anio
                    FROM datos
                    INNER JOIN archivos ON arc_id=dat_arc_id
                    WHERE dat_origen = 2 AND dat_emp_id = $emp_id ORDER BY dat_fecha_reg DESC");
        return $query->getResult();
    }

}