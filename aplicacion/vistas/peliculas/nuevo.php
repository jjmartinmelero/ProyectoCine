<?php

$this->textoHead = CHTML::cssFichero("/estilos/principalBody.css");
$this->textoHead.=CHTML::scriptFichero("/js/nuevaPelicula.js");
$this->textoHead.= CHTML::cssFichero("/estilos/nuevo.css");
/*
echo CHTML::modeloErrorSumario($modelo);

//var_dump($modelo->getErrores());

echo CHTML::iniciarForm("","POST",array("enctype"=>"multipart/form-data"));//por defecto es action='' y POST


echo CHTML::modeloLabel($modelo, "titulo");
echo CHTML::dibujaEtiqueta("br").PHP_EOL;
echo CHTML::modeloText($modelo, "titulo",array("maxlength"=>50,"size"=>51));
echo CHTML::modeloError($modelo, "usuario");

echo CHTML::dibujaEtiqueta("br").PHP_EOL;

echo CHTML::modeloLabel($modelo, "fLanzamiento");
echo CHTML::dibujaEtiqueta("br").PHP_EOL;
echo CHTML::modeloText($modelo, "fLanzamiento",array("maxlength"=>10,"id"=>"inputFecha",
                                                    "size"=>11,"placeholder"=>"dd/mm/aaaa"));
echo CHTML::modeloError($modelo, "fLanzamiento");


echo CHTML::dibujaEtiqueta("br").PHP_EOL;
echo CHTML::modeloLabel($modelo, "cod_categoria");
echo CHTML::modeloListaDropDown($modelo, "cod_categoria",Categorias::dameCategorias(),array("linea"=>"Indique tipo"));
echo CHTML::modeloError($modelo, "cod_categoria");



echo CHTML::dibujaEtiqueta("br").PHP_EOL;

echo CHTML::modeloLabel($modelo, "director");
echo CHTML::dibujaEtiqueta("br").PHP_EOL;
echo CHTML::modeloText($modelo, "director",array("maxlength"=>30,"size"=>31));
echo CHTML::modeloError($modelo, "director");

echo CHTML::dibujaEtiqueta("br").PHP_EOL;

echo CHTML::modeloLabel($modelo, "sinopsis");
echo CHTML::dibujaEtiqueta("br").PHP_EOL;
echo CHTML::modeloTextArea($modelo, "sinopsis",array("rows"=>10,"cols"=>70));
echo CHTML::modeloError($modelo, "sinopsis");

echo CHTML::dibujaEtiqueta("br").PHP_EOL;

echo CHTML::modeloLabel($modelo, "imagen");
echo CHTML::dibujaEtiqueta("br").PHP_EOL;
echo CHTML::campoFile("peliculas[imagen]");
//echo CHTML::modeloFile($modelo, "imagen" ,array("class"=>"inputFile"));
echo CHTML::modeloError($modelo, "imagen");


//No anado la opcion de disponible, ya que si se crea una pelicula doy por entendido que esta disponible

echo CHTML::dibujaEtiqueta("br").CHTML::dibujaEtiqueta("br").PHP_EOL;
echo CHTML::campoLabel("NOTA: Marque la pelicula como tendencia si quiere que aparezca en el inicio de la pagina", "");


echo CHTML::dibujaEtiqueta("br").PHP_EOL;

echo CHTML::modeloCheckBox($modelo, "tendencia",array("id"=>"chkTendencia",
                                                    "value"=>"1","uncheckValor"=>"0","etiqueta"=>"Marcar Tendencia"));
echo CHTML::modeloError($modelo, "tendencia");

echo CHTML::dibujaEtiqueta("br").PHP_EOL;


echo CHTML::modeloLabel($modelo, "imagenP");
echo CHTML::dibujaEtiqueta("br").PHP_EOL;
echo CHTML::campoFile("peliculas[imagenP]");
//echo CHTML::modeloFile($modelo, "imagenP", array("id"=>"imgP","class"=>"inputFile"));
echo CHTML::modeloError($modelo, "imagenP");

echo CHTML::dibujaEtiqueta("br").PHP_EOL;
echo CHTML::campoBotonSubmit("Añadir", array("id"=>"bAnadir"));

echo CHTML::finalizarForm();

echo CHTML::link("volver",Sistema::app()->generaURL(array("peliculas","index")));*/


$errores = $modelo->getErrores();

/*
echo CHTML::dibujaEtiqueta("div",array("class"=>"modal-dialog", "id"=>"contenedorNuevo"),"",false);

echo CHTML::dibujaEtiqueta("div",array("class"=>"modal-content"),"",false);

//header
echo CHTML::dibujaEtiqueta("div",array("class"=>"modal-header primary-color white-text"),"",false);

echo CHTML::dibujaEtiqueta("h4", array("class"=>"title"),"",false);
echo CHTML::dibujaEtiqueta("i",array("class"=>"fa fa-pencil"),"",false);
echo CHTML::dibujaEtiquetaCierre("i");
echo "Crear Película";
echo CHTML::dibujaEtiquetaCierre("h4");

echo CHTML::dibujaEtiquetaCierre("div");
*/


