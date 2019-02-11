<?
class Dashboard{
	function __construct($contenido=''){
		$this->content=$contenido;
	}
	function __toString(){
		global $user;
		foreach($this->items as $key=>$item){
			$results.=($item['divider']!=''?'<li class="divider"></li>':'');
			
		$toreturn='
<aside class="sidebar pos-fixed z-2"
       data-role="sidebar"
       data-toggle="#sidebar-toggle-3"
       id="sb3"
       data-shift=".shifted-content">
    <div class="sidebar-header" data-image="'.$this->fondo.'">
      <a href="/" class="fg-white sub-action"
         onclick="Metro.sidebar.close(\'#sb3\'); return false;">
          <span class="mif-arrow-left mif-2x"></span>
      </a>
        <div class="avatar">
            <img src="'.$this->logo.'" "></img>
        </div>
        <span class="title fg-black">'.$this->titulo.'</span>
        <span class="subtitle fg-black">'.($this->sub!=""?"$this->sub":"<span id='liveclock' style='position: fixed; left: 150px;'></span>").'</span>
        </img>
    </div>
    <ul id="menuDash" class="sidebar-menu" style= "margin:0%">
        '.$results.'
    </ul>
</aside>
<div class="shifted-content h-100 p-ab">
    <div class="pos-fixed app-bar-expand-md z-1 p-1" data-role="appbar" style="background-color: '.$this->barcolor.'">
        <button class="app-bar-item c-pointer" id="sidebar-toggle-3">
            <span class="mif-menu fg-white"></span>
        </button>
				<ul id="menuAdd" class="t-menu open horizontal compact ml-auto " style="background-color: '.$this->barcolor.'; border-color:'.$this->barcolor.'">
        	'.$this->menuAdd.'
        </ul>
    </div>

    <div class="h-100 p-4" style="overflow-y: auto; height:150px;">';
    
    
		if($this->content==''){
    		$nframework['prebodyend']='</div></div>';
	    }else{
	    	$toreturn.=$this->content.'</div>
	</div>';
	    }
		return $toreturn;
	}
}
?>