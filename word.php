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
    public static function getWordCount(){
      $data = 0;
      $results = $GLOBALS['db']
          ->database(parent::DB)
          ->table(self::TABLE)
          ->select("count(*) as word_count")
          ->get();
      while($row = mysqli_fetch_assoc($results)){
          $data = $row['word_count'];
      }
      return $data;
    }
    public static function getRandomWord(){
        $max = self::getWordCount();
        $selection = mt_rand(0,$max);
        return new self($ids[$selection]);
    }
}
