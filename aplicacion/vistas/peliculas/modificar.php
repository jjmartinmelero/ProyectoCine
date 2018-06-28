<?php

$this->textoHead = CHTML::cssFichero("/estilos/principalBody.css");
$this->textoHead .= CHTML::cssFichero("/estilos/peliculas/modificar.css");


$tarjeta =new CMiTarjeta("Modificar Pelicula",array("style"=>"width: 80%;","id"=>"tarjeta"));

echo $tarjeta->dibujaApertura();


echo CHTML::iniciarForm("","POST",array("enctype"=>"multipart/form-data"));//por defecto es action='' y POST


echo CHTML::dibujaEtiqueta("div",array("class"=>"md-form"),"",false);

echo CHTML::modeloLabel($modelo, "titulo");
echo CHTML::modeloText($modelo, "titulo",array("class"=>"form-control"));

echo CHTML::modeloError($modelo, "titulo",array("style"=>"color:red"));

echo CHTML::dibujaEtiquetaCierre("div");
//*******************************************
echo CHTML::dibujaEtiqueta("br").PHP_EOL;

echo CHTML::dibujaEtiqueta("div",array("class"=>"md-form"),"",false);

echo CHTML::modeloLabel($modelo, "director");
echo CHTML::modeloText($modelo, "director",array("class"=>"form-control"));

echo CHTML::modeloError($modelo, "director",array("style"=>"color:red"));

echo CHTML::dibujaEtiquetaCierre("div");

//******************************************
echo CHTML::dibujaEtiqueta("br").PHP_EOL;

echo CHTML::dibujaEtiqueta("div",array("class"=>"md-form"),"",false);

echo CHTML::modeloLabel($modelo, "fLanzamiento");
echo CHTML::modeloText($modelo, "fLanzamiento",array("class"=>"form-control"));

echo CHTML::modeloError($modelo, "fLanzamiento",array("style"=>"color:red"));

echo CHTML::dibujaEtiquetaCierre("div");

//*******************************************
echo CHTML::dibujaEtiqueta("br").PHP_EOL;


echo CHTML::dibujaEtiqueta("div",array(),"",false);

echo CHTML::modeloLabel($modelo, "cod_categoria");

echo CHTML::modeloListaDropDown($modelo, "cod_categoria",Categorias::dameCategorias(),array(
    "class"=>"form-control form-control-sm",
    "id"=>"lista"));

echo CHTML::modeloError($modelo, "cod_categoria",array("style"=>"color:red"));

echo CHTML::dibujaEtiquetaCierre("div");
//****************************************

echo CHTML::dibujaEtiqueta("br").PHP_EOL;

echo CHTML::dibujaEtiqueta("div",array(),"",false);


echo CHTML::modeloLabel($modelo, "sinopsis");

echo CHTML::modeloTextArea($modelo, "sinopsis",array("class"=>"form-control z-depth-1","rows"=>3,"placeholder"=>"Sinopsis..."));

echo CHTML::modeloError($modelo, "sinopsis",array("style"=>"color:red"));

echo CHTML::dibujaEtiquetaCierre("div");

//************************************************

echo CHTML::dibujaEtiqueta("br").PHP_EOL;

echo CHTML::dibujaEtiqueta("div",array(),"",false);

echo CHTML::modeloLabel($modelo, "imagen");
echo CHTML::dibujaEtiqueta("br").PHP_EOL;
echo CHTML::campoFile("peliculas[imagen]");

echo CHTML::modeloError($modelo, "imagen",array("style"=>"color:red"));

echo CHTML::dibujaEtiquetaCierre("div");

//********************************************
echo CHTML::dibujaEtiqueta("br").PHP_EOL;

echo CHTML::dibujaEtiqueta("div",array(),"",false);
echo CHTML::modeloCheckBox($modelo, "disponible",array("value"=>"1","uncheckValor"=>"0",
                                                    "etiqueta"=>"disponible"));

echo CHTML::modeloError($modelo, "disponible",array("style"=>"color:red"));

echo CHTML::dibujaEtiqueta("br").PHP_EOL;

echo CHTML::modeloCheckBox($modelo, "tendencia",array("id"=>"chkTendencia",
    "value"=>"1","uncheckValor"=>"0","etiqueta"=>"Marcar Tendencia"));

echo CHTML::modeloError($modelo, "tendencia",array("style"=>"color:red"));

echo CHTML::dibujaEtiquetaCierre("div");
//*************************************
echo CHTML::dibujaEtiqueta("br").PHP_EOL;

echo CHTML::dibujaEtiqueta("div",array(),"",false);
echo CHTML::modeloLabel($modelo, "imagenP");
echo CHTML::dibujaEtiqueta("br").PHP_EOL;
echo CHTML::campoFile("peliculas[imagenP]");
echo CHTML::modeloError($modelo, "imagenP",array("style"=>"color:red"));

echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiqueta("br").PHP_EOL;

echo CHTML::campoBotonSubmit("GUARDAR CAMBIOS",array("class"=>"btn btn-yellow"));

echo CHTML::finalizarForm();


echo CHTML::dibujaEtiqueta("br").PHP_EOL;

echo CHTML::dibujaEtiqueta("div",array("id"=>"divBotones"),"",false);


echo CHTML::link("Volver",Sistema::app()->generaURL(array("peliculas","index")),array("class"=>"btn btn-blue"));

echo CHTML::link("Consultar",Sistema::app()->generaURL(array("peliculas","consultar"),
    array("id"=>$modelo->cod_pelicula)),array("class"=>"btn btn-green"));

echo CHTML::link("Eliminar",Sistema::app()->generaURL(array("peliculas","eliminar"),
    array("id"=>$modelo->cod_pelicula)),array("class"=>"btn btn-red"));


echo CHTML::dibujaEtiquetaCierre("div");



echo $tarjeta->dibujaFin();

