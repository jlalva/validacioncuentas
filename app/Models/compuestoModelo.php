<?php
namespace App\Models;
use CodeIgniter\Model;
class compuestoModelo extends Model{
    protected $table = 'compuesto';
    protected $primaryKey = 'com_id';
    protected $allowedFields = ['com_nombre', 'com_descripcion', 'com_emp_id', 'com_estado'];

    public function reads($emp_id){
        return $this->where("com_emp_id = $emp_id")->orderBy('com_id DESC')->findAll();
    }

    public function validarCompuesto($emp_id)
    {
        return $this->query("SELECT com_nombre FROM compuesto WHERE com_estado = 1 AND com_emp_id = $emp_id")
                    ->getResult();
    }

    public function validarDuplicado($emp_id, $com_nombre)
    {
        return $this->query("SELECT com_nombre FROM compuesto WHERE com_estado = 1 AND com_emp_id = $emp_id AND com_nombre = '$com_nombre'")
                    ->getRow();
    }

    public function readCompuesto($id){
        return $this->find($id);
    }

    public function updateCompuesto($id, $data){
        return $this->update($id, $data);
    }

}