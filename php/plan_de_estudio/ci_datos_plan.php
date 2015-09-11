<?php
class ci_datos_plan extends ci_plan_de_estudio
{
    protected $s__id_sede;
    protected $s__nuevo_plan;
        //-----------------------------------------------------------------------------------
	//---- pant_datos -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------
	//---- form_cargo -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_plan(libro_unco_ei_formulario $form)
	{
            
            if(isset($this->controlador->s__id_plan)){//Existen datos de este plan
                $ar = $this->controlador()->dep('datos')->tabla('plan_estudio')->get();
                $ar['titulo'] = utf8_decode($ar['titulo']);
                $ar['nombre'] = utf8_decode($ar['nombre']);
                return $ar;
            }
            
	}

	function evt__form_plan__guardar($datos)
	{
            $datos['titulo'] = utf8_encode($datos['titulo']);
            $datos['nombre'] = utf8_encode($datos['nombre']);
            $datos['id_unidad_academica'] = $this->s__sigla;
            $this->controlador()->dep('datos')->tabla('plan_estudio')->set($datos);
            $ar = $this->controlador()->dep('datos')->tabla('plan_estudio')->get();
            if(!isset($ar['id_plan'])){//No Existe el campo id_plan ent no existe este registro
                $this->controlador()->dep('datos')->tabla('plan_estudio')->sincronizar();
                $ar = $this->controlador()->dep('datos')->tabla('plan_estudio')->get();
                $this->controlador->s__id_plan = $ar['id_plan'];
                $this->s__nuevo_plan = true;//Como no existia ent si presiona cancelar este registro se debe borrar
            }
            
	}

	//-----------------------------------------------------------------------------------
	//---- pant_sede --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------
	//---- form_sede -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_sede(libro_unco_ei_formulario $form){//Si ingresa a este form ent ya existe plan y está cargado
            
            $sede = $this->controlador()->dep('datos')->tabla('sede')->get();
            if(!isset($sede)){//No existe sede cargada en memoria
                //Se busca en la base de datos  
                $filtro['id_plan']['valor'] = $this->controlador->s__id_plan;
                $sede = $this->controlador()->dep('datos')->tabla('sede')->get_listado($filtro);
                if(count($sede)>0)//Existe en la BD
                    $sede = $sede[0];
            }
        
