<?php

//namespace nframework;


function assignArrayByPath(&$arr, $path, $value, $separator='.') {
    $keys = explode($separator, $path);
    foreach ($keys as $key) {
        $arr = &$arr[$key];
    }
    $arr = $value;
}

function booltotag($tag,$val){
    return ' '.$tag.'="'.($val?'true':'false').'"';
}
function strtotag($tag,$val){
    return (!empty($val)?' '.$tag.'="'.$val.'"':'');
}
function icontotag($tag,$val){
    return ($tag!=''?' '.$tag.'="'.str_replace('"','\'',$val).'"':'');
}

class Base{
    public $tags;
    public function __construct($options = []) {
        $this->tags = [];
        foreach ($options as $option => $value) {
            $this->{$option} = $value;
        }
    }

}
class baseInput  {
    public $required;
    public $class;
    public $infobox;
    public $id;
    public $name;
    public $nameprefix;
    public $dataset;
    public $field;
    public $addclass;
    public $disabled;
    public $placeholder;
	public $caption;
	public $prependicon;
	public $readonly;
	public $default;
	public $validate;
    public function __lset($option,$value){
        $ovars=array_keys( get_object_vars($this));
        if($option=='value'){
           $this->value=$value;
        }elseif($option=='dataset'){
            $this->dataset=$value;
            $value->addElement($this);               
        }elseif(in_array($option, $ovars)){
            $this->{$option} = $value;
        }else{
            $this->tags[$option]=$value;
        }
        echo "$option,$value<br>";
    }
    public function __get($name) {
        switch ($name){
            case 'value':
                if(isset($this->dataset)){
                    return $this->dataset->{$this->field};
                } else { 
                    return $this->value;           
                }                
        }        
    }
    public function __isset($name) {
        $ovars=array_keys( get_object_vars($this));
        if(in_array($option, $ovars)){
           return (isset( $this->{$option}));
        }else{
            return (isset($this->tags[$option]));
        }
    }

