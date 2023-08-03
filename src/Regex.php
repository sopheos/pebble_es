<?php

namespace Pebble\ES;

/**
 * Regex
 *
 * @author mathieu
 */
class Regex extends Term
{
    protected string $name = 'regexp';

    /**
     * @param string $value
     * @return static
     */
    public function flags(string $value): static
    {
        return $this->set('flags', $value);
    }
}
