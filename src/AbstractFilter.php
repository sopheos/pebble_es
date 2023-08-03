<?php

namespace Pebble\ES;

/**
 * AbstractFilter
 *
 * @author mathieu
 */
abstract class AbstractFilter extends AbstractRaw
{
    /**
     * @param BoolQuery $query
     * @return static
     */
    public function must(BoolQuery $query): static
    {
        $query->must($this);
        return $this;
    }

    /**
     * @param BoolQuery $query
     * @return static
     */
    public function filter(BoolQuery $query): static
    {
        $query->filter($this);
        return $this;
    }

    /**
     * @param BoolQuery $query
     * @return static
     */
    public function should(BoolQuery $query): static
    {
        $query->should($this);
        return $this;
    }

    /**
     * @param BoolQuery $query
     * @return static
     */
    public function mustNot(BoolQuery $query): static
    {
        $query->mustNot($this);
        return $this;
    }
}
