<?php

//Validar que hay un usuario registrado. Aqui puede acceder tenga o no permisos de CONFIGURAR, pero si de ACCEDER
if(!Sistema::app()->acceso()->puedeAcceder()){
    Sistema::app()->paginaError(404,"Solo los usuarios registrados pueden acceder");
    exit;
}

$this->textoHead = CHTML::cssFichero("/estilos/principalBody.css");
$this->textoHead.= CHTML::cssFichero("/estilos/configuracion.css");
$this->textoHead.=CHTML::scriptFichero("/js/configuracion.js");


//Tarjeta cotnenedor
echo CHTML::dibujaEtiqueta("div",array("class"=>"card", "id"=>"cardContainer"),"",false);

echo CHTML::dibujaEtiqueta("div",array("class"=>"card text-center"),"",false);

echo CHTML::dibujaEtiqueta("div",array("class"=>"card-header danger-color-dark white-text"),
                                                "¡Bienvenido/a ".Sistema::app()->acceso()->getNombre()."!");

echo CHTML::dibujaEtiqueta("div",array("class"=>"card-body"),"",false);

//CONTENIDO *************************

//TARJETA *********************

echo CHTML::dibujaEtiqueta("div",array("class"=>"card","id"=>"divForm"),"",false);

echo CHTML::dibujaEtiqueta("div",array("class"=>"card-body"),"",false);

echo CHTML::dibujaEtiqueta("h4",array("class"=>"card-title"),"",false);

echo "PERSONALIZAR";

echo CHTML::dibujaEtiquetaCierre("h4");


echo CHTML::dibujaEtiqueta("p",array("class"=>"card-text"),"",false);

echo "¡¡Personalice el fondo de la aplicación!!";

echo CHTML::dibujaEtiquetaCierre("p");

echo CHTML::iniciarForm("","POST",array("id"=>"formTema"));

echo CHTML::dibujaEtiqueta("input",array("type"=>"color","name"=>"temaApp", "id"=>"iColor","value"=>Sistema::app()->colorApp));

echo CHTML::boton("APLICAR",array("class"=>"btn danger-color-dark","id"=>"bAplicar"));

echo CHTML::finalizarForm();

echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiquetaCierre("div");


//FIN TARJETA ********************************




//TARJETA *********************

echo CHTML::dibujaEtiqueta("div",array("class"=>"card", "id"=>"tNoti"),"",false);

echo CHTML::dibujaEtiqueta("div",array("class"=>"card-body"),"",false);

echo CHTML::dibujaEtiqueta("h4",array("class"=>"card-title"),"",false);

echo "Notificaciones";

echo CHTML::dibujaEtiquetaCierre("h4");


echo CHTML::dibujaEtiqueta("p",array("class"=>"card-text"),"",false);

echo CHTML::iniciarForm();

if($usu->notificaciones){
    echo "Actualmente estas recibiendo las notificaciones sobres nuevas peliculas, ¿Quieres de dejar de recibirlas?";
    echo CHTML::dibujaEtiquetaCierre("p");
    echo CHTML::campoBotonSubmit("DESACTIVAR",array("id"=>"bNoti","class"=>"btn danger-color-dark"));
    
}
else {
    echo "¡¡Puedes activar las notificaciones para estar siempre a la última !!";
    echo CHTML::dibujaEtiquetaCierre("p");
    echo CHTML::campoBotonSubmit("ACTIVAR",array("id"=>"bNoti","class"=>"btn danger-color-dark"));
    
}

echo CHTML::modeloHidden($usu, "cod_usuario");
//echo CHTML::modeloHidden($usu, "notificaciones");             //valor directo al de usu
echo CHTML::campoHidden("notificaciones",($usu->notificaciones)?"0":"1");

echo CHTML::finalizarForm();

echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiquetaCierre("div");


//FIN TARJETA ********************************





//TARJETA *********************

echo CHTML::dibujaEtiqueta("div",array("class"=>"card", "id"=>"tNoti"),"",false);

echo CHTML::dibujaEtiqueta("div",array("class"=>"card-body"),"",false);

echo CHTML::dibujaEtiqueta("h4",array("class"=>"card-title"),"",false);

echo "Cambiar Nick";

echo CHTML::dibujaEtiquetaCierre("h4");


echo CHTML::dibujaEtiqueta("p",array("class"=>"card-text"),"",false);

echo CHTML::iniciarForm();

echo CHTML::dibujaEtiqueta("p",array(),"Cambie su nick de usuario");

echo CHTML::campoText("nick","");

echo CHTML::modeloHidden($usu, "cod_usuario");

echo CHTML::dibujaEtiqueta("br");

echo CHTML::campoBotonSubmit("CAMBIAR",array("id"=>"bCambiar","class"=>"btn danger-color-dark"));

echo CHTML::finalizarForm();

echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiquetaCierre("div");


//FIN TARJETA ********************************






//END CONTENIDO *********************
echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiqueta("div",array("class"=>"card-footer text-muted danger-color-dark white-text"),"",false);
    echo CHTML::dibujaEtiqueta("p",array("class"=>"mb-0"),"");
echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiquetaCierre("div");









