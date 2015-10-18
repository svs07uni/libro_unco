<?php
class dt_sede extends toba_datos_tabla
{
    function eliminar_sede($id_sede){
        $sql = "DELETE FROM sede WHERE id_sede = ".$id_sede;
        toba::db('libro_unco')->consultar($sql);
    }
    function get_listado($filtro = array()){
            $where = array();
            
            if(isset($filtro['direccion_calle'])){
                $where[] = 'direccion_calle ILIKE '.quote("%{$filtro['direccion_calle']['valor']}%");
            }
            if(isset($filtro['localidad'])){
                $where[] = 'localidad ILIKE '.quote("%{$filtro['localidad']['valor']}%");
            }
            if(isset($filtro['provincia'])){
                $where[] = 'provincia ILIKE '.quote("%{$filtro['provincia']['valor']}%");
            }
            if(isset($filtro['id_sede'])){
                $where[] = 'id_sede = '.$filtro['id_sede']['valor'];
            }
            if(isset($filtro['id_plan'])){
                $where[] = 'id_plan = '.$filtro['id_plan']['valor'];
            }
            if(isset($filtro['id_unidad_academica'])){
                $where[] = 'id_unidad_academica ILIKE '.quote("%{$filtro['id_unidad_academica']['valor']}%");
            }
            
            $sql = "SELECT 
                    t_s.id_sede, 
                    t_s.direccion, 
                    t_s.id_localidad,
                    t_s.coordenadas_x,
                    t_s.coordenadas_y,
                    t_l.nombre as localidad,
                    t_s.telefono_1,
                    t_s.telefono_2,
                    t_s.telefono_3,
                    t_s.fax_1,
                    t_s.fax_2,
                    t_s.fax_3,
                    t_p.id_provincia,
                    t_p.nombre as provincia,
                    t_s.id_unidad_academica
                    
                    FROM sede as t_s
                    LEFT OUTER JOIN localidad as t_l ON t_l.id_localidad = t_s.id_localidad
                    LEFT OUTER JOIN provincia as t_p ON t_p.id_provincia = t_l.id_provincia
                    ";
            if(count($where)>0){
                    $sql = sql_concatenar_where($sql, $where);
                }
                $sql = $sql." ORDER BY provincia";
                return toba::db('libro_unco')->consultar($sql);
                
    }
        
    function listar_sedes($array_id_sedes, $filtro = null){
            $where = array();
            
            if(isset($filtro['direccion_calle'])){
                $where[] = 'direccion_calle ILIKE '.quote("%{$filtro['direccion_calle']['valor']}%");
            }
            if(isset($filtro['localidad'])){
                $where[] = 'localidad ILIKE '.quote("%{$filtro['localidad']['valor']}%");
            }
            if(isset($filtro['provincia'])){
                $where[] = 'provincia ILIKE '.quote("%{$filtro['provincia']['valor']}%");
            }
            if(isset($filtro['id_sede'])){
                $where[] = 'id_sede = '.$filtro['id_sede']['valor'];
            }
            
            if(isset($filtro['id_unidad_academica'])){
                $where[] = 'id_unidad_academica ILIKE '.quote("%{$filtro['id_unidad_academica']['valor']}%");
            }
            
            $w = "(";
            for($i = 0; $i<sizeof($array_id_sedes)-1; $i++){
                $w .= "id_sede = ".$array_id_sedes[$i]['id_sede']." OR ";
            }
            $w .= "id_sede = ".$array_id_sedes[$i]['id_sede'].")";
            $where[] = $w;
            $sql = "SELECT 
                    t_s.id_sede, 
                    t_s.direccion, 
                    t_s.id_localidad,
                    t_s.coordenadas_x,
                    t_s.coordenadas_y,
                    t_l.nombre as localidad,
                    t_s.telefono_1,
                    t_s.telefono_2,
                    t_s.telefono_3,
                    t_s.fax_1,
                    t_s.fax_2,
                    t_s.fax_3,
                    t_p.id_provincia,
                    t_p.nombre as provincia,
                    t_s.id_unidad_academica
                    
                    FROM sede as t_s
                    LEFT OUTER JOIN localidad as t_l ON t_l.id_localidad = t_s.id_localidad
                    LEFT OUTER JOIN provincia as t_p ON t_p.id_provincia = t_l.id_provincia
                    ";
            if(count($where)>0){
                    $sql = sql_concatenar_where($sql, $where);
           }
           $sql = $sql." ORDER BY provincia";
           return toba::db('libro_unco')->consultar($sql);
              
        }    

}

?>