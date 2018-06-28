<?php
class peliculasControlador extends CControlador{
    
    
    //Definir el crud para la tabla de peliculas
    
    public function accionIndex(){
        
        $this->validarPermisos();
        
        
        //Se usara para mostrar todas las peliculas para poder realizar diferentes acciones
        
        $pelis = new Peliculas();
        
      
        
        $resultPeliculas = $pelis->buscarTodos();
        

        foreach ($resultPeliculas as $clave => $valor){
            
            
            $resultPeliculas[$clave]["fLanzamiento"]=
            CGeneral::fechaMysqlANormal($resultPeliculas[$clave]["fLanzamiento"]);
            
            //Valores booleanos
            $resultPeliculas[$clave]["disponible"] = ($resultPeliculas[$clave]["disponible"])?"SI":"NO";
            $resultPeliculas[$clave]["tendencia"] = ($resultPeliculas[$clave]["tendencia"])?"SI":"NO";
            
            //si la sinopsis tiene mas de 6o caracteres
            if(strlen($resultPeliculas[$clave]["sinopsis"])>60)
                $resultPeliculas[$clave]["sinopsis"] = substr($resultPeliculas[$clave]["sinopsis"], 0,60)."...";
            
            //botones
            $cadena=CHTML::link(CHTML::imagen("/imagenes/24x24/ver.png"),
                Sistema::app()->generaURL(
                    array("peliculas","consultar"),
                    array("id"=>$resultPeliculas[$clave]["cod_pelicula"])));
            
            $cadena.=CHTML::link(CHTML::imagen(
                '/imagenes/24x24/modificar.png'),
                Sistema::app()->generaURL(
                    array("peliculas","modificar"), 
                    array("id"=>$resultPeliculas[$clave]["cod_pelicula"])));
            
            $cadena.=CHTML::link(CHTML::imagen(
                '/imagenes/24x24/borrar.png'),
                Sistema::app()->generaURL(
                    array("peliculas","eliminar"),
                    array("id"=>$resultPeliculas[$clave]["cod_pelicula"])));
            $resultPeliculas[$clave]["opciones"]=$cadena;
        
        }//End foreach
        
        //definiciones de las cabeceras de las
        //columnas para el CGrid
        $cabecera=array(
            array("CAMPO"=>"titulo","ETIQUETA"=>"Título"),
            array("CAMPO"=>"fLanzamiento","ETIQUETA"=>"Lanzamiento"),
            array("CAMPO"=>"director","ETIQUETA"=>"Director"),
            array("CAMPO"=>"sinopsis","ETIQUETA"=>"Sinopsis"),
        //    array("CAMPO"=>"imagen"),
            array("CAMPO"=>"disponible","ETIQUETA"=>"Disponible"),
            array("CAMPO"=>"tendencia","ETIQUETA"=>"Tendencia"),
        //    array("CAMPO"=>"imagenP"),
            array("CAMPO"=>"nombre_categoria","ETIQUETA"=>"Categoría"),
            array("CAMPO"=>"opciones",
                "ETIQUETA"=>" Operaciones")
        );
        
  
        $this->dibujaVista("indice",array("filas"=>$resultPeliculas,
            "cabecera"=>$cabecera),"CINES MELERO");
        
        
    }//End index
    
    
    public function accionConsultar(){
        
        
        $this->validarPermisos();
        
        
        $id = $_REQUEST["id"];
        
        $peliculas = new Peliculas();
        
        $peliculas->buscarPorId($id);
        
        $this->dibujaVista("consultar",array("modelo"=>$peliculas),"CINES MELERO");
        
        
        
        
    }//End consultar
    
    
    public function accionNuevo(){
        
        
        $this->validarPermisos();
        
        
        $peliculas = new Peliculas();
        

        
        
        if(isset($_POST[$peliculas->getNombre()])){
            

            $peliculas->setValores($_POST[$peliculas->getNombre()]);
            
            $peliculas->cod_pelicula = 1;
            $peliculas->nombre_categoria = Categorias::dameCategorias($peliculas->cod_categoria);
            
            
            //Para el nombre de la imagen (nombre que tiene el usuario en el archivo) SIEMPRE EXISTE
            $aux = $_FILES["peliculas"]["name"];
            $peliculas->imagen = $aux["imagen"];//si esta vacio dara error porque lo igualara a '' vacia
            
            //Imagen panoramica (SI NO SE ENVIA EL ORMULARIO HABILITADO, NO EXISTE)
            if(isset($aux["imagenP"])){
                
                $peliculas->imagenP = $aux["imagenP"];
                
            }
            
            //Se validan que los archivos que nos han subido son de formato imagen, si no, asignamos un error:

            $peliculas->setError("imagen", "Error en el archivo.");

            if($peliculas->validar()){
            
                //si el formato de la fecha es valido, debe de cumplir que sea mas actual al dia de hoy
                //lo valido esto aqui, porque en el modificar utilizo otra restriccion a esta, y por eso no lo pongo en el modelo
                $fecha1=DateTime::createFromFormat('d/m/Y',
                    $peliculas->fLanzamiento);
                
                $fecha2=DateTime::createFromFormat('d/m/Y',date("d/m/Y"));
                
                
                if ($fecha1<$fecha2){
                    
                    $peliculas->setError("fLanzamiento", "La fecha tiene que ser igual o superior al dia actual.");
                    
                    
                    //dibujo la vista para mostrar el error de que el archivo no es correcto, porque un breack no se puede poner aqui.
                    $this->dibujaVista("nuevo",array("modelo"=>$peliculas),"CINES MELERO");
                    exit;
                    
                }
                    
                
                
                
                //lo pongo aqui para que el validar, lance un error si no se ha subido ninguna imagen y aqui muestre el error de que
                //el archivo que se intenta subir no es una imagen.
                if(Funcion::validaTipoImagen("imagen")==false){
                    
                    $peliculas->setError("imagen", "Error en el archivo.");
                    
                    
                    //dibujo la vista para mostrar el error de que el archivo no es correcto, porque un breack no se puede poner aqui.
                    $this->dibujaVista("nuevo",array("modelo"=>$peliculas),"CINES MELERO");
                    exit;
                    
                }
                
                Funcion::moverArchiboSubido($nImagenAleatorio, "imagen");
                
                $peliculas->imagen = $nImagenAleatorio;
                
                
                if($peliculas->imagenP!==""){
                    
                    //significa que la pelicula ha sido marcada para tendencia
                    
                    if(Funcion::validaTipoImagen("imagenP")==false){
                        $peliculas->setError("imagenP", "Error en el archivo.");
                        
                        $this->dibujaVista("nuevo",array("modelo"=>$peliculas),"CINES MELERO");
                        exit;
                    }
                    
                    Funcion::moverArchiboSubido($nPanoramicaAleatorio, "imagenP");
                    
                    $peliculas->imagenP = $nPanoramicaAleatorio;
                }
                
                
                
                if (!$peliculas->guardar()){

                    $this->dibujaVista("nuevo",array("modelo"=>$peliculas),"CINES MELERO");
                    exit;
                    
                }
                
                //Funcion::env
                
                $usu = new Usuarios();
                
                $opFil["where"] = "t.notificaciones = true";
                
                $notificacionesUsu = $usu->buscarTodos($opFil);
                
                foreach ($notificacionesUsu as $usu){
                    
                    Funcion::contenidoNuevaPelicula($cont, $usu["nombre"], $peliculas);
                    Funcion::enviarCorreo(array($usu["correo"]), $cont);
                    
                }
                //Se redirecciona a la pagina donde aparecen todos los mensajes
                Sistema::app()->irAPagina(array("peliculas","index"));
                
                exit;
                
                
            }//End validar
            
        }//End isset $_POST
        
        
        $this->dibujaVista("nuevo",array("modelo"=>$peliculas),"CINES MELERO");
        
    }//End accionNuevo
    
    
    public function accionModificar(){
        
        
        $this->validarPermisos();
        
        
        $peliculas = new Peliculas();
        
        if(!$peliculas->buscarPorId(intval($_GET['id']))){
            Sistema::app()->paginaError(400,"ac->modificar - No se puede obtener la peliculas");
            exit;
        }
        
        
        if(isset($_POST[$peliculas->getNombre()])){
            
            $fecha = $peliculas->fLanzamiento;
            
            $peliculas->setValores($_POST[$peliculas->getNombre()]);
            
            
            $aux = $_FILES["peliculas"]["name"];

            
            if($aux["imagenP"]!=""){
                $peliculas->imagenP = $aux["imagenP"];//le doy provisionalmente el nombre del archivo de subida sin validar nada
                                                      // para pasar el metodo validar.
            }
            
            
            if($peliculas->validar()){//el validar inicializa los errores, con lo cual no puedo anadir errores
                                      //antes del validar, pero si despues y dibujando su vista.
                
                $fecha1=DateTime::createFromFormat('d/m/Y',
                    $peliculas->fLanzamiento);
                
                $fecha2=DateTime::createFromFormat('d/m/Y',date("d/m/Y"));
                
                
                //si la pelicula tiene una fecha de lanzamiento que se ha lanzado ya, no lo voy a dejar que se modifique.
                //if(($fecha1<=$fecha2)&&($fecha!=$peliculas->fLanzamiento)){

                
                if((Peliculas::peliculaLanzada($peliculas->cod_pelicula)==true)&&($fecha!=$peliculas->fLanzamiento)){    
                    $peliculas->setError("fLanzamiento", "La fecha ya no se puede modificar.");
                    
                    $peliculas->fLanzamiento = $fecha;//le doy el valor que ya tiene la pelicula
                    //dibujo la vista para mostrar el error de que el archivo no es correcto, porque un breack no se puede poner aqui.
                    $this->dibujaVista("modificar",array("modelo"=>$peliculas),"CINES MELERO");
                    exit;

               }
               
               if ($fecha1<$fecha2&&($fecha!=$peliculas->fLanzamiento)){
                   
                   $peliculas->setError("fLanzamiento", "La fecha que tiene no puede ser mas antigua al día actual");
                   
                   
                   //dibujo la vista para mostrar el error de que el archivo no es correcto, porque un breack no se puede poner aqui.
                   $this->dibujaVista("modificar",array("modelo"=>$peliculas),"CINES MELERO");
                   exit;
                   
               }
                
                
                
                if($aux["imagen"]!=""){
                    
                    //si se esta modificando la imagen, pero el archivo no es de tipo imagen
                    if(Funcion::validaTipoImagen("imagen")==false){
                        $peliculas->setError("imagen", "El archivo que se quiere subir no es una imagen");
                    
                        $this->dibujaVista("modificar",array("modelo"=>$peliculas),"CINES MELERO");
                        exit;
                    }
                    
                    Funcion::moverArchiboSubido($nImagenAleatorio, "imagen");
                    
                    //1 eliminar la imagen que tiene actual
                    Funcion::eliminaImagen("imagen", $peliculas->imagen);
                    
                    $peliculas->imagen = $nImagenAleatorio;
                        
                }
                
                
                
                if($aux["imagenP"]!=""){
                    
                    //si se esta modificando la imagen PANORAMICA, pero el archivo no es de tipo imagen
                    if(Funcion::validaTipoImagen("imagenP")==false){
                        $peliculas->setError("imagenP", "El archivo que se quiere subir no es una imagen");
                        
                        $this->dibujaVista("modificar",array("modelo"=>$peliculas),"CINES MELERO");
                        exit;
                    }
                    
                    //1 eliminar la imagen panoramica actual
                    Funcion::eliminaImagen("imagenP", $peliculas->imagenP);
                    
                    //Asignar la nueva pelicula
                    Funcion::moverArchiboSubido($nImagenAleatorio, "imagenP");
                    $peliculas->imagenP = $nImagenAleatorio;
                }
                
                
                
                
                if (!$peliculas->guardar()){
                    
                    $this->dibujaVista("modificar",array("modelo"=>$peliculas),"CINES MELERO");
                    exit;
                    
                }
                
                //Se redirecciona a la pagina donde aparecen todos los mensajes
                Sistema::app()->irAPagina(array("peliculas","index"));
                
                exit;
                
            }//End if
            else{
                
                $this->dibujaVista("modificar",array("modelo"=>$peliculas),"CINES MELERO");
                exit;
                
            } 
            
        }//End if isset
        
        
        
        
        
        $this->dibujaVista("modificar",array("modelo"=>$peliculas),"CINES MELERO");
        
        
    }//End modificar
    
