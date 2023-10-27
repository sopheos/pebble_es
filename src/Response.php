<?php

namespace Pebble\ES;

class Response
{
    private int $status = 500;
    private string $content = '';
    private ?array $json = null;

    public function __construct(int $status, string $content)
    {
        $this->status = $status;
        $this->content = $content;
    }

    public function status(): int
    {
        return $this->status;
    }

    public function body(): string
    {
        return $this->content;
    }

    public function json(): array
    {
        if ($this->json === null) {

            if (!$this->content) {
                return [];
            }

            $json = json_decode($this->content, true);
            $this->json = json_last_error() === JSON_ERROR_NONE ? $json : [];
        }

        return $this->json;
    }

    public function scrollID(): ?string
    {
        $json = $this->json();
        return $json['_scroll_id'] ?? null;
    }

    public function took(): int
    {
        $json = $this->json();
        return $json['took'] ?? 0;
    }

    public function total(): int
    {
        $json = $this->json();
        return $json['hits']['total']['value'] ?? $json['hits']['total'] ?? 0;
    }

    public function maxScore(): int
    {
        $json = $this->json();
        return $json['hits']['max_score'] ?? 0;
    }

    public function hits(): array
    {
        $json = $this->json();
        return $json['hits']['hits'] ?? [];
    }

    public function aggs(...$names)
    {
        $agg = $this->aggSearch($names);
        return $agg['buckets'] ?? $agg['value'] ?? null;
    }

    private function aggSearch(array $names): array
    {
        $json = $this->json();

        if (!($json['aggregations'] ?? null)) {
            return [];
        }

        $current = $json['aggregations'];

        foreach ($names as $name) {
            if (!($current[$name] ?? null)) {
                return [];
            }
            $current = $current[$name];
        }

        return $current;
    }
}
