<?php
class CAcceso {
    // defino las propiedades de la clase
    private $_validado;
    private $_nick;
    private $_nombre;
    private $_puedeAcceder;
    private $_puedeConfigurar;
    private $_objetoSesion;
    // constructor
    public function __construct($objSesion){
     
        $this->_objetoSesion = $objSesion;
        
        $this->_validado=false;   
      
     // partiendo de los datos de sesión compruebo si está validado
     // para tomar los valores del usuario registrado
     if(isset($this->_objetoSesion->get("usuario")["validado"]))
     {  
         $this->_validado=$this->_objetoSesion->get("usuario")["validado"];
        if ($this->_validado)
        {
            $this->_nick           =$this->_objetoSesion->get("usuario")["nick"];
            $this->_nombre         =$this->_objetoSesion->get("usuario")["nombre"];
            $this->_puedeAcceder   =$this->_objetoSesion->get("usuario")["puedeAcceder"];
            $this->_puedeConfigurar=$this->_objetoSesion->get("usuario")["puedeConfigurar"];
        }//if validado
     }//ifisset   
    }//constructor
    
    // métodos de instancia
    /**
     * Sirve para registrar un usuario en la aplicación. 
     *  Almacena los valores en las propiedades apropiadas, 
        y en la sesión, para guardar en la sesión la 
        información del usuario validado
     * @param  $nick
     * @param  $nombre
     * @param  $puedeAcceder
     * @param  $puedeConfigurar
     */
    public function registrarUsuario( $nick, $nombre, $puedeAcceder, $puedeConfigurar)
    {
      // asigno los datos pasados por parámetros y valido como registrado
        $this->_validado       = true;
        $this->_nick           = $nick;
        $this->_nombre         = $nombre;
        $this->_puedeAcceder   = $puedeAcceder;
        $this->_puedeConfigurar= $puedeConfigurar;
       
        if (isset($this->_objetoSesion))
            {
                // doy la información del usuario registrado a la sesión de éste
                $arrayDatos["validado"]       = true;
                $arrayDatos["nick"]           = $this->_nick;
                $arrayDatos["nombre"]         = $this->_nombre;
                $arrayDatos["puedeAcceder"]   = $this->_puedeAcceder;
                $arrayDatos["puedeConfigurar"]= $this->_puedeConfigurar;
                
                $this->_objetoSesion->set("usuario",$arrayDatos);
                return true;
            }
      return false;
    }//registrarUsuario()
    
    /**
     * Hace que no haya ningún usuario registrado en el sistema
     */
    public function quitarRegistroUsuario()
    {
        // unset($_SESSION["validado"]);
        $this->_validado = false;
        if (isset($this->_objetoSesion))
        {
            // doy la información del usuario registrado a la sesión de éste
            $this->_objetoSesion->set("usuario",["validado"=>false]);
            

        }  
        return true;
    }// quitarRegistro()
    
    /**
     *  Devuelve true si hay un usuario validado 
     *  y false en caso contrario
     */
    public function hayUsuario()
    {
        return ($this->_validado);
    }//hayUsuario()
    
    /**
     * Devuelve true si puede acceder el usuario validado 
     * y false en caso contrario
     */
    public function puedeAcceder()
    {
       // primero miro si está validado correctamente el usuario
       if($this->hayUsuario())
       {   
           return ($this->_puedeAcceder);
       }//if validado     
       return false;     
    }// puedeAcceder()
    
    /**
     * Devuelve true si puede configurar el usuario validado
     * y false en caso contrario
     */
    public function puedeConfigurar()
    {
      // primero miro si está validado correctamente el usuario
      if($this->hayUsuario())
        {
          return ($this->_puedeConfigurar);
        }//if validado
      return false;     
    }//puedeConfigurar()
    
    /**
     * devuelve el Nick del usuario registrado
     */
    public function getNick()
    {
       // compruebo si está validado
       if($this->hayUsuario())
       {
           return $this->_nick;
       }
       return false;
    }//getNick()
    
    /**
     * devuelve el nombre del usuario registrado
     */
    public function getNombre()
    {
       if($this->hayUsuario())
       {
         return $this->_nombre;    
       }
      return false; 
    }//getNombre()
    
    
}// class 