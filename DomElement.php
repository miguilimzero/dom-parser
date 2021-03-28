<?php

namespace App\DomQueryBuilder;

class DomElement
{
    /**
     * @var mixed
     */
    public $attr;

    /**
     * @var mixed
     */
    public $html;

    /**
     * @var mixed
     */
    public $tag;

    /**
     * @var mixed
     */
    public $value;

    /**
     * @param $element
     * @param mixed $html
     * @param mixed $parent_level
     */
    public function __construct($element, $html, $parent_level = 0)
    {
        $this->attr = (object) [];

        if ($element->hasAttributes()) {
            foreach ($element->attributes as $i => $data) {
                $this->attr->{$data->name} = $data->value;
            }
        }

        $this->tag   = $element->tagName;
        $this->value = trim($element->nodeValue);

        $this->html       = $html;
        $this->parent     = null;
        $this->insideHtml = null;
        // $this->childs = $element->childNodes;

        foreach ($element->childNodes as $i => $node) {

            $this->insideHtml .= $node->ownerDocument->saveHTML($node);

        }

        /*
         * Get parent objects
         */
        if ($parent_level < 3) {

            if (null !== $element->parentNode->ownerDocument) {

                $parent_html = $element->parentNode->ownerDocument->saveHTML($element->parentNode);

                $this->parent = new self($element->parentNode, $parent_html, $parent_level + 1);

            }

        }

        // $this->childs = [];
        // foreach ($element->childNodes as $i => $node) {

        //     $this->childs[] = (new DomBuilder($node->ownerDocument->saveHTML($node)))->query(null)->element('*')->first();

        //     $this->childs[] = new DomElement($node, $node->ownerDocument->saveHTML($node));

        // }
    }

    /**
     * Get what type of value.
     *
     * @param bool $root
     * @param bool $inside
     *
     * @return void
     */
    public function query($root = false, $inside = false)
    {
        if ($inside == true) {
            return (new DomBuilder($this->insideHtml))->query($root);
        }

            return (new DomBuilder($this->html))->query($root);

    }
}
