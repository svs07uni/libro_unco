<?php
class dt_modulo extends toba_datos_tabla
{
	function get_listado($filtro=array())
	{
		$where = array();
                if (isset($filtro['nombre'])) {
			$where[] = "nombre ILIKE ".quote("%{$filtro['nombre']['valor']}%");
		}
		if (isset($filtro['tipo_modulo'])) {
			$where[] = "tipo_modulo ILIKE ".quote("%{$filtro['tipo_modulo']['valor']}%");
		}
                if (isset($filtro['modulo_padre'])) {
			$where[] = "modulo_padre ILIKE ".quote("%{$filtro['modulo_padre']['valor']}%");
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

}

?>