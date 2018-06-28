<?php

$this->textoHead = CHTML::cssFichero("/estilos/principalBody.css");


$tarjeta = new CMiTarjeta("Eliminar Pelicula");

echo $tarjeta->dibujaApertura();


if($peliEliminar->disponible==false){

    echo CHTML::campoLabel($peliEliminar->titulo." ya ha sido eliminada", "");
    
}
else {

    echo CHTML::dibujaEtiqueta("div",array("class"=>"alert alert-info","style"=>"width:50%;"),"<i class='fa fa-info-circle' aria-hidden='true'></i>".
        " Recuerde que si elimina la pelicula, significará que no estara disponible.");
    
    echo CHTML::iniciarForm();
    echo CHTML::campoHidden("id", $peliEliminar->cod_pelicula);
    
    
    echo CHTML::campoLabel("¿Quiere eliminar la pelicula: ".$peliEliminar->titulo."?", "");
    echo CHTML::dibujaEtiqueta("br");
    
    echo CHTML::campoBotonSubmit("Confirmar", array("name" => "si","class"=>"btn btn-red"));
    
    
    echo CHTML::finalizarForm() . "\r\n";

}

echo CHTML::dibujaEtiqueta("br").CHTML::dibujaEtiqueta("br");

echo CHTML::dibujaEtiqueta("div",array("id"=>"divBotones"),"",false);


echo CHTML::link("Volver",Sistema::app()->generaURL(array("peliculas","index")),array("class"=>"btn btn-blue"));

echo CHTML::link("Modificar",Sistema::app()->generaURL(array("peliculas","modificar"),
    array("id"=>$peliEliminar->cod_pelicula)),array("class"=>"btn btn-yellow"));

echo CHTML::link("Consultar",Sistema::app()->generaURL(array("peliculas","consultar"),
    array("id"=>$peliEliminar->cod_pelicula)),array("class"=>"btn btn-green"));


echo CHTML::dibujaEtiquetaCierre("div");



echo $tarjeta->dibujaFin();
