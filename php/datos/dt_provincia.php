<?php
class dt_provincia extends toba_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id_provincia, nombre FROM provincia ORDER BY nombre";
		$ar = toba::db('libro_unco')->consultar($sql);
                for($i=0; $i< sizeof($ar); $i++){
                    $ar[$i]['nombre'] = utf8_decode($ar[$i]['nombre']);
                }
                return $ar;
	}
        
        function get_listado(){
            $sql = "SELECT id_provincia, nombre, imagen_ubicacion FROM provincia ORDER BY nombre";
            return toba::db('libro_unco')->consultar($sql);
                
        }

}

?>