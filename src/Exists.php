<?php

namespace Pebble\ES;

/**
 * Exists
 *
 * @author mathieu
 */
class Exists extends AbstractFilter
{
    private string $field = '';

    /**
     * @param string $field
     */
    public function __construct(string $field)
    {
        $this->field = $field;
    }

    /**
     * @param string $field
     * @return static
     */
    public static function make(string $field): static
    {
        return new static($field);
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return ['exists' => ['field' => $this->field]];
    }
}
