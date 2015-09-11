<?php
class dt_seccion extends toba_datos_tabla
{
	function get_listado($filtro=array())
	{
		$where = array();
                $from = '';
                if (isset($filtro['contenido'])) {
			$where[] = "contenido ILIKE ".quote("%{$filtro['contenido']['valor']}%");
		}
		if (isset($filtro['id_titulo'])) {
			$where[] = "id_titulo = ".quote($filtro['id_titulo']['valor']);
		}
		if (isset($filtro['id_seccion'])) {
			$where[] = "id_seccion = ".quote($filtro['id_seccion']['valor']);
		}
                if (isset($filtro['id_sector'])) {
                        $from = 'JOIN seccion_sector as t_ss ON t_s.id_seccion = t_ss.id_seccion ';
			$where[] = "id_sector = ".quote($filtro['id_sector']['valor']);
		}
                if (isset($filtro['id_plan'])) {
                        $from = 'seccion_plan as t_sp ON t_s.id_seccion = t_sp.id_seccion ';
			$where[] = "JOIN id_plan = ".quote($filtro['id_plan']['valor']);
		}
		$sql = "SELECT
			t_s.contenido,
			t_ts.titulo as titulo,
			t_s.con_extra,
			t_s.id_seccion
		FROM
			seccion as t_s	
                LEFT OUTER JOIN titulo_seccion as t_ts ON (t_s.id_titulo = t_ts.id_titulo_seccion)
                         $from ";
		if(count($where)>0){
                    $sql = sql_concatenar_where($sql, $where);
                }
                $sql = $sql." ORDER BY id_seccion";
                return toba::db('libro_unco')->consultar($sql);
                
	}

}

?>