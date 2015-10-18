<?php
class ci_plan_de_estudio extends toba_ci
{
    protected $s__datos_filtro;
    protected $s__id_plan;
    protected $s__sigla;
    protected $s__id_modulo;
    
    protected $s__pantalla; //Pantalla de retorno luego de guardar o cancelar algun formulario, ej. materia, seccion, modulo
        
        //-----------------------------------------------------------------------------------
	//---- exportacion  -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        
        function vista_excel(toba_vista_excel $salida){
            $salida->set_nombre_archivo($this->s__sigla.".xls");
            $excel = $salida->get_excel();
            $cabecera=false;//para determinar si escribir los nombre de campo en la fila 1
            $fila = 2;
            $columna = 65;//caracter A 
            //chr(66) devuelve letra de ascii
            $datos = $this->dep('datos')->tabla('plan_estudio')->listar_planes($this->s__sigla);
            //print_r($datos[1]);
            foreach($datos as $key => $valor){//Itera sobre los planes
                $salida->set_hoja_nombre('Planes');
                foreach($valor as $campo => $dato){//Itera sobre los campos de un plan
                    if(!$cabecera){//aun no se escribieron los nombres de campos
                        $celda = chr($columna).'1';
                        $excel->setActiveSheetIndex(0)->setCellValue($celda, mb_convert_encoding($campo,"UTF8"));//con utf8_decode no se visualiza bien
                    }
                    $celda = chr($columna).$fila;
                    $excel->setActiveSheetIndex(0)->setCellValue($celda, mb_convert_encoding($dato,"UTF8"));//con utf8_decode no se visualiza bien
                    $columna ++;
                }
                $fila ++;
                $columna = 65;
                $cabecera = true;
                
            }
            //Los vuelvo a obtener porque en la otra consulta traia las sedes entonces repetia registros para distintas sedes
            $datos = $this->dep('datos')->tabla('plan_estudio')->get_ids($this->s__sigla);
            $hoja=1;$cabecera = false;
            foreach($datos as $key => $valor){//Itera sobre los planes
                $nombre_hoja = is_null($valor['iniciales_siu'])? "Plan":$valor['iniciales_siu'];
                $salida->crear_hoja($nombre_hoja);
                $fila = 2;
                //Coloca el nombre de la carrera en fila 1
                $excel->setActiveSheetIndex($hoja)->setCellValue("A1", mb_convert_encoding($valor['nombre'],"UTF8"));//con utf8_decode no se visualiza bien
                //Coloca la/s descripcion/es del plan
                $filtro['id_plan']['valor'] = $valor['id_plan'];
                $descr = $this->dep('datos')->tabla('seccion')->get_listado($filtro);
                foreach($descr as $key => $una_descripcion){
                    $celda = 'A'.$fila;
                    $excel->setActiveSheetIndex($hoja)->setCellValue($celda, mb_convert_encoding($una_descripcion['titulo'],"UTF8"));//con utf8_decode no se visualiza bien
                    $celda = 'B'.$fila;
                    $excel->setActiveSheetIndex($hoja)->setCellValue($celda, mb_convert_encoding($una_descripcion['contenido'],"UTF8"));//con utf8_decode no se visualiza bien
                    
                    $fila++;
                }
                $fila+=2;//Separación extra entre descripciones y materias
                
                //Coloca las materias del plan, una por fila
                $datos = $this->dep('datos')->tabla('materia')->listar_materias($valor['id_plan']);
                foreach($datos as $key => $valor){//Itera sobre las materias
                    foreach($valor as $campo => $dato){//Itera sobre los campos de una materia
                        if(!$cabecera){//aun no se escribieron los nombres de campos
                            $celda = chr($columna).($fila-1);
                            $excel->setActiveSheetIndex($hoja)->setCellValue($celda, mb_convert_encoding($campo,"UTF8"));//con utf8_decode no se visualiza bien
                        }
                        $celda = chr($columna).$fila;
                        $excel->setActiveSheetIndex($hoja)->setCellValue($celda, mb_convert_encoding($dato,"UTF8"));//con utf8_decode no se visualiza bien
                        $columna ++;
                    }
                    $fila++;$columna=65;
                    $cabecera = true;
                }
                
                $fila++;
                //Coloca la observación del plan
                $obs = $this->dep('datos')->tabla('obs_plan')->get_listado($filtro);
                if(count($obs)>0){
                    $excel->setActiveSheetIndex($hoja)->setCellValue("A$fila", "Observaciones:");
                    $excel->setActiveSheetIndex($hoja)->setCellValue("B$fila", mb_convert_encoding($obs[0]['descripcion'],"UTF8"));
                }
                $cabecera = false;
                $hoja++;
            }
            
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
            $sigla = toba::memoria()->get_parametro('sigla');
            if(isset($sigla))
                $this->s__sigla = $sigla;
            $this->dep('cuadro_planes')->set_titulo($this->s__sigla);
            $this->s__datos_filtro['sigla']['valor'] = $this->s__sigla;
            $cuadro->set_datos($this->dep('datos')->tabla('plan_estudio')->get_listado($this->s__datos_filtro));
            
	}

