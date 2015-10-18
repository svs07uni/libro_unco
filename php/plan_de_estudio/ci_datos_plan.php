<?php
class ci_datos_plan extends ci_plan_de_estudio
{
    protected $s__id_sede;
    protected $s__nuevo_plan;
    protected $s__areas;
    protected $s__sedes;
    
    protected $s__lista_sedes;
    protected $s__sedes_seleccionados;
    protected $s__filtro_ua;
	
    
    protected $s__datos_filtro;
    
        
        //-----------------------------------------------------------------------------------
	//---- pant_datos -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------
	//---- form_cargo -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_plan(libro_unco_ei_formulario $form)
	{
            
            if(isset($this->controlador->s__id_plan)){//Existen datos de este plan
                $filtro['id_plan']['valor'] = $this->controlador->s__id_plan;
                $ar = $this->controlador()->dep('datos')->tabla('plan_estudio')->get();
                if(!isset($ar)){//No hay nada cargado en memoria ent buscar en BD, en el caso que se presionó cancelar
                    $plan = $this->controlador()->dep('datos')->tabla('plan_estudio')->get_listado($filtro);
                    if(count($plan)>0){
                        $ar = $plan[0];
                        $p['id_plan'] = $plan[0]['id_plan'];
                        $this->controlador()->dep('datos')->tabla('plan_estudio')->cargar($p);
                    } 
                    
                } 
                if(!isset($this->s__areas)){
                    $pertenece = $this->controlador()->dep('datos')->tabla('pertenece')->get_listado($filtro);
                    if(sizeof($pertenece)>0){
                        foreach($pertenece as $clave => $valor){
                            $areas[$clave] = $valor['id_area'];
                        }
                        $this->s__areas = $areas;
                    }
                }
                $ar['areas'] = $this->s__areas;
                
                return $ar;
            }
            else{//Es plan nuevo, oculto las pantallas y el evento guardar propio del controlador
                $this->pantalla()->tab('pant_sede')->ocultar();
                $this->pantalla()->tab('pant_observacion')->ocultar();
                $this->pantalla()->tab('pant_modulos')->ocultar();
                $this->pantalla()->tab('pant_materias')->ocultar();
                $this->pantalla()->tab('pant_descripcion')->ocultar();
                $this->evento('guardar')->ocultar();
                $this->evento('cancelar')->ocultar();
                $this->evento('listo')->ocultar();
            }            
	}

	function evt__form_plan__guardar($datos)
	{             
            $this->s__areas = $datos['areas'];
            $datos['nombre'] = strtoupper($datos['nombre']);
            $datos['titulo'] = strtoupper($datos['titulo']);
            $datos['iniciales_siu'] = strtoupper($datos['iniciales_siu']);
            $datos['id_unidad_academica'] = $this->controlador->s__sigla;
            $this->controlador()->dep('datos')->tabla('plan_estudio')->set($datos);
            $ar = $this->controlador()->dep('datos')->tabla('plan_estudio')->get();
            $this->controlador->s__id_plan = $ar['id_plan'];            
	}
        
        function evt__form_plan__crear($datos){
            $datos['id_unidad_academica'] = $this->controlador->s__sigla;
            $this->controlador()->dep('datos')->tabla('plan_estudio')->set($datos);
            $this->controlador()->dep('datos')->tabla('plan_estudio')->sincronizar();
            
            $ar = $this->controlador()->dep('datos')->tabla('plan_estudio')->get();
            $this->controlador->s__id_plan = $ar['id_plan'];
            
        }
        
        function evt__form_plan__cancelar(){
            $this->controlador()->dep('datos')->tabla('plan_estudio')->resetear();
            $this->controlador()->set_pantalla('pant_planes');
        }
        
        //-----------------------------------------------------------------------------------
	//---- pant_sede --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------
	//---- form_sede --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        function conf__form_sede(libro_unco_ei_formulario $form){
            
