<?php

$this->textoHead = CHTML::cssFichero("/estilos/login.css");
$this->textoHead.=CHTML::scriptFichero("/js/login.js");


echo CHTML::dibujaEtiqueta("section",array("class"=>"form-simple","id"=>"caja"),"",false);

echo CHTML::dibujaEtiqueta("div",array("class"=>"card","id"=>"tarjetaLogin"),"",false);


//header
echo CHTML::dibujaEtiqueta("div",array("class"=>"header pt-3 danger-color-dark"),"",false);

echo CHTML::dibujaEtiqueta("div",array("class"=>"row d-flex justify-content-start"),"",false);

echo CHTML::dibujaEtiqueta("h3",array("class"=>"deep-grey-text mt-3 mb-4 pb-1 mx-5","style"=>"color:white;"),"",false);
echo "Iniciar Sesion";
echo CHTML::dibujaEtiquetaCierre("h3");

echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiquetaCierre("div");
//end header

echo CHTML::iniciarForm("","POST",array("id"=>"fLogin","autocomplete"=>"off"));

//cuerpo
echo CHTML::dibujaEtiqueta("div",array("class"=>"card-body mx-4 mt-4","id"=>"mensaError"),"",false);

if(isset($mensajeErr)){
    echo CHTML::dibujaEtiqueta("div",array("class"=>"alert alert-danger text-center","role"=>"alert" ),"",false);
    
    echo CHTML::dibujaEtiqueta("p",array(),$mensajeErr);
    
    echo CHTML::dibujaEtiquetaCierre("div");
}

echo CHTML::dibujaEtiqueta("div",array(),"",false);

echo CHTML::modeloLabel($modelo, "nick", array("for"=>"inputNick"));

echo CHTML::modeloText($modelo, "nick",array("id"=>"inputNick","class"=>"form-control"));


echo CHTML::dibujaEtiquetaCierre("div");

/*
echo CHTML::dibujaEtiqueta("div",array("class"=>"md-form"),"",false);


echo CHTML::modeloText($modelo, "nick",array("class"=>"form-control","id"=>"inputNick"));

echo CHTML::modeloLabel($modelo, "nick",array("for"=>"inputNick"));

echo CHTML::dibujaEtiquetaCierre("div");
*/
echo CHTML::dibujaEtiqueta("br");
//pass
echo CHTML::dibujaEtiqueta("div",array(),"",false);

echo CHTML::modeloLabel($modelo, "contrasenia",array("for"=>"inputPass"));

echo CHTML::modeloPassword($modelo, "contrasenia",array("class"=>"form-control","id"=>"inputPass"));

//poner aqui el recordarme !!!!!!!!!!
echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiqueta("div");


echo CHTML::dibujaEtiqueta("br");

//echo CHTML::campoCheckBox("recordar",true,array("etiqueta"=>"Recuérdame"));//check y uncheckd
echo CHTML::campoCheckBox("recordar",true,array("id"=>"chkRecordar"));
echo CHTML::campoLabel("Recuerdame", "chkRecordar");
//echo "Recuérdame";//ETIQUETA QUE TENDRA EL CHECKBOX, SI LO PONGO EN EL ARRAY CON EL CAMPO DE ETIQUETA, SALE MUY SEPARADO


echo CHTML::dibujaEtiquetaCierre("div");


echo CHTML::dibujaEtiqueta("br");


echo CHTML::dibujaEtiqueta("div",array("class"=>"text-center mb-4"),"",false);
echo CHTML::boton("ENTRAR",array("class"=>"btn danger-color-dark btn-block z-depth-2","id"=>"bLogin"));
echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::finalizarForm();

echo CHTML::dibujaEtiquetaCierre("div");//end cuerpo

echo CHTML::dibujaEtiquetaCierre("div");

echo CHTML::dibujaEtiquetaCierre("section");







