<?php

namespace Pebble\ES;

/**
 * Match
 *
 * @author mathieu
 */
class Range extends AbstractFilter
{

    private string $field = '';
    private array $data = [];

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
     * @param mixed $value
     * @return static
     */
    public function gte($value): static
    {
        return $this->set('gte', $value);
    }

    /**
     * @param mixed $value
     * @return static
     */
    public function gt($value): static
    {
        return $this->set('gt', $value);
    }

    /**
     * @param mixed $value
     * @return static
     */
    public function lte($value): static
    {
        return $this->set('lte', $value);
    }

    /**
     * @param mixed $value
     * @return static
     */
    public function lt($value): static
    {
        return $this->set('lt', $value);
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
     * @param float $value
     * @return static
     */
    public function format(string $value): static
    {
        return $this->set('format', $value);
    }

    /**
     * @param float $value
     * @return static
     */
    public function timeZone(string $value): static
    {
        return $this->set('time_zone', $value);
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
        return ['range' => [$this->field => $this->data]];
    }
}
