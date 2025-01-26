<?php
namespace App\Models;
use CodeIgniter\Model;
class tipopersonaModelo extends Model{
    protected $table = 'tipopersona';
    protected $primaryKey = 'tip_id';
    protected $allowedFields = ['tip_nombre', 'tip_descripcion', 'tip_estado'];

    public function reads(){
        return $this->orderBy('tip_id DESC')->findAll();
    }

    public function readTipo($id){
        return $this->find($id);
    }

    public function updateTipo($id, $data){
        return $this->update($id, $data);
    }

}