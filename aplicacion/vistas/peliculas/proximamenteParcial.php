<?php

echo CHTML::dibujaEtiqueta("div",array("class"=>"card"),"",false);

echo CHTML::dibujaEtiqueta("img",array("src"=>"/imagenes/caratulas/".$pelicula["imagen"],"class"=>"card-img-top","alt"=>$pelicula["titulo"]));

echo CHTML::dibujaEtiqueta("div",array("class"=>"card-body"),"",false);

echo CHTML::dibujaEtiqueta("label",array("class"=>"card-title"),$pelicula["titulo"]);


echo CHTML::dibujaEtiqueta("p",array("class"=>"card-text"),"FECHA LANZAMIENTO: ".CGeneral::fechaMysqlANormal($pelicula["fLanzamiento"]));

echo CHTML::dibujaEtiqueta("div",array("class"=>"botones"),"",false);


echo CHTML::link("INFORMACIÓN",Sistema::app()->generaURL(array("peliculas","infoPelicula"),
    array("id"=>$pelicula["cod_pelicula"])),array("class"=>"btn btn-blue"));

echo CHTML::dibujaEtiquetaCierre("div");



echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiquetaCierre("div");