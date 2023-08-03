<?php

namespace Pebble\ES;

/**
 * Fuzzy
 *
 * @author mathieu
 */
class Fuzzy extends Term
{
    protected string $name = 'fuzzy';

    /**
     * @param int $value
     * @return static
     */
    public function fuzziness(int $value): static
    {
        return $this->set('fuzziness', $value);
    }

    /**
     * @param int $value
     * @return static
     */
    public function prefixLength(int $value): static
    {
        return $this->set('prefix_length', $value);
    }

    /**
     * @param int $value
     * @return static
     */
    public function maxExpansions(int $value): static
    {
        return $this->set('max_expansions', $value);
    }
}
