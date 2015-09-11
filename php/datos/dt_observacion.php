<?php
class dt_observacion extends toba_datos_tabla
{
	function get_listado($filtro=array())
	{
		$where = array();
                $from = '';
                if (isset($filtro['descripcion'])) {
			$where[] = "descripcion ILIKE ".quote("%{$filtro['descripcion']['valor']}%");
		}
		if (isset($filtro['id_plan'])) {
                        $from = 'obs_plan ';
			$where[] = 'id_entidad = '.$filtro['id_plan']['valor'];
		}
                if (isset($filtro['id_materia'])) {
                        $from = 'obs_mat ';
			$where[] = "id_entidad = ".$filtro['id_materia']['valor'];
		}
                if (isset($filtro['id_modulo'])) {
                        $from = 'obs_mod ';
			$where[] = "id_entidad = ".$filtro['id_modulo']['valor'];
		}
		$sql = "SELECT
			id_observacion,
                        descripcion,
                        id_entidad
		FROM
			$from ";
		if(count($where)>0){
                    $sql = sql_concatenar_where($sql, $where);
                }
                $sql = $sql." ORDER BY id_observacion";
                return toba::db('libro_unco')->consultar($sql);
                
	}

}

?>