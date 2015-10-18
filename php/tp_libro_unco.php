<?php
/**
* 
* Incluye una barra con nombre y ayuda contextual de la operación, 
* y centraliza el contenido de la salida de la operación
* 
* @package SalidaGrafica
*/
class tp_encuesta extends toba_tp_basico
{
	protected $clase_encabezado = 'encabezado';	

	function barra_superior()
	{
		echo "<div align=center>";
		echo "Acá va el logo!";	
		echo "</div>";
		echo "\n\n";
	}
	
		
	
		
		
	function pre_contenido()
	{
		echo "\n<div align='center' class='cuerpo'>Esto es el pre contendio\n";		

        }
	function post_contenido()
	{
		echo "</div>";		
		echo "<div class='login-pie'>";
		echo "<div>Desarrollado por <strong>SE</a></strong></div>
			<div>2015-".date('Y')."</div>";
		echo "</div>";
	}
        
        function pie(){
            echo "<div align=center>Esto es el pie</div>";
        }
			
}
?>
