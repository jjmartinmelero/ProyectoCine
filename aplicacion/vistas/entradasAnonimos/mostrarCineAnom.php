<?php



$this->textoHead = CHTML::cssFichero("/estilos/principalBody.css");
$this->textoHead.= CHTML::cssFichero("/estilos/mostrarCine.css");
$this->textoHead.= CHTML::scriptFichero("/js/mostrarCinev2.js");


$tar = new CMiTarjeta("Seleccionar butaca");

echo $tar->dibujaApertura();

//info butacas
echo CHTML::dibujaEtiqueta("div",array("id"=>"infoButacas"),"",false);

echo CHTML::imagen("/imagenes/butacas/bSeleccionada.png");
echo CHTML::dibujaEtiqueta("label",array(),"Butaca Seleccionada");


echo CHTML::imagen("/imagenes/butacas/bOcupada.png");
echo CHTML::dibujaEtiqueta("label",array(),"Butaca Ocupada");


echo CHTML::imagen("/imagenes/butacas/bLibre.png");
echo CHTML::dibujaEtiqueta("label",array(),"Butaca Libre");

echo CHTML::dibujaEtiquetaCierre("div");





echo CHTML::imagen("/imagenes/butacas/pantalla.png","pantalla",array("id"=>"iPantalla"));

echo CHTML::dibujaEtiqueta("table",array("id"=>"tButacas"),"",false);


//los asientos empiezan en la fila 1 y columna 1
//por cada fila tr

//pantalla

$auxiliar = 0;

for ($contFila=1; $contFila <= $filas; $contFila++) {

	echo CHTML::dibujaEtiqueta("tr",array(),"",false);


	for ($contCols=1; $contCols <= $cols; $contCols++) {
		
		echo CHTML::dibujaEtiqueta("td",array("class"=>$codAsientos[$auxiliar]["cod_asiento"]),"",false);

		if(isset($asientosOcupados[$contFila][$contCols])&&$asientosOcupados[$contFila][$contCols]==true){

			//butaca ocupada - mostrar imagen - class='disabled'??
		    echo CHTML::imagen("/imagenes/butacas/bOcupada.png","",array("class"=>"ocupada"));

		}
		else{

			//butaca NO ocupada - mostrar imagen
			//echo CHTML::dibujaEtiqueta("img",array("src"=>"/imagenes/asientos/bLibre.png"));
		    echo CHTML::imagen("/imagenes/butacas/bLibre.png");
		}
		
		$auxiliar++;

		echo CHTML::dibujaEtiquetaCierre("td");


	}//end for 2


	echo CHTML::dibujaEtiquetaCierre("tr");

}//end for 1


echo CHTML::dibujaEtiquetaCierre("table");





//echo CHTML::link("Comprar entradas", "/ruta");

echo CHTML::dibujaEtiqueta("div",array(),"",false);


//por defecto es action='' y POST
echo CHTML::iniciarForm("","POST",array("id"=>"formButacas"));

//campo hidden del codigo del pase.
//echo CHTML::campoHidden("codPase",$codPase);
//campo hidden array con codigos butacas de forma dinamica con jquery

echo CHTML::boton("Confirmar",array("class"=>"btn btn-blue","id"=>"bComprar"));


/*
 echo CHTML::link("Comprar",Sistema::app()->generaURL(array("pel","modificar"),
 array()),array("class"=>"btn btn-blue","id"=>"bComprar"));
 */

//fin form
echo CHTML::finalizarForm();


echo CHTML::dibujaEtiquetaCierre("div");

echo $tar->dibujaFin();


