<?php

class Categorias extends CActiveRecord{
    
    // método que devuelve el nombre que se asignará al modelo. (para formularios)
    protected function fijarNombre(){
        return 'catg';
    }
    
    //PARA EL SOPORTE DE BDD********************************************
    
    //Nombre de la tabla
    protected function fijarTabla(){
        
        return "categorias";
        
    }
    
    //establece el campo que se corresponde con la clave principal de la tabla, en este caso codArticulo
    protected function fijarId(){
        return "cod_categoria";
    }
    
    
    //Fijar atributos del modelo
    protected function fijarAtributos(){
        
        return array("cod_categoria","nombre");
        
    }//End fijarAtributos
    
    
    public function fijarDescripciones(){
        return array("nombre"=>"Nombre categoria"
        );
    }
    
    protected function fijarRestricciones(){
        Return
        array(array("ATRI"=>"nombre","TIPO"=>"CADENA",
                "TAMANIO"=>15,
                "MENSAJE"=>"La categoria no puede ser superior a 15 caracteres"
            ),
        );
        
    }//End Restricciones
    
    // método se usa para asignar a los atributos del modelo los valores por defecto que deseemos.
    protected function afterCreate(){
        
        $this->cod_categoria=0;
        $this->nombre="";

        
    }//end afterCreate
    
    
    //Metodo que devuelve un array con key -> cod_categoria y value->nombreCategoria
    public static function dameCategorias($codCategoria=null){
        
        $cat = new Categorias();
        
        if($codCategoria!==null){
            
            $cat->buscarPorId($codCategoria);
            return $cat->nombre;
        }
        
        
        
        $categorias = $cat->buscarTodos();
        
        $aux = [];
        
        foreach ($categorias as $categoria){
            
            $aux[$categoria["cod_categoria"]] = $categoria["nombre"];
            
        }
        
        return $aux;
        
    }//End dameCategorias
    

    //Sentencias para la bdd
    protected function fijarSentenciaInsert(){}//End sentenciaInsert
    
    protected function fijarSentenciaUpdate(){}//End sentenciaUpdate
    
    
    
    
    
}//End class

