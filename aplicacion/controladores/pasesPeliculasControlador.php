<?php

class pasesPeliculasControlador extends CControlador{
    
    
    //Definir el crud para la tabla de peliculas
    
    public function accionIndex(){
        
        $this->validarPermisos();
        
        $pases = new Pases_peliculas();
        
        $opfil["where"] = "t.fecha >= CURDATE()";
        
        $resultPases = $pases->buscarTodos($opfil);
        

        foreach ($resultPases as $clave => $valor){
            
            
            $resultPases[$clave]["fecha"]=
            CGeneral::fechaMysqlANormal($resultPases[$clave]["fecha"]);
            
                
            $resultPases[$clave]["precio"].=" €";
                
           /*     $cadena=CHTML::link(CHTML::imagen(
                    '/imagenes/24x24/modificar.png'),
                    Sistema::app()->generaURL(
                        array("peliculas","modificar"),
                        array("id"=>$resultPases[$clave]["cod_pelicula"])));
                
                $cadena.=CHTML::link(CHTML::imagen(
                    '/imagenes/24x24/borrar.png'),
                    Sistema::app()->generaURL(
                        array("peliculas","eliminar"),
                        array("id"=>$resultPases[$clave]["cod_pelicula"])));
                $resultPases[$clave]["opciones"]=$cadena;*/
                
        }//End foreach
        
        
        //definiciones de las cabeceras de las
        //columnas para el CGrid
        $cabecera=array(
            array("CAMPO"=>"titulo_pelicula","ETIQUETA"=>"Pelicula"),
            array("CAMPO"=>"nombre_sala","ETIQUETA"=>"Sala"),
            array("CAMPO"=>"fecha","ETIQUETA"=>"Fecha"),
            array("CAMPO"=>"hora_inicio","ETIQUETA"=>"Hora inicio"),
            array("CAMPO"=>"precio","ETIQUETA"=>"Precio")/*,
            array("CAMPO"=>"opciones",
                "ETIQUETA"=>" Operaciones")*/
        );
        
        
        $this->dibujaVista("index",array("filas"=>$resultPases,
            "cabecera"=>$cabecera),"CINES MELERO");
        
        
    }//End index
    
    
    
