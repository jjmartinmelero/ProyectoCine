<?php

	class CBaseDatos{
		private $_conexion;
		
		public function __construct($servidor,$usuario,
							$contrasenia,$nombreBD)
		{
			$this->_conexion=new mysqli($servidor,$usuario,
							$contrasenia,$nombreBD);
			
			$this->_conexion->query("SET NAMES 'utf8'");
			
		}
		
		public function error()
		{
			return $this->_conexion->connect_errno;
		}
		
		public function mensajeError()
		{
			return $this->_conexion->connect_error;
		}
		
		public function getEnlace()
		{
			return $this->_conexion;
		}
		
		public function crearConsulta($sentencia)
		{
			return new CCommand($this->_conexion,$sentencia);
		}
		
		public function cerrarConexion()
		{
			$this->_conexion->close();
		}
		
		public function beginTran()
		{
			$this->_conexion->autocommit(false);
		}
		
		public function commit()
		{
			return $this->_conexion->commit();
		}
		
		public function rollback()
		{
			return $this->_conexion->rollback();
		}
	}
