<?php

require __DIR__ . '/../vendor/autoload.php';

use Srdante\DomParser\DomParser;

$htmlContent = file_get_contents('https://www.uol.com.br/');
$domParser   = DomParser::startRelativeQuery($htmlContent);

$elements = $domParser->element('article', ['class', 'contains', 'headlineMain'])->get();

var_dump($elements->toArray());