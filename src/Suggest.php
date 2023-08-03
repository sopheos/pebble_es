<?php

namespace Pebble\ES;

/**
 * Suggest
 *
 * @author mathieu
 */
class Suggest extends AbstractRaw
{
    private array $data = [];

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
     * @param string $name
     * @param string $search
     * @param AbstractRaw $term
     * @return static
     */
    public function term(string $name, string $search, AbstractRaw $term): static
    {
        $this->data[$name] = [
            "text" => $search,
            "term" => $term
        ];
        return $this;
    }

    /**
     * @param string $name
     * @param string $search
     * @param AbstractRaw $term
     * @return static
     */
    public function phrase(string $name, string $search, AbstractRaw $phrase): static
    {
        $this->data[$name] = [
            "text" => $search,
            "phrase" => $phrase
        ];
        return $this;
    }

    // -------------------------------------------------------------------------

    public function raw(): array
    {
        return $this->data;
    }

    // -------------------------------------------------------------------------
}
