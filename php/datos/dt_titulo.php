<?php
class dt_titulo extends toba_datos_tabla
{
	function get_descripciones($genero = null)
	{//print_r($genero);exit();
            if(!is_null($genero) && strcasecmp($genero, 'Masculino') ==0 )
                $sexo = 'false';
            else
                $sexo = 'true';
            $sql = "SELECT id_titulo, nombre as titulo FROM titulo "
                    . "WHERE genero = $sexo"
                    . " ORDER BY titulo";
            return toba::db('libro_unco')->consultar($sql);
                
	}

}

?>