<?php

class entradasAnonimosControlador extends CControlador{
    
    //accion por defecto del controlador
    public function accionIndex(){
 
        //llega el pase de la pelicula
        $codPase = intval($_GET["id"]);        
        
        //obtener las entradas compradas para ese pase, esa pelicula y esa hora
        $entUsuarios = new Entradas_usuarios();
        $pasePelicula = new Pases_peliculas();
        $sala = new Salas();
        $asientos = new Asientos();
        $entAnonimos = new Entradas_anonimos();
        
        
        $pasePelicula->buscarPorId($codPase);//CONTROLAR QUE EXISTE.
                                            //CONTROLAR TAMBIEN SI HA CADUCADO.
        
        //obtengo la sala donde se proyecta ese pase
        $sala->buscarPorId($pasePelicula->cod_sala);
        

        $opFil["select"] = "cod_asiento, fila, columna";
        $opFil["where"] = "t.cod_sala =".$pasePelicula->cod_sala;
        $opFil["order by"] = "fila, columna";
        
        $totalAsientos = $asientos->buscarTodos($opFil);
        
        //tengo todos las entradas para ese pase.
        $opFiltrado["where"] = "t.cod_pase_pelicula = ".$codPase;
        $entradasUsu = $entUsuarios->buscarTodos($opFiltrado);
        $entradasAnon = $entAnonimos->buscarTodos($opFiltrado);
        
        $filas = $sala->n_filas;
        $columnas = $sala->n_columnas;
        $capacidad = $sala->capacidad;
        

        $asientosOcupados = [];
        
        //Si no hay entradas compreadas para este pase, no se ejecuta ---
        
        if(!empty($entradasUsu)||!empty($entradasAnon)){
            $asientosOcupados = $this->asientosOcupados($entradasUsu, $entradasAnon);
        }

        //if(!empty($entradasAnonimos)){
        //    $asientosOcupados2 = $this->asientosOcupados($entradasAnonimos);
        //    var_dump($asientosOcupados2);
        //}

      if(isset($_POST["butacas"])){

            //llega el imput que almacena en un string creado en js
            //con un string con todos los asientos seleccionados
            $asientosSel = explode(",", $_POST["butacas"]);

            Sistema::app()->sesion()->set("butacas",$asientosSel);
            Sistema::app()->sesion()->set("codPase",$codPase);

            Sistema::app()->irAPagina(array("entradasAnonimos","mostrarResumenAnom"));
            exit;
        }
   
        //var_dump($asientosOcupados2);
        
        
        $this->dibujaVista("mostrarCineAnom",array("asientosOcupados"=>$asientosOcupados,
            "filas"=>$filas,
            "cols"=>$columnas,
            "codPase"=>$codPase,
            "codAsientos"=>$totalAsientos
        ),
            "CINES MELERO");
        
        
        
    }//End index
    
    /*Mostrar Resumen para los usuarios anonimos*/
    
    public function accionMostrarResumenAnom(){
        
            //si se intenta acceder a un resumen el primer momento, redirigira a una pagina de error
        if(!Sistema::app()->sesion()->existe("butacas")||!Sistema::app()->sesion()->existe("codPase")){

            Sistema::app()->paginaError(404,"Pagina no disponible, vuelva a inicio para comprar la entrada.");
            exit;
        }

        //los asientos seleccionados
        $codAsientos = Sistema::app()->sesion()->get("butacas");

        //el pase seleccionado
        $codPase = Sistema::app()->sesion()->get("codPase");


        $pase = new Pases_peliculas();
        $sala = new Salas();


        $pase->buscarPorId($codPase);
        $sala->buscarPorId($pase->cod_sala);


        foreach ($codAsientos as $codAsiento) {
            
	        $asient = new Asientos();
	         
	        $asient->buscarPorId($codAsiento);
	         
	        $filCol[] = array("fila"=>$asient->fila, "columna"=>$asient->columna);
	         
         }//end foreach

        //$precioTotal = (count($codAsientos))*$pase->importe;
        
        $precioEntrada = $pase->precio;
        
        $nSala = $sala->nombre;
        
        $horaPelicula = $pase->hora_inicio;

        //si existe el formulario significa que se ha pulsado el boton de comprar
        if(isset($_POST["tCreditoA"])){

            //var_dump($_POST);

        			//estan en la sesion
        	foreach ($codAsientos as $codAsiento) {
        		
        		$entrada = new Entradas_anonimos();

        		$entrada->cod_entrada_anon = 1;
        		$entrada->cod_pase_pelicula = $codPase;
        		$entrada->cod_asiento = intval($codAsiento);
        		$entrada->importe = $pase->precio;

        		//valido todas las entradas del bucle
        		if(!$entrada->validar() || Entradas_usuarios::asientoOcupado($entrada->cod_asiento, $codPase)){
        			//la entrada no es valida
        			Sistema::app()->paginaError(404,"Algunas butacas han sido ya compradas, vuelva al proceso de compra.");
            		exit;
        		}
        		


        	}//end foreach

        	//una vez aqui, las entradas anteriores han sido todas validadas.

        	//hago un segundo bucle pero para insertarlas
        	//si lo hago todo en el mismo bucle se podrían insertar algunas entradas,
        	//y si encuentra alguna ocupada daria error, por eso lo hago asi.

        	foreach ($codAsientos as $codAsiento) {
        		
        		$entrada = new Entradas_anonimos();

        		$entrada->cod_entrada_anon = 1;
        		$entrada->cod_pase_pelicula = $codPase;
        		$entrada->cod_asiento = intval($codAsiento);
        		$entrada->importe = $pase->precio;


                

        		                
                if (!$entrada->guardar()){
                    
                    $this->dibujaVista("nuevoPase",array("modelo"=>$pasesPeliculas),"CINES MELERO");
                    exit;
                    
                }
                $codEntradas[] = $entrada->cod_entrada_anon;                                                           //envio los objetos de entradas	

        	}//end foreach
        	
        	//utilizo la sesion porque no quiero pasar el array de las entradas en la url.
        	Sistema::app()->sesion()->set("codEntradas", $codEntradas);

        	Sistema::app()->irAPagina(array("entradasAnonimos","finalizar"));
        	exit;
        	//Sistema::app()->irAPagina(array("entradasAnonimas","finalizar"),array("codEntradas"=>$codEntradas));
        	//exit;
        	

        }//end post


        $this->dibujaVista("vistaResumenAnom",array("filCol"=>$filCol,
    												"nSala"=>$nSala,
    												"horaPelicula"=>$horaPelicula,
    												"pasePelicula"=>$pase,
    												"codAsientos"=>$codAsientos,
    												"precioEntrada"=>$precioEntrada),"CINES MELERO");
        
    }//end mostrarResumen



