<?php

namespace Pebble\ES;

/**
 * Term
 *
 * @author mathieu
 */
class Term extends AbstractFilter
{
    protected string $name  = 'term';
    protected string $field = '';
    protected array $data  = [];

    /**
     * @param string $field
     * @param mixed $value
     */
    public function __construct(string $field, $value)
    {
        $this->field         = $field;
        $this->data['value'] = $value;
    }

    /**
     * @param string $field
     * @param mixed $value
     * @return static
     */
    public static function make(string $field, $value): static
    {
        return new static($field, $value);
    }

    /**
     * @param float $value
     * @return static
     */
    public function boost(float $value): static
    {
        return $this->set('boost', $value);
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
        return [$this->name => [$this->field => $this->data]];
    }
}
