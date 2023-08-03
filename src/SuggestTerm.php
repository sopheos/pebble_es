<?php

namespace Pebble\ES;

/**
 * SuggestTerm
 *
 * @author mathieu
 */
class SuggestTerm extends AbstractRaw
{
    private array $data = [];

    // -------------------------------------------------------------------------

    public function __construct(string $field)
    {
        $this->field($field);
    }

    /**
     * @return static
     */
    public static function make(string $field): static
    {
        return new static($field);
    }

    // -------------------------------------------------------------------------

    /**
     * @param string $name
     * @param mixed $data
     * @return static
     */
    public function set(string $name, $data): static
    {
        $this->data[$name] = $data;
        return $this;
    }

    /**
     * @param string $field
     * @return static
     */
    public function field(string $field): static
    {
        return $this->set('field', $field);
    }

    /**
     * @param string $text
     * @return static
     */
    public function text(string $text): static
    {
        return $this->set('text', $text);
    }

    /**
     * @param string $analyzer
     * @return static
     */
    public function analyzer(string $analyzer): static
    {
        return $this->set('analyzer', $analyzer);
    }

    /**
     * @param int $size
     * @return static
     */
    public function size(int $size): static
    {
        return $this->set('size', $size);
    }

    /**
     * @param string $sort
     * @return static
     */
    public function sort(string $sort = 'score'): static
    {
        return $this->set('sort', $sort);
    }

    /**
     * @return static
     */
    public function sortScore(): static
    {
        return $this->sort('score');
    }

    /**
     * @return static
     */
    public function sortFrequency(): static
    {
        return $this->sort('frequency');
    }

    /**
     * @param string $mode
     * @return static
     */
    public function mode(string $mode = 'missing'): static
    {
        return $this->set('suggest_mode', $mode);
    }

    /**
     * @return static
     */
    public function modeMissing(): static
    {
        return $this->mode('missing');
    }

    /**
     * @return static
     */
    public function modePopular(): static
    {
        return $this->mode('popular');
    }

    /**
     * @return static
     */
    public function modeAlways(): static
    {
        return $this->mode('always');
    }

    /**
     * @param string $lowercase
     * @return static
     */
    public function lowercase(bool $lowercase): static
    {
        return $this->set('lowercase_terms', $lowercase);
    }

    /**
     * @param int $max_edits
     * @return static
     */
    public function maxEdits(int $max_edits): static
    {
        return $this->set('max_edits', $max_edits);
    }

    /**
     * @param int $prefix_length
     * @return static
     */
    public function prefixLength(int $prefix_length): static
    {
        return $this->set('prefix_length', $prefix_length);
    }

    /**
     * @param int $min_word_length
     * @return static
     */
    public function minWordLength(int $min_word_length): static
    {
        return $this->set('min_word_length', $min_word_length);
    }

    /**
     * @param int $shard_size
     * @return static
     */
    public function shardSize(int $shard_size): static
    {
        return $this->set('shard_size', $shard_size);
    }

    /**
     * @param int $max_inspections
     * @return static
     */
    public function maxInspections(int $max_inspections): static
    {
        return $this->set('max_inspections', $max_inspections);
    }

    /**
     * @param int $min_doc_freq
     * @return static
     */
    public function minDocFreq(int $min_doc_freq): static
    {
        return $this->set('min_doc_freq', $min_doc_freq);
    }

    /**
     * @param int $max_term_freq
     * @return static
     */
    public function maxTermFreq(int $max_term_freq): static
    {
        return $this->set('max_term_freq', $max_term_freq);
    }

    /**
     * @param int $string_distance
     * @return static
     */
    public function stringDistance(int $string_distance): static
    {
        return $this->set('string_distance', $string_distance);
    }

    // -------------------------------------------------------------------------

    public function raw(): array
    {
        return $this->data;
    }

    // -------------------------------------------------------------------------
}
