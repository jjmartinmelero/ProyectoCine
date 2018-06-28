<?php

class Entradas_usuarios extends CActiveRecord{
    
    // método que devuelve el nombre que se asignará al modelo. (para formularios)
    protected function fijarNombre(){
        return 'entrUsu';
    }
    
    //PARA EL SOPORTE DE BDD********************************************
    
    //Nombre de la tabla
    protected function fijarTabla(){
        
        return "entradas_usuarios";
        
    }
    
    //establece el campo que se corresponde con la clave principal de la tabla, en este caso codArticulo
    protected function fijarId(){
        return "cod_entrada";
    }
    
    
    //Fijar atributos del modelo
    protected function fijarAtributos(){
        
        return array("cod_entrada","cod_pase_pelicula","cod_asiento","cod_venta","importe");
        
    }//End fijarAtributos
    
    
    public function fijarDescripciones(){
        return array("cod_entrada"=>"Entrada",
                    "cod_pase_pelicula"=>"Pase Pelicula",
                    "cod_asiento"=>"Asiento",
                    "cod_venta"=>"Venta",
                    "importe"=>"Importe"
        );
    }
    
    protected function fijarRestricciones(){
        Return
        array(
            array("ATRI"=>"cod_entrada, cod_pase_pelicula, cod_venta, cod_asiento","TIPO"=>"REQUERIDO"),
            array("ATRI"=>"cod_entrada, cod_pase_pelicula, cod_venta, cod_asiento","TIPO"=>"ENTERO",
            ),
            array("ATRI"=>"importe","TIPO"=>"REAL"),
            array("ATRI"=>"cod_asiento","TIPO"=>"FUNCION","FUNCION"=>"validaAsiento")
        );
        
    }//End Restricciones
    
    // método se usa para asignar a los atributos del modelo los valores por defecto que deseemos.
    protected function afterCreate(){
        
        $this->cod_entrada=0;
        $this->cod_pase_pelicula=0;
        $this->cod_venta=0;
        $this->cod_asiento=0;
        $this->importe=0;
        
        
    }//end afterCreate
    
    
    public function validaAsiento(){
        
        //Funcion que se encarga de validar si el asiento que se esta comprando en ese momento
        //esta o no ocupado
        
        if($this->asientoOcupado($this->cod_asiento, $this->cod_pase_pelicula)===true){
            $this->setError("cod_asiento", "El asiento ya ha sido reservado");
        }
        
        
        
    }// end validaFecha
    
    public static function asientoOcupado($codAsiento, $codPase){
        
        $codasiento = intval($codAsiento);
        
        $entradaUsu = new Entradas_usuarios();
        
        $opFilt["where"] = "t.cod_asiento = ".$codAsiento." and t.cod_pase_pelicula = ".$codPase;
        
        $result = $entradaUsu->buscarTodos($opFilt);
        
        if(empty($result)){
            //significa que no esta ocupado
            return false;
        }
        
        return true;
            
    }
    
    //Sentencias para la bdd
    protected function fijarSentenciaInsert(){
        
        
        $cod_pase_pelicula=$this->cod_pase_pelicula;
        $cod_asiento=$this->cod_asiento;
        $importe=$this->importe;
        $cod_venta = $this->cod_venta;
        
        return "insert into entradas_usuarios (cod_pase_pelicula, cod_asiento, cod_venta, importe ".
            " ) values ( ".
            "$cod_pase_pelicula, $cod_asiento, $cod_venta, $importe ) ";
        
        
    }//End sentenciaInsert
    
    protected function fijarSentenciaUpdate(){
        
        $cod_pase_pelicula=$this->cod_pase_pelicula;
        $cod_asiento=$this->cod_asiento;
        $importe=$this->importe;
        $cod_venta = $this->cod_venta;
        
        return "update entradas_usuarios set ".
            " cod_pase_pelicula=$cod_pase_pelicula, ".
            " cod_asiento=$cod_asiento, ".
            " cod_venta=$cod_venta, ".
            " importe=$importe ".
            " where cod_entrada=".intval($this->cod_entrada);
        
    }//End sentenciaUpdate
    
    
    
    
    
}//End class

