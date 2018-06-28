<?php

class comentariosControlador extends CControlador{
    
    
    
    public function accionIndex(){
     
        
        //Obtengo un array asociativo:::
        $obJson = json_decode(file_get_contents('php://input'), true);//String JSON ----> ARRAY ASOCIATIVO
        
        
        $codPelicula = $obJson["codPelicula"];
        $codUsuario = Sistema::app()->ACL()->getCodUsu(Sistema::app()->acceso()->getNick());
        $coment = $obJson["comentario"];
        
        $comentario = new Comentarios();
        
        $comentario->cod_comentario = 1;
        $comentario->cod_pelicula = intval($codPelicula);
        $comentario->cod_usuario = intval($codUsuario);
        $comentario->comentario = $coment;
        $comentario->fecha = date("Y-m-d");
        $comentario->tiempo = date("H:i:s");
        
        
        if($comentario->validar()){
            
            if(!$comentario->guardar()){
                $respuestaJson["insertado"] = "n";
            }
            else{
                $respuestaJson["insertado"] = "y";
            }
        }
        
        
        
        
        //echo json_encode($respuestaJson);
        
    }
    
    
    public function accionModificar(){
     
        
        //Obtengo un array asociativo:::
        $obJson = json_decode(file_get_contents('php://input'), true);//String JSON ----> ARRAY ASOCIATIVO
        
        
        $comentario = new Comentarios();
        
        $codComentario = intval($obJson["codigoComent"]);
        
        $comentario->buscarPorId($codComentario);
        
        
        $coment = $obJson["comentario"];
        
        $comentario->comentario = $coment;
        
        
        if($comentario->validar()){
            
            if(!$comentario->guardar()){
                $respuestaJson["insertado"] = "n";
            }
            else{
                $respuestaJson["insertado"] = "y";
            }
        }
        
        
        
        
        //echo json_encode($respuestaJson);
            
    }
    
    
    
    public function accionobtenerComentarios(){
        

        //Obtengo un array asociativo:::
        $obJson = json_decode(file_get_contents('php://input'), true);//String JSON ----> ARRAY ASOCIATIVO
        
        
        
        
        
        
        $codPelicula = $obJson["codPelicula"];
        
        $comentario = new Comentarios();
        
        $opFil["where"] = "t.cod_pelicula = ".$codPelicula;
        $comentarios = $comentario->buscarTodos($opFil);
        
        foreach ($comentarios as $clave=>$coment){
            $comentarios[$clave]["fecha"] = CGeneral::fechaMysqlANormal($coment["fecha"]);
            
            
            $aux = valoracion_comentarios::totalValoraciones($comentarios[$clave]["cod_comentario"]);
            
            $comentarios[$clave]["totalPositivos"] = intval($aux["positivos"]);
            $comentarios[$clave]["totalNegativos"] = intval($aux["negativos"]);
            
            
            $puedeVotar = false;
            $esMio = false;
            
            
            
            if(Sistema::app()->acceso()->hayUsuario()){
                
                $codUsu = intval(Sistema::app()->ACL()->getCodUsu(Sistema::app()->acceso()->getNick()));
                
                if(empty(valoracion_comentarios::dameValoracion($comentarios[$clave]["cod_comentario"],$codUsu))&&
                    ($codUsu!==intval($comentarios[$clave]["cod_usuario"])))
                        $puedeVotar = true;
                    
                if($codUsu===intval($comentarios[$clave]["cod_usuario"]))
                    $esMio = true;
                        
            }
            
            $comentarios[$clave]["puedeVotar"] = $puedeVotar;
            $comentarios[$clave]["esMio"] = $esMio;
            
        }
        
        
        $respuesta["comentarios"] = $comentarios;
        
        
        echo json_encode($respuesta);
        
    }//end obtenerComentarios
    
    
    
    
    
}//end controller