    public function accionProximamente(){
        
        
        
        //Filtrado
        $peliculas = new Peliculas();
        
        $opcion["where"] = "t.disponible = true and t.fLanzamiento > CURRENT_DATE";
        
        
        $pelis = $peliculas->buscarTodos($opcion);
        
        
        $this->dibujaVista("proximamente",array("pelis"=>$pelis),"CINES MELERO");
        
        
    }//end proximamente
    
    
    public function accionEliminar(){
        
        
        $this->validarPermisos();
        
     
        $peliculas = new Peliculas();
        
        if(!$peliculas->buscarPorId(intval($_REQUEST['id']))){
            Sistema::app()->paginaError(400,"ac->eliminar - No se puede eliminar la peliculas");
            exit;
        }
        
        //var_dump($_POST);
        
        if(!empty($_POST)){
            
            //El usuario ha elegido una opcion
            
            if(isset($_POST["si"])){
                $peliculas->disponible=0;
                
                $peliculas->guardar();
                Sistema::app()->irAPagina(array("peliculas","index"));
            }
            else {
                
                Sistema::app()->irAPagina(array("peliculas","index"));
                
            }
            
            
        }
        
        
        $this->dibujaVista("eliminar",array("peliEliminar"=>$peliculas),"CINES MELERO");
        
        
    }//End eliminar
    
