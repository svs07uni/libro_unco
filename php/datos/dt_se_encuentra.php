<?php
class dt_se_encuentra extends toba_datos_tabla
{
    function listar_materias_de_modulo($id_modulo){
        $sql = "SELECT id_materia FROM se_encuentra WHERE id_modulo = ".$id_modulo;
        return toba::db('libro_unco')->consultar($sql);
    }
    function eliminar_materias_de_modulo($id_modulo){
        $sql = "DELETE FROM se_encuentra WHERE id_modulo = ".$id_modulo;
        toba::db('libro_unco')->consultar($sql);
    }
}
