<?php

class Entradas_anonimos extends CActiveRecord{
    
    // método que devuelve el nombre que se asignará al modelo. (para formularios)
    protected function fijarNombre(){
        return 'entrAnom';
    }
    
    //PARA EL SOPORTE DE BDD********************************************
    
    //Nombre de la tabla
    protected function fijarTabla(){
        
        return "cons_entradas_anonimos";
        
    }
    
    //establece el campo que se corresponde con la clave principal de la tabla, en este caso codArticulo
    protected function fijarId(){
        return "cod_entrada_anon";
    }
    
    
    //Fijar atributos del modelo
    protected function fijarAtributos(){

        return array("cod_entrada_anon","cod_pase_pelicula","cod_asiento","importe", "fecha_compra", "pase_fecha");
        
    }//End fijarAtributos
    
    
    public function fijarDescripciones(){
        return array("cod_entrada_anon"=>"Entrada",
                    "cod_pase_pelicula"=>"Pase Pelicula",
                    "cod_asiento"=>"Asiento",
                    "importe"=>"Importe",
                    "fecha_compra"=>"Fecha compra"
        );
    }
    
    protected function fijarRestricciones(){
        
        return array(
                array("ATRI"=>"cod_entrada_anon, cod_pase_pelicula, cod_asiento","TIPO"=>"REQUERIDO"),
                array("ATRI"=>"cod_entrada_anon, cod_pase_pelicula, cod_asiento","TIPO"=>"ENTERO",
                ),
                array("ATRI"=>"importe","TIPO"=>"REAL"),
                array("ATRI"=>"fecha_compra", "TIPO"=>"FUNCION", "FUNCION"=>"fechaCompra"),
                array("ATRI"=>"cod_asiento","TIPO"=>"FUNCION","FUNCION"=>"validaAsiento")
            );
            
    }//End Restricciones
    
    // método se usa para asignar a los atributos del modelo los valores por defecto que deseemos.
    protected function afterCreate(){
        
        $this->cod_entrada_anon=0;
        $this->cod_pase_pelicula=0;
        $this->cod_asiento=0;
        $this->importe=0;
        $this->fecha_compra = date("Y-m-d");
        
    }//end afterCreate
    
    public function fechaCompra(){
        
        $fecha = date("Y-m-d");
        
        $this->fecha_compra = $fecha;
        
    }
    
    
    public function validaAsiento(){
        
        //Funcion que se encarga de validar si el asiento que se esta comprando en ese momento
        //no esta ocupado
        
        /*$filtrado["where"] = "t.cod_asiento = ".$this->cod_asiento;

        $entradas = $this->buscarTodos($filtrado);

        if(!empty($entradas))
            $this->setError("cod_asiento","El asiento ya ha sido comprado");*/
        
        if($this->asientoOcupado($this->cod_asiento,$this->cod_pase_pelicula)===true){
            $this->setError("cod_asiento", "El asiento ya ha sido reservado");
        }

    }// end validaAsiento
    
    public static function asientoOcupado($codAsiento, $codPase){
        
        $codasiento = intval($codAsiento);
        
        $entradaUsu = new Entradas_anonimos();
        
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
        $fechaCompra = $this->fecha_compra;
        
        
        return "insert into entradas_anonimos (cod_pase_pelicula, cod_asiento, importe, fecha_compra ".
            " ) values ( ".
            "$cod_pase_pelicula, $cod_asiento, $importe, '$fechaCompra' ) ";

    }//End sentenciaInsert
    
    protected function fijarSentenciaUpdate(){
        
        
        
        $cod_pase_pelicula=$this->cod_pase_pelicula;
        $cod_asiento=$this->cod_asiento;
        $importe=$this->importe;
        $fechacompra = $this->fecha_compra;
        
        return "update entradas_anonimos set ".
            " cod_pase_pelicula=$cod_pase_pelicula, ".
            " cod_asiento=$cod_asiento, ".
            " importe=$importe, ".
            " fecha_compra = '$fechacompra'".
            " where cod_entrada_anon=".intval($this->cod_entrada_anon);
        
        
        
        
    }//End sentenciaUpdate
    
    
}//End class

