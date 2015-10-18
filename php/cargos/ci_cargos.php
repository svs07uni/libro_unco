<?php
class ci_cargos extends toba_ci
{
    protected $s__datos_filtro;
    protected $s__id_sector;
    protected $s__sigla;
    
    protected $s__pantalla;
	//-----------------------------------------------------------------------------------
	//---- pant_ua --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------
	//---- filtro_ua --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro_ua(libro_unco_ei_filtro $filtro)
	{
            if(isset($this->s__datos_filtro)){
                $filtro->set_datos($this->s__datos_filtro);
            }
	}

	function evt__filtro_ua__filtrar($datos)
	{
            $this->s__datos_filtro = $datos;
	}

	function evt__filtro_ua__cancelar()
	{
            unset($this->s__datos_filtro);
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro_ua --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_ua(libro_unco_ei_cuadro $cuadro)
	{
            if(isset($this->s__datos_filtro)){
                $cuadro->set_datos($this->dep('datos')->tabla('unidad_academica')->get_listado($this->s__datos_filtro));
            }
            else{
                $cuadro->set_datos($this->dep('datos')->tabla('unidad_academica')->get_listado());
            }
	}

	function evt__cuadro_ua__seleccion($seleccion)
	{
            $this->dep('datos')->tabla('unidad_academica')->cargar($seleccion);
            $this->s__sigla = $seleccion['sigla'];
            $this->set_pantalla('pant_cargos');
	}
        //-----------------------------------------------------------------------------------
	//---- pant_cargos --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------
	//---- filtro_cargo --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro_cargo(libro_unco_ei_filtro $filtro)
	{
            if(isset($this->s__datos_filtro)){
                $filtro->set_datos($this->s__datos_filtro);
            }
	}

	function evt__filtro_cargo__filtrar($datos)
	{
            $this->s__datos_filtro = $datos;
	}

	function evt__filtro_cargo__cancelar()
	{
            unset($this->s__datos_filtro);
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro_cargos --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_cargos(libro_unco_ei_cuadro $cuadro)
	{
            $this->s__datos_filtro['sigla']['valor'] = $this->s__sigla;
            $cuadro->set_datos($this->dep('datos')->tabla('sector')->get_listado_con_persona($this->s__datos_filtro));
            
	}

	function evt__cuadro_cargos__seleccion($seleccion)
	{
            $this->s__id_sector = $seleccion['id_sector'];
            $this->dep('datos')->tabla('sector')->cargar($seleccion);
            $this->set_pantalla('pant_cargo');
	}
        
        function evt__cuadro_cargos__agregar($datos)
	{
            $this->set_pantalla('pant_cargo');
	}

	function evt__cuadro_cargos__eliminar($seleccion)
	{
            $sql = "delete from sector where id_sector = '".$seleccion['id_sector']."'";
            toba::db('libro_unco')->consultar($sql);
	}
        
        
        
	function resetear()
	{
		$this->dep('datos')->resetear();
	}

}


?>