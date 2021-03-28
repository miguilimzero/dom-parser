<?php

namespace App\DomQueryBuilder;

use DomXPath;
use DOMDocument;

class DomBuilder
{
    /**
     * @var mixed
     */
    private $file;

    /**
     * @var mixed
     */
    private $query;

    /**
     * @var array
     */
    private $result = [];

    /**
     * @param string $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Go inside to dom.
     *
     * @param string $to
     * @param array  $where
     * @param int    $position
     *
     * @return mixed
     */
    public function element($to, $where = [], $position = 0)
    {
        /*
         * If is the first element to search
         */
        if ($this->query == '//' || $this->query == './') {

            $this->query .= "{$to}";

        } else {

            $this->query .= "/{$to}";

        }

        /*
         * If have where query
         */
        if (! empty($where)) {

            /*
             * Where functions
             */
            $get = $where[0];
            $op  = $where[1];
            $val = $where[2];

            if ($op == '=') {

                $this->query .= "[contains(@{$get}, '{$val}')]";

            } elseif ($op == '==') {

                $this->query .= "[@{$get}='{$val}']";

            } elseif ($get == 'value') {

                $this->query .= "[text(){$op}'{$val}']";

            } else {

                $this->query .= "[{$op}(@{$get}, '{$val}')]";

            }
        }

        if ($position != 0) {
            $this->query .= "[{$position}]";
        }

        return $this;
    }

    /**
     * Return first element on object.
     *
     * @return mixed
     */
    public function first()
    {
        $result = $this->process();

        return ($result == null) ? null : $result[0];
    }

    /**
     * Return all elements on array.
     *
     * @return mixed
     */
    public function get()
    {
        return $this->process();
    }

    /**
     * Return last element on object.
     *
     * @return mixed
     */
    public function last()
    {
        return array_values(array_slice($this->process(), -1))[0];
    }

    /**
     * Start dom query from relative or root path.
     *
     * @param mixed $root
     *
     * @return mixed
     */
    public function query($root = false)
    {
        $this->query = ($root == false) ? '//' : './';

        return $this;
    }

    /**
     * Insert raw query.
     *
     * @param string $value
     *
     * @return mixed
     */
    public function rawQuery($value = null)
    {
        $this->query = $value;

        return $this;
    }

    /**
     * Decode dom nodes.
     *
     * @param mixed $nodes
     *
     * @return mixed
     */
    private function decode_nodes($nodes)
    {
        $result = [];

        /*
         * Foreach results and inserting
         */
        foreach ($nodes as $i => $node) {

            $result[] = new DomElement($node, $node->ownerDocument->saveHTML($node));

        }

        return $result;
    }

    /**
     * Process the query.
     *
     * @return mixed
     */
    private function process()
    {
        $document = new DOMDocument();
        $results  = [];

        $html = mb_convert_encoding($this->file, 'HTML-ENTITIES', 'UTF-8');

        @$document->loadHTML($html);

        $xpath = new DomXPath($document);

        /*
         * Make query
         */
        $nodes = $xpath->query($this->query);

        /*
         * If found something
         */
        if ($nodes->length > 0) {

            return $this->decode_nodes($nodes);

        }

    }
}
