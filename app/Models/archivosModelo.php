<?php
namespace App\Models;
use CodeIgniter\Model;
class archivosModelo extends Model{
    protected $table = 'archivos';
    protected $primaryKey = 'arc_id';
    protected $allowedFields = ['arc_nombre', 'arc_ruta', 'arc_total', 'arc_subido', 'arc_usu_id', 'arc_fecha_reg','arc_estado','arc_origen',
                                'arc_tipo_archivo', 'arc_descripcion', 'arc_tipo_persona', 'arc_tiempo','arc_emp_id'];

    public function registros($origen, $emp_id){
        $query = $this->query("SELECT arc_id,arc_nombre,arc_ruta,arc_total,arc_subido,usu_nombre,usu_apellido,arc_fecha_reg,tip_nombre, arc_tiempo
        FROM archivos arc
        INNER JOIN usuario usu ON usu.usu_id = arc.arc_usu_id
        LEFT JOIN tipopersona tip ON tip.tip_id = arc.arc_tipo_persona
        WHERE arc_origen = $origen AND arc_subido > 0 AND arc_emp_id = $emp_id
        ORDER BY arc_id DESC");
        return $query->getResult();
    }

    public function todo($emp_id){
        $query = $this->query("SELECT arc_id,arc_nombre,arc_ruta,arc_total,arc_subido,usu_nombre,usu_apellido,arc_fecha_reg, arc_tiempo, arc_origen
        FROM archivos arc
        INNER JOIN usuario usu ON usu.usu_id = arc.arc_usu_id
        WHERE arc_subido > 0 AND arc_emp_id = $emp_id
        ORDER BY arc_id DESC");
        return $query->getResult();
    }

    public function filtrado($emp_id,$tipo,$fecha_inicio,$fecha_fin){
        $and = '';
        if($tipo > 0){
            $and .=" AND arc_origen = $tipo";
        }
        if($fecha_inicio !='' && $fecha_fin != ''){
            $and .=" AND DATE_FORMAT(arc_fecha_reg, '%Y-%m-%d') BETWEEN '$fecha_inicio' AND '$fecha_fin'";
        }
        $query = $this->query("SELECT arc_id,arc_nombre,arc_ruta,arc_total,arc_subido,usu_nombre,usu_apellido,arc_fecha_reg, arc_tiempo, arc_origen
        FROM archivos arc
        INNER JOIN usuario usu ON usu.usu_id = arc.arc_usu_id
        WHERE arc_subido > 0 AND arc_emp_id = $emp_id $and
        ORDER BY arc_id DESC");
        return $query->getResult();
    }

    public function archivo($id){
        $query = $this->query("SELECT *
        FROM archivos arc
        INNER JOIN usuario usu ON usu.usu_id = arc.arc_usu_id
        LEFT JOIN tipopersona tip ON tip.tip_id = arc.arc_tipo_persona
        WHERE arc_id = $id
        ORDER BY arc_id DESC");
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