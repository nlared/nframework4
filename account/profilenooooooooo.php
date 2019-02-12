<?php
require 'include.php';
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
$data=$_POST['data'];
($dataset->password==''?$password=new inputText(['name'=>'data[password2]','type'=>'password','placeholder'=>'Contraseña Nueva','addclass'=>'full-size','required'=>true])
	:$password=new inputText(['name'=>'data[password2]','type'=>'password','placeholder'=>'Ya Tiene Asignada Contraseña','addclass'=>'full-size','disabled'=>true]));
$titulo=new inputtext(['dataset'=>$dataset,'field'=>'titulo','placeholder'=>'Titulo','required'=>true]);
$nombre=new inputtext(['dataset'=>$dataset,'field'=>'nombres','placeholder'=>'Nombre(s)','required'=>true]);
$primerapellido=new inputtext(['dataset'=>$dataset,'field'=>'primerapellido','placeholder'=>'Primer Apellido','addclass'=>'full-size','required'=>true]);
$segundoapellido=new inputtext(['dataset'=>$dataset,'field'=>'segundoapellido','placeholder'=>'Segundo Apellido','addclass'=>'full-size']);
$domicilio=new textArea(['dataset'=>$dataset,'field'=>'domicilio','placeholder'=>'Domicilio Personal','required'=>true]);
$numtel=new inputtext(['dataset'=>$dataset,'field'=>'numtel','placeholder'=>'Número Telefono','addclass'=>'full-size']);
$numcel=new inputtext(['dataset'=>$dataset,'field'=>'numcel','placeholder'=>'Númro de Celular','addclass'=>'full-size','required'=>true]);
$fhcumpleaños=new inputdatetime(['dataset'=>$dataset,'field'=>'fhcumpleaños','placeholder'=>'Fecha de Cumpleaños','required'=>true]);
$curp=new inputtext(['dataset'=>$dataset,'field'=>'curp','placeholder'=>'CURP','required'=>true]);
$rfc=new inputtext(['dataset'=>$dataset,'field'=>'rfc','placeholder'=>'RFC','required'=>true]);
$fhingreso=new inputdatetime(['dataset'=>$dataset,'field'=>'fhingreso','placeholder'=>'Fecha en la que Ingreso','required'=>true]);
$cargo=new textArea(['dataset'=>$dataset,'field'=>'cargo','placeholder'=>'Cargo','required'=>true]);
$correooficial=new inputtext(['dataset'=>$dataset,'field'=>'username','placeholder'=>'example@tjacoahuila.org.mx','required'=>true]);
$correopersonal=new inputtext(['dataset'=>$dataset,'field'=>'correopersonal','placeholder'=>'example@example.com','required'=>true]);
$ultimogradoestudio=new inputtext(['dataset'=>$dataset,'field'=>'ultimogradoestudio','placeholder'=>'Ultimo Grado de Estudios','required'=>true]);
$centrotrabajo=new inputtext(['dataset'=>$dataset,'field'=>'centrotrabajo','placeholder'=>'Example: 0$000000N','required'=>true]);
$descripciontrabajo=new textarea(['dataset'=>$dataset,'field'=>'descripciontrabajo','placeholder'=>'Descripción del Trabajo','required'=>true]);
$categoria=new inputtext(['dataset'=>$dataset,'field'=>'categoria','placeholder'=>'Categoria','required'=>true]);
$plaza=new inputtext(['dataset'=>$dataset,'field'=>'plaza','placeholder'=>'Plaza','required'=>true]);
$sexo=new select([
	'dataset'=>$dataset,
	'field'=>'sexo',
	'options'=>[
		''=>'Select....',
		'Femenino'=>'Femenino',
		'Masculino'=>'Masculino',],
	'requires'=>true,
]);
$estado=new select([
	'dataset'=>$dataset,
	'field'=>'estado',
	'options'=>[
		''=>'Select....',
		'Activo'=>'Activo',
		'Baja'=>'Baja',],
	'requires'=>true,
]);
$permisos=new inputCheckboxs([
	'dataset'=>$dataset,
	'field'=>'permisos',
	'options'=>[
		'admin'=>'Administrador',
		'recursoshumanos'=>'Recursos Humanos',
		'oficialia'=>'Oficialia'],
	'required'=>true,
]);
$folio= 0;
if($nframework['isAjax']){
	$dataset->save();
	if($data['password2']!='' && $dataset->password=='') $dataset->password=hash('sha512',$data['password2']);
	$dataset->fhcreacion= date('Y-m-d H-i-s');
	if($dataset->folio==''){
		foreach($m->{$config['sitedb']}->recepciones->find() as $doc){
			$folio++;
		}
		$dataset->folio=$folio;
	}
	$result=['error'=>''];
}else{
	require 'common.php';
	$metro=new Metro();
	$metro->backlink='./';
	echo $metro;
	?>
		<script>
		function Confirmar(){
			 confirm("Favor de Confirmar los Datos Antes de Cerrar") 
			 return false;
		}
		$('.topbar').last().addClass( "bg-darkCrimson" );
		
	</script>
	<div class="container-fluid">
		<br>
		<div class="border p-3 bd-gray">
			<div align="center">
				<h3><img src="../img/logo-color2.jpg" style="height:60px;"><strong>Capturar Datos</strong></h3>
			</div>
			<hr>
	    	<form class='ajaxform' data-on-success="nAjaxFormDone" data-on-before-submit="Confirmar" id="nose">
		    	<div class="row">
				        <div class="cell-md-8">
				            <label>Correo Oficial</label><br>
				            	<?=$correooficial?>
				            <small class="text-muted">Campo Obligatorio.</small>
				        </div>
				        <div class="cell-md-4">
				            <label>Contraseña</label><br>
				            	<?=$password?>
				            <small class="text-muted">Campo Obligatorio.</small>
				        </div>
				    </div>
		    	<div class="row">
		    		<div class="cell-md-2">
			            <label>Titulo</label><br>
			            <?=$titulo?>
			            <small class="text-muted">Campo Obligatorio.</small>
			        </div>
			        <div class="cell-md-4">
			            <label>Nombre(s)</label><br>
			            <?=$nombre?>
			            <small class="text-muted">Campo Obligatorio.</small>
			        </div>
			        <div class="cell-md-3">
			            <label>Primer Apellido</label><br>
			            <?=$primerapellido?>
			            <small class="text-muted">Campo Obligatorio.</small>
			        </div>
			        <div class="cell-md-3">
			        	<label>Segundo Apellido</label><br>
			            <?=$segundoapellido?>
			            <small class="text-muted">Campo  No Obligatorio.</small>
			        </div>
			    </div>
			    <div class="row">
			    	<div class="cell-md-2">
			            <label>Sexo</label><br>
			            <?=$sexo?>
			            <small class="text-muted">Campo Obligatorio.</small>
			        </div>
			        <div class="cell-md-5">
			            <label>Domicilio</label><br>
			            <?=$domicilio?>
			            <small class="text-muted">Campo Obligatorio.</small>
			        </div>
			        <div class="cell-md-2">
			            <label>Número de Telefono Fijo</label><br>
			            <?=$numtel?>
			            <small class="text-muted">Campo NO Obligatorio.</small>
			        </div>
			        <div class="cell-md-3">
			        	<label>Número de Telefono Celular</label><br>
			            <?=$numcel?>
			            <small class="text-muted">Campo Obligatorio.</small>
			        </div>
			    </div>
			    <div class="row">
			        <div class="cell-md-4">
			            <label>Fecha de Cumpleaños</label><br>
			            <?=$fhcumpleaños?>
			            <small class="text-muted">Campo Obligatorio.</small>
			        </div>
			        <div class="cell-md-4">
			            <label>CURP</label><br>
			            <?=$curp?>
			            <small class="text-muted">Campo Obligatorio.</small>
			        </div>
			        <div class="cell-md-4">
			        	<label>RFC</label><br>
			            <?=$rfc?>
			            <small class="text-muted">Campo Obligatorio.</small>
			        </div>
			    </div>
			    <div class="row">
			        <div class="cell-md-5">
			            <label>Fecha de Ingreso</label><br>
			            <?=$fhingreso?>
			            <small class="text-muted">Campo Obligatorio.</small>
			        </div>
			        <div class="cell-md-7">
			            <label>Cargo</label><br>
			            <?=$cargo?>
			            <small class="text-muted">Campo Obligatorio.</small>
			        </div>
			    </div>
			    <div class="row">
			        <div class="cell-md-4">
			            <label>Correo Personal</label><br>
			            <?=$correopersonal?>
			            <small class="text-muted">Campo Obligatorio.</small>
			        </div>
			        <div class="cell-md-4">
			        	<label>Ultimo Grado de Estudio</label><br>
			            <?=$ultimogradoestudio?>
			            <small class="text-muted">Campo Obligatorio.</small>
			        </div>
			        <div class="cell-md-4">
			            <label>Estado</label><br>
			            <?=$estado?>
			            <small class="text-muted">Campo Obligatorio.</small>
			        </div>
			    </div>
			    <div class="row">
			    	<div class="cell-md-3">
			            <label>Centro de Trabajo</label><br>
			            <?=$centrotrabajo?>
			            <br>
			            <small class="text-muted">Campo Obligatorio.</small>
			        </div>
			        <div class="cell-md-3">
			            <label>Descripción del Trabajo</label><br>
			            <?=$descripciontrabajo?>
			            <br>
			            <small class="text-muted">Campo Obligatorio.</small>
			        </div>
			        <div class="cell-md-3">
			            <label>Categoria</label><br>
			            <?=$categoria?>
			            <br>
			            <small class="text-muted">Campo Obligatorio.</small>
			        </div>
			        <div class="cell-md-3">
			            <label>Plaza</label><br>
			            <?=$plaza?>
			            <br>
			            <small class="text-muted">Campo Obligatorio.</small>
			        </div>
			    </div>
			    <div class="row">
			    	
			    	<div class="cell" align="center">
			            <label>Permisos</label><br>
			            <?=$permisos?>
			            <br>
			            <small class="text-muted">Campo Obligatorio.</small>
			        </div>
			    </div>
		        <div class="row">
                    <div class="cell " align="right">
                    	<br>
                    	<a href="./" class="button full-size ">Cerrar</a>
                    	<button id="Guardar" class="button success">Guardar</button>
                    </div>
                </div>
		    </form>
		</div>
	</div>


	
<?}?>
