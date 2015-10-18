<?php
class ci_cargo extends ci_cargos
{
    protected $s__datos_filtro;
    
        function conf(){
            if(isset($this->controlador->s__pantalla)){
                $this->set_pantalla($this->controlador->s__pantalla);
                $this->controlador->s__pantalla = null;
            }
        }

//-----------------------------------------------------------------------------------
	//---- pant_dato_cargo -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------
	//---- form_cargo -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_cargo(libro_unco_ei_formulario $form)
	{
            if (isset($this->controlador->s__id_sector)) {
                $ar = $this->controlador()->dep('datos')->tabla('sector')->get();
                if(!isset($ar)){//No está en memoria, busco en BD
                    $this->s__datos_filtro['id_sector']['valor'] = $this->controlador->s__id_sector;
                    $cargo = $this->controlador()->dep('datos')->tabla('sector')->get_listado($this->s__datos_filtro);
                    if(count($cargo)>0){
                        $ar = $cargo[0];
                        $c['id_sector'] = $this->controlador->s__id_sector;
                        $this->controlador()->dep('datos')->tabla('sector')->cargar($c);
                    }
                }
                return $ar;
		}
            else{
                $this->pantalla()->tab('pant_personal_cargo')->ocultar();
                $this->pantalla()->tab('pant_descripcion')->ocultar();
                $this->evento('guardar')->ocultar();
                $this->evento('cancelar')->ocultar();
            }
                
	}

	function evt__form_cargo__guardar($datos)
	{
            $datos['id_unidad_academica'] = $this->controlador->s__sigla;
            $this->controlador()->dep('datos')->tabla('sector')->set($datos);
            $ar = $this->controlador()->dep('datos')->tabla('sector')->get();
            $this->controlador->s__id_sector = $ar['id_sector'];
        }

	function evt__form_cargo__cancelar()
	{
            $this->controlador()->dep('datos')->tabla('sector')->resetear();
            $this->set_pantalla('pant_dato_cargo');
	}
        
        function evt__form_cargo__crear($datos){
            $datos['id_unidad_academica'] = $this->controlador->s__sigla;
            $this->controlador()->dep('datos')->tabla('sector')->set($datos);
            $this->controlador()->dep('datos')->tabla('sector')->sincronizar();
            
            $ar = $this->controlador()->dep('datos')->tabla('sector')->get();
            $this->controlador->s__id_sector = $ar['id_sector'];
        }
        
        //-----------------------------------------------------------------------------------
	//---- pant_personal_cargo --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------
	//---- form_persona -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_persona(libro_unco_ei_formulario $form)
	{
            $persona = $this->controlador()->dep('datos')->tabla('persona')->get();
            if(!isset($persona)){//Primero verifico si hay datos en memoria
                $filtro['id_sector']['valor'] = $this->controlador->s__id_sector;
                $ar = $this->controlador()->dep('datos')->tabla('persona')->get_listado($filtro);
                if(count($ar)>0){
                    $persona = $ar[0];
                    $p['tipo_doc'] = $ar[0]['tipo_doc'];
                    $p['nro_doc'] = $ar[0]['nro_doc'];
                    $this->controlador()->dep('datos')->tabla('persona')->cargar($p);
                    
                }
            }
            if(isset($persona)){
                if(isset($persona['genero']))
                    $persona['genero'] = ($persona['genero'])?'Femenino':'Masculino';
                if(isset($persona['tipo_doc']))
                    $persona['tipo_doc'] = ($persona['tipo_doc']=='dni')?'DNI':'Pasaporte';
                return $persona;
            }
            
	}
                
	function evt__form_persona__guardar($datos)
	{
            $datos['id_sector'] = $this->controlador->s__id_sector;
            $datos['genero'] = ($datos['genero']=='Femenino')?true:false;
            $datos['tipo_doc'] = ($datos['tipo_doc']=='DNI')?'dni':'pas';
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
	//---- form_descripcion --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        function conf__form_descripcion(libro_unco_ei_formulario $form){
            $seccion = $this->controlador()->dep('datos')->tabla('seccion')->get();
            if(!isset($seccion)){//No existe observación cargada en memoria
                //Se busca en la base de datos  
                $filtro['id_sector']['valor'] = $this->controlador->s__id_sector;
                $sec = $this->controlador()->dep('datos')->tabla('seccion')->get_listado($filtro);
                if(count($sec)>0){//Existe en la BD
                    $seccion = $sec[0];
                    $s['id_seccion'] = $seccion['id_seccion'];
                    $this->controlador()->dep('datos')->tabla('seccion')->cargar($s);
                }
            }
            if(count($seccion)>0){//Existe en memoria
                return $seccion;
            }
        }
        
        function evt__form_descripcion__guardar($datos){
            $datos['id_sector'] = $this->controlador->s__id_sector;
            $this->controlador()->dep('datos')->tabla('seccion')->set($datos);
            
        }
        
	
        
        function evt__guardar(){
            try{//Sincronizar form_persona
                $this->controlador()->dep('datos')->tabla('sector')->sincronizar();
            }catch(toba_error_validacion $e){
                
            }
            
            try{//Sincronizar form_persona
                $this->controlador()->dep('datos')->tabla('persona')->sincronizar();
            }catch(toba_error_validacion $e){
                
            }
            try{//Sincronizar form_persona
                $this->controlador()->dep('datos')->tabla('seccion')->sincronizar();
            }catch(toba_error_validacion $e){
                
            }         
        }
        
        function evt__cancelar(){
            $this->controlador()->dep('datos')->tabla('sector')->resetear();
            $this->controlador()->dep('datos')->tabla('persona')->resetear();
            $this->controlador()->dep('datos')->tabla('seccion')->resetear();
        }
        
        function evt__listo(){
            $this->controlador()->dep('datos')->tabla('sector')->resetear();
            $this->controlador->s__id_sector = null;
            $this->controlador()->dep('datos')->tabla('persona')->resetear();
            $this->controlador()->dep('datos')->tabla('seccion')->resetear();
            $this->controlador()->set_pantalla('pant_cargos');
        }
    
}

