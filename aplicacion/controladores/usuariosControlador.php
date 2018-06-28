<?php
class usuariosControlador extends CControlador{
    
    
    //LOGIN DE LA PAGINA WEB ********************************************************************
    public function accionLogin(){
        
        $usuarios = new Usuarios();
        
        
        if(isset($_REQUEST[$usuarios->getNombre()])){
            
            $nick = $_POST[$usuarios->getNombre()]["nick"];
            $pass = $_POST[$usuarios->getNombre()]["contrasenia"];
            
            $usuarios->nick = $nick;
            
            if (Sistema::app()->ACL()->esValido($nick, $pass)){
                
                
                $nombre = Sistema::app()->ACL()->getNombre($nick);
                
                Sistema::app()->ACL()->getPermisos($nick, $puedeAcceder, $puedeConfigurar);
                
                
                Sistema::app()->acceso()->registrarUsuario($nick, $nombre, $puedeAcceder, $puedeConfigurar);
                
                Sistema::app()->irAPagina(array("inicial","index"));
                
            }
            else {
                error_log("[".date("r")."][ERROR]"."Acceso con credenciales no correctas en login\n", 3, "log/error.log");
                $mensajeErr = "Datos erroneos";
                $this->dibujaVista("login",array("modelo"=>$usuarios,"mensajeErr"=>$mensajeErr),"CINES MELERO");
                exit;
            }
            
            
        }//End isset formulario login
        
        
        
        $this->dibujaVista("login",array("modelo"=>$usuarios),"CINES MELERO");
        
    }//End function login
    
    
    
    //Ejecuta la funcion para quitar el login
    public function accionQuitarLogin(){
        
        Funcion::quitarUsuario();
        
        
    }//End quitarLogin
    
    
    //Funcion que se encarga de devolver en el login si existe el usuario en la bdd
    public function accionValidarAjax(){
        
        //Obtengo un array asociativo:::
        $obJson = json_decode(file_get_contents('php://input'), true);//String JSON ----> ARRAY ASOCIATIVO
        
        $nick = $obJson["nickUsu"];
        $pass = $obJson["passUsu"];
        
        
        $correcto = false;
        
        if(Sistema::app()->ACL()->esValido($nick, $pass))
            $correcto = true;
        
        
        //Sistema::app()->ACL()->existeUsuario($nick);
        $respuestaJson["existeNick"] = $correcto;
        
        
        echo json_encode($respuestaJson);
        
    }
    
    //Funcion para configurar el tema de la aplicacion
    public function accionConfiguracion(){
        $usuario = new Usuarios();
        $usuario->buscarPorId(Sistema::app()->ACL()->getCodUsu(Sistema::app()->acceso()->getNick()));
        
        
        
        if(isset($_POST["temaApp"])){
            //Si $_POS no esta vacio
            
            setcookie("temaApp",$_POST["temaApp"],time()+60*60*24, "/");
            
            //Pongo esto para que recarge de nuevo la configuracion y se actualice el color
            Sistema::app()->irAPagina(array("usuarios","configuracion"));
            
            
        }//end empty(post)
        
        if(isset($_POST[$usuario->getNombre()])&&isset($_POST["notificaciones"])){
            $usuario->buscarPorId(intval($_POST[$usuario->getNombre()]["cod_usuario"]));
            
           
            
            $usuario->notificaciones = intval($_POST["notificaciones"]);
            
            //No puedo utilizar el guardar del modelo, porque valida el nick y dice que ya existe
            //asi que utilizo el ejecutar consulta de la BD
            //if($usuario->guardar()){
            //    Sistema::app()->irAPagina(array("usuarios","configuracion"));
            //    exit;
            //}
            $sentencia = "update acl_usuarios set notificaciones = ".intval($_POST["notificaciones"])." where ".
                        " cod_usuario = ".$usuario->cod_usuario;
            
            Sistema::app()->BD()->crearConsulta($sentencia);
            
            Sistema::app()->irAPagina(array("usuarios","configuracion"));
            exit;
        }
        
        if(isset($_POST["nick"])){
            
            $usuario->buscarPorId(intval($_POST[$usuario->getNombre()]["cod_usuario"]));
            
            if(!$usuario || !Sistema::app()->acceso()->hayUsuario()){
                Sistema::app()->paginaError(404,"Usuario no encontrado");
                exit;
            }
            
            
            //comprobar si el nick existe en la aplicacion
            if(Sistema::app()->ACL()->existeUsuario($_POST["nick"])){
                Sistema::app()->paginaError(404, "El usuario ya existe en la aplicacion");
                exit;
            }
            
            
            $sentencia = "update acl_usuarios set nick = '".($_POST["nick"])."' where ".
                " cod_usuario = ".$usuario->cod_usuario;
            
            
            
            Sistema::app()->BD()->crearConsulta($sentencia);
            
            $nick = $_POST["nick"];
            
            //Vuelvo a registrar al usuario en acceso para que actualice los datos de la aplicacion.
            
            $nombre = Sistema::app()->ACL()->getNombre($nick);
            
            Sistema::app()->ACL()->getPermisos($nick, $puedeAcceder, $puedeConfigurar);
            
            
            Sistema::app()->acceso()->registrarUsuario($nick, $nombre, $puedeAcceder, $puedeConfigurar);
            
            
            Sistema::app()->irAPagina(array("usuarios","configuracion"));
            exit;
            
            
            
        }

        $this->dibujaVista("configuracion",array("usu"=>$usuario),"CINES MELERO");
        
    }
    
    
    
