<?
class XMLS{
	private $tagName;
	public $className;
	public function __construct(){
	    $this->className=get_class($this);
	}
	public function Deserialize($xml,$nstrans=[],$classtrans=[]){
		$this->tagName=$xml->tagName;
	    foreach($xml->attributes as $so){
			$this->{$so->name}=$so->value;
		}
	    foreach($xml->childNodes as $so){
			if($so->localName!=''){
			    $clase='\\'.str_replace(':','\\',$so->nodeName);
			    
			    foreach($nstrans as $nso=>$nsn){
			    	if(strpos($clase,$nso)==0){
			    		$clase=str_replace($nso,$nsn,$clase);
			    	}
			    }
			    
			    if(array_key_exists($clase,$classtrans)){
			    	$clase=$classtrans[$clase];
			    }
			    if (class_exists($clase,true)){
				    //print_r($classtranslacions);
				    //echo "<br>$clase<br>";
					$objeto=new $clase;
					$objeto->Deserialize($so,$nstrans,$classtrans);
					if (is_array($this->{$so->localName})){
						$this->{$so->localName}[]=$objeto;
					}else{
						$this->{$so->localName}=$objeto;
					}
			    }else{
			    	echo "$clase no existe<br>";
			    }
			}
		}
	}
}