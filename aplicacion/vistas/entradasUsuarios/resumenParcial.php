<?php

//dibujar tarjeta por cada asiento seleccionado


echo CHTML::dibujaEtiqueta("div",array("class"=>"card"),"",false);

echo CHTML::dibujaEtiqueta("h5",array("class"=>"card-header"),$pasePel->titulo_pelicula);

//echo CHTML::dibujaEtiqueta("h5",array("class"=>"card-title"),"2 Cabecera");

echo CHTML::dibujaEtiqueta("p",array(),"Sala: ".$nSala);

echo CHTML::dibujaEtiqueta("p",array(),"Hora: ".$horaPelicula);


echo CHTML::dibujaEtiqueta("p",array("class"=>"card-text"),"Butaca de reserva: fila ".$asiento["fila"]." butaca ".$asiento["columna"]);


//echo CHTML::campoBotonSubmit("Quitar entrada",array("class"=>"btn btn-blue btn-block"));

//echo CHTML::dibujaEtiqueta("div",array("class"=>"divBoton"),"",false);

echo CHTML::boton("QUITAR",array("class"=>"btn btn-blue","id"=>$codAsiento));

//echo CHTML::dibujaEtiquetaCierre("div");

//link del boton para quitar la entrada.
/*echo CHTML::link("Quitar Entrada",Sistema::app()->generaURL(array("entradasUsuariosControlador","mostrarResumen"),
    array("id"=>$codigo)),array("class"=>"btn btn-yellow","disabled"=>1));*/

echo CHTML::dibujaEtiquetaCierre("div");





