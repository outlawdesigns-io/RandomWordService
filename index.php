<?php

require_once __DIR__ . '/Models/Word.php';

$word = Word::getRandomWord();
echo $word->word;
