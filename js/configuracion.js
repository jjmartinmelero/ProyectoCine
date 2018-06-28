// ON READY ******************************************************************************************
$(document).ready(function(){
	
	$("#bAplicar").click(botonGuardar);
	$("#bNoti").click(actualizarNoti);
});//end ready



function botonGuardar() {
	
	//.effect( effect [, options ] [, duration ] [, complete ] )
	
	//$("#divForm").effect("fade","","",function(){
		
		//Envio formulario
		$("#formTema").submit();
		
	//});
	

	
}//End botonGuardar

