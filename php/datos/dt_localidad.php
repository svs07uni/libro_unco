<?php
class dt_localidad extends toba_datos_tabla
{
	function get_descripciones($id_provincia=null)
	{
            $where = "";
            if(!is_null($id_provincia))
                $where = " WHERE id_provincia = $id_provincia";
            $sql = "SELECT id_localidad, nombre FROM localidad $where ORDER BY nombre";
            $ar = toba::db('libro_unco')->consultar($sql);
            return $ar;
	}
        
	function get_listado($filtro = null)
	{
            $where = "";
            if(isset($filtro['id_localidad']['valor']))
                $where = " WHERE id_localidad = ".$filtro['id_localidad']['valor'];
            $sql = "SELECT
			t_l.id_localidad,
			t_l.nombre,
			t_l.cp,
			t_l.caracteristica,
			t_p.nombre as id_provincia_nombre,
                        t_p.id_provincia
		FROM
			localidad as t_l	
                        LEFT OUTER JOIN provincia as t_p ON (t_l.id_provincia = t_p.id_provincia)
                        $where
		ORDER BY nombre ";
		return toba::db('libro_unco')->consultar($sql);
	}


}
?>