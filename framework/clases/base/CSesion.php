<?php

	class CSesion{
	    
		public function __construct(){
			
		}
		
		public function haySesion(){
		    
			if(isset($_SESSION)){
			    
				return true;
			}
			
			return false;
		}
		
		
		public function crearSesion(){
		    
			if(!$this->haySesion()){
	
			    session_start();
			}
			
		}
		
				
		public function destruirSesion(){
		    
			if($this->haySesion()){
			    
				session_destroy();
			}
		}
		
		public function existe($nombre){
		    
			if($this->haySesion() && isset($_SESSION[$nombre])){
			    
				return true;
			}
			
			return false;
		}
		
		public function get($nombre){
		    
			if($this->existe($nombre)){
			    
				return $_SESSION[$nombre];
			}
			
			return null;
		}
		
		public function set($nombre,$valor){
		    
			if($this->haySesion()){
			    
				$_SESSION[$nombre] = $valor;				
				return true;
			}
			
			return false;
		}
		
		public function borrar($nombre){
		    
			if($this->existe($nombre)){
			    
				unset($_SESSION[$nombre]);
				return true;
			}
			
			return false;
		}
	}
