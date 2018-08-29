<?php

require_once __DIR__ . '/record.php';

class Word extends Record{

    const TABLE = 'english_words';
    const PRIMARYKEY = 'id';

    public $word;

    public function __construct($UID = null){
        parent::__construct(self::TABLE,$UID);
    }
    public static function getAllIds(){
        $data = array();
        $results = $GLOBALS['db']
            ->database(parent::DB)
            ->table(self::TABLE)
            ->select(self::PRIMARYKEY)
            ->get();
        while($row = mysqli_fetch_assoc($results)){
            $data[] = $row[self::PRIMARYKEY];
        }
        return $data;
    }
    public static function getRandomWord(){
        $ids = self::getAllIds();
        $max = count($ids);
        $selection = mt_rand(0,$max);
        return new self($ids[$selection]);
    }
}