<?php
class dt_se_dicta extends toba_datos_tabla
{
    function listar_planes_de_ubicacion($id_ubicacion){
        $sql = "SELECT id_plan FROM se_dicta WHERE id_ubicacion = ".$id_ubicacion;
        return toba::db('libro_unco')->consultar($sql);
    }
    function eliminar_planes_de_ubicacion($id_ubicacion){
        $sql = "DELETE FROM se_dicta WHERE id_ubicacion = ".$id_ubicacion;
        toba::db('libro_unco')->consultar($sql);
    }
    function listar_sedes_de_plan($id_plan){
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
                    
                    FROM se_dicta as t_sd
                    LEFT OUTER JOIN sede as t_s ON t_sd.id_sede = t_s.id_sede
                    LEFT OUTER JOIN localidad as t_l ON t_l.id_localidad = t_s.id_localidad
                    LEFT OUTER JOIN provincia as t_p ON t_p.id_provincia = t_l.id_provincia
                WHERE id_plan = $id_plan";
        return toba::db('libro_unco')->consultar($sql);
    }
}