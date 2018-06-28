<?php

abstract class CACLBase {
    
    //$_rol=array("nombre"=>"",codRol=>-1, "puedeAcceder"=>"", "puedeConfigurar"=>"");
    //$_usuario=array("nombre"=>"", "nick"=>"", "contrasena"=>"", codRol=>-1);
    
    abstract public function anadirRole($nombre, $puedeAcceder, $puedeConfigurar);
    
    abstract public function getCodRole($nombre);
    
    abstract public function existeRole($codRol);
    
    abstract public function anadirUsuario($nombre,$nick, $contrasena,$correo, $codRol, $notificacion);
    
    abstract public function existeUsuario($nick);
    
    abstract function esValido($nick, $contrasena);
    
    abstract function getPermisos($nick, &$puedeAcceder, &$puedeAdministrar);
    
    abstract function getNombre($nick);
    
    abstract function setNombre($nick, $nombre);
    
    abstract function dameUsuarios();
    
    abstract function dameRoles();
    
        
    
    
}