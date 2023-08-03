<?php

namespace Pebble\ES;

/**
 * Query
 *
 * @author mathieu
 */
class Query extends AbstractRaw
{
    protected array $data = [];

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
     * @param string $key
     * @param mixed $value
     * @return static
     */
    public function set(string $key, $value): static
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * @param int $value
     * @return static
     */
    public function size(int $value = 10): static
    {
        return $this->set('size', $value);
    }

    /**
     * @param int $value
     * @return static
     */
    public function from(int $value = 0): static
    {
        return $this->set('from', $value);
    }

    /**
     * @param array mixed $value
     * @return static
     */
    public function searchAfter(array $value): static
    {
        return $this->set('search_after', $value);
    }

    /**
     * @param AbstractRaw $query
     * @return static
     */
    public function query(AbstractRaw $query): static
    {
        return $this->set('query', $query);
    }

    /**
     * Undocumented function
     *
     * @param AbstractRaw $suggest
     * @return static
     */
    public function suggest(AbstractRaw $suggest): static
    {
        return $this->set('suggest', $suggest);
    }

    /**
     * @return static
     */
    public function conflictsProceed(): static
    {
        return $this->set('conflicts', 'proceed');
    }

    /**
     * @param Aggregation $agg
     * @return static
     */
    public function aggs(AbstractRaw $agg): static
    {
        if (!isset($this->data['aggs'])) {
            $this->data['aggs'] = [];
        }

        $this->data['aggs'] = array_merge($this->data['aggs'], $agg->raw());

        return $this;
    }

    /**
     * @param AbstractRaw $sort
     * @return static
     */
    public function sort(AbstractRaw $sort): static
    {
        return $this->set('sort', $sort);
    }

    /**
     * @param AbstractRaw $highlight
     * @return static
     */
    public function highlight(AbstractRaw $highlight): static
    {
        return $this->set('highlight', $highlight);
    }

    // -------------------------------------------------------------------------

    public function raw(): array
    {
        return $this->data;
    }

    // -------------------------------------------------------------------------
}
