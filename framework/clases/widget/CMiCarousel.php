<?php

class CMiCarousel extends CWidget
{
    
    private $_atributosHTML=array();
    
    
    public function __construct($idCarousel,$atributosHTML=array()){
        
        $this->_atributosHTML = $atributosHTML;
        
        //estos valores son siempre fijos
        $this->_atributosHTML["class"]="carousel slide";
        $this->_atributosHTML["data-ride"]="carousel";
        //el id siempre es necesario
        $this->_atributosHTML["id"]=$idCarousel;
        
    }
    
    public function dibujate(){}
    
    
    public function principioItem($inicio, $actual){
        
        ob_start();
        
        $activo = "";
        
        if($inicio===$actual){
            $activo = "active";
        }
        
        echo CHTML::dibujaEtiqueta("div",array("class"=>"carousel-item $activo"),"",false);
        
        $escrito=ob_get_contents();
        ob_end_clean();
        
        return $escrito;
    }
    
    
    
    public function finItem(){
        
        ob_start();
        
        
        echo CHTML::dibujaEtiquetaCierre("div");
        
        $result=ob_get_contents();
        
        ob_end_clean();
        
        return $result;
    }
    
    
    public function dibujaApertura(){
        
        ob_start();
        
        //inicio carouel
        
        echo CHTML::dibujaEtiqueta("div",$this->_atributosHTML,"",false);
        
        echo CHTML::dibujaEtiqueta("div",array("class"=>"carousel-inner"),"",false);
        
        
        $escrito=ob_get_contents();
        ob_end_clean();
        
        return $escrito;
        
        
    }
    
    public function dibujaFin(){
        
        ob_start();
        
        
        echo CHTML::dibujaEtiquetaCierre("div");
        
        echo CHTML::dibujaEtiqueta("a",array("class"=>"carousel-control-prev","href"=>"#".$this->_atributosHTML["id"],"role"=>"button","data-slide"=>"prev")
                                ,"",false);
        
        echo CHTML::dibujaEtiqueta("span",array("class"=>"carousel-control-prev-icon","aria-hidden"=>"true"));
        
        echo CHTML::dibujaEtiqueta("span",array("class"=>"sr-only"),"Previous");
        
        echo CHTML::dibujaEtiquetaCierre("a");
       
        //NEXT        
        echo CHTML::dibujaEtiqueta("a",array("class"=>"carousel-control-next","href"=>"#".$this->_atributosHTML["id"],"role"=>"button","data-slide"=>"next")
            ,"",false);
        
        echo CHTML::dibujaEtiqueta("span",array("class"=>"carousel-control-next-icon","aria-hidden"=>"true"));
        
        echo CHTML::dibujaEtiqueta("span",array("class"=>"sr-only"),"Next");
        
        echo CHTML::dibujaEtiquetaCierre("a");
        
        
        echo CHTML::dibujaEtiquetaCierre("div");
        
        
        $result=ob_get_contents();
        
        ob_end_clean();
        
        return $result;
        
    }
    
}//End class
