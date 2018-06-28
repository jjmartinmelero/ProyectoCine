<?php

	class CCommand
	{
		private $_resultado;
		private $_conexion;
		
		public function __construct($conexion,$sentencia)
		{
			$this->_conexion=$conexion;
			$this->_resultado=$this->_conexion->query($sentencia);
		}
		
		public function __destruct()
		{
			$this->free();	
		}
		
		public function error()
		{
			if ($this->_resultado===false)
			       return 1;
			if ($this->_conexion->errno!=0)
			     return $this->_conexion->errno;
			return 0;
		}
		
		public function mensajeError()
		{
			return ($this->_conexion->error);
		}
		
		public function numFilas()
		{
		   if ($this->error()!=0)
		        return false;
			if (!is_object($this->_resultado))
			    return false;
				
			return $this->_resultado->num_rows;
		}
		
		public function filas()
		{
			if ($this->error()!=0)
			   return false;
			
			if (is_object($this->_resultado))
					return ($this->_resultado->fetch_all(MYSQLI_ASSOC));
				else
					return false; 
		}
		
		public function fila()
		{
			if ($this->error()!=0)
			   return false;
			
			if (is_object($this->_resultado))
					return ($this->_resultado->fetch_assoc());
				else
					return false;
		}
		
		public function idGenerado()
		{
			return ($this->_conexion->insert_id);
		}
		
		public function free()
		{
			if (is_resource($this->_resultado))
				$this->_resultado->free();
		}
	}