    public function __construct($options = []) {
        $this->tags = [];
        $ovars=array_keys( get_object_vars($this));
        /*$tmp= get_class($this);
        $parentclass=get_parent_class($tmp);
        /print_r($ovars);
        while($parentclass){
        	echo $parentclass.'</br>';
        	$tmp=$parentclass;
        	$parentarray=(array)array_keys(get_class_vars($tmp));
        	$ovars= array_merge($ovars,$parentarray);
        	print_r ($ovars);
        	$parentclass=get_parent_class($tmp);
        }
        */
        if(!isset($options['class'])){
            $options['class']='inputText';
        }
        foreach ($options as $option => $value) {
            if($option=='value'){
               $this->value=$value;
            }elseif($option=='dataset'){
                $this->dataset=$value;
                $value->addElement($this);               
            }elseif(in_array($option, $ovars)){
                $this->{$option} = $value;
            }else{
                $this->tags[$option]=$value;
            }
        }
        
        if (isset($this->value)) {
            $tmpval = $this->value;
        } else {
            $this->value=(isset($this->default)?$this->default:'');
        }
        
        
        if($this->dataset!=''){
            if($this->name=='' &$this->field!=''){
                $this->name=$this->field;
            }
            $this->name=  $this->dataset->nameprefix.'['.$this->name.']';
			if (strpos($this->field, '.') !== false)
			{
			    $data=(array)$this->dataset->info;
			    $keys = explode('.', str_replace('$',$this->dataset->position,$this->field));
			    foreach ($keys as $innerKey){
			        if (!array_key_exists($innerKey, $data))
			        {
			            return $options['default'];
			        }
			        $data = $data[$innerKey];
			    }
			    $this->value=$data;
			}else{
	            if(!isset($this->dataset->{$this->field}) && isset($options['default'])){
	        		$this->value=$options['default'];
	            }else{
	            	$this->value=$this->dataset->{$this->field};
	            }
			}
            //echo "dd:".$this->field .$this->name.' '.$this->dataset->{$this->field}."\n";
        }else{
        	if(!isset($options['value']) && isset($options['default'])){
        		$this->value=$options['default'];
        	}
        }
        if($this->id=='')$this->id=str_replace(['[',']','.'],['_','','_'],$this->name);
        if($this->id=='')$this->id=str_replace(['[',']','.'],['_','','_'],$this->field);
    }
    
    
    public function is_valid($newval) {
        return ($this->pattern != '' ?  filter_var($newval, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/' . $this->pattern . '/']]):true);
    }
    protected function writetags() {
        foreach ($this->tags as $name => $value) {
            $result.=' ' . $name . '="' . $value . '"';
        }
        return $result;
        
    }
	public function data_validate(){
		$rules=explode(' ',$this->validate);
		if($this->required && !in_array('required',$rules)){
			$rules[]='required';
		}
	
		if($this->pattern){
			foreach($rules as $rule){
				if(substr($rule,0,7)=='pattern'){
					$encontrado=true;
				}
			}
			if(!$encontrado){
				$rules[]='pattern('.$this->pattern.')';
			}
		}
		return implode(' ',$rules);
	}
	
}
class baseOptions extends baseInput{
    public $options=[];
}
class label extends baseInput {
    public function __toString() {
        return '<label' . $this->writetags() . ' id="' . $this->id . '"' . '>' . $this->value . '</label>';
    }
}
class inputHidden extends baseInput {
    public function __construct($options = array()) {
             
        parent::__construct($options);
    }
    public function __toString() {
        return '<input type="hidden"'. ' id="' . $this->id . '" name="'. $this->name . '"' . $this->writetags().
        ' value="' . $this->value . '">' ;
    }
}
class inputText extends baseInput {
    public $search;
    public $btn;
    public $pattern;
    public $inputtype;
    public $addclass;
    public $type;
    public $uppercase;
    public $invalid_feedbak;
    
    public function __construct($options = array()) {
        $options['class']='inputText';
        if(!isset($options['type'])){
            $options['type']='text';        
        }        
        parent::__construct($options);
    }

    public function __toString() {
        return ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'')
        .($this->infobox!=''?'&nbsp;<span class="mif-question nfinfoicon fg-red" content="'.htmlentities($this->infobox,ENT_QUOTES).'"></span>':'')
        .'<input data-role="input" name="' .$this->name . '" id="' . $this->id . '"' .
            ' value="' . $this->value . '"' .
            ($this->type=='password' ? ' type="password"' : '') .                
            ($this->required ? ' required="required"' : '') .
            ($this->readonly ? ' readonly="readonly"' : '') .
            ($this->disabled ? ' disabled' : '') .
            ($this->uppercase ? ' uppercase="true"' : '') .
            ($this->addclass ? ' class="'.$this->addclass.'"' : '') .
            ($this->prependicon?' data-prepend="<span class=\'mif-'.$this->prependicon. '\'></span>"':'') .
            ($this->placeholder ? ' placeholder="' . $this->placeholder . '"' : '') .              
            ($this->pattern ? ' pattern="' . $this->pattern . '"' : '') . $this->writetags().            
        	' data-validate="'.$this->data_validate().'" autocomplete="disabled">'.
        	($this->invalid_feedback!=''?'<span class="invalid_feedback">'.$this->invalid_feedback.'</span>':'');
    }    
}
class inputNumber extends baseInput {
   
    
    public function __toString() {
        return ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'').'<input type="number" id="'.$this->id.
			'" name="' . $this->name .
			'"' . $this->writetags() .
			' data-validate="'.$this->data_validate().'" value="'.$this->value .'"'.
			($this->datasize ? ' data-size="'.$this->datasize.'"' : '') .
			($this->required ? ' required="required"' : '') .
			($this->readonly ? ' readonly="readonly"' : '') .
			($this->disabled ? ' disabled' : '') . 
			($this->placeholder ? ' placeholder="' . $this->placeholder . '"' : '') . 
			' autocomplete="disabled">';
    }
  public function is_valid($newval) {
	return is_numeric($newval);	
  }
    
}
class inputDate extends baseInput {
    
