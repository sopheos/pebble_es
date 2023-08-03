<?php

namespace Pebble\ES;

/**
 * Raw
 *
 * @author mathieu
 */
class Raw extends AbstractRaw
{
    public array $data = [];

    // -------------------------------------------------------------------------

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @param array $data
     * @return static
     */
    public static function make(array $data): static
    {
        return new static($data);
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return $this->data;
    }

    // -------------------------------------------------------------------------
}
