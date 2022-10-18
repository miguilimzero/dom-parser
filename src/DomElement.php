<?php

namespace Srdante\DomParser;

use DOMNode;

class DomElement
{
    public function __construct(protected DOMNode $rawNode)
    {
    }

    public function getTagName(): string
    {
        return $this->rawNode->tagName;
    }

    public function getAttributes(): array
    {
        $final = [];

        foreach(iterator_to_array($this->rawNode->attributes) as $attribute) {
            $final[$attribute->name] = $attribute->value;
        }

        return $final;
    }

    public function getText(): string
    {
        return trim($this->rawNode->nodeValue);
    }

    public function getHtml(): string
    {
        return $this->rawNode->ownerDocument->saveHTML($this->rawNode);
    }

    public function getInsideHtml(): string
    {
        $childHtmlList = array_map(
            callback: fn ($rawChildNode) => $rawChildNode->ownerDocument->saveHTML($rawChildNode), 
            array: iterator_to_array($this->rawNode->childNodes)
        );

        return implode('', $childHtmlList);
    }

    public function newRelativeQuery(): DomParser
    {
        return DomParser::startRelativeQuery($this->getInsideHtml());
    }
    
    public function newRootQuery(): DomParser
    {
        return DomParser::startRootQuery($this->getInsideHtml());
    }
}
