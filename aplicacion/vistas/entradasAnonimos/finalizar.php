<?php

//carga en la cabecera
$this->textoHead = CHTML::cssFichero("/estilos/principalBody.css");
$this->textoHead .= CHTML::cssFichero("/estilos/finalizarAnom.css");
$this->textoHead .= CHTML::scriptFichero("/js/finalizarAnom.js");


$tar = new CMiTarjeta("¡Gracias por su compra!");

echo $tar->dibujaApertura();

if(isset($mensaError)){
	echo CHTML::dibujaEtiqueta("div",array("class"=>"succes alert-danger","style"=>"margin: 0 auto;"),
	    " $mensaError");
	
	echo CHTML::dibujaEtiqueta("br");
	
}

echo CHTML::dibujaEtiqueta("div",array("class"=>"succes alert-info"),"<i class='fa fa-info-circle' aria-hidden='true'></i>".
                                                " Reciba sus entradas como quiera, y no olvide que si se registra, ¡puede disfrutar de las mejores ofertas!");

echo CHTML::dibujaEtiqueta("br");

echo CHTML::dibujaEtiqueta("p",array(),"Utilice la forma mas cómoda para recibir las entradas, enviandoselas a su correo o descargandolas por PDF.");


echo CHTML::dibujaEtiqueta("br");

echo CHTML::iniciarForm("","POST");


//echo CHTML::dibujaEtiqueta("div",array(),"",false);

//check para descargar la entrada

//echo CHTML::campoCheckBox("descargarPDF",false,array("id"=>"chkPDF"));
//echo CHTML::campoLabel("Descargar PDF", "chkPDF");

//echo CHTML::dibujaEtiquetaCierre("div");


//echo CHTML::campoCheckBox("chkCorreo",true,array("id"=>"chkCorreo"));
//echo CHTML::campoLabel("Enviar a mi correo", "chkCorreo");
//echo CHTML::dibujaEtiqueta("br");



echo CHTML::dibujaEtiqueta("div",array("class"=>"md-form form-sm"),"",false);

echo CHTML::campoText("txtCorreo","",array("id"=>"txtCorreo","class"=>"form-control form-control-sm"));
echo CHTML::campoLabel("Correo Electronico", "txtCorreo");

echo CHTML::dibujaEtiquetaCierre("div");


echo CHTML::dibujaEtiqueta("br");

echo CHTML::campoBotonSubmit("Enviar Correo",array("class"=>"btn btn-blue","name"=>"bEnviar"));

echo CHTML::finalizarForm();


echo CHTML::link("Descargar Entradas",Sistema::app()->generaURL(array("entradasAnonimos","descargarPDF")),array("class"=>"btn btn-red"));
echo CHTML::link("Inicio",Sistema::app()->generaURL(array("inicial","index")),array("class"=>"btn btn-blue"));



echo $tar->dibujaFin();
