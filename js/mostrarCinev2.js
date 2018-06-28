
$(document).ready(function(){
	
	//para que aparezca la mano sobre la butaca
	$('table img').css('cursor', 'pointer');
	
	
  //asignar la accion al boton
  $('#bComprar').click(clickComprar);

	$("#tButacas").on("click", "img", function(){
     	

      if($(this).attr("class")=="ocupada"){
        //si tiene la clase ocupada salgo (se asigna en php)
        return;
      }

      if($(this).attr("class")=="seleccionado"){

        //$(this).src = "/imagenes/butacas/bLibre";
        $(this).attr("src","/imagenes/butacas/bLibre.png");
        //$(this).attr("class","libre");

      }
      else{

        //la butaca esta libre y se ha marcado
        $(this).attr("src","/imagenes/butacas/bSeleccionada.png");
        //$(this).attr("class","seleccionada");

      }

      /*
      * Si tiene la clase se la quita, si no la tiene la pone.
      */
      $(this).toggleClass("seleccionado");

   	});//end funcion onclick img



});//end ready


function clickComprar(){
	
	
	
    if($(".seleccionado").length===0){
    	
    	toastr.info('Seleccione una butaca...');//cambiar por un modal si hay ganas
                                                //porque hoy no hay
    	//return false;
    }
    else {

      //en este caso hay alguna butaca seleccionada

      var table = document.getElementById("tButacas");

      var totalTrs = table.getElementsByTagName("tr");

       arraySel = new Array();
    
       for (var cont = 0; cont < totalTrs.length; cont++) {

           var tds = totalTrs[cont].getElementsByTagName("td");
           
           
            for(var cont2 = 0; cont2 < tds.length; cont2++) {

              var img = tds[cont2].getElementsByTagName("img");

       
              if(img[0].className == "seleccionado"){//butaca seleccionada

                    var codButaca = tds[cont2].className;//obtengo el codigo de ese asiento
                                                          //que lo tiene la clase del td
                    
                    arraySel.push(codButaca);

                  }//end if

            }//end for 2

          }//end for 1

        //console.log(arraySel);
        
        $('<input>').attr({
            type: 'hidden',
            name: 'butacas',
            value : arraySel
        }).appendTo('#formButacas');

        $("#formButacas").submit();

      //return false;

    }//end else
    
	
}//end clickComprar