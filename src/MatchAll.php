<?php

namespace Pebble\ES;

/**
 * MatchAll
 *
 * @author mathieu
 */
class MatchAll extends AbstractFilter
{
    /**
     * @return static
     */
    public static function make(): static
    {
        return new static();
    }

    public function raw(): array
    {
        return ["match_all" => new \stdClass()];
    }
}
