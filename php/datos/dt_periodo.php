<?php
class dt_periodo extends toba_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_periodo, nombre FROM periodo ORDER BY nombre";
		return toba::db('libro_unco')->consultar($sql);
                
	}

	function get_listado($periodo = null)
	{
		$sql = "SELECT
			t_p.nombre,
			t_p.id_periodo
		FROM
			periodo as t_p";
                if(!is_null($periodo))
                    $sql .= " WHERE periodo = '".$periodo."'";
		$sql .= "ORDER BY nombre";
                
		return toba::db('libro_unco')->consultar($sql);
	}

}
?>