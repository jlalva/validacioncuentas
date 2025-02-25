<?php
namespace App\Models;
use CodeIgniter\Model;
class peyorativosModelo extends Model{
    protected $table = 'peyorativo';
    protected $primaryKey = 'pey_id';
    protected $allowedFields = ['pey_nombre', 'pey_descripcion', 'pey_estado'];

    public function reads(){
        return $this->orderBy('pey_id DESC')->findAll();
    }

    public function validarPeyorativo()
    {
        return $this->query("SELECT pey_nombre FROM peyorativo WHERE pey_estado = 1")
                    ->getResult();
    }

    public function readPeyorativo($id){
        return $this->find($id);
    }

    public function updatePeyorativo($id, $data){
        return $this->update($id, $data);
    }

}