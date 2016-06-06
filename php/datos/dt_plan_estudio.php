<?php
class dt_plan_estudio extends toba_datos_tabla
{
    function get_ids($filtro){//Para exportar
        $where = array();
            if(isset($filtro['titulo'])){
                $where[] = 't_pe.titulo ILIKE '.quote("%{$filtro['titulo']['valor']}%");
            }
            if(isset($filtro['nivel'])){
                if(strcasecmp($filtro['nivel']['condicion'], "es_igual_a")==0){//condicion de igualdad
                    if(strcasecmp($filtro['nivel']['valor'], "Grado")==0)
                        $where[] = 't_pe.nivel = 0';
                    else
                        if(strcasecmp($filtro['nivel']['valor'], "Pregrado")==0)
                            $where[] = 't_pe.nivel = -1';
                        else//posgrado
                            $where[] = 't_pe.nivel = 1';
                }
                else{//condicion distinta
                    if(strcasecmp($filtro['nivel']['valor'], "Grado")==0)
                        $where[] = 't_pe.nivel <> 0';
                    else
                        if(strcasecmp($filtro['nivel']['valor'], "Pregrado")==0)
                            $where[] = 't_pe.nivel <> -1';
                        else//posgrado
                            $where[] = 't_pe.nivel <> 1';
                }
                
            }
            if(isset($filtro['ordenanza'])){
                $where[] = 't_pe.ordenanza = '.$filtro['ordenanza']['valor'];
            }
            if(isset($filtro['iniciales_siu'])){
                $where[] = 't_pe.iniciales_siu ILIKE '.quote("%{$filtro['iniciales_siu']['valor']}%");
            }
            if(isset($filtro['nombre'])){
                $where[] = 't_pe.nombre ILIKE '.quote("%{$filtro['nombre']['valor']}%");
            }
            if(isset($filtro['sigla'])){
                $where[] = 't_pe.id_unidad_academica ILIKE '.quote("{$filtro['sigla']['valor']}");
            }
            
            if(isset($filtro['id_plan'])){
                $where[] = "t_pe.id_plan = ".$filtro['id_plan']['valor']."";
            }
		$sql = "SELECT t_pe.id_plan, t_pe.iniciales_siu,t_pe.nombre "
                        . "FROM plan_estudio t_pe";
		if(count($where)>0){
                    $sql = sql_concatenar_where($sql, $where);
                }
                $sql = $sql." ORDER BY t_pe.nombre";
                return toba::db('libro_unco')->consultar($sql);        
    }
    function listar_planes ($filtro){
        $where = array();
            if(isset($filtro['titulo'])){
                $where[] = 't_pe.titulo ILIKE '.quote("%{$filtro['titulo']['valor']}%");
            }
            if(isset($filtro['nivel'])){
                if(strcasecmp($filtro['nivel']['condicion'], "es_igual_a")==0){//condicion de igualdad
                    if(strcasecmp($filtro['nivel']['valor'], "Grado")==0)
                        $where[] = 't_pe.nivel = 0';
                    else
                        if(strcasecmp($filtro['nivel']['valor'], "Pregrado")==0)
                            $where[] = 't_pe.nivel = -1';
                        else//posgrado
                            $where[] = 't_pe.nivel = 1';
                }
                else{//condicion distinta
                    if(strcasecmp($filtro['nivel']['valor'], "Grado")==0)
                        $where[] = 't_pe.nivel <> 0';
                    else
                        if(strcasecmp($filtro['nivel']['valor'], "Pregrado")==0)
                            $where[] = 't_pe.nivel <> -1';
                        else//posgrado
                            $where[] = 't_pe.nivel <> 1';
                }
                
            }
            if(isset($filtro['ordenanza'])){
                $where[] = 't_pe.ordenanza = '.$filtro['ordenanza']['valor'];
            }
            if(isset($filtro['iniciales_siu'])){
                $where[] = 't_pe.iniciales_siu ILIKE '.quote("%{$filtro['iniciales_siu']['valor']}%");
            }
            if(isset($filtro['nombre'])){
                $where[] = 't_pe.nombre ILIKE '.quote("%{$filtro['nombre']['valor']}%");
            }
            
            if(isset($filtro['id_plan'])){
                $where[] = "t_pe.id_plan = ".$filtro['id_plan']['valor']."";
            }
            if(isset($filtro['sigla'])){
                $where[] = 't_pe.id_unidad_academica ILIKE '.quote("{$filtro['sigla']['valor']}");
            }
        $sql = "SELECT 
                  t_pe.id_plan,
                  t_pe.iniciales_siu,
                  t_pe.nombre,
                  t_pe.titulo,
                  t_pe.nivel,
                  t_pe.ordenanza,
                  t_pe.cant_inscriptos,
                  t_pe.clave_preinscripcion,
                  t_p.nombre as provincia,
                  t_l.nombre as localidad,
                  t_l.cp,
                  t_l.caracteristica,
                  t_s.direccion,
                  t_s.telefono_1,
                  t_s.telefono_2,
                  t_s.telefono_3,
                  t_s.fax_1,
                  t_s.fax_2,
                  t_s.fax_3,
                  t_s.interno_1,
                  t_s.interno_2,
                  t_s.interno_3,
                  t_s.coordenadas_x,
                  t_s.coordenadas_y,
                  t_a.id_area,
                  t_a.nombre as area
                        
                FROM plan_estudio as t_pe
                LEFT OUTER JOIN se_dicta as t_sd ON t_sd.id_plan = t_pe.id_plan
                LEFT OUTER JOIN sede as t_s ON t_s.id_sede=t_sd.id_sede
                LEFT OUTER JOIN localidad as t_l ON t_l.id_localidad=t_s.id_localidad
                LEFT OUTER JOIN provincia as t_p ON t_p.id_provincia=t_l.id_provincia
                LEFT OUTER JOIN pertenece as t_pert ON t_pert.id_plan = t_pe.id_plan
                LEFT OUTER JOIN area as t_a ON t_a.id_area = t_pert.id_area
                ";
        if(count($where)>0){
                    $sql = sql_concatenar_where($sql, $where);
                }
                $sql = $sql." ORDER BY t_pe.nombre";
        return toba::db('libro_unco')->consultar($sql);
    }
   function get_listado($filtro = array())
	{
            $where = array();
            if(isset($filtro['titulo'])){
                $where[] = 't_pe.titulo ILIKE '.quote("%{$filtro['titulo']['valor']}%");
            }
            if(isset($filtro['nivel'])){
                if(strcasecmp($filtro['nivel']['condicion'], "es_igual_a")==0){//condicion de igualdad
                    if(strcasecmp($filtro['nivel']['valor'], "Grado")==0)
                        $where[] = 't_pe.nivel = 0';
                    else
                        if(strcasecmp($filtro['nivel']['valor'], "Pregrado")==0)
                            $where[] = 't_pe.nivel = -1';
                        else//posgrado
                            $where[] = 't_pe.nivel = 1';
                }
                else{//condicion distinta
                    if(strcasecmp($filtro['nivel']['valor'], "Grado")==0)
                        $where[] = 't_pe.nivel <> 0';
                    else
                        if(strcasecmp($filtro['nivel']['valor'], "Pregrado")==0)
                            $where[] = 't_pe.nivel <> -1';
                        else//posgrado
                            $where[] = 't_pe.nivel <> 1';
                }
                
            }
            if(isset($filtro['ordenanza'])){
                $where[] = 't_pe.ordenanza = '.$filtro['ordenanza']['valor'];
            }
            if(isset($filtro['unidad_academica'])){
                $where[] = 'unidad_academica ILIKE '.quote("%{$filtro['unidad_academica']['valor']}%");
            }
            if(isset($filtro['iniciales_siu'])){
                $where[] = 't_pe.iniciales_siu ILIKE '.quote("%{$filtro['iniciales_siu']['valor']}%");
            }
            if(isset($filtro['sigla'])){
                $where[] = "id_unidad_academica = '".$filtro['sigla']['valor']."'";
            }
            if(isset($filtro['nombre'])){
                $where[] = 't_pe.nombre ILIKE '.quote("%{$filtro['nombre']['valor']}%");
            }
            
            if(isset($filtro['id_plan'])){
                $where[] = "t_pe.id_plan = ".$filtro['id_plan']['valor']."";
            }
		$sql = "SELECT
			t_pe.titulo,
			t_pe.ordenanza,
			t_pe.id_plan,
			t_pe.duracion,
			t_pe.iniciales_siu,
			t_pe.nombre			
                        
		FROM
			plan_estudio as t_pe
                       
                ";
		if(count($where)>0){
                    $sql = sql_concatenar_where($sql, $where);
                }
                $sql = $sql." ORDER BY t_pe.nombre";
                return toba::db('libro_unco')->consultar($sql);
                
	}

        function eliminar_plan($id_plan){
            $sql = "delete from plan_estudio where id_plan = ".$id_plan;
            toba::db('libro_unco')->consultar($sql);
        }
}

?>