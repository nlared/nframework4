<?
class Tabs{
	function __toString(){
		foreach($this->frames as $header=>$content){
			$frames.='<div class="frame">
		        <div class="heading">'.$header.'</div>
		        <div class="content">
		            '.$content.'
		        </div>
		    </div>';
		}
	
		return'<ul class="'.$this->class.'" data-role="tabs">
        '.$tabs.'
        </div>';
		
	}
}