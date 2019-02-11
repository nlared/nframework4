<?
require_once 'common.php';

$webcam=new Webcam($_SERVER['DOCUMENT_ROOT'] . '/profiles/' . $user->_id . '.png');
$metro= new Metro();
$metro->backlink='myprofile.php';
echo $metro;

if ($_POST['op']=='Cargar'){
	$fname=$_FILES['fotou']['name'];
	$lastpoint=strrpos($fname,'.');
	$extencion=substr($fname,$lastpoint+1);
	if($extencion=='png'){
		move_uploaded_file($_FILES['fotou']['tmp_name'],$_SERVER['DOCUMENT_ROOT'] . '/profiles/' . $user->_id . '.png');      
	}else{
		$filename=$_FILES['fotou']['tmp_name'];
		switch ($extencion) {
		    case 'jpg':
		    case 'jpeg':
		       $image = imagecreatefromjpeg($filename);
		    break;
		    case 'gif':
		       $image = imagecreatefromgif($filename);
		    break;
		    case 'png':
		       $image = imagecreatefrompng($filename);
		    break;
		}
		imagepng($image,$_SERVER['DOCUMENT_ROOT'] . '/profiles/' . $user->_id . '.png');
		clearstatcache(true, $_SERVER['DOCUMENT_ROOT'] . '/profiles/' . $user->_id . '.png');
	}
}
echo '<div class="container">'.$webcam.'
<form enctype="multipart/form-data" method="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="30000000" />
<input name="fotou" type="file">
<input name="op" type="submit" value="Cargar">
</form></div>';
?>