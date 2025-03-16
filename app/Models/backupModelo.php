<?php
namespace App\Models;
use CodeIgniter\Model;
class backupModelo extends Model{
    protected $table = 'backup';
    protected $primaryKey = 'bac_id';
    protected $allowedFields = ['bac_nombre', 'bac_tamanio', 'bac_fecha', 'bac_usu_id', 'bac_emp_id', 'bac_estado'];

    public function reads(){
        $query = $this->query("SELECT bac_id,bac_nombre,bac_tamanio,DATE_FORMAT(bac_fecha,'%d-%m-%Y %H:%i') as fecha,usu_usuario,emp_razonsocial
        FROM `backup` bac 
        INNER JOIN usuario usu ON usu.usu_id=bac.bac_usu_id
        INNER JOIN empresa emp ON emp.emp_id = bac.bac_emp_id
        WHERE bac_estado = 1");
        return $query->getResult();
    }

    public function tablas(){
        $query = $this->query("SELECT table_name AS tabla FROM information_schema.tables WHERE table_schema = '".database."'");
        return $query->getResult();
    }

    public function borrarTabla($tabla){
        $this->db->tableExists($tabla);
        $this->db->query("DROP TABLE $tabla");
    }

    public function add($data){
        return $this->insert($data);
    }

    public function updateBackup($id, $data){
        return $this->update($id, $data);
    }
}