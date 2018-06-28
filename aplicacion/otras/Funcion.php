<?php

//Clase estatica que va a realizar diferentes operaciones

class Funcion {
    
    //Quita un usuario de la sesion
    public static function quitarUsuario(){
        
        Sistema::app()->acceso()->quitarRegistroUsuario();
        Sistema::app()->irAPagina(array("inicial","index"));
        
    }
    
    //mueve la imagen a la carpeta correspondiente
    public static function moverArchiboSubido(&$nombreImagen,$nombreCampo){
        
        if($nombreCampo === "imagen"){
            $carpeta = "caratulas";
            
        }
        else if($nombreCampo ==="imagenP") {
            $carpeta = "panoramicas";
        }
        else {
            return false;
        }
        
        
        //Validar el archivo.
        
        $archivo = $_FILES["peliculas"];
        
        $nombreOriginal = new SplFileInfo($archivo["name"][$nombreCampo]);
        $extencion = $nombreOriginal->getExtension();
        
        $nombreAleatorio = self::generarNombreAleatorio();
        
        //Para sacar el nombre de la funcion y utilizarlo en el constructor
        $nombreImagen = $nombreAleatorio.".".$extencion;
        
        
        $uploadfile = $_SERVER["DOCUMENT_ROOT"]."/imagenes/".$carpeta."/".$nombreAleatorio.".".$extencion;
        
        
        
        if (move_uploaded_file($archivo['tmp_name'][$nombreCampo], $uploadfile)){
            
            //echo "El archivo es válido y fue cargado exitosamente.\n";
            return true;
            
        }
        else {
            
            //echo "¡Posible ataque de carga de archivos!\n";
            return false;
        }
        
        
        
        
    }//End moverArchivoSubido
    
    
    //Funcion que genera un nombre aleatorio
    public static function generarNombreAleatorio() {
        
        $nombre="";
        
        for ($cont = 0;$cont<10;$cont++){
            $aleatorio = rand(97,122);//ambos incluidos
            $nombre.=chr($aleatorio);
        }
        
        return $nombre;
        
    }
    
    
    public static function eliminaImagen($tipoImagen,$nombreImagen){
        
        if($tipoImagen==="imagen")
            $carpeta = "caratulas";
        
       
        if($tipoImagen==="imagenP")
            $carpeta = "panoramicas";
        
        
       unlink(RUTA_BASE."/imagenes/".$carpeta."/".$nombreImagen);
        
            
    }//End eliminaImagen
    
    
    //$nombreCampo es imagen o imagenP
    public static function validaTipoImagen($nombreCampo){
        
        $archivo = $_FILES["peliculas"];

        
        //El tipo de archivo
        if(($archivo["type"][$nombreCampo]!="image/png")&&($archivo["type"][$nombreCampo]!="image/jpeg"))
            return false;
            
     
        if($archivo["error"][$nombreCampo]!==0)//Si es 0 es que no hay errores
            return false;
            
            
            
        return true;
            
    }
    
    
    public static function insertarButacas($codSala){
        
        $salas = new Salas();
        
        if(!$salas->buscarPorId($codSala))
            return false;
        


        $filasSala = $salas->n_filas;
        
        $colSala = $salas->n_columnas;

      //  echo $filasSala;//5
        
        
        for ($fila = 1; $fila <= $filasSala; $fila++) {
            for ($col = 1; $col <= $colSala; $col++) {
                
                
                $sentencia = "insert into asientos (cod_sala,".
                                                " fila, columna ".
                                                " ) values ( ".
                                                "$codSala, $fila, $col )";
                                            
                Sistema::app()->BD()->crearConsulta($sentencia);

                
            }//end for2

        }//end for1
        
        
    }
    

    /*
    * Funcion que envia un contenido a uno/s correos
    */

    public static function enviarCorreo($arrayCorreos, $contenido){

        if(empty($arrayCorreos))
            return false;

            
        foreach ($arrayCorreos as $correo) {
            
            
            
            $para  = "$correo";
            $titulo = 'Cines Melero';
            //$mensaje ="<html>Gracias por la compra</html>";
            $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
            $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            
            
            $tipocorreos=explode('@',$para);
            
            if ($tipocorreos['1']=='gmail.com')
                $cabeceras .= "From: CinesMelero" . "\r\n";
            else 
                $cabeceras .= 'From: CinesMelero <webmaster@cinesmelero.com>' . "\r\n";
            
            
                mail($para, $titulo, utf8_decode($contenido), $cabeceras);
        }//end foreach



    }//end enviarCorreos
    

    /*
    * Funcion que genera el contenido para notificar a los usuarios
    * sobre la publicacion de una nueva pelicula
    */
    public static function contenidoNuevaPelicula(&$cont, $nombreUsu, $pelicula){
        
        $cad = "Hola {$nombreUsu}! <br>";
        $cad.="Es un placer poder comunicarte de que una nueva pelicula se encuentra en CINES MELERO. <br>";
        
        $cad.="La pelicula es {$pelicula->titulo} y podrás reservar la entradar a partir del {$pelicula->fLanzamiento}";
        
        $cont=$cad;
        
    }


