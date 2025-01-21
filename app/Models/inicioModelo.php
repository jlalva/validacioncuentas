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
        $query = $this->query("SELECT COUNT(*) as total FROM datos");
        return $query->getRow();
    }

}