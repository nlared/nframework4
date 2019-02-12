<?php
error_reporting(0);
require 'config.php';
require 'vendor/autoload.php';
$m = new MongoDB\Client($nframework['mongo_connection_string']);

header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
session_set_cookie_params(0, '/', 'nlared.com');
session_start();
//require_once 'include.php';
$sourcesite = '//nlared.com/';
require '/var/www/includes/javas.php';
if (isset($_GET['lim']))
    $m->nlared->cache->remove(['type'=>'js']);

$vart=$m->nlared->cache->findOne(['type'=>'js']);
foreach ($files as $lib) {
    if (file_exists($nframework['basedir'] .'/'. $lib)) {
        $ftime = filemtime($nframework['basedir'] .'/'. $lib);
        if ($vart['cachejstime'] < $ftime) {
            $vart['cachejstime'] = $ftime;
            $update = true;
        }
    }
}
//$update=true;
if ($update) {
    require_once 'jsmin.php';
    foreach ($files as $lib) {
        if (file_exists($nframework['basedir'] .'/'. $lib)) {
            $pos = strpos($lib, '.min.js');
            if ($pos === false) {
                $content.=JSMin::minify(file_get_contents($nframework['basedir'].'/'. $lib)) . ';';
            } else {
                $content.=file_get_contents($nframework['basedir'].'/'. $lib) . ';';
            }
        }
    }    
    $vart['cachejsdata']= $content;
    $vart['type']='js';
    $m->nlared->cache->replaceOne(['type'=>'js'],$vart,['upsert'=>true]);
} else {
    $content = $vart['cachejsdata'];
}

$etag = '"'.md5('js' . $vart['cachejstime']).join(',',$files).'"';
$seconds = 300000;

if (trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag and ! $update) {
    header('HTTP/1.1 304 Not Modified', true, 304);
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", $vart['cachejstime']) . " GMT");
    header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
    header("Content-type: application/javascript");
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false) {
    	header("Cache-Control: must-revalidate, private");
    	header("Pragma: cache");
	}else{
	    header("Cache-Control:max-age=$seconds");
	}
    header('Expires: ' . gmdate('D, j M Y H:i:s T', time() + $seconds));
    header('ETag:' . $etag);
    
} else {
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", $vart['cachejstime']) . " GMT");
    header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
    header("Content-type: application/javascript");
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false ||strpos($_SERVER['HTTP_USER_AGENT'], 'Edge') !== false) {
    	header("Cache-Control: must-revalidate, private");
    	header("Pragma: cache");
	}else{
	    header("Cache-Control:max-age=$seconds");
	}
    
    header('Expires: ' . gmdate('D, j M Y H:i:s T', time() + $seconds));
    header('ETag:' . $etag);
//	header("Pragma: cache");
    echo $content;
}