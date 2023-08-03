<?php

namespace Pebble\ES;

/**
 * Sort
 *
 * @author mathieu
 */
class Sort extends AbstractRaw
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
     * @param string $field
     * @param string $mode
     * @return static
     */
    public function asc(string $field, string $mode = null): static
    {
        return $this->add($field, 'asc', $mode);
    }

    /**
     * @param string $field
     * @param string $mode
     * @return static
     */
    public function desc(string $field, string $mode = null): static
    {
        return $this->add($field, 'desc', $mode);
    }

    /**
     * @param string $field
     * @param string|null $order
     * @param string|null $mode
     * @return static
     */
    public function add(string $field, ?string $order = null, ?string $mode = null): static
    {
        if ($order && $mode) {
            $this->data[] = [$field => [
                'order' => $order,
                'mode' => $mode,
            ]];
        } elseif ($order) {
            $this->data[] = [$field => $order];
        } else {
            $this->data[] = $field;
        }

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * @return array
     */
    public function raw(): array
    {
        return $this->data;
    }

    // -------------------------------------------------------------------------
}
