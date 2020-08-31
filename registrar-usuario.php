<?php 

	// Envio de datos a Smart Home
	function PostApi( $dataForm ){
		//API URL
		$url = 'http://api.smart-home.com.co/api/LeadForm/';
		//create a new cURL resource
		$ch = curl_init($url);
		//setup request json via POST
		$data = array(
				'name' => $dataForm[ 'nombre' ]
			, 'email' => $dataForm[ 'email' ]
			,	'phone_number'	=>	$dataForm[ 'telefono' ]
			,	'origin'	=>	'pagina.web_location.href'
			,	'comment'	=> 'Registro desde Landing page Amaro'
			,	'projectId'	=>	'NFBJyAqCPD1G4QLZUowGG-Q3FM2ad92Mw8WeFrgrqT3YZi5A9nqSep9u3FPmB8wi'
			,	'locationSourceId'	=>	'b81daa2c-ff95-4f25-b8ab-d0c98c5e4fb7'
		);
		$payload = json_encode( $data );

		//attach encoded JSON string to the POST fields
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

		//set the content type to application/json
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

		//return response instead of outputting
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//execute the POST request
		$result = curl_exec($ch);
		// var_dump( $result );

		//close cURL resource
		curl_close($ch);

	}

	// Create response object Ajax
	$objLoad = ( object ) array(
		'validate' 	=> false
	);

	// Comprobamos host 
	if( $_SERVER[ 'HTTP_HOST' ] == 'localhost' ){
		// echo "localhost";
		// Localhost
		$host_db    = "localhost";
		$user_db    = "root";
		$pass_db    = "";
		$db_name    = "db_alcabama_landing";
		$tbl_name   = "tb_contacto";
		$p3_contacto = '';

	}else{
		// echo "Servidor";
		// Servidor
		$host_db    = "127.0.0.1";
		$user_db    = "alcabama";
		$pass_db    = "Th1sc0untry";
		$db_name    = "alcabama";
		$tbl_name   = "tb_contacto";
		$p3_contacto = '';

	}

	// Datos predeterminados
	$dataForm[ 'p1_contacto' ] = "";
	$dataForm[ 'p2_contacto' ] = "";
	$dataForm[ 'p3_contacto' ] = "";

	// Capturamos datos 
	if(isset($_POST['nombre'])){
			$dataForm[ 'nombre' ] = $_POST['nombre'];
	}
	if(isset($_POST['correo'])){
			$dataForm[ 'email' ] = $_POST['correo'];
	}
	if(isset($_POST['telefono'])){
			$dataForm[ 'telefono' ] = $_POST['telefono'];
	}
	if(isset($_POST['p1_contacto'])){
			$dataForm[ 'p1_contacto' ] = $_POST['p1_contacto'];
	}
	if(isset($_POST['p2_contacto'])){
			$dataForm[ 'p2_contacto' ] = $_POST['p2_contacto'];
	}
	if(isset($_POST['p3_contacto'])){
			$dataForm[ 'p3_contacto' ] = $_POST['p3_contacto'];
	}
	if(isset($_POST['idlanding'])){
			$dataForm[ 'idlanding' ] = $_POST['idlanding'];
	}
	
	if((isset($dataForm[ 'nombre' ])) && isset($dataForm[ 'email' ]) && isset($dataForm[ 'telefono' ])){
		// Enviamos datos a Smart home
		PostApi( $dataForm );


		$conexion = new mysqli($host_db, $user_db, $pass_db, $db_name);

		if ($conexion->connect_error){
				die("La conexion falló: " . $conexion->connect_error);
		}

    $query = "INSERT INTO $tbl_name (nombre_contacto, mail_contacto, tel_contacto, fecha_contacto, p1_contacto, p2_contacto, p3_contacto, id_landing) VALUES ('$dataForm[nombre]', '$dataForm[email]', '$dataForm[telefono]', NOW(), '$dataForm[p1_contacto]', '$dataForm[p2_contacto]', '$dataForm[p3_contacto]','$dataForm[idlanding]')";

		if ($conexion->query($query) === TRUE) {
		
				$objLoad -> validate = TRUE;
				
				
				$email_to = "amoreno@zocodigital.com, jrubio@zocodigital.com";

				$subject = "Contacto";
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				
				$message = "El usuario: ".$dataForm[ 'nombre' ].", con el correo electronico: ".$dataForm[ 'email' ].'. Esta interesado en el proyecto: '.$dataForm[ 'idlanding' ];
				
				// mail($email_to, $subject, $message, $headers);


		}
		
		// mysqli_close($conexion);

		echo json_encode( $objLoad );
		die(); // Siempre hay que terminar con die

	}


?>
