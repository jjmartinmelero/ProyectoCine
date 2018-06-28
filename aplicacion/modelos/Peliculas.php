<?php
class Peliculas extends CActiveRecord{
    
    // método que devuelve el nombre que se asignará al modelo. (para formularios)
    protected function fijarNombre(){
        return 'peliculas';
    }
    
    //PARA EL SOPORTE DE BDD********************************************
    
    //Nombre de la tabla
    protected function fijarTabla(){
        
        return "cons_peliculas";
        
    }
    
    //establece el campo que se corresponde con la clave principal de la tabla, en este caso codArticulo
    protected function fijarId(){
        return "cod_pelicula";
    }
    
    
    //Fijar atributos del modelo
    protected function fijarAtributos(){
        
        return array("cod_pelicula",
            "titulo","cod_categoria","fLanzamiento",
            "director","sinopsis","imagen","disponible","tendencia","imagenP","nombre_categoria");
        
    }//End fijarAtributos
    
    
    public function fijarDescripciones(){
        return array("titulo"=>"Título",
            "fLanzamiento"=>"Fecha lanzamiento",
            "director"=>"Director",
            "sinopsis"=>"Sinopsis",
            "imagen"=>"Imagen caratula",
            "disponible"=>"Disponible",
            "tendencia"=>"Marcar como tendencia",
            "imagenP"=>"Imagen panoramica para tendencia",
            "nombre_categoria"=>"Categoría",
            "cod_categoria"=>"Categoria"
        );
    }
    
    //NOTA: Cuando va a dar un error, como lanzo solo un error[0] para no lanzarlos todos, el orden aqui influye
    //POR ESO AQUI EL ORDEN CORRESPONDE AL CREAR O MODIFICAR LAS PELICULAS !!!!
    
    protected function fijarRestricciones(){
        Return
        array(
            array("ATRI"=>"titulo","TIPO"=>"CADENA",
                "TAMANIO"=>40,
                "MENSAJE"=>"El titulo no puede ser superior a 40 caracteres"
            ),
            array("ATRI"=>"titulo,director,sinopsis,imagen",
                "TIPO"=>"REQUERIDO",
                "MENSAJE"=>"Debe completar la información"
            ),
            array("ATRI"=>"cod_pelicula,cod_categoria",
            "TIPO"=>"REQUERIDO",
            "MENSAJE"=>"Debe indicar un valor"),
            array("ATRI"=>"cod_pelicula,cod_categoria","TIPO"=>"ENTERO",
                "MIN"=>0),
            
            array("ATRI"=>"fLanzamiento", "TIPO"=>"FECHA", "MENSAJE"=>"Debe ser formato dd/mm/AAAA"),
            /*array("ATRI"=>"fLanzamiento",
                "TIPO"=>"FUNCION",
                "FUNCION"=>"validaFecha"),*/
            array("ATRI"=>"director","TIPO"=>"CADENA",
                "TAMANIO"=>30),
            array("ATRI"=>"sinopsis","TIPO"=>"CADENA",
                "TAMANIO"=>600, "MENSAJE"=>"Es como máximo de 600 carácteres."),
            array("ATRI"=>"imagen","TIPO"=>"CADENA",
                "TAMANIO"=>20
            ),
            array("ATRI"=>"disponible","TIPO"=>"ENTERO",
                    "MIN"=>0,"MAX"=>1),
            array("ATRI"=>"tendencia", "TIPO"=>"ENTERO",
                    "MIN"=>0,"MAX"=>1
            ),
            array("ATRI"=>"imagenP","TIPO"=>"CADENA",
                    "TAMANIO"=>20
            ),
            array("ATRI"=>"nombre_categoria","TIPO"=>"CADENA",
                "TAMANIO"=>15
            ),
            array("ATRI"=>"titulo","TIPO"=>"FUNCION",
                "FUNCION"=>"pasarMayusculas"
                
            ),
  
            array("ATRI"=>"imagenP","TIPO"=>"FUNCION",
                    "FUNCION"=>"validaImagenPanoramica"
            ),
            array("ATRI"=>"titulo","TIPO"=>"FUNCION",
                "FUNCION"=>"espaciosBlanco"
            )
            
        );
        
    }//End Restricciones
    
    // método se usa para asignar a los atributos del modelo los valores por defecto que deseemos.
    protected function afterCreate(){
        
        $this->cod_pelicula=0;
        $this->cod_categoria=0;
        $this->titulo="";
        $this->fLanzamiento = date("d/m/Y");
        $this->director = "";
        $this->sinopsis = "";
        $this->imagen = "";
        $this->disponible = 1;
        $this->tendencia = 0;
        $this->imagenP="";
        $this->nombre_categoria = "";
        
    }//end afterCreate
    
    
    public function espaciosBlanco(){
        
        //supongo que se podra comprobar con un foreach porque implementa iterator
        
        if(trim($this->titulo)==="")
            $this->setError("titulo", "No es correcto");
            
        
        if(trim($this->director)==="")
            $this->setError("director", "No es correcto");
        
        if(trim($this->sinopsis)==="")
            $this->setError("sinopsis", "No es correcto");   
    }

    
    
    
    public function pasarMayusculas(){
        
        
        $this->titulo = strtoupper($this->titulo);
        $this->director = strtoupper($this->director);
        
        
    }
    
    
    //public function validaFecha(){
        
        
        /*$fecha1=DateTime::createFromFormat('d/m/Y',
            $this->fLanzamiento);
        
        $fecha2=DateTime::createFromFormat('d/m/Y',date("d/m/Y"));
        
        
        if ($fecha1<$fecha2){
            $this->setError("fLanzamiento",
                "La fecha debe ser igual o mayor a la fecha actual");
            
        }*/
        
