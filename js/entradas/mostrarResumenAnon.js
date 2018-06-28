
// ON READY ******************************************************************************************
$(document).ready(function(){
	
	$( ".btn" ).on("click",eliminarEntrada);
	
	$("#bComprarA").on("click", comprar);
	
	
});//end ready



function comprar(){
	
	
	//tarjeta
	var $numeroTar = $("#tCreditoA").val();
	
	
    var visaRegEx = /^(?:4[0-9]{12}(?:[0-9]{3})?)$/;
    var mastercardRegEx = /^(?:5[1-5][0-9]{14})$/;

    
    
    if (visaRegEx.test($numeroTar) === false ){ // Visa
     
    	
    	if (mastercardRegEx.test($numeroTar) === false){ // MasterCard
            
			$("#dInfoA").attr("class","alert alert-danger");
			$("#dInfoA").text("Numero tarjeta erróneo");
			return;
    		
    		
            }  
    	
     }  
    
	//mes
	var $mm = $("#caducidadMesA").val();
	
	
	if($mm.length==2){
		
		$mm = parseInt($mm);
		
		if($mm<1 || $mm>12 || isNaN($mm)){
			$("#dInfoA").attr("class","alert alert-danger");
			$("#dInfoA").text("Més no válido (Ej. 02)");
			return;
		}
		
	}
	else{
		$("#dInfoA").attr("class","alert alert-danger");
		$("#dInfoA").text("Més no válido (Ej. 02)");
		return;
	}
	
	//Año
	var $ano = $("#caducidadAnoA").val();
	var dateA = new Date();
	var ano = dateA.getFullYear().toString();
	
	
	ano = ano.substring(ano.length-2, ano.length);
	
	
	ano = parseInt(ano);
	
	if($ano.length==2){
		
		
		$ano= parseInt($ano);
		
		if($ano<ano || $ano>99 || isNaN($ano)){
			$("#dInfoA").attr("class","alert alert-danger");
			$("#dInfoA").text("Año no válido (Ej.("+ano+")");
			return;
		}
		
		
		
	}
	else {
		$("#dInfoA").attr("class","alert alert-danger");
		$("#dInfoA").text("Año no válido (Ej.("+ano+")");
		return;
	}
	
	
	//cvv
	
	var $cvv = $("#tCvvA").val();
	
	if($cvv.length ===3){
		
		$cvv = parseInt($cvv);
		
		if($cvv<1 || $cvv>999 || isNaN($cvv)){
			
			$("#dInfoA").attr("class","alert alert-danger");
			$("#dInfoA").text("CVV no válido.");
			return;
		}
		
		
	}
	else {
		$("#dInfoA").attr("class","alert alert-danger");
		$("#dInfoA").text("CVV debe tener 3 dígitos");
		return;
	}
	
	
	$("#fComprarA").submit();

	  
	
}//End comprar


function eliminarEntrada(){
	
	var id = $(this).attr("id");
	
	if(id==="bComprarA")
		return;


var $nodoSuperior = $(this).parent();
	//probar el nodo obtenido
$nodoSuperior.parent().effect("puff","","",function(){
	
	//obj = {nickUsu : nick, passUsu : pass};
	var obj = {idEliminar : id};
	var objAux = JSON.stringify(obj);


	$.ajax({

		// la URL para la petición
		url : '/entradasAnonimos/quitarEntrada',

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

			$("#entradaA").text("Numero de entradas "+respuesta.totalEntradas+" x "+respuesta.precioEntrada+" €");
			
			$("#precioA").text("Precio total: "+(parseFloat(nuevoPrecio).toFixed(2))+" €");

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