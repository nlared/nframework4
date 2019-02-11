<?
require 'include.php';
$user= new User(array('username'=>  strtolower(trim($_POST['username'])),'password'=>trim($_POST['password'])));
if($user->username!=''){
	$_SESSION['user']=$user->username;
	session_write_close();
	$result['login']='ok';
	$result['user']=$user;
}else{
	$result['login']='Datos incorrectos';
}