            if(count($sede)>0){//Si existe cargada en la memoria o en la BD
                $sede['direccion_calle'] = utf8_decode($sede['direccion_calle']);
                $fp_imagen = $this->controlador()->dep('datos')->tabla('sede')->get_blob('imagen_plano_referencial');
                    
                if (isset($fp_imagen)) {
                    //-- Se necesita el path fisico y la url de una archivo temporal que va a contener la imagen
                    $temp_nombre = 'img'.uniqid(time()).'.jpg';
                    $temp_archivo = toba::proyecto()->get_www_temp($temp_nombre);
	
                    //-- Se pasa el contenido al archivo temporal
                    $temp_fp = fopen($temp_archivo['path'], 'w');
                    stream_copy_to_stream($fp_imagen, $temp_fp);
                    fclose($temp_fp);
                    $tamaño = round(filesize($temp_archivo['path']) / 1024);
		  	
                    //-- Se muestra la imagen temporal
                    $sede['imagen_vista_previa'] = "<img src='{$temp_archivo['url']}' alt='' width=100 height=100>";
                    $sede['imagen'] = 'Tamaño: '.$tamaño. ' KB';
                        
                 } else {
                    $sede['imagen'] = null;
                        
                 }
                return $sede;
            }
        }
                
	function evt__form_sede__guardar($datos)
	{print_r($datos);
            $datos['direccion_calle'] = utf8_encode($datos['direccion_calle']);
            $datos['id_plan'] = $this->controlador->s__id_plan;
            $this->controlador()->dep('datos')->tabla('sede')->set($datos);
            //$this->s__id_sede = $this->controlador()->dep('datos')->tabla('sede')->get();
            if (is_array($datos['imagen_plano_referencial'])) {
                //Se subio una imagen
                $fp = fopen($datos['imagen_plano_referencial']['tmp_name'], 'rb');
                $this->controlador()->dep('datos')->tabla('sede')->set_blob('imagen_plano_referencial', $fp);
            } 
            
        }

	//-----------------------------------------------------------------------------------
	//---- pant_observacion -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------
	//---- form_observacion -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_observacion(libro_unco_ei_formulario $form)
	{//Ya existe el plan cargado
            $obs = $this->controlador()->dep('datos')->tabla('obs_plan')->get();
            if(!isset($obs)){//No existe observación cargada en memoria
                //Se busca en la base de datos  
                $filtro['id_plan']['valor'] = $this->controlador->s__id_plan;
                $obs = $this->controlador()->dep('datos')->tabla('obs_plan')->get_listado($filtro);
                if(count($obs)>0)//Existe en la BD
                    $obs = $obs[0];
            }
            if(count($obs)>0){//Existe en memoria
                $obs['descripcion'] = utf8_decode($obs['descripcion']);
                return $obs;
            }
            
	}

	function evt__form_observacion__guardar($datos)
	{
            $datos['descripcion'] = utf8_encode($datos['descripcion']);
            $datos['id_entidad'] = $this->controlador->s__id_plan;
            $this->controlador()->dep('datos')->tabla('obs_plan')->set($datos);
            
	}
        
        //-----------------------------------------------------------------------------------
	//---- pant_modulos --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------
	//---- filtro_modulo --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro_modulo(libro_unco_ei_filtro $filtro)
	{
            if(isset($this->s__datos_filtro)){
                $filtro->set_datos($this->s__datos_filtro);
            }
	}

	function evt__filtro_modulo__filtrar($datos)
	{
            $this->s__datos_filtro = $datos;
	}

	function evt__filtro_modulo__cancelar()
	{
            unset($this->s__datos_filtro);
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro_modulos --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_modulos(libro_unco_ei_cuadro $cuadro)
	{
            $this->s__datos_filtro['id_plan']['valor'] = $this->controlador->s__id_plan;
            if(isset($this->s__datos_filtro)){
                $cuadro->set_datos($this->controlador()->dep('datos')->tabla('modulo')->get_listado($this->s__datos_filtro));
            }
            else{
                $cuadro->set_datos($this->controlador()->dep('datos')->tabla('modulo')->get_listado());
            }
	}

	function evt__cuadro_modulos__seleccion($seleccion)
	{
            $this->controlador->s__id_modulo = $seleccion['id_modulo'];
            $this->controlador()->dep('datos')->tabla('modulo')->cargar($seleccion);
            $this->controlador->s__pantalla = 'pant_modulos';
            $this->controlador()->set_pantalla('pant_modulo');
	}
        
        function evt__cuadro_modulos__agregar($datos)
	{
            $this->controlador->s__pantalla = 'pant_modulos';
            $this->controlador->s__id_modulo = null;
            $this->controlador()->set_pantalla('pant_modulo');
	}

	function evt__cuadro_modulos__eliminar($seleccion)
	{
            $sql = "delete from modulo where id_modulo = '".$seleccion['id_modulo']."'";
            toba::db('libro_unco')->consultar($sql);
	}
    
        //-----------------------------------------------------------------------------------
	//---- pant_materias --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------
	//---- filtro_materia --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro_materia(libro_unco_ei_filtro $filtro)
	{
            if(isset($this->s__datos_filtro)){
                $filtro->set_datos($this->s__datos_filtro);
            }
	}

	function evt__filtro_materia__filtrar($datos)
	{
            $this->s__datos_filtro = $datos;
	}

	function evt__filtro_materia__cancelar()
	{
            unset($this->s__datos_filtro);
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro_materias --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_materias(libro_unco_ei_cuadro $cuadro)
	{
            $this->s__datos_filtro['id_plan']['valor'] = $this->controlador->s__id_plan;
            if(isset($this->s__datos_filtro)){
                $datos = $this->controlador()->dep('datos')->tabla('materia')->get_listado($this->s__datos_filtro);
            }
            else{
                $datos = $this->controlador()->dep('datos')->tabla('materia')->get_listado();
            }
            print_r($datos);
            return $datos;
	}

	function evt__cuadro_materias__seleccion($seleccion)
	{
            $this->controlador()->dep('datos')->tabla('materia')->cargar($seleccion);
            $this->controlador->s__pantalla = 'pant_materias';
            $this->controlador()->set_pantalla('pant_materia');
	}
        
        function evt__cuadro_materias__agregar($datos)
	{
            $this->controlador->s__pantalla = 'pant_materias';
            $this->controlador()->set_pantalla('pant_materia');
	}

	function evt__cuadro_materias__eliminar($seleccion)
	{
            $sql = "delete from materia where id_materia = '".$seleccion['id_materia']."'";
            toba::db('libro_unco')->consultar($sql);
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
	//---- cuadro_secciones --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_secciones(libro_unco_ei_cuadro $cuadro)
	{
            $this->s__datos_filtro['id_plan']['valor'] = $this->controlador->s__id_plan;
            if(isset($this->s__datos_filtro)){
                $cuadro->set_datos($this->controlador()->dep('datos')->tabla('seccion')->get_listado($this->s__datos_filtro));
            }
            else{
                $cuadro->set_datos($this->controlador()->dep('datos')->tabla('seccion')->get_listado());
            }
	}

	function evt__cuadro_secciones__seleccion($seleccion)
	{
            $this->controlador()->dep('datos')->tabla('seccion')->cargar($seleccion);
            $this->controlador->s__pantalla = 'pant_descripcion';
            $this->controlador()->set_pantalla('pant_seccion');
	}
        
        function evt__cuadro_secciones__agregar($datos)
	{
            $this->controlador->s__pantalla = 'pant_descripcion';
            $this->controlador()->set_pantalla('pant_seccion');
	}

	function evt__cuadro_secciones__eliminar($seleccion)
	{
            $sql = "delete from seccion where id_seccion = '".$seleccion['id_seccion']."'";
            toba::db('libro_unco')->consultar($sql);
	}
        
        //-----------------------------------------------------------------------------------
	//---- ci --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        function conf(){
            if($this->controlador->s__pantalla != null){
                $this->set_pantalla($this->controlador->s__pantalla);
                $this->controlador->s__pantalla = null;
            }
        }
        
        //-----------------------------------------------------------------------------------
	//---- eventos del ci --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        function evt__guardar(){
            try{
                $this->controlador()->dep('datos')->tabla('plan_estudio')->sincronizar();
            }catch(toba_error_validacion $e){
                $this->set_pantalla('pant_plan');
            }
            try{
                $this->controlador()->dep('datos')->tabla('sede')->sincronizar();
            }catch(toba_error_validacion $e){
                $this->set_pantalla('pant_sede');
            }
            try{
                $this->controlador()->dep('datos')->tabla('obs_plan')->sincronizar();
            }catch(toba_error_validacion $e){
                $this->set_pantalla('pant_observacion');
            }
            try{
                $this->controlador()->dep('datos')->tabla('materia')->sincronizar();
            }catch(toba_error_validacion $e){
                
            }
            try{
                $this->controlador()->dep('datos')->tabla('obs_mat')->sincronizar();
            }catch(toba_error_validacion $e){
                
            }
            try{
                $this->controlador()->dep('datos')->tabla('modulo')->sincronizar();
            }catch(toba_error_validacion $e){
                
            }
            try{
                $this->controlador()->dep('datos')->tabla('obs_mod')->sincronizar();
            }catch(toba_error_validacion $e){
                
            }
        }
        
        function evt__cancelar(){
            if($this->s__nuevo_plan){
                    $sql = "delete from plan_estudio where id_plan = '".$this->controlador->s__id_plan."'";
                    toba::db('libro_unco')->consultar($sql);
            }

            $this->disparar_limpieza_memoria();
        }
        
        function evt__listo(){
            $this->controlador()->dep('datos')->resetear();
            $this->controlador()->set_pantalla('pant_planes');
        }
    
}



