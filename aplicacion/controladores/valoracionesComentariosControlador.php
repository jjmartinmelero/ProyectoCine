<?php

class valoracionesComentariosControlador extends CControlador{
    
    
    
    public function accionIndex(){
        
        
        //Obtengo un array asociativo:::
        $obJson = json_decode(file_get_contents('php://input'), true);//String JSON ----> ARRAY ASOCIATIVO
        
        
        $opinionUsu = intval($obJson["votoUsu"]);
        $codMensaje = intval($obJson["codMensaje"]);
        $codUsuario = Sistema::app()->ACL()->getCodUsu(Sistema::app()->acceso()->getNick());
        
        
        
        $vComentario = new valoracion_comentarios();
        
        $vComentario->cod_usuario = $codUsuario;
        $vComentario->cod_comentario = $codMensaje;
        $vComentario->opinion = $opinionUsu;
        $vComentario->reportado = 0;
        $vComentario->cod_valoracion = 1;
        
        if($vComentario->validar()){
            
            if(!$vComentario->guardar()){
                $respuestaJson["insertado"] = "n";
            }
            else{
                $respuestaJson["insertado"] = "y";
            }
        }
        
        
    }//end index
    
 
    
    
}//end controller

