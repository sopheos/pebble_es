<?php

namespace Pebble\ES;

/**
 * MatchPhrase
 */
class MatchPhrase extends AbstractFilter
{
    private string $field = '';
    private array $data = [];

    public function __construct(string $field, string $query, int $slop = 0)
    {
        $this->field = $field;
        $this->data['query'] = $query;
        $this->data['slop'] = $slop;
    }

    /**
     * @param string $field
     * @param string $query
     * @param int $slop
     * @return static
     */
    public static function make(string $field, string $query, int $slop = 0): static
    {
        return new static($field, $query, $slop);
    }

    /**
     * @param string $option
     * @param mixed $value
     * @return static
     */
    public function set(string $option, $value): static
    {
        $this->data[$option] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return ['match_phrase' => [$this->field => $this->data]];
    }
}
