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

    public function totalcuentas(){
        $query = $this->query("SELECT COUNT(*) as total FROM datos WHERE dat_origen=2");
        return $query->getRow();
    }

    public function usurioxrol(){
        $query = $this->query("SELECT rol_nombre,COUNT(usu.usu_rol_id) as total
                                FROM usuario usu
                                INNER JOIN rol rol ON rol.rol_id = usu.usu_rol_id
                                WHERE usu_estado = 1
                                GROUP BY rol_nombre");
        return $query->getResult();
    }

    public function graficabarra($emp_id){
        $query = $this->query("SELECT DATE_FORMAT(dat_fecha_reg, '%m') AS mes, COUNT(*) AS total 
                                FROM datos 
                                WHERE DATE_FORMAT(dat_fecha_reg, '%Y') = 2025 AND dat_origen = 2 AND dat_emp_id = $emp_id
                                GROUP BY mes ORDER BY mes;");
        return $query->getResult();
    }

}