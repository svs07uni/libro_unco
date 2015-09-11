<?php
class ci_cargos extends toba_ci
{
    protected $s__datos_filtro;
    protected $s__id_sector;
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
            if(isset($this->s__datos_filtro)){
                $cuadro->set_datos($this->dep('datos')->tabla('sector')->get_listado_con_persona($this->s__datos_filtro));
            }
            else{
                $cuadro->set_datos($this->dep('datos')->tabla('sector')->get_listado_con_persona());
            }
	}

	function evt__cuadro_cargos__seleccion($seleccion)
	{
            $this->s__id_cargo = $seleccion['id_sector'];
            $this->dep('datos')->cargar($seleccion);
            $this->set_pantalla('pant_cargo');
	}
        
        function evt__cuadro_cargos__agregar($datos)
	{
            $this->dep('datos')->tabla('sector')->cargar();
            $this->set_pantalla('pant_cargo');
	}

	function evt__cuadro_cargos__eliminar($seleccion)
	{
            $sql = "delete from sector where id_sector = '".$seleccion['id_sector']."'";
            toba::db('libro_unco')->consultar($sql);
	}
        //-----------------------------------------------------------------------------------
	//---- pant_cargo --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        
        //-----------------------------------------------------------------------------------
	//---- pant_seccion --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------
	//---- form_seccion -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_seccion(libro_unco_ei_formulario $form)
	{
            $filtro['id_sector']['valor'] = $this->s__id_sector;
            $ar = $this->dep('datos')->tabla('seccion')->get_listado($filtro);
            if(count($ar)>0){
                $ar['contenido'] = utf8_decode($ar['contenido']);
                $ar['titulo'] = utf8_decode($ar['titulo']);
                return $ar;
            }
            
	}
                
	function evt__form_seccion__guardar($datos)
	{
            $ar['contenido'] = utf8_encode($ar['contenido']);
            $ar['titulo'] = utf8_encode($ar['titulo']);
            $id_seccion = $this->dep('datos')->tabla('seccion')->set($datos);
            $this->dep('datos')->tabla('seccion')->sincronizar();
            $datos2['id_sector'] = $this->s__id_sector;
            $datos2['id_seccion'] = $id_seccion;
            $this->dep('datos')->tabla('seccion_sector')->set($datos2);
            $this->dep('datos')->tabla('seccion_sector')->sincronizar();
            $this->resetear();
            $this->set_pantalla('pant_descripcion');
	}

	function evt__form_seccion__cancelar()
	{
            $this->set_pantalla('pant_seccion');
	}
        
	function resetear()
	{
		$this->dep('datos')->resetear();
	}

}


?>