    public function accionInfoPelicula(){
        
        $reservar = false;
        
        //if(isset($_GET["res"]))
        //    $reservar = intval($_GET["res"]);
        
        
        $pelicula = new Peliculas();
        
        if(!$pelicula->buscarPorId(intval($_GET["id"]))){
            Sistema::app()->paginaError(404, "Pelicula no encontrada");
            exit;
        }
        
        
        $fecha2=DateTime::createFromFormat('d/m/Y',date("d/m/Y"));
        
        $fecha1=DateTime::createFromFormat('d/m/Y',
            $pelicula->fLanzamiento);
        
        if ($fecha1<=$fecha2)
            $reservar = true;
        
        
        $modeloComent = new Comentarios();
        
        //$opFil["where"] = "t.cod_pelicula = ".$pelicula->cod_pelicula;
        //$comentarios = $modeloComent->buscarTodos($opFil);
        
        
        
        if($reservar)
            $enlaceVolver = Sistema::app()->generaURL(array("peliculas","cartelera"));
        else
            $enlaceVolver = Sistema::app()->generaURL(array("peliculas","proximamente"));
        
        
        
        
        
        
        $this->dibujaVista("infoPelicula",array("pelicula"=>$pelicula,"enlaceVolver"=>$enlaceVolver,"res"=>$reservar,
                                                    /*"comentarios"=>$comentarios,*/"modeloComent"=>$modeloComent
        ),"CINES MELERO");
    }
    
    
    public function accionCartelera(){
        
        
        //En cartelera voy a mostrar todas las peliculas que esten disponible, independientemente
        //de que tenga un pase o no, ya que me parece mejor asi en lugar de que aparezcan solo las 
        //que tienen pase.
        
        //Filtrado
        $peliculas = new Peliculas();
        
        $opcion["where"] = "t.disponible = true and t.fLanzamiento <= CURRENT_DATE";
         
        
        $pelis = $peliculas->buscarTodos($opcion);
        
        
        
        
        $this->dibujaVista("cartelera",array("pelis"=>$pelis),"CINES MELERO");
        
    }//End accionCartelera
    
    
    private function obtenerCondicion($filtro){
        
        $condicion="";
        
        if($filtro["categoria"]!=""){
            
            $codCategoria=intval($filtro["categoria"]);
            
            $condicion.=" t.cod_categoria=$codCategoria";
            
        }
        
        if($filtro["disponible"]!=""){
            
            if ($condicion!=""){
                
                $condicion.=" and ";
            }
            
            
            $cadena=CGeneral::stripSlashes($filtro["disponible"]);
            
            if ($cadena=='S')
                $condicion.=" t.disponible=true";
                else
                    $condicion.=" t.disponible=false";
            
            
        }
        
        
        if($filtro["tendencia"]!=""){
            
            if ($condicion!=""){
                
                $condicion.=" and ";
            }
            
            
            $cadena=CGeneral::stripSlashes($filtro["tendencia"]);
            
            if ($cadena=='S')
                $condicion.=" t.tendencia=true";
                else
                    $condicion.=" t.tendencia=false";
                    
                    
        }
            
        
        
        return $condicion;
        
    }//End obtenerCondicion
    
    
    public function acciondatosSemanales(){
        
        //Obtengo un array asociativo:::
      
        for ($i=0; $i<7;$i++){
            
            $dia = date("Y-m-d", strtotime("-$i day"));
            
            $ventas = new Ventas();
            
            $opFil["select"] = "count(t.cod_venta) ";
            $opFil["where"] = "t.fecha = '".$dia."'";
            
            
            $result = $ventas->buscarTodos($opFil);
            
            $total[] = intval($result[0]["count(t.cod_venta)"]);
            
            
            $entradasAnon = new Entradas_anonimos();
            
            $opFil["select"] = "count(t.cod_entrada_anon) ";
            $opFil["where"] = "t.fecha_compra = '".$dia."'";
            
            $result = $entradasAnon->buscarTodos($opFil);
            /*intval($result[0]["count(t.cod_entrada_anon)"])*/
            $total2[] = intval($result[0]["count(t.cod_entrada_anon)"]);
            
            
        }
                //devuelvo los datos inversos: de la 1 pos el mas reciente pasa a la ultima el mas reciente
        $total = array_reverse($total);
        $total2 = array_reverse($total2);
        
        
        $respuestaJson["usuarios"] = $total;
        $respuestaJson["anonimos"] = $total2;
        
        
        echo json_encode($respuestaJson);
        
    }
    
    
    
    public function accionDescargar(){
        
        
        header('Content-Type: ' . 'text/plain');
        header('Content-Disposition: attachment; filename="peliculas.txt"');
        
        $peliculas = new Peliculas();
        
        $filtro = $_REQUEST["filtro"];
        
        $condicion = $this->obtenerCondicion($filtro);
        
        $opcionesFiltrado["where"]=$condicion;
        
        
        $todasPeliculas = $peliculas->buscarTodos($opcionesFiltrado);
        
        
        foreach($todasPeliculas as $peli){
            foreach ($peli as $campo =>$valor)
            {
                echo "$campo:$valor;";
            }
            echo PHP_EOL;
        }
        
    }
    
    
    private function validarPermisos(){
        
        //Comprobar que el usuario tiene permisos
        if(!Sistema::app()->acceso()->puedeConfigurar()){
            Sistema::app()->paginaError(404,"Solo los administradores pueden acceder");
            exit;
        }
        
        
        
    }//End validarPermisos
    
    
}//End peliculasControlador