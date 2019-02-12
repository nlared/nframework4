<?php
function json_decode2($json){
	$comment = false;
	$out = '$x=';
	for ($i=0; $i<strlen($json); $i++){
		if (!$comment){
			if (($json[$i] == '{') || ($json[$i] == '['))
				$out .= ' array(';
			else if (($json[$i] == '}') || ($json[$i] == ']'))
				$out .= ')';
			else if ($json[$i] == ':')
				$out .= '=>';
			else
				$out .= $json[$i];
		}else
			$out .= $json[$i];
		if ($json[$i] == '"' && $json[($i-1)]!="\\")$comment = !$comment;
	}
	eval($out . ';');
	return $x;
}
$info=base64_decode($_POST['info']);
$m = new MongoClient();	
$db = $m->nlared;
$ip=$_SERVER['REMOTE_ADDR'];
$info=json_decode2(stripslashes(stripslashes($info)),true);
$info['ip']=$ip;
$info['lasttime']=date("Y-m-d H:i:s");

//if loc agregar las wifis y las ip

//else if wifis buscar wifis y agregar ip

//else buscar ip



if (count($info['Geolocation']['wifiAccessPoints'])>0){
	foreach($info['Geolocation']['wifiAccessPoints'] as $wifi) $wifis[]=str_replace('-','',$wifi['macAddress']);
	$request=$db->wifi->find(array('_id'=>array('$in' => $wifis)))->sort(array('str'=>1))->limit(1);
	$request->next();
	$locinfo=$request->current();
//insertar ip
}else{
	$request=$db->ip->find(array('_id'=>$ip));
	$request->next();
	$ipdoc=$request->current();
	$loc=$ipdoc['loc'];
	
}


if (isset($info['Win32_ComputerSystemProduct'][0])){
	$doc=$info['Win32_ComputerSystemProduct'][0];
	$doc['ip']=$ip;
	$doc['lasttime']=date("Y-m-d H:i:s");
	$doc['Geolocation']=$info['Geolocation'];
	
	
	$doc['Geolocation']['finded']=$locinfo;
	$doc['loc']=$locinfo['loc']['x'].','.$locinfo['loc']['y'];
	
	
	if($doc['IdentifyingNumber']=='None'){
		$doc['_id']=$doc['UUID'];
	}else{
		$doc['_id']=$doc['IdentifyingNumber'];
	}
	$doc['mb']=$info['Win32_BaseBoard'][0];
	$doc['bios']=$info['Win32_BIOS'][0];
	$doc['cpu']=$info['Win32_Processor'][0];
	$doc['mm']=$info['Win32_PhysicalMemory'];
	$doc['hd']=$info['Win32_DiskDrive'];
	
	foreach($info['Win32_NetworkAdapter'] as $net){
		if ($net['PhysicalAdapter']=='True') $doc['nc'][]=$net;
	}
	//$doc['net']=$info['Win32_NetworkAdapterConfiguration'];
	$doc['bt']=$info['Win32_PortableBattery'];
	
	$db->findme->save($doc);
	echo "OK";
}
/*
	if ($info['app']<"1.0.0.1")
	{
		echo "UPDATE;http://sistemas.nlared.com/nupdate/nupdate.exe;".sha1_file("nupdate/nupdate.exe");
	}else{
		echo "OK";
	}
}else{
	echo "error en transmision";
	$info=base64_decode($_POST['info']);
	$info=stripslashes(stripslashes($info));
	$info=substr($info,0,-1)."''}";
	$info=json_decode2($info,true);	
//	print_r($info);

}

*/
?>