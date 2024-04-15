<?php
namespace App\Models;
use CodeIgniter\Model;
class rolesModelo extends Model{
    protected $table = 'rol';
    protected $primaryKey = 'rol_id';
    protected $allowedFields = ['rol_nombre', 'rol_descripcion', 'rol_estado'];

    public function readRoles(){
        return $this->where('rol_estado = 1')->findAll();
    }

    public function readRol($id){
        return $this->find($id);
    }

    public function addRoles($data){
        return $this->insert($data);
    }

    public function updateRoles($id, $data){
        return $this->update($id, $data);
    }

    public function deleteRoles($id){
        return $this->delete($id);
    }

}
