<?php
if(isset($_POST["action"])) {
	$name = $_POST['name'];        // Sender's name
	$email = $_POST['email'];      // Sender's email address
	$phone  = $_POST['phone'];     // Sender's phone number
	$message = $_POST['message'];  // Sender's message
	$headers = "From: contacto@esbelta.com.mx" . "\r\n";
	$headers .= "X-Mailer: PHP/" . phpversion() . " \r\n";
            $headers .= "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	$to = "llaboratoriovisualmx@gmail.com, clinicaesbelta@live.com.mx";     // Recipient's email address
	$subject = "Mensaje de Esbelta "; // Message title

	$body = '
			<html>
				<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
					
					<title>Cl&iacute;nica Esbelta</title>
				</head>
				<body style="font-size:15px;">
					<div style="background: white; box-shadow: 5px 5px 15px #646464;">
						<div style="background: #A8DA6A; text-align: center;">
							<a href="http://esbelta.com.mx">
							<h1 style="display:inline-block;font-size:3em;color:#fff;">Cl&iacute;nica Esbelta</h1></a>
						</div>
						<div>
							
						</div>
						
							<h2 style="padding-left: .8em;">Usted tiene un mensaje nuevo.</h2>
							<p style="padding-left: .8em;"> Estos son los datos enviados</p>
							<table style="padding-left: 1em;">
								<tr>
									<td style="padding: 0;margin:0;"><strong>Nombre: </strong></td>
									<td style="padding: 0;margin:0;"><p>'.$name.'</p></td>
								</tr>
								<tr>
									<td style="padding: 0;margin:0;"><strong>Teléfono: </strong></td>
									<td style="padding: 0;margin:0;"><p>'.$phone.'</p></td>
								</tr>
								<tr>
									<td style="padding: 0;margin:0;"><strong>Correo: </strong></td>
									<td style="padding: 0;margin:0;"><p>'.$email.'</p></td>
								</tr>
                                <tr>
									<td style="padding: 0;margin:0;"><strong>Asunto: </strong></td>
									<td style="padding: 0;margin:0;"><p>'.$subject.'</p></td>
								</tr>
								<tr>
									<td style="padding: 0;margin:0;"><strong>Mensaje: </strong></td>
									<td style="padding: 0;margin:0;"><p>'.$message.'</p></td>
								</tr>
							</table>
							<p style="padding-left: .8em;"></p>
							<p style="padding-left: .8em;"></p>
						</div>
						<div style="background: #A8DA6A;">
							<div style="width: 45%;display:inline-block;vertical-align:top;">
								<p style="color: white;margin:0;padding:0 0 0 .8em; font-weight:bold;"></p>
								<p style="color: white;margin:0;padding:0 0 0 .8em; font-weight:bold;"></p>
								<p style="color: white;margin:0;padding:0 0 0 .8em; font-weight:bold;"></p>
							</div>
							<h4 style="color: white;text-align: center;"><a href="http://esbelta.com.mx" style="color: white;text-align: center;text-decoration:none;">www.esbelta.com.mx</a></h4>
							<div style="width: 50%;display:inline-block;vertical-align:top;">
								<p style="color: white;margin:0;padding:0 0 0 .8em; font-weight:bold;"></p>
								<p style="color: white;margin:0;padding:0 0 0 .8em; font-weight:bold;"></p>
								<p style="color: white;margin:0;padding:0 0 0 .8em; font-weight:bold;"></p>
								<p style="color: white;margin:0;padding:0 0 0 .8em; font-weight:bold;"></p>
							</div>
						</div>
					</div>	
				</body>
				</html>
				'  ;
	
	// init error message
	$errmsg='';
	// Check if name has been entered
	if (isset($_POST['name']) && $_POST['name'] == '') {
		$errmsg .= '<p>Por favor introduzca un nombre.</p>';
	}
	// Check if email has been entered and is valid
	if (!$_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$errmsg .= '<p>Por favor introduzca un correo electr贸nico valido.</p>';
	}
	//Check if phone number has been entered
	if ( isset($_POST['phone']) && $_POST['phone'] == '') {
		$errmsg .= '<p>Por favor introduzca un n煤mero de t茅lefono.</p>';
	}
	
	//Check if message has been entered
	if ( isset($_POST['message']) && $_POST['message'] == '') {
		$errmsg .= '<p>Introduzca su mensaje.</p>';
	}

	/* Check Google captch validation */
	if( isset( $_POST['g-recaptcha-response'] ) ){
		$error_message = validation_google_captcha( $_POST['g-recaptcha-response'] );
		if($error_message!=''){
			$errmsg .= $error_message;
		}
	}	
	
	$result='';
	// If there are no errors, send the email
	if (!$errmsg) {
		if (mail ($to, $subject, $body, $headers)) {
			$result='<div class="alert alert-success">Gracias por contactarnos, estaremos en contacto con usted</div>';
		}
		else {
		  $result='<div class="alert alert-danger">Ocurri贸 un error, favor de intentarlo nuevamente.</div>';
		}
	}
	else{
		$result='<div class="alert alert-danger">'.$errmsg.'</div>';
	}
	echo $result;
 }

/*
 * Validate google captch
 */
function validation_google_captcha( $captch_response){

	/* Replace google captcha secret key*/
	$captch_secret_key = '6LcAH6IUAAAAAG7ZGxwb2qxpskgPolryurs-0AAo';
	
	$data = array(
            'secret'   => $captch_secret_key,
            'response' => $captch_response,
			'remoteip' => $_SERVER['REMOTE_ADDR']
        );
	$verify = curl_init();
	curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
	curl_setopt($verify, CURLOPT_POST, true);
	curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
	curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($verify);
	$response = json_decode( $response, true );
	$error_message='';
	if( isset($response['error-codes']) && !empty($response['error-codes'])){
		if( $response['error-codes'][0] == 'missing-input-secret' ){
			
			$error_message = '<p>The recaptcha secret parameter is missing.</p>';
			
		}elseif( $response['error-codes'][0] == 'invalid-input-secret' ){
			
			$error_message = '<p>The recaptcha secret parameter is invalid or malformed.</p>';
			
		}elseif( $response['error-codes'][0] == 'missing-input-response' ){
			
			$error_message = '<p>The recaptcha response parameter is missing.</p>';
			
		}elseif( $response['error-codes'][0] == 'invalid-input-response' ){
			
			$error_message = '<p>The recaptcha response parameter is invalid or malformed.</p>';
			
		}elseif( $response['error-codes'][0] == 'bad-request' ){
			
			$error_message = '<p>The recaptcha request is invalid or malformed.</p>';
		}
	}	
	if( $error_message !=''){
		return $error_message;
	}else{
		return '';
	}
  }