    public $type;
    public $format='%Y-%m-%d';
    //public $inputformat='%Y-%m-%d';
    public $clearbutton;
    public function __toString() {
   
		if($this->type==''){
			$this->type='calendarpicker';
		}
		//TODO provisional esperando fix en metroui 
		//$this->type='datetimepicker2date';
    return 
           ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'').'<input type="text" name="' . $this->name . '" id="' . $this->id . '" data-role="'.$this->type.'" 
            data-format="'.$this->format.'"
            data-input-format="'.$this->format.'" 
            data-validate="'.$this->data_validate().'" '.
           	($this->required ? ' required="required"' : '') .
           	($this->placeholder ? ' placeholder="'.$this->placeholder.'"' : '') .//TODO eliminar al arreglar componente
			($this->readonly ? ' readonly="readonly"' : '') .
			($this->disabled ? ' disabled' : '') .
			($this->clearbutton ? ' data-clear-button="true"' : '').
			($this->prependicon?' data-prepend="<span class=\'mif-'.$this->prependicon. '\'></span>"':'') .
           $this->addtags . ' '.($this->type=="datepicker"?'data-':' data-dialog-mode="true" ').'value="' . $this->value 
           . '" autocomplete="disabled">';
    }
    public function is_valid($date) {
    	$d = DateTime::createFromFormat(str_replace('%','',$this->format), $date);
    	return ($date==''&&!$this->required)||($d && $d->format($format) == $date);
	}
}
class inputTime extends baseInput {
   
    public $type;
    public function __toString() {
   
		if($this->type==''){
			$this->type='timepicker';
		}
    return 
           '<input type="text" name="' . $this->name . '" id="' . $this->id . '" data-role="'.$this->type.'" ' .
           	($this->required ? ' required="required"' : '') .
			($this->readonly ? ' readonly="readonly"' : '') .
			($this->disabled ? ' disabled' : '') .
			' data-validate="'.$this->data_validate().'"'.
			($this->prependicon?' data-prepend="<span class=\'mif-'.$this->prependicon. '\'></span>"':'') .
           $this->addtags . ' value="' . $this->value . '" autocomplete="disabled">';
           //<a class="button" onclick="$(this).prev().datetimepicker(\'show\');"><span class="mif-calendar"></span></a>';
    }
    public function is_valid($date) {
      $d = DateTime::createFromFormat('Y-m-d', $date);
      if(str_replace([' ','-','/','_'],['','','',''],$date)==''){
      	if ($this->required){
      		return false;
      	}else{
      		return true;
      	}
      }
	  return $d && $d->format('Y-m-d') == $date;
	}
}
class inputDateTime extends baseInput {
    public function __toString() {
    	 return 
    	 ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'').'<input name="' . $this->name . '" id="' . $this->id . '" type="text" data-role="input"' .
        	($this->required ? ' required="required"' : '') .
			($this->readonly ? ' readonly="readonly"' : '') .
			($this->disabled ? ' disabled' : '') .  
			'data-validate="'.$this->data_validate().'"'.
        $this->addtags . ' value="' . $this->value 
        . '" data-clear-button="false" data-custom-buttons="customCalendarButton" autocomplete="disabled"/>';
    }
}
class inputRte extends baseInput {
    public function __toString() {
        return '<textarea name="' . $this->name . '" id="' . $this->id
                . '"' .
            	($this->required ? ' required="required"' : '') .
				($this->readonly ? ' readonly="readonly"' : '') .
				($this->disabled ? ' disabled' : '') .
				($this->placeholder ? ' placeholder="'.$this->placeholder.'"' : '') .     
                $this->addtags . ' data-role="jqte">' . $this->value .
                '</textarea>'; 
    }
}
class textArea extends baseInput {
	var $uppercase;
    public function __toString() {
        return ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'').'<textarea data-role="textarea" name="' .
        $this->name . '" id="' . $this->id . '"' . $this->addtag .
    	($this->required ? ' required="required"' : '') .
		($this->readonly ? ' readonly="readonly"' : '') .
		($this->placeholder ? ' placeholder="'.$this->placeholder.'"' : '') .   
		' data-validate="'.$this->data_validate().'"'.
		($this->uppercase ? ' uppercase="true"' : '').
		($this->addclass ? ' class="'.$this->addclass.'"' : '') .
		($this->prependicon?' data-prepend="<span class=\'mif-'.$this->prependicon. '\'></span>"':'') .
		($this->disabled ? ' disabled' : '') .            
        '>' .$this->value . '</textarea>';
       
    }
}
class AutoformList extends baseInput {
    public $options=[];
}
class inputRadios extends baseOptions{
    public function __toString() {
        $contas=0;       
        foreach ($this->options as $value => $text) {
            $result.='<input type="radio" name="'. $this->name.'" id="' . $this->id .'_'. $contas . '" value="' . $value 
            . '" data-role="radio" data-caption="'.$text.'"' .
						($this->validate? 'data-validate="'.$this->validate.'"':'').
                    ($this->value == $value ? ' checked ' : ' ') .'/>';
            $contas++;
        }
        return ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'').$result;
    }
}
class Select extends baseOptions {
    public $combobox;
    public $multiple;
    public $options=[];
   