    public function accionRegistrarUsuario(){

        $usu = new Usuarios();

        if(isset($_POST[$usu->getNombre()])){

            //recibo formulario
            
            $usu->setValores($_POST[$usu->getNombre()]);
            
            
            $usu->cod_role = 1;
            $usu->cod_usuario = 1;
            $usu->contrasenia = $_POST["contrasenia"];
            $usu->segundaContrasenia = $_POST["segundaContrasenia"];
            
            
            
            if($usu->validar()){
               
                //insertar usu
                Sistema::app()->ACL()->anadirUsuario($usu->nombre, $usu->nick, $usu->contrasenia,
                    $usu->correo, $usu->cod_role, $usu->notificaciones);
                
                
                //aunque podria poner los permisos directamente, los obtengo por si en la bdd cambiasen
                Sistema::app()->ACL()->getPermisos($usu->nick, $puedeAcceder, $puedeConfigurar);
                
                
                Sistema::app()->acceso()->registrarUsuario($usu->nick, $usu->nombre, $puedeAcceder, $puedeConfigurar);
                
                //guardar en la sesion que un usu se ha registrado
                
                Sistema::app()->sesion()->set("usuReg",true);
                
                Sistema::app()->irAPagina(array("usuarios","bienvenida"));
                
                
                //Sistema::app()->irAPagina(array("inicial","index"));
                //Sistema::app()->ACL()->anadirUsuario($nombre, $nick, $contrasena, $codRol)
            }
            
            
            
        }
        
        
        $this->dibujaVista("registrarUsuario",array("modelo"=>$usu),"CINES MELERO");
        
    }//End registrarUsuario
    
    
    public function accionBienvenida(){
        
        
        if(Sistema::app()->sesion()->get("usuReg")!==true){
            //significa que han accedido a travez del enlace pero sin registrarse
            Sistema::app()->irAPagina(array("inicial","index"));
        }
        
        Sistema::app()->sesion()->borrar("usuReg");
        
        
        $this->dibujaVista("bienvenida");
        
    }
    
    
    public function accionCompras(){
        
        if(!Sistema::app()->acceso()->hayUsuario()){
            Sistema::app()->paginaError(400,"Solos los usuarios registrados pueden acceder");
            exit;
        }
        
        //Funcion que se encarga de mostrar todas las entradas que ha comprado un usuario.
        
        $resultVentas = new Ventas();
        
        $codUsu = intval(Sistema::app()->ACL()->getCodUsu(Sistema::app()->acceso()->getNick()));
        
        $opFil["where"] = "t.cod_usuario = ".$codUsu;
        
        $resultVentas = $resultVentas->buscarTodos($opFil);

        
        foreach ($resultVentas as $clave => $valor){
            
            
            $resultVentas[$clave]["fecha"]=
            CGeneral::fechaMysqlANormal($resultVentas[$clave]["fecha"]);
            
            $ent = new Entradas_usuarios();
            
            $opFil["where"] = "t.cod_venta = ".$resultVentas[$clave]["cod_venta"];
            
            $entradas = $ent->buscarTodos($opFil);
            
            $resultVentas[$clave]["importe"] = $entradas[0]["importe"]." €";
            
            
            $pase = new Pases_peliculas();
            $opFil["where"] = "t.cod_pase_pelicula = ".$entradas[0]["cod_pase_pelicula"];
            $paseResult = $pase->buscarTodos($opFil);
            
            $resultVentas[$clave]["pelicula"] = $paseResult[0]["titulo_pelicula"];
            
            
            //$cadena=CHTML::link(CHTML::imagen("/imagenes/24x24/descarga.png"));
            
            $cadena=CHTML::link("Descargar Entrada", Sistema::app()->generaURL(
                        array("usuarios","Descarga"),
                        array("cod"=>$entradas[0]["cod_entrada"])),
                        array("style"=>"color: blue;"));
            $resultVentas[$clave]["opciones"]=$cadena;
            
        }//End foreach
        
        //definiciones de las cabeceras de las
        //columnas para el CGrid
        $cabecera=array(
            
            array("CAMPO"=>"pelicula","ETIQUETA"=>"Pelicula"),
            array("CAMPO"=>"fecha","ETIQUETA"=>"Fecha Compra"),
            array("CAMPO"=>"importe","ETIQUETA"=>"Precio"),
            array("CAMPO"=>"opciones","ETIQUETA"=>"Descargar")
            
        );
        
        
        //$this->dibujaVista("indice",array("filas"=>$ventas,
        //    "cabecera"=>$cabecera),"CINES MELERO");
        
        
        
        $this->dibujaVista("compras",array("filas"=>$resultVentas,
                "cabecera"=>$cabecera),"CINES MELERO");
        
        
        
        
        
        
    }//End compras
    
    public function accionDescarga(){
        
        $codEntrada = intval($_GET["cod"]);
        
        Funcion::descargarPdf(array($codEntrada));
        
    }
    
    
    
}//End class