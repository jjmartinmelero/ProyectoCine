<?php 

$this->textoHead = CHTML::cssFichero("/estilos/principalBody.css");

$tarjeta = new CMiTarjeta("Información Sesiones");

echo $tarjeta->dibujaApertura();


if(!empty($filas)){

$tabla=new MiTabla($cabecera,$filas);

echo $tabla->dibujate();

}
else {
    
    echo CHTML::dibujaEtiqueta("div",array("class"=>"alert alert-info","style"=>"width:100%;"),"<i class='fa fa-info-circle' aria-hidden='true'></i>".
        " Vaya, en este momento no hay sesiones pases de peliculas disponibles");
}

echo CHTML::dibujaEtiqueta("br");

echo CHTML::link("Crear Sesion",Sistema::app()->generaURL(array("pasesPeliculas","nuevaSesion")),array("class"=>"btn danger-color-dark"));

echo $tarjeta->dibujaFin();