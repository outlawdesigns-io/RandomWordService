<?php
include_once __DIR__ . '/db.php';
if(!isset($GLOBALS['db'])){
    $db = new Db();
}
interface RecordBehavior{
    public function create();
    public function update();
    public function setFields($updateObj);
}
abstract class Record implements RecordBehavior{

    const DB = 'utility';

    public $id;

    protected $table;

    public function __construct($table,$UID){
        $this->table = $table;
        if(!is_null($UID)){
            $this->id = $UID;
            $this->_build();
        }
    }

    protected function _build(){
        $results = $GLOBALS['db']
            ->database(self::DB)
            ->table($this->table)
            ->select("*")
            ->where("id","=","'" . $this->id . "'")
            ->get();
        if(!mysqli_num_rows($results)){
            throw new \Exception('Invalid UID');
        }
        while($row = mysqli_fetch_assoc($results)){
            foreach($row as $key=>$value){
                $this->$key = $value;
            }
        }
        return $this;
    }
    protected function _getUID(){
        $results = $GLOBALS['db']
            ->database(self::DB)
            ->table($this->table)
            ->select("id")
            ->orderBy("id desc limit 1")
            ->get();
        while($row = mysqli_fetch_assoc($results)){
            $this->id = $row['id'];
        }
        return $this;
    }
    public function create(){
        $reflection = new \ReflectionObject($this);
        $data = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        $upData = array();
        foreach($data as $obj){
            $key = $obj->name;
            if(!is_null($this->$key) && !empty($this->$key)){
                $upData[$key] = $this->$key;
            }
            if($key == 'created_date'){
                $upData[$key] = date('Y-m-d H:i:s');
            }
            if($key == 'dob'){
                $upData[$key] = date('Y-m-d',strtotime($this->$key));
            }
        }
        unset($upData['id']);
        $results = $GLOBALS['db']
            ->database(self::DB)
            ->table($this->table)
            ->insert($upData)
            ->put();
        $this->_getUID()->_build();
        return $this;
    }
    public function update(){
        $reflection = new \ReflectionObject($this);
        $data = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        $upData = array();
        foreach($data as $obj){
            $key = $obj->name;
            if(!is_null($this->$key) && !empty($this->$key)){
                $upData[$key] = $this->$key;
            }
        }
        unset($upData['id']);
        $results = $GLOBALS['db']
            ->database(self::DB)
            ->table($this->table)
            ->update($upData)
            ->where("id","=","'" . $this->id . "'")
            ->put();
        return $this;
    }
    public function setFields($updateObj){
        if(!is_object($updateObj)){
            throw new \Exception('Trying to perform object method on non object');
        }
        foreach($updateObj as $key=>$value){
            $this->$key = $value;
        }
        return $this;
    }
}