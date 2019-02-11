
<?php
require_once('include.php');
require_once "recaptchalib.php";

unset($_SESSION['user']);
if(isset($_POST['op'])){
	// empty response
	$response = null;
	// check secret key
	$reCaptcha = new ReCaptcha($config['google-captcha-invisible-secret']);
	$response = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
    );
    $login=$_POST['login'];
    $user= new User(array());
    //$username =  AntiXSS::setFilter($login['username'], "whitelist", "string");
   
 	if ($response != null && $response->success) {
        if (filter_var($login['username'], FILTER_VALIDATE_EMAIL)){
            $user->create(array('username'=>$login['username'],'password'=>$login['password']));
            //echo $user->activationcode;
            if($user->activationcode!=''){
                $_SESSION['user']=$user->username;

                $mail = new PHPMailer;
                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = $config['mailhost'];  // Specify main and backup server
                $mail->Port=$config['mailport'];
                $mail->SMTPAuth = ($config['mailauth']=='on');                               // Enable SMTP authentication
                $mail->Username = $config['mailusername'];                            // SMTP username
                $mail->Password = $config['mailpassword'];                           // SMTP password
                $mail->SMTPSecure = $config['mailcrypt'];                            // Enable encryption, 'ssl' also accepted

                $mail->From = $config['mailusername'];
                $mail->FromName = 'Delivery';
                $mail->addAddress($user->username);  // Add a recipient
                $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Activacion '.$config['site'].' '.$user->username .' '.$user->activationcode;
                $mail->Body    = 'Este correo es enviado automaticamente <b>no require ser confirmado!</b></br>
                Su codigo de activacion es '.$user->activationcode.' <br>
                <a href="https://'.$_SERVER['HTTP_HOST'].'/account/activate.php?username='.
                $user->username.'&code='.$user->activationcode. '">Pulse aqui para activar</a>
                ';
                $mail->AltBody = 'Codigo de activacion:'.$user->activationcode;
                if(!$mail->send()) {
                   $msgError= 'El mensaje no pudo ser enviado.<br>Error: ' . $mail->ErrorInfo;

                }else{			
                        header('Location: activate.php?username='.$user->username);
                }
            }else{
                $msgError=$user->error;
            }	
        }else{
                $msgError='Error en datos de entrada';
        }			 			
    }else{
        $msgError='Error en capcha';
    }
}
require_once('common.php');
?>
<!--script src='https://www.google.com/recaptcha/api.js'></script-->
<style>
      body { 
    background-image: url('/img/loginback.jpg');
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center; 
}
.login-form {
    width: 350px;
    height: auto;
    top: 50%;
    margin-top: -200px;
}
.animated {
  -webkit-animation-duration: 1s;
  animation-duration: 1s;
  -webkit-animation-fill-mode: both;
  animation-fill-mode: both;
}
.fadeInUp {
  -webkit-animation-name: fadeInUp;
  animation-name: fadeInUp;
} 
</style>

<body class="h-vh-100 bg-brandColor">
	 <form method="POST" id="newform" class="login-form bg-white p-6 mx-auto border bd-default win-shadow animated fadeInUp"
      data-clear-invalid="2000"
      data-on-error-form="invalidForm"
      data-on-validate-form="validateForm" >
	
	<span class="mif-vpn-lock mif-4x place-right" style="margin-top: -10px;"></span>
	    <h2 class="text-light">Nueva</h2>
	    <hr class="thin mt-4 mb-4 bg-white">
	    <div class="form-group">
	        <input name="login[username]" type="text" data-role="input" data-prepend="<span class='mif-envelop'>" placeholder="Correo electrónico..." data-validate="required email">
	    </div>
	    <div class="form-group">
	        <input name="login[password]" type="password" data-role="input" data-prepend="<span class='mif-key'>" placeholder="Contraseña..." data-validate="required minlength=6">
        </div>
        <div class="form-group">
	        <input name="login[password2]" type="password" data-role="input" data-prepend="<span class='mif-key'>" placeholder="Contraseña..." data-validate="required minlength=6">
        </div>
        <div class="form-group">
        	<a href="/" class="button link">Cancelar</a>
            <input name="op" type="submit" class="button primary" value="Crear">
        </div>
        <div class="form-group centrar">
        <div class="g-recaptcha" data-sitekey="<?=$config['google-captcha-invisible-key']?>"></div>
        
     </div>
     <div class="form-group"><?=$msgError?>
        </div><script src="https://www.google.com/recaptcha/api.js?" async defer></script>
    </form>
    
<?=$javas?>
<script>
function invalidForm(){
    var form  = $(this);
    form.addClass("ani-ring");
    setTimeout(function(){
        form.removeClass("ani-ring");
    }, 1000);
}

function validateForm(){
    $(".login-form").animate({
        opacity: 0
    });
}
</script>