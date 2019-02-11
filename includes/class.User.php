<?php

class User {
    public $info;
    private $m;
    private $db;
    function __construct($info) {
        global $m, $config;
        //$this->info=array('username'=>'guest');
        //print_r($info);
        $this->m = $m;
        $this->info=[];
        if ($config['sitedb'] != '') {
            $this->db = $config['sitedb']; ///checar usuario no injection
            if (!isset($_SESSION['user'])) {
                if (isset($info['password'])){
                	$info['password'] = hash('sha512', $info['password']);
                }
                filter_var($info['username'], FILTER_VALIDATE_EMAIL);
                $this->info = (array)$this->m->{$this->db}->users->findOne($info);
                if($this->info['activationcode']!=$info['activationcode']){
                	header('Location: /account/activate.php');
                	exit();
                }
                
            }else {
                $this->info =(array) $this->m->{$this->db}->users->findOne(['username' => $_SESSION['user']]);
            }
        }
    }
   
    public function requireAuth(){
    	$_SESSION['nframework']['logiopage']=$_SERVER['DOCUMENT_URI'];
    	if($this->info['username']=='guest'){
    		header ('location: /account/login.php');
    		die();
    	}
    }
    public function can($verb){
    	return $this->info->permissions[$verb];
    }
    public function create($info) {
        $this->info = $this->m->{$this->db}->users->findOne(array('username' => $info['username']));
        if ($this->info) {
            $this->info['error'] = 'Cuenta ya existe';
        } else {
            $info['password'] = hash('sha512', $info['password']); //hash
            $info['activationcode'] = uniqid();
            $this->m->{$this->db}->users->insertOne($info);
            $this->info =(array) $this->m->{$this->db}->users->findOne($info);
        }
    }

    public function data() {
        return $info;
    }

	public function gravatar($width='',$height=''){
		$_SESSION['images']['avatar'.$width.'x'.$height]=[
			'src'=>$_SERVER['DOCUMENT_ROOT'].'/profiles/',
			'dst'=>$_SERVER['DOCUMENT_ROOT'].'/profiles/mini/',
			'width'=>$width,
		];
		return '/nframework/imagen.php?id=usermini&file='.$this->info['_id'].'.png';
	}
	

    public function usermenu() {
        global $themecolor,$config;
        
        
		$_SESSION['images']['usermini']=[
			'src'=>$_SERVER['DOCUMENT_ROOT'].'/profiles/',
			'dst'=>$_SERVER['DOCUMENT_ROOT'].'/profiles/mini/',
			'width'=>'32',
			
		];
        
        
        $addtheme = ' ' . $themecolor;
        if($this->info['username'] != 'guest' &&$this->info['username']!=''){
        $result='
        <a class="app-bar-item dropdown-toggle marker-light pl-1 pr-5" href="#">
            <img class="rounded"  data-email="'.$this->info['username'].'" data-size="25" src="/nframework/imagen.php?id=usermini&file='.$this->info['_id'].'.png">
        </a>
        <ul class="v-menu place-right" data-role="dropdown" style="display: none;">
            <li><a href=""><strong>'.$this->info['nombres'].'</strong></a></li>
            <li class="divider"></li>
        	<li><a href="/account/myprofile.php"><span class="mif-profile icon"></span> Perfil</a></li>
			<li><a href="/account/cpassword.php"><span class="mif-key icon"></span> Cambiar Contraseña</a></li>
			<li><a href="/account/logout.php"><span class="mif-exit icon"></span> Salir</a></li> 
			<li class="divider"></li>
			<li><a href="javascript:Metro.dialog.open(\'#dialogreport\')"><span class="mif-bug icon"></span> Reportar un problema</a></li> 
        </ul>';
         
         
       /* <a href="#"><img src="/nframework/imagen.php?id=usermini&amp;file='.$this->info['_id'].'.png" class="mif-2x icon"> '.$this->info['nombres'].'</a>
    	<ul class="d-menu context place-right" data-role="dropdown">
			<li><a href="/account/myprofile.php"><span class="mif-profile icon"></span> Perfil</a></li>
			<li><a href="/account/cpassword.php"><span class="mif-key icon"></span> Cambiar Contraseña</a></li>
			<li><a href="/account/logout.php"><span class="mif-exit icon"></span> Salir</a></li> 
			<li class="divider"></li>
			<li><a href="javascript:Metro.dialog.open(\'#dialogreport\')"><span class="mif-bug icon"></span> Reportar un problema</a></li> 
    	</ul>';*/   
            
        }else{        
        $result='<a href="#">Iniciar&nbsp;<span class="mif-enter icon"></span></a>
        <ul class="d-menu context place-right" data-role="dropdown" data-no-close="true">
			<div class="p-3 fg-black" style="width:300px">
                <form method="POST" data-role="validator" action="/account/login.php">
                    <h4 class="text-light">Iniciar sesión...</h4>
                    
                    <div class="frm-group">
                    	<label>Usuario</label>
                        <input name="login[username]" data-role="input" data-prepend="<span class=\'mif-user\'></span>"  
                        type="text" data-validate="required email">
                    </div>
                    <div class="frm-group">
                    	<label>Contraseña</label>
                        <input name="login[password]" data-role="input" data-prepend="<span class=\'mif-lock\'></span>" 
                        type="password" data-validate="required">
                    </div>
                    <label class="input-control checkbox small-check">
                        <input name="login[remember]" type="checkbox">
                        <span class="check"></span>
                        <span class="caption">Recordar me</span>
                    </label>
                    <div class="form-group">
                    	'.($config['canregister']?'<button href="/account/new.php" class="button">Registrate</button>':'').'
                    	<input name="op" value="Iniciar" class="button" type="submit">
                    </div>
                </form>
            </div>
		</ul>'; 
        }       
        
        return $result;
    }

    public function __isset($name) {
        return isset($this->info[$name]);
    }

    public function __set($name, $value) {
        switch ($name) {
            case 'username':
            case '_id':
                return true;
                break;
            default:
                if ($this->info[$name] != $value) {
                    $this->info[$name] = $value;
                    $this->m->{$this->db}->users->updateOne(
                    	['_id'=>$this->info['_id']],
                    	['$set'=>[$name=>$value]]
                    );
                }
        }
    }

    public function __unset($name) {
        switch ($name) {
            case 'username':
            case '_id':
                return true;
                break;
            default:
                unset($this->info[$name]);
                $this->m->{$this->db}->users->updateOne(
                	['_id'=>$this->info['_id']],
                	['$unset'=>[$name=>'']]);
        }
    }

    public function __get($name) {
        switch ($name) {
            case 'fullname':
                return $this->info['nombres'] . ' ' .
                $this->info['primerap'].' '.
                $this->info['segundoap'];
                break;
            case '':
                return false;
                break;
            case '_id':
                return (string)  $this->info['_id'];
                break;                
            default:
               // if ($this->info)) {
               if (array_key_exists($name, $this->info)) {
               //     if (property_exists( $this->info,$name)) {
                        return $this->info[$name];
                 //   }
               }
        }
        return false;
    }
    public function __debugInfo(){
    	return [
    		'db'=>$this->db,
    		'info'=>$this->info
    		];
    }
}