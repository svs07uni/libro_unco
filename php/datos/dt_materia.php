<?php
class dt_materia extends toba_datos_tabla
{
    function listado_materias_de($id_plan){//Para lista doble
        $sql = "SELECT     t_m.id_materia,
                                t_m.orden,
                                t_m.nombre 
                                
                  FROM     materia as t_m
                  WHERE     t_m.id_plan = ".$id_plan
                    ." ORDER BY orden";
        return toba::db()->consultar($sql);
    }
    
    function listar_materias($id_plan){//Para exportar
        $sql = "SELECT     
                    t_m.id_materia,
                    t_m.codigo_siu,
                    t_m.nombre,
                    t_m.orden,
                    t_m.optativa,
                    t_m.anio_cursado,
                    t_p.nombre as periodo,
                    t_o.descripcion as observacion,
                    t_tmod.nombre as tipo_modulo,
                    t_mod.nombre as modulo,
                    t_tmod2.nombre as tipo_modulo_padre,
                    t_mod2.nombre as modulo_padre,
                    t_tmod3.nombre as tipo_modulo_padre_padre,
                    t_mod3.nombre as modulo_padre_padre
                                
                FROM     materia as t_m
                LEFT OUTER JOIN periodo as t_p ON t_p.id_periodo=t_m.id_periodo
                LEFT OUTER JOIN se_encuentra as t_se ON t_se.id_materia=t_m.id_materia
                LEFT OUTER JOIN obs_mat as t_o ON t_o.id_entidad = t_m.id_materia
                
                LEFT OUTER JOIN modulo as t_mod ON t_mod.id_modulo=t_se.id_modulo
                LEFT OUTER JOIN tipo_modulo as t_tmod ON t_tmod.id_tipo_modulo=t_mod.id_tipo_modulo
                
                LEFT OUTER JOIN modulo as t_mod2 ON t_mod2.id_modulo = t_mod.id_modulo_padre
                LEFT OUTER JOIN tipo_modulo as t_tmod2 ON t_tmod2.id_tipo_modulo=t_mod2.id_tipo_modulo
                
                LEFT OUTER JOIN modulo as t_mod3 ON t_mod3.id_modulo = t_mod2.id_modulo_padre
                LEFT OUTER JOIN tipo_modulo as t_tmod3 ON t_tmod3.id_tipo_modulo=t_mod3.id_tipo_modulo
                WHERE     t_m.id_plan = $id_plan
                     ORDER BY orden";
        return toba::db()->consultar($sql);
    }
    
    function get_max_orden($id_plan){
        $sql = "SELECT max(orden) as orden FROM materia WHERE id_plan = ".$id_plan;
        return toba::db('libro_unco')->consultar($sql);
    }
    function eliminar_materia($id_materia){
        $sql = "delete from materia where id_materia = ".$id_materia;
        toba::db('libro_unco')->consultar($sql);
    }
    
     
    function get_listado($filtro=array())
	{
		$where = array();
                if (isset($filtro['nombre'])) {
			$where[] = "t_m.nombre ILIKE ".quote("%{$filtro['nombre']['valor']}%");
		}
		if (isset($filtro['codigo_siu'])) {
			$where[] = "t_m.codigo_siu = ".$filtro['codigo_siu']['valor'];
		}
                if (isset($filtro['anio_cursado'])) {
			$where[] = "t_m.anio_cursado = ".($filtro['anio_cursado']['valor']);
		}
                if (isset($filtro['periodo'])) {
			$where[] = "t_p.nombre ILIKE ".quote("%{$filtro['periodo']['valor']}%");
		}
                if (isset($filtro['optativa'])) {
			$where[] = "t_m.optativa = '".(($filtro['optativa']['valor']=='si')?true:false)."'";
		}
		if (isset($filtro['id_plan'])) {
			$where[] = "t_m.id_plan = ".$filtro['id_plan']['valor'];
		}
                
		$sql = "SELECT
			t_m.id_materia,
                        t_m.nombre,
                        t_m.codigo_siu,
                        t_m.cuatri_inicio,
                        t_p.nombre as periodo,
                        t_p.id_periodo,
                        t_m.anio_cursado,
                        t_m.orden,
                        t_m.optativa,
                        t_m.id_plan,
                        t_mo.nombre as modulo
		FROM
			materia as t_m	
                LEFT OUTER JOIN periodo as t_p 
                ON (t_m.id_periodo = t_p.id_periodo)
                LEFT OUTER JOIN se_encuentra as t_se
                ON (t_m.id_materia = t_se.id_materia)
                LEFT OUTER JOIN modulo as t_mo
                ON (t_mo.id_modulo = t_se.id_modulo)
                ";
		if(count($where)>0){
                    $sql = sql_concatenar_where($sql, $where);
                }
                $sql = $sql." ORDER BY orden";
                return toba::db('libro_unco')->consultar($sql);
                
	}
        
        //Metodo para combo editable
        function get_materia($id_materia = null){
            if (! isset($id_materia)) {//ninguna materia
			return array();
		}
            $sql = "SELECT 
                                    id_materia, 
                                    nombre
                            FROM 
                                    materia
                            WHERE
                                    id_materia = $id_materia";
            $ar = toba::consultar($sql);	
            if (! empty($ar)) {
                return $ar[0]['nombre'];
            }
        }
        
        //Metodo para combo editable llamado del ci_materia
    function get_nombres($filtro=array(), $id_plan){
        $w = '';
        if (isset($filtro)) {
                $w = "t_m.nombre ILIKE ".quote("%{$filtro}%");
        }
        
        $sql = "SELECT
			t_m.id_materia,
                        t_m.nombre
                        
		FROM
			materia as t_m	
                
                WHERE t_m.id_plan = $id_plan"
                . " AND $w"
                . " ORDER BY nombre;";
		
        return toba::db('libro_unco')->consultar($sql);
    }
}

?>
