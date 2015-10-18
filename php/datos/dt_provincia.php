<?php
class dt_provincia extends toba_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_provincia, nombre FROM provincia ORDER BY nombre";
		return toba::db('libro_unco')->consultar($sql);
	}

        
	function get_listado()
	{
		$sql = "SELECT
			t_p.id_provincia,
			t_p.nombre,
			t_p.imagen_ubicacion
		FROM
			provincia as t_p
		ORDER BY nombre";
		return toba::db('libro_unco')->consultar($sql);
	}


}
?>