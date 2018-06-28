<?php

class Ventas extends CActiveRecord{
    
    // método que devuelve el nombre que se asignará al modelo. (para formularios)
    protected function fijarNombre(){
        return 'vent';
    }
    
    //PARA EL SOPORTE DE BDD********************************************
    
    //Nombre de la tabla
    protected function fijarTabla(){
        
        return "ventas";
        
    }
    
    //establece el campo que se corresponde con la clave principal de la tabla, en este caso codArticulo
    protected function fijarId(){
        return "cod_venta";
    }
    
    
    //Fijar atributos del modelo
    protected function fijarAtributos(){
        
        return array("cod_venta","cod_usuario","fecha");
        
    }//End fijarAtributos
    
    
    public function fijarDescripciones(){

        return array("cod_venta"=>"Codigo venta",
                    "cod_usuario"=>"Codigo usuario",
                    "fecha"=>"Fecha de compra: "
        );
    }
    
    protected function fijarRestricciones(){
        Return
        array(
            array("ATRI"=>"cod_venta,cod_usuario",
                "TIPO"=>"REQUERIDO"),
            array("ATRI"=>"cod_venta, cod_usuario","TIPO"=>"ENTERO",
                "MIN"=>0),
            array("ATRI"=>"fecha","TIPO"=>"FECHA"),
            array("ATRI"=>"fecha","TIPO"=>"FUNCION","FUNCION"=>"asignarFecha")
        );
        
    }//End Restricciones
    
    // método se usa para asignar a los atributos del modelo los valores por defecto que deseemos.
    protected function afterCreate(){
        
        $this->cod_venta=0;
        $this->cod_usuario=0;
        $this->fecha=date("d/m/Y");
        
    }//end afterCreate
    

    public function asignarFecha(){
                            //formato mysql para la insersion
        $this->fecha = date("d/m/Y");

    }// end validaFecha

    
    //Sentencias para la bdd
    protected function fijarSentenciaInsert(){

        $cod_usuario = $this->cod_usuario;
        $fecha=CGeneral::fechaNormalAMysql($this->fecha); 
        
        return "insert into ventas (cod_usuario, fecha) values ( ".
            "$cod_usuario, '$fecha')";


    }//End sentenciaInsert
    
    
    protected function fijarSentenciaUpdate(){
        
        
        $cod_usuario = intval($this->cod_usuario);
        $fecha=CGeneral::fechaNormalAMysql($this->fecha);     
        
        
        return "update ventas set ".
            " cod_usuario=$cod_usuario, ".
            " fecha='$fecha'".
            " where cod_venta=".intval($this->cod_venta);
        
        
        
        
    }//End sentenciaUpdate
    
    
    
    
    
}//End class