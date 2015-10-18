<?php
class dt_area extends toba_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_area, nombre FROM area ORDER BY nombre";
		return toba::db('libro_unco')->consultar($sql);
                
	}
        
        function get_listado(){
            $sql = "SELECT id_area, nombre FROM area ORDER BY nombre";
            return toba::db('libro_unco')->consultar($sql);
                
        }

}

?>