<?php

	class CValidaciones
	{
		
			
		/**
		 * Permite validar un DNI obteniendo sus partes
		 * Los formatos correctos son A9999999A o 99999999A
		 * 
		 * @param string $dni  Dni a analizar
		 * @param array $partes  Partes analizadas
		 * @return boolean Devuelve true si es correcto y false 
		 * en cualquier otro caso
		 */
		public static function validaDNI($dni, &$partes)
		{
			$expre="/^(?|([a-z]\d{7})|(\d{1,8}))([a-z])$/i";
			
			$dni=trim($dni);
			$dni=strtoupper($dni);
			
			
			if (preg_match($expre, $dni,$partes))
			     {
			     	if (is_numeric($partes[1]))
						{
							$partes[1]=substr("00000000".$partes[1], -8);
							$partes[0]=$partes[1].$partes[2];
						}
			     	return true;
			     }
					
			return false;
		}
		
		/**
		 * Permite comprobar si un numero esta dentro de un rango dado. Si no esta 
		 * se inicializa el numero con el valor por defecto
		 * 
		 * @param $numero integer Numero a analizar
		 * @param $valorDefecto integer
		 * @param $min integer
		 * @param $max integer
		 * @return boolean true si es valido false en cualquier otro caso
		 */
		public static function validaEntero(&$numero,$valorDefecto, $min, $max)
		{
			$numero=intval($numero);
			
			if (($numero>=$min) && ($numero<=$max))
			     return true;
				else
					{
						$numero=$valorDefecto;
						return false;
					}
			
		}
		
		
		
		public static function validaReal(&$numero,$valorDefecto, $min, $max)
		{
			$numero=floatval($numero);
			
			if (($numero>=$min) && ($numero<=$max))
			     return true;
				else
					{
						$numero=$valorDefecto;
						return false;
					}
			
		}
		
		public static function validaCodPostal(&$codpostal)
		{
			$codpostal=trim($codpostal);
			if (!is_numeric($codpostal))
			{
				$codpostal='00000';
				return false;
			}
			$expresion="/^(\d{1,2})(\d{3})$/";
			$partes=array();
			if (!preg_match($expresion, $codpostal,$partes))
			{
				$codpostal='00000';
				return false;
			}
			
			if (($partes[1]<1) || ($partes[1]>52))
			{
				$codpostal='00000';
				return false;
			}
			$codpostal=substr("00000".$partes[0], -5);
			return true;
		}
		
		public static function validaEMail(&$email,$valorDefecto="aa@aaa.es")
		{
			$email=trim ($email);
			$expresion="/^[[:alnum:]\-_]+(\.[[:alnum:]\-_]+)*@[[:alnum:]\-_]+(\.[[:alnum:]\-_]+)*\.[a-z]{2,4}$/i";
			$partes=array();
			if (!preg_match($expresion, $email,$partes))
			{
				$email=$valorDefecto;
				return false;
			}
			
			$email=$partes[0];
			return true;
		}
		
		public static function validaLista(&$elemento,$lista)
		{
			$encontrado=false;
			$elemento=trim($elemento);
			
			foreach ($lista as $valor) 
			{
				if ($elemento==$valor)
				{
					$encontrado=true;
				}	
				
			}
			return $encontrado;
		}
		
		public static function validaFecha(&$fecha)
		{
			$fecha=trim($fecha);
			$expresion="/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/";
			$partes=array();
			if (!preg_match($expresion, $fecha,$partes))
			{
				return false;
			}
			
			$aux=mktime(0,0,0,$partes[2],$partes[1],$partes[3]);
			if (!$aux)
			    return false;
			
			if ($partes[1]!=date('d',$aux) ||
			    $partes[2]!=date('m',$aux) ||
			    $partes[3]!=date('Y',$aux))
			   {
			   	return false;
			   }	
			$fecha=substr("00".$partes[1],-2)."/".
					substr("00".$partes[2],-2)."/".
					substr("0000".$partes[3],-4);
			return true;
		}
		
		public static function validaHora(&$hora)
		{
			$hora=trim($hora);
			$expresion="/^(\d{1,2}):(\d{1,2}):(\d{1,2})$/";
			$partes=array();
			if (!preg_match($expresion, $hora,$partes))
			{
				return false;
			}
			
		    $aux=mktime($partes[1],$partes[2],$partes[3]);
			if ($partes[1]!=date('H',$aux) ||
			    $partes[2]!=date('i',$aux) ||
			    $partes[3]!=date('s',$aux))
			   {
			   	return false;
			   }	
			$hora=substr("00".$partes[1],-2)."/".
					substr("00".$partes[2],-2)."/".
					substr("002".$partes[3],-2);
			return true;
			
			
			
		}
	}
