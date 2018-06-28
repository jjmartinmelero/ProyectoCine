<?php

$this->textoHead = CHTML::cssFichero("/estilos/principalBody.css");
$this->textoHead.= CHTML::cssFichero("/estilos/proximamente.css");



//echo $tarjeta->dibujaApertura();

if(count($pelis)===0){
    echo CHTML::dibujaEtiqueta("div",array("id"=>"divImagen"),"",false);
    echo CHTML::imagen("/imagenes/default/default.png","CINES MELERO",array("class"=>"iSinTendencias"));
    echo CHTML::dibujaEtiquetaCierre("div");
}
else {
    foreach ($pelis as $pelicula){
        
        $this->dibujaVistaParcial("proximamenteParcial",
            array("pelicula"=>$pelicula));
    }
}