<?php

namespace Srdante\DomParser;

use DOMXPath;
use DOMDocument;
use DOMNode;
use InvalidArgumentException;

class DomParser
{
    /**
     * Construct method.
     */
    public function __construct(protected string $htmlContent, protected string $pendingQuery = '')
    {
    }

    /**
     * Initiate a new dom parser from the root path.
     */
    public static function startRootQuery(string $htmlContent): static
    {
        return new static($htmlContent, '*/');
    }

    /**
     * Initiate a new dom parser from a relative path.
     */
    public static function startRelativeQuery(string $htmlContent): static
    {
        return new static($htmlContent, '//');
    }

    /**
     * Build find element query.
     */
    public function element(string $tagName, array $where = [], ?int $position = null): self
    {
        $this->pendingQuery .= (str_ends_with($this->pendingQuery, '/'))
            ? "{$tagName}"
            : "/{$tagName}";

        if (! empty($where)) {
            if (count($where) !== 3) {
                throw new InvalidArgumentException('"$where" parameter must contains 3 values');
            }

            [$property, $operator, $value] = $where;

            $property = ($property === 'content')
                ? 'text()'
                : "@{$property}";

            $this->pendingQuery .= match ($operator) {
                '='     => "[{$property}='{$value}']",
                default => "[{$operator}({$property}, '{$value}')]",
            };
        }

        if ($position !== null) {
            $this->pendingQuery .= "[{$position}]";
        }

        return $this;
    }

    /**
     * Return first element from result.
     */
    public function first(): ?DomElement
    {
        return $this->executeQuery()[0] ?? null;
    }

    /**
     * Return last element from result.
     */
    public function last(): ?DomElement
    {
        return array_values(array_slice($this->executeQuery()->toArray(), -1))[0] ?? null;
    }

    /**
     * Return all elements from result.
     */
    public function get(): DomElementList
    {
        return $this->executeQuery();
    }

    /**
     * Execute pending dom query.
     */
    protected function executeQuery(): DomElementList
    {
        $document = new DOMDocument();

        $html = mb_convert_encoding($this->htmlContent, 'HTML-ENTITIES', 'UTF-8');
        $document->loadHTML($html, LIBXML_NOERROR);

        // Execute query
        if (! $nodes = (new DOMXPath($document))->query($this->pendingQuery)) {
            return null;
        }

        return new DomElementList($nodes);
    }
}
