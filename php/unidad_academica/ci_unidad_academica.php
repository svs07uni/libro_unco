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
		  	$tama�o = round(filesize($temp_archivo['path']) / 1024);
		  	
		  	//-- Se muestra la imagen temporal
		  	$ar['imagen_vista_previa'] = "<img src='{$temp_archivo['url']}' width=100 height=100>";
		  	$ar['imagen'] = 'Tama�o: '.$tama�o. ' KB';
                        
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
            $this->dep('datos')->tabla('unidad_academica')->sincronizar();
            $this->resetear();
            $this->disparar_limpieza_memoria();
            $this->set_pantalla('pant_ua');
	}

	function evt__form_datos__cancelar()
	{
            $this->dep('datos')->tabla('unidad_academica')->resetear();
            $this->disparar_limpieza_memoria();
            $this->set_pantalla('pant_ua');
	}
        
        //-----------------------------------------------------------------------------------
	//---- pant_sede --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        function conf__cuadro_sedes(libro_unco_ei_cuadro $cuadro){
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
        protected $s__datos_sede;//para mantener los datos del formulario cuando ocurre un error
        
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
            else{//Si hay datos temporales cargados pero ocurrio un error en chequeo
                if(isset($this->s__datos_sede)){
                    return $this->s__datos_sede;
                }
            }
        }
                
	function evt__form_sede__guardar($datos)
	{
            $datos['id_unidad_academica'] = $this->s__sigla;
            
            $datos['coordenadas_x'] = floatval(str_replace(',', '.', $datos['coordenadas_x']));
            $datos['coordenadas_y'] = floatval(str_replace(',', '.', $datos['coordenadas_y']));
            
            if($datos['coordenadas_x']>180 || $datos['coordenadas_x']<-180){
                toba::notificacion()->agregar("Longitud no v�lida",'error');
                $this->s__datos_sede = $datos;
                return $datos;
            }
            if($datos['coordenadas_y']>90 || $datos['coordenadas_y']<-90){
                toba::notificacion()->agregar("Latitud no v�lida",'error');
                $this->s__datos_sede = $datos;
                return $datos;
            }                
            
            $this->controlador()->dep('datos')->tabla('sede')->set($datos); 
            $this->controlador()->dep('datos')->tabla('sede')->sincronizar();
            $this->controlador()->dep('datos')->tabla('sede')->resetear();
            $this->s__datos_sede = null;
        }
        
        function evt__form_sede__volver(){
            $this->dep('datos')->resetear();
            $this->s__datos_sede = null;
            $this->set_pantalla('pant_ua');
        }
	
        
        //-----------------------------------------------------------------------------------
	//---- AJAX --------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

        function ajax__get_caracteristica($id_localidad, toba_ajax_respuesta $respuesta){
            $ar = $this->dep('datos')->tabla('localidad')->get_caracteristica($id_localidad);
            $respuesta -> set( $ar['caracteristica'] );
        }

}
?>