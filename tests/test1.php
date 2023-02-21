<?php

require __DIR__ . '/../vendor/autoload.php';

use Srdante\DomParser\DomParser;

$htmlContent = file_get_contents('https://www.uol.com.br/');
$domParser   = DomParser::startRelativeQuery($htmlContent);

$element = $domParser->element('article', ['class', 'contains', 'headlineSub'])->last();
$heading = $element->newRootQuery()->element('a')->first();

$title = $heading->getAttributes()['href'];
$link  = $heading->getText();

var_dump($title, $link);
