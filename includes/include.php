<?php
require 'Browser.php';

$browser = new Browser();
if ($browser->getPlatform() == Browser::PLATFORM_ANDROID || $browser->getBrowser() == Browser::BROWSER_POCKET_IE || $browser->getPlatform() == Browser::PLATFORM_IPHONE || $browser->getPlatform() == Browser::PLATFORM_IPAD
) {
    $nframework['mobil'] = true;
}
$nframework['tablet'] = $browser->isTablet();
require_once 'config.php';
require 'vendor/autoload.php';



$m = new MongoDB\Client($nframework['mongo_connection_string']);


class_alias('\MongoDB\BSON\ObjectID','\MongoId',true);



error_reporting(E_ALL & ~E_NOTICE & ~ E_WARNING);
if (!defined('E_FATAL')) {
	require_once 'errorhandling.php';
}


if($config['forcehttps']=='on' && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off")){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}


$hoy=getdate();
/*
$bannedIP='';//'/(201\.130\.*)|(200\.95\.170\.204)|(^189\.218\.*\.*)/';

$ipAddress = $_SERVER['REMOTE_ADDR'];
if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
    $ipAddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
}


if(preg_match($bannedIP,$ipAddress)) {
	header("location: https://google.com/");
	exit();
}
*/


/*
if($ipAddress!=''){
$m->statistics->uri->insertOne([
	'host'=> $_SERVER['HTTP_HOST'],
	'uri'=> $_SERVER['REQUEST_URI'],
	'browser'=>$_SERVER['HTTP_USER_AGENT'],
	'ip'=>$ipAddress
	]);
}
$m->nlared->statsip->updateOne([
	'ip'=> $ipAddress,
	],[
	'$inc'=>['counter'=>1],
	'$set'=>[
		'ip'=> $ipAddress,
		]
	],['upsert'=>true]);
*/


if($config['cookie_domain']==''){
	$config['cookie_domain']=$_SERVER['HTTP_HOST'];
}
use Altmetric\MongoSessionHandler;
$sessions = $m->{$config['sitedb']}->sessions;
$handler = new MongoSessionHandler($sessions);
session_set_save_handler($handler);
session_name(str_replace('.','_',$config['cookie_domain']));
session_set_cookie_params(0, '/', $config['cookie_domain'],$config['forcehttps'],false);
session_start();
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');


function nframework_autoload($class_name) {
    $ipaths = get_include_path();
    $iarray = explode(PATH_SEPARATOR, $ipaths);
    foreach ($iarray as $ipath) {
        if (file_exists($ipath . '/class.' . $class_name . '.php')) {
            require_once($ipath.'/class.' . $class_name . '.php');
            return true;
        }
    }
}
spl_autoload_register('nframework_autoload');
if (isset($_SESSION['user'])) {
    $user = new User(array('username' => $_SESSION['user']));
    if ($user->username == ''|| $user->disabled==true) {
        unset($_SESSION['user']);
        if($user->disabled==true){
        	header('location: /account/disabled.php');
        }else{
        	header('location: /'); // expulsar
        }
    }
} else {
    if (isset($requiresession)){
        header('Location: /');
    }else{
        $user = new User(array('username' => 'guest'));        
    }
}
if ($user->display_errors == 'on') {
    ini_set('display_errors', 'On');
}
$nframework['locales']=explode(',',explode(';',$_SERVER['HTTP_ACCEPT_LANGUAGE'])[0])[0];


$languages=[
	'es-419'=>'es-MX',
	'es-MX'=>'es-MX'
];

$slanguages=explode(';',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
foreach($slanguages as $language){
	$sublanguages=explode(',',$language);
	foreach($sublanguages as $sublanguage){
	
		if(array_key_exists($sublanguage,$languages)){
		
			$locale=$languages[$sublanguage];
		
			break;
		}
	}
	if($locale!='') break;
}
if($locale==''){
	$locale='en-US';
}

$nframework['isAjax']=(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
		&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');



class javasclass {
    public $js=['general'=>'','resize'=>'','ready'=>''];
    public $flushed;

    function __construct() {
        $this->js = ['general'=>'','resize'=>'','ready'=>''];
        $this->docend=[];
    }

    function addjs($jss, $seccion = 'general') {
        $this->js[$seccion].=$jss;
    }

    function __toString() {
        if (!$this->flushed) {
            $this->flushed = true;
            return implode("\r\n",array_reverse($this->docend)).'
<script>
var nbacklink="/";

'. $this->js['general'] . '
    
function nfWindowResize() {
'.$this->js['resize'].'
};
var nfWindowResizeTimer;
$(window).resize(function() {
    clearTimeout(nfWindowResizeTimer);
    nfWindowResizeTimer = setTimeout(nfWindowResize, 100);
});

$(window).scroll(function(){
'.$this->js['scroll'].'
});


function speak(text,callback){
  	if (\'speechSynthesis\' in window) {
	  	var u = new SpeechSynthesisUtterance();
	    u.text = text;
	    u.lang = \'es-MX\';
	    u.onend = function () {
	        if (callback) {
	            callback();
	        }
	    };
	    u.onerror = function (e) {
	        if (callback) {
	            callback(e);
	        }
	    };
	    speechSynthesis.speak(u);
  	} else {
    	console.log("Oops! Your browser does not support HTML SpeechSynthesis.")
  	}
}



$(document).ready(function() {
    window.addEventListener("keyup", function(e){
    	if(e.keyCode == 27)
    	window.location.href=nbacklink;
    }, false);
    $("input[data-role=\'spiner\']").spinner();
    $("input[data-custom-buttons=\'customCalendarButton\']").datetimepicker({
		format:\'Y-m-d H:i\',mask:false,lang:\'es\'
	});
    $("select[data-role=\'combobox\']").combobox();
    $("select[multiple=\'multiple\']").multipleSelect({
        selectAll: false,
         //multiple: true,
         //multipleWidth: 100,
            width: \'100%\'
    });
    $(".ms-parent").addClass("full-size");
    $(".ms-choice").addClass("full-size");
    $(".ms-drop").addClass("full-size");

    $("div[data-role-aux=\'file-progress-bar\']").hide();
    
    $("input[data-sequential-uploads=\'true\']").each(function( index ) {
		var mid=$(this).attr("id");
	    $.ajax({
			url: \'/nframework/uploadfile.php\',
			method:"POST",
			data: "mid="+mid, 
			dataType: \'json\',
			success: function(data) {
				nfFileMakeList(mid, data);
			}
		});
    });
    $("input[uppercase=\'true\']").each(function(index){
      this.addEventListener("keypress", forceKeyPressUppercase, false);
    });
    $("input[data-sequential-uploads=\'true\']").fileupload({
		url: \'/nframework/uploadfile.php\',
		    sequentialUploads: true,
		dataType: \'json\',
		progressall: function (e, data) {
			var mid=$(this).attr("id");
	        var progress = parseInt(data.loaded / data.total * 100, 10);		
	        var pg=$("#"+mid+"_progressbar");
	        if (progress==100){
	        	pg.hide();
			}else{
	        	pg.show();
	        	pg.attr("data-value",progress);
	        	//console.log(progress);
	        }        
	    },
	    done:function (e, data) {
	    	var mid=$(this).attr("id");
	    	//console.log(data);
	    	nfFileMakeList(mid,data.result);
	    },
	    fail: function(e, data) {
	    	var o=$(this).attr(\'id\');
	  		alert(\'Fail!\'+o);
		}
	});
	
	$(\'.nfinfoicon\').click(function() {
	 var content=$(this).attr(\'content\');
	  Metro.infobox.create(content);
	});
	
	$(".ajaxform").submit(function(e) {
	    var form=$(this);
	    var url = form.attr( "action" );; // the script where you handle the form input.
	    var f=form.attr("data-on-success");
	    if (f === undefined || f === null) {
	    	f="nAjaxFormDone"
	    }
	    $.ajax({
			type: "post",
			url: url,
			data: form.serialize(), // serializes the forms elements.
			success: function(data){
			   	Metro.utils.callback(f,[data]);
			},
			error:function(jqXHR, textStatus) {
				  alert( "Request failed: " + textStatus );
			}
		});
		
	    e.preventDefault(); // avoid to execute the actual submit of the form.
	});
   
   jQuery(\'.datetimepicker2date\').datetimepicker({
  timepicker:false,
  format:\'Y-m-d\'
});
   
    $(".ui-spinner").addClass("w-100");
    '. $this->js['ready'] . '
}); 
</script></body></html>';
        }else{
            return '';
        }
    }
}

$javas = new javasclass();
function speak($text){
	global $javas;
	$javas->addjs("
	speak('$text');
",'ready');
}
//TODO> Other options
function notify($title='nlared.com',$text='',$options=[]){
	global $javas;
	$javas->addjs("
	toast('$text');
",'ready');
}
$datasession = new DataSession();
require 'class.Base.php';