<?php
class dt_unidad_academica extends toba_datos_tabla
{
	function get_listado($filtro = array())
	{
            $where = array();
            if(isset($filtro['sigla'])){
                $where[] = 'sigla ILIKE '.quote("%{$filtro['sigla']['valor']}%");
            }
            if(isset($filtro['nombre'])){
                $where[] = 'nombre ILIKE '.quote("%{$filtro['nombre']['valor']}%");
            }
		$sql = "SELECT
			t_ua.sigla,
			t_ua.correo_1,
			t_ua.correo_2,
			t_ua.correo_3,
			t_ua.pagina,
			t_ua.nombre,
			t_ua.orden,
			t_ua.interno_1,
			t_ua.interno_2,
			t_ua.interno_3,
			t_ua.imagen
		FROM
			unidad_academica as t_ua";
                if(count($where)>0){
                    $sql = sql_concatenar_where($sql, $where);
                }
                $sql = $sql." ORDER BY nombre";
                return toba::db('libro_unco')->consultar($sql);
                
	}

	function get_descripciones()
	{
		$sql = "SELECT sigla, nombre FROM unidad_academica ORDER BY nombre";
		return toba::db('libro_unco')->consultar($sql);
	}

}
?>