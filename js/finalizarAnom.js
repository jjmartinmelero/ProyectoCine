
// ON READY ******************************************************************************************
$(document).ready(function(){

	//si el txt del correo no esta deshabilitado desde php
	//se asigna la funcion
	//si esta deshabilitado es que hay un usuario en la aplicacion y no puede modificar este campo
	if($("txtCorreo").attr("disable")!==true)
		$("#chkCorreo").change(modificarInput);


});//end ready


function modificarInput(){

        if(this.checked){
            $("#txtCorreo").attr("disabled",false);
        }
        else{
        	$("#txtCorreo").attr("disabled",true);
        	$("#txtCorreo").val("");
        }
}//end modificar input