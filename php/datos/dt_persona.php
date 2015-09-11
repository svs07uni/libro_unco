<?php
class dt_persona extends toba_datos_tabla
{
        function get_listado($filtro = array())
	{
            $where = array();
            if(isset($filtro['nombre'])){
                $where[] = 'nombre ILIKE '.quote("%{$filtro['nombre'][valor]}%");
            }
            if(isset($filtro['apellido'])){
                $where[] = 'apellido ILIKE '.quote("%{$filtro['apellido']['valor']}%");
            }
            if(isset($filtro['nro_doc'])){
                $where[] = 'nro_doc ILIKE '.quote("%{$filtro['nro_doc']['valor']}%");
            }
            if(isset($filtro['tipo_doc'])){
                $where[] = 'tipo_doc = '.$filtro['tipo_doc']['valor'];
            }
            if(isset($filtro['genero'])){
                $where[] = 'genero = '.$filtro['genero']['valor'];
            }
            if(isset($filtro['id_sector'])){
                $where[] = 'id_sector = '.$filtro['id_sector']['valor'];
            }
		$sql = "SELECT
			t_t.nombre as titulo,
			t_p.tipo_doc,
			t_p.genero,
			t_s.nombre as id_sector_nombre,
			t_p.nro_doc,
			t_p.nombre,
			t_p.apellido,
			t_p.correo
		FROM
			persona as t_p	LEFT OUTER JOIN titulo as t_t ON (t_p.id_titulo = t_t.id_titulo)
			LEFT OUTER JOIN sector as t_s ON (t_p.id_sector = t_s.id_sector)";
                if(count($where)>0){
                    $sql = sql_concatenar_where($sql, $where);
                }
                $sql = $sql." ORDER BY nombre";
                return toba::db('libro_unco')->consultar($sql);
                
	}
    
}

?>