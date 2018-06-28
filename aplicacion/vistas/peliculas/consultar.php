<?php

$this->textoHead = CHTML::cssFichero("/estilos/principalBody.css");
$this->textoHead.= CHTML::cssFichero("/estilos/consultar.css");

$tarjeta =new CMiTarjeta("Informacion Pelicula",array("style"=>"width: 80%;","id"=>"tarjeta"));

echo $tarjeta->dibujaApertura();




echo CHTML::dibujaEtiqueta("div",array("class"=>"md-form"),"",false);


echo CHTML::imagen("/imagenes/caratulas/".$modelo->imagen,"",array("id"=>"img","class"=>"img-fluid"));

echo CHTML::dibujaEtiquetaCierre("div");

//*******************************


echo CHTML::dibujaEtiqueta("div",array("class"=>"md-form"),"",false);

echo CHTML::modeloLabel($modelo, "titulo");
echo CHTML::modeloText($modelo, "titulo",array("class"=>"form-control","readonly"=>1));


echo CHTML::dibujaEtiquetaCierre("div");

//***************************


echo CHTML::dibujaEtiqueta("div",array("class"=>"md-form"),"",false);

echo CHTML::modeloLabel($modelo, "director");
echo CHTML::modeloText($modelo, "director",array("class"=>"form-control","readonly"=>1));


echo CHTML::dibujaEtiquetaCierre("div");


//***************************

echo CHTML::dibujaEtiqueta("div",array("class"=>"md-form"),"",false);

echo CHTML::modeloLabel($modelo, "fLanzamiento");
echo CHTML::modeloText($modelo, "fLanzamiento",array("class"=>"form-control","readonly"=>1));


echo CHTML::dibujaEtiquetaCierre("div");


//*******************************

echo CHTML::dibujaEtiqueta("div",array("class"=>"md-form"),"",false);

echo CHTML::modeloLabel($modelo, "nombre_categoria");

echo CHTML::modeloText($modelo, "nombre_categoria",array("class"=>"form-control","readonly"=>1));

echo CHTML::dibujaEtiquetaCierre("div");

//****************************

echo CHTML::dibujaEtiqueta("div",array("class"=>"md-form"),"",false);

echo CHTML::modeloLabel($modelo, "sinopsis");

echo CHTML::modeloTextArea($modelo, "sinopsis",array("class"=>"form-control z-depth-1","rows"=>3,"readonly"=>1));

echo CHTML::dibujaEtiquetaCierre("div");

//******************************

echo CHTML::dibujaEtiqueta("div",array(),"",false);

echo CHTML::modeloCheckBox($modelo, "disponible",array("value"=>"1","uncheckValor"=>"0","etiqueta"=>"Disponible","disabled"=>true));

echo CHTML::dibujaEtiquetaCierre("div");

//******************************

echo CHTML::dibujaEtiqueta("div",array(),"",false);

echo CHTML::modeloCheckBox($modelo, "tendencia",array("value"=>"1","uncheckValor"=>"0","etiqueta"=>"Tendencia","disabled"=>true));

echo CHTML::dibujaEtiquetaCierre("div");



//*******************************


if($modelo->imagenP!=""){

echo CHTML::dibujaEtiqueta("div",array("class"=>"md-form"),"",false);



echo CHTML::imagen("/imagenes/panoramicas/".$modelo->imagenP,"",array("id"=>"imgP","class"=>"img-fluid"));

echo CHTML::dibujaEtiquetaCierre("div");

}


//Conjunto de botones ************

echo CHTML::dibujaEtiqueta("br");

echo CHTML::dibujaEtiqueta("div",array("id"=>"divBotones"),"",false);


echo CHTML::link("Volver",Sistema::app()->generaURL(array("peliculas","index")),array("class"=>"btn btn-blue"));

echo CHTML::link("Modificar",Sistema::app()->generaURL(array("peliculas","modificar"),
                            array("id"=>$modelo->cod_pelicula)),array("class"=>"btn btn-yellow"));

echo CHTML::link("Eliminar",Sistema::app()->generaURL(array("peliculas","eliminar"),
                            array("id"=>$modelo->cod_pelicula)),array("class"=>"btn btn-red"));

echo CHTML::dibujaEtiquetaCierre("div");


echo $tarjeta->dibujaFin();
