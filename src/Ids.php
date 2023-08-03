<?php

namespace Pebble\ES;

/**
 * Ids
 *
 * @author mathieu
 */
class Ids extends AbstractFilter
{
    private array $data = [];

    /**
     * @param array $values
     * @param string $type
     */
    public function __construct(array $values, string $type = '')
    {
        if ($type) {
            $this->data['type'] = $type;
        }
        $this->data['values'] = $values;
    }

    /**
     * @param array $values
     * @param string $type
     * @return static
     */
    public static function make(array $values, string $type = ''): static
    {
        return new static($values, $type);
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return ['ids' => $this->data];
    }
}
