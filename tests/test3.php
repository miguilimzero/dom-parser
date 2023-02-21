<?php

require __DIR__ . '/../vendor/autoload.php';

use Srdante\DomParser\DomParser;

$htmlContent = '<div>Hello World!</div>';
$query = DomParser::startRelativeQuery($htmlContent);

echo $query->element('div')->first()->getText();
