<?php

namespace Pebble\ES;

/**
 * Type
 *
 * @author mathieu
 */
class Type extends AbstractFilter
{
    private string $type = '';

    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @param string $type
     * @return static
     */
    public static function make(string $type): static
    {
        return new static($type);
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return ['type' => ['value' => $this->type]];
    }
}