    /*
    * Funcion que genera el contenido para enviar las entradas
    * compradas por email
    */
    public static function contenidoEntradaComprada(&$cont,$codEntradas){

            $cad = "<html><head></head><body>";
        
        
            $cad.= "<p><b>CINES MELERO - Alameda</b> </p><br>";
            $cad.="<p>¡¡ Gracias por la compra !! </p>";
            $cad.="<p>A continuacion se muestra la informacion sobre su compra: </p><br>";
            
            //$cad.="<img src='".RUTA_BASE."/imagenes/caratulas/'/>"
            
            if(Sistema::app()->acceso()->hayUsuario()){
                $ent = new Entradas_usuarios();
                $entradaAux = new Entradas_usuarios();
            }
            else{
                $ent = new Entradas_anonimos();
                $entradaAux = new Entradas_anonimos();
            }

        $pase = new Pases_peliculas();
        
        $entradaAux->buscarPorId($codEntradas[0]);
        
        $pase->buscarPorId($entradaAux->cod_pase_pelicula);
    
        $cad.= "Entrada para ".$pase->titulo_pelicula."<br>";
        
        $cad.= "<p>Dia: ".$pase->fecha." a las : ".$pase->hora_inicio."</p><br>";
        $cad.= "<p>Sala: ".$pase->nombre_sala."</p><br>";
        foreach ($codEntradas as $entrada) {
            
            $asient = new Asientos();

            $ent->buscarPorId($entrada);
            
            $asient->buscarPorId($ent->cod_asiento);
            
            

            
            
            $cad.= "<p>Zona: - Fila: ".$asient->fila." - Butaca: ".$asient->columna."</p>";

        }//end foreach

        $cad.= "</body></html>";
        //return false;
        $cont = $cad;
    }


    /*
    *Funcion que provoca la descarga de un pdf con la informacion de las entradas
    */

    public static function descargarPdf($entradas){
       
        ob_start();
        $pdf = new FPDF();

        if(Sistema::app()->acceso()->hayUsuario())
            $ent = new Entradas_usuarios();
        else
            $ent = new Entradas_anonimos();
                
        
        foreach ($entradas as $codEntrada){
            
            
            $ent->buscarPorId($codEntrada);
            
            $asient = new Asientos();
            
            $asient->buscarPorId($ent->cod_asiento);
            $pasePel = new Pases_peliculas();
            
            $pasePel->buscarPorId($ent->cod_pase_pelicula);
            
            $pdf->AddPage();
            //$pdf->SetFont('Times','',16);
            
            //$pdf->Image(RUTA_BASE."/imagenes/caratulas/".$pasePel->imagen_pelicula,10,10,-300);
            
            $pdf->Cell(0, 120, "", 0, 1, 'C',$pdf->Image(RUTA_BASE."/imagenes/caratulas/".$pasePel->imagen_pelicula,10,10,-350));
            
            //$pdf->Ln(110);

            //$mensaje = "<b>CINES MELERO - Alameda</b>";
            
            $pdf->SetFont('Times','B',16);
            
            $pdf->Cell(80,10, ('CINES MELERO - Alameda'));
            $pdf->Ln();
            $pdf->SetFont('Times','',16);
            $mensaje = "Entrada para ".$pasePel->titulo_pelicula;
            $pdf->Cell(80,10, utf8_decode($mensaje));
            $pdf->Ln();
            $pdf->Cell(80,10, utf8_decode("Día: ".$pasePel->fecha." a las: ".$pasePel->hora_inicio));
            $pdf->Ln();
            $pdf->Cell(80,10, utf8_decode("Sala: ".$pasePel->nombre_sala));
            $pdf->Ln();
            $pdf->Cell(80,10, "Zona: - Fila: ".$asient->fila." - Butaca: ".$asient->columna);
      
            
            
            $pdf->Ln();
            
            $pdf->Cell(80,10, "Estas son tus entradas:");
            $pdf->Ln();
            $pdf->Cell(80,10, utf8_decode("Muestra tu código QR y accede directamente al recinto."));
            $pdf->Ln();
            $pdf->Cell(80,10, "Puedes imprimirlo o mostrarlo desde la pantalla de tu smartphone.");
           
            $pdf->Image(RUTA_BASE."/imagenes/chart.png",6,210,0);
            
            
            
           // 
           // Puedes imprimirlo o mostrarlo desde la pantalla de tu smartphone.
           // Si no ves el código QR, deberás pasar por taquilla con tu número de localizador para imprimir las entradas.
            
            //$pdf->Cell(40,10,'Hello World!');
            //$mensaje = "Gracias por comprar la entrada !!<br>";
            //$mensaje.= "su asiento esta en la Fila: ".$asient->fila." en la butaca N: ".$asient->columna;
            //$pdf->Write(0, $mensaje);
            //$pdf->Cell(40,10,$mensaje);
        }//End foreach
        
        //$pdf->Image(RUTA_BASE."/imagenes/caratulas/iPotter.jpg" , 80 ,22, 35 , 38,'JPG', 'http://www.desarrolloweb.com');
        $pdf->Output('D','cinesmelero.pdf');
        ob_end_flush();

        
    }//End descargarPDF
    
    
}//End class Funcion

