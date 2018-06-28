<?php 

    class CACLBD extends CACLBase{
        
        private $_conex;
        //private $_conectado;
        
        public function __construct($BD)
        {
            $this->_conex=$BD;
                
          //  $this->_conectado=true;
            if ($this->_conex->error()!=0)
            {
                error_log("[".date("r")."][ERROR]"."No hay acceso en la base de edatos en CACLBD\n", 3, "log/error.log");
            //    $this->_conectado=false;
                throw new Exception("ACL: No se ha podido crear la conexion");
            }
            
            
            
        }
        
        public function anadirRole($nombre, $puedeAcceder, $puedeConfigurar)
        {
            $nombre=$this->_conex->getEnlace()->real_escape_string(strtolower(trim(substr($nombre,0,30))));
            if (!is_bool($puedeAcceder))
                return false;
                
            if (!is_bool($puedeConfigurar))
                return false;
            
            $puedeAcceder=intval($puedeAcceder);
            $puedeConfigurar=intval($puedeConfigurar);
                
            if ($this->getCodRole($nombre)===false)
                {
                    $sentencia="insert into acl_roles (".
                               "       nombre, puedeAcceder, puedeConfigurar,borrado ".
                               "   ) values (".
                               "       '$nombre', $puedeAcceder, $puedeConfigurar,false ".
                               "   ) ";
                    
                    if ($this->_conex->crearConsulta($sentencia)->error()!==0)
                        return false;
                        
                    return true;
                }
                else
                    return false;
        }
        
         public function getCodRole($nombre)
         {
             $nombre=$this->_conex->getEnlace()->real_escape_string(strtolower(trim(substr($nombre,0,30))));
             
             $sentencia="SELECT cod_role FROM acl_roles where nombre='$nombre'";
             $salida=$this->_conex->crearConsulta($sentencia);
             if ($salida->error()!==0)
                  return false;
             
             if ($salida->numFilas()==0)
                 return false;
                else
                  {
                      $fila=$salida->fila();
                      return ($fila["cod_role"]);
                  }
             
         }
        
         public function getCodUsu($nick)
         {
             $nick=$this->_conex->getEnlace()->real_escape_string(trim(substr($nick,0,30)));
             
             $sentencia="SELECT cod_usuario FROM acl_usuarios where nick='$nick'";
             $salida=$this->_conex->crearConsulta($sentencia);
             if ($salida->error()!==0)
                 return false;
                 
                 if ($salida->numFilas()==0)
                     return false;
                     else
                     {
                         $fila=$salida->fila();
                         return ($fila["cod_usuario"]);
                     }
                     
         }
         
         
         
         public function existeRole($codRol)
         {
             $codRol=intval($codRol);
             
             $sentencia="SELECT cod_role FROM acl_roles where cod_role=$codRol";
             
             $salida=$this->_conex->crearConsulta($sentencia);
             if ($salida->error()!==0)
                 return false;
                     
             return($salida->numFilas()!=0);
         }
        
         public function anadirUsuario($nombre,$nick, $contrasena, $correo, $codRol, $notificaciones)
         {
             $nick=$this->_conex->getEnlace()->real_escape_string(trim(substr($nick,0,20)));
             $nombre=$this->_conex->getEnlace()->real_escape_string(strtolower(trim(substr($nombre,0,30))));
             $contrasena=$this->_conex->getEnlace()->real_escape_string(trim(substr($contrasena,0,30)));
             $correo=$this->_conex->getEnlace()->real_escape_string(strtolower(trim(substr($correo,0,50))));
             
             if (!$this->existeRole($codRol))
                  return false;
             
             if ($this->existeUsuario($nick))
                  return false;
             
             $sentencia="insert into acl_usuarios (".
                        "       nombre,nick,contrasenia, correo,cod_role, notificaciones      ".
                        "            ) values (".
                        "       '$nombre','$nick',sha2('$contrasena',256),'$correo',$codRol, $notificaciones      ".
                        "            ) ";
             $salida=$this->_conex->crearConsulta($sentencia);
             return ($salida->error()==0);
         }
        
         public function existeUsuario($nick)
         {
             $nick=$this->_conex->getEnlace()->real_escape_string(trim(substr($nick,0,20)));
             
             $sentencia="SELECT cod_usuario FROM acl_usuarios where nick='$nick'";
             
             $salida=$this->_conex->crearConsulta($sentencia);
             if ($salida->error()!==0)
                 return false;
                 
             return ($salida->numFilas()!=0);
             
         }
        
         function esValido($nick, $contrasena)
         {
             $nick=$this->_conex->getEnlace()->real_escape_string(trim(substr($nick,0,20)));
             $contrasena=$this->_conex->getEnlace()->real_escape_string(trim($contrasena));
             
             $sentencia="SELECT cod_usuario FROM acl_usuarios where nick='$nick'".
                        "      and contrasenia=sha2('$contrasena',256)";
             
             $salida=$this->_conex->crearConsulta($sentencia);
             if ($salida->error()!==0)
                 return false;
                 
             return ($salida->numFilas()!=0);
         }
        
         function getPermisos($nick, &$puedeAcceder, &$puedeAdministrar)
         {
             $nick=$this->_conex->getEnlace()->real_escape_string(trim(substr($nick,0,20)));
             
             $sentencia="SELECT ar.puedeAcceder, ar.puedeConfigurar ".
                        "      FROM acl_usuarios au ".
                        "           join acl_roles ar using (cod_role) ".
                        "      where au.nick='$nick'";
             
             $salida=$this->_conex->crearConsulta($sentencia);
             if ($salida->error()!==0)
                 return false;
                 
             if ($salida->numFilas()==0)
                 return false;
             
             $fila=$salida->fila();
             $puedeAcceder=$fila["puedeAcceder"];
             $puedeAdministrar=$fila["puedeConfigurar"];
             return true;
         }
        
         function getNombre($nick)
         {
             $nick=$this->_conex->getEnlace()->real_escape_string(trim(substr($nick,0,20)));
             
             $sentencia="SELECT nombre ".
                 "      FROM acl_usuarios  ".
                 "      where nick='$nick'";
             
             $salida=$this->_conex->crearConsulta($sentencia);
             if ($salida->error()!==0)
                 return false;
                 
             if ($salida->numFilas()==0)
                 return false;
                 
             $fila=$salida->fila();
             return $fila["nombre"];
         }
        
         function setNombre($nick, $nombre)
         {
             $nick=$this->_conex->getEnlace()->real_escape_string(trim(substr($nick,0,20)));
             $nombre=$this->_conex->getEnlace()->real_escape_string(strtolower(trim(substr($nombre,0,30))));
             
             $sentencia="update acl_usuarios ".
                 "           set nombre='$nombre' ".
                 "      where nick='$nick'";
            
             $salida=$this->_conex->crearConsulta($sentencia);
                
             return ($salida->error()==0);
         }
        
         function dameUsuarios()
         {
             $sentencia="SELECT cod_usuario,nick ".
                 "      FROM acl_usuarios  ".
                 "      order by cod_usuario";
             
             $salida=$this->_conex->crearConsulta($sentencia);
             if ($salida->error()!==0)
                 return false;
                 
             $datos=[];    
             while ($fila=$salida->fila())
             {
                 $datos[$fila["cod_usuario"]]=$fila["nick"];
                 
             }
                 
             return $datos;
         }
        
         function dameRoles()
         {
             $sentencia="SELECT cod_role,nombre ".
                 "      FROM acl_roles  ".
                 "      order by cod_role";
             
             $salida=$this->_conex->crearConsulta($sentencia);
             if ($salida->error()!==0)
                 return false;
                 
                 
             $datos=[];
             while ($fila=$salida->fila())
             {
                 $datos[$fila["cod_role"]]=$fila["nombre"];
                 
             }
             
             return $datos;
         }
        
        
        
        
        
    }