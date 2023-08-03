<?php

namespace Pebble\ES;

class Script extends AbstractFilter
{
    private string $script;

    /**
     * @param string $script
     */
    public function __construct(string $script)
    {
        $this->script = $script;
    }

    /**
     * @param string $script
     * @return static
     */
    public static function make(string $script): static
    {
        return new static($script);
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return [
            "script" => [
                "script" => [
                    "source" => $this->script
                ]
            ]
        ];
    }
}
