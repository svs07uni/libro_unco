<?php
class dt_seccion extends toba_datos_tabla
{
    function eliminar_seccion($id_seccion){
        $sql = "delete from seccion where id_seccion = ".$id_seccion;
        toba::db('libro_unco')->consultar($sql);
    }
    
    function get_listado($filtro=array())
	{
		$where = array();
                $from = '';
                if (isset($filtro['contenido'])) {
			$where[] = "contenido ILIKE ".quote("%{$filtro['contenido']['valor']}%");
		}
                if (isset($filtro['titulo'])) {
			$where[] = "titulo ILIKE ".quote("%{$filtro['titulo']['valor']}%");
		}
		if (isset($filtro['id_titulo'])) {
			$where[] = "id_titulo = ".$filtro['id_titulo']['valor'];
		}
		if (isset($filtro['id_seccion'])) {
			$where[] = "id_seccion = ".$filtro['id_seccion']['valor'];
		}
                if (isset($filtro['id_sector'])) {
                        $where[] = "id_sector = ".$filtro['id_sector']['valor'];
		}
                if (isset($filtro['id_plan'])) {
                       $where[] = "id_plan = ".$filtro['id_plan']['valor'];
		}
		$sql = "SELECT
			t_s.contenido,
			t_ts.titulo as titulo,
                        t_ts.id_titulo_seccion,
			t_s.con_extra,
			t_s.id_seccion,
                        t_s.id_sector,
                        t_s.id_plan
		FROM
			seccion as t_s	
                LEFT OUTER JOIN titulo_seccion as t_ts ON (t_s.id_titulo = t_ts.id_titulo_seccion)
                         ";
		if(count($where)>0){
                    $sql = sql_concatenar_where($sql, $where);
                }
                $sql = $sql." ORDER BY id_seccion";
                return toba::db('libro_unco')->consultar($sql);
                
	}

}

?>