<?php
	/**
	 * Clase base para los modelos. Abstrae el comportamiento de 
	 * un archivo/tabla/objeto real.
	 * 
	 */
	class CActiveRecord implements Iterator
	{
		private  $_nombre="";
		protected  $_esNuevo=true;
		
		//soporte para base de datos
		private  $_tabla="";
		private  $_id=""; 
		
		
		/**
		 * lista de atributos del objeto (campo)
		 */
		protected $_atributos=array(); 
		/**
		 * lista de las restricciones que debe cumplir cada 
		 * atributo. Por ejemplo que sea cadena, entero, etc
		 */
		protected $_restricciones=array();
		/**
		 * descripcion de cada atributo del objeto
		 */
		protected $_descripciones=array();
		/**
		 * Mensajes de error por campo tras una validacion
		 */
		protected $_errores=array();
		
		//indice usado al iterar 
		private $_indiceIter=0;
		
		public function __construct()
		{
			$this->_nombre=$this->fijarNombre();
			$this->_tabla=$this->fijarTabla();
			$this->_id=$this->fijarId();
			$this->inicializarAtributos();
			$this->inicializarDescripciones();
			$this->inicializarRestricciones();
			
			$this->_esNuevo=true;
			
			$this->afterCreate();
		}


		protected function fijarNombre()
		{
			return "";
		}
		
		private function inicializarAtributos()
		{
			
			//lo primero es obtener los campos para trabajar e inicializar
			//las variables del objeto
			foreach ($this->fijarAtributos() as $nombreCampo) 
			{
				$nombreCampo=$this->ajustarNombreCampo($nombreCampo);
				//añado el campo
				$this->_atributos[$nombreCampo]='';
				
				//añado una descripcion por defecto
				$this->_descripciones[$nombreCampo]=$nombreCampo;		
			}
			
			return true;
		}
		
		private function inicializarDescripciones()
		{
			//recogemos las descripciones de los campos que se hayan puesto
			foreach ($this->fijarDescripciones() as $campo => $valor) {
					
				$campo=$this->ajustarNombreCampo($campo);
				
				//compruebo si existe el campo
				if (isset($this->_atributos[$campo]))
				{
					$this->_descripciones[$campo]=$valor;
				}
			}
			
			return true;
		}
		
		private function inicializarRestricciones()
		{
			foreach($this->fijarRestricciones() as $resOriginal)
			{
				$restriccion=array();
				$valida=false;
				
				if (!isset($resOriginal["TIPO"]))
				    $resOriginal["TIPO"]="CADENA";
				
				
				switch (strtolower($resOriginal["TIPO"]))
				{
					case "requerido":
							$restriccion["TIPO"]=strtolower($resOriginal["TIPO"]);
							if (!isset($resOriginal["MENSAJE"]))
							      $restriccion["MENSAJE"]="El campo no puede estar vacio";
								else
								  $restriccion["MENSAJE"]=$resOriginal["MENSAJE"];
							$valida=true;							
							
							break;	
					case "cadena":
							$restriccion["TIPO"]="cadena";
							if (!isset($resOriginal["MENSAJE"]))
							      $restriccion["MENSAJE"]="La cadena no es valida";
								else
								  $restriccion["MENSAJE"]=$resOriginal["MENSAJE"];
							
							if (!isset($resOriginal["TAMANIO"]))
							      $restriccion["TAMANIO"]=30;
								else
								  $restriccion["TAMANIO"]=intval($resOriginal["TAMANIO"]);
							
							$valida=true;							
							break;

					case "entero":
					case "real":
						 	$restriccion["TIPO"]=strtolower($resOriginal["TIPO"]);
							if (!isset($resOriginal["MENSAJE"]))
							      $restriccion["MENSAJE"]="Numero no valido";
								else
								  $restriccion["MENSAJE"]=$resOriginal["MENSAJE"];
								
							if (!isset($resOriginal["MIN"]))
							      $restriccion["MIN"]=-10000;
								else
								  $restriccion["MIN"]=intval($resOriginal["MIN"]);

							if (!isset($resOriginal["MAX"]))
							      $restriccion["MAX"]=10000;
								else
								  $restriccion["MAX"]=intval($resOriginal["MAX"]);

							if (!isset($resOriginal["DEFECTO"]))
							      $restriccion["DEFECTO"]=10000;
								else
								  $restriccion["DEFECTO"]=floatval($resOriginal["DEFECTO"]);
							$valida=true;							
							break;

					case "fecha":
						 	$restriccion["TIPO"]=strtolower($resOriginal["TIPO"]);
							if (!isset($resOriginal["MENSAJE"]))
							      $restriccion["MENSAJE"]="Fecha no valida";
								else
								  $restriccion["MENSAJE"]=$resOriginal["MENSAJE"];
								  
							$valida=true;							
							break;
					case "hora":
						 	$restriccion["TIPO"]=strtolower($resOriginal["TIPO"]);
							if (!isset($resOriginal["MENSAJE"]))
							      $restriccion["MENSAJE"]="Hora no valida";
								else
								  $restriccion["MENSAJE"]=$resOriginal["MENSAJE"];
								
							$valida=true;							
							break;
					case "email":
						 	$restriccion["TIPO"]=strtolower($resOriginal["TIPO"]);
							if (!isset($resOriginal["MENSAJE"]))
							      $restriccion["MENSAJE"]="Direccion de correo no v&aacute;lida";
								else
								  $restriccion["MENSAJE"]=$resOriginal["MENSAJE"];
								
							if (!isset($resOriginal["DEFECTO"]))
							      $restriccion["DEFECTO"]="aa@aaa.es";
								else
								  $restriccion["DEFECTO"]=$resOriginal["DEFECTO"];
							if (!isset($resOriginal["VACIO"]))
							      $restriccion["VACIO"]=false;
								else
								  $restriccion["VACIO"]=($resOriginal["VACIO"]==true);
							
							$valida=true;							
							break;

					case "rango":
						 	$restriccion["TIPO"]=strtolower($resOriginal["TIPO"]);
							if (!isset($resOriginal["MENSAJE"]))
							      $restriccion["MENSAJE"]="El campo no es valido";
								else
								  $restriccion["MENSAJE"]=$resOriginal["MENSAJE"];
							
							if (isset($resOriginal["RANGO"]) && is_array($resOriginal["RANGO"]))
								  $restriccion["RANGO"]=$resOriginal["RANGO"];
							    else
							      $restriccion["RANGO"]=array();
							
							$valida=true;							
							break;

					case "funcion":
						 	$restriccion["TIPO"]=strtolower($resOriginal["TIPO"]);
							if (!isset($resOriginal["MENSAJE"]))
							      $restriccion["MENSAJE"]="El campo no es valido";
								else
								  $restriccion["MENSAJE"]=$resOriginal["MENSAJE"];
							
							if (isset($resOriginal["FUNCION"]) && is_string($resOriginal["FUNCION"]))
								  $restriccion["FUNCION"]=$resOriginal["FUNCION"];
							    else
							      $restriccion["FUNCION"]="";
							
							$valida=true;							
							break;
					
				}
				
				//busco lo campos a los que indicar la restriccion
				if (isset($resOriginal["ATRI"]) && $valida)
				{
					$atributos=explode(',', $resOriginal["ATRI"]);
					foreach($atributos as $atributo)
					{
						$atributo=$this->ajustarNombreCampo(trim($atributo));
						if (isset($this->_atributos[$atributo]))
						{
							if (!isset($this->_restricciones[$atributo]))
									$this->_restricciones[$atributo]=array();
							
							$this->_restricciones[$atributo][]=$restriccion;	    
						}
					}
				}
			}
			
			
			return true;
		}
		
		
		
		protected function fijarAtributos()
		{
			return array();
		}
		
		protected function fijarDescripciones()
		{
			return array();
		}
		
		public function getNombre()
		{
			return $this->_nombre;
		}
		
		public function getDescripcion($campo)
		{
			$campo=$this->ajustarNombreCampo($campo);
				
			//compruebo si existe el campo
			if (isset($this->_atributos[$campo]))
				{
					return $this->_descripciones[$campo];
				}	
			  else
			  	return null;
		}
		
		
		protected function fijarRestricciones()
		{
			return array();
		}
		
		public function setError($campo,$mensaje)
		{
			$campo=$this->ajustarNombreCampo($campo);
			
			if (!isset($this->_atributos[$campo]))
				return;
			
			if (!isset($this->_errores[$campo]))
				$this->_errores[$campo]=array();
			
			$this->_errores[$campo][]=$mensaje;
		}
		
		public function errorAtributo($campo)
		{
			$campo=$this->ajustarNombreCampo($campo);
			
			if (isset($this->_errores[$campo]))
				return $this->_errores[$campo];
			  else
			  	return null;
		}
		
		public function getErrores()
		{
			$listaErrores=array();
			
			foreach ($this->_errores as $campo => $errores) 
			{
				foreach ($errores as $error) 
				{
					$listaErrores[]=$this->getDescripcion($campo).": ".$error;					
				}
			}
			return $listaErrores;
		}
		
		public function validar()
		{
			$this->_errores=array();
			
			foreach ($this->_restricciones as $campo=>$restric)
			{
				foreach ($restric as $res) 
				{
					switch (strtolower($res["TIPO"])) 
					{
						case 'requerido':
								if (!(bool)$this->$campo)
								{
									$this->setError($campo, $res["MENSAJE"]);
								}
								break;
								
						case 'cadena':
							    if (!is_string($this->$campo))
								{
									$this->setError($campo, $res["MENSAJE"]);
								}
								if (strlen($this->$campo)>$res["TAMANIO"])
								{
									$this->setError($campo, $res["MENSAJE"]);
								}
								
								break;
						
						case "funcion":
							   if ($res["FUNCION"]!='')
							   		$this->$res["FUNCION"]();
							    break;  
						
						case "entero":
								//uso la funcion de validar entero
								$otra=$this->$campo;
								if (!CValidaciones::validaEntero($otra, 
														$res["DEFECTO"], $res["MIN"], $res["MAX"]))	
									{
										$this->setError($campo, $res["MENSAJE"]);
									}
								$this->$campo=$otra;
								
								break;

						case "real":
								//uso la funcion de validar real
								$otra=$this->$campo;
								if (!CValidaciones::validaReal($otra, 
														$res["DEFECTO"], $res["MIN"], $res["MAX"]))	
									{
										$this->setError($campo, $res["MENSAJE"]);
									}
								$this->$campo=$otra;
								
								break;
										  
						case "email":
								//uso la funcion de validar email
								$otra=$this->$campo;
								if (!CValidaciones::validaEMail($otra, 
														$res["DEFECTO"]))	
									{
										$this->setError($campo, $res["MENSAJE"]);
									}
								$this->$campo=$otra;
								
								break;
						
						case "fecha":
								//uso la funcion de validar fecha
								$otra=$this->$campo;
								if (!CValidaciones::validaFecha($otra))	
									{
										$this->setError($campo, $res["MENSAJE"]);
									}
								$this->$campo=$otra;
								
								break;
						
						case "hora":
								//uso la funcion de validar hora
								$otra=$this->$campo;
								if (!CValidaciones::validaHora($otra))	
									{
										$this->setError($campo, $res["MENSAJE"]);
									}
								$this->$campo=$otra;
								
								break;
								
						case "rango":
								//uso la funcion de validar lista
								$otra=$this->$campo;
								if (!CValidaciones::validaLista($otra,$res["RANGO"]))	
									{
										$this->setError($campo, $res["MENSAJE"]);
									}
								$this->$campo=$otra;
								
								break;
					
					}	
				}
			}
			
			//se devuelve true si no hay ningun elemento en el array de
			//errores, es decir, no hay errores
			return ($this->_errores===array());
		}

		protected function afterCreate()
		{
			
		}
		
		protected function afterBuscar()
		{
			
		}
		
		protected function ajustarNombreCampo($nombre)
		{
			return strtolower($nombre);
		}
		
		
		public function setValores($arrayValores)
		{
			foreach ($arrayValores as $campo => $valor) 
			{
				if (isset($this->$campo))
					$this->$campo=$valor;	
			}
		}
		
		
		//funciones para sobrecarga de atributos
		public function __get($nombre)
		{
			$nombre=$this->ajustarNombreCampo($nombre);
			
			if (isset($this->_atributos[$nombre]))
			  	return $this->_atributos[$nombre];
			  else
				return null;
		}
		
		public function __set($nombre,$valor)
		{
			$nombre=$this->ajustarNombreCampo($nombre);
			
			//no permito agregar campos al objeto si no se 
			//han definido mediante la funcion fijarAtributos
			if (isset($this->_atributos[$nombre]))
				$this->_atributos[$nombre]=$valor;
			
		}
		
		public function __isset($nombre)
		{
			$nombre=$this->ajustarNombreCampo($nombre);
			
			return (isset($this->_atributos[$nombre]));
			
		}
		
		public function __unset($nombre)
		{
			$nombre=$this->ajustarNombreCampo($nombre);
			//no permito unset sobre los campos de un objeto
			//if (isset($this->_atributos[$nombre]))
			//      unset($this->_atributos[$nombre]);
			
		}
		
		//funciones para iterar sobre los campos definidos
		public function key()
		{
			$claves=array_keys($this->_atributos);
			return $claves[$this->_indiceIter];
		}
		
		public function current()
		{
			$valores=array_values($this->_atributos);
			return $valores[$this->_indiceIter];
			
		}
		
		public function next()
		{
			$this->_indiceIter++;
		}
		
		public function rewind()
		{
			$this->_indiceIter=0;
		}
		
		public function valid()
		{
			if (($this->_indiceIter>=0) &&
			    ($this->_indiceIter<count($this->_atributos))) 
				return true;
			  else
			  	return false;
		}
		
		
		
		//soporte de BD
		protected function fijarTabla()
		{
			return "";
		}
		
		protected function fijarId()
		{
			return "";
		}
		
		
		protected function fijarSentenciaInsert()
		{
			return "";
		}
		
		protected function fijarSentenciaUpdate()
		{
			return "";
		}
		
		
		
		
		public function buscarPorId($valor,$opciones=array())
		{
			if (!isset($opciones["where"]))
			     $opciones["where"]="";
			$opciones["order"]="";
			$opciones["limit"]="1";
			
			if ($opciones["where"]!="")
			    $opciones["where"].=" and ";
			
			$opciones["where"].=" t.{$this->_id}=$valor";
			
			$filas=$this->ejecutarConsultaSelect($opciones);
			
			if (is_array($filas) && count($filas)!=0)
			  {
			  	$this->_esNuevo=false;
				$this->setValores($filas[0]);
				$this->afterBuscar();
			    return true;
			  }
			
			return false;  
			
			
		}
		
		public function buscarPor($opciones=array())
		{
			if (!isset($opciones["where"]))
			     $opciones["where"]="";
			$opciones["order"]="";
			$opciones["limit"]="1";
			
			$filas=$this->ejecutarConsultaSelect($opciones);
			
			if (is_array($filas) && count($filas)!=0)
			  {
			  	$this->_esNuevo=false;
				$this->setValores($filas[0]);
				$this->afterBuscar();
			    return true;
			  }
			
			return false;  
		}
		
		public function buscarTodos($opciones=array())
		{
			$filas=$this->ejecutarConsultaSelect($opciones);
			return $filas;
		}
		
		
		
		public function guardar()
		{
			if ($this->_tabla=="")
			     return false;
				
			if (!$this->_esNuevo)
			   {  //se guarda un registro modificado
			   	  $sentencia=$this->fijarSentenciaUpdate();
				  
				  if ($sentencia=="")
				     return false;
			   	
				  if (!$this->ejecutarSentencia($sentencia))
				        return false;
				  $campo=$this->_id;
				  $this->buscarPorId($this->$campo);	
				  return true;	
			   }
			 else
			   {
			   	  $sentencia=$this->fijarSentenciaInsert();
				  
				  if ($sentencia=="")
				     return false;
			   	  
				  $valor=$this->ejecutarSentencia($sentencia,true);
				  if (!$valor)
				      return false;
				      
				  $this->buscarPorId($valor);	
				  return true;				  	
				
			   }	  
		}
		
		
		public function ejecutarSentencia($sentencia, $esInsert=false)
		{
			if ($this->_tabla=="")
			     return false;
			
			if (Sistema::app()->BD())
			   {
			   	  $consulta=Sistema::app()->BD()->crearConsulta($sentencia);
				  if ($consulta->error()!=0)
				        return false;
				      else
					  	{
					  	  if ($esInsert)
						      return $consulta->idGenerado();
						  	
					  	  $filas=$consulta->filas();	
					  	  
					  	  if (is_array($filas))
					  	  		return $filas;
							  else
							    return true;
						}
			   }
			  else 
			   {
				  return false;
			   }
		
		}
		
		private function ejecutarConsultaSelect($opciones=array())
		{
			if ($this->_tabla=="")
			     return false;
			
			$sentencia=$this->componerSentencia($opciones);
			
			return $this->ejecutarSentencia($sentencia);	  	 
		}
		
		private function componerSentencia($opciones=array())
		{
			$sentSelect="*";
			$sentFrom=" {$this->_tabla} t";
			$sentWhere="";
			$sentOrder="";
			$sentLimit="";
			
			if (isset($opciones["select"]) &&
			          trim($opciones["select"])!="")
			   $sentSelect=$opciones["select"];
			
			if (isset($opciones["from"])&&
			          trim($opciones["from"])!="")
			    $sentFrom.=" ".$opciones["from"];
			
			if (isset($opciones["where"])&&
			          trim($opciones["where"])!="")
			    $sentWhere.=" ".$opciones["where"];
			
			if (isset($opciones["order"])&&
			          trim($opciones["order"])!="")
			    $sentOrder.=" ".$opciones["order"];
			
			if (isset($opciones["limit"]))
			    $sentLimit.=" ".$opciones["limit"];
			
			
			$sentencia="select $sentSelect".
			           "    from $sentFrom";
			if ($sentWhere!="")
				$sentencia.="     where $sentWhere";
			
			if ($sentOrder!="")
			    $sentencia.="     order by $sentOrder";
			    
			if ($sentLimit!="")
			    $sentencia.="  limit $sentLimit";
				
			
			return $sentencia;
			
		}
	}
