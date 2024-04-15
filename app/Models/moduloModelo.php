<?php
namespace App\Models;
use CodeIgniter\Model;
class moduloModelo extends Model{
    protected $table = 'menu';
    protected $primaryKey = 'men_id';
    protected $allowedFields = ['men_nombre', 'men_descripcion', 'men_url', 'men_padre', 'men_icono', 'men_orden', 'men_estado'];

    public function readModulos(){
        return $this->where('men_estado in (0,1)')->findAll();
    }

    public function readModulo($id){
        return $this->find($id);
    }

    public function readPadre(){
        return $this->where('men_padre = 1 AND men_estado = 1')->orderby('men_orden')->findAll();
    }

    public function addModulo($data){
        return $this->insert($data);
    }

    public function updateModulo($id, $data){
        return $this->update($id, $data);
    }

    public function deleteModulo($id){
        return $this->delete($id);
    }

    public function menPadre($men_id){
        return $this->where("men_estado=1 AND men_padre =  $men_id")->orderby('men_orden')->findAll();
    }

    public function permiso($rol_id, $men_id){
        $query = $this->query("SELECT * FROM permiso WHERE pso_rol_id = $rol_id AND pso_men_id = $men_id");
        return $query->getRow();
    }

    public function accede($url,$rol_id){
        $query = $this->query("SELECT *
        FROM menu men
        INNER JOIN permiso per ON per.pso_men_id=men.men_id
        WHERE men_url='$url' AND pso_rol_id = $rol_id");
        return $query->getRow();
    }
}
