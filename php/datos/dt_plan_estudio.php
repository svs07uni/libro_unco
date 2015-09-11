<?php
class dt_plan_estudio extends toba_datos_tabla
{
   function get_listado($filtro = array())
	{
            $where = array();
            if(isset($filtro['titulo'])){
                $where[] = 'titulo ILIKE '.quote("%{$filtro['titulo']['valor']}%");
            }
            if(isset($filtro['ordenanza'])){
                $where[] = 'ordenanza = '.$filtro['ordenanza']['valor'];
            }
            if(isset($filtro['unidad_academica'])){
                $where[] = 'unidad_academica ILIKE '.quote("%{$filtro['unidad_academica']['valor']}%");
            }
            if(isset($filtro['iniciales_siu'])){
                $where[] = 'iniciales_siu = '.$filtro['iniciales_siu']['valor'];
            }
            if(isset($filtro['sigla'])){
                $where[] = "sigla = '".$filtro['sigla']['valor']."'";
            }
            if(isset($filtro['nombre'])){
                $where[] = 'nombre ILIKE '.quote("%{$filtro['nombre']['valor']}%");
            }
            if(isset($filtro['localidad'])){
                $where[] = 'localidad ILIKE '.quote("%{$filtro['localidad']['valor']}%");
            }
            if(isset($filtro['provincia'])){
                $where[] = 'provincia ILIKE '.quote("%{$filtro['provincia']['valor']}%");
            }
		$sql = "SELECT
			t_pe.titulo,
			t_pe.ordenanza,
			t_pe.id_plan,
			t_ua.nombre as unidad_academica,
			t_pe.duracion,
			t_pe.iniciales_siu,
			t_pe.nombre,
			t_l.nombre as localidad,
                        t_p.nombre as provincia
                        
		FROM
			plan_estudio as t_pe	LEFT OUTER JOIN unidad_academica as t_ua ON (t_pe.id_unidad_academica = t_ua.sigla)
			LEFT OUTER JOIN sede as t_s ON (t_pe.id_plan = t_s.id_plan)
                        LEFT OUTER JOIN localidad as t_l ON (t_l.id_localidad = t_s.id_localidad)
                        LEFT OUTER JOIN provincia as t_p ON (t_p.id_provincia = t_l.id_provincia)
                ";
		if(count($where)>0){
                    $sql = sql_concatenar_where($sql, $where);
                }
                $sql = $sql." ORDER BY t_pe.nombre";
                return toba::db('libro_unco')->consultar($sql);
                
	}

}

?>