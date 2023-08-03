<?php

namespace Pebble\ES;

/**
 * AbstractRaw
 *
 * @author mathieu
 */
abstract class AbstractRaw implements \JsonSerializable
{

    /**
     * @return array
     */
    abstract function raw(): array;

    /**
     * @return array
     */
    public function jsonSerialize(): mixed
    {
        return $this->raw();
    }
}
