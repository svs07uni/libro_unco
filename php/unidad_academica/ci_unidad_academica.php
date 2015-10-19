<?php

class ci_unidad_academica extends toba_ci
{	
    protected $s__datos_filtro;
    protected $s__sigla;
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
        
        function evt__cuadro_ua__sedes($id_ua){
           $this->s__sigla = $id_ua['sigla'];
           $this->dep('datos')->tabla('unidad_academica')->cargar($id_ua);
            $this->set_pantalla('pant_sedes');
        }
        
        function evt__cuadro_ua__planes($id_ua){
            $this->dep('datos')->tabla('unidad_academica')->cargar($id_ua);
            toba::vinculador()->navegar_a("",3509,$id_ua);
        }
        
        //-----------------------------------------------------------------------------------
	//---- form_datos -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_datos(libro_unco_ei_formulario $form)
	{
            if ($this->dep('datos')->tabla('unidad_academica')->esta_cargada()) {
                    $this->dep('form_datos')->ef('sigla')->set_solo_lectura();
                    $ar = $this->dep('datos')->tabla('unidad_academica')->get();
                    $fp_imagen = $this->dep('datos')->tabla('unidad_academica')->get_blob('imagen',$ar['x_dbr_clave']);
                    
                    if (isset($fp_imagen)) {
		  	//-- Se necesita el path fisico y la url de una archivo temporal que va a contener la imagen
		  	$temp_nombre = 'img'.$ar['sigla'].'.jpg';
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
            $datos['sigla'] = strtoupper($datos['sigla']);
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
        
        //-----------------------------------------------------------------------------------
	//---- pant_sede --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        function conf__cuadro_sedes(libro_unco_ei_cuadro $cuadro){
            //$filtro['id_plan']['valor'] = $this->controlador->s__id_plan;
            //    $cuadro->set_datos($this->controlador()->dep('datos')->tabla('sede')->get_listado($filtro));
            $this->dep('cuadro_sedes')->set_titulo($this->s__sigla);
            $filtro['id_unidad_academica']['valor'] = $this->s__sigla;
            $this->s__lista_sedes = $this->controlador()->dep('datos')->tabla('sede')->get_listado($filtro);
            $cuadro->set_datos($this->s__lista_sedes);
            
        }
        
        function evt__cuadro_sedes__seleccion($sede){
            $this->controlador()->dep('datos')->tabla('sede')->cargar($sede);
        }
        
        function evt__cuadro_sedes__eliminar($sede){
            $this->controlador()->dep('datos')->tabla('sede')->eliminar_sede($sede['id_sede']);
            
        }
               
        //-----------------------------------------------------------------------------------
	//---- form_sede -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_sede(libro_unco_ei_formulario $form){
            
            $sede = $this->controlador()->dep('datos')->tabla('sede')->get();
            if(isset($sede)){//Encontre en memoria o BD
                if(isset($sede['id_localidad'])){
                    $filtro['id_localidad']['valor'] = $sede['id_localidad'];
                    $localidad = $this->controlador()->dep('datos')->tabla('localidad')->get_listado($filtro);
                    
                    $sede['id_provincia'] = $localidad[0]['id_provincia'];
                }

                return $sede;
            }
        }
                
	function evt__form_sede__guardar($datos)
	{
            $datos['id_plan'] = $this->controlador->s__id_plan;
            //$this->s__sedes .= $datos;print_r($this->s__sedes);exit();
            $this->controlador()->dep('datos')->tabla('sede')->set($datos); 
            $this->controlador()->dep('datos')->tabla('sede')->resetear();
        }
        
        function evt__form_sede__limpiar(){
            $this->controlador()->dep('datos')->tabla('sede')->resetear();
        }
        
        function evt__form_sede__volver(){
            $this->dep('datos')->resetear();
            $this->set_pantalla('pant_ua');
        }
	
        
        function resetear()
	{
		$this->dep('datos')->resetear();
	}

}
?>