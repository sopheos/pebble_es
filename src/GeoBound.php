<?php

namespace Pebble\ES;

/**
 * GeoBound
 *
 * @author mathieu
 */
class GeoBound extends AbstractFilter
{
    protected string $name = 'geo_bounding_box';
    protected string $field = '';
    protected array $bounds = [];

    /**
     * @param string $field
     * @param float $latMin
     * @param float $latMax
     * @param float $lonMin
     * @param float $lonMax
     */
    public function __construct(string $field, float $latMin, float $latMax, float $lonMin, float $lonMax)
    {
        $this->field = $field;

        $this->bounds = [
            'top_left' => ['lat' => $latMax, 'lon' => $lonMin],
            'bottom_right' => ['lat' => $latMin, 'lon' => $lonMax]
        ];
    }

    /**
     * @param string $field
     * @param float $latMin
     * @param float $latMax
     * @param float $lonMin
     * @param float $lonMax
     * @return static
     */
    public static function make(string $field, float $latMin, float $latMax, float $lonMin, float $lonMax): static
    {
        return new static($field, $latMin, $latMax, $lonMin, $lonMax);
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return [$this->name => [
            $this->field => $this->bounds
        ]];
    }
}
