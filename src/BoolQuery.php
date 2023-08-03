<?php

namespace Pebble\ES;

/**
 * BoolQuery
 *
 * @author mathieu
 */
class BoolQuery extends AbstractRaw
{
    protected array $data = [];
    protected bool $empty = true;

    // -------------------------------------------------------------------------

    /**
     * @return static
     */
    public static function make(): static
    {
        return new static();
    }

    // -------------------------------------------------------------------------

    /**
     * @param AbstractRaw $raw
     * @return static
     */
    public function must(AbstractRaw $raw): static
    {
        if ($this->empty) $this->empty = false;
        $this->data['must'][] = $raw;
        return $this;
    }

    /**
     * @param AbstractRaw $raw
     * @return static
     */
    public function filter(AbstractRaw $raw): static
    {
        if ($this->empty) $this->empty = false;
        $this->data['filter'][] = $raw;
        return $this;
    }

    /**
     * @param AbstractRaw $raw
     * @return static
     */
    public function should(AbstractRaw $raw): static
    {
        if ($this->empty) $this->empty = false;
        $this->data['should'][] = $raw;
        return $this;
    }

    /**
     * @param AbstractRaw $raw
     * @return static
     */
    public function mustNot(AbstractRaw $raw): static
    {
        if ($this->empty) $this->empty = false;
        $this->data['must_not'][] = $raw;
        return $this;
    }

    /**
     * @param int $value
     * @return static
     */
    public function minimumSouldMatch(int $value): static
    {
        $this->data['minimum_should_match'] = $value;
        return $this;
    }

    /**
     * @param float $value
     * @return static
     */
    public function boost(float $value): static
    {
        $this->data['boost'] = $value;
        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * @return array
     */
    public function raw(): array
    {
        return $this->empty ? MatchAll::make()->raw() : ['bool' => $this->data];
    }

    // -------------------------------------------------------------------------
}
