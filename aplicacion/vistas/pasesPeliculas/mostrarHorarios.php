<?php

//vista encargada de mostrar los dias para un pase de pelicula

//llega array con todos los pases disponibles para una pelicula

$this->textoHead = CHTML::cssFichero("/estilos/principalBody.css");
$this->textoHead.= CHTML::cssFichero("/estilos/pasesPeliculas/mostrarHorarios.css");



//muestro aqui un mensaje. Aunque podría mostrar en el controlador una pagina de error en el caso de que no haya pases de pelicula,
//como quiero mostrar un mensaje "mas bonito" en lugar de la pagina de error, por eso lo controlo aqui.

    
//$tarB = new CMiTarjeta("title");


//echo $tarB->dibujaApertura();
    

    
    for ($i=0;$i<count($diasSesiones);$i++){
        
        $tar = new CMiTarjeta($diasSesiones[$i]);
        
        
        echo $tar->dibujaApertura();
 
        
        if(isset($pasesDisponibles[$diasSesiones[$i]])){
            
            
            
            for ($aux=0;$aux<count($pasesDisponibles[$diasSesiones[$i]]);$aux++){
                
                //echo "<br>pase";
                //echo CHTML::link($pasesDisponibles[$diasSesiones[$i]]["hora_inicio"],Sistema::app()->generaURL(array("pasesPeliculas","seleccionarButaca"),
                //    array("id"=>"sss")),array("class"=>"btn btn-red"));
                
                $pases = $pasesDisponibles[$diasSesiones[$i]];
                
                $paseAux = $pases[$aux];
                
                                                            //link llega del controlador
               /* echo CHTML::link($paseAux["hora_inicio"],$link,
                    array("id"=>$paseAux["cod_pase_pelicula"])),array("class"=>"btn btn-blue"));
                */
                
                if(!$hayUsu){
                    echo CHTML::link($paseAux["hora_inicio"],Sistema::app()->generaURL(array("entradasAnonimos"),
                        array("id"=>$paseAux["cod_pase_pelicula"])),array("class"=>"btn btn-blue"));
                }
                else {

                    echo CHTML::link($paseAux["hora_inicio"],Sistema::app()->generaURL(array("entradasUsuarios"),
                        array("id"=>$paseAux["cod_pase_pelicula"])),array("class"=>"btn btn-blue"));
                }
                

                
            }//end 2 for
            
            
            
            
        }
        else {
            echo CHTML::dibujaEtiqueta("div",array("class"=>"alert alert-danger text-center","style"=>"width:60%;"),"<i class='fa fa-info-circle' aria-hidden='true'></i>".
                " No hay pases disponibles este día, lo sentimos.");
        }
        
        echo $tar->dibujaFin();
        echo CHTML::dibujaEtiqueta("hr");
        
    }
    
    /*foreach ($pasesDisponibles as $pase) {//los pases que tiene la pelicula
        
        echo CHTML::link($pase["hora_inicio"],Sistema::app()->generaURL(array("pasesPeliculas","seleccionarButaca"),
            array("id"=>$pase["cod_pelicula"])),array("class"=>"btn btn-red"));
        
    }//end foreach
    
    */
    
    ?>
    
    
    <?php 
    

    

    //echo $tarB->dibujaFin();






/*

PASES PELICULAS CONTROLADOR

public function mostrarHorario(){

        //llega el codigo de la pelicula seleccionada

        $codPelicua = intval($_GET("id"));


        $pases = new Pases_peliculas();


        $opFiltrado["select"] = "select t.hora_inicio";
        $opFiltrado["where"] = "t.cod_pelicula = ".$codPelicua;

        $totalPases = $pases->buscarTodos($opFiltrado);


        //validar si la pelicula tiene pases

        if(empty($totalPases)){

            //$this->paginaError(404,"Lo sentimos, no hay pases disponibles");

            $this->dibujaPaginaError(404,"Lo sentimos, no hay pases disponibles");
            exit;

        }


        $this->dibujaVista("mostrarHorarios",array("horariosDisponibles"=>$totalPases),"CINES MELERO");



    }//end mostrarHorario

*/






