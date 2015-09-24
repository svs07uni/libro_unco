<?php
class ci_datos_plan extends ci_plan_de_estudio
{
    protected $s__id_sede;
    protected $s__nuevo_plan;
    protected $s__areas;
    
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
                    $pertenece = $this->controlador()->dep('datos')->tabla('pertenece')->get_listado($filtro);
                    foreach($pertenece as $clave => $valor){
                        $areas[$clave] = $valor['id_area'];
                    }
                    $this->s__areas = $areas;
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
	//---- form_sede -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_sede(libro_unco_ei_formulario $form){//Si ingresa a este form ent ya existe plan y está cargado
            
            $sede = $this->controlador()->dep('datos')->tabla('sede')->get();//print_r($sede);
            if(!isset($sede)){//No existe sede cargada en memoria
                //Se busca en la base de datos  
                $filtro['id_plan']['valor'] = $this->controlador->s__id_plan;
                $sede = $this->controlador()->dep('datos')->tabla('sede')->get_listado($filtro);
                if(count($sede)>0){//Existe en la BD
                    $sede = $sede[0];
                    $s['id_sede'] = $sede['id_sede'];
                    $this->controlador()->dep('datos')->tabla('sede')->cargar($s);
                }
            }
        
            if(isset($sede)){//Encontre en memoria o BD
                if(isset($sede['id_localidad'])){
                    $filtro['id_localidad']['valor'] = $sede['id_localidad'];
                    $localidad = $this->controlador()->dep('datos')->tabla('localidad')->get_listado($filtro);
                    $sede['id_provincia'] = $localidad[0]['id_provincia'];
                }
//$sede['maps']="<iframe src='https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d96159.42063655656!2d-71.3399145!3d-41.1349212!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses-419!2sar!4v1442344288208' width='200' height='200' frameborder='0' style='border:0' allowfullscreen></iframe>";
                return $sede;
            }
        }
                
	function evt__form_sede__guardar($datos)
	{
            $datos['id_plan'] = $this->controlador->s__id_plan;
            $this->controlador()->dep('datos')->tabla('sede')->set($datos);
            
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
            try{
                $this->controlador()->dep('datos')->tabla('sede')->sincronizar();
            }catch(toba_error_validacion $e){
                
            }
            try{
                $this->controlador()->dep('datos')->tabla('obs_plan')->sincronizar();
            }catch(toba_error_validacion $e){
                
            }
            
        }
        
        function evt__cancelar(){
            $this->controlador()->dep('datos')->tabla('plan_estudio')->resetear();
            $this->controlador()->dep('datos')->tabla('sede')->resetear();
            $this->controlador()->dep('datos')->tabla('obs_plan')->resetear();
        }
        
        function evt__listo(){
            $this->controlador()->dep('datos')->tabla('plan_estudio')->resetear();
            $this->controlador()->dep('datos')->tabla('sede')->resetear();
            $this->controlador()->dep('datos')->tabla('obs_plan')->resetear();
            $this->controlador->s__id_plan = null;
            $this->controlador()->set_pantalla('pant_planes');
        }
    
}



