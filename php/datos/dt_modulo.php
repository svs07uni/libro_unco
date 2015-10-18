<?php
class dt_modulo extends toba_datos_tabla
{
    function listar_modulos_distinto_de($id_plan, $id_modulo){
        $and = "";
            if(isset($id_modulo))//Se seleccionó un modulo
                $and = " AND id_modulo <> ".$id_modulo;
            $sql = "SELECT id_modulo, nombre FROM modulo "
                . " WHERE id_plan = ". $id_plan
                .$and;
            return toba::db('libro_unco')->consultar($sql);
    }
    function eliminar_modulo($id_modulo){
        $sql = "delete from modulo where id_modulo = ".$id_modulo;
        toba::db('libro_unco')->consultar($sql);
    }
    function get_listado($filtro=array())
	{
		$where = array();
                if (isset($filtro['nombre'])) {
			$where[] = "t_m.nombre ILIKE ".quote("%{$filtro['nombre']['valor']}%");
		}
		if (isset($filtro['tipo_modulo'])) {
			$where[] = "tipo_modulo ILIKE ".quote("%{$filtro['tipo_modulo']['valor']}%");
		}
                if (isset($filtro['modulo_padre'])) {
			$where[] = "t_m.modulo_padre ILIKE ".quote("%{$filtro['modulo_padre']['valor']}%");
		}
                if (isset($filtro['id_plan'])) {
			$where[] = "t_m.id_plan = ".$filtro['id_plan']['valor'];
		}
		if (isset($filtro['id_modulo'])) {
			$where[] = "t_m.id_modulo = ".$filtro['id_modulo']['valor'];
		}
		$sql = "SELECT
			t_m.id_modulo,
                        t_m.nombre,
                        t_m.id_tipo_modulo,
                        t_mp.nombre as modulo_padre,
                        t_m.id_plan,
                        t_tm.nombre as tipo_modulo
		FROM
			modulo as t_m	
                LEFT OUTER JOIN tipo_modulo as t_tm 
                ON (t_m.id_tipo_modulo = t_tm.id_tipo_modulo)
                LEFT OUTER JOIN modulo as t_mp
                ON (t_mp.id_modulo = t_m.id_modulo_padre)
                ";
		if(count($where)>0){
                    $sql = sql_concatenar_where($sql, $where);
                }
                $sql = $sql." ORDER BY id_tipo_modulo";
                return toba::db('libro_unco')->consultar($sql);
                
	}
        
        function get_modulo_de_materia($id_materia){
            $sql = "SELECT
			t_m.nombre,
                        t_tm.nombre as tipo_modulo
		FROM
			modulo as t_m	
                LEFT OUTER JOIN tipo_modulo as t_tm 
                ON (t_m.id_tipo_modulo = t_tm.id_tipo_modulo)
                LEFT OUTER JOIN se_encuentra as t_se
                ON (t_se.id_modulo = t_m.id_modulo)
                WHERE t_se.id_materia = $id_materia";
            return toba::db('libro_unco')->consultar($sql);
        }

}

?>