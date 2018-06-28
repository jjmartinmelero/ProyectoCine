<?php

	abstract class CWidget
	{
	
		public abstract function dibujate();
		public abstract function dibujaApertura();
		public abstract function dibujaFin();
		
		public static function requisitos()
		{
			return '';
		}
	}
	