<?php
namespace App\Models;
use CodeIgniter\Model;
class dominioModelo extends Model{
    protected $table = 'dominio';
    protected $primaryKey = 'dom_id';
    protected $allowedFields = ['dom_nombre', 'dom_descripcion', 'dom_estado'];

    public function reads(){
        return $this->orderBy('dom_id DESC')->findAll();
    }

    public function readDominio($id){
        return $this->find($id);
    }

    public function updateDominio($id, $data){
        return $this->update($id, $data);
    }

}