<?php
class ci_prueba_multi_doble extends toba_ci
{
	//---- Cuadro -----------------------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
            $sql = "SELECT id_materia, nombre FROM materia";
            $ar = toba::db('libro_unco')->consultar($sql);
            $cuadro->set_datos($ar);
	}

	function evt__cuadro__seleccion($datos)
	{
		$this->dep('datos')->cargar($datos);
	}

	//---- Formulario -------------------------------------------------------------------

	function conf__formulario(toba_ei_formulario $form)
	{
            
	}

	function evt__formulario__alta($datos)
	{
		print_r($datos);
	}

	function evt__formulario__modificacion($datos)
	{
		print_r($datos);
	}

	function evt__formulario__baja()
	{
		$this->dep('datos')->eliminar_todo();
		$this->resetear();
	}

	function evt__formulario__cancelar()
	{
		$this->resetear();
	}
        
        function get_descripciones(){
            $sql = "SELECT id_materia, nombre FROM materia";
            $ar = toba::db('libro_unco')->consultar($sql);
            //print_r($ar);
            return $ar;
        }

	function resetear()
	{
		$this->dep('datos')->resetear();
	}

}

?>