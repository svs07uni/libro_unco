<?php
class dt_materia extends toba_datos_tabla
{
	function get_listado($filtro=array())
	{
		$where = array();
                if (isset($filtro['nombre'])) {
			$where[] = "nombre ILIKE ".quote("%{$filtro['nombre']['valor']}%");
		}
		if (isset($filtro['codigo_siu'])) {
			$where[] = "codigo_siu = ".$filtro['codigo_siu']['valor'];
		}
                if (isset($filtro['anio_cursado'])) {
			$where[] = "anio_cursado = ".$filtro['anio_cursado']['valor'];
		}
                if (isset($filtro['periodo'])) {
			$where[] = "periodo ILIKE ".quote("%{$filtro['periodo']['valor']}%");
		}
                if (isset($filtro['optativa'])) {
			$where[] = "optativa = ".$filtro['optativa']['valor'];
		}
		if (isset($filtro['id_plan'])) {
			$where[] = "t_m.id_plan = ".$filtro['id_plan']['valor'];
		}
                
		$sql = "SELECT
			t_m.id_materia,
                        t_m.nombre,
                        t_m.codigo_siu,
                        t_m.anio_cursado,
                        t_p.nombre as periodo,
                        t_p.id_periodo,
                        t_m.orden,
                        t_m.optativa,
                        t_m.id_plan,
                        t_mo.nombre as modulo
		FROM
			materia as t_m	
                LEFT OUTER JOIN periodo as t_p 
                ON (t_m.id_periodo = t_p.id_periodo)
                LEFT OUTER JOIN se_encuentra as t_se
                ON (t_m.id_materia = t_se.id_materia)
                LEFT OUTER JOIN modulo as t_mo
                ON (t_mo.id_modulo = t_se.id_modulo)
                ";
		if(count($where)>0){
                    $sql = sql_concatenar_where($sql, $where);
                }
                $sql = $sql." ORDER BY nombre";
                return toba::db('libro_unco')->consultar($sql);
                
	}

}

?>
