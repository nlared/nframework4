<?php
define('E_FATAL',  E_ERROR | E_USER_ERROR | E_PARSE | E_CORE_ERROR |  E_COMPILE_ERROR );
set_error_handler(function ($errno, $errstr, $errfile, $errline){
	global $developermode,$m,$config;
	if (!$developermode){
	    if($errno ^ E_NOTICE ){
		    $errordoc=[
		        'type'=>'System',
		        'file'=>$errfile,
		        'line'=>$errline,
		        'number'=>$errno,
		        'desc'=>$errstr,
		    ];    
		    $result=$m->{$config['sitedb']}->errorlog->updateOne($errordoc,[
		        '$inc'=>['tries'=>1],
		        '$set'=>['lasttime'=>date('Y-m-d H:i:s')]
		        ],['upsert'=> true]);
		        
	        if($errordoc['number'] & E_FATAL){
		       echo 'ocurrio una incidencia en el programa, reportando el problema para su solucion, disculpe las molestias ';
		       require_once('class.PHPMailer.php');
		          if(isset($result['upserted'])){
		            $mail = new PHPMailer();
		            $mail->isSMTP();                                      // Set mailer to use SMTP
		           	$mail->Host=$config['mailhost'];
					$mail->Port=$config['mailport'];
					$mail->SMTPAuth=$config['mailsmtpauth'];
					$mail->Username=$config['mailusername'];
					$mail->Password=$config['mailpassword'];
					$mail->Subject = 'Incidencia critica '.$result['upserted']  ;
					$mail->From = 'contacto@hmail.nlared.com';
		            $mail->FromName = 'Incidencia critica';
		            $mail->addAddress('quique@nlared.com', 'Enrique Flores'); // Add a recipient
		            $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
		            $mail->IsHTML(true);
		            $mail->Body    = 'A ocurrido una incidencia critica #'.$result['upserted'];
		            $mail->AltBody = 'A ocurrido una incidencia critica #'.$result['upserted'];
		            if(!$mail->send()) {
		                echo 'Error enviando correo';
		            }
		        }
			}
		}
	    return true;
	}else{
		return false;
	}
});

/*
function exception_handler($exception) {
      echo "Uncaught exception: " , $exception->getMessage(), "\n";
      return false;
   }
set_exception_handler('exception_handler');
*/
date_default_timezone_set('America/Mexico_City');
