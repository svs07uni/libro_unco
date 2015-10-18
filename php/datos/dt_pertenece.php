<?php
class dt_pertenece extends toba_datos_tabla
{
	function get_listado($filtro = array()){
            $where = array();
            
            if(isset($filtro['id_area'])){
                $where[] = 'id_area = '.$filtro['id_area']['valor'];
            }
            if(isset($filtro['id_plan'])){
                $where[] = 'id_plan = '.$filtro['id_plan']['valor'];
            }
            
            $sql = "SELECT 
                    t_p.id_area, 
                    t_p.id_plan 
                    
                    FROM pertenece as t_p
                    ";
            if(count($where)>0){
                    $sql = sql_concatenar_where($sql, $where);
                }
            $sql = $sql." ORDER BY id_plan";
            return toba::db('libro_unco')->consultar($sql);
                
        }

}

?>