	function evt__cuadro_planes__seleccion($seleccion)
	{
            $this->s__id_plan = $seleccion['id_plan'];
            $this->dep('datos')->tabla('plan_estudio')->cargar($seleccion);
            $this->s__datos_filtro=null;
            $this->set_pantalla('pant_plan');            
	}
        
        function evt__cuadro_planes__agregar($datos)
	{
            $this->s__datos_filtro=null;
            $this->set_pantalla('pant_plan');
	}

	function evt__cuadro_planes__eliminar($seleccion)
	{
            $this->dep('datos')->tabla('plan_estudio')->eliminar_plan($seleccion['id_plan']);
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
                    $mod['observacion'] = $obs[0]['descripcion'];
                    $ob['id_observacion'] = $obs[0]['id_observacion'];
                    $this->dep('datos')->tabla('obs_mod')->cargar($ob);
                    
                }
                $mat = $this->dep('datos')->tabla('se_encuentra')->listar_materias_de_modulo($this->s__id_modulo);
                
                $mod['materias'] = '';
                for($i=0; $i<sizeof($mat)-1; $i++){
                    $mod['materias'] .= $mat[$i]['id_materia'].',';
                }
                $mod['materias'] .= $mat[$i]['id_materia'];
                
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
                $obs['descripcion'] = $datos['observacion'];
                $obs['id_entidad'] = $this->s__id_modulo;
            
