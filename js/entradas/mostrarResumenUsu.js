
// ON READY ******************************************************************************************
$(document).ready(function(){
	
	
	$( ".btn" ).on("click",eliminarEntrada);
	
	$("#bComprar").on("click", comprar);
	
});//end ready


function comprar(){
	
	
	
	
	//tarjeta
	var $numeroTar = $("#tCredito").val();
	
	
    var visaRegEx = /^(?:4[0-9]{12}(?:[0-9]{3})?)$/;
    var mastercardRegEx = /^(?:5[1-5][0-9]{14})$/;

    
    
    if (visaRegEx.test($numeroTar) === false ){ // Visa
     
    	
    	if (mastercardRegEx.test($numeroTar) === false){ // MasterCard
            
			$("#dInfo").attr("class","alert alert-danger");
			$("#dInfo").text("Numero tarjeta erróneo");
			return;
    		
    		
            }  
    	
     }  

    
    
    
	
	
	/*
	
	//visa															//mastercard
	if (!$numeroTar.match(/^4\d{3}-?\d{4}-?\d{4}-?\d{4}$/) || !$numeroTar.match(/^5[1-5]\d{2}-?\d{4}-?\d{4}-?\d{4}$/)){
		
			//no es visa ni mastercard
			$("#dInfo").attr("class","alert alert-danger");
			$("#dInfo").text("Numero tarjeta erróneo");
			return;
	}
	*/
	
	
	//mes
	var $mm = $("#caducidadMes").val();
	
	
	if($mm.length==2){
		
		$mm = parseInt($mm);
		
		if($mm<1 || $mm>12 || isNaN($mm)){
			$("#dInfo").attr("class","alert alert-danger");
			$("#dInfo").text("Més no válido (Ej. 02)");
			return;
		}
		
	}
	else{
		$("#dInfo").attr("class","alert alert-danger");
		$("#dInfo").text("Més no válido (Ej. 02)");
		return;
	}
	
	//Año
	var $ano = $("#caducidadAno").val();
	var dateA = new Date();
	var ano = dateA.getFullYear().toString();
	
	
	ano = ano.substring(ano.length-2, ano.length);
	
	
	ano = parseInt(ano);
	
	if($ano.length==2){
		
		
		$ano= parseInt($ano);
		
		if($ano<ano || $ano>99 || isNaN($ano)){
			$("#dInfo").attr("class","alert alert-danger");
			$("#dInfo").text("Año no válido (Ej.("+ano+")");
			return;
		}
		
		
		
	}
	else {
		$("#dInfo").attr("class","alert alert-danger");
		$("#dInfo").text("Año no válido (Ej.("+ano+")");
		return;
	}
	
	
	//cvv
	
	var $cvv = $("#tCvv").val();
	
	if($cvv.length ===3){
		
		$cvv = parseInt($cvv);
		
		if($cvv<1 || $cvv>999 || isNaN($cvv)){
			
			$("#dInfo").attr("class","alert alert-danger");
			$("#dInfo").text("CVV no válido.");
			return;
		}
		
		
	}
	else {
		$("#dInfo").attr("class","alert alert-danger");
		$("#dInfo").text("CVV debe tener 3 dígitos");
		return;
	}
	
	
	$("#fComprar").submit();

	  
	
}//End comprar



function eliminarEntrada(){
	
	
	
	var id = $(this).attr("id");
	
	if(id==="bComprar")
		return;

var $nodoSuperior = $(this).parent();
	//probar el nodo obtenido
$nodoSuperior.parent().effect("puff","","",function(){
		
	
	//obj = {nickUsu : nick, passUsu : pass};
	var obj = {idEliminar : id};
	var objAux = JSON.stringify(obj);


	$.ajax({

		// la URL para la petición
		url : '/entradasUsuarios/quitarEntrada',

		// la información a enviar
		data : objAux,
		// especifica si será una petición POST o GET
		type : 'POST',
		// el tipo de información que se espera de respuesta
		dataType : 'json',
		// código a ejecutar si la petición es satisfactoria;
		// la respuesta es pasada como argumento a la función
		success : function(respuesta){
			
			if(respuesta.error){
				//Si devuelve esto es que se ha eliminado todas las entradas.
				window.location.href = 'http://www.cinesmelero.es/peliculas/cartelera';
			}
			
			
			//console.log(respuesta.nuevoPrecio);
			//Se espera la respuesta del servidor el nuevo precio de las entradas, ya que han sido modificadas
			var nuevoPrecio = respuesta.nuevoPrecio;
			
			$("#pEntradas").text("Numero de entradas "+respuesta.totalEntradas+" x "+respuesta.precioEntrada+" €");
			$("#tDescuento").text("Total Descuento: "+respuesta.nuevoDescuento.toFixed(2)+" €");
			$("#pPrecio").text("Precio total: "+(parseFloat(nuevoPrecio).toFixed(2))+" €");

		},
		// código a ejecutar si la petición falla;
		error : function(jqXHR, status, error){
			alert("Se ha perdido la conexión con el servidor");
		}
	});
	
	//tras la peticion asincrona con el servidor se elimina el nodo
	//$(this).parent().remove();

	
	//var $nodoSuperior = $(this).parent();
				//probar el nodo obtenido
	$nodoSuperior.parent().remove();

});

}