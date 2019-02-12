<?php
error_reporting(0);
set_time_limit(5);
require 'vendor/autoload.php';
require 'config.php';
$m = new MongoDB\Client($nframework['mongo_connection_string']);
header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
session_set_cookie_params(0, '/', 'nlared.com');
session_start();

function changepaths($archivo,$insertpath){
    $datos=file_get_contents($archivo);
    $posicion=strpos($datos,'url(',$posicion);
    while($posicion>1){
        $posicion+=4;
        if(substr($datos,$posicion,1)==='\''){
            $posicion++;
            $comillas=1;
        }else{
            $comillas=0;
        }
        $fin=strpos($datos,')',$posicion);    
        $archivo=substr($datos,$posicion,$fin-$posicion-$comillas);
        if(strpos($archivo,':')===false){
            $datos=substr($datos,0,$posicion).$insertpath.$archivo.substr($datos,$fin-1);       
        }            
        $posicion=strpos($datos,'url(',$posicion);
    }
    return $datos;
}
if ($_GET['lim'] == 1)
    $m->nlared->cache->remove(['type'=>'css']);
$sourcesite = '//nlared.com/';
require '/var/www/includes/csss.php';

$vart=$m->nlared->cache->findOne(['type'=>'css']);
//$vart = wincache_ucache_get('cachecsstime');
foreach ($files as $lib) {
    if (file_exists($nframework['basedir'] .'/'. $lib)) {
        $ftime = filemtime($nframework['basedir'] .'/'. $lib);
        if ($vart['cachecsstime'] < $ftime) {
            $vart['cachecsstime'] = $ftime;
            $update = true;
            $toetag.=$lib.$ftime;
        }
    }
}
//$update=true;

if ($update) {
    require_once 'cssmin.php';
    $filters = array(/* ... */);
    $plugins = array(/* ... */);
    foreach ($files as $lib) {
        $filepath=str_replace('/var/www/','//',$nframework['basedir'] .'/'. $lib);
        $filepath=substr($filepath,1, strrpos($filepath,'/'));         
        if (file_exists($nframework['basedir'] .'/'. $lib)) {
            $pos = strpos($lib, '.min.css');
            if ($pos === false) {                
                $content.=CssMin::minify(changepaths($nframework['basedir'].'/'.$lib,$filepath), $filters, $plugins) . "\n";
            } else {
                $content.=file_get_contents($nframework['basedir'] .'/'. $lib) . "\n";
            }
        }
    }
   $vart['cachecssdata']= $content;
   $vart['type']='css';
   
    $m->nlared->cache->replaceOne(['type'=>'css'],$vart,['upsert'=>true]);
} else {
    $content = $vart['cachecssdata'];
}
$etag = '"'.md5('css' . $vart['cachecsstime'].join('',$files)).'"';
//$seconds = 300;
if (trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag and ! $update) {
    header('HTTP/1.1 304 Not Modified', true, 304);
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", $vart['cachecsstime']) . " GMT");
    header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
    header("Content-type: text/css");
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
    header("Content-type: text/css");
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false||strpos($_SERVER['HTTP_USER_AGENT'], 'Edge') !== false) {
    	header("Cache-Control: must-revalidate, private");
    	header("Pragma: cache");
	}else{
	    header("Cache-Control:max-age=$seconds");
	}
   // header("Date: " . gmdate("D, d M Y H:i:s", $vart['cachecsstime']) . " GMT");   
    header('Expires: ' . gmdate('D, j M Y H:i:s T', time() + $seconds));
    header('ETag: ' . $etag);
    echo $content;
}