<?php
class dt_sede extends toba_datos_tabla
{
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
            $join = "";
            if(isset($filtro['id_plan'])){
                $where[] = 'id_plan = '.$filtro['id_plan']['valor'];
            }
            
            $sql = "SELECT 
                    t_s.id_sede, 
                    t_s.direccion_calle, 
                    t_s.direccion_nro, 
                    t_s.imagen_plano_referencial,
                    t_l.nombre as localidad,
                    t_s.telefono_1,
                    t_s.telefono_2,
                    t_s.telefono_3,
                    t_s.fax_1,
                    t_s.fax_2,
                    t_s.fax_3,
                    t_p.nombre as provincia,
                    t_s.id_plan
                    
                    FROM sede as t_s
                    LEFT OUTER JOIN localidad as t_l ON t_l.id_localidad = t_s.id_localidad
                    LEFT OUTER JOIN provincia as t_p ON t_p.id_provincia = t_l.id_provincia
                    $join";
            if(count($where)>0){
                    $sql = sql_concatenar_where($sql, $where);
                }
                $sql = $sql." ORDER BY provincia";
                return toba::db('libro_unco')->consultar($sql);
                
        }

}

?>