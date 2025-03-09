<?php
namespace App\Models;
use CodeIgniter\Model;
class peyorativosModelo extends Model{
    protected $table = 'peyorativo';
    protected $primaryKey = 'pey_id';
    protected $allowedFields = ['pey_nombre', 'pey_descripcion', 'pey_emp_id'];

    public function reads($emp_id){
        return $this->where("pey_emp_id = $emp_id")->orderBy('pey_id DESC')->findAll();
    }

    public function validarPeyorativo($emp_id)
    {
        return $this->query("SELECT pey_nombre FROM peyorativo WHERE pey_estado = 1 AND pey_emp_id = $emp_id")
                    ->getResult();
    }

    public function readPeyorativo($id){
        return $this->find($id);
    }

    public function updatePeyorativo($id, $data){
        return $this->update($id, $data);
    }

}