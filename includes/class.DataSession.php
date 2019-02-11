<?php

class DataSession {
    private $datas;
    public function __construct() {
        global $m;
        $this->datas = array();
        if (isset($_SESSION['datas']) && is_array($_SESSION['datas'])) {
            foreach ($_SESSION['datas'] as $key => $value) {
                $this->datas[$key] = new Data($m->{$value['db']}->{$value['col']}, $value['id'], $value['simple']);
            }
        }
    }

    public function __unset($name) {
        unset($this->datas[$name]);
        unset($_SESSION['datas'][$name]);
    }

    public function add($key, $db, $col, $id, $simple = false) {
        global $m;
        //falta checar key duplicado
        $_SESSION['datas'][$key] = array('db' => $db, 'col' => $col, 'id' => $id, 'simple' => $simple);
        $this->datas[$key] = new Data($m->{$db}->{$col}, $id, $simple);
    }

    public function __isset($name) {
        return isset($this->datas[$name]);
    }

    public function __get($name) {
        if (array_key_exists($name, $this->datas)) {
            return $this->datas[$name];
        }
    }

}

?>