    public function accionNuevaSesion(){
        
        $this->validarPermisos();
        
        $pasesPeliculas = new Pases_peliculas();
        
        if(isset($_POST[$pasesPeliculas->getNombre()])){
            
            $pasesPeliculas->setValores($_POST[$pasesPeliculas->getNombre()]);
            
            
            $decimales = explode(",",$pasesPeliculas->precio);
            
            if(count($decimales)>1)    
                  $pasesPeliculas->precio = "$decimales[0].$decimales[1]";
                
            
            
            //el set valores el precio es REAL, si le llega '4,50' y no viene con el punto, en el
            //modelo toma el valor de '4.00', perdiendo el valor decimal, asi que lo controlo aqui.
            //otra solucion seria dejarlo en cadena en lugar de real, pero lo voy a dejar asi porque es numero real.
            //$precio = $_POST[$pasesPeliculas->getNombre()]["precio"];
            
            
            
            //cod_pase_pelicula que se le asigna aqui
            $pasesPeliculas->cod_pase_pelicula=1;
            
            //solo lo utilizo para que lo valide de esta forma el CActiveRecord
            $auxHora = $pasesPeliculas->hora_inicio;
            $pasesPeliculas->hora_inicio = $pasesPeliculas->hora_inicio.":00";
            

            if($pasesPeliculas->validar()){
                
                $pasesPeliculas->hora_inicio = $auxHora;
                
                //Una vez se ha validado, compruebo si la pelicula que se ha seleccionado, cumple que la fecha de lanzamiento
                //se ha producido ya.
                if(Peliculas::peliculaLanzada($pasesPeliculas->cod_pelicula/*, $pasesPeliculas->fecha*/)==false){
                    
                    $pasesPeliculas->setError("cod_pelicula", "No se ha producido su lanzamiento.");
                    
                    $this->dibujaVista("nuevoPase",array("modelo"=>$pasesPeliculas),"CINES MELERO");
                    
                    exit;
                    
                }
                
                //si se ha lanzado la pelicula, se comprueba que no haya una pelicula en el tramo horario y en el mismo dia
                if($this->existePase($pasesPeliculas->fecha, $pasesPeliculas->hora_inicio,$pasesPeliculas->cod_sala)){
                    
                    $pasesPeliculas->setError("fecha", "Ya existe una sesión para esta fecha y hora");
                    
                    $this->dibujaVista("nuevoPase",array("modelo"=>$pasesPeliculas),"CINES MELERO");
                    
                    exit;
                }
                
      
                /*if(count(explode(",", $precio))>1){
                    
                    $pasesPeliculas->setError("precio", "Los decimales se indican separados por un (.) EJ: 5.50");
                    
                    $this->dibujaVista("nuevoPase",array("modelo"=>$pasesPeliculas),"CINES MELERO");
                    
                    exit;
                }*/
                
                
                if (!$pasesPeliculas->guardar()){
                    
                    $this->dibujaVista("nuevoPase",array("modelo"=>$pasesPeliculas),"CINES MELERO");
                    exit;
                    
                }
                
                //Se redirecciona a la pagina donde aparecen todos los mensajes
                                                //controlador    accion
                Sistema::app()->irAPagina(array("pasesPeliculas","index"));
                
                exit;
                
            }//End validar

            //lo pongo dos veces, una dentro del validar y otra fuera, para segun que casos de errores se produzcan, siempre recuerde la 
            //hora introducida por el usuario.
            $pasesPeliculas->hora_inicio = $auxHora;
        }//end isset post
        
        //como un pase puede ser tanto de un usuario registrado, o de un usuario animo, en lugar de controlarlo
        //a la hora de realizar la compra, creo que es mejor forma de separar a ambos en este punto.


        
        $this->dibujaVista("nuevoPase",array("modelo"=>$pasesPeliculas),"CINES MELERO");
        
    }//End accion nuevo
    
    
    /*
    public function accionMostrarCartelera(){
        
        
        $pases = new Pases_peliculas();
        
        $fActual = date("Y/m/d");
        
        
        $fil["where"] = "t.hora_inicio > DATE_FORMAT(NOW( ), '%H:%i' ) or t.fecha > '$fActual'";

        
        $todosPases = $pases->buscarTodos($fil);
        
        
        $this->dibujaVista("cartelera",array("pasesDisponibles"=>$todosPases),"CINES MELERO");
        
        
    }*/
    
    
    
    
    
    /*
     * Mostrar todas las horas a las que está disponible la pelicula seleccionada
     */
    
    public function accionMostrarHorarios(){
        

        
        
        //llega el codigo de la pelicula seleccionada
        
        $codPelicula = intval($_GET["id"]);
        
        
        //lo hago asi para obtener el resultado deseado
        
        $sentencia =  "(select *
         from pases_pelicula 
         where cod_pelicula = $codPelicula and (fecha >= CURDATE() and hora_inicio > DATE_FORMAT(NOW( ), '%H:%i')))
         UNION
         (select *
         from pases_pelicula
         where cod_pelicula = $codPelicula and fecha > CURDATE())
         ORDER BY fecha,hora_inicio";
        
        $salida = Sistema::app()->BD()->crearConsulta($sentencia);
        
        $totalPases = $salida->filas();
        
        
        
        //$pases = new Pases_peliculas();
        
        //$opFiltrado["select"] = "select t.hora_inicio";
        //$opFiltrado["where"] = "(t.fecha >= CURDATE() and t.hora_inicio > DATE_FORMAT(NOW( ), '%H:%i')) or (t.fecha > CURDATE()) and t.cod_pelicula = ".$codPelicula;
        //$opFiltrado["where"] = "t.cod_pelicula = {$codPelicula} and t.fecha = '2018-05-01'";
        //$opFiltrado["UNION"] = "select * from pases_peliculas t2 where nd t2.fecha = '2018-05-02'";
        //$totalPases = $pases->buscarTodos($opFiltrado);
        //var_dump($totalPases);
        $fechasDevolver = [];
        
