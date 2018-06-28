window.onload = function () {

	//generarRender();
	//fechaHoy();
	//$("#dGrafico").text("<i class='fa fa-info-circle' aria-hidden='true'></i>"+
   // " El contenido de la grafica se actuazará en 10 segundos";
	obtenerDatos();
	intervaloRefresco();
	cont = 0;
	
}


function intervaloRefresco(){
	
	setInterval(function(){
		obtenerDatos();
	//	cont++;
		
	/*	$("#dGrafico"),text("<i class='fa fa-info-circle' aria-hidden='true'></i>"+
			    " El contenido de la grafica se actuazará en"+(10-cont)+"segundos";
		
		if(cont==10){
			cont=0;
			obtenerDatos();
		}
		*/	
		
		
	}, 10000);

	
	
}


function obtenerDatos(){
	


	$.ajax({
		// la URL para la petición
		url : '/peliculas/datosSemanales',
		
		// especifica si será una petición POST o GET
		type : 'POST',
		// el tipo de información que se espera de respuesta
		dataType : 'json',
		// código a ejecutar si la petición es satisfactoria;
		// la respuesta es pasada como argumento a la función
		success : function(respuesta) {

			
		
			resultUsu = respuesta.usuarios;
			
			resultAnon = respuesta.anonimos;
						
			generarRender();
			
		},
		// código a ejecutar si la petición falla;
		error : function(jqXHR, status, error) {
			console.log("No hay conexion con el servidor");
			
		}
	});


}//end obtenerDatos



function generarRender(){

datos1 = crearJSONusuarios();
datos2 = crearJSONanonimos();



var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	title:{
		text: "Numero de ventas"
	},
	axisY:{
		includeZero: true
	},
	axisX: {
		valueFormatString: "DD MMM,YY"
	},
	data: [{
		name: "Usuarios Registrados",
		type: "spline",
		showInLegend: true,
		dataPoints: datos1
		
		
	},
	{
		name: "Usuarios Anonimos",
		type: "spline",
		showInLegend: true,
		dataPoints: datos2
		
		
	}]
});
chart.render();

}//end function generarRender

function fechaHoy(){


var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //Enero es 0!

var yyyy = today.getFullYear();
if(dd<10){
    dd='0'+dd;
} 
if(mm<10){
    mm='0'+mm;
} 
var today = dd+'/'+mm+'/'+yyyy;

//alert(today);

}//end fechaHoy


function crearJSONusuarios() {


	var jsonObj = [];

	for (var i = 0; i < 7; i++) {

		item = {}
		
			
			var fecha=new Date();
			
			var semana = 6-i;

			fecha.setDate(fecha.getDate() - semana);

			item ["x"] =  new Date(fecha.getFullYear(),(fecha.getMonth()+1),fecha.getDate());
    		
			
    		item ["y"] = resultUsu[i];

    		jsonObj.push(item);

		}//end for


	    return jsonObj;
}


function crearJSONanonimos() {

	var jsonObj = [];

	for (var i = 0; i < 7; i++) {

			item = {}
			
				
				var fecha=new Date();
				
				var semana = 6-i;

				fecha.setDate(fecha.getDate() - semana);

				item ["x"] =  new Date(fecha.getFullYear(),(fecha.getMonth()+1),fecha.getDate());
        		
        		item ["y"] = resultAnon[i];

        		jsonObj.push(item);

			}//end for

/*	
			var fecha=new Date();

			fecha.setDate(fecha.getDate());

			
			item ["x"] =  new Date(fecha.getFullYear(),(fecha.getMonth()+1),fecha.getDate());
    		
    		item ["y"] = resultAnon[6];

    		jsonObj.push(item);

    		console.log(jsonObj);
  */  		
		    return jsonObj;
}