            if(isset($this->s__filtro_ua))
                $ua['id_unidad_academica'] =  $this->s__filtro_ua;
            else
                $ua['id_unidad_academica'] = $this->controlador->s__sigla;
            return $ua;
        }
        
        function evt__form_sede__filtrar($filtro){
            $this->s__filtro_ua = $filtro['id_unidad_academica'];
        }
        
        function evt__form_sede__guardar(){
            
        }
        //-----------------------------------------------------------------------------------
	//---- cuadro_sede --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        
        function conf__cuadro_sede(libro_unco_ei_cuadro $cuadro){
            if(isset($this->s__filtro_ua))
                $filtro['id_unidad_academica']['valor'] = $this->s__filtro_ua;
            else
                $filtro['id_unidad_academica']['valor'] = $this->controlador->s__sigla;
            
            //Obtiene todas las sedes de esta unidad academica
            $this->s__lista_sedes = $this->controlador()->dep('datos')->tabla('sede')->get_listado($filtro);
                       
            if(isset($this->s__sedes_seleccionados)){//Observo de forma local lo seleccionado, por si luego se presiona cancelar
                //solo se tienen los ids de las sedes, busco más informacion de estas sedes
                $sedes_seleccionadas = $this->controlador()->dep('datos')->tabla('sede')->listar_sedes($this->s__sedes_seleccionados);
                
                //Concatena las sedes, primero lo seleccionado y luego el resto
                $this->s__lista_sedes = array_merge($sedes_seleccionadas, $this->s__lista_sedes);
                
            }
            else{//Si no hay nada cargado de forma local busco en BD
                
                //Obtiene todas las sedes ya confirmadas que son sedes dictadas
                $sedes_dictado = $this->controlador()->dep('datos')->tabla('se_dicta')->listar_sedes_de_plan($this->controlador->s__id_plan);
                if(count($sedes_dictado)>0){
                    //Concatena las sedes, primero lo seleccionado y luego el resto
                    $this->s__lista_sedes = array_merge($sedes_dictado, $this->s__lista_sedes);
                }
            }
            $this->s__lista_sedes = $this->quitar_duplicados($this->s__lista_sedes);
            $cuadro->set_datos($this->s__lista_sedes);
            
        }
        
        function quitar_duplicados($array){
            $nuevo = array();
            for($i=0; $i<sizeof($array); $i++){
                if(!in_array($array[$i], $nuevo))
                        $nuevo[sizeof($nuevo)] = $array[$i];
            }
            return $nuevo;
        }
        
        function evt__cuadro_sede__dictado($sede){//Se activa cuando se cambia de pantalla
           $this->s__sedes_seleccionados = $sede;
           
        }
        
        function conf_evt__cuadro_sede__dictado(toba_evento_usuario $evento, $fila){
           //s__lista_sede son todas las sedes que se muestra en el cuadro
            //s__sedes_seleccionados son las sedes que ya fueron seleccionados
            if(isset($this->s__sedes_seleccionados)){//Ya existen sedes seleccionados
                $evento->set_check_activo($this->es_sede($this->s__lista_sedes[$fila]['id_sede'], $this->s__sedes_seleccionados));
                
            }
            else{//Recuperar de la BD
                $sedes = $this->controlador()->dep('datos')->tabla('se_dicta')->listar_sedes_de_plan($this->controlador->s__id_plan);
                $evento->set_check_activo($this->es_sede($this->s__lista_sedes[$fila]['id_sede'], $sedes));
                
            }
            
        }
        
        function es_sede($id_sede, $lista_sedes){
            foreach($lista_sedes as $clave => $valor){
                if($valor['id_sede'] == $id_sede)
                    return true;
            }
            return false;
        }
        
        //-----------------------------------------------------------------------------------
	//---- pant_observacion -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------
	//---- form_observacion -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        
	function conf__form_observacion(libro_unco_ei_formulario $form)
	{
            //Ya existe el plan cargado
            $obs = $this->controlador()->dep('datos')->tabla('obs_plan')->get();
            if(!isset($obs)){//No existe observación cargada en memoria
                //Se busca en la base de datos  
                $filtro['id_plan']['valor'] = $this->controlador->s__id_plan;
                $obs = $this->controlador()->dep('datos')->tabla('obs_plan')->get_listado($filtro);
                if(count($obs)>0){//Existe en la BD
                    $obs = $obs[0];
                    $o['id_observacion'] = $obs['id_observacion'];
                    $this->controlador()->dep('datos')->tabla('obs_plan')->cargar($o);
                }
            }
            if(count($obs)>0){//Existe en memoria
                return $obs;
            }
            
	}

	function evt__form_observacion__guardar($datos)
	{
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
            $cuadro->set_datos($this->controlador()->dep('datos')->tabla('modulo')->get_listado($this->s__datos_filtro));
            
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
            $this->controlador()->dep('datos')->tabla('modulo')->eliminar_modulo($seleccion['id_modulo']);
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
            for($i=0; $i<sizeof($datos); $i++){
                $datos[0]['optativa'] = $datos[0]['optativa']?"Sí":"No";               
            }
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
            $this->controlador()->dep('datos')->tabla('materia')->eliminar_materia($seleccion['id_materia']);
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
            $cuadro->set_datos($this->controlador()->dep('datos')->tabla('seccion')->get_listado($this->s__datos_filtro));
            
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
            $this->controlador()->tabla('seccion')->eliminar_seccion($seleccion['id_seccion']);
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
            try{//Actualización de datos entre plan_estudio y area
                $this->controlador()->dep('datos')->tabla('plan_estudio')->sincronizar();
                $sql = "DELETE FROM pertenece WHERE id_plan = ".$this->controlador->s__id_plan;
                toba::db('libro_unco')->consultar($sql);
                foreach($this->s__areas as $clave => $valor){
                    $pertenece = array();
                    $pertenece['id_plan'] = $this->controlador->s__id_plan;
                    $pertenece['id_area'] = $valor;
                    $this->controlador()->dep('datos')->tabla('pertenece')->set($pertenece);
                    $this->controlador()->dep('datos')->tabla('pertenece')->sincronizar();
                    $this->controlador()->dep('datos')->tabla('pertenece')->resetear();
                }
            
            }catch(toba_error_validacion $e){
                
            }
            try{//Actualizacion de datos entre sedes y plan_estudio
                $sql = "DELETE FROM se_dicta WHERE id_plan = ".$this->controlador->s__id_plan;
                toba::db('libro_unco')->consultar($sql);
                $dictados = $this->s__sedes_seleccionados;
                if(isset($dictados)){
                    foreach($dictados as $key => $sede){
                        $s_d['id_sede'] = $sede['id_sede'];
                        $s_d['id_plan'] = $this->controlador->s__id_plan;
                        $this->controlador()->dep('datos')->tabla('se_dicta')->set($s_d);
                        $this->controlador()->dep('datos')->tabla('se_dicta')->sincronizar();
                        $this->controlador()->dep('datos')->tabla('se_dicta')->resetear();
                    }
                }
                
            }catch(toba_error_validacion $e){
                
            }
            try{
                $this->controlador()->dep('datos')->tabla('obs_plan')->sincronizar();
            }catch(toba_error_validacion $e){
                
            }
            
            
        }
        
        function evt__cancelar(){
            $this->controlador()->dep('datos')->tabla('plan_estudio')->resetear();
            $this->controlador()->dep('datos')->tabla('obs_plan')->resetear();
            $this->controlador()->dep('datos')->tabla('se_dicta')->resetear();
            $this->s__areas = null;
        }
        
        function evt__listo(){
            $this->controlador()->dep('datos')->tabla('plan_estudio')->resetear();
            $this->controlador()->dep('datos')->tabla('obs_plan')->resetear();
            $this->controlador()->dep('datos')->tabla('se_dicta')->resetear();
            
            $this->controlador->s__id_plan = null;
            $this->s__areas = null;
            $this->s__filtro_ua = null;
            $this->s__lista_sedes = null;
            $this->s__sedes_seleccionados = null;
        
            $this->controlador()->set_pantalla('pant_planes');
        }
    
}