    public $canadd;
    public $datafilter=true;
    public function __toString() {
        if ($this->combobox && $this->value != '' && !array_search($this->value, $this->options)) {
            $this->options +=[$this->value];
        }
    	$this->role='select';
        if ($this->combobox) {
            $this->role='combobox';
        }
        
        if ($this->multiple) {
            $this->role='multiple';
            $this->value=(array)($this->value);
            //if (!is_array($this->value) && get_class($this->value)!='MongoDB\\Model\\BSONArray')$this->value=[];     
            foreach ($this->options as $value => $text) {
                $result.='<option value="' . $value . '"' .(in_array($value, $this->value) ? ' selected>' : '>') . $text . '</option>';
            }
        } else {
            foreach ($this->options as $value => $text) {
                $result.='<option value="' . $value . '"' . ($value == $this->value ? ' selected>' : '>') . $text . '</option>';
            }
        }
        // onfocus=\"Autoformonfocus(this)\" onblur=\"Autoformonblur(this)\">\n";					
        return 
            ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'').'<select name="' . $this->name . ($this->multiple ? '[]" multiple="multiple"' : '"') .
            ' id="' .$this->id . '"' . $this->writetags() .
            ' data-role="'.$this->role.'"' .
            ($this->canadd?' canadd="canadd"':'').
            ($this->disabled?' disabled="disabled"':'').
            ($this->required?' required="required"':'').
            (!$this->datafilter?' data-filter="false"':'').
            ($this->placeholder?' data-filter-placeholder="'.$this->placeholder.'"':'').
						($this->validate? 'data-validate="'.$this->validate.'"':'').
            ($this->multiple? ' class="'.$this->addclass.'"':''). '>' .$result.'</select>';
    }
    public function is_valid($newval) {
        //falta validar array
        //return($this->multiple ? true : filter_var($newval));
        //TODO: PROBAR return !is_array($newval);
        return true;
    }
}
class inputCheckBox extends baseInput {
    public $caption;
    public $type;
    public function __toString() {
       // $name=($this->dataset!=''?$this->dataset->nameprefix.'['.$this->name.']':$this->name);
        
        if ($this->type==''){
            $this->type='checkbox';
        }
        return '<input name="' . $this->name . '" id="' . $this->id 
                . '" type="checkbox" data-role="'.$this->type.'" data-caption="'.$this->caption.'"' .
                ($this->value != '' ? ' checked' : '').
                ($this->disabled != '' ? ' disabled' : '').
                $this->addtags . '>';
    }
}
class inputCheckBoxs extends Select {
	public $captionposition="right";
    public function __toString() {
        $result = '';
        $tempcheck = $this->value;
        if($this->type==''){
            $this->type='checkbox';
        }
        foreach ($this->options as $value => $text) {
            $result.= '<input type="checkbox" data-role="checkbox" id="'.$this->id.'_'.$value.'" name="' .
                    $this->name . "[$value]\"" .
                    ($tempcheck[$value] == 'on' ? ' checked' : '') .
                    " data-caption=\"$text\" data-caption-position=\"".$this->captionposition."\">\n";
            //$fields.=str_replace('%field%', $result, $this->format['fields'][2]);
        }
        //$result = str_replace('%fields%', $fields, $this->format['fields'][1]);
        return ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'').$result;
    }
}
class inputFiles extends baseInput {
	public $dir;
	public $download;
	public $preview;
	public $delete;
	public $disabled;
	public $accept;
    public function __toString() {
    	if($this->id=='')$this->id='veamos';
        $_SESSION['uploads4'][$this->id] = [
            'dir' => $this->dir,
            'formname' => $this->name,
            'delete'=>$this->delete,
            'download'=>$this->download,
            'preview'=>$this->preview,
        ];
        return ($this->caption!=''?'<label for="'.$this->id.'">'.$this->caption.'</label>':'').'<p>
        <input name="' . $this->name . '" id="' . $this->id . '" type="file"' .
        ($this->disabled ? ' disabled' : '') .
        ($this->accept ? ' accept="'.$this->accept.'"' : '') .
        ' data-url="/nframework/uploadfile.php"
data-sequential-uploads="true" multiple placeholder="Arrastra hasta aqui para subir archivos"
data-role="file" data-button-title="<span class=\'mif-folder\'></span>"
data-form-data=\'{"mid":"' . $this->id . '"}\'/><div id="' . $this->id . '_list"></div>
<div data-role-aux="file-progress" style="display: none;" data-role="progress" id="' . $this->id . '_progressbar"></div></p>';
    }
}



