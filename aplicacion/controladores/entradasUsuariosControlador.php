<?php

class entradasUsuariosControlador extends CControlador{
    
    
    //Definir el crud para la tabla de peliculas
    
    public function accionIndex(){
        
        if(!Sistema::app()->acceso()->hayUsuario()){
            Sistema::app()->paginaError(400,"Solos los usuarios registrados pueden acceder");
            exit;
        }

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
            
            Sistema::app()->irAPagina(array("entradasUsuarios","mostrarResumen"));
            exit;
        }
        
        
        $this->dibujaVista("mostrarCine",array("asientosOcupados"=>$asientosOcupados,
            "filas"=>$filas,
            "cols"=>$columnas,
            "codPase"=>$codPase,
            "codAsientos"=>$totalAsientos
        ),
            "CINES MELERO");
        
        
        
    }//End index
    
    
    public function accionMostrarResumen(){

        
        if(!Sistema::app()->acceso()->hayUsuario()){
            Sistema::app()->paginaError(400,"Solos los usuarios registrados pueden acceder");
            exit;
        }
        
        
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
        if(isset($_POST["tCredito"])){
            
            //valido todas las entradas elegidas por el usuario y despues inserto todas
            //si valido e inserto a la vez, podría encontrar que alguna butaca estuviese ocupada.
            
            foreach ($codAsientos as $codAsiento) {
                
                $entrada = new Entradas_usuarios();
                
                $entrada->cod_pase_pelicula = $codPase;
                $entrada->cod_asiento = $codAsiento;
                $entrada->cod_entrada = 1;
                $entrada->importe = $pase->precio - Sistema::app()->descuentoUsu;
                $entrada->cod_venta = 1;
                
                if(!$entrada->validar()||Entradas_anonimos::asientoOcupado($entrada->cod_asiento,$codPase)===true){
                    Sistema::app()->paginaError(404,"Algunas de sus entradas ya no estan disponibles");
                }
            }//end foreach
            
            
            //INSERTAR VENTA/S
            for ($i=0; $i <count($codAsientos) ; $i++) {
                
                $venta = new Ventas();
                $codUsu = intval(Sistema::app()->ACL()->getCodUsu(Sistema::app()->acceso()->getNick()));
                $venta->cod_usuario = $codUsu;
                $venta->cod_venta = 1;
                
                if($venta->validar()){
                    
                    if(!$venta->guardar()){
                        Sistema::app()->paginaError(404,"ERROR EN LA VENTA");
                    }
                    
                }
                
                //guardo el codigo de la venta para utilizarlo en la proxima insercion
                $codVentas[] = $venta->cod_venta;
                
            }//end for
            
            
            
            //INSERTAR ENTRADA/S
            //en el mismo momento en el que todas las entradas elegidas son validadas de forma correcta
            //se insertan en la BDD
            $cont = 0;
            foreach ($codAsientos as $codAsiento) {
                
                $entrada = new Entradas_usuarios();
                
                $entrada->cod_pase_pelicula = $codPase;
                $entrada->cod_asiento = $codAsiento;
                $entrada->cod_entrada = 1;
                $entrada->importe = $pase->precio - Sistema::app()->descuentoUsu;
                $entrada->cod_venta = $codVentas[$cont];
                
                if(!$entrada->guardar()){
                    Sistema::app()->paginaError(404,"Error en la conexion del servidor");
                    exit;
                }
                
                $codEntradas[] = $entrada->cod_entrada;
                
                $cont++;
            }//end foreach
            
            Sistema::app()->sesion()->set("codEntradas", $codEntradas);
            
            Sistema::app()->irAPagina(array("entradasUsuarios","fin"));
            exit;
            
            
            //Se redirecciona a la pagina donde aparecen todos los mensajes
            //Sistema::app()->irAPagina(array("entradasUsuarios","fin"));
            
        }//end post
        
        
        $this->dibujaVista("vistaResumen",array("filCol"=>$filCol,
            "nSala"=>$nSala,
            "horaPelicula"=>$horaPelicula,
            "pasePelicula"=>$pase,
            "codAsientos"=>$codAsientos,
            "precioEntrada"=>$precioEntrada),"CINES MELERO");
        
    }//end mostrarResumen
    
    
    public function accionFin(){
        
        if(!Sistema::app()->acceso()->hayUsuario()){
            Sistema::app()->paginaError(400,"Solos los usuarios registrados pueden acceder");
            exit;
        }
        
        //tras insertar los datos en la BDD, se da opcion de que envie y/o descargar la entrada
        if(isset($_POST["bEnviar"])){
            
            $usu = new Usuarios();
            
            $usu->buscarPorId(intval(Sistema::app()->ACL()->getCodUsu(Sistema::app()->acceso()->getNick())));
            
            
            $correo = $usu->correo;
            
            //las entradas ya estan en la bdd
            $codEntradas = Sistema::app()->sesion()->get("codEntradas");
            
            Funcion::contenidoEntradaComprada($contenido, $codEntradas);//obtengo el contenido
            
            Funcion::enviarCorreo(array($correo), $contenido);
            
        }//end post
        
        //dibujo la vista
        $this->dibujaVista("finalizar",array(),"CINES MELERO");
        
    }//end accionFinalizar
    
    
    

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
    
    
    public function acciondescargarPDF(){
        //las entradas ya estan en la bdd
        $codEntradas = Sistema::app()->sesion()->get("codEntradas");
        
        //var_dump($codEntradas);
        Funcion::descargarPdf($codEntradas);
    }
    
    
    
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
        $respuestaJson["nuevoPrecio"] = (floatval($pase->precio))*count($codAsientos) - Sistema::app()->descuentoUsu*count($codAsientos);
        $respuestaJson["precioEntrada"] = $pase->precio;
        $respuestaJson["totalEntradas"] = count($codAsientos);
        $respuestaJson["nuevoDescuento"] = Sistema::app()->descuentoUsu*count($codAsientos);
        //if(Sistema::app()->acceso()->hayUsuario()){
        //    $respuestaJson["descuento"] = ;
       // }
        
        echo json_encode($respuestaJson);
        
    }//end quitarEntrada
    
    

}//End peliculasControladorpeliculasControlador