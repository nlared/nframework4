<?php 
require_once 'common.php';
$developermode=true;

if($user->username=='guest'){
	exit();
}

$dataset=new dataset([
    'collection'=>$m->{$config['sitedb']}->users,
    'simpleid'=>false,
    '_id'=>$data['_id'],
    'nameprefix'=>'data']
);

$userincludes['Direccion']='direccion.php';

$primerap   = new inputText(['dataset'=>$dataset,'addclass'=>'full-size', 'required'=>true,'field'=>'primerap' ,'caption'=>'Primer Ap.','placeholder'=>'Primer Apellido']);
$segundoap   = new inputText(['dataset'=>$dataset,'addclass'=>'full-size', 'required'=>false, 'field'=>'segundoap' ,'caption'=>'Segundo Ap.','placeholder'=>'Segundo Apellido']);
$nombres    = new inputText(['dataset'=>$dataset,'addclass'=>'full-size', 'required'=>true,'field'=>'nombres'  ,'caption'=>'Nombre(s)','placeholder'=>'Nombre(s)']);
$telefono	= new inputText(['dataset'=>$dataset,'addclass'=>'full-size', 'required'=>false,'field'=>'numtel' ,'caption'=>'Telefono','placeholder'=>'Telefono']);
$cel    	= new inputText(['dataset'=>$dataset,'addclass'=>'full-size', 'required'=>false,'field'=>'numcel' ,'caption'=>'Celular','placeholder'=>'Celular']);
$username   = new inputText(['dataset'=>$dataset,'addclass'=>'full-size', 'required'=>true,'field'=>'username' ,'caption'=>'Correo Electronico','placeholder'=>'email']);    
$nacimiento = new inputDate(['dataset'=>$dataset,'addclass'=>'full-size', 'required'=>false,'field'=>'fhcumpleaños','caption'=>'Fecha Nacimiento','format'=>'%Y-%m-%d','typse'=>'calendarpicker' ]);    
$sexo       = new Select(['dataset'=>$dataset,'caption'=>'Genero','field'=>'sexo','addclass'=>'full-size','required'=>true,'options'=>[''=>'Seleccione..','Masculino'=>'Masculino','Femenino'=>'Femenino']]);    
    
//$form= new Autoform($m->nlared->users,$user->_id);

$metro = new Metro();
$metro->backlink = $backlink;
$metro->secondary = true;
echo $metro;

$_SESSION['images']['profile']=[
	'src'=>'/var/www/sice.nlared.com/profiles/',
	'dst'=>'/var/www/sice.nlared.com/profiles/w200/',
	'width'=>'200'
	];


foreach($userincludes as $includetitle=>$includefile){
	include str_replace('.php','.inc.php',$includefile);
}

if($_POST['op']=="Guardar"){
	$dataset->save();
	$dataset->nombrecompleto=strtoupper(trim($dataset->nombres.' '.$dataset->primerap.' '.$dataset->segundoap));
}

?>
<br>
<form method="post">
<input type="hidden" name="data[_id]" value="<?=$dataset->_id?>">
<div class="container p-5">
    <ul class="" data-role="materialtabs" data-on-tab-change="tab_change" data-expand="fs">
        <li><a href="#frame_General">General</a></li>
        <?
        foreach($userincludes as $includetitle=>$includefile){
				echo "<li><a href=\"#frame_$includetitle\">$includetitle</a></li>";
			}
		?>
    </ul>
	<div class="border bd-default p-2" style="margin-top: 62px">
        <div class="frame" id="frame_General">
			<div class="grid">
				<div class="row">
					<div class="cell-md-6">
						<div class="grid">
						    <div class="row">
						        <div class="cell"><?=$primerap?></div>        
						        <div class="cell"><?=$segundoap?></div>
					        </div>
					        <div class="row">
						        <div class="cell "><?=$nombres?></div>
						    </div>
						      <div class="row">
						        <div class="cell"><?=$username?></div> 
					        </div>
					        <div class="row">
						        <div class="cell"><?=$telefono?></div>
						        <div class="cell"><?=$cel?></div> 
					        </div>
					        <div class="row">
						        <div class="cell"><?=$sexo?></div>
						        <div class="cell"><?=$nacimiento?></div>
						    </div>
						</div>
					</div>
					<div class="cell-md-6 align-center">
						<img src="/nframework/imagen.php?id=profile&file=<?=(file_exists($_SERVER['DOCUMENT_ROOT'].'/profiles/'.$data['_id'].'.png')?$data['_id']:'guest')?>.png">
						<a href="picture.php" class="button full-size">Cambiar imagen</a>
					</div>
				</div>
			</div>
		</div>
		<?
		foreach($userincludes as $includetitle=>$includefile){
			
			echo '<div class="frame" id="frame_'.$includetitle.'">';
					include $includefile;
			echo '</div>';
		}
		?>
	</div>

<div class="grid">
	<div class="row">
        <div class="cell p-5 text-right">
        	<a href="<?=$backlink?>" class="button primary full-size">Cerrar</a>
        	<input type="submit" name="op" class="button success full-size" value="Guardar">
        </div>
     </div>
</div>
</form>
</div>
<script>
    $('.ms-choice').width(300);
    $('.ms-drop').width('');

function tab_change(tab){
	google.maps.event.trigger(map, 'resize');
	map.setCenter(myLatlng);
}
</script>
<?=$javas?>