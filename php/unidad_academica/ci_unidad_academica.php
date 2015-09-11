<?php

class ci_unidad_academica extends toba_ci
{	
    protected $s__datos_filtro;
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
            $this->dep('datos')->cargar($seleccion);
            $this->set_pantalla('pant_datos');
	}

	function evt__cuadro_ua__agregar($datos)
	{
            $this->set_pantalla('pant_datos');
	}

	function evt__cuadro_ua__eliminar($seleccion)
	{
            $sql = "delete from unidad_academica where sigla = '".$seleccion['sigla']."'";
            toba::db('libro_unco')->consultar($sql);
	}
        
        //-----------------------------------------------------------------------------------
	//---- form_datos -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_datos(libro_unco_ei_formulario $form)
	{
            if ($this->dep('datos')->esta_cargada()) {
                    $ar = $this->dep('datos')->tabla('unidad_academica')->get();
                    $ar['nombre'] = utf8_decode($ar['nombre']);
                    $fp_imagen = $this->dep('datos')->tabla('unidad_academica')->get_blob('imagen',$ar['x_dbr_clave']);
                    
                    if (isset($fp_imagen)) {
		  	//-- Se necesita el path fisico y la url de una archivo temporal que va a contener la imagen
		  	$temp_nombre = 'img'.$ar['sigla'].'.pdf';
		  	$temp_archivo = toba::proyecto()->get_www_temp($temp_nombre);
	
		  	//-- Se pasa el contenido al archivo temporal
		  	$temp_fp = fopen($temp_archivo['path'], 'w');
		  	stream_copy_to_stream($fp_imagen, $temp_fp);
		  	fclose($temp_fp);
		  	$tamaño = round(filesize($temp_archivo['path']) / 1024);
		  	
		  	//-- Se muestra la imagen temporal
		  	$ar['imagen_vista_previa'] = "<img src='{$temp_archivo['url']}' width=100 height=100>";
		  	$ar['imagen'] = 'Tamaño: '.$tamaño. ' KB';
                        
		} else {
			$ar['imagen'] = null;
                        
		}
		return $ar;
		}
                
	}

	function evt__form_datos__guardar($datos)
	{
            $datos['nombre'] = utf8_encode($datos['nombre']);
            $this->dep('datos')->tabla('unidad_academica')->set($datos);
            if(is_array($datos['imagen'])){
                $fp = fopen($datos['imagen']['tmp_name'], 'rb');
                $this->dep('datos')->tabla('unidad_academica')->set_blob('imagen', $fp);
            }
            $this->dep('datos')->sincronizar();
            $this->resetear();
            $this->disparar_limpieza_memoria();
            $this->set_pantalla('pant_ua');
	}

	function evt__form_datos__cancelar()
	{
            $this->resetear();
            $this->disparar_limpieza_memoria();
            $this->set_pantalla('pant_ua');
	}
        
        function resetear()
	{
		$this->dep('datos')->resetear();
	}

}
?>