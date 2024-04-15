<?php
namespace App\Models;
use CodeIgniter\Model;
class configuracionModelo extends Model{
    protected $table = 'configuracion';
    protected $primaryKey = 'con_id';
    protected $allowedFields = ['con_per_id', 'con_tipo', 'con_mensaje', 'con_fecharegistro', 'con_estado'];

    public function reads(){
        $query = $this->query("SELECT * FROM configuracion con
                        INNER JOIN anio ani ON ani.ani_id = con.con_per_id
                        WHERE con.con_estado in (0, 1)");
        return $query->getResult();
    }

    public function tipomensaje($tipo, $ani_id){
        $query = $this->query("SELECT * FROM configuracion con
                        INNER JOIN anio ani ON ani.ani_id = con.con_per_id
                        WHERE con.con_tipo = $tipo AND ani.ani_codigo = $ani_id AND con.con_estado = 1");
        return $query->getRow();
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

    public function del($id){
        return $this->delete($id);
    }

}
