<?php
class dt_sector extends toba_datos_tabla
{
	function get_listado($filtro = array())
	{
            $where = array();
            if(isset($filtro['nombre'])){
                $where[] = 't_s.nombre ILIKE '.quote("%{$filtro['nombre'][valor]}%");
            }
            if(isset($filtro['nombre_persona'])){
                $where[] = 'nombre_persona ILIKE '.quote("%{$filtro['nombre_persona']['valor']}%");
            }
            if(isset($filtro['apellido_persona'])){
                $where[] = 'apellido_persona ILIKE '.quote("%{$filtro['apellido_persona']['valor']}%");
            }
            if(isset($filtro['sigla'])){
                $where[] = 'sigla ILIKE '.quote("%{$filtro['sigla']['valor']}%");
            }
            if(isset($filtro['id_sector'])){
                $where[] = 'id_sector = '.$filtro['id_sector']['valor'];
            }
		$sql = "SELECT
			t_s.id_sector,
			t_s.nombre,
			t_s.id_unidad_academica,
			t_s.correo,
			t_s.telefono,
			t_s.fax,
			t_s.orden,
			t_s.interno_1,
			t_s.interno_2,
			t_s.nombre_masc,
			t_s.nombre_fem
		FROM
			sector as t_s";
                if(count($where)>0){
                    $sql = sql_concatenar_where($sql, $where);
                }
                $sql = $sql." ORDER BY nombre";
                return toba::db('libro_unco')->consultar($sql);
                
	}
        
        function get_listado_con_persona($filtro = array())
	{
            $where = array();
            if(isset($filtro['nombre'])){
                $where[] = 't_s.nombre ILIKE '.quote("%{$filtro['nombre']['valor']}%");
            }
            if(isset($filtro['nombre_persona'])){
                $where[] = 't_p.nombre ILIKE '.quote("%{$filtro['nombre_persona']['valor']}%");
            }
            if(isset($filtro['apellido_persona'])){
                $where[] = 't_p.apellido ILIKE '.quote("%{$filtro['apellido_persona']['valor']}%");
            }
		$sql = "SELECT
			t_s.id_sector,
			t_s.nombre,
			t_s.id_unidad_academica,
			t_s.correo,
			t_s.telefono,
			t_s.fax,
			t_s.orden,
			t_s.interno_1,
			t_s.interno_2,
			t_s.nombre_masc,
			t_s.nombre_fem,
                        t_p.nro_doc as nro_doc_persona,
                        t_p.tipo_doc as tipo_doc_persona,
                        t_p.nombre as nombre_persona,
                        t_p.apellido as apellido_persona
		FROM
			sector as t_s 
                LEFT JOIN persona as t_p
                ON t_s.id_sector = t_p.id_sector";
                if(count($where)>0){
                    $sql = sql_concatenar_where($sql, $where);
                }
                $sql = $sql." ORDER BY nombre";
                return toba::db('libro_unco')->consultar($sql);
                
	}
    
        function get_descripciones()
	{
		$sql = "SELECT id_sector, nombre FROM sector ORDER BY nombre";
		return toba::db('libro_unco')->consultar($sql);
	}

}

?>