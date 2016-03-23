<?php

class ci_materia extends ci_plan_de_estudio{
    
    protected $s__id_materia;
    protected $s__nro_materia;
    
    function conf(){
        $this->pantalla()->tab('pant_correlativas')->ocultar();
    }
    //-----------------------------------------------------------------------------------
    //---- pant_datos -------------------------------------------------------------------
    //-----------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------
    //---- form_materia -------------------------------------------------------------------
    //-----------------------------------------------------------------------------------
        
	function conf__form_materia(toba_ei_formulario $form)
	{
            if($this->controlador()->dep('datos')->tabla('materia')->esta_cargada()){//Se seleccionó una materia
                $mat = $this->controlador()->dep('datos')->tabla('materia')->get();
                $this->s__id_materia = $mat['id_materia'];
                $filtro['id_materia']['valor'] = $mat['id_materia'];
                $obs = $this->controlador()->dep('datos')->tabla('obs_mat')->get_listado($filtro);//print_r(count($obs));exit();
                
                if(sizeof($obs)>0){
                    $mat['una_observacion'] = $obs[0]['descripcion'];
                    $o['id_observacion'] = $obs[0]['id_observacion'];
                    $this->controlador()->dep('datos')->tabla('obs_mat')->cargar($o);
                }
                if(isset($mat['optativa']))
                    $mat['optativa'] = $mat['optativa']?"Sí":"No";
                $mat['anio_cursado'] = floor($mat['cuatri_inicio'] / 2);
                $mat['cuatri'] = ($mat['cuatri_inicio'] % 2 == 0)?"2° Cuatrimestre":"1° Cuatrimestre";
                
                return $mat;                
            }
            else{
                $o = $this->controlador()->dep('datos')->tabla('materia')->get_max_orden($this->s__id_plan);
                if(count($o)>0){
                    $mat['orden'] = $o[0]['orden']+1;
                    return $mat;
                }
                    
            }
	}
        
	function evt__form_materia__guardar($datos)
	{
            $datos['id_plan'] = $this->controlador->s__id_plan;
            $datos['optativa'] = ($datos['optativa']=='Sí')?true:false;
            $datos['nombre'] = strtoupper($datos['nombre']);
            
            $this->controlador()->dep('datos')->tabla('materia')->set($datos);
                        
            $mat = $this->controlador()->dep('datos')->tabla('materia')->get();
            $this->s__id_materia = $mat['id_materia'];
            
            if(isset($datos['una_observacion'])){//Tiene alguna observacion
                $obs['descripcion'] = $datos['una_observacion'];            
                $obs['id_entidad'] = $this->s__id_materia;
                $this->controlador()->dep('datos')->tabla('obs_mat')->set($obs);
                $this->controlador()->dep('datos')->tabla('obs_mat')->sincronizar();
                $this->controlador()->dep('datos')->tabla('obs_mat')->resetear();
            }  
            $this->s__nro_materia = $datos['orden'];
            
            $this->controlador()->dep('datos')->tabla('materia')->sincronizar();
        
        
        $this->controlador()->dep('datos')->tabla('materia')->resetear();
            
            $this->controlador()->set_pantalla('pant_plan');
            
	}
        
        function evt__form_materia__cancelar(){
            $this->controlador()->dep('datos')->tabla('materia')->resetear();
            $this->controlador()->dep('datos')->tabla('obs_mat')->resetear();
            $this->controlador()->set_pantalla('pant_plan');
        }
        
       
    //-----------------------------------------------------------------------------------
    //---- form_ml_aprobadas-------------------------------------------------------------
    //-----------------------------------------------------------------------------------
      function conf__form_ml_aprobadas(libro_unco_ei_formulario_ml $form){
          
      }
        
    //-----------------------------------------------------------------------------------
    //---- form_ml_cursar-------------------------------------------------------------
    //-----------------------------------------------------------------------------------
      function conf__form_ml_cursar(libro_unco_ei_formulario_ml $form){
          
      } 
        

    //Metodo para combo editable
        function get_materias($filtro = null){
            //obtengo todas las materias del plan que contengan una frase, traido en filtro, en su nombre
            $ar = $this->controlador()->dep('datos')->tabla('materia')->get_nombres($filtro, $this->controlador->s__id_plan);
            
            return $ar;
            
        }
        
    //-----------------------------------------------------------------------------------
    //---- controlador -------------------------------------------------------------
    //-----------------------------------------------------------------------------------
    function evt__guardar(){
        $this->controlador()->dep('datos')->tabla('materia')->sincronizar();
        
        $this->controlador()->dep('datos')->tabla('obs_mat')->sincronizar();
        $this->controlador()->dep('datos')->tabla('materia')->resetear();
            $this->controlador()->dep('datos')->tabla('obs_mat')->resetear();
        $this->controlador()->set_pantalla('pant_plan');
        
    }
    
    function evt__cancelar(){
            $this->controlador()->dep('datos')->tabla('materia')->resetear();
            $this->controlador()->dep('datos')->tabla('obs_mat')->resetear();
            $this->controlador()->set_pantalla('pant_plan');
        }
        
    function evt__listo() {
        $this->controlador()->dep('datos')->tabla('materia')->resetear();
        $this->controlador()->dep('datos')->tabla('obs_mat')->resetear();
        
        $this->controlador()->set_pantalla('pant_plan');
    }
 
}

?>
