<?

class AccordionFrame extends Base{
    public $content;
    public $heading;
    function __toString(){
        return  '<div class="frame">
        <div class="heading">'.$this->heading.'</div>
        <div class="content">
            '.$this->content.'
        </div>
    </div>';
    }
}



class Accordion extends Base{
	public $duration;
    public $oneframe;
	public $showactive;
    public $activeframeclass;
    public $activeheadingclass;
    public $activecontentclass;
    public $onframeopen;
    public $onframebeforeopen;
    public $onframeclose;
    public $onframebeforeclose;
    public $onaccordioncreate;

    function addframe(AccordionFrame $frame){
        $this->frames[]=$frame;
    }

	function __toString(){

		return'<div data-role="accordion"'.
            strtotag('data-duration',$this->duration).
            booltotag('data-one-frame',$this->oneframe).
            booltotag('data-show-active',$this->showactive).
            strtotag('data-active-frame-class',$this->activeframeclass).
            strtotag('data-active-heading-class',$this->activeheadingclass).
            strtotag('data-active-content-class',$this->activecontentclass).
            strtotag('data-on-frame-open',$this->onframeopen).
            strtotag('data-on-frame-before-open',$this->onframebeforeopen).
            strtotag('data-on-frame-close',$this->onframeclose).
            strtotag('data-on-frame-before-close',$this->onframebeforeclose).
            strtotag('data-on-accordion-create',$this->onaccordioncreate).
            '>'.implode('',$this->frames).'
        </div>';
		
	}
}