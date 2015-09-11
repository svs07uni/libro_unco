<?php
/**
 * Esta clase fue y será generada automáticamente. NO EDITAR A MANO.
 * @ignore
 */
class libro_unco_autoload 
{
	static function existe_clase($nombre)
	{
		return isset(self::$clases[$nombre]);
	}

	static function cargar($nombre)
	{
		if (self::existe_clase($nombre)) { 
			 require_once(dirname(__FILE__) .'/'. self::$clases[$nombre]); 
		}
	}

	static protected $clases = array(
		'libro_unco_ci' => 'extension_toba/componentes/libro_unco_ci.php',
		'libro_unco_cn' => 'extension_toba/componentes/libro_unco_cn.php',
		'libro_unco_datos_relacion' => 'extension_toba/componentes/libro_unco_datos_relacion.php',
		'libro_unco_datos_tabla' => 'extension_toba/componentes/libro_unco_datos_tabla.php',
		'libro_unco_ei_arbol' => 'extension_toba/componentes/libro_unco_ei_arbol.php',
		'libro_unco_ei_archivos' => 'extension_toba/componentes/libro_unco_ei_archivos.php',
		'libro_unco_ei_calendario' => 'extension_toba/componentes/libro_unco_ei_calendario.php',
		'libro_unco_ei_codigo' => 'extension_toba/componentes/libro_unco_ei_codigo.php',
		'libro_unco_ei_cuadro' => 'extension_toba/componentes/libro_unco_ei_cuadro.php',
		'libro_unco_ei_esquema' => 'extension_toba/componentes/libro_unco_ei_esquema.php',
		'libro_unco_ei_filtro' => 'extension_toba/componentes/libro_unco_ei_filtro.php',
		'libro_unco_ei_firma' => 'extension_toba/componentes/libro_unco_ei_firma.php',
		'libro_unco_ei_formulario' => 'extension_toba/componentes/libro_unco_ei_formulario.php',
		'libro_unco_ei_formulario_ml' => 'extension_toba/componentes/libro_unco_ei_formulario_ml.php',
		'libro_unco_ei_grafico' => 'extension_toba/componentes/libro_unco_ei_grafico.php',
		'libro_unco_ei_mapa' => 'extension_toba/componentes/libro_unco_ei_mapa.php',
		'libro_unco_servicio_web' => 'extension_toba/componentes/libro_unco_servicio_web.php',
		'libro_unco_comando' => 'extension_toba/libro_unco_comando.php',
		'libro_unco_modelo' => 'extension_toba/libro_unco_modelo.php',
	);
}
?>