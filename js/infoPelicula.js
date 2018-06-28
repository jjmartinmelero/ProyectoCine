

$( document ).ready(function() {
	
	$("#coment_comentario").keyup(contar);
	$("#coment_comentario").keydown(contar);
	
	$("#bPublicar").on("click", enviarDatos);
	$("#bComent").on("click", obtenerComentarios);
	
	$('#txtArea').css('cursor', 'default');
	
});

function contar() { 
	
	if($("#coment_comentario").is(":focus")){
		var idPantalla = "labelComentario";
		var idTa = "coment_comentario";
	}
		
	else{
		var idPantalla = "labelEditar";
		var idTa = "taEditar";
	}
		
		
	
	
	var max = 100;
	var cadena = document.getElementById(idTa).value; 
    
	var longitud = cadena.length; 
	
	//LONGITUD BUENA !

	
        if(longitud > max) { 
        	
        	if(idTa == "coment_comentario")
        		$("#bPublicar").attr("disabled",true);
        	
        	$("#"+idPantalla).attr("class", "textoError");

        
        } else {
        	
        	if($("#bPublicar").attr("disabled")&&idTa=="coment_comentario"){
        		$("#bPublicar").attr("disabled",false);
        		
        	}

    		$("#"+idPantalla).attr("class", "");
        	
        } 
        
        
        document.getElementById(idPantalla).text = max-longitud;
        $("#"+idPantalla).text(max-longitud);

} 


function enviarDatos(){
	
	
	if($("#coment_comentario").val().trim()==""){
		toastr.error('Introduzca algun texto...');
		return;
	}
	
	$("#bPublicar").attr("disabled",true);
	

	var $codPelicula = $("#cod_pelicula").val();
	var $comentario = $("#coment_comentario").val();
	
	var obj = {codPelicula : $codPelicula,
				comentario : $comentario};
	var objAux = JSON.stringify(obj);

	
	$.ajax({
		// la URL para la petición
		url : '/comentarios/index',

		// la información a enviar
		data : objAux,
		// especifica si será una petición POST o GET
		type : 'POST',
		// el tipo de información que se espera de respuesta

		// código a ejecutar si la petición es satisfactoria;
		// la respuesta es pasada como argumento a la función
		success : function() {

			
		
			$("#coment_comentario").val("");
			
			
			$("#bPublicar").attr("disabled",false);
			
			toastr.success('Gracias por su opinión.');

			
			eliminarComentarios();
			
			obtenerComentarios();
			
			
		},
		// código a ejecutar si la petición falla;
		error : function(jqXHR, status, error) {
			console.log("Error en la conexion");
			
		}
	});
	
}


function obtenerComentarios(){
	
	
	if($("#bComent").attr("name")!="id_1"){

		console.log("eliminacion");
		eliminarComentarios();
		return;
	}
	
	
/*
	if($("#bComent").attr("name")=="id_1"){

		console.log("preparando para optener");
		$("#bComent").attr("value","Ocultar Comentarios");
		$("#bComent").attr("name","id_2");
		
	}
	else {
		
		console.log("eliminacion");
		eliminarComentarios();
		return;
	}
	*/
	

	console.log("next step");
	
	
	
	var $codPelicula = $("#cod_pelicula").val();
	
	var obj = {codPelicula : $codPelicula};
	var objAux = JSON.stringify(obj);

	
	$.ajax({
		// la URL para la petición
		url : '/comentarios/obtenerComentarios',

		// la información a enviar
		data : objAux,
		// especifica si será una petición POST o GET
		type : 'POST',
		// el tipo de información que se espera de respuesta
		dataType : 'json',
		// código a ejecutar si la petición es satisfactoria;
		// la respuesta es pasada como argumento a la función
		success : function(respuesta) {

			
		comentarios = respuesta.comentarios;

		if(comentarios.length === 0){
			toastr.info('No hay ningún comentario');
			return;
		}
		
		$("#bComent").attr("value","Ocultar Comentarios");
		$("#bComent").attr("name","id_2");
		
		//cambia el width del div
		$("#dComent").width('80%');
		

		$("#bComent").attr("disabled",false);
		
		insertarComentarios();
			
			
		},
		// código a ejecutar si la petición falla;
		error : function(jqXHR, status, error) {
			console.log("Error en la conexion");
			
		}
	});	
}

function eliminarComentarios(){
	
	var $nodoBoton = $("#bComent");
	
	$("#dComent").width('20%');
	
	$("#dComent").empty();
	
	$nodoBoton.prependTo('#dComent');
	
	$("#bComent").on("click", obtenerComentarios);
	$("#bComent").attr("name","id_1");
	$("#bComent").attr("value","Ver Comentarios");
	
}


