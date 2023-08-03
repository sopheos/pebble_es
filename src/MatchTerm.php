<?php

namespace Pebble\ES;

/**
 * MatchTerm
 *
 * @author mathieu
 */
class MatchTerm extends AbstractFilter
{
    private string $field = '';
    private array $data = [];

    public function __construct(string $field, string $query)
    {
        $this->field = $field;
        $this->data['query'] = $query;
    }

    /**
     * @param string $field
     * @param string $query
     * @return static
     */
    public static function make(string $field, string $query): static
    {
        return new static($field, $query);
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
     * @param int $value
     * @return static
     */
    public function boost(int $value = 1): static
    {
        return $this->set('boost', $value);
    }

    /**
     * @param int|string $value
     * @return static
     */
    public function fuzzy($value = 'auto'): static
    {
        return $this->set('fuzziness', $value);
    }

    /**
     * @param integer $len
     * @return static
     */
    public function prefixLen(int $len): static
    {
        return $this->set('prefix_length', $len);
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return ['match' => [$this->field => $this->data]];
    }
}
