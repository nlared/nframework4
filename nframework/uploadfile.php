<?
set_time_limit(0);
function utf8_to_win($string){
	for ($c=0;$c<strlen($string);$c++){
		$i=ord($string[$c]);
		if ($i <= 127) @$out .= $string[$c];
		if (@$byte2){
			$new_c2=($c1&3)*64+($i&63);
			$new_c1=($c1>>2)&5;
			$new_i=$new_c1*256+$new_c2;
			if ($new_i==1025){
				$out_i=168;
			} else {
				if ($new_i==1105){
					$out_i=184;
				} else {
					$out_i=$new_i-848;
				}
			}
			@$out .= chr($out_i);
			$byte2 = false;
		}
		if (($i>>5)==6) {
			$c1 = $i;
			$byte2 = true;
		}
	}
	return $out;
}
require_once 'include.php';
header('Content-Type: application/json');

function valid_filename(){
	$valido=true;
	$special_chars = ["?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", "%", "+", chr(0)];
   	foreach($specialchars as $char){
   		if(strpos($filename,$char)!==false){
   			$valido=false;
   		}
   	}
   	return $valido;
}
if (isset($_POST['mid']) && isset($_SESSION['uploads4'][$_POST['mid']])) {
    $ret = [];
    $upload = $_SESSION['uploads4'][$_POST['mid']];
    $directorio=$upload['dir'];
    //if($upload['disabled']!=false){
    
    if ($_POST['delete']){
    	if($upload['delete']) {
	        $filename=rawurldecode($_POST['file']);
			$coding=mb_detect_encoding($filename);
			if($coding!='UTF-8'){
				$filename=iconv($coding,'UTF-8', $filename);
			}
			$valido=valid_filename($filename);
	        if($valido){
	        	$filename=$directorio.$filename;
	        	unlink($filename);
	        }
    	}
    } else {
    	$ufile=$_FILES[$upload['formname']];
    	$filename=rawurldecode($ufile['name']);
		$coding=mb_detect_encoding($filename);
		if($coding!='UTF-8'){
			$filename=iconv($coding,'UTF-8', $filename);
		}
		$valido=valid_filename($filename);
        if($valido){
	        if (!file_exists($directorio)) mkdir($directorio, 0777, true);
	        $filename=$directorio.$filename;
	        move_uploaded_file($ufile['tmp_name'],$filename);
        }
    }
    
    $afiles = scandir($directorio);
    $o = 0;
    foreach ($afiles as $afile) {
        if ($afile != '.' && $afile != '..'){
            $ret[] = array('id' => $o, 'name' => $afile, 'length' => filesize($directorio . "/$afile"));
        $o++;
        }
    }
    echo json_encode([
    
    	'delete'=>$upload['delete'],
    	'download'=>$upload['download'],
    	'preview'=>($upload['preview']!=false),
    	'files'=>$ret,
    	//'dir'=>$directorio,
//    	'ss'=>session_id(),
  //  	'll'=>$ufile,'os'=>$_SESSION['uploads5'],
    	//'filea'=>$filename
    	]);
}else{
	echo 'session '. session_id(); 
}