<?
require_once 'common.php';
if(isset($_POST['op'])){
	$login=$_POST['login'];
}
if(isset($_GET['username'])){
	$login=[
		'username'=>$_GET['username'],
		'code'=>$_GET['code']
		];
}

if ($login['code']!=''){
	$user= new User([
		'username'=>  strtolower(trim($login['username'])),
		'activationcode'=>$login['code']
		]);
	if ($user->activationcode!=''&&$user->activationcode == $login['code']){
		$_SESSION['user']=(string)$user->username;
		unset($user->activationcode);
		$user->datetime=date('Y-m-d H:m:s');
		echo 'Bienvenido...'.$_SESSION['user'].'<a href="/">Iniciar</a>
		<script>
		window.location.replace("/");
		</script>
		';
	}
}else{
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
    <form method="POST" id="activarform" class="login-form bg-white p-6 mx-auto border bd-default win-shadow animated fadeInUp"
      data-clear-invalid="2000"
      data-on-error-form="invalidForm"
      data-on-validate-form="validateForm" >
		<span class="mif-vpn-lock mif-4x place-right" style="margin-top: -10px;"></span>
	    <h2 class="text-light">Activar cuenta</h2>
	    <hr class="thin mt-4 mb-4 bg-white">
		<div class="form-group">
        	<input name="login[username]" type="text" 
        	data-role="input"
        	data-icon="<span class='mif-envelop'>"
			data-label="User email"
			data-informer="Enter a valid email address"
        	data-prepend="<span class='mif-envelop'>" 
        	placeholder="Correo electrónico..." 
        	data-validate="required email">
		</div>
		<div class="form-group">
        	<input name="login[code]" type="password"  
        	data-role="input" 
        	data-icon="<span class='mif-envelop'>"
    		data-label="User email"
    		data-informer="Enter a valid email address"
        	data-prepend="<span class='mif-key'>" 
        	placeholder="Contraseña..." 
        	data-validate="required minlength=6">
    	</div>
    	<div class="form-group">
        	<a href="/" class="button link">Cancelar</a>
            <input name="op" type="submit" class="button primary" value="Activar">
        </div>
	</form>
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
<?}?>