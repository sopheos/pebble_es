<?php

namespace Pebble\ES;

/**
 * Terms
 *
 * @author mathieu
 */
class Terms extends AbstractFilter
{
    private string $field = '';
    private array $data  = [];

    /**
     * @param string $field
     * @param array $values
     */
    public function __construct(string $field, array $values)
    {
        $this->field = $field;
        $this->data  = $values;
    }

    /**
     * @param string $field
     * @param mixed $values
     * @return static
     */
    public static function make(string $field, ...$values): static
    {
        return new static($field, $values);
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return ['terms' => [$this->field => $this->data]];
    }
}