    //}// end validaFecha
    
    
    public function validaImagenPanoramica(){
        
        //Si se ha marcado que la pelicula es tendencia
        if($this->tendencia == true){
            
            if($this->imagenP ==="")
                $this->setError("imagenP", "Debe completar la información");
            
        }
        
        
    }//End validaImagenPanoramica
    
    
    
    
    //Metodo que devuelve un array con key -> cod_categoria y value->nombreCategoria
    public static function damePeliculas($codPelicula=null){
        
        $pelis = new Peliculas();
        
        if($codPelicula!==null){
            
            $pelis->buscarPorId($codPelicula);
            return $pelis->titulo;
        }
        
        $opFil["where"] = "t.disponible = 1";
        
        $peliculas = $pelis->buscarTodos($opFil);
        
        $aux = [];
        
        foreach ($peliculas as $pelicula){
            
            $aux[$pelicula["cod_pelicula"]] = $pelicula["titulo"];
            
        }
        
        return $aux;
        
    }//End damePeliculas
    
    
    public static function peliculaLanzada($codPelicula, $fechaPase=null){
        
        
        if($fechaPase==null){
            $fecha2=DateTime::createFromFormat('d/m/Y',date("d/m/Y"));
        }
        else{
            $fecha2=DateTime::createFromFormat('d/m/Y',$fechaPase);
        }
        
        //funcion que devuelve true, si la fecha de lanzamiento de la pelicula se ha producido, y false, si la pelicula aun no ha
        //llegado a ser lanzada.
        $peli = new Peliculas();
        
        
        if($peli->buscarPorId($codPelicula)){
            
            $fecha1=DateTime::createFromFormat('d/m/Y',
                $peli->fLanzamiento);
            
            //$fecha2=DateTime::createFromFormat('d/m/Y',date("d/m/Y"));
            
            //$fecha2=DateTime::createFromFormat('d/m/Y',$fechaPase);
            
            if ($fecha1<=$fecha2)
                return true;
                
            
            
        }
        
        return false;
    }
    
        
    /*
     * Método que se encargará de realizar operaciones con los datos ya cargados
     * no se ejecuta en el buscar todos
     */
    protected function afterBuscar(){
        
        $fecha = CGeneral::fechaMysqlANormal($this->fLanzamiento);
        $this->fLanzamiento = $fecha;
    }
    
    //Sentencias para la bdd
    protected function fijarSentenciaInsert(){
        
        
        $titulo=CGeneral::addSlashes($this->titulo);
        $fLanzamiento=CGeneral::fechaNormalAMysql($this->fLanzamiento);
        $director=CGeneral::addSlashes($this->director);
        $sinopsis=CGeneral::addSlashes($this->sinopsis);
        $imagen = CGeneral::addSlashes($this->imagen);
        $disponible = ($this->disponible?"1":"0");
        $tendencia = ($this->tendencia?"1":"0");
        $imagenP = CGeneral::addSlashes($this->imagenP);
        $categoria=$this->cod_categoria;
        
        
        return "insert into peliculas (cod_categoria,".
            " titulo, fLanzamiento, director, sinopsis, imagen, disponible, tendencia, imagenP ".
            " ) values ( ".
            "$categoria, '$titulo', '$fLanzamiento', ".
            " '$director', '$sinopsis', '$imagen', $disponible, $tendencia, '$imagenP'".
            " ) ";
        
        
        
        
        
    }//End sentenciaInsert
    
    protected function fijarSentenciaUpdate(){
        
        
        $titulo=CGeneral::addSlashes($this->titulo);
        $fLanzamiento=CGeneral::fechaNormalAMysql($this->fLanzamiento);
        $director=CGeneral::addSlashes($this->director);
        $sinopsis=CGeneral::addSlashes($this->sinopsis);
        $imagen = CGeneral::addSlashes($this->imagen);
        $disponible = CGeneral::addSlashes($this->disponible);
        $tendencia = CGeneral::addSlashes($this->tendencia);
        $imagenP = CGeneral::addSlashes($this->imagenP);
        $categoria=$this->cod_categoria;
        
        
        return "update peliculas set ".
            " titulo='$titulo', ".
            " fLanzamiento='$fLanzamiento', ".
            " director='$director', ".
            " sinopsis='$sinopsis', ".
            " imagen='$imagen', ".
            " disponible=$disponible, ".
            " tendencia=$tendencia, ".
            " imagenP='$imagenP', ".
            " cod_categoria=$categoria ".
            " where cod_pelicula=".intval($this->cod_pelicula);
        
        
        
    }//End sentenciaUpdate
    
    
    
}//End peliculas