<?php
class ci_plan_de_estudio extends toba_ci
{
    protected $s__datos_filtro;
    protected $s__id_plan;
    protected $s__sigla;
    protected $s__id_modulo;
    
    protected $s__pantalla; //Pantalla de retorno luego de guardar o cancelar algun formulario, ej. materia, seccion, modulo
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
            $this->s__sigla = $seleccion['sigla'];
            
            $this->dep('datos')->tabla('unidad_academica')->cargar($seleccion);
            $this->set_pantalla('pant_planes');
	}
        
        //-----------------------------------------------------------------------------------
	//---- pant_planes --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------
	//---- filtro_plan --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro_plan(libro_unco_ei_filtro $filtro)
	{
            if(isset($this->s__datos_filtro)){
                $filtro->set_datos($this->s__datos_filtro);
            }
	}

	function evt__filtro_plan__filtrar($datos)
	{
            $this->s__datos_filtro = $datos;
	}

	function evt__filtro_plan__cancelar()
	{
            unset($this->s__datos_filtro);
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro_planes --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_planes(libro_unco_ei_cuadro $cuadro)
	{
            $this->s__datos_filtro['sigla']['valor'] = $this->s__sigla;
            if(isset($this->s__datos_filtro)){
                $cuadro->set_datos($this->dep('datos')->tabla('plan_estudio')->get_listado($this->s__datos_filtro));
            }
            else{
                $cuadro->set_datos($this->dep('datos')->tabla('plan_estudio')->get_listado());
            }
            
	}

	function evt__cuadro_planes__seleccion($seleccion)
	{
            $this->s__id_plan = $seleccion['id_plan'];
            $this->dep('datos')->tabla('plan_estudio')->cargar($seleccion);
            $this->set_pantalla('pant_plan');
	}
        
        function evt__cuadro_planes__agregar($datos)
	{
            $this->set_pantalla('pant_plan');
	}

	function evt__cuadro_planes__eliminar($seleccion)
	{
            $sql = "delete from plan_estudio where id_plan = '".$seleccion['id_sector']."'";
            toba::db('libro_unco')->consultar($sql);
	}
        //-----------------------------------------------------------------------------------
	//---- pant_modulo -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------
	//---- form_modulo -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        function conf__form_modulo(libro_unco_ei_formulario $form)
	{
            $mod = $this->dep('datos')->tabla('modulo')->get();
            
            if(count($mod)>0){//Se selecciono un modulo
                $this->s__id_modulo = $mod['id_modulo'];
                $filtro['id_modulo']['valor'] = $mod['id_modulo'];
                $obs = $this->dep('datos')->tabla('obs_mod')->get_listado($filtro);
                if(count($obs)>0){//observacion existente
                    $mod['observacion'] = utf8_decode($obs[0]['descripcion']);
                    $ob['id_observacion'] = $obs[0]['id_observacion'];
                    $this->dep('datos')->tabla('obs_mod')->cargar($ob);
                    
                }
                $sql = "SELECT id_materia FROM se_encuentra WHERE id_modulo = ".$mod['id_modulo'];
                $mod['materias'] = toba::db('libro_unco')->consultar($sql);
                
                $filtro['id_modulo']['valor'] = $mod['id_modulo'];
                $un_mod = $this->dep('datos')->tabla('modulo')->get_listado($filtro);
                $mod['nombre'] = $un_mod[0]['nombre'];
                $mod['tipo_modulo'] = $un_mod[0]['tipo_modulo'];
                $mod['modulo_padre'] = $un_mod[0]['modulo_padre'];
                return $mod;
            }
	}

	function evt__form_modulo__guardar($datos)
	{
            $datos['id_plan'] = $this->s__id_plan;
            $this->dep('datos')->tabla('modulo')->set($datos);
            $mod = $this->dep('datos')->tabla('modulo')->get();
            
            if(!isset($mod['id_modulo'])){
                $this->dep('datos')->tabla('modulo')->sincronizar();
                $mod = $this->dep('datos')->tabla('modulo')->get();
                $this->s__id_modulo = $mod['id_modulo'];
            } 
            if(isset($datos['observacion'])){
                $obs['descripcion'] = utf8_encode($datos['observacion']);
                $obs['id_entidad'] = $this->s__id_modulo;
            
                $this->dep('datos')->tabla('obs_mod')->set($obs);
                $this->dep('datos')->tabla('obs_mod')->sincronizar(); 
                $this->dep('datos')->tabla('obs_mod')->resetear();
            }
            
            $this->dep('datos')->tabla('modulo')->resetear();
            $this->s__id_modulo = null;
            $this->set_pantalla('pant_plan');            
	}
        
        function evt__form_modulo__cancelar($datos){
            $this->dep('datos')->tabla('modulo')->resetear();
            $this->set_pantalla('pant_plan');
        }
        
        function get_modulos (){
            $and = "";
            if(isset($this->s__id_modulo))//Se seleccionó un modulo
                $and = " AND id_modulo <> ".$this->s__id_modulo;
            $sql = "SELECT id_modulo, nombre FROM modulo "
                . " WHERE id_plan = ". $this->s__id_plan
                .$and;
            return toba::db('libro_unco')->consultar($sql);
            
            
        }
        
        //para multiseleccion doble de materias
        function get_lista_materias(){
            $sql = "SELECT     t_m.id_materia, 
                                t_m.nombre 
                                
                  FROM     materia as t_m
                  JOIN plan_estudio as t_p
                  ON t_p.id_plan = t_m.id_plan
                  WHERE     t_p.id_plan = ".$this->s__id_plan;
            return toba::db()->consultar($sql);
            
        }
        
        //-----------------------------------------------------------------------------------
	//---- pant_materia -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------
	//---- form_materia -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        protected $s__id_materia;
	function conf__form_materia(libro_unco_ei_formulario $form)
	{
            if($this->dep('datos')->tabla('materia')->esta_cargada()){//Se seleccionó una materia
                $mat = $this->dep('datos')->tabla('materia')->get();
                $this->s__id_materia = $mat['id_materia'];
                $filtro['id_materia']['valor'] = $mat['id_materia'];
                $obs = $this->dep('datos')->tabla('obs_mat')->get_listado($filtro);
                if(count($obs)>0){
                    $mat['observacion'] = $obs[0];
                    $this->dep('datos')->tabla('obs_mat')->cargar($obs);
                }print_r($obs);
                $mat['optativa'] = '';
                //$mat['optativa'] = $mat['optativa']?'Si':'No';
                //$mat = $this->dep('datos')->tabla('materia')->get_listado($filtro);print_r($mat);
                return $mat;
                //print_r($mat);
            }
	}

	function evt__form_materia__guardar($datos)
	{
            $obs['descripcion'] = $datos['observacion'];
            $obs['id_entidad'] = $this->s__id_materia;
            $this->dep('datos')->tabla('obs_mat')->set($obs);
            $this->dep('datos')->tabla('obs_mat')->sincronizar();
            $datos['nombre'] = $datos['nombre'];
            $datos['id_plan'] = $this->s__id_plan;
            //$datos['optativa'] = (strcasecmp($datos['optativa'], "Si")==0)?1:0;
            $datos['optativa'] = 0;
            $this->dep('datos')->tabla('materia')->set($datos);
            $this->dep('datos')->tabla('materia')->sincronizar();
            $this->set_pantalla('pant_plan');
	}
        
        function evt__form_materia__cancelar($datos){
            $this->dep('datos')->tabla('materia')->resetear();
            $this->set_pantalla('pant_plan');
        }
        
        //-----------------------------------------------------------------------------------
	//---- pant_seccion -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------
	//---- form_seccion -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        function conf__form_seccion(libro_unco_ei_formulario $form)
	{
            $seccion = $this->dep('datos')->tabla('seccion')->get();//Se selecciono una seccion
            if(count($seccion)>0){//seccion existente
                $filtro['id_plan']['valor'] = $this->s__id_plan;
                $seccion = $this->dep('datos')->tabla('seccion')->get_listado($filtro);
                $seccion['contenido'] = utf8_decode($seccion_plan[0]['contenido']);
                $seccion['titulo'] = utf8_decode($seccion['titulo']);
                
                print_r($seccion);
                return $seccion;
            }
	}

	function evt__form_seccion__guardar($datos)
	{
            $datos['contenido'] = utf8_encode($datos['contenido']);
            $datos['id_plan'] = $this->controlador->s__id_plan;
            print_r($datos);
            $this->controlador()->dep('datos')->tabla('seccion_plan')->set($datos);
            $this->controlador()->dep('datos')->tabla('seccion')->set($datos);
            $this->set_pantalla('pant_plan');
	}
        
        function evt__form_seccion__cancelar($datos){
            $this->dep('datos')->tabla('seccion')->resetear();
            $this->set_pantalla('pant_plan');
        }

	function resetear()
	{
		$this->dep('datos')->resetear();
	}
        
        function evt__listo(){
            $this->set_pantalla('pant_ua');
        }

}

?>