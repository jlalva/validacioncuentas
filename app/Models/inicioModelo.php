<?php
namespace App\Models;
use CodeIgniter\Model;
class inicioModelo extends Model{
    protected $table = 'encuesta';
    protected $primaryKey = 'enc_id';
    protected $allowedFields = [];

    public function estudiantes($periodo){
        $query = $this->query("SELECT COUNT(*) as total FROM persona WHERE per_rol_id=4 AND per_periodo= '$periodo'");
        return $query->getRow();
    }

    public function docentes($periodo){
        $query = $this->query("SELECT COUNT(*) as total FROM persona WHERE per_rol_id=3 AND per_periodo= '$periodo'");
        return $query->getRow();
    }

    public function usuarios($idrol){
        $query = $this->query("SELECT COUNT(*) as total FROM usuario WHERE usu_estado = 1 AND usu_rol_id = $idrol");
        return $query->getRow();
    }

    public function encuestas($idperiodo,$estado){
        $query = $this->query("SELECT COUNT(*) AS total
        FROM condicion_estudiante ces
        INNER JOIN encuesta enc ON enc.enc_id=ces.ces_enc_id
        WHERE enc.enc_ani_id=$idperiodo AND ces.ces_estado = $estado");
        return $query->getRow();
    }

    public function docentesencuestados($idperiodo,$estado){
        $query = $this->query("SELECT COUNT(*) AS total
        FROM condicion_docente cdo
        INNER JOIN encuesta enc ON enc.enc_id=cdo.cdo_enc_id
        WHERE enc.enc_ani_id=$idperiodo AND cdo.cdo_estado = $estado");
        return $query->getRow();
    }

    public function cursos($periodo){
        $query = $this->query("SELECT COUNT(*) as total FROM curso cur WHERE cur_periodo = '$periodo'");
        return $query->getRow();
    }

    public function barras($periodo){
        $query = $this->query("SELECT esc.esc_nombre,COUNT(est_id) AS total
        FROM estudiante est
        INNER JOIN escuela esc ON esc.esc_id=est.est_esc_id AND esc.esc_periodo=est.est_periodo
        INNER JOIN condicion_estudiante ces ON ces.ces_est_per_codigo=est.est_per_codigo AND ces.ces_periodo=est.est_periodo
        INNER JOIN encuesta enc ON enc.enc_id=ces.ces_enc_id
        WHERE enc.enc_ani_id= $periodo AND ces.ces_estado = 2 GROUP BY esc.esc_nombre");
        return $query->getResult();
    }
}