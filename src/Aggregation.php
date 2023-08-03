<?php

namespace Pebble\ES;

/**
 * Aggregation
 *
 * @author mathieu
 */
class Aggregation extends AbstractRaw
{
    protected string $name = '';
    protected array $data = [];

    // -------------------------------------------------------------------------

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string $name
     * @return static
     */
    public static function make(string $name): static
    {
        return new static($name);
    }

    /**
     * @param string $name
     * @param array $data
     * @return static
     */
    public function field(string $name, $data = []): static
    {
        $this->data[$name] = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return [$this->name => $this->data];
    }

    /**
     * @param Query $query
     * @return static
     */
    public function addToQuery(Query $query): static
    {
        $query->aggs($this);
        return $this;
    }

    // -------------------------------------------------------------------------
}
