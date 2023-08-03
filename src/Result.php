<?php

namespace Pebble\ES;

/**
 * Result
 *
 * @author mathieu
 */
class Result implements \JsonSerializable
{
    protected object $raw;

    // -------------------------------------------------------------------------

    public function __construct(object $raw)
    {
        $this->raw = $raw;
    }

    /**
     * @return string|null
     */
    public function scrollID()
    {
        return $this->raw->_scroll_id ?? null;
    }

    /**
     * @return integer
     */
    public function took()
    {
        return $this->raw->took ?? 0;
    }

    /**
     * @return integer
     */
    public function total()
    {
        return $this->raw->hits->total->value ?? $this->raw->hits->total ?? 0;
    }

    /**
     * @return integer
     */
    public function maxScore()
    {
        return $this->raw->hits->max_score ?? 0;
    }

    /**
     * @return array
     */
    public function hits()
    {
        return $this->raw->hits->hits ?? [];
    }

    /**
     * @param array $names
     * @return mixed
     */
    public function aggs(...$names)
    {
        if (($agg = $this->aggSearch($names))) {

            if (isset($agg->buckets)) {
                return $agg->buckets;
            }

            if (isset($agg->value)) {
                return $agg->value;
            }
        }

        return null;
    }

    /**
     * @param array $names
     * @return mixed
     */
    private function aggSearch(array $names)
    {
        if (empty($this->raw->aggregations)) {
            return null;
        }

        $current = $this->raw->aggregations;

        foreach ($names as $name) {
            if (empty($current->{$name})) {
                return null;
            }
            $current = $current->{$name};
        }

        return $current;
    }

    /**
     * @return object
     */
    public function raw(): object
    {
        return $this->raw;
    }

    /**
     * @return object
     */
    public function jsonSerialize(): mixed
    {
        return $this->raw;
    }
}
