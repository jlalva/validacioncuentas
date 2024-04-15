<?php
namespace App\Models;
use CodeIgniter\Model;
class empresaModelo extends Model{
    protected $table = 'empresa';
    protected $primaryKey = 'emp_id';
    protected $allowedFields = ['emp_razonsocial', 'emp_ruc', 'emp_siglas', 'emp_ubi_id', 'emp_direccion', 'emp_telefono',
    'emp_fechafundacion', 'emp_logo', 'emp_imgmarcaagua', 'emp_imgfondo', 'emp_banner', 'emp_fecharegistro', 'emp_sitioweb', 'emp_facebook', 'emp_youtube',
    'emp_instagram', 'emp_twitter', 'emp_correo','emp_descripcion', 'emp_estado'];

    public function reads(){
        return $this->orderBy('emp_id DESC')->findAll();
    }

    public function read($id){
        return $this->find($id);
    }

    public function add($data){
        return $this->insert($data);
    }

    public function upd($id, $data){
        return $this->update($id, $data);
    }

    public function cerrar(){
        $sql = "UPDATE empresa SET emp_estado = ?";
        return $this->db->query($sql, array(0));
    }

    public function datosEmpresa(){
        return $this->where('emp_estado', 1)->first();
    }

    public function ubigeo($ubigeo){
        $query = $this->query("SELECT * FROM ubigeo WHERE ubigeo1 = '$ubigeo'");
        return $query->getRow();
    }
}