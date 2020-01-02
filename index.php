<?php

require_once __DIR__ . '/word.php';

$word = Word::getRandomWord();
echo $word->word;