function insertarComentarios(){
	
	//comentarios variable global
	console.log(comentarios);
	for (var i = 0; i < comentarios.length; i++) {

		var divCard = $("<div id='div"+comentarios[i].cod_comentario+"' class='card' role='alert'></div>");
		
		var header = $("<h5 class='card-header'>"+comentarios[i].nick_usuario+"<span class='datos'>"+comentarios[i].fecha+" - "+comentarios[i].tiempo+"</span></h5>");
		
		//var header2 = $("<label class='card-title'>"+comentarios[i].fecha+" "+comentarios[i].tiempo+"</label>");

		var contenido = $("<p>"+comentarios[i].comentario+"</p>");
		
		$enlaces = "";
		if(comentarios[i].puedeVotar===true){
			
			var footer = $("<div class='card-footer'><a onclick='votar(1,"+comentarios[i].cod_comentario+");' style='color:blue;'>Me gusta</a> - "+
							"<a onclick='votar(0,"+comentarios[i].cod_comentario+");' style='color:blue;'>No me gusta</a><span class='datos'>Positivos: "+
					comentarios[i].totalPositivos+" - Negativos: "+comentarios[i].totalNegativos+"</span></div>");
			
		}
		else{

			
			var footer = $("<div class='card-footer'><span class='datos'>Positivos: "+
					comentarios[i].totalPositivos+" - Negativos: "+comentarios[i].totalNegativos+"</span></div>");
			
			
			if(comentarios[i].esMio===true){
				var $editar = "<a style='color:blue;' class='edit' id='edit"+comentarios[i].cod_comentario+"' onclick='editar("+
																comentarios[i].cod_comentario+")'>Editar</a>";
				footer.append($editar);
				
			}
				
			
			
			
		}
		


		divCard.append(header);
		//divCard.append(header2);
		divCard.append(contenido);
		divCard.append(footer);		
		
		divCard.prependTo('#dComent');
		
		

	}//end for
	
	
}


function editar(){
	
	//deshabilitar el resto de editar
	$(".edit").hide();
	
	var codComentario = arguments[0];
	
	var $nodoDiv = $("#div"+codComentario);
	
	var $enlaceEditar = $("#edit"+codComentario);
	
	$copiaEnlace = $enlaceEditar;
	
	$enlaceEditar.remove();
	
	
	var $enlaces = "<a style='color:blue;' class='edicion' onclick='guardar(1,"+codComentario+")'>"+
						"Guardar</a>  <a style='color:blue;' class='edicion' onclick='guardar(0,"+codComentario+")'> Cancelar</a>";
	
	
	footer = $("#div"+codComentario+" .card-footer");
	
	footer.append($enlaces);

	var texto = $("#div"+codComentario+" p");
	
	
	var textArea = $("<textarea id='taEditar' name='textarea' rows='6' cols='50'>"+texto.text()+"</textarea>");
	
	var label = $("<label id='labelEditar'>100</label>");
	
	$("#div"+codComentario+" .card-header" ).after(textArea);
	
	textArea.after(label);
	textArea.focus();
	contar();
	textArea.keyup(contar);
	textArea.keydown(contar);
	
	
	copiaText = texto;
	
	texto.remove();
	
	
	
	
}


function guardar(){
	
	
	var resultado = arguments[0];
	var codComentario = arguments[1];
	
	
	if(resultado == 0){
		
		$("#div"+codComentario+" textarea" ).remove();

		$("#div"+codComentario+" label" ).remove();
		
		$("#div"+codComentario+" .card-header" ).after(copiaText);
		
		$(".edicion").remove();
		footer.append($copiaEnlace);
		$(".edit").show();
		return;
	}
	
	
	//si ha llegado aqui, significa que el usuario ha modificado el comentario y le ha dado a guardar
	
	var $comentario = $("#div"+codComentario+" textarea" ).val();
	
	if($comentario.length > 100){
		toastr.error('Límite de carácteres superado.');
		return;
	}
		
	

	if($("#div"+codComentario+" textarea" ).val().trim()==""){
		toastr.error('Introduzca algun texto...');
		return;
	}
	

	var $codPelicula = $("#cod_pelicula").val();
	
	var $codComentario = codComentario;
	
	var obj = {codPelicula : $codPelicula,
				comentario : $comentario,
				codigoComent : codComentario};
	var objAux = JSON.stringify(obj);

	
	$.ajax({
		// la URL para la petición
		url : '/comentarios/modificar',

		// la información a enviar
		data : objAux,
		// especifica si será una petición POST o GET
		type : 'POST',
		// el tipo de información que se espera de respuesta

		// código a ejecutar si la petición es satisfactoria;
		// la respuesta es pasada como argumento a la función
		success : function() {

			
		
			//$("#coment_comentario").val("");
			
			
			//$("#bPublicar").attr("disabled",false);
			
			//toastr.success('Gracias por su opinión.');

			
			eliminarComentarios();
			
			obtenerComentarios();
			
			
		},
		// código a ejecutar si la petición falla;
		error : function(jqXHR, status, error) {
			console.log("Error en la conexion");
			
		}
	});
	
	
	
	
	
	
}

 

function votar(){
	
	//arguments[0] ==0 -> no me gusta
	//arguments[0] ==1 -> me gusta
	
	
	var voto = arguments[0];
	var codMensa = arguments[1];
		
	var obj = {votoUsu : voto,
				codMensaje : codMensa};
	
	var objAux = JSON.stringify(obj);

	
	$.ajax({
		// la URL para la petición
		url : '/valoracionescomentarios/index',

		// la información a enviar
		data : objAux,
		// especifica si será una petición POST o GET
		type : 'POST',
		// el tipo de información que se espera de respuesta

		// código a ejecutar si la petición es satisfactoria;
		// la respuesta es pasada como argumento a la función
		success : function() {
			
			eliminarComentarios();
			
			obtenerComentarios();
			
			
		},
		// código a ejecutar si la petición falla;
		error : function(jqXHR, status, error) {
			console.log("Error en la conexion");
			
		}
	});
	
	
}

