<?php
namespace App\Models;
use CodeIgniter\Model;
class dominioModelo extends Model{
    protected $table = 'dominio';
    protected $primaryKey = 'dom_id';
    protected $allowedFields = ['dom_nombre', 'dom_descripcion', 'dom_estado','dom_emp_id'];

    public function reads($emp_id){
        return $this->where("dom_emp_id = $emp_id")->orderBy('dom_id DESC')->findAll();
    }

    public function readDominio($id){
        return $this->find($id);
    }

    public function updateDominio($id, $data){
        return $this->update($id, $data);
    }

}