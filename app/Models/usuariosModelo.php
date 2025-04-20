<?php
namespace App\Models;
use CodeIgniter\Model;
class usuariosModelo extends Model{
    protected $table = 'usuario';
    protected $primaryKey = 'usu_id';
    protected $allowedFields = ['usu_nombre', 'usu_apellido', 'usu_rol_id', 'usu_usuario', 'usu_correo', 'usu_clave', 'usu_estado', 'usu_genero', 'usu_foto'];

    public function getUsuarios(){
        $query = $this->query("SELECT * FROM usuario us
                        INNER JOIN rol r ON r.rol_id = us.usu_rol_id");
        return $query->getResult();
    }

    public function getUsuario($id){
        $query = $this->query("SELECT * FROM usuario us
                        INNER JOIN rol r ON r.rol_id = us.usu_rol_id
                        WHERE usu_id = $id");
        return $query->getRow();
    }

    public function usuarioLogin($usuario){
        $query = $this->query("SELECT * FROM usuario us
                        INNER JOIN rol r ON r.rol_id = us.usu_rol_id
                        WHERE TRIM(us.usu_usuario) = '$usuario'");
        return $query->getRow();
    }

    public function validarCorreo($correo){
        $query = $this->query("SELECT * FROM usuario WHERE usu_correo ='$correo'");
        return $query->getRow();
    }

    public function addUsuario($data){
        return $this->insert($data);
    }

    public function ubigeoDepartamento(){
        $query = $this->query("SELECT dpto FROM ubigeo GROUP BY dpto ORDER BY dpto ASC");
        return $query->getResult();
    }

    public function ubigeoProvincia($dpto){
        $query = $this->query("SELECT prov FROM ubigeo WHERE dpto='$dpto' GROUP BY prov ORDER BY prov ASC");
        return $query->getResult();
    }

    public function ubigeoDistrito($dpto,$prov){
        $query = $this->query("SELECT ubigeo1,distrito FROM ubigeo WHERE dpto='$dpto' AND prov='$prov' ORDER BY distrito ASC");
        return $query->getResult();
    }



    public function tipoUsuario($tipo){
        $query = $this->query("SELECT usu_id, per_nombres, per_apellidos
        FROM usuario us
        INNER JOIN persona per ON per_correoinstitucional = usu_usuario
        WHERE usu_rol_id = $tipo");
        return $query->getResult();
    }

    public function buscarusuario($correo){
        return $this->where("usu_usuario = '$correo'")->limit(1)->find();
    }

    public function getUsuarioCorreo($usu_email){
        $query = $this->query("SELECT * FROM usuario us
                        INNER JOIN rol r ON r.rol_id = us.usu_rol_id
                        WHERE us.usu_usuario ='$usu_email'");
        return $query->getRow();
    }

    public function usuariosModulos(){
        return $this->where('usu_estado = 1')->findAll();
    }

    public function usuariosId($email){
        return $this->where("usu_usuario = '$email' AND usu_estado = 1")->limit(1)->findAll();
    }

    public function updateUsuario($id, $data){
        return $this->update($id, $data);
    }

    public function updateClave($id, $data){
        return $this->update($id, $data);
    }

    public function ubigeo($ubigeo){
        $query = $this->query("SELECT * FROM ubigeo WHERE ubigeo1='$ubigeo'");
        return $query->getRow();
    }

}