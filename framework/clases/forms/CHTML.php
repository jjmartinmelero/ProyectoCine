<?php

	class CHTML{
		
		private static $_errorCSS="error";
		private static $_cerrarEtiquetasUnicas=true;
		private static $_prefijoID="id_";
		private static $_numID=0;
		
		public static function dibujaOpciones($atributosHTML=array())
		{
			$especiales=array("async"=>1, "autofocus"=>1, "autoplay"=>1, 
								"checked"=>1, "controls"=>1, 
								"declare"=>1, "default"=>1,
								"defer"=>1, "disabled"=>1, 
								"formnovalidate"=>1, "hidden"=>1, "ismap"=>1, 
								"loop"=>1, "multiple"=>1, 
								"muted"=>1, "nohref"=>1, 
								"noresize"=>1, "novalidate"=>1,
								"open"=>1, "readonly"=>1, "required"=>1, 
								"reversed"=>1, "scoped"=>1, "seamless"=>1, 
								"selected"=>1, "typemasmatch"=>1);
								
			$cadena="";
			foreach($atributosHTML as $atributo=>$valor)
			{
				if ($cadena<>'')
				    $cadena.=" ";
				
				if (isset($especiales[$atributo]))
				    $cadena.=$atributo.'="'.$atributo.'"';
				  else
				  	$cadena.=$atributo.'="'.$valor.'"';
			}
			return $cadena;
			
		}
		
		public static function dibujaEtiqueta($etiqueta,$atributosHTML=array(),$contenido=null,
											$cerrarEtiqueta=true)
		{
			$cadena="";
			$cadena.="<".$etiqueta." ".self::dibujaOpciones($atributosHTML);
			if ($contenido===null) //no hay contenido, etiqueta unica
			     {
			     	if (self::$_cerrarEtiquetasUnicas)
					     $cadena.='/';
					$cadena.=">";
			     }
				else //hay un contenido
				 {
				    $cadena.=" >".$contenido;
					if ($cerrarEtiqueta)
			    			$cadena.="</".$etiqueta.">";
				 }
			return $cadena;
			
		}
		
		public static function dibujaEtiquetaCierre($etiqueta)
		{
			$cadena="</".$etiqueta.">";
			
			return $cadena;
		}
		
		public static function generaID()
		{
			$cadena=self::$_prefijoID.self::$_numID;
			self::$_numID++;
			
			return $cadena;
		}
		
		public static function boton($etiqueta,$atributosHTML=array())
		{
			if (!isset($atributosHTML["name"]))
			{
				$atributosHTML["name"]=self::generaID();
			}
			
			if (!isset($atributosHTML["type"]))
				$atributosHTML["type"]="button";
			
			if (!isset($atributosHTML["value"]))
				$atributosHTML["value"]=$etiqueta;
			
			return self::dibujaEtiqueta("input",$atributosHTML);
		}	
		
		public static function css($texto,$medio="")
		{
			if ($medio!=="")
				$medio=' media="'.$media.'"';
			
			$cadena="<style type=\"text/css\"{$medio}>\n{$texto}\n</style>";
			
			return $cadena; 
		}

		public static function cssFichero($url,$medio="")
		{
			return self::linkHead("stylesheet","text/css",$url,
									$medio!=='' ? $medio : null);
		}
		
		public static function botonHtml($etiqueta,$atributosHTML=array())
		{
		   if (!isset($atributosHTML["name"]))
		   		$atributosHTML["name"]=self::generaID();
		   
		   if (!isset($atributosHTML["type"]))
		   		$atributosHTML["type"]="button";
		   
		   return self::dibujaEtiqueta("button",$atributosHTML,$etiqueta);
		}
		
		public static function imagen($src,$alt="",$atributosHTML=array())
		{
			$atributosHTML["src"]=$src;
			$atributosHTML["alt"]=$alt;
			
			return self::dibujaEtiqueta("img",$atributosHTML);
		}
		
		public static function link($texto,$url="#",$atributosHTML=array())
		{
			if ($url!=="")
				$atributosHTML["href"]=self::normalizaURL($url);
			
			return self::dibujaEtiqueta("a",$atributosHTML,$texto);
		}
		
		public static function linkHead($rel=null,$type=null,$href=null,$media=null,$atributosHTML=array())
		{
			if ($rel!==null)
				$atributosHTML["rel"]=$rel;
			if ($type!==null)
				$atributosHTML["type"]=$type;
			if ($href!==null)
				$atributosHTML["href"]=$href;
			if ($media!==null)
				$atributosHTML["media"]=$media;
			
			return self::dibujaEtiqueta("link",$atributosHTML);
		}
		
		public static function metaHead($content,$name=null,$httpEquiv=null,$atributosHTML=array())
		{
			if ($name!==null)
				$atributosHTML["name"]=$name;
			if ($httpEquiv!==null)
				$atributosHTML["http-equiv"]=$httpEquiv;
			$atributosHTML["content"]=$content;
			
			return self::dibujaEtiqueta("meta",$atributosHTML);
		}
		
		public static function normalizaURL($url)
		{
			if (is_array($url))
				return Sistema::app()->generaURL($url);
			if ($url=="")
				$url="#";
			return $url;
		}
		
		public static function script($texto,$atributosHTML=array())
		{
			if (!isset($atributosHTML["type"]))
				$atributosHTML["type"]="text/javascript";
			
			
			return self::dibujaEtiqueta("script",$atributosHTML,$texto); 
		}

		public static function scriptFichero($url,$atributosHTML=array())
		{
			if (!isset($atributosHTML["type"]))
				$atributosHTML["type"]="text/javascript";
			if (!isset($atributosHTML["src"]))
				$atributosHTML["src"]=$url;
			
			
		
			return self::dibujaEtiqueta("script",$atributosHTML,"");
		}
		
		
		//***********************************************
		//metodos para dibujar componentes de formulario 
		//***********************************************
		
		protected static function dameIdDeNombre($name)
		{
			return str_replace(array('[]','][','[',']',' '),array('','_','_','','_'),$name);
		}
		
		
		
		protected static function campoInput($tipo,$nombre,$valor,$atributosHTML=array())
		{
			$atributosHTML["type"]=$tipo;
			$atributosHTML["value"]=$valor;
			$atributosHTML["name"]=$nombre;
			if (!isset($atributosHTML["id"]))
			     $atributosHTML["id"]=self::dameIdDeNombre($nombre);
			
			if ($atributosHTML["id"]===false)
			    unset($atributosHTML["id"]);
			
			return self::dibujaEtiqueta("input",$atributosHTML);
			
		}
		
		
		public static function campoCheckBox($nombre,$checked=false, $atributosHTML=array())
		{
			if($checked)
		        $atributosHTML['checked']='checked';
		    else
		        unset($atributosHTML['checked']);
			
		    $valor=isset($atributosHTML['value']) ? $atributosHTML['value'] : 1;
		
		    if(array_key_exists('uncheckValor',$atributosHTML))
		    {
		        $uncheckValor=$atributosHTML['uncheckValor'];
		        unset($atributosHTML['uncheckValor']);
		    }
		    else
		        $uncheckValor=null;
		
		    if($uncheckValor!==null)
		    {
		        /**
				 * Si en $atributosHTML aparece la opcion uncheckValor, se añade un
				 * campo oculo con el valor de uncheck para que siempre se envie el campo
				 * en el formulario: value en el caso de que este marcado y valor unchecked 
				 * en caso de que no este marcado.
				 */
				 
				 if(isset($atributosHTML['id']) && $atributosHTML['id']!==false)
			            $atributosUncheck=array('id'=>"ocul_".$atributosHTML['id']);
			        else
			            $atributosUncheck=array('id'=>false);
					
		        $oculto=self::campoHidden($nombre,$uncheckValor,$atributosUncheck);
		    }
		    else
		        $oculto='';
		
			if(array_key_exists('etiqueta',$atributosHTML))
			{
				$etiqueta=$atributosHTML['etiqueta'];
				unset($atributosHTML['etiqueta']);
				$etiqueta=" ".self::campoLabel($etiqueta, isset($atributosHTML["id"])?$atributosHTML["id"]:"");
			}
			else
				$etiqueta="";
		    return $oculto . self::campoInput('checkbox',$nombre,$valor,$atributosHTML)." ".$etiqueta;
		}
		
		/**
		 * string $nombre Name que se asignar� a la lista de checked
		 * string $seleccionado Cadena que representa el valor seleccionado un array 
		 * 				con los valores seleccionados.
		 * array $datos Array donde cada elemento es de la forma "valor => Etiqueta", 
		 * 			Se generar� un checkbox con la etiqueta indicada y el value valor
		 * string $separador. Cadena usara para separar un checkbox de otro
		 * array $atributosHTML. Atributos html a indicar para cada checkbox
		 * 
		 * 
		 * @return string
		 */
		public static function campoListaCheckBox($nombre,$seleccionado,$datos,$separador="<br/>\n",$atributosHTML=array())
		{
			if(substr($nombre,-2)!=='[]')
				$nombre.='[]';
				
			$elementos=array();
			$IDBase=self::dameIdDeNombre($nombre);

			$id=0;
			
			foreach($datos as $valor=>$textoEtiqueta)
			{
				$checked=(!is_array($seleccionado) && ($valor==$seleccionado)) || 
						  (is_array($seleccionado) && in_array($valor,$seleccionado));
				$atributosHTML['value']=$valor;
				$atributosHTML['id']=$IDBase.'_'.$id++;
				$opcion=self::campoCheckBox($nombre,$checked,$atributosHTML);
				$etiqueta=self::campoLabel($textoEtiqueta,$atributosHTML['id']);
				$elementos[]=$opcion." ".$etiqueta;
			}
			
			
			return implode($separador,$elementos);
		}
		
		public static function campoDate($nombre,$valor="",$atributosHTML=array())
		{
			return self::campoInput("date", $nombre, $valor,$atributosHTML);
		}
		
		public static function campoListaDropDown($nombre,$seleccionado,$datos,$atributosHTML=array())
		{
			$atributosHTML['name']=$nombre;
			
			if(!isset($atributosHTML['id']))
					$atributosHTML['id']=self::dameIdDeNombre($nombre);
				else
					if($atributosHTML['id']===false)
						unset($atributosHTML['id']);
			
			$linea="Seleccione una opcion";
			if (isset($atributosHTML["linea"]))
			   {
				$linea=$atributosHTML["linea"];
				unset($atributosHTML["linea"]);
			   }
			 
			$elementos=array();
			 
			if ($linea!==false)
			    {
					$atribu=array();
					$atribu["value"]="";

					$elementos[]=self::dibujaEtiqueta("option",$atribu,$linea);
				}				   			
			
			foreach($datos as $valor=>$textoEtiqueta)
				{
					$atribu=array();
					if ($valor==$seleccionado)
						$atribu["selected"]="selected";
					$atribu["value"]=$valor;
					
					$elementos[]=self::dibujaEtiqueta("option",$atribu,$textoEtiqueta);
				}
				
			$opciones=implode("\n",$elementos);

			return self::dibujaEtiqueta('select',$atributosHTML,$opciones);
				
			
		}
		
		public static function campoEmail($nombre,$valor="",$atributosHTML=array())
		{
			return self::campoInput("email", $nombre, $valor,$atributosHTML);
		}
		
		public static function campoFile($nombre,$valor="",$atributosHTML=array())
		{
			return self::campoInput("file", $nombre, $valor,$atributosHTML);
		}
		
		public static function campoHidden($nombre,$valor="",$atributosHTML=array())
		{
			return self::campoInput("hidden", $nombre, $valor,$atributosHTML);
		}
		
		public static function campoLabel($etiqueta,$for,$atributosHTML=array())
		{
			if ($for===false)
			     unset($atributosHTML["for"]);
				else
				 $atributosHTML["for"]=$for;
			
			return self::dibujaEtiqueta("label",$atributosHTML,$etiqueta);
		}
		
		public static function campoNumber($nombre,$valor="",$atributosHTML=array())
		{
			return self::campoInput("number", $nombre, $valor,$atributosHTML);
		}
		
		public static function campoPassword($nombre,$valor="",$atributosHTML=array())
		{
			return self::campoInput("password", $nombre, $valor,$atributosHTML);
		}
		
		public static function campoRadioButton($nombre,$checked=false, $atributosHTML=array())
		{
			if($checked)
		        $atributosHTML['checked']='checked';
		    else
		        unset($atributosHTML['checked']);
			
		    $valor=isset($atributosHTML['value']) ? $atributosHTML['value'] : 1;
		
		    if(array_key_exists('uncheckValor',$atributosHTML))
		    {
		        $uncheckValor=$atributosHTML['uncheckValor'];
		        unset($atributosHTML['uncheckValor']);
		    }
		    else
		        $uncheckValor=null;
		
		    if($uncheckValor!==null)
		    {
		        /**
				 * Si en $atributosHTML aparece la opcion uncheckValor, se añade un
				 * campo oculo con el valor de uncheck para que siempre se envie el campo
				 * en el formulario: value en el caso de que este marcado y valor unchecked 
				 * en caso de que no este marcado.
				 */
				 
				 if(isset($atributosHTML['id']) && $atributosHTML['id']!==false)
			            $atributosUncheck=array('id'=>"ocul_".$atributosHTML['id']);
			        else
			            $atributosUncheck=array('id'=>false);
					
		        $oculto=self::campoHidden($nombre,$uncheckValor,$atributosUncheck);
		    }
		    else
		        $oculto='';
			
			if(array_key_exists('etiqueta',$atributosHTML))
			{
				$etiqueta=$atributosHTML['etiqueta'];
				unset($atributosHTML['etiqueta']);
				$etiqueta=" ".self::campoLabel($etiqueta, isset($atributosHTML["id"])?$atributosHTML["id"]:"");
			}
			else
				$etiqueta="";
		    
		    return $oculto . self::campoInput('radio',$nombre,$valor,$atributosHTML)." ".$etiqueta;
		}
		
		public static function campoListaRadioButton($nombre,$seleccionado,$datos,$separador="<br/>\n",$atributosHTML=array())
		{
				
			$elementos=array();
			$IDBase=self::dameIdDeNombre($nombre);

			$id=0;
			
			foreach($datos as $valor=>$textoEtiqueta)
			{
				$checked=(!is_array($seleccionado) && ($valor==$seleccionado)) || 
						  (is_array($seleccionado) && in_array($valor,$seleccionado));
				$atributosHTML['value']=$valor;
				$atributosHTML['id']=$IDBase.'_'.$id++;
				$opcion=self::campoRadioButton($nombre,$checked,$atributosHTML);
				$etiqueta=self::campoLabel($textoEtiqueta,$atributosHTML['id']);
				$elementos[]=$opcion." ".$etiqueta;
			}
			
			
			return implode($separador,$elementos);
		}
		
		public static function campoRange($nombre,$valor="",$atributosHTML=array())
		{
			return self::campoInput("range", $nombre, $valor,$atributosHTML);
		}
		
		public static function campoBotonReset($etiqueta="Limpiar",$atributosHTML=array())
		{
			$atributosHTML["type"]="reset";
			return self::boton($etiqueta,$atributosHTML);
		}
		
		public static function campoBotonSubmit($etiqueta="Enviar",$atributosHTML=array())
		{
			$atributosHTML["type"]="submit";
			return self::boton($etiqueta,$atributosHTML);
		}
		
		public static function campoText($nombre,$valor="",$atributosHTML=array())
		{
			return self::campoInput("text", $nombre, $valor,$atributosHTML);
		}
		
		
		public static function campoTextArea($nombre,$valor="",$atributosHTML=array())
		{
			$atributosHTML["name"]=$nombre;
			if (!isset($atributosHTML["id"]))
			     $atributosHTML["id"]=self::dameIdDeNombre($nombre);
			
			
			return self::dibujaEtiqueta("textarea",$atributosHTML,$valor);
		
		}
		
		public static function campoTime($nombre,$valor="",$atributosHTML=array())
		{
			return self::campoInput("time", $nombre, $valor,$atributosHTML);
		}
		
		public static function campoUrl($nombre,$valor="",$atributosHTML=array())
		{
			return self::campoInput("url", $nombre, $valor,$atributosHTML);
		}
		
		
		public static function iniciarForm($accion="",$metodo="post",$atributosHTML=array())
		{
			
			$url=self::normalizaURL($accion);
			$atributosHTML["action"]=$url;
			$metodo=strtolower($metodo);
			$atributosHTML["method"]=$metodo;
			
			$formulario=self::dibujaEtiqueta("form",$atributosHTML,"",false);
			$ocultos=array();
			
			if (($metodo=="get") && (($pos=strpos($url, "?"))!==false))
			{
				foreach(explode("&",substr($url,$pos+1)) as $atributo)
				{
					if (($pos=strpos($atributo,"="))!=false)
					      $ocultos[]=self::campoHidden(substr($atributo,0,$pos),
						  								substr($atributo,$pos+1),
						  								array("id"=>false));
						else
						  $ocultos[]=self::campoHidden($atributo,"",array("id"=>false));
				}
			}
			
			if ($ocultos!==array())
			   {
			   	$ocultos=implode("\n", $ocultos);
				$formulario.="\n".self::dibujaEtiqueta("div",array("display"=>"none"),$ocultos);
			   }
			   
			return $formulario;
		} 
		
		public static function finalizarForm()
		{
			return "</form>";
		}
		

		//***************************************************************
		//metodos para dibujar componentes de formulario desde un modelo
		//***************************************************************
		
		protected static function addErrorCss(&$atributosHTML)
		{
			if (isset($atributosHTML["class"]))
				$atributosHTML["class"].=" ".self::$_errorCSS;
			  else
				$atributosHTML["class"]=self::$_errorCSS;
			  	
			
		}
		
		
		public static function dameModeloNombreId($modelo,$atributo,&$atributosHTML)
		{
			if(!isset($atributosHTML['name']))
				$atributosHTML['name']=$modelo->getNombre()."[".$atributo."]";
			
			if(!isset($atributosHTML['id']))
					$atributosHTML['id']=self::dameIdDeNombre($atributosHTML['name']);
				else
					if($atributosHTML['id']===false)
						unset($atributosHTML['id']);
		}
		
		
		
		protected static function modeloInput($tipo,$modelo,$atributo,$atributosHTML=array())
		{
			$atributosHTML["type"]=$tipo;
			if (!isset($atributosHTML["value"]))
					$atributosHTML["value"]=$modelo->$atributo;
				
			return self::dibujaEtiqueta("input",$atributosHTML);
				
		}
		
		
		public static function modeloError($modelo,$atributo,$atributosHTML=array())
		{
			$errores=$modelo->errorAtributo($atributo);
			if ($errores)
					{
						self::addErrorCss($atributosHTML);
						
						return self::dibujaEtiqueta("div",$atributosHTML,implode("<br>\n",$errores));
					}
				else
					return ""; 
		}
		
		public static function modeloErrorSumario($modelo,$atributosHTML=array())
		{
			$errores=$modelo->getErrores();
			if ($errores)
					{
						self::addErrorCss($atributosHTML);
						
						return self::dibujaEtiqueta("div",$atributosHTML,implode("<br>\n",$errores));
					}
				else
					return ""; 
		}
		
		public static function modeloCheckBox($modelo,$atributo, $atributosHTML=array())
		{
			self::dameModeloNombreId($modelo, $atributo,$atributosHTML);
			
			if(!isset($atributosHTML['value']))
				$atributosHTML['value']=1;
			if(!isset($atributosHTML['checked']) && 
					$modelo->$atributo==$atributosHTML['value'])
				$atributosHTML['checked']='checked';
			
			if(array_key_exists('uncheckValor',$atributosHTML))
			{
				/**
				 * Si en $atributosHTML aparece la opcion uncheckValor, se añade un
				 * campo oculo con el valor de uncheck para que siempre se envie el campo
				 * en el formulario: value en el caso de que este marcado y valor unchecked
				 * en caso de que no este marcado.
				 */
				$uncheck=$atributosHTML['uncheckValor'];
				unset($atributosHTML['uncheckValor']);
			}
			else
				$uncheck='0';
			
			$oculto=$uncheck!==null ? self::campoHidden($atributosHTML['name'],$uncheck,array("id"=>false)) : '';
			
			
			if(array_key_exists('etiqueta',$atributosHTML))
			{
				$etiqueta=$atributosHTML['etiqueta'];
				unset($atributosHTML['etiqueta']);
				$etiqueta=" ".self::campoLabel($etiqueta, $atributosHTML["id"]);
			}
			else
				$etiqueta="";
			
			return $oculto . self::modeloInput('checkbox',$modelo,$atributo,$atributosHTML).$etiqueta;
		}
		
		/**
		 * string $nombre Name que se asignar� a la lista de checked
		 * string $seleccionado Cadena que representa el valor seleccionado un array
		 * 				con los valores seleccionados.
		 * array $datos Array donde cada elemento es de la forma "valor => Etiqueta",
		 * 			Se generar� un checkbox con la etiqueta indicada y el value valor
		 * string $separador. Cadena usara para separar un checkbox de otro
		 * array $atributosHTML. Atributos html a indicar para cada checkbox
		 *
		 *
		 * @return string
		 */
		public static function modeloListaCheckBox($modelo,$atributo,$datos,$separador="<br/>\n",$atributosHTML=array())
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			
			$nombre=$atributosHTML["name"];
			unset($atributosHTML["name"]);
			
			if(array_key_exists('uncheckValor',$atributosHTML))
			    {
			        $uncheck=$atributosHTML['uncheckValor'];
			        unset($atributosHTML['uncheckValor']);
			    }
			    else
			        $uncheck='';
		
		    $opcionesHidden=isset($atributosHTML['id']) ? array('id'=>"ocul_".$atributosHTML['id']) : array('id'=>false);
		    $oculto=$uncheck!==null ? self::campoHidden($nombre,$uncheck,$opcionesHidden) : '';

    		return $oculto . self::campoListaCheckBox($nombre,$modelo->$atributo,$datos,$separador,$atributosHTML);
		}
		
		
		
		
		public static function modeloDate($modelo,$atributo,$atributosHTML=array())
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			return self::modeloInput("date", $modelo,$atributo,$atributosHTML);
		}
		
		public static function modeloListaDropDown($modelo,$atributo,$datos,$atributosHTML=array())
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			$seleccionado=$modelo->$atributo;
			$nombre=$atributosHTML["name"];
			
			$linea="Seleccione una opcion";
			if (isset($atributosHTML["linea"]))
			   {
				$linea=$atributosHTML["linea"];
				unset($atributosHTML["linea"]);
			   }
			 
			$elementos=array();
			 
			if ($linea!==false)
			    {
					$atribu=array();
					$atribu["value"]="";

					$elementos[]=self::dibujaEtiqueta("option",$atribu,$linea);
				}				   			
				
			foreach($datos as $valor=>$textoEtiqueta)
			{
				$atribu=array();
				if ($valor==$seleccionado)
					$atribu["selected"]="selected";
				$atribu["value"]=$valor;
					
				$elementos[]=self::dibujaEtiqueta("option",$atribu,$textoEtiqueta);
			}
			$opciones=implode("\n",$elementos);
		
			
			return self::dibujaEtiqueta('select',$atributosHTML,$opciones);
		
				
		}
		
		public static function modeloEmail($modelo,$atributo,$atributosHTML=array())
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			return self::modeloInput("email", $modelo,$atributo,$atributosHTML);
		}
		
		public static function modeloFile($modelo,$atributo,$atributosHTML=array())
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			return self::modeloInput("file", $modelo,$atributo,$atributosHTML);
		}
		
		public static function modeloHidden($modelo,$atributo,$atributosHTML=array())
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			return self::modeloInput("hidden", $modelo,$atributo,$atributosHTML);
		}
		
		public static function modeloLabel($modelo,$atributo,$atributosHTML=array())
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			
			$nombreInput=$atributosHTML["name"];
			$for=$atributosHTML["id"];
			$etiqueta=$modelo->getDescripcion($atributo);
			
			unset($atributosHTML["name"]);
			unset($atributosHTML["id"]);	
			
			if (isset($atributosHTML["label"]))
			{
				$etiqueta=$atributosHTML["label"];
				unset($atributosHTML["label"]);
			}
			     
			if (isset($atributosHTML["for"]))
			{
				$for=$atributosHTML["for"];
				unset($atributosHTML["for"]);
			}
			
			
			return self::campoLabel($etiqueta, $for,$atributosHTML);
		}
		
		public static function modeloNumber($modelo,$atributo,$atributosHTML=array())
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			return self::modeloInput("number", $modelo,$atributo,$atributosHTML);
		}
		
		public static function modeloPassword($modelo,$atributo,$atributosHTML=array())
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			return self::modeloInput("password", $modelo,$atributo,$atributosHTML);
		}
		
		public static function modeloRadioButton($modelo,$atributo, $atributosHTML=array())
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			$nombre=$atributosHTML["name"];
			
			if (!isset($atributosHTML["value"]))
			      $atributosHTML["value"]=1;
			
			if (!isset($atributosHTML["checked"]) && ($modelo->$atributo==$atributosHTML["value"]))
					$atributosHTML["checked"]="checked";
			
				
			$valor=isset($atributosHTML['value']) ? $atributosHTML['value'] : 1;
		
			if(array_key_exists('uncheckValor',$atributosHTML))
				{
					$uncheckValor=$atributosHTML['uncheckValor'];
					unset($atributosHTML['uncheckValor']);
				}
				else
					$uncheckValor="0";
		
					
			if(isset($atributosHTML['id']) && $atributosHTML['id']!==false)
					$atributosUncheck=array('id'=>"ocul_".$atributosHTML['id']);
				else
					$atributosUncheck=array('id'=>false);

			if(array_key_exists('etiqueta',$atributosHTML))
			{
				$etiqueta=$atributosHTML['etiqueta'];
				unset($atributosHTML['etiqueta']);
				$etiqueta=" ".self::campoLabel($etiqueta, $atributosHTML["id"]);
			}
			else
				$etiqueta="";


					
			$oculto=self::campoHidden($nombre,$uncheckValor,$atributosUncheck);
			
			return $oculto . self::modeloInput('radio',$modelo,$atributo,$atributosHTML)." ".$etiqueta;
		}
		
		public static function modeloListaRadioButton($modelo,$atributo,$datos,$separador="<br/>\n",$atributosHTML=array())
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			$nombre=$atributosHTML["name"];
			$seleccionado=$modelo->$atributo;
			
			if(array_key_exists('uncheckValor',$atributosHTML))
				{
					$uncheckValor=$atributosHTML['uncheckValor'];
					unset($atributosHTML['uncheckValor']);
				}
				else
					$uncheckValor="0";
		
					
			if(isset($atributosHTML['id']) && $atributosHTML['id']!==false)
					$atributosUncheck=array('id'=>"ocul_".$atributosHTML['id']);
				else
					$atributosUncheck=array('id'=>false);
					
			$oculto=self::campoHidden($nombre,$uncheckValor,$atributosUncheck);
			
			return $oculto . self::campoListaRadioButton($nombre, $seleccionado, $datos,$separador,$atributosHTML);
		}
		
		public static function modeloRange($modelo,$atributo,$atributosHTML=array())
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			return self::modeloInput("range", $modelo,$atributo,$atributosHTML);
		}
		
		
		public static function modeloText($modelo,$atributo,$atributosHTML=array())
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			return self::modeloInput("text", $modelo,$atributo,$atributosHTML);
		}
		
		
		public static function modeloTextArea($modelo,$atributo,$atributosHTML=array())
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
				
				
			return self::dibujaEtiqueta("textarea",$atributosHTML,$modelo->$atributo);
		
		}
		
		public static function modeloTime($modelo,$atributo,$atributosHTML=array())
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			return self::modeloInput("time", $modelo,$atributo,$atributosHTML);
		}
		
		public static function modeloUrl($modelo,$atributo,$atributosHTML=array())
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			return self::modeloInput("url", $modelo,$atributo,$atributosHTML);
		}
		
}
