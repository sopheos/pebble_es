<?php

namespace Pebble\ES;

class Script extends AbstractFilter
{
    const PAINLESS = 'painless';
    const EXPRESSION = 'expression';
    const MUSTACHE = 'mustache';

    private string $lang = self::PAINLESS;
    private string $source = "";
    private array $params = [];

    public function __construct(string $source)
    {
        $this->source = $source;
    }

    public static function make(string $source): static
    {
        return new static($source);
    }

    public function lang(string $lang): static
    {
        $this->lang = $lang;
        return $this;
    }

    public function params(array $params = []): static
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        $data = [
            "lang" => $this->lang,
            "source" => $this->source,
        ];

        if ($this->params) {
            $data['params'] = $this->params;
        }

        return ["script" => $data];
    }
}
