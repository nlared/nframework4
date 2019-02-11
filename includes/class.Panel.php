<?

class Panel extends Base {
    public $caption;
    public $icon;
    public $collapsible;
    public $collapsed;
    public $content;
	function __toString(){
	    return '<div data-role="panel"'.
            strtotag(data-title-caption,$this->caption).
            booltotag('data-collapside',$this->collapsible).
            booltotag('data-collapsed',$this->collapsed).
            icontotag('data-title-icon',$this->icon).'>'.
            $this->content.'</div>';
	}
}
