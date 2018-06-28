<?php

	class CControlador
	{
		public $accionDefecto="index";
		public $plantilla="main";
		
		public function __construct()
		{
			;
		}
		
		public function ejecutar($accion)
		{
			$accion=strtolower($accion);
			//comprobar si se permite ejecutar la accion
			
			
			//busco el método para la acción	
			$_nombreFuncion='accion'.strtoupper(substr($accion,0,1)).
									substr($accion,1);
			
			
			if (!method_exists($this, $_nombreFuncion))
			    return false;
			
			//ejecuto la accion
			$this->$_nombreFuncion();
			
			return true;
		}
		
		public function dibujaVistaParcial($vista, $variables=array(),$devolver=false)
		{
			//existe la vista
			$_ruta=get_class($this);
			$_ruta=str_replace('Controlador', '', $_ruta);
			$_ruta=RUTA_BASE.'/aplicacion/vistas/'.$_ruta.'/'.$vista.'.php';
			if (!file_exists($_ruta))
			    return false;
			
			//definir las variables
			foreach($variables as $_var=>$_valor)
			{
				$$_var=$_valor;
			}
			//iniciar captura de salida
			ob_start();
			
			//incluir el fichero de la vista
			include($_ruta);
			//finalizar captura de salida
			$_salida=ob_get_contents();
			
			ob_end_clean();
			//operar segun $devolver
			
			if ($devolver)
			    return $_salida;
			   else
			   	{
			   		echo $_salida;
			   		return true;
				}
		}
		
		public function dibujaVista($vista, $variables=array(),$titulo="aplicacion")
		{
			//comprobamos si existe la plantilla
			$_ruta=RUTA_BASE.'/aplicacion/vistas/plantillas/'.
						$this->plantilla.'.php';
						
		    if (!file_exists($_ruta))
			     return false;
			
			//cargo la vista parcial
			$contenido=$this->dibujaVistaParcial($vista, $variables,true);
			
			//incluyo la plantilla
			include($_ruta);
			
		}
		
		
		
		
	}
