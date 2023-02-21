# DOM Parser

DOM Parser is an elegant and modern HTML / XML parser implemented in PHP with a focus on simplicity.

## Contents

- [Installation](#installation)
- [Usage](#usage)
- [Initializing a new parser](#initializing-a-new-parser)
- [Searching for element](#searching-for-element)
- [Getting element content](#getting-element-content)
- [Getting element attributes](#getting-element-attributes)
- [New query from element](#new-query-from-element)
- [License](#license)

## Installation

You can install the package via composer:

```sh
composer require srdante/dom-parser
```

## Usage

Below there is a simple example of how to use the package:

```php
use Srdante\DomParser\DomParser;

$htmlContent = '<div>Hello World!</div>';
$query = DomParser::startRelativeQuery($htmlContent);

echo $query->element('div')->first()->getText();
``` 

## Initializing a new parser

You can initialize a new parser class instance using relative query method:

```php
$query = DomParser::startRelativeQuery($htmlContent);
```

Or root query method:

```php
$query = DomParser::startRootQuery($htmlContent);
```

## Searching for element

You can do a search for an element and get an array as result:

```php
$parser->element('div', ['id', '=', 'my-header'])->get();
```

Or get the first or last element:

```php
$parser->element('div', ['id', '=', 'my-header'])->first();

$parser->element('div', ['id', '=', 'my-header'])->last();
```

The first parameter is the element tag, and the second (optional) is the "where clause".

## Getting element content

There is 3 ways to get the element content. The `getText()`, `getHtml()` and `getInsideHtml()` methods.

- `getText()` returns the element plain-text content.
- `getHtml()` returns the element HTML content including the own element.
- `getInsideHtml()` returns the element inner HTML content.

```php
$element->getText();

$element->getHtml();

$element->getInsideHtml();
```

## Getting element attributes

You can get all element attributes with the `getAttributes()` method.

```php
$element->getAttributes()['href'];
```

## New query from element

You can start a new query from any element from the library.

```php
$element->newRootQuery()->element('a')->first();

$element->newRelativeQuery()->element('a')->first();
```

## License

DOM Parser is open-sourced software licensed under the [MIT license](LICENSE.md).