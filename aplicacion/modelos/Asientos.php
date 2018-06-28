<?php

class Asientos extends CActiveRecord{
    
    // método que devuelve el nombre que se asignará al modelo. (para formularios)
    protected function fijarNombre(){
        return 'asient';
    }
    
    //PARA EL SOPORTE DE BDD********************************************
    
    //Nombre de la tabla
    protected function fijarTabla(){
        
        return "asientos";
        
    }
    
    //establece el campo que se corresponde con la clave principal de la tabla, en este caso codArticulo
    protected function fijarId(){
        return "cod_asiento";
    }
    
    
    //Fijar atributos del modelo
    protected function fijarAtributos(){
        
        return array("cod_asiento","cod_sala","fila","columna");
        
    }//End fijarAtributos
    
    
    public function fijarDescripciones(){
        return array("cod_asiento"=>"Asiento",
                    "cod_sala"=>"Sala",
                    "fila"=>"Fila",
                    "columna"=>"Columna"
        );
    }
    
    protected function fijarRestricciones(){
        Return
        array(array("ATRI"=>"cod_asiento,cod_sala","TIPO"=>"REQUERIDO",
            ),
            array("ATRI"=>"cod_asiento, cod_sala, fila, columna", "TIPO"=>"ENTERO")
        );
        
    }//End Restricciones
    
    // método se usa para asignar a los atributos del modelo los valores por defecto que deseemos.
    protected function afterCreate(){
        
        $this->cod_categoria=0;
        $this->cod_sala=0;
        $this->fila=0;
        $this->columna=0;

        
    }//end afterCreate
    

    //Sentencias para la bdd
    protected function fijarSentenciaInsert(){}//End sentenciaInsert
    
    protected function fijarSentenciaUpdate(){}//End sentenciaUpdate
    
    
    
    
    
}//End class

