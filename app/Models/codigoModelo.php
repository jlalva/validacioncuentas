<?php
namespace App\Models;
use CodeIgniter\Model;
class codigoModelo extends Model{
    protected $table = 'codigo';
    protected $primaryKey = 'cod_id';
    protected $allowedFields = ['cod_email', 'cod_codigo', 'cod_fecha', 'cod_estado'];

    public function addCodigo($data){
        return $this->insert($data);
    }

    public function validaCodigo($email,$codigo){
        return $this->where("cod_email = '$email' AND cod_codigo = $codigo AND cod_estado=1")->limit(1)->find();
    }

}