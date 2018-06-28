<?php

//carga en la cabecera
$this->textoHead = CHTML::cssFichero("/estilos/principalBody.css");
$this->textoHead .= CHTML::cssFichero("/estilos/finalizarAnom.css");
$this->textoHead .= CHTML::scriptFichero("/js/finalizarAnom.js");


$tar = new CMiTarjeta("¡Gracias por la compra!");

echo $tar->dibujaApertura();

if(isset($mensaError)){
	echo $mensaError;
}

echo CHTML::dibujaEtiqueta("div",array("class"=>"succes alert-success","style"=>"margin: 0 auto;"),"<i class='fa fa-info-circle' aria-hidden='true'></i>".
                                                " Se utilizara el correo que uso para registrarse");


echo CHTML::dibujaEtiqueta("br");

echo CHTML::dibujaEtiqueta("p",array(),"Utilice la forma mas cómoda para recibir las entradas, enviandoselas a su correo o descargandolas por PDF.");

echo CHTML::iniciarForm("","POST");

echo CHTML::dibujaEtiqueta("br");

echo CHTML::campoBotonSubmit("Enviar Correo",array("class"=>"btn btn-blue","name"=>"bEnviar"));

echo CHTML::finalizarForm();


echo CHTML::link("Descargar Entradas",Sistema::app()->generaURL(array("entradasUsuarios","descargarPDF")),array("class"=>"btn btn-red"));
echo CHTML::link("Inicio",Sistema::app()->generaURL(array("inicial","index")),array("class"=>"btn btn-blue"));



echo $tar->dibujaFin();
