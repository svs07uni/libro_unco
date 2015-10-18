<?php
class dt_ubicacion extends toba_datos_tabla
{
    function eliminar_sede($id_sede){
        $sql = "DELETE FROM ubicacion WHERE id_ubicacion = ".$id_sede;
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
            if(isset($filtro['id_unidad_academica'])){
                $where[] = 'id_unidad_academica ILIKE '.quote("%{$filtro['id_unidad_academica']['valor']}%");
            }
            
            $sql = "SELECT 
                    t_u.id_ubicacion, 
                    t_u.direccion, 
                    t_u.id_localidad,
                    t_u.coordenadas_x,
                    t_u.coordenadas_y,
                    t_l.nombre as localidad,
                    t_u.telefono_1,
                    t_u.telefono_2,
                    t_u.telefono_3,
                    t_u.fax_1,
                    t_u.fax_2,
                    t_u.fax_3,
                    t_p.id_provincia,
                    t_p.nombre as provincia
                    
                    FROM ubicacion as t_u
                    LEFT OUTER JOIN localidad as t_l ON t_l.id_localidad = t_u.id_localidad
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