        //var_dump($totalPases[0]["fecha"] == date("Y/m/d"));
        //var_dump(date("Y/m/d"));
        //var_dump($totalPases[0]["fecha"]);
        
        foreach ($totalPases as $pase) {//los pases que tiene la pelicula
            
            
            switch ($pase["fecha"]) {
                case date("Y-m-d"):
                    
                    $arrHorarios1[] = $pase;
                    
                    $fechasDevolver[CGeneral::fechaMysqlANormal($pase["fecha"])] = $arrHorarios1;
                    
                    break;
                case date("Y-m-d", strtotime("+1 day")):
                    
                    $arrHorarios2[] = $pase;
                    
                    $fechasDevolver[CGeneral::fechaMysqlANormal($pase["fecha"])] = $arrHorarios2;
                    
                    break;
                case date("Y-m-d", strtotime("+2 day")):
                    
                    $arrHorarios3[] = $pase;
                    
                    $fechasDevolver[CGeneral::fechaMysqlANormal($pase["fecha"])] = $arrHorarios3;
                    
                    break;
                case date("Y-m-d", strtotime("+3 day")):
                    
                    $arrHorarios4[] = $pase;
                    
                    $fechasDevolver[CGeneral::fechaMysqlANormal($pase["fecha"])] = $arrHorarios4;
                    
                    break;
                case date("Y-m-d", strtotime("+4 day")):
                    
                    $arrHorarios5[] = $pase;
                    
                    $fechasDevolver[CGeneral::fechaMysqlANormal($pase["fecha"])] = $arrHorarios5;
                    
                    break;
                case date("Y-m-d", strtotime("+5 day")):
                    
                    $arrHorarios6[] = $pase;
                    
                    $fechasDevolver[CGeneral::fechaMysqlANormal($pase["fecha"])] = $arrHorarios6;
                    
                    break;
                case date("Y-m-d", strtotime("+6 day")):
                    
                    $arrHorarios7[] = $pase;
                    
                    $fechasDevolver[CGeneral::fechaMysqlANormal($pase["fecha"])] = $arrHorarios7;
                    
                    break;
            }
            
            
            
        }//end foreach
        
        
        $diasSesiones = Pases_peliculas::diasSesiones();
        
        $hayUsu = Sistema::app()->acceso()->hayUsuario();
        
        $this->dibujaVista("mostrarHorarios",array("pasesDisponibles"=>$fechasDevolver,
                                                    "diasSesiones"=>$diasSesiones,
                                                    "hayUsu"=>$hayUsu
        ),
            "CINES MELERO");
        
        
    }//end mostrarHorario
    
    
    
    
    
    
    
    private function existePase($fecha,$hora,$codSala){
        
        //tambien podria crearse con un onjeto de peliculas y buscanTodos() con unas opciones de filtrado.
        
        $sentencia="select count(*) as total".
            "    from pases_pelicula t ".
            "   where t.fecha = '".CGeneral::fechaNormalAMysql($fecha)."' and t.hora_inicio = '$hora' and t.cod_sala = $codSala";
        
        $resultado=Sistema::app()->BD()->crearConsulta($sentencia);
        
        
        $nPeliculasCoinciden=$resultado->fila()["total"];
        //si el resultado es 0, significa que el pase NO se puede anadir porque existe una pelicula a esa hora, en ese dia y en esa sala.
        if($nPeliculasCoinciden!=0)
            return true;
        
       return false;
        
    }
    
    private function validarPermisos(){
        
        //Comprobar que el usuario tiene permisos
        if(!Sistema::app()->acceso()->puedeConfigurar()){
            Sistema::app()->paginaError(404,"Solo los administradores pueden acceder");
            exit;
        }
        
        
        
    }//End validarPermisos
    
}//End peliculasControlador