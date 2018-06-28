<?php


class Comentarios extends CActiveRecord{
    
    // método que devuelve el nombre que se asignará al modelo. (para formularios)
    protected function fijarNombre(){
        return 'coment';
    }
    
    //PARA EL SOPORTE DE BDD********************************************
    
    //Nombre de la tabla
    protected function fijarTabla(){
        
        return "cons_comentarios_usuarios";
        
    }
    
    //establece el campo que se corresponde con la clave principal de la tabla, en este caso codArticulo
    protected function fijarId(){
        return "cod_comentario";
    }
    
    
    //Fijar atributos del modelo
    protected function fijarAtributos(){
        
        return array("cod_comentario","cod_usuario","cod_pelicula","comentario","nick_usuario","fecha","tiempo");
        
    }//End fijarAtributos
    
    
    public function fijarDescripciones(){
        return array("cod_comentario"=>"Comentario",
            "cod_usuario"=>"Usuario",
            "cod_pelicula"=>"Pelicula",
            "comentario"=>"Comentario"
        );
    }
    
    protected function fijarRestricciones(){
        Return
        array(array("ATRI"=>"cod_comentario, cod_usuario, cod_pelicula, comentario","TIPO"=>"REQUERIDO"),
            array("ATRI"=>"cod_comentario, cod_usuario, cod_pelicula", "TIPO"=>"ENTERO"),
            array("ATRI"=>"comentario","TIPO"=>"CADENA","TAMANIO"=>100)
        );
        
    }//End Restricciones
    
    // método se usa para asignar a los atributos del modelo los valores por defecto que deseemos.
    protected function afterCreate(){
        
        $this->cod_comentario=0;
        $this->cod_usuario=0;
        $this->cod_pelicula=0;
        $this->comentario="";
        
        
    }//end afterCreate
    
    
    
    //Sentencias para la bdd
    protected function fijarSentenciaInsert(){
        
        $cod_usuario = intval($this->cod_usuario);
        $cod_pelicula = intval($this->cod_pelicula);
        $comentario = CGeneral::addSlashes($this->comentario);
        $fecha = $this->fecha;
        $tiempo = $this->tiempo;
        
        
        return "insert into comentarios_usuarios (cod_usuario, cod_pelicula, comentario, fecha, tiempo) values ( ".
            "$cod_usuario,$cod_pelicula, '$comentario', '$fecha', '$tiempo')";
        
        
    }//End sentenciaInsert
    
    
    protected function fijarSentenciaUpdate(){
        
        $cod_comentario = intval($this->cod_comentario);
        $cod_usuario = intval($this->cod_usuario);
        $cod_pelicula = intval($this->cod_pelicula);
        $comentario = CGeneral::addSlashes($this->comentario);
        $fecha = $this->fecha;
        $tiempo = $this->tiempo;
        
        return "update comentarios_usuarios set ".
            " cod_usuario=$cod_usuario, ".
            " cod_pelicula=$cod_pelicula, ".
            " comentario = '$comentario', ".
            " fecha= '$fecha',".
            " tiempo= '$tiempo'".
            " where cod_comentario=".$cod_comentario;
        
    }//End sentenciaUpdate
    
    
    
}//End class

