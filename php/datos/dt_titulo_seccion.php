<?php
class dt_titulo_seccion extends toba_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_titulo_seccion, titulo FROM titulo_seccion ORDER BY titulo";
		return toba::db('libro_unco')->consultar($sql);
	}

}
?>