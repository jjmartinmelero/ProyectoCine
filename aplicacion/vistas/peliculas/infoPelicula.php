<?php

$this->textoHead = CHTML::cssFichero("/estilos/principalBody.css");
$this->textoHead.= CHTML::cssFichero("/estilos/infoPelicula.css");
$this->textoHead.=CHTML::scriptFichero("/js/infoPelicula.js");


//var_dump($pelicula);

$tar = new CMiTarjeta("");

echo $tar->dibujaApertura();

echo CHTML::dibujaEtiqueta("h1",array("class"=>"titulo"),$pelicula->titulo);


echo CHTML::dibujaEtiqueta("div",array("class"=>"divSinop"),"",false);

echo CHTML::imagen("/imagenes/caratulas/".$pelicula->imagen,$pelicula->titulo, array("class"=>"caratula"));

echo CHTML::dibujaEtiqueta("div",array("class"=>"divInfo"),"",false);

echo CHTML::modeloLabel($pelicula, "director");
echo CHTML::dibujaEtiqueta("label",array(),": {$pelicula->director}");

echo CHTML::dibujaEtiqueta("br");

echo CHTML::modeloLabel($pelicula, "fLanzamiento");
echo CHTML::dibujaEtiqueta("label",array(),": {$pelicula->fLanzamiento}");

echo CHTML::dibujaEtiqueta("br");

echo CHTML::modeloLabel($pelicula, "nombre_categoria");
echo CHTML::dibujaEtiqueta("label",array(),": {$pelicula->nombre_categoria}");

echo CHTML::dibujaEtiqueta("br");

echo CHTML::modeloLabel($pelicula, "sinopsis",array());

echo CHTML::dibujaEtiqueta("br");
echo CHTML::modeloTextArea($pelicula, "sinopsis",array("class"=>"txtArea","readonly"=>1,"id"=>"txtArea"));
echo CHTML::dibujaEtiquetaCierre("div");
//echo CHTML::dibujaEtiqueta("p",array("class"=>"sinopsis"),$pelicula->sinopsis);

echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiqueta("div",array("class"=>"divBtn"),"",false);


if($res){//significa que la pelicula esta lanzada
echo CHTML::link("RESERVAR",Sistema::app()->generaURL(array("pasesPeliculas","mostrarHorarios"),
    array("id"=>$pelicula->cod_pelicula)),array("class"=>"btn btn-red"));
}

echo CHTML::link("Volver",$enlaceVolver,array("class"=>"btn btn-blue"));


echo CHTML::dibujaEtiquetaCierre("div");

//////////////////////////////////////////


echo CHTML::campoHidden("cod_pelicula",$pelicula->cod_pelicula,array("id"=>"cod_pelicula"));

if(Sistema::app()->acceso()->hayUsuario()&&$res){
    
    echo CHTML::dibujaEtiqueta("div",array("id"=>"dComentario","class"=>"form-group blue-border-focus"),"",false);
    
    echo CHTML::modeloLabel($modeloComent, "comentario");
    
    echo CHTML::modeloTextArea($modeloComent, "comentario", array("class"=>"form-control"));
    
    echo CHTML::dibujaEtiqueta("label",array("id"=>"labelComentario"),"100");
    
    echo CHTML::boton("Publicar",array("class"=>"btn btn-blue","id"=>"bPublicar"));
    
    echo CHTML::dibujaEtiquetaCierre("div");
    

}
    



///////////////////////////////////////////

if($res){

    echo CHTML::dibujaEtiqueta("div",array("id"=>"dComent"),"",false);
    
    echo CHTML::boton("Ver Comentarios",array("class"=>"btn btn-green","id"=>"bComent", "name"=>"id_1"));
    
    
    echo CHTML::dibujaEtiquetaCierre("div");

}


echo $tar->dibujaFin();