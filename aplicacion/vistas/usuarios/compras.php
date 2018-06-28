<?php

$this->textoHead = CHTML::cssFichero("/estilos/principalBody.css");
$this->textoHead.= CHTML::cssFichero("/estilos/compras.css");

$tar = new CMiTarjeta("Mis Compras");

echo $tar->dibujaApertura();

if(!empty($filas)){
    
    $tabla=new MiTabla($cabecera,$filas);
    
    echo $tabla->dibujate();
}
else {
    echo CHTML::dibujaEtiqueta("div",array("class"=>"alert alert-info","style"=>"width:100%;"),"<i class='fa fa-info-circle' aria-hidden='true'></i>".
        " No tienes ninguna compra");
}

echo CHTML::dibujaEtiqueta("br");

echo CHTML::link("Volver a inicio",Sistema::app()->generaURL(array("inicial","index")),array("class"=>"btn danger-color-dark"));

echo $tar->dibujaFin();
