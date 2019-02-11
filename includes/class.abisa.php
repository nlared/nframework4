<?php

function youtubebutons($inputorg) {
    set_time_limit(4);
    $input = $inputorg;
    global $youtubeids, $youtubejs;
    $init = strpos($input, '<a href="');
    while ($init !== false) {
        $youtubeids++;
        $init+=9;
        $linkpos = strpos($input, '"', $init);
        $initlabel = strpos($input, '>', $linkpos) + 1;
        $endlabel = strpos($input, '</a>', $initlabel);
        $link = substr($input, $init, $linkpos - $init);
        if (strpos($link, 'youtube.com') !== false) {
            $link = str_ireplace('watch?v=', 'embed/', $link);
            $label = substr($input, $initlabel, $endlabel - $initlabel);
            $input = substr($input, 0, $init - 9) . "<label class=\"button\" id=\"youtube$youtubeids\">$label</label>" . substr($input, $endlabel + 4);
            $you = new MetroWindowYoutube('youtube' . $youtubeids, $link);
            $youtubejs.=$you->make();
        } else {
            $mas = $endlabel;
        }
        $init = strpos($input, '<a href="', $mas);
    }
    return $input;
}

class MetroWindowYoutube {

    public $width;
    public $height;
    public $id;
    public $src;

    public function __construct($id, $src) {
        $this->id = $id;
        $this->src = $src;
        $this->width = 640;
        $this->height = 480;
    }
    public function make() {
        return '$("#'. $this->id . '").on(\'click\', function(){
    $.Dialog({
        overlay: false,
        shadow: true,
        flat: false,
        icon: \'<img src="images/excel2013icon.png">\',
        title: \'Youtube Video!\',
        content: \'\',
        onShow: function(_dialog){
            var html = [
                \'<iframe width="' . $this->width . '" height="' . $this->height . '" src="' . $this->src . '" frameborder="0"></iframe>\'
            ].join(""); 
            $.Dialog.content(html);
        }
    });
});';
    }

}
class abisa {
    var $js = '';
    var $subtitle;
    var $title;
    var $content;
    var $secondary; //true
    var $backlink;
    var $notify = '';
    var $start = true;
    public $menuAdd;
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
    function make() {
        return $this->__toString();
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
        global $user;
        //nunca usar, causa errores en <link rel="next" href="' . $this->nextlink . '"/>
        return '<link rel="prev" href="' . $this->backlink . '"/>
        <div class="app-bar frozen_top bg-lightBlue" style="z-index: 999;" data-role="appbar">
            <ul class="app-bar-menu">
                <li class="divider"></li>
                &nbsp'.$this->menuAdd.'
                	<div class="input-control text" data-role="input">
				    <input type="text">
				    <button class="button"><span class="mif-search"></span></button>
            </ul>
            
            <div class="app-bar-pullbutton automatic no-phone"></div>
            '.$user->usermenu().'  
    </div><div class="top_space"></div>';
    }
}