<?
require 'include.php';


$file=$_GET['file'];
$conf=$_SESSION['images'][$_GET['id']];
$src=realpath($conf['src'].$file);
$dst=$conf['dst'].$file;

if(isset($conf['height'])){
	
}else{
	$new_width=$conf['width'];
}

$extheaders=[
	'png'=>'Content-Type: image/png'
	];

if(file_exists($src)){
	$ext = pathinfo($src, PATHINFO_EXTENSION);
	if(!file_exists($dst) || (filectime($src)>filectime($dst))){
		if($ext=='png'){
			$im = imagecreatefrompng($src);
			$nim =imagescale($im,$new_width);
			imagepng($nim,$dst);
		}
		imagedestroy($im);
	}
	
	$etag = '"'.md5($dst.'-'.filectime($dst)).'"';
	if (trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag) {
	    header('HTTP/1.1 304 Not Modified', true, 304);
	    header("Last-Modified: " . gmdate("D, d M Y H:i:s", $vart['cachecsstime']) . " GMT");
	    header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
    	header($extheaders[$ext]);
	    if (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false) {
	    	header("Cache-Control: must-revalidate, private");
	    	header("Pragma: cache");
		}else{
		    header("Cache-Control:max-age=$seconds");
		}
	   // header("Dalte: " . gmdate("D, d M Y H:i:s", $vart['cachecsstime']) . " GMT");
	    header('Expires: ' . gmdate('D, j M Y H:i:s T', time() + $seconds));
	    header('ETag: ' . $etag);	
	} else {
	    header("Last-Modified: " . gmdate("D, d M Y H:i:s", $vart['cachecsstime']) . " GMT");
	    header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
    	header($extheaders[$ext]);
	    if (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false||strpos($_SERVER['HTTP_USER_AGENT'], 'Edge') !== false) {
	    	header("Cache-Control: must-revalidate, private");
	    	header("Pragma: cache");
		}else{
		    header("Cache-Control:max-age=$seconds");
		}
	   // header("Date: " . gmdate("D, d M Y H:i:s", $vart['cachecsstime']) . " GMT");   
	    header('Expires: ' . gmdate('D, j M Y H:i:s T', time() + $seconds));
	    header('ETag: ' . $etag);
	    readfile($dst);
	}
}