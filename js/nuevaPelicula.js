

$(document).ready(function(){
	
	$("#bNuevo").on("click", function(){
		
		$("#bNuevo").attr("disabled",true);
		
		$("#formNuevo").submit();
		
	});
	
	
	$("#imgP").attr("disabled",true);
	
	//Para que si se recarga el formulario y lo ha marcado el usuario, aparezca habilitado el boton
	if( $("#chkTendencia").is(':checked') ){
		
		$("#imgP").attr("disabled",false);
	}
	
	
	$("#chkTendencia").on( "click", controlarChk);

	
	
	
});//end ready

/*
 * Habilita o deshabilita que el usuario pueda subir una imagen como tendencia si no esta marcado el checkbox
 */

function controlarChk(){

   if( $(this).is(':checked') ){
	        // Hacer algo si el checkbox ha sido seleccionado
		$("#imgP").attr("disabled",false);
	    } else {
	        // Hacer algo si el checkbox ha sido deseleccionado
	    	$("#imgP").attr("disabled",true);
	    }
	
}//end controlarChk