class example extends Base{
    public $content;
    public $title;
    public function __toString() {
        return '<div class="example" data-text="'.$this->title.'">'.$this->content.'</div>';
    }
}

class datasetpdo{
	public $elements; 
    private $collection;
    private $_id;
    public $info=[];
    public $nameprefix;
    public $simpleid;
    public $autosave;
    public $position;
    public $fieldprefix;
    public function addElement(&$element){
        $this->elements[]=$element;       
    }
    
    public function __construct($options,$query=[]) {              
        foreach ($options as $option=>$value){
            $this->{$option}=$value;
        }
        if ($this->_id != '' && $this->key!='') {
        	
        	
        	//$this->info =(array) $this->collection->findOne(['_id'=>$this->_id]);
            $sth=$this->pdo->query('SELECT * FROM '.$this->table.' WHERE '.$this->key.'="'.$this->_id.'"');
            $this->info=$sth->fetch(PDO::FETCH_ASSOC);
            if (count($this->info) == 0 ){
                $this->info = ['_id' => $this->_id ];                
            }else{
            	$this->exists=true;
            }
        }
        unset($this->info['']);
    }
    public function save(){
    	foreach($this->elements as $element){
        	$element->value=$_POST[$this->nameprefix][$element->field];
            if($element->disabled!=false&& !$element->is_valid($_POST[$this->nameprefix][$element->field])){
                $errores.='Error en:'.$element->field.'<br/>';
            }
        }
        if ($errores==''){
        		
    		if(!$this->exists){
    			foreach($this->elements as $element){
    				$changes[$element->field]=$_POST[$this->nameprefix][$element->field];
    			}
    			$sql='INSERT INTO '.$this->table
    			.' ('.implode(',',array_keys($changes))      			.') values("'.implode('","',$changes).'")';
    		}else{
    			
    			foreach($this->elements as $element){
    				if($element->field==$this->key){
    					$where=' where '.$element->field.'="'.$this->_id.'"';
    				}else{
    					$sqls[]=$element->field.'="'.$_POST[$this->nameprefix][$element->field].'"';
    				}
    			}
    			$sql.='UPDATE '.$this->table.' SET '.implode(',',$sqls).$where;
    			
    		}
    		echo $sql;
    		$this->pdo->query($sql);
        }
    }
    public function __get($name) {
        $result=false;
        if ($name!=''){
	        if ($name=='_id'){
	            $result= (string)$this->_id;
	        }else{
	             if (array_key_exists($name, $this->info)) {
	            	if (gettype($this->info[$name])=='object'){
	            		$result=iterator_to_array($this->info[$name],true);
	            	}else{
	                	$result= $this->info[$name];
	            	}
	            }
	        }
        }
        return $result;
    }
    public function __isset($name) {
        return isset($this->info[$name]);
    }
}
class dataset  {
    public $elements; 
    private $collection;
    private $_id;
    public $info=[];
    public $nameprefix;
    public $simpleid;
    public $autosave;
    public $position;
    public $fieldprefix;
    public function addElement(&$element){
        $this->elements[]=$element;       
    }
    public function __construct($options,$query=[]) {              
        foreach ($options as $option=>$value){
            $this->{$option}=$value;
        }
        if ($this->_id != '') {
        	$this->_id=($this->simpleid==true ?
                    trim($this->_id)
                    :new MongoDB\BSON\ObjectID(trim($this->_id ))
                );
             
            $this->info =(array) $this->collection->findOne(['_id'=>$this->_id]);
            if (count($this->info) == 0 ){
                $this->info = ['_id' => $this->_id ];                
            }
        }else{
        	$this->_id=new MongoDB\BSON\ObjectID();
            $this->info = ['_id' => $this->_id];
          
	
        }
        unset($this->info['']);
    }
    public function refresh(){
    	if ($this->_id != '') {           
            $this->info = $this->collection->findOne(['_id'=>
                ($this->simpleid==true ?
                    trim($this->_id)
                    : new MongoId(trim($this->_id ))
                )
                ]);
            if (count($this->info) == 0 ){
                $this->info = ['_id' => $this->_id ];                
            }
        }
    }
    public function __isset($name) {
        return isset($this->info[$name]);
    }
    public function __set($name, $value) {
        if ($name!='_id') {            
            if ($this->info[$name] != $value) {
                $this->info[$name] = $value;
                if ($this->_id==''){
                	$r=$this->collection->insertOne($this->info);
                	$this->info['_id']=$r['_id'];
                }else{
                	$this->collection->updateOne(['_id'=>$this->_id ],['$set'=>[$name=>$value]]
		                ,['upsert'=>true, ]);
		      //       echo "set"; 
		       //      print_r($this->_id);  	
                	//$this->collection->save($this->info);
                }
                //$this->col->update(['_id'=>$this->id],['$set'=>[$name=>$value]]);            
            }        
        }
        return true;
    }
    public function __unset($name) {
       if ($name!='_id'){
            unset($this->info[$name]);            
            $this->collection->updateOne(
                	['_id'=>$this->info['_id']],
                	['$unset'=>[$name=>'']]);
            
            //$this->col->update(['_id'=>$this->id],['$unset'=>[$name=>1]]);            
        }
        return true;
    }
    public function __get($name) {
        $result=false;
        if ($name!=''){
	        if ($name=='_id'){
	            $result= (string)$this->_id;
	        }else{
	             if (array_key_exists($name, $this->info)) {
	            	if (gettype($this->info[$name])=='object'){
	            		$result=iterator_to_array($this->info[$name],true);
	            	}else{
	                	$result= $this->info[$name];
	            	}
	            }
	        }
        }
        return $result;
    }
    public function save(){
        foreach($this->elements as $element){
        	$element->value=$_POST[$this->nameprefix][$element->field];
            if($element->disabled!=false&& !$element->is_valid($_POST[$this->nameprefix][$element->field])){
                $errores.='Error en:'.$element->field.'<br/>';
            }
        }
        if ($errores==''){
        	$toset=[];
        	$tounset=[];
	        foreach($this->elements as $element){
	        	if ($element->field=='_id'){
	        		$element->value=(string)$this->_id;
	        	}else{
	        		if($_POST[$this->nameprefix][$element->field]==''){
        				//assignArrayByPath($tounset,$element->field,1,'.');
        				$changes['$unset'][str_replace('$',$this->position,$this->fieldprefix.$element->field)]=1;
        			}else{
        				//assignArrayByPath($toset,$element->field,$_POST[$this->nameprefix][$element->field],'.');
        		
        				$changes['$set'][str_replace('$',$this->position,$this->fieldprefix.$element->field)]=$_POST[$this->nameprefix][$element->field];
        			}
	        		if(strpos($element->field,'.')!==false){
	        			$punto=true;
	        			
	        		}else{
	        			$this->info[$element->field]=$_POST[$this->nameprefix][$element->field];
	        		}
	        	}
	        }
	        if($punto){
	        //	echo '<textarea>'.print_r($changes,true).'</textarea>';
	        	$this->collection->updateOne(['_id'=>$this->_id],$changes,['upsert'=>true]);
	        }else{
	        	$this->collection->updateOne(['_id'=>$this->_id],['$set'=>$this->info],['upsert'=>true]);
	        }
	         return false;
        }else{
        	// $errores;
        	return $errores;
        }
        
    }
}

