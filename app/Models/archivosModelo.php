<?php
namespace App\Models;
use CodeIgniter\Model;
class archivosModelo extends Model{
    protected $table = 'archivos';
    protected $primaryKey = 'arc_id';
    protected $allowedFields = ['arc_nombre', 'arc_ruta', 'arc_total', 'arc_subido', 'arc_usu_id', 'arc_fecha_reg','arc_estado','arc_origen',
                                'arc_tipo_archivo', 'arc_descripcion', 'arc_tipo_persona'];

    public function registros($origen){
        $query = $this->query("SELECT arc_id,arc_nombre,arc_ruta,arc_total,arc_subido,usu_nombre,arc_fecha_reg 
        FROM archivos arc
        INNER JOIN usuario usu ON usu.usu_id = arc.arc_usu_id
        WHERE arc_origen = $origen
        ORDER BY arc_id DESC");
        return $query->getResult();
    }

    public function archivo($id){
        $query = $this->query("SELECT *  FROM archivos WHERE arc_id = $id");
        return $query->getRow();
    }

    public function add($data){
        return $this->insert($data);
    }

    public function upd($id, $data){
        return $this->update($id, $data);
    }

    public function deleteArchivo($id){
        return $this->delete($id);
    }
}