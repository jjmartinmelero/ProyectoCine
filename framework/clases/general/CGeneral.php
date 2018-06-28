<?php


	class CGeneral{
		
		public static function fechaMysqlANormal($fecha)
		{
			$fechaAux=explode("/",$fecha);
			if (count($fechaAux)==3)
			    return $fecha;
			
			$fecha=explode("-", $fecha);
			$fecha=date('d/m/Y',mktime(0,0,0,$fecha[1],$fecha[2],$fecha[0]));
			
			return $fecha;
		}

		public static function fechaNormalAMysql($fecha)
		{
			$fechaAux=explode("-",$fecha);
			if (count($fechaAux)==3)
			    return $fecha;
			
			$fecha=explode("/", $fecha);
			$fecha=date('Y-m-d',mktime(0,0,0,$fecha[1],$fecha[0],$fecha[2]));
			
			return $fecha;
			
		}

		
		/**
		 * Permite escapar una cadena . Los caracteres que se 
		 * escapan son '
		 * 
		 * @param string $cadena
		 * @return string
		 * 
		 */
		public static function addSlashes($cadena)
		{
			return str_replace("'", "''", $cadena);
		}
		
		
		/**
		 * Elimina el escape para una cadena 
		 * 
		 * @param string $cadena 
		 * @return string
		 * 
		 */
		public static function stripSlashes($cadena)
		{
			return str_replace("''", "'", $cadena);
		}
		
		
		
	}
