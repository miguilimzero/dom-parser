<?php

namespace Srdante\DomParser;

use ArrayAccess;
use DOMNode;
use DOMNodeList;
use InvalidArgumentException;
use JsonSerializable;

class DomElementList implements ArrayAccess
{
    public function __construct(protected DOMNodeList $elements)
    {
    }

    public function offsetSet(mixed $offset, $value): void
    {
        if (!($value instanceof DOMNode)) {
            throw new InvalidArgumentException('Value must be an instance of DOMNode');
        }

        if (is_null($offset)) {
            $this->elements[] = $value;
        } else {
            $this->elements[$offset] = $value;
        }
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->elements[$offset]);
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->elements[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return isset($this->elements[$offset])
            ? new DomElement($this->elements[$offset])
            : null;
    }

    public function toArray(): array
    {
        return array_map(
            callback: fn ($element) => new DomElement($element),
            array: iterator_to_array($this->elements)
        );
    }
}
