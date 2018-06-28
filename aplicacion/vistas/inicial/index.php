<?php 

$this->textoHead = CHTML::cssFichero("/estilos/index.css");


//error_log("[".date("r")."][ERROR]"."segundo acceso acceso\n", 3, "log/error.log");

//Se manda un correo de bienvenida al correo del usuario

/*$para="juae94@outlook.es";
$titulo='Cines melero';
$cabeceras='MIME-Version: 1.0'."\r\n";
$cabeceras.='Content-type: text/html; charset=UTF-8'."\r\n";

// Cabeceras adicionales para hotmail y demas
$cabeceras.='From: room<mail@room.com>'."\r\n";


$mensaje="
mesá..
";

//Se manda un email al coreo del registrado dandole la bienvenida y recordandole sus datos
mail($para,$titulo,$mensaje,$cabeceras);
*/


if($totalTendencias===0){
    echo CHTML::dibujaEtiqueta("div",array("id"=>"divImagen"),"",false);
    echo CHTML::imagen("/imagenes/default/default.png","CINES MELERO",array("id"=>"iSinTendencias"));
    echo CHTML::dibujaEtiquetaCierre("div");
}

//podria poner EXIT, pero lo controlo con if

if($totalTendencias!==0){

//se dibuja una pelicula

$this->dibujaVistaParcial("indexParcial",
    array("pelicula"=>$pTendencias[0]));

}

//*********************************************

if($totalTendencias==2){
    $this->dibujaVistaParcial("indexParcial",
        array("pelicula"=>$pTendencias[1]));
    
}
else if($totalTendencias>2) {

    $carousel = new CMiCarousel("carousel");
    
    echo $carousel->dibujaApertura();
    
    
    for ($i=1; $i<count($pTendencias);$i++){
        
        echo $carousel->principioItem(1,$i);
        
        $this->dibujaVistaParcial("indexParcial",
            array("pelicula"=>$pTendencias[$i]));
        
        echo $carousel->finItem();
        
    }
    
    echo $carousel->dibujaFin();

}

   
    