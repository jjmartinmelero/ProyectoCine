<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo $titulo;?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- LIBRERIAS **************************************************************** -->


<!-- jquery -->
<script type="text/javascript" src="/js/jquery/jquery-3.3.1min.js"></script>

<!-- Efectos -->
<script type="text/javascript" src="/js/jqueryui1.12/jquery-ui.min.js"></script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<!-- Bootstrap core CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

<!-- Material Design Bootstrap -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.0/css/mdb.min.css" rel="stylesheet">


<!-- Alertas -->
<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<!-- ARCHIVOS ESTAN EN LOCAL -->
<!-- Bootstrap core CSS 
<link href="/estilos/bootstrapmd/bootstrap.min.css" rel="stylesheet">
-->
<!-- Material Design Bootstrap 
<link href="/estilos/bootstrapmd/mdb.min.css" rel="stylesheet">
-->


<!-- Tema para navBar DA ERRORES DE FUENTE EN CONSOLA ************************
<link href="https://mdbootstrap.com/wp-content/themes/mdbootstrap4/css/compiled.min.css?ver=4.4.0" rel="stylesheet">
-->


<!-- css para el funcionamiento general BARRA NAVEGACION-->
<link rel="stylesheet" type="text/css" href="/estilos/principalCabecera.css" />

<!-- Icono aplicacion web -->
<link rel="icon" type="image/png" href="/imagenes/favicon2.jpg" />
		
		
		
		<?php 
			if (isset($this->textoHead))
			    echo $this->textoHead; 
			
	    ?>
	    
	    <style type="text/css">
	    
	       	<?php 
			
	       	if(isset($_COOKIE["temaApp"])){
	       	    
	       	    Sistema::app()->colorApp = $_COOKIE["temaApp"];

	       	}
	        
	        ?>
	        
	           html,body {
	               background-color: <?php echo Sistema::app()->colorApp.";";?>
	           }

/*	    
	       #listaDrop div *{
	       			
		width:80%;
		background-color:blue;
	
	       }
	    
	       #listaDrop div a:hover{
	       width:80%;
	       background-color:red;}
	       
	    */
	    </style>
	    
	    
	    
	</head>
	
	<body>
	
		<!-- Barra de navegacion ******************************************************************-->
	
<nav class="mb-1 navbar navbar-expand-lg navbar-dark danger-color-dark font-weight-bold" id="navPrincipal">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-5" aria-controls="navbarSupportedContent-5"
                    aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent-5">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo Sistema::app()->generaURL(array("inicial","index"));?>">
                            Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo Sistema::app()->generaURL(array("peliculas","cartelera"))?>">Cartelera</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo Sistema::app()->generaURL(array("peliculas","proximamente"))?>">Próximamente</a>
                        </li>
                        
                    </ul>
                    <ul class="navbar-nav ml-auto nav-flex-icons">
                    
                    <!-- Enlaces en la parte derecha -->
                    
                    
                        <?php if(Sistema::app()->acceso()->hayUsuario()&&Sistema::app()->acceso()->puedeConfigurar()){?>
                        <li class="nav-item">
                        
                            <a class="nav-link" href="<?php echo Sistema::app()->generaURL(array("pasesPeliculas","index"));?>">Sesiones</a>
                        
                        </li>
                        <?php }?>
                    
                    
                        <?php if(!Sistema::app()->acceso()->hayUsuario()){?>
                        <li class="nav-item">
                        
                            <a class="nav-link" href="<?php echo Sistema::app()->generaURL(array("usuarios","login"));?>">Iniciar Sesión</a>
                        
                        </li>
                        <?php }?>
                        
                        <?php if(Sistema::app()->acceso()->hayUsuario()){?>
                        
                        <li class="nav-item dropdown" id="listaDrop">
                            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo Sistema::app()->acceso()->getNick();?></a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-purple" aria-labelledby="navbarDropdownMenuLink-5">
                                
                                
                                <a class="dropdown-item" href="<?php echo Sistema::app()->generaURL(array("usuarios","configuracion"))?>">Configuración</a>
                                
                                <a class="dropdown-item" href="<?php echo Sistema::app()->generaURL(array("usuarios","compras"))?>">Mis Compras</a>
                                
                                <?php iF(Sistema::app()->acceso()->puedeConfigurar()){?>
                                
                                 <a class="dropdown-item" href="<?php echo Sistema::app()->generaURL(array("peliculas","index"))?>">
                                 Gestor peliculas</a>
                                
                                <?php }?>
                                
                                <a class="dropdown-item" href="<?php echo Sistema::app()->generaURL(array("usuarios","quitarLogin"))?>">
                                Cerrar Sesión</a>
                            </div>
                        </li>



                        
                        <?php }?>
                        
                         <?php if(!Sistema::app()->acceso()->hayUsuario()){?>
                        <li class="nav-item">
                        
                            <a class="nav-link" href="<?php echo Sistema::app()->generaURL(array("usuarios","registrarUsuario"))?>">
                            Registrarse</a>
                        
                        </li>
                        <?php }?>
                        
                        
                    </ul>
                </div>
            </nav>


		
		
		
		
		<!-- CONTENIDO ******************************************************************-->
		
		<div id="todo">
			<?php echo $contenido;?>


		</div>
		
		
		
		 <!-- SCRIPTS -->
		 
		 <!--  Canvas -->
	 <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	
	<!-- ALERTAS CON JQUERY CLASE TOASTR -->	 
    <!--  <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>

    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="/js/bootstrapmd/popper.min.js"></script>
    
    <!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <!-- Bootstrap core JavaScript
    <script type="text/javascript" src="/js/bootstrapmd/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="/js/bootstrapmd/mdb.min.js"></script>

    
	
	</body>		
</html>
