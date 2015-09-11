<?php
class dt_localidad extends toba_datos_tabla
{
	function get_descripciones($provincia = null)
	{
            $where = "";
            if(!is_null($provincia))
                $where = " WHERE id_provincia = $provincia";
            $sql = "SELECT id_localidad, nombre FROM localidad $where ORDER BY nombre";
            return toba::db('libro_unco')->consultar($sql);
                
	}
        
        function get_listado(){
            $sql = "SELECT id_localidad, nombre, cp, caracteristica, id_provincia FROM localidad ORDER BY nombre";
            return toba::db('libro_unco')->consultar($sql);
                
        }

}

?>