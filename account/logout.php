<?
require_once 'common.php';
unset($user);
unset($_SESSION['user']);
unset($_SESSION['emisor']);
session_regenerate_id(true); 
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
    left:50%;
    margin-left:-350px;
    margin-top: -200px;
}
</style>
<body class="h-vh-100 bg-brandColor">
    <div class="login-form col-xs-6 padding20 block-shadow">
        <form method="POST" id="newform" class="login-form bg-white p-6 mx-auto border bd-default win-shadow"
      data-role="validator"
      data-clear-invalid="2000"
      data-on-error-form="invalidForm"
      data-on-validate-form="validateForm">
        	<span class="mif-vpn-lock mif-4x place-right" style="margin-top: -10px;"></span>
		    <h2 class="text-light">Cerrando sesión</h2>
		    <hr class="thin mt-4 mb-4 bg-white">
		    <div class="form-group">
                <a href="/" class="button primary full-size">Cerrar</a>
            </div>
        </form>
    </div>
</body>
<script>
window.location.href = '/';
</script>