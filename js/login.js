
// ON READY ******************************************************************************************
$(document).ready(function(){
	
	//Deshabilitar boton inicialmente
	$("#bLogin").attr("disabled",true);
	
	//Color al cargar la pagina inicialmente
	$("#bLogin").css({"backgroundColor":"gray"});
	$("#bLogin").css({"borderColor": "gray"});
	

	rellenaDatos();
    
	
	compruebaInputs();

	$("#inputPass").keyup(compruebaInputs);
	$("#inputNick").keyup(compruebaInputs);
	
	$("#bLogin").click(peticionValidarUser);
	

});//end ready

//-------------------------------------------------------------------------------------------------------------

//ESTILOS *****************************************************************************************************
function compruebaInputs(){
	
	//Comprobando el input de la contraseña

		
		if($("#bLogin").attr("disabled")=="disabled" && $("#inputNick").val()!="" && $("#inputPass").val()!="" ){
			
			$("#bLogin").attr("disabled",false);
			$("#bLogin").css({"backgroundColor":"red"});
			
		}
		
		if($("#bLogin").attr("disabled")!="disabled" && ($("#inputNick").val()=="" || $("#inputPass").val()=="")){
			
			$("#bLogin").attr("disabled",true);
			$("#bLogin").css({"backgroundColor":"gray"});
		}

	
}//End compruebaInputs


//EJECUTADO EN EL ONCLICK DEL BOTON*********************************************************************************
function recordarUsuario(){
	

	//El usuario y contraseña existen

	if($(":checkbox").prop('checked')){
		setCookie("nick",$("#inputNick").val(),30);
		setCookie("pass",$("#inputPass").val(),30);
		
		
	}
	else {
		//Si la opcion de recordar usuario no esta vacia, elimino la cookie
		eliminaCookie("nick");
		eliminaCookie("pass");
		
	}
	
}//End recordarUsuario


//PETICION AJAX CON ENVIO DE DATOS EN JSON ******************************************************************

function peticionValidarUser(){

	//Ejecuto la funcion de peticion ajax
	peticionAjax();
	


	
}//End peticionValidar


function peticionAjax(){
	
	let nick = $("#inputNick").val();
	let pass = $("#inputPass").val();
	
	obj = {nickUsu : nick, passUsu : pass};
	//var obj = {nickUsu : nick};
	var objAux = JSON.stringify(obj);

	$.ajax({
		// la URL para la petición
		url : '/usuarios/validarAjax',

		// la información a enviar
		data : objAux,
		// especifica si será una petición POST o GET
		type : 'POST',
		// el tipo de información que se espera de respuesta
		dataType : 'json',
		// código a ejecutar si la petición es satisfactoria;
		// la respuesta es pasada como argumento a la función
		success : function(respuesta) {
		
			resultAjax = respuesta.existeNick;
			
			if(!resultAjax){
				$("#fLogin").submit();
				//La peticion ajax devuelve que el usuario no existe en el sistema
				return false;//me salgo de la funcion
			}
			
			//Si la peticion devuelve que el usuario existe
			recordarUsuario();
			
			
			//Si llega aqui, el usuario existe y la contraseña cumple con los requisitos de seguridad
			//ejecuto la accion submit de php
			$("#fLogin").submit();
			
		},
		// código a ejecutar si la petición falla;
		error : function(jqXHR, status, error) {
			alert("Lo sentimos, no hay conexion");
			
		}
	});
	
	
}//End peticionAjax


//MANTENIMIENTO DE LAS COOKIES CON JAVASCRIPT *****************************************************************

function rellenaDatos(){
	
	let nick = obtenerCookie("nick");
	let pass = obtenerCookie("pass");
	
	
	if(nick=="-1"||pass=="-1"){
		return;
	}
	
	//$("#inputNick").attr("placeholder","nick");
	//$("#inputPass").attr("placeholder","pass");
	
	$("#inputNick").attr("value",nick);
	$("#inputPass").attr("value",pass);
	
	//$("#inputNick").val(nick);
	//$("#inputPass").val(pass);
	
}


//Obtiene el valor de la cookie (-1 en caso de no encontrarla)
function obtenerCookie() {

	var nombreCookie = arguments[0];

	let arrayCookies = document.cookie.split(";");//Genera ESPACIOS !!! 


	for (var i = 0; i< arrayCookies.length; i++) {
		
		let cookieAlmacenada = arrayCookies[i].split("=");

		if((cookieAlmacenada[0].trim())==nombreCookie)
			return cookieAlmacenada[1];

	}

	return "-1";

}//end obtenerCookie


//MODIFICA / CREA LA COOKIE
function setCookie() {
	
	let nombre = arguments[0];
	let valor = arguments[1];
	let diasCaduca = arguments[2]
 
	var caducidad = new Date() //coge la fecha actual
	caducidad.setDate(caducidad.getDate()+diasCaduca);
 
	//crea la cookie: incluye el nombre, la caducidad y la ruta donde esta guardada
	//cada valor esta separado por ; y un espacio
	document.cookie = nombre + "=" + escape(valor) + "; expires=" + caducidad.toUTCString();
}


function eliminaCookie(){

	let nombre = arguments[0];

	document.cookie = nombre+"=; max-age=0";

}