                $this->dep('datos')->tabla('obs_mod')->set($obs);
                $this->dep('datos')->tabla('obs_mod')->sincronizar(); 
                $this->dep('datos')->tabla('obs_mod')->resetear();
            }
            if(isset($datos['materias'])){//Guardar las materias 
                //Primero elimino todo lo cargado para este modulo 
                $this->dep('datos')->tabla('se_encuentra')->eliminar_materias_de_modulo($this->s__id_modulo);
                
                $mat = str_getcsv($datos['materias'], ",");//Convierto el string en materias seleccionadas en un array de id_materia
                // Se crea un objeto se_encuentra por cada materia seleccionada
                for($i=0; $i<sizeof($mat); $i++){
                    $s_e[$i]['id_materia'] = intval($mat[$i]);//Convierto el string a integer
                    $s_e[$i]['id_modulo'] = $this->s__id_modulo;
                    $this->dep('datos')->tabla('se_encuentra')->nueva_fila($s_e[$i]);
                    $this->dep('datos')->tabla('se_encuentra')->sincronizar();
                    $this->dep('datos')->tabla('se_encuentra')->resetear();
                }            
            }
            $this->dep('datos')->tabla('modulo')->sincronizar();
            $this->dep('datos')->tabla('modulo')->resetear();
            $this->s__id_modulo = null;
            $this->set_pantalla('pant_plan');            
	}
        
        function evt__form_modulo__cancelar($datos){
            $this->dep('datos')->tabla('modulo')->resetear();
            $this->set_pantalla('pant_plan');
        }
        
        function get_modulos (){
            return $this->dep('datos')->tabla('modulo')->listar_modulos_distinto_de($this->s__id_plan,$this->s__id_modulo);
                       
        }
        
        //para multiseleccion doble de materias
        function get_lista_materias(){
            $ar = $this->dep('datos')->tabla('materia')->listado_materias_de($this->s__id_plan);
            for($i=0; $i<sizeof($ar); $i++){
                $ar[$i]['nombre'] = (($ar[$i]['orden']!=null)?$ar[$i]['orden']:"").". ".$ar[$i]['nombre'];
            }
            return $ar;
        }
        
        //-----------------------------------------------------------------------------------
	//---- pant_materia -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------
	//---- form_materia -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        protected $s__id_materia;
	function conf__form_materia(toba_ei_formulario $form)
	{
            if($this->dep('datos')->tabla('materia')->esta_cargada()){//Se seleccionó una materia
                $mat = $this->dep('datos')->tabla('materia')->get();
                $this->s__id_materia = $mat['id_materia'];
                $filtro['id_materia']['valor'] = $mat['id_materia'];
                $obs = $this->dep('datos')->tabla('obs_mat')->get_listado($filtro);//print_r(count($obs));exit();
                if(sizeof($obs)>0){
                    $mat['una_observacion'] = $obs[0]['descripcion'];
                    $o['id_observacion'] = $obs[0]['id_observacion'];
                    $this->dep('datos')->tabla('obs_mat')->cargar($o);
                }
                if(isset($mat['optativa']))
                    $mat['optativa'] = $mat['optativa']?"Sí":"No";
                $mat['anio_cursado'] = floor($mat['cuatri_inicio'] / 2);
                $mat['cuatri'] = ($mat['cuatri_inicio'] % 2 == 0)?"2° Cuatrimestre":"1° Cuatrimestre";
                
                return $mat;                
            }
            else{
                $o = $this->dep('datos')->tabla('materia')->get_max_orden($this->s__id_plan);
                if(count($o)>0){
                    $mat['orden'] = $o[0]['orden']+1;
                    return $mat;
                }
                    
            }
	}
        protected $s__nro_materia;
	function evt__form_materia__guardar($datos)
	{
            $datos['id_plan'] = $this->s__id_plan;
            $datos['optativa'] = ($datos['optativa']=='Sí')?true:false;
            $datos['nombre'] = strtoupper($datos['nombre']);
            
            $this->dep('datos')->tabla('materia')->set($datos);
            $this->dep('datos')->tabla('materia')->sincronizar();
            
            $mat = $this->dep('datos')->tabla('materia')->get();
            $this->s__id_materia = $mat['id_materia'];
            
            if(isset($datos['una_observacion'])){//Tiene alguna observacion
                $obs['descripcion'] = $datos['una_observacion'];            
                $obs['id_entidad'] = $this->s__id_materia;
                $this->dep('datos')->tabla('obs_mat')->set($obs);
                $this->dep('datos')->tabla('obs_mat')->sincronizar();
                $this->dep('datos')->tabla('obs_mat')->resetear();
            }  
            //$this->s__nro_materia = $datos['orden'];
            $this->dep('datos')->tabla('materia')->resetear();
            $this->set_pantalla('pant_plan');
	}
        
        function evt__form_materia__cancelar($datos){
            $this->dep('datos')->tabla('materia')->resetear();
            $this->set_pantalla('pant_plan');
        }
        
        //Para combo editable de observacion
        function get_observaciones_mat(){
            $filtro['id_materia']['valor'] = -1;
            if($this->dep('datos')->tabla('materia')->esta_cargada())
                $filtro['id_materia']['valor'] = $this->s__id_materia;
            
            return $this->dep('datos')->tabla('obs_mat')->get_listado($filtro);
        }

        
        //-----------------------------------------------------------------------------------
	//---- pant_seccion -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------
	//---- form_seccion -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
        function conf__form_seccion(libro_unco_ei_formulario $form)
	{
            if($this->dep('datos')->tabla('seccion')->esta_cargada()){//seccion existente
                $seccion = $this->dep('datos')->tabla('seccion')->get();//Se selecciono una seccion
                $seccion['id_titulo_seccion'] = $seccion['id_titulo'];
                return $seccion;
            }
	}

	function evt__form_seccion__guardar($datos)
	{
            $datos['id_plan'] = $this->s__id_plan;
            $datos['id_titulo'] = $datos['id_titulo_seccion'];
            $this->dep('datos')->tabla('seccion')->set($datos);
            $this->dep('datos')->tabla('seccion')->sincronizar();
            $this->dep('datos')->tabla('seccion')->resetear();
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
            $this->s__datos_filtro = null;
            
        }

}

?>