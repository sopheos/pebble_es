<?php

namespace Pebble\ES;

/**
 * Percolate
 *
 * @author mathieu
 */
class Percolate extends AbstractFilter
{
    protected array $data = [];

    /**
     * @param string $field
     * @param array $document
     */
    public function __construct(string $field, array $document)
    {
        $this->data = [
            "field" => $field,
            "document" => $document
        ];
    }

    /**
     * @param string $field
     * @param array $document
     * @return static
     */
    public static function make(string $field, array $document): static
    {
        return new static($field, $document);
    }

    /**
     * @param string $option
     * @param mixed $value
     * @return static
     */
    protected function set(string $option, $value): static
    {
        $this->data[$option] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return [
            "percolate" => $this->data
        ];
    }
}
