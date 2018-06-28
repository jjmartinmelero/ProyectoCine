<?php

$this->textoHead = CHTML::cssFichero("/estilos/bienvenida.css");


echo CHTML::dibujaEtiqueta("div",array("id"=>"container"),"",false);

echo CHTML::dibujaEtiqueta("h1",array(),"¡ Gracias por registrarte !");

echo CHTML::dibujaEtiquetaCierre("div");


echo CHTML::dibujaEtiqueta("br");

echo CHTML::dibujaEtiqueta("div",array("id"=>"boton"),"",false);
    echo CHTML::link("Comenzar",Sistema::app()->generaURL(array("inicial","index")),array("class"=>"btn danger-color-dark"));
echo CHTML::dibujaEtiquetaCierre("div");