<?php

$this->textoHead=CHTML::scriptFichero("/js/registrarUsuario.js");
$this->textoHead.= CHTML::cssFichero("/estilos/registrarUsuario.css");

$errores = $modelo->getErrores();


echo CHTML::dibujaEtiqueta("div",array("class"=>"modal-dialog"),"",false);

echo CHTML::dibujaEtiqueta("div",array("class"=>"modal-content"),"",false);

//header
echo CHTML::dibujaEtiqueta("div",array("class"=>"modal-header danger-color-dark white-text"),"",false);

echo CHTML::dibujaEtiqueta("h4", array("class"=>"title"),"",false);
echo CHTML::dibujaEtiqueta("i",array("class"=>"fa fa-pencil"),"",false);
echo CHTML::dibujaEtiquetaCierre("i");
echo " ¡Regístrate!";
echo CHTML::dibujaEtiquetaCierre("h4");

echo CHTML::dibujaEtiquetaCierre("div");

//body
echo CHTML::dibujaEtiqueta("div",array("class"=>"modal-body"),"",false);
    echo CHTML::dibujaEtiqueta("div",array("class"=>"panel-body"),"",false);
    
    //Mensaje de error si hubiera
    if(!empty($errores)){
        
        echo CHTML::dibujaEtiqueta("div",array("class"=>"alert alert-danger","style"=>"text-align:center"),"",false);
        
        
        echo CHTML::dibujaEtiqueta("p",array(),$errores[0]);
        
        echo CHTML::dibujaEtiquetaCierre("div");
        
    }
    
    
    //INICIAR FORM
    echo CHTML::iniciarForm("","POST",array("id"=>"formRegistro","autocomplete"=>"off"));
    
    echo CHTML::dibujaEtiqueta("div",array("class"=>"md-form"),"",false);
    
    echo CHTML::modeloText($modelo, "nombre",array("class"=>"form-control","id"=>"inputNombre"));
    echo CHTML::modeloLabel($modelo, "nombre",array("for"=>"inputNombre","style"=>"font-weight:normal"));
    
   // echo CHTML::dibujaEtiqueta("input",array("type"=>"text","class"=>"form-control","id"=>"inputNombre"));
   // echo CHTML::dibujaEtiquetaCierre("input");
   // echo CHTML::dibujaEtiqueta("label",array("for"=>"inputNombre"),"",false);
   // echo CHTML::dibujaEtiqueta("strong",array(),"Nombre");
    //echo CHTML::dibujaEtiquetaCierre("label");
    
    echo CHTML::dibujaEtiquetaCierre("div");
    
   
    echo CHTML::dibujaEtiqueta("div",array("class"=>"md-form"),"",false);
    
    echo CHTML::modeloText($modelo, "nick",array("class"=>"form-control","id"=>"inputNick"));
    echo CHTML::modeloLabel($modelo, "nick",array("for"=>"inputNick","style"=>"font-weight:normal"));
    
    
    echo CHTML::dibujaEtiquetaCierre("div");
    
    
    echo CHTML::dibujaEtiqueta("div",array("class"=>"md-form"),"",false);
    
    echo CHTML::modeloText($modelo, "correo",array("class"=>"form-control","id"=>"inputCorreo"));
    echo CHTML::modeloLabel($modelo, "correo",array("for"=>"inputCorreo","style"=>"font-weight:normal"));

    
    echo CHTML::dibujaEtiquetaCierre("div");
    
    
    echo CHTML::dibujaEtiqueta("div",array("class"=>"row"),"",false);
    
    echo CHTML::dibujaEtiqueta("div",array("class"=>"col-xs-6 col-sm-6 col-md-6"),"",false);
        
    echo CHTML::dibujaEtiqueta("div",array("class"=>"md-form"),"",false);
    
    echo CHTML::campoPassword("contrasenia","",array("class"=>"form-control","id"=>"inputContra"));
    //echo CHTML::modeloPassword($modelo, "contrasenia",array("class"=>"form-control","id"=>"inputContra"));
    echo CHTML::modeloLabel($modelo, "contrasenia",array("for"=>"inputContra","style"=>"font-weight:normal"));
    
    
    echo CHTML::dibujaEtiquetaCierre("div");
    
    echo CHTML::dibujaEtiquetaCierre("div");
    
    
    echo CHTML::dibujaEtiqueta("div",array("class"=>"col-xs-6 col-sm-6 col-md-6"),"",false);
    
    echo CHTML::dibujaEtiqueta("div",array("class"=>"md-form"),"",false);
    
    
    echo CHTML::campoPassword("segundaContrasenia","",array("class"=>"form-control","id"=>"inputContra2"));
    //echo CHTML::modeloPassword($modelo, "",array("class"=>"form-control","id"=>"inputContra2"));
    echo CHTML::modeloLabel($modelo, "segundaContrasenia",array("for"=>"inputContra2","style"=>"font-weight:normal"));
    
    
    
    echo CHTML::dibujaEtiquetaCierre("div");
    
    echo CHTML::dibujaEtiquetaCierre("div");
    

    
    echo CHTML::dibujaEtiquetaCierre("div");//row
    
    echo CHTML::dibujaEtiqueta("br");
    
    echo CHTML::dibujaEtiqueta("div",array(),"",false);
    
    echo CHTML::modeloCheckBox($modelo, "notificaciones",array("value"=>"1","uncheckValor"=>"0","etiqueta"=>"¿ Quieres recibir notificaciones ?"));
    
    echo CHTML::dibujaEtiquetaCierre("div");
    
    echo CHTML::dibujaEtiqueta("br");
    
    echo CHTML::boton("REGISTRAR",array("class"=>"btn danger-color-dark btn-block","id"=>"bRegistrar"));
    
    //fin form
    echo CHTML::finalizarForm();
    
    echo CHTML::dibujaEtiquetaCierre("div");
    echo CHTML::dibujaEtiquetaCierre("div");
    echo CHTML::dibujaEtiquetaCierre("div");
    echo CHTML::dibujaEtiquetaCierre("div");