<?php

	$config=array("CONTROLADOR"=> array("inicial"),
				  "RUTAS_INCLUDE"=>array("aplicacion/modelos","aplicacion/otras"),
				  "URL_AMIGABLES"=>true,
				  "VARIABLES"=>array("autor"=>"Juan Jesus Martin Melero",
				  					"direccion"=>"Antequera",
				                    "colorApp"=>"#333333",
				                     "descuentoUsu"=>1.50
				  ),
				  "BD"=>array("hay"=>true,
								"servidor"=>"localhost",
								"usuario"=>"default",
								"contra"=>"default",
            				    "basedatos"=>"cinefinal"),
                        	    "sesion"=>array("controlAutomatico"=>true),
                        	    "acceso"=>array("controlAutomatico"=>true),
                        	    "ACL"=>array("controlAutomatico"=>true)
				  );

