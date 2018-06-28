<?php


class Usuarios extends CActiveRecord{

// método que devuelve el nombre que se asignará al modelo. (para formularios)
protected function fijarNombre(){
    return 'usu';
}

//PARA EL SOPORTE DE BDD********************************************
protected function fijarTabla(){
    
    return "cons_usuarios";//Nombre de la tabla
}


//establece el campo que se corresponde con la clave principal de la tabla, en este caso codArticulo
protected function fijarId(){
    return "cod_usuario";
}


protected function fijarAtributos(){
    
    return array("cod_usuario","nombre",
        "nick","contrasenia","correo",
        "cod_role","notificaciones","borrado","puede_acceder","puede_configurar","segundaContrasenia");
    
}//End fijarAtributos

public function fijarDescripciones(){
    return array("cod_usuario"=>"Codigo Usuario",
        "nombre"=>"Nombre",
        "nick"=>"Nick",
        "contrasenia"=>"Contraseña",
        "correo"=>"Correo electrónico",
        "cod_role"=>"Codigo Rol",
        "notificaciones"=>"notificaciones",
        "borrado"=>"Borrado",
        "puedeAcceder"=>"Puede acceder",
        "puedeConfigurar"=>"Puede configurar",
        "segundaContrasenia"=>"Confirmar contraseña"
    );
}

protected function fijarRestricciones(){
    Return
    array(array("ATRI"=>"cod_usuario , cod_role",
        "TIPO"=>"REQUERIDO"),
        array("ATRI"=>"cod_ususario, cod_role",
            "TIPO"=>"ENTERO",
            "MIN"=>0),
        array("ATRI"=>"nombre","TIPO"=>"CADENA",
            "TAMANIO"=>30,
            "MENSAJE"=>"Nombre Usuario no puede terner mas de 30 caracteres"
        ),
        array("ATRI"=>"nick",
            "TIPO"=>"CADENA",
            "TAMANIO"=>20,
            "MENSAJE"=>"El nick no puede ser superior a 20 caracteres"
        ),
        array("ATRI"=>"contrasenia","TIPO"=>"CADENA",
            "TAMANIO"=>30,
            "MENSAJE"=>"La contraseña debe ser entre 8 y 30 caracteres"
        ),
        array("ATRI"=>"segundaContrasenia","TIPO"=>"CADENA",
            "TAMANIO"=>30,
            "MENSAJE"=>"La contraseña debe ser entre 8 y 30 caracteres"
        ),
        array("ARI"=>"notificaciones","TIPO"=>"ENTERO",
            "MIN"=>0,"MAX"=>1
        ),
        array("ATRI"=>"correo","TIPO"=>"CADENA",
                "TAMANIO"=>50),
        array("ATRI"=>"borrado", "TIPO"=>"ENTERO",
            "MIN"=>0, "MAX"=>1
        ),
        array("ATRI"=>"puedeAcceder","TIPO"=>"ENTERO",
              "MIN"=>0,"MAX"=>1
        ),
        array("ARI"=>"puedeConfigurar","TIPO"=>"ENTERO",
                "MIN"=>0,"MAX"=>1
        ),
        array("ARI"=>"notificaciones","TIPO"=>"ENTERO",
            "MIN"=>0,"MAX"=>1
        ),
        array("ATRI"=>"segundaContrasenia","TIPO"=>"FUNCION",
            "FUNCION"=>"verificaPass"
        ),
        array("ATRI"=>"nick","TIPO"=>"FUNCION",
             "FUNCION"=>"validaNick"
        ),
        array("ATRI"=>"nombre, nick, correo, contrasenia, segundaContrasenia",
            "TIPO"=>"REQUERIDO"),
        array("ATRI"=>"correo","TIPO"=>"FUNCION","FUNCION"=>"validaCorreo"),
        array("ATRI"=>"contrasenia","TIPO"=>"FUNCION",
                "FUNCION"=>"seguridadPass"
        ),
        array("ATRI"=>"nombre","TIPO"=>"FUNCION","FUNCION"=>"espaciosBlanco")
        
//        array("ATRI"=>"contrasenia","TIPO"=>"FUNCION",
//            "FUNCION"=>"encriptarPass"
//        )
        
    );
    
}//End Restricciones


public function espaciosBlanco(){
    
    //supongo que se podra comprobar con un foreach porque implementa iterator
    
    if(trim($this->nombre)==="")
        $this->setError("nombre", "No es correcto");
    
        
    if(trim($this->nick)==="")
        $this->setError("nick", "No es correcto");
    
    if(trim($this->correo)==="")
        $this->setError("correo", "No es correcto");
    
    if(trim($this->contrasenia)==="")
        $this->setError("contrasenia", "No es correcto");
    
}


public function validaCorreo(){
    
    $correo = $this->correo;
    
    if(!CValidaciones::validaEMail($correo)){
        
        $this->setError("correo", "Formato incorrecto");
        
    }
    
}


public function seguridadPass(){
                //la segundaContrasenia la omito, porque si no son iguales va a dar un error al usuario
    if(strlen($this->contrasenia)<8){
        $this->setError("contrasenia", "Minimo 8 caracteres");
    }
    
    
    
}


public function validaNick(){
    
    if(strlen($this->nick)<5){
        $this->setError("nick", "Minimo 5 caracteres");
    }
    else if(Sistema::app()->ACL()->existeUsuario($this->nick))
                $this->setError("nick", "Ya se encuentra registrado");
    
}

/* LO HACE LA ACL
public function encriptarPass(){
    //la cadena vacia la encripta
    if($this->contrasenia!==""&&$this->segundaContrasenia!==""){
        $this->contrasenia = sha1($this->contrasenia);
        $this->segundaContrasenia = sha1($this->segundaContrasenia);
    }
}//End encriptar
*/

public function verificaPass(){
    
    if($this->contrasenia!==$this->segundaContrasenia){
        $this->setError("contrasenia", "La contraseña no coincide");
    }
    
}



// método se usa para asignar a los atributos del modelo los valores por defecto que deseemos.
protected function afterCreate(){
    
    $this->cod_usuario=0;
    $this->cod_role=0;
    $this->nombre="";
    $this->nick="";
    $this->cantrasenia="";
    $this->borrado = 0;
    $this->correo="";
    $this->notificaciones =1;
}

protected function afterBuscar(){}


//No pongo la sentencia insertar porque utilizo la aclBd
//protected function fijarSentenciaInsert(){}//End sentenciaInsert

protected function fijarSentenciaUpdate(){}//End sentenciaUpdate







}//End class