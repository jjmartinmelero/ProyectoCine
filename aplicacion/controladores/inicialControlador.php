<?php

class inicialControlador extends CControlador{
 

//INDEX DE LA APLICACION WEB ***************************************************************
		public function accionIndex(){
		    
		    //Objeto pelicuas
		    $peliculas = new Peliculas();
		    
		    
		    //Obtenemos todas las peliculas que son tendencia para la pagina de inicio
		    $resultado = $peliculas->buscarTodos(array("where"=>"t.tendencia=1 and t.disponible=1 and t.fLanzamiento <= CURRENT_DATE"));
		    
		    
		    $totalTendencias = count($resultado);
		    
		    //Sistema::app()->sesion()->set("inicio", true);
		    
		    
			$this->dibujaVista("index",array("pTendencias"=>$resultado,"totalTendencias"=>$totalTendencias),"CINES MELERO");
			
			
		}//End index

		
		
		

		
		
}//End class
