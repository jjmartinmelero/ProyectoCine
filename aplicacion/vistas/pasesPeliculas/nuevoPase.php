<?php

$this->textoHead = CHTML::cssFichero("/estilos/principalBody.css");



$errores = $modelo->getErrores();


$tarjeta = new CMiTarjeta("Crear nueva sesion",array("style"=>"width: 80%;","class"=>"centrado"));

echo $tarjeta->dibujaApertura();


if(!empty($errores)){
    
    echo CHTML::dibujaEtiqueta("div",array("class"=>"alert alert-danger","style"=>"text-align:center"),"",false);
    //MUESTRO LOS ERRORES DE FORMA INDIVIDUAL, PORQUE NO QUIERO MOSTRARLOS TODOS A LA VEZ   
    echo CHTML::dibujaEtiqueta("p",array(),$errores[0]);
    
    echo CHTML::dibujaEtiquetaCierre("div");
    
}

//INICIAR FORM
echo CHTML::iniciarForm();//por defecto es action='' y POST



echo CHTML::dibujaEtiqueta("div",array(),"",false);

echo CHTML::modeloLabel($modelo, "titulo_pelicula");

echo CHTML::modeloListaDropDown($modelo, "cod_pelicula",Peliculas::damePeliculas(),array("linea"=>"Seleccione pelicula....",
    "class"=>"form-control form-control-sm"));

echo CHTML::dibujaEtiquetaCierre("div");


echo CHTML::dibujaEtiqueta("br");



echo CHTML::dibujaEtiqueta("div",array(),"",false);

echo CHTML::modeloLabel($modelo, "nombre_sala");

echo CHTML::modeloListaDropDown($modelo, "cod_sala",Salas::dameSalas(),array("linea"=>"Seleccione sala....",
    "class"=>"form-control form-control-sm"));

echo CHTML::dibujaEtiquetaCierre("div");



echo CHTML::dibujaEtiqueta("br");


echo CHTML::dibujaEtiqueta("div",array(),"",false);

echo CHTML::modeloLabel($modelo, "fecha");

echo CHTML::dibujaEtiqueta("br");

echo CHTML::modeloText($modelo, "fecha");

echo CHTML::dibujaEtiquetaCierre("div");


echo CHTML::dibujaEtiqueta("br");


echo CHTML::dibujaEtiqueta("div",array(),"",false);

echo CHTML::modeloLabel($modelo, "hora_inicio");

echo CHTML::dibujaEtiqueta("br");

echo CHTML::modeloTime($modelo, "hora_inicio");

echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiqueta("br");


echo CHTML::dibujaEtiqueta("div",array(),"",false);

echo CHTML::modeloLabel($modelo, "precio");

echo CHTML::dibujaEtiqueta("br");

echo CHTML::modeloText($modelo, "precio");

echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiqueta("br");

echo CHTML::campoBotonSubmit("AÑADIR",array("class"=>"btn danger-color-dark btn-block"));

//fin form
echo CHTML::finalizarForm();




echo $tarjeta->dibujaFin();
