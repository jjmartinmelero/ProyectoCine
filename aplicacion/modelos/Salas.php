<?php

class Salas extends CActiveRecord{
    
    // método que devuelve el nombre que se asignará al modelo. (para formularios)
    protected function fijarNombre(){
        return 'salas';
    }
    
    //PARA EL SOPORTE DE BDD********************************************
    
    //Nombre de la tabla
    protected function fijarTabla(){
        
        return "salas";
        
    }
    
    //establece el campo que se corresponde con la clave principal de la tabla, en este caso codArticulo
    protected function fijarId(){
        return "cod_sala";
    }
    
    
    //Fijar atributos del modelo
    protected function fijarAtributos(){
        
        return array("cod_sala","nombre","capacidad","n_columnas","n_filas");
        
    }//End fijarAtributos
    
    
    public function fijarDescripciones(){
        return array("nombre"=>"Nombre categoria"
        );
    }
    
    protected function fijarRestricciones(){
        Return
        array(
            
            array("ATRI"=>"cod_sala",
                "TIPO"=>"REQUERIDO",
                "MENSAJE"=>"Debe indicar un valor"),
            array("ATRI"=>"cod_sala","TIPO"=>"ENTERO",
                "MIN"=>0),
            array("ATRI"=>"nombre","TIPO"=>"CADENA",
                "TAMANIO"=>10,
                "MENSAJE"=>"No puede ser superior a 10 caracteres"),
            array("ATRI"=>"capacidad","TIPO"=>"ENTERO"),
            array("ATRI"=>"n_columnas","TIPO"=>"ENTERO"),
            array("ATRI"=>"n_filas","TIPO"=>"ENTERO")
        );
        
    }//End Restricciones
    
    // método se usa para asignar a los atributos del modelo los valores por defecto que deseemos.
    protected function afterCreate(){
        
        $this->cod_sala=0;
        $this->nombre="";
        $this->capacidad=0;
        $this->n_filas=0;
        $this->n_columnas=0;
        
        
    }//end afterCreate
    
    
    //Metodo que devuelve un array con key -> cod_categoria y value->nombreCategoria
    public static function dameSalas($codSala=null){
        
        $obSala = new Salas();
        
        if($codSala!==null){
            
            $obSala->buscarPorId($codSala);
            return $obSala->nombre;
        }
        
        
        
        $salas = $obSala->buscarTodos();
        
        $aux = [];
        
        foreach ($salas as $sala){
            
            $aux[$sala["cod_sala"]] = $sala["nombre"];
            
        }
        
        return $aux;
        
    }//End dameSalas
    
    
    //Sentencias para la bdd
    protected function fijarSentenciaInsert(){}//End sentenciaInsert
    
    protected function fijarSentenciaUpdate(){}//End sentenciaUpdate
    
    
    
    
    
}//End class

