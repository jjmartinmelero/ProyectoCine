<?php

class Pases_peliculas extends CActiveRecord{
    
    // método que devuelve el nombre que se asignará al modelo. (para formularios)
    protected function fijarNombre(){
        return 'pases';
    }
    
    //PARA EL SOPORTE DE BDD********************************************
    protected function fijarTabla(){
        
        return "cons_pases_peliculas";//Nombre de la tabla - vista
    }
    
    
    //establece el campo que se corresponde con la clave principal de la tabla, en este caso codArticulo
    protected function fijarId(){
        return "cod_pase_pelicula";
    }
    
    
    protected function fijarAtributos(){
        
        return array("cod_pase_pelicula","cod_pelicula",
            "cod_sala","titulo_pelicula","nombre_sala",
            "fecha","hora_inicio","precio","imagen_pelicula");
        
    }//End fijarAtributos
    
    public function fijarDescripciones(){
        return array(
            "cod_pelicula"=>"Pelicula",
            "cod_sala"=>"Sala",
            "titulo_pelicula"=>"Pelicula",
            "nombre_sala"=>"Sala",
            "fecha"=>"Fecha",
            "hora_inicio"=>"Hora inicio",
            "precio"=>"Precio",
            "imagen_pelicula"=>"imagen"
        );
        
    }//End fijarDescripciones
    
    
    protected function fijarRestricciones(){
        Return
        array(array("ATRI"=>"cod_pase_pelicula, cod_pelicula, cod_sala",
            "TIPO"=>"REQUERIDO"),
            array("ATRI"=>"cod_pase_pelicula, cod_pelicula, cod_sala",
                "TIPO"=>"ENTERO",
                "MIN"=>0),
            array("ATRI"=>"titulo_pelicula","TIPO"=>"CADENA",
                "TAMANIO"=>50
            ),
            array("ATRI"=>"nombre_sala",
                "TIPO"=>"CADENA",
                "TAMANIO"=>10
            ),
            array("ATRI"=>"fecha","TIPO"=>"FECHA"
            ),
            array("ATRI"=>"fecha","TIPO"=>"FUNCION",
                "FUNCION"=>"validaFechaPase"
            ),
            array("ATRI"=>"hora_inicio","TIPO"=>"HORA"
            ),
            array("ATRI"=>"hora_inicio","TIPO"=>"FUNCION","FUNCION"=>"validarHora"),
            array("ATRI"=>"precio","TIPO"=>"REAL", "MIN"=>1, "MAX"=>50, "DEFECTO"=>"5.00", "MENSAJE"=>"Introduzca un precio de sesión válido"),
            array("ATRI"=>"precio","TIPO"=>"REQUERIDO","MENSAJE"=>"Introduzca un valor valido"),
            array("ATRI"=>"precio","TIPO"=>"FUNCION","FUNCION"=>"validaPrecio"),
            array("ATRI"=>"imagen_pelicula", "TIPO"=>"CADENA",
                "TAMANIO"=>20
            )
            
        );
        
    }//End Restricciones
    
    
    public function validarHora(){
        
        //en el caso de que el pase coincida con la fecha actual, se va a validar la hora
        
        //$date1 = new DateTime($this->fecha);
        //$date2 = new DateTime(date("d/m/Y"));
        
        $date1=DateTime::createFromFormat('d/m/Y',
            $this->fecha);
        
        $date2=DateTime::createFromFormat('d/m/Y',
            date("d/m/Y"));
        
        
        $horaActual = date("H:i");
        
        $horaActual = explode(":", $horaActual);
        $horaPase = explode("/", $this->hora_inicio);
        
        
        if($date1==$date2){
            //significa que este pase se esta creando para hoy
            if(intval($horaActual[0])>=intval($horaPase[0])){
                $this->setError("hora_inicio", "Si va a crear para hoy el pase, modifique la hora");
            }
            
        }
        
        
    }
    
    
    //La creacio nde un pase de pelicula sera valido para una fecha superior a la actual.
    public function validaFechaPase(){
        
        $fecha1=DateTime::createFromFormat('d/m/Y',
            $this->fecha);
        
        $fecha2=DateTime::createFromFormat('d/m/Y',date("d/m/Y"));
        
        
        if ($fecha1<$fecha2){
            $this->setError("fecha",
                "Debe ser igual o mayor a la fecha actual");
            
        }
        
    }//End valida fecha
    
    public function validaPrecio(){
        
        
        $decimales = explode(".",$this->precio);
        
        if(isset($decimales[1])){
            
            if(strlen($decimales[1])>2)
                $this->setError("precio", "Debe de contener solo dos decimales.");
        }
        
        
        /*if(!isset($decimales[1])){
            $this->setError("precio", "Debe de contener dos decimales, por ejemplo: 5.00");
        }
        else {
            if(strlen($decimales[1])>2){
                $this->setError("precio", "Debe de contener solo dos decimales. 2");
            }
        }*/
            
        
        $this->precio = number_format($this->precio,2);
        
    }
    

    
    // método se usa para asignar a los atributos del modelo los valores por defecto que deseemos.
    protected function afterCreate(){
        
        $this->cod_pase_pelicula=0;
        $this->cod_pelicula=0;
        $this->cod_sala=0;
        $this->titulo_pelicula="";
        $this->nombre_sala="";
        $this->fecha = date("d/m/Y", strtotime("+1 day"));
        $this->hora_inicio="19:30";
        $this->precio="5.00";
        $this->imagen_pelicula="";
    }
    
    protected function afterBuscar(){
        
        $fecha = CGeneral::fechaMysqlANormal($this->fecha);
        $this->fecha = $fecha;
        
    }
    
    
    protected function fijarSentenciaInsert(){
        
        
        $codPelicula=$this->cod_pelicula;
        $codSala=$this->cod_sala;
        $fecha=CGeneral::fechaNormalAMysql($this->fecha);
        $hora=CGeneral::addSlashes($this->hora_inicio);
        $precio = $this->precio;
        
        
        
        return "insert into pases_pelicula (cod_pelicula, cod_sala, fecha, hora_inicio, precio ".
            " ) values ( ".
            "$codPelicula, $codSala, '$fecha', ".
            " '$hora', $precio".
            " ) " ;
        
        
        
        
        
        
    }//End sentenciaInsert
    
    protected function fijarSentenciaUpdate(){
        
        $codPelicula=$this->cod_pelicula;
        $codSala=$this->cod_sala;
        $fecha=CGeneral::fechaNormalAMysql($this->fecha);
        $hora=CGeneral::addSlashes($this->hora_inicio);
        $precio = $this->precio;
        
        return "update pases_pelicula set ".
            " cod_pelicula=$codPelicula, ".
            " cod_sala=$codSala, ".
            " fecha='$fecha', ".
            " hora_inicio='$hora', ".
            " precio=$precio ".
            " disponible=$disponible, ".
            " tendencia=$tendencia, ".
            " imagenP='$imagenP', ".
            " cod_categoria=$categoria ".
            " where cod_pase_pelicula=".intval($this->cod_pase_pelicula);
        
        
    }//End sentenciaUpdate
    
    
    /*
     * Funcion que devuelve los 7 dias proximos al dia actual, ya que solo se podra reservar peliculas
     * en los 6 dias siguientes al actual
     */
    
    public static function diasSesiones(){
        
       
        
        for ($i = 0; $i<7; $i++){
            
            $arrFechas[] = date("d/m/Y", strtotime("+".$i."day"));
            
        }//end for
        
        
        return $arrFechas;
        
    }//end diasSesiones
    
    
    
    
    
}//End class