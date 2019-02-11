<?
$estados['']='Selecciona...';
foreach ($m->pub->C_ENTIDAD->find() as $row){
    $estados[str_pad($row['CD_ENT'], 2, '0', STR_PAD_LEFT)]=$row['NB_ENTIDAD'];
}

if($dataset->estado!=''){
	foreach ($m->geo->mapa->find([
		'geografico'=>'Municipio',
		 'cve_ent' => $dataset->estado,
	]) as $row){
	    $municipios[$row['cve_mun']]=$row['nom_mun'];
	}	
}
asort($municipios);
$lat=($dataset->lat==''?'25.43228030':$dataset->lat);
$lng=($dataset->lng==''?'-101.00447970':$dataset->lng);


$cp   = new inputNumber(['dataset'=>$dataset,'id'=>'cp','caption'=>'Codigo Postal','addclass'=>'full-size', 'required'=>false,'field'=>'cp' ,'placeholder'=>'cp']);
$estado=new inputText(['dataset'=>$dataset,'field'=>'estado','caption'=>'Estado','id'=>'estado','prependicon'=>'earth','addclass'=>'full-size','options'=>$estados]);
$municipio= new inputText(['dataset'=>$dataset,'caption'=>'Municipio','addclass'=>'full-size','id'=>'municipio', 'field'=>'municipio','options'=>$municipios,'onChange'=>"getAjaxSelect('asentamiento','municipio='+this.value);"]);
$asentamiento= new inputText(['dataset'=>$dataset,'caption'=>'Asentamiento','addclass'=>'full-size','id'=>'asentamiento','field'=>'asentamiento']);
$vialidad1= new inputText(['dataset'=>$dataset,'caption'=>'Vialidad','addclass'=>'full-size','id'=>'vialidad','field'=>'vialidad1']);
$latitud=new inputText(['dataset'=>$dataset,'caption'=>'','field'=>'lat','id'=>'data_lat','default'=>'','required'=>false]);
$longitud=new inputText(['dataset'=>$dataset,'caption'=>'','field'=>'lng','id'=>'data_lng','default'=>'','required'=>false]);
$noext   = new inputText(['dataset'=>$dataset,'caption'=>'Noext','addclass'=>'full-size', 'required'=>false,'field'=>'noext' ,'placeholder'=>'# exterior']);
$noint   = new inputText(['dataset'=>$dataset,'caption'=>'NoInt','addclass'=>'full-size','field'=>'noint' ,'placeholder'=>'# interior']);
?>