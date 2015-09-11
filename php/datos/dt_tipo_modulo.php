<?php
class dt_tipo_modulo extends toba_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_tipo_modulo, nombre FROM tipo_modulo ORDER BY nombre";
		return toba::db('libro_unco')->consultar($sql);
                
	}

}

?>