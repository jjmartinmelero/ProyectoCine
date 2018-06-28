<?php

//carga en la cabecera
$this->textoHead = CHTML::cssFichero("/estilos/principalBody.css");
$this->textoHead.= CHTML::cssFichero("/estilos/entradas/mostrarResumen.css");
$this->textoHead.=CHTML::scriptFichero("/js/entradas/mostrarResumenUsu.js");

//echo CHTML::iniciarForm("","POST");


$cont = 0;

//var_dump($filCol);

//var_dump($codAsientos);

foreach ($filCol as $asiento) {



    $this->dibujaVistaParcial("resumenParcial",
        array("asiento"=>$asiento,
    		  "pasePel"=>$pasePelicula,
    		  "codAsiento"=>$codAsientos[$cont],
    		  "nSala"=>$nSala,
    		   "horaPelicula"=>$horaPelicula));

    $cont++;

}//end foreach

//echo CHTML::campoBotonSubmit("COMPRAR",array("class"=>"btn btn-blue","name"=>"bComprar"));

//echo CHTML::boton("boton -> submit",array("class"=>"btn btn-blue","disabled"=>1));


//echo CHTML::finalizarForm();


$tar = new CMiTarjeta("",array("id"=>"tTotal"));

echo $tar->dibujaApertura();

echo CHTML::iniciarForm("","POST", array("id"=>"fComprar", "autocomplete"=>"off"));

echo CHTML::dibujaEtiqueta("p",array("id"=>"pEntradas"),"Numero de entradas ".count($filCol)." x ".$precioEntrada." €");

echo CHTML::dibujaEtiqueta("p",array(),"Descuento por entrada: ".number_format((Sistema::app()->descuentoUsu),2)." €");

echo CHTML::dibujaEtiqueta("hr");

echo CHTML::dibujaEtiqueta("p",array("id"=>'tDescuento'),"Total Descuento: ".number_format((Sistema::app()->descuentoUsu)*count($filCol),2)." €");
//el precio por entrada es:
echo CHTML::dibujaEtiqueta("p",array("id"=>"pPrecio"),"Precio total: ".number_format((($precioEntrada)*count($filCol))
                                                                                        -(Sistema::app()->descuentoUsu*count($filCol)),2)." €");

echo CHTML::dibujaEtiqueta("hr");

echo CHTML::dibujaEtiqueta("h3",array("class"=>"panel-title"),"Datos de pago");

echo CHTML::dibujaEtiqueta("div",array("id"=>"dInfo","class"=>"alert alert-info",
                            "style"=>"width:100%; text-align: center;"),"<i class='fa fa-info-circle' aria-hidden='true'></i>".
    " Visa o Mastercard");


//cuerpo tarjeta de pago
echo CHTML::dibujaEtiqueta("div",array("class"=>"panel-body"),"",false);

echo CHTML::dibujaEtiqueta("div",array("class"=>"form-group"),"",false);

echo CHTML::dibujaEtiqueta("label",array("for"=>"tCredito"),"Numero de Tarjeta");

echo CHTML::dibujaEtiqueta("div",array("class"=>"input-group"),"",false);

echo CHTML::campoText("tCredito","",array("class"=>"form-control","id"=>"tCredito","placeholder"=>"Numero de tarjeta"));


echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiquetaCierre("div");


///////////////////////////////////


echo CHTML::dibujaEtiqueta("div",array("class"=>"form-group"),"",false);

echo CHTML::dibujaEtiqueta("label",array("for"=>"caducidadMes"),"Fecha de Validez");

echo CHTML::dibujaEtiqueta("div",array("class"=>"input-group"),"",false);

echo CHTML::campoText("caducidadMes","",array("class"=>"form-control","maxlength"=>"2","id"=>"caducidadMes","placeholder"=>"MM"));

//echo CHTML::campoText("caducidadAno","",array("class"=>"form-control","id"=>"caducidadAno","placeholder"=>"AA"));
echo CHTML::campoText("caducidadAno","",array("class"=>"form-control","maxlength"=>"2","id"=>"caducidadAno","placeholder"=>"AA"));

echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiquetaCierre("div");


echo CHTML::dibujaEtiqueta("div",array("class"=>"form-group"),"",false);

//echo CHTML::dibujaEtiqueta("label",array("for"=>"tCredito"),"CVV");

echo CHTML::dibujaEtiqueta("div",array("class"=>"input-group"),"",false);

echo CHTML::campoText("tCvv","",array("class"=>"form-control","id"=>"tCvv","maxlength"=>"3","placeholder"=>"CVV"));


echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiquetaCierre("div");




//echo CHTML::campoBotonSubmit("COMPRAR",array("class"=>"btn btn-blue","name"=>"bComprar"));

echo CHTML::boton("COMPRAR",array("class"=>"btn danger-color-dark","name"=>"bComprar","id"=>"bComprar"));

echo CHTML::finalizarForm();

echo $tar->dibujaFin();


