<?php
class ci_cargo extends ci_cargos
{
    protected $s__datos_filtro;
        //-----------------------------------------------------------------------------------
	//---- pant_dato_cargo -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------
	//---- form_cargo -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_cargo(libro_unco_ei_formulario $form)
	{
            if ($this->controlador()->dep('datos')->esta_cargada()) {
                    $ar = $this->controlador()->dep('datos')->tabla('sector')->get();
                    $ar['nombre'] = utf8_decode($ar['nombre']);
                    $ar['nombre_masc'] = utf8_decode($ar['nombre_masc']);
                    $ar['nombre_fem'] = utf8_decode($ar['nombre_fem']);
                    return $ar;
		}
            
                
	}

	function evt__form_cargo__guardar($datos)
	{
            $datos['nombre'] = utf8_encode($datos['nombre']);
            $datos['nombre_masc'] = utf8_encode($datos['nombre_masc']);
            $datos['nombre_fem'] = utf8_encode($datos['nombre_fem']);
            $this->controlador()->dep('datos')->tabla('sector')->set($datos);
            $ar = $this->controlador()->dep('datos')->tabla('sector')->get();
            $this->controlador->s__id_sector = $ar['id_sector'];
            print_r($this->controlador()->dep('datos')->tabla('sector')->get_proximo_id());
	}

	function evt__form_cargo__cancelar()
	{
            $this->set_pantalla('pant_dato_cargo');
	}
        
        //-----------------------------------------------------------------------------------
	//---- pant_personal_cargo --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------
	//---- form_persona -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_persona(libro_unco_ei_formulario $form)
	{
            $filtro['id_sector']['valor'] = $this->controlador->s__id_sector;
            $ar = $this->controlador()->dep('datos')->tabla('persona')->get_listado($filtro);
            $ar['nombre'] = utf8_decode($ar['nombre']);
            $ar['apellido'] = utf8_decode($ar['apellido']);
            $ar['titulo'] = utf8_decode($ar['titulo']);
            return $ar;
	}
                
	function evt__form_persona__guardar($datos)
	{
            $ar['nombre'] = utf8_encode($ar['nombre']);
            $ar['apellido'] = utf8_encode($ar['apellido']);
            $ar['titulo'] = utf8_encode($ar['titulo']);
            $this->controlador()->dep('datos')->tabla('persona')->set($datos);
            
	}

	function evt__form_persona__cancelar()
	{
            $this->set_pantalla('pant_personal_cargo');
	}
    
        //-----------------------------------------------------------------------------------
	//---- pant_descripcion --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------
	//---- filtro_seccion --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro_seccion(libro_unco_ei_filtro $filtro)
	{
            if(isset($this->s__datos_filtro)){
                $filtro->set_datos($this->s__datos_filtro);
            }
	}

	function evt__filtro_seccion__filtrar($datos)
	{
            $this->s__datos_filtro = $datos;
	}

	function evt__filtro_seccion__cancelar()
	{
            unset($this->s__datos_filtro);
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro_seccion --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_seccion(libro_unco_ei_cuadro $cuadro)
	{
            $this->s__datos_filtro['id_sector']['valor'] = $this->controlador->s__id_sector;
            $cuadro->set_datos($this->controlador()->dep('datos')->tabla('seccion')->get_listado($this->s__datos_filtro));
            
	}

	function evt__cuadro_seccion__seleccion($seleccion)
	{
            $this->controlador()->dep('datos')->cargar($seleccion);
            $this->set_pantalla('pant_seccion');
	}
        
        function evt__cuadro_seccion__agregar($datos)
	{
            $this->controlador()->set_pantalla('pant_seccion');
	}

	function evt__cuadro_seccion__eliminar($seleccion)
	{
            $sql = "delete from seccion where id_seccion = '".$seleccion['id_seccion']."'";
            toba::db('libro_unco')->consultar($sql);
	}
        
        function evt__guardar(){
            try{//Sincronizar form_cargo
                $this->controlador()->dep('datos')->tabla('sector')->sincronizar();
                $this->controlador()->dep('datos')->tabla('sector')->resetear();
            }catch(toba_error_validacion $e){
                $this->set_pantalla('pant_dato_cargo');
            }
            try{//Sincronizar form_persona
                $this->controlador()->dep('datos')->tabla('persona')->sincronizar();
                $this->controlador()->dep('datos')->tabla('persona')->resetear();
            }catch(toba_error_validacion $e){
                $this->set_pantalla('pant_personal_cargo');
            }
                      
        }
        
        function evt__cancelar(){
            $this->disparar_limpieza_memoria();
        }
        
        function evt__listo(){
            $this->set_pantalla('pant_dato_cargo');
        }
    
}

