<?php
/*if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}*/

require_once 'include.php';



if(isset($_POST['token'])){
	$user= new User([
		'username'=>  strtolower(trim($_SESSION['Tusername'])),
		'password'=>trim($_SESSION['Tpassword']),
		'token'=>trim($_POST['token'])
		]);
	if($user->username!=''){
		unset($_SESSION['Tpassword']);
		unset($_SESSION['Tusername']);
		
		$_SESSION['user']=$user->username;
		session_write_close();
        if( $_SESSION['nframework']['logiopage']!='' && $_SESSION['nframework']['logiopage']!='/account/login.php'){
        	
            header('location: '.$_SESSION['nframework']['logiopage']);                
        }else{
        	header('location: /');                
        }
        exit();
	}
	$msgError='Datos incorrectos';
}
require_once 'common.php';
?>
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
</style>
<body class="h-vh-100 bg-brandColor">
    <form method="POST" id="newform" class="login-form bg-white p-6 mx-auto border bd-default win-shadow"
      data-clear-invalid="2000"
      data-on-error-form="invalidForm"
      data-on-validate-form="validateForm">
		<span class="mif-vpn-lock mif-4x place-right" style="margin-top: -10px;"></span>
	    <h2 class="text-light">Inicio de Sesión</h2>
	    <hr class="thin mt-4 mb-4 bg-white">
	    <div class="form-group">
            <input type="text" name="token" data-role="input" data-prepend="<span class='mif-mobile'>" placeholder="Token" data-validate="required">
        </label>
        </div>
        <div class="form-group">
        	<a href="/" class="button link">Cancelar</a>
            <input name="op" type="submit" class="button primary" value="Iniciar">
        </div>
    </form>
</body>
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
<?
if($msgError){
	echo 'Notify("error","error");';
}
?>
</script>
</body>