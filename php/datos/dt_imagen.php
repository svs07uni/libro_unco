<?php
class dt_imagen extends toba_datos_tabla
{
	function get_listado()
	{
		$sql = "SELECT
			t_i.recurso,
			t_i.id
		FROM
			imagen as t_i";
		return toba::db('libro_unco')->consultar($sql);
	}

}

?>