class datasetArray{
    private $info;
    public $elements;
    public $nameprefix;
    public $dataset;
    public $name;
    public $field;
    public function addElement(&$element){
        $this->elements[]=$element;       
    }
    public function __construct($options) {
        $ovars=array_keys( get_object_vars($this));
        foreach ($options as $option => $value) {
            if($option=='value'){
               $this->value=$value;
            }elseif($option=='dataset'){
                $this->dataset=$value;
                $value->addElement($this);               
            }elseif(in_array($option, $ovars)){
                $this->{$option} = $value;
            }else{
                $this->tags[$option]=$value;
            }
        }        
        if($this->dataset!=''){
            if($this->name=='' && $this->field!=''){
                $this->name=$this->field;
            }
            $this->name=$this->dataset->nameprefix.'['.$this->name.']';
            $this->nameprefix=$this->name;
            $this->value=$this->dataset->{$this->field};
        }
    }
    public function save(){
        $this->dataset->{$this->field}=$this->info;
    }
    public function is_valid($value){
        return true; //TODO check 
    }
}

class Icon{
	public $src;
	public function __construct($src){
		$this->src=$src;
	}
	public function __toString(){
		return (strpos($this->src,'.')===false?
            '<span class="icon mif-'.$this->src.'"></span> ':
            '<img src="'.$this->src.'" class="icon">'
        );
	}
}

class TreeViewItem{
	public $children;
	public $icon;
	public $caption;
	public $addnodetag;
	public function __construct($caption,$icon,$options=[]){
		$this->caption=$caption;
		$this->icon=$icon;
		foreach ($options as $option=>$valor){
			$this->{$option}=$valor;
		}
	}
	public function __toString(){
		if(count($this->children)>0){
			$tmp.='<ul>'.implode('',$this->children).'</ul>';
		}
		return '<li class="item" '.$this->addnodetag.' data-icon="'.$this->icon->data().'" data-caption="'.$this->caption.'">'.$tmp.'</li>';
	}
}
class TreeView{
	public $children;
	public function __toString(){
		return 	'<ul data-role="treeview"
			     id="tree_add_leaf_example">'.implode('',$this->children).'</ul>';
	
	}
}

