<?
include 'include.php';
$_SESSION['tipoingreso']=$_GET['tipoingreso'];
if($_SESSION['esperandotoken']){
	$_SESSION['tipoingreso']='token';
}
switch($_SESSION['tipoingreso']){
		case 'token' :
			// $select='
   //     			<div id="sat" onclick="sat()" class="button primary rounded btnpad bg-darkSteel bd-darkSteel" otro="sat">SAT</div>
   //     			<div id="contra" onclick="contra()" class="button primary rounded btnpad bg-darkSteel bd-darkSteel" otro="contra">Contraseña</div>
			// ';
			$login='
		        <div class="form-group">
		        	<label>Token<br>
		            <input type="text" name="token" data-role="input" data-prepend="<span class=\'mif-mobile\'>" placeholder="Teclea el TOKEN..." data-validate="required">
		            </label>
		        </div>
		        <div class="form-group mt-10 text-right">
		             <div class="button">Enviar Nuevo Token</div>
		             &nbsp;
		             <input name="op" type="submit" value="Iniciar" class="button ">
		        </div>
			';
		break;
		case 'sat' :
			$select='
        			<div id="contra" onclick="contra()" class="button primary rounded btnpad bg-darkSteel bd-darkSteel" otro="contra">Contraseña</div>
			';
			$login="
				<div class='form-group'>
			        <input name='login[username]' type='text' data-role='input' data-prepend='<span class=\"mif-envelop\">' placeholder='Correo electrónico...' data-validate='required email'>
			    </div>
				<div class='form-group'>
		        	<label>Certificado<br>
		            <input type='file' name='cert' data-role='file' accept='.cer' data-caption='.cer' data-prepend='<span class=\"mif-folder\">' data-validate='required'>
		            </label>
		            
		        </div>
		        <div class='form-group'>
		        	<label>Llave Privada<br>
		            <input type='file' name='key' data-role='file' accept='.key' data-caption='.key' data-prepend='<span class=\"mif-folder\">' data-validate='required'>
		            </label>
		        </div>
		        <div class='form-group'>
		        	<label>Contraseña de llave privada<br>
		            <input type='password' name='password' data-role='input' data-prepend='<span class=\"mif-key\">' placeholder='Enter your password...' data-validate='required'>
		            </label>
		        </div>
		        <div class='form-group mt-10 text-right'>
		             <input  name='op' type='submit' value='Iniciar' class='button '>
		        </div>
			";
		break;
		case 'contra':
			$select='
        			<div id="sat" onclick="sat()" class="button primary rounded btnpad bg-darkSteel bd-darkSteel" otro="sat">SAT</div>
			';
			$login='
				<div class="form-group">
			        <input name="login[username]" type="text" data-role="input" data-prepend="<span class=\'mif-envelop\'>" placeholder="Correo electrónico..." data-validate="required email">
			    </div>
			    <div class="form-group">
			        <input name="login[password]" type="password" data-role="input" data-prepend="<span class=\'mif-key\'>" placeholder="Contraseña..." data-validate="required minlength=6">
		        </div>
		        <div class="form-group">
		        	<a href="/" class="button link">Cancelar</a>
		            <input name="op" type="submit" class="button primary" value="Iniciar">
		        </div>
			';
		break;
	}


$result=[
	'login'=>$login,
	'select'=>$select,
];
