<?

class Metro {
    var $js = '';
    var $subtitle;
    var $title;
    var $content;
    var $secondary; //true
    var $backlink;
    var $notify = '';
    var $start = true;
    public $menuAdd;
    public $logoAdd;
    function __construct($content='') {
    	global $javas;
        $doc = str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], strlen($_SERVER['DOCUMENT_ROOT'])));
        $paths = explode('/', $doc);
        $con = count($paths);
        $this->subtitle = ucfirst(substr($paths[$con - 1], 0, -4));
        if ($this->subtitle == 'Index')
            $this->subtitle = '';
        $this->title = ucfirst($paths[$con - 2]);
        if ($this->title == '')
            $this->title = 'Inicio';
        $this->content = $content;
        $this->backlink = '../';
        
        
        $javas->addjs('
    $(".frozen_top").css("top",$(this).scrollTop());
','scroll');
/*
 $(".frozen_top").css("top",Math.max(130,$(this).scrollTop()));
    if($(this).scrollTop() > 135) {
        $(".frozen_top").css("margin-top","-95px");
                    $("#task_flyout").css("top","53px");    
    } else {
        $(".frozen_top").css("margin-top","-5px");
                    $("#task_flyout").css("top","33px");    
    }
*/
    }      
 
    public function __toString() {        
        global $thmecolor,$javas;
        $addtheme = ' ' . $themecolor;
        $result = ($this->start === false ? '<a href="' . $this->backlink . '" class="back-button big page-back"><span class="mif-arrow-left"></span></a>' : '');
        if ($this->notify != '')
            $javas->addjs('$.Notify({shadow: false,position: \'bottom-right\',content: \'' . $this->notify . '\'});');
    		$javas->addjs('nbacklink="'.$this->backlink.'";');
            $_SESSION['nframework']['logiopage']=$_SERVER['PHP_SELF'];
            
            $result = $this->appbar().($this->content!=''?'<div class="container">'. $this->content . '</div>'.$javas:'');
    	return $result;
    }
    function appbar(){
        global $user,$javas,$config;
        //nunca usar, causa errores en <link rel="next" href="' . $this->nextlink . '"/>
        ///*
        
        if($this->backlink!='/'){
        	$backlink=$this->backlink;
        	if ($backlink==''){
        		$backlink='./';
        	}
        	$tam=strlen($_SERVER['DOCUMENT_ROOT'])+1;
        	$word=substr(realpath($backlink),$tam);
        	if(substr($word,-4)=='.php'){
        		$word=substr($word,0,-4);
        	}
        	$word=ucwords($word,'/');
        	if ($word==''){
        		$this->title;
        	}
        	
        	$backlink='<a href="'.$this->backlink.'" class="'.($_SERVER['PHP_SELF'] == '/index.php'?'':'mif-chevron-thin-left').' app-bar-item fg-white p-2"></a>';
        	//<span class="mif-'.($_SERVER['PHP_SELF'] == '/index.php'?'home':'chevron-thin-left').'"></span> '.$this->title.'</a>            
            
        	
        }
        return '
        <link rel="prev" href="' . $this->backlink . '"/>
        <body style="padding-top: 50px">
		
	<div class="container-fluid app-bar fg-white pos-fixed fixed-top z-top"><div id="spider-navigator">
			<ul class="inline-list"></ul>
		</div>
        <header class=" fg-white pos-relative" data-expand-point="md" data-role="appbar">
            &nbsp'.$config['logo'].$backlink.$this->logoAdd.'
            <ul class="app-bar-menu ml-auto">
            	'.$this->menuAdd.'
            	<li>'.$user->usermenu().'</li>
            </ul>
        </header>
    </div>
    ';
        /*
		return '<link rel="prev" href="' . $this->backlink . '"/>
		<header class="topbar topbar-expand-sm">
		    <a href="/" class="topbar-brand fg-white border bd-white border-radius pr-2 pl-2"><sup>4</sup></a>
		    '.$backlink.'
		    <ul class="topbar-menu d-flex flex-justify-start" style="width:100%">
		        '.$this->menuAdd.'
		        <li class="ml-auto">
		        '.$user->usermenu().'
		        </li>
		    </ul>
		</header>';*/
    }
}