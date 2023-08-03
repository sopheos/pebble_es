<?php

namespace Pebble\ES;

/**
 * Highlight
 */
class Highlight extends AbstractRaw
{
    protected array $data = [];

    // -------------------------------------------------------------------------

    /**
     * @param string ...$fields
     */
    public function __construct(string ...$fields)
    {
        $this->data['fields'] = array_fill_keys($fields, new \stdClass);
    }

    /**
     * @param string ...$fields
     * @return static
     */
    public static function make(string ...$fields)
    {
        return new static(...$fields);
    }

    // -------------------------------------------------------------------------

    /**
     * @param string $pre_tags
     * @return static
     */
    public function preTags(string $pre_tags): static
    {
        $this->data['pre_tags'] = $pre_tags;
        return $this;
    }

    /**
     * @param string $pre_tags
     * @return static
     */
    public function postTags(string $post_tags): static
    {
        $this->data['post_tags'] = $post_tags;
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
