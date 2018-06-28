<?php

//carga en la cabecera
$this->textoHead = CHTML::cssFichero("/estilos/principalBody.css");
$this->textoHead.= CHTML::cssFichero("/estilos/entradas/mostrarResumenAnon.css");
$this->textoHead.=CHTML::scriptFichero("/js/entradas/mostrarResumenAnon.js");


$cont = 0;

//var_dump($filCol);

//var_dump($codAsientos);

foreach ($filCol as $asiento) {



    $this->dibujaVistaParcial("resumenParcialAnom",
        array("asiento"=>$asiento,
    		  "pasePel"=>$pasePelicula,
    		  "codAsiento"=>$codAsientos[$cont],
    		  "nSala"=>$nSala,
    		   "horaPelicula"=>$horaPelicula));

    $cont++;

}//end foreach

//echo CHTML::boton("boton -> submit",array("class"=>"btn btn-blue","disabled"=>1));




$tar = new CMiTarjeta("",array("id"=>"tTotal"));

echo $tar->dibujaApertura();

echo CHTML::iniciarForm("","POST", array("id"=>"fComprarA", "autocomplete"=>"off"));

echo CHTML::dibujaEtiqueta("p",array("id"=>"entradaA"),"Numero de entradas ".count($filCol)." x ".$precioEntrada." €");

echo CHTML::dibujaEtiqueta("hr");

echo CHTML::dibujaEtiqueta("p",array("id"=>"precioA"),"Precio total: ".number_format((($precioEntrada)*count($filCol)),2)." €");

echo CHTML::dibujaEtiqueta("hr");
echo CHTML::dibujaEtiqueta("h3",array("class"=>"panel-title"),"Datos de pago");

echo CHTML::dibujaEtiqueta("div",array("id"=>"dInfoA","class"=>"alert alert-info",
    "style"=>"width:100%; text-align: center;"),"<i class='fa fa-info-circle' aria-hidden='true'></i>".
    " Visa o Mastercard");


//cuerpo tarjeta de pago
echo CHTML::dibujaEtiqueta("div",array("class"=>"panel-body"),"",false);

echo CHTML::dibujaEtiqueta("div",array("class"=>"form-group"),"",false);

echo CHTML::dibujaEtiqueta("label",array("for"=>"tCreditoA"),"Numero de Tarjeta");

echo CHTML::dibujaEtiqueta("div",array("class"=>"input-group"),"",false);

echo CHTML::campoText("tCreditoA","",array("class"=>"form-control","id"=>"tCreditoA","placeholder"=>"Numero de tarjeta"));


echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiquetaCierre("div");


///////////////////////////////////


echo CHTML::dibujaEtiqueta("div",array("class"=>"form-group"),"",false);

echo CHTML::dibujaEtiqueta("label",array("for"=>"caducidadMesA"),"Fecha de Validez");

echo CHTML::dibujaEtiqueta("div",array("class"=>"input-group"),"",false);

echo CHTML::campoText("caducidadMesA","",array("class"=>"form-control","maxlength"=>"2","id"=>"caducidadMesA","placeholder"=>"MM"));

//echo CHTML::campoText("caducidadAno","",array("class"=>"form-control","id"=>"caducidadAno","placeholder"=>"AA"));
echo CHTML::campoText("caducidadAnoA","",array("class"=>"form-control","maxlength"=>"2","id"=>"caducidadAnoA","placeholder"=>"AA"));

echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiquetaCierre("div");


echo CHTML::dibujaEtiqueta("div",array("class"=>"form-group"),"",false);

//echo CHTML::dibujaEtiqueta("label",array("for"=>"tCredito"),"CVV");

echo CHTML::dibujaEtiqueta("div",array("class"=>"input-group"),"",false);

echo CHTML::campoText("tCvvA","",array("class"=>"form-control","id"=>"tCvvA","maxlength"=>"3","placeholder"=>"CVV"));


echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiquetaCierre("div");




//echo CHTML::campoBotonSubmit("COMPRAR",array("class"=>"btn btn-blue","name"=>"bComprar"));

echo CHTML::boton("COMPRAR",array("class"=>"btn danger-color-dark","name"=>"bComprar","id"=>"bComprarA"));

echo CHTML::finalizarForm();

echo $tar->dibujaFin();

