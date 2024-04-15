<?php
namespace App\Models;
use CodeIgniter\Model;
class permisoModelo extends Model{
    protected $table = 'permiso';
    protected $primaryKey = 'pso_id';
    protected $allowedFields = ['pso_rol_id', 'pso_men_id', 'pso_estado', 'pso_ver', 'pso_agregar', 'pso_editar', 'pso_eliminar'];

    public function addModulo($data){
        return $this->insert($data);
    }

    public function getPermiso($rol_id,$men_id){
        return $this->where('pso_estado=1 AND pso_men_id = '.$men_id.' AND pso_rol_id = '.$rol_id)->find();
    }

    public function updatePermiso($id, $data){
        return $this->update($id, $data);
    }

    public function deletePermiso($id){
        return $this->delete($id);
    }
}
