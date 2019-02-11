<?php
class Data {
    public $info = array();
    private $col;
    private $id;
    function __construct($col, $id, $simpleid = false) {
        $this->col = $col;
        if ($id != '') {
            $this->id =($simpleid? trim($id): new MongoDB\BSON\ObjectID(trim($id )));
            $this->info = $this->col->findOne(array('_id' => $this->id));
            if (count($this->info) == 0 ){
                $this->info = ['_id' => $this->id ];
                
            }
        }else{
            $this->info = ['_id' => new MongoDB\BSON\ObjectID()];            
        }
    }
    
    public function getData() {
        return $this->info;
    }
    public function __isset($name) {
        return isset($this->info[$name]);
    }
    public function __set($name, $value) {
        if ('$name'!='_id') {            
            if ($this->info[$name] != $value) {
                $this->info[$name] = $value;
                //$this->col->save($this->info);                    
                $this->col->updateOne(['_id'=>$this->_id],['$set'=>[$name=>$value]],['upsert'=>true]);
            }
        }
        return true;
    }
    public function __unset($name) {
       if ($name!='_id'){
            unset($this->info[$name]);            
            //$this->col->save($this->info);            
            $this->col->updateOne(['_id'=>$this->id],['$unset'=>[$name=>1]]);            
        }
        return true;
    }
    public function __get($name) {
        if ($name=='_id'){
            $result= (string) $this->info['_id'];
        }else{
            if (array_key_exists($name, $this->info)) {
                $result= $this->info[$name];
            }
        }
    return $result;
    }
}