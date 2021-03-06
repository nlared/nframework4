<?php
//**https://github.com/laplacesdemon/Mongodb-Autoincrement-PHP-Helper
class AutoIncrement {
    private $_counterCollectionName = "counters";
    private $_collectionName;
    private $_dbName;
    public function __construct($collectionName, $dbName = "masterdb_take1") {
        $this->_collectionName = $collectionName;
        $this->_dbName = $dbName;
    }
    public function getNext() {
        global $m; 
        //$m = new Mongo();
        $dbName = $this->_dbName;
        $counterCollName = $this->_counterCollectionName;
        $collName = $this->_collectionName;        
        // get the next available id
        $nextAvailableId = $this->_getNextAutoIncrementId($m->$dbName);
        // if it's null, then there is no entry in the db, create one
        if(!$nextAvailableId) {
            $this->_createTheObject($m->$dbName);
            return 1;
        }
        return $nextAvailableId;
    }    
    /**
     * return the next available unique id
     * if there is any deleted id's before, returns it for you to reuse it.
     * otherwise returns the next available auto increment id
     * 
     * @return integer
     */
    public function getNextUnique() {
        global $m;
//$m = new Mongo();
        $dbName = $this->_dbName;
        $counterCollName = $this->_counterCollectionName;
        $collName = $this->_collectionName;
        
        // get the first deleted row if one
        $object = $m->$dbName->$counterCollName->findOne(array("_id" => $collName));
        
        // if no removed id, return the next available
        if($object == null || count($object["removedIds"]) == 0)
            return $this->getNext();
        
        // sort the removed ids low to high, because we'll delete the 1st one
        sort($object["removedIds"]);
        
        // find the first item on the array and delete it
        $return = $object["removedIds"][0];
        unset($object["removedIds"][0]);
        $m->$dbName->$counterCollName->update(array("_id" => $collName), $object);
        return $return;

    }
    
    /**
     * adds the id to the "removed ids" list so we can reuse them
     * 
     * @param integer $id 
     */
    public function removeId($id) {
        global $m;
        //$m = new Mongo();
        $dbName = $this->_dbName;
        $counterCollName = $this->_counterCollectionName;
        $collName = $this->_collectionName;
        
        $collection = $m->$dbName->$counterCollName;
        
        // fetch the object
        $objectToUpdate = $collection->findOne(array("_id" => $collName));
        //print_r($objectToUpdate);
        // add the id to the removed ids stack
        $objectToUpdate["removedIds"][]=$id;
        //array_push($objectToUpdate["removedIds"], $id);
        //print_r($objectToUpdate);
        
        $collection->update(array("_id" => $collName), $objectToUpdate);
    }
    
    private function _createTheObject(MongoDb $db) {
        $counterCollName = $this->_counterCollectionName;
        $db->$counterCollName
                    ->insert(array(
                        "_id" => $this->_collectionName, 
                        "seq" => new MongoInt32("1"),
                        "removedIds" => array()
                        ));
    }
    
    /**
     * finds the next available id and increases the counter
     * 
     * @param MongoDb $db
     * @return integer 
     */
    private function _getNextAutoIncrementId(MongoDb $db) {
        $res = $db->command(
                    array(
                        'findandmodify' => $this->_counterCollectionName,
                        'query' => array('_id' => $this->_collectionName),
                        'update' => array('$inc' => array('seq' => 1)),
                        'new' => TRUE
                    )
               );
        
        // check if there is an error
        if($res["ok"] != 1)
            return false;
        
        return $res["value"]["seq"];
    }   
}