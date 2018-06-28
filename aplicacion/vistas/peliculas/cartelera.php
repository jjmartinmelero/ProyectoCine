<?php


$this->textoHead = CHTML::cssFichero("/estilos/principalBody.css");
$this->textoHead.= CHTML::cssFichero("/estilos/cartelera.css");

$tarjeta = new CMiTarjeta("Cartelera");


//echo $tarjeta->dibujaApertura();

foreach ($pelis as $pelicula){
    
    $this->dibujaVistaParcial("carteleraParcial2",
        array("pelicula"=>$pelicula));
}

//echo $tarjeta->dibujaFin();

