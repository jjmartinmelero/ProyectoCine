<?php

class CMiTarjeta extends CWidget
{
    
    private $_titulo;
    private $_atributosHTML=array();
    
    
    public function __construct($titulo,$atributosHTML=array()){
       
        $this->_titulo = $titulo;
        $this->_atributosHTML = $atributosHTML;
                        
                        
    }
    
    public function dibujate(){}
    
    public function dibujaApertura(){
        
        ob_start();
        
        //Tarjeta cotnenedor
        echo CHTML::dibujaEtiqueta("div",$this->_atributosHTML,"",false);
        
        echo CHTML::dibujaEtiqueta("div",array("class"=>"card"),"",false);//text-center
        
        echo CHTML::dibujaEtiqueta("div",array("class"=>"card-header danger-color-dark white-text text-center"),
            $this->_titulo);
        
        echo CHTML::dibujaEtiqueta("div",array("class"=>"card-body"),"",false);
        
        
        $escrito=ob_get_contents();
        ob_end_clean();
        
        return $escrito;		
        
	
    }
				
	public function dibujaFin(){

	    ob_start();
	    


	    
	    echo CHTML::dibujaEtiquetaCierre("div");
	    
	    
	    echo CHTML::dibujaEtiquetaCierre("div");
	    
	    echo CHTML::dibujaEtiquetaCierre("div");
	    
	    
	    $result=ob_get_contents();
	    
	    ob_end_clean();
	    
	    return $result;
	    
	}
		
}//End class