//Tarjeta cotnenedor
echo CHTML::dibujaEtiqueta("div",array("class"=>"card", "class"=>"contenedor"),"",false);

echo CHTML::dibujaEtiqueta("div",array("class"=>"card"),"",false);

echo CHTML::dibujaEtiqueta("div",array("class"=>"card-header danger-color-dark white-text","style"=>"text-align: center;"),
    "Crear Pelicula");

echo CHTML::dibujaEtiqueta("div",array("class"=>"card-body"),"",false);

//body
//echo CHTML::dibujaEtiqueta("div",array("class"=>"modal-body"),"",false);
//echo CHTML::dibujaEtiqueta("div",array("class"=>"panel-body"),"",false);

//Mensaje de error si hubiera
if(!empty($errores)){
    
    echo CHTML::dibujaEtiqueta("div",array("class"=>"alert alert-danger","style"=>"text-align:center"),"",false);
    
    echo CHTML::dibujaEtiqueta("p",array(),$errores[0]);
    
    echo CHTML::dibujaEtiquetaCierre("div");
    
}

//INICIAR FORM
echo CHTML::iniciarForm("","POST",array("id"=>"formNuevo","enctype"=>"multipart/form-data", "autocomplete"=>"off"));//por defecto es action='' y POST

echo CHTML::dibujaEtiqueta("div",array("class"=>"md-form"),"",false);


echo CHTML::modeloText($modelo, "titulo",array("class"=>"form-control"));
echo CHTML::modeloLabel($modelo, "titulo",array("style"=>"font-weight:normal"));

echo CHTML::dibujaEtiquetaCierre("div");


echo CHTML::dibujaEtiqueta("br");


echo CHTML::dibujaEtiqueta("div",array("class"=>"md-form"),"",false);


echo CHTML::modeloText($modelo, "director",array("class"=>"form-control"));
echo CHTML::modeloLabel($modelo, "director",array("style"=>"font-weight:normal"));

echo CHTML::dibujaEtiquetaCierre("div");


echo CHTML::dibujaEtiqueta("br");


echo CHTML::dibujaEtiqueta("div",array("class"=>"md-form"),"",false);

echo CHTML::modeloText($modelo, "fLanzamiento",array("class"=>"form-control","id"=>"inputNick"));
echo CHTML::modeloLabel($modelo, "fLanzamiento",array("for"=>"inputNick","style"=>"font-weight:normal"));


echo CHTML::dibujaEtiquetaCierre("div");


echo CHTML::dibujaEtiqueta("br");

echo CHTML::dibujaEtiqueta("div",array("class"=>"md-form"),"",false);

//echo CHTML::modeloLabel($modelo, "cod_categoria");

echo CHTML::modeloListaDropDown($modelo, "cod_categoria",Categorias::dameCategorias(),array("linea"=>"Categoria...",
                                                                                            "class"=>"form-control form-control-sm",
                                                                                            "id"=>"lista"));

echo CHTML::dibujaEtiquetaCierre("div");


echo CHTML::dibujaEtiqueta("br");


echo CHTML::dibujaEtiqueta("div",array("class"=>"md-form"),"",false);


echo CHTML::modeloTextArea($modelo, "sinopsis",array("class"=>"form-control z-depth-1","rows"=>3,"placeholder"=>"Sinopsis..."));

echo CHTML::dibujaEtiquetaCierre("div");


echo CHTML::dibujaEtiqueta("br");


echo CHTML::dibujaEtiqueta("div",array(),"",false);


echo CHTML::modeloLabel($modelo, "imagen");
echo CHTML::dibujaEtiqueta("br");
echo CHTML::campoFile("peliculas[imagen]");

echo CHTML::dibujaEtiquetaCierre("div");


echo CHTML::dibujaEtiqueta("br");


echo CHTML::dibujaEtiqueta("div",array(),"",false);

echo CHTML::modeloCheckBox($modelo, "tendencia",array("id"=>"chkTendencia",
    "value"=>"1","uncheckValor"=>"0","etiqueta"=>"Marcar Tendencia"));


echo CHTML::dibujaEtiquetaCierre("div");



echo CHTML::dibujaEtiqueta("br");


echo CHTML::dibujaEtiqueta("div",array(),"",false);

echo CHTML::modeloLabel($modelo, "imagenP");
echo CHTML::dibujaEtiqueta("br").PHP_EOL;
echo CHTML::campoFile("peliculas[imagenP]","",array("id"=>"imgP","class"=>"inputFile"));

echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiqueta("br");


echo CHTML::boton("AÑADIR",array("class"=>"btn danger-color-dark btn-block", "id"=>"bNuevo"));
//echo CHTML::boton("REGISTRAR",array("class"=>"btn btn-info btn-block"));

//fin form
echo CHTML::finalizarForm();

//END CONTENIDO *********************
echo CHTML::dibujaEtiquetaCierre("div");



echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiquetaCierre("div");


