<?
require_once 'include.php';
$upload = $_SESSION['uploads4'][$_GET['mid']];
//print_r($upload);
if (is_array($upload)){
$directorio=realpath($upload['dir']);

	if($_GET['preview']==''){
		header('Content-Type: application/octet-stream');
		header("Content-Transfer-Encoding: Binary"); 
		header('Content-disposition: attachment; filename="' .$_GET['file'].'"' );
	}else{
		 $mimetype = mime_content_type($directorio.'/'.$_GET['file']);
		 if(empty($mimetype)){
			 header('Content-Type: application/octet-stream');
			 header("Content-Transfer-Encoding: Binary"); 
			 header('Content-disposition: attachment; filename="' .$_GET['file'].'"' );
		 }else{
			 header('Content-Type: '.$mimetype);
		 }
	}
	readfile($directorio.'/'.$_GET['file']);
}