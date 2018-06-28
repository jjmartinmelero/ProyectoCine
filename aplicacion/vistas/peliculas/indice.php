<?php

$this->textoHead = CHTML::cssFichero("/estilos/principalBody.css");
$this->textoHead.= CHTML::cssFichero("/estilos/indiceAdmin.css");
$this->textoHead.=CHTML::scriptFichero("/js/indiceAdmin.js");

//se crea la tabla

$tabla=new MiTabla($cabecera,$filas);




//Tarjeta cotnenedor
echo CHTML::dibujaEtiqueta("div",array("class"=>"card"),"",false);

echo CHTML::dibujaEtiqueta("div",array("class"=>"card"),"",false);

echo CHTML::dibujaEtiqueta("div",array("class"=>"card-header danger-color-dark white-text text-center"),
    "GESTOR DE PELÍCULAS");

echo CHTML::dibujaEtiqueta("div",array("class"=>"card-body"),"",false);

//CONTENIDO *************************

echo CHTML::dibujaEtiqueta("div",array("class"=>"alert alert-info","style"=>"width:40%;"),"<i class='fa fa-info-circle' aria-hidden='true'></i>".
                                                " Dar a la lupa para ver ".
                                                "la sinopsis completa y las imágenes");


echo $tabla->dibujate();


echo CHTML::dibujaEtiqueta("br");
echo CHTML::link("Crear Película",Sistema::app()->generaURL(array("peliculas","nuevo")),array("class"=>"btn danger-color-dark"));


echo CHTML::dibujaEtiqueta("hr");


echo CHTML::dibujaEtiqueta("div",array("id"=>"dGrafico","class"=>"alert alert-info","style"=>"width:40%;"),"<i class='fa fa-info-circle' aria-hidden='true'></i>".
    " El contenido de la gráfica se actualiza cada 10 segundos.");


echo CHTML::dibujaEtiqueta("div",array("id"=>"chartContainer"));




//END CONTENIDO *********************
echo CHTML::dibujaEtiquetaCierre("div");


echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiquetaCierre("div");


