# Dom Parser

```php
use Srdante\DomParser\DomParser;

$htmlContent = file_get_contents('https://www.uol.com.br/');
$domParser   = DomParser::startRelativeQuery($htmlContent);

$element = $domParser->element('article', ['class', 'contains', 'headlineMain'])->first();
$heading = $element->newRootQuery()->element('a')->first();

$title = $heading->getAttributes()['href'];
$link  = $heading->getText();

var_dump($title, $link);
```

```php
$domParser->element('span', ['content', '=', 'SOME_TEXT_HERE']);
$domParser->element('span', ['content', 'contains', 'SOME_TEXT_HERE']);

$domParser->element('span', ['id', '=', 'SOME_ELEMENT_ID_HERE']);

$domParser->element('span', ['class', 'contains', 'SOME_CLASSES_HERE']);
```