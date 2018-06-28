<?php



class valoracion_comentarios extends CActiveRecord{
    
    // método que devuelve el nombre que se asignará al modelo. (para formularios)
    protected function fijarNombre(){
        return 'valor_coment';
    }
    
    //PARA EL SOPORTE DE BDD********************************************
    
    //Nombre de la tabla
    protected function fijarTabla(){
        
        return "valoracion_comentarios";
        
    }
    
    //establece el campo que se corresponde con la clave principal de la tabla, en este caso codArticulo
    protected function fijarId(){
        return "cod_valoracion";
    }
    
    
    //Fijar atributos del modelo
    protected function fijarAtributos(){
        
        return array("cod_valoracion","cod_comentario","cod_usuario","opinion","reportado");
        
    }//End fijarAtributos
    
    
    public function fijarDescripciones(){
        return array(
            "cod_valoracion"=>"Valoracion",
            "cod_comentario"=>"Comentario",
            "cod_usuario"=>"Usuario",
            "opinion"=>"Opinion",
            "reportado"=>"Reportado"
        );
    }
    
    protected function fijarRestricciones(){
        Return
        array(
            array("ATRI"=>"cod_valoracion,cod_comentario, cod_usuario","TIPO"=>"REQUERIDO"),
            array("ATRI"=>"cod_comentario, cod_usuario, cod_pelicula", "TIPO"=>"ENTERO"),
            array("ATRI"=>"opinion,reportado","TIPO"=>"ENTERO","MIN"=>0,"MAX"=>1)
            
        );
        
    }//End Restricciones
    
    // método se usa para asignar a los atributos del modelo los valores por defecto que deseemos.
    protected function afterCreate(){
        
        
        $this->cod_valoracion=0;
        $this->cod_comentario=0;
        $this->cod_usuario=0;
        $this->opinion =0;
        $this->reportado = 0;
        
        
    }//end afterCreate
    
    
    
    public static function totalValoraciones($cod_coment){
        
        $valor = new valoracion_comentarios();
        
        $opFil["select"] = " count(t.cod_valoracion) as positivos";
        
        $opFil["where"] = "t.opinion=1 and cod_comentario = $cod_coment";
        
        $aux = $valor->buscarTodos($opFil);
        
        $result["positivos"] = $aux[0]["positivos"];
        
        
        $opFil["select"] = " count(t.cod_valoracion) as negativos";
        
        $opFil["where"] = "t.opinion=0 and cod_comentario = $cod_coment";
        
        $aux = $valor->buscarTodos($opFil);
        
        $result["negativos"] = $aux[0]["negativos"];
        
        return $result;
        
    }
    
    public static function dameValoracion($cod_coment, $codUsu){
        
        $valor = new valoracion_comentarios();
        
        
        /*
         * 
         * select (select count(v2.cod_valoracion) as positivos from valoracion_comentarios v2 where v2.opinion = 1 and cod_comentario = 54) as positivos, count(v1.cod_valoracion) as negativos
from valoracion_comentarios v1
where v1.opinion=0 and cod_comentario = 54
         * 
         * */
        
        /*$opFil["select"]=" cod_usuario, cod_comentario, (select count(v2.cod_valoracion) as positivos from valoracion_comentarios ".
                            "v2 where v2.opinion = 1 and cod_comentario = $cod_coment) as positivos";*/
        
        $opFil["select"]=" cod_usuario, cod_comentario, opinion ";
        
        $opFil["where"] = "cod_comentario = $cod_coment and cod_usuario = $codUsu";
        
        return $valor->buscarTodos($opFil);
        
    }
    
    
    
    
    //Sentencias para la bdd
    protected function fijarSentenciaInsert(){
        
        $cod_valoracion = intval($this->cod_valoracion);
        $cod_comentario = intval($this->cod_comentario);
        $cod_usuario = intval($this->cod_usuario);
        $opinion = intval($this->opinion);
        $reportado = intval($this->reportado);
        
        
        return "insert into valoracion_comentarios (cod_usuario, cod_comentario, opinion, reportado) values ( ".
            "$cod_usuario,$cod_comentario, $opinion, $reportado)";
        
        
    }//End sentenciaInsert
    
    
    protected function fijarSentenciaUpdate(){
        
        $cod_valoracion = intval($this->cod_valoracion);
        $cod_comentario = intval($this->cod_comentario);
        $cod_usuario = intval($this->cod_usuario);
        $opinion = intval($this->opinion);
        $reportado = intval($this->reportado);
        
        return "update valoracon_comentarios set ".
            " cod_usuario=$cod_usuario, ".
            " cod_comentario=$cod_comentario, ".
            " opinion=$opinion ".
            " reportado=$reportado".
            " where cod_valoracion=".$cod_valoracion;
        
        
        
        
    }//End sentenciaUpdate
    
    
    
}//End class

