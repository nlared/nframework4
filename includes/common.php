<?php
require_once 'include.php';
header('Content-Type:text/html; charset=utf-8');
$metas = '<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es_MX" xml:lang="es_MX">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<META NAME="google-site-verification" content="' . $config['google-site-verification'] . '" />
<META NAME="Title" CONTENT="' . $config['title'] . '" />
<META NAME="Author" content="' . $config['author'] . '" />
<META NAME="Subject" CONTENT="' . $config['subject'] . '" />
<META NAME="Description" CONTENT="' . $config['description'] . '" />
<META NAME="Keywords" lang="en" CONTENT="' . $config['keywords'] . '" />
<meta name="theme-color" content="#005696">
<meta name="metro4:init" content="true">
<meta name="metro4:locale" content="'.$locale.'">
<meta name="metro4:week_start" content="1">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, shrink-to-fit=no" />
<title>' . $config['title'] . '</title>
';
echo $metas;
//<meta name="viewport" content="target-densitydpi=device-dpi, width=device-width, initial-scale=1.0, maximum-scale=1">

if (//ie9
        ( $browser->getBrowser() == Browser::BROWSER_IE && $browser->getVersion() < 6)||
        ( $browser->getBrowser() == Browser::BROWSER_SAFARI && $browser->getVersion() < 5.1 ) ||
        ( $browser->getBrowser() == Browser::BROWSER_ANDROID && $browser->getVersion() < 6 )
) {
    echo 'Actualize su navegador' . $_SERVER['HTTP_USER_AGENT'];
    exit();
}


if($browser->getBrowser() != Browser::BROWSER_SAFARI){
 	$mixjs[0]=[
		//'pace-progress/pace.js',
		'jquery-ui/jquery-ui.js',
	];	
	$mixcss[0]=[
		//'pace-progress/themes/blue/pace-theme-loading-bar.css',
		'jquery-ui/jquery-ui.css',
	];
}else{
	$mixjs[0]=[
		'jquery-ui/jquery-ui.js',
	];
	$mixcss[0]=[
		'jquery-ui/jquery-ui.css',
	];
}



$mixcss[1]=[
		'multiple-select/multiple-select.css',
		'jquery-datetimepicker/build/jquery.datetimepicker.min.css',
		'nframework/nframework4.css',
];

if($browser->getBrowser()==Browser::BROWSER_IE &&$browser->getVersion()>10){
	$mixcss[0][]='nframework/iefix.css';
}

if($config['scheme']!=''){
	$nframework['scheme']=$config['scheme'];
}
if($user->nfscheme!=''){
	$nframework['scheme']=$user->nfscheme;
}

if($nframwork['scheme']==''){
	$nframework['scheme']='schemes/sky-net.min.css';
}

$mixjs[1]=[
		'jquery-datetimepicker/build/jquery.datetimepicker.full.min.js',
		'multiple-select/multiple-select.js',
		'jQuery-File-Upload-9.20.0/js/jquery.fileupload.js',
		'datatables.net/js/jquery.dataTables.js',
		'nframework/nframework4.js',
];
if($locale!='en-US'){
	$mixjs[1][]='nframework/metro-'.$locale.'.js';
}

$usejavas=true;  
//<link rel="stylesheet" hkef="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
//<script srac="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

if($nframework['online']){
?>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-te/1.4.0/jquery-te.min.css"/>
<link rel="stylesheet" href="//cdn.nlared.com/mixcss.php?f=<?=implode(';',$mixcss[0])?>"/>
<link rel="stylesheet" href="<?=$config['metropath']?>/css/metro-all.min.css"/>
<link rel="stylesheet" href="https://metroui.org.ua/metro/css/third-party/datatables.css"/>
<link rel="stylesheet" href="//cdn.nlared.com/mixcss.php?f=<?=implode(';',$mixcss[1])?>"/>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css"/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="//cdn.nlared.com/mixjs.php?f=<?=implode(';',$mixjs[0])?>"></script>
<script src="<?=$config['metropath']?>/js/metro.min.js"></script>
<?=($config['scheme']!=''?'<link rel="stylesheet" href="'.$config['scheme'].($config['schemetest']?'?id='.date('Ymdhi'):'').'"/>':'')?>
<script src="//cdn.nlared.com/mixjs.php?f=<?=implode(';',$mixjs[1])?>"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-te/1.4.0/jquery-te.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
<?}else{?>

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-te/1.4.0/jquery-te.min.css"/>
<link rel="stylesheet" href="//cdn.nlared.com/mixcss.php?f=<?=implode(';',$mixcss[0])?>"/>
<link rel="stylesheet" href="<?=$config['metropath']?>/css/metro-all.min.css"/>
<link rel="stylesheet" href="https://metroui.org.ua/metro/css/third-party/datatables.css"/>
<link rel="stylesheet" href="//cdn.nlared.com/mixcss.php?f=<?=implode(';',$mixcss[1])?>"/>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css"/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="//cdn.nlared.com/mixjs.php?f=<?=implode(';',$mixjs[0])?>"></script>
<script src="<?=$config['metropath']?>/js/metro.min.js"></script>
<?=($config['scheme']!=''?'<link rel="stylesheet" href="'.$config['scheme'].($config['schemetest']?'?id='.date('Ymdhi'):'').'"/>':'')?>
<script src="//cdn.nlared.com/mixjs.php?f=<?=implode(';',$mixjs[1])?>"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-te/1.4.0/jquery-te.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
<?}?>
</head>
<form method="POST" id="nframeworksendreportf" target="nframeworksendreportw" action="/nframework/report.php">
</form>	
<div class="dialog" id="dialogreport" data-role="dialog">
    <div class="dialog-title">Desea reportar un problema?</div>
    <div class="dialog-content">
        <textarea name="report[description]" data-role="textarea" placeholder="Describa el problema"></textarea>
    </div>
    <div class="dialog-actions">
        <button class="button js-dialog-close">Disagree</button>
        <button class="button primary js-dialog-close">Agree</button>
    </div>
</div>