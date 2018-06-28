<?php

echo CHTML::dibujaEtiqueta("div",array("class"=>"card card-cascade wider reverse my-4"),"",false);

//imagen
echo CHTML::dibujaEtiqueta("div",array("class"=>"view overlay"),"",false);

echo CHTML::imagen("/imagenes/panoramicas/".$pelicula["imagenP"],$pelicula["titulo"]);


echo CHTML::dibujaEtiqueta("a",array("href"=>Sistema::app()->generaURL(array("peliculas","infoPelicula"),array("id"=>$pelicula["cod_pelicula"]))),"",false);

echo CHTML::dibujaEtiqueta("div",array("class"=>"mask rgba-white-slight"),"",false);
echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiquetaCierre("a");




echo CHTML::dibujaEtiquetaCierre("div");


//contenido

echo CHTML::dibujaEtiqueta("div",array("class"=>"card-body text-center"),"",false);

//titulo
echo CHTML::dibujaEtiqueta("h5",array("class"=>"card-title"),"",false);

echo CHTML::dibujaEtiqueta("strong",array(),$pelicula["titulo"]);

echo CHTML::dibujaEtiquetaCierre("h5");

echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiquetaCierre("div");