    //accion en la que el usuario escoge la forma de recibir sus entradas.
    public function accionFinalizar(){

        
        //tras insertar los datos en la BDD, se da opcion de que envie y/o descargar la entrada
        if(isset($_POST["bEnviar"])){
            

                $correo = $_POST["txtCorreo"];
                
                if(!CValidaciones::validaEMail($correo)){
                    //el correo no tiene un formato valido
                    $this->dibujaVista("finalizar",array("mensaError"=>"El correo no es valido"),"CINES MELERO");
                    exit;
                }
                
                //las entradas ya estan en la bdd
                $codEntradas = Sistema::app()->sesion()->get("codEntradas");
                
                Funcion::contenidoEntradaComprada($contenido, $codEntradas);//obtengo el contenido
                
                Funcion::enviarCorreo(array($_POST["txtCorreo"]), $contenido);
            
            //pdf entradas
            //if(isset($_POST["descargarPDF"])){
                
                //las entradas ya estan en la bdd
           //     $codEntradas = Sistema::app()->sesion()->get("codEntradas");
                
                //var_dump($codEntradas);
           //     Funcion::descargarPdf($codEntradas);
           // }//End descargarPdf
            
            
            
            //Sistema::app()->irAPagina(array("inicial","index"));
            
            //echo "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA";
        }//end post

        //dibujo la vista
        $this->dibujaVista("finalizar",array(),"CINES MELERO");

    }//end accionFinalizar

    public function acciondescargarPDF(){
        //las entradas ya estan en la bdd
        $codEntradas = Sistema::app()->sesion()->get("codEntradas");
        
        //var_dump($codEntradas);
        Funcion::descargarPdf($codEntradas);
    }
    
   

    /*Funcion que devuelve los asientos ocupados de ese pase*/

    private function asientosOcupados($entradasUsu, $entradasAnon){
        
        
        
        foreach ($entradasUsu as $entU){
                    
            $asiento = new Asientos();
            
            $asiento->buscarPorId($entU["cod_asiento"]);
            
            $asientoOcupado[$asiento->fila][$asiento->columna] = true;
            
        }
        
        
        foreach ($entradasAnon as $entA){
            
            $asiento = new Asientos();
            
            $asiento->buscarPorId($entA["cod_asiento"]);
            
            $asientoOcupado[$asiento->fila][$asiento->columna] = true;
            
        }
        
        return $asientoOcupado;
        
    }//end obtenerAsientos
    

	/*Se ejecuta la peticion Ajax para quitar una entrada de las seleccionadas*/
    public function accionQuitarEntrada(){

    	$pase = new Pases_peliculas();

    	$pase->buscarPorId(Sistema::app()->sesion()->get("codPase"));

    	$codAsientos = Sistema::app()->sesion()->get("butacas");

    	$obJson = json_decode(file_get_contents('php://input'), true);//String JSON ----> ARRAY ASOCIATIVO
        
        $codAsie = $obJson["idEliminar"];

                //devuelve un array con la clave que queremos eliminar
        $keyEliminar = array_search($codAsie, $codAsientos);
                
                //elimino la posicion que contiene el codigo de asiento
                //a eliminar
        unset($codAsientos[$keyEliminar]);

        if(empty($codAsientos)){
            $respuestaJson["error"] = "fin"; 
        	//significa que el usuario ha eliminado todas las peliculas
        	//Sistema::app()->irAPagina(array("entradasAnonimos","index"));
        	//exit;
        }

        //se almacena de nuevo en la sesion las butacas menos la eliminada
        Sistema::app()->sesion()->set("butacas",array_values($codAsientos));

        //el nuevo precio se envia a javascript
        $respuestaJson["nuevoPrecio"] = (floatval($pase->precio))*count($codAsientos);
        $respuestaJson["totalEntradas"] = count($codAsientos);
        $respuestaJson["precioEntrada"] = $pase->precio;
        
        echo json_encode($respuestaJson);

    }//end quitarEntrada


}//End peliculasControladorpeliculasControlador