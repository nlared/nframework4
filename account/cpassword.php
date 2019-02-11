<?php 
require_once('common.php');
require 'class.autoform2.php';
if($user->username=='guest'||$config['sitedb']==''){
    http_response_code(404);
    $content='Acceso denegado';
    exit();
}

$referer=parse_url($_SERVER['HTTP_REFERER']);
if($referer['host'].$referer['path']!=$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']){
    $_SESSION['referer']=$_SERVER['HTTP_REFERER'];
}

$data=$_POST['data'];
$dataset=new dataset([
    'collection'=>$m->{$config['sitedb']}->users,
    'simpleid'=>false,
    '_id'=>$user->_id,
    'nameprefix'=>'data']
);

if ($_POST['op']=="Cambiar"){
    if( $data['new1password']==$data['new2password'] and
    $dataset->password==hash('sha512',$data['oldpassword'])){
        $user->password=hash('sha512',$data['new1password']);
        notify('Datos guardados','Contraseña cambiada');
    }else{
        notify('Error en datos','Verifique por favor'); 
    }

}

$metro= new Metro();
$metro->backlink=$_SESSION['referer'];
$metro->secondary=true;
$metro->notify=$noti;
//echo $metro;
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
		    <h2 class="text-light">Cambiar contraseña</h2>
		    <hr class="thin mt-4 mb-4 bg-white">
		    <div class="form-group">
		        <input name="data[oldpassword]" type="text" data-role="input" data-prepend="<span class='mif-key'>" placeholder="Contraseña anterior..." data-validate="required">
		    </div>
		    <div class="form-group">
		        <input name="data[new1password]" type="text" data-role="input" data-prepend="<span class='mif-key'>" placeholder="Contraseña nueva..." data-validate="required minlength=6">
		    </div>
			<div class="form-group">
		        <input name="data[new2password]" type="text" data-role="input" data-prepend="<span class='mif-key'>" placeholder="Confirmar contraseña..." data-validate="required compare=data[new1password] minlength=6">
		    </div>
            <div class="form-group">
            	 <a href="<?=$_SESSION['referer']?>" class="button link">Cerrar</a>
                <input name="op" type="submit" class="button primary" value="Cambiar">
            </div>
        </form>
    </div>
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
<?=$javas?>
</body>