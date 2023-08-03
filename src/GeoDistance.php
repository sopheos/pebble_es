<?php

namespace Pebble\ES;

/**
 * GeoDistance
 *
 * @author mathieu
 */
class GeoDistance extends AbstractFilter
{
    protected string $name = 'geo_distance';
    protected string $field;
    protected string $distance;
    protected array $point;

    /**
     * @param string $field
     * @param float $lat
     * @param float $lon
     * @param string $distance
     */
    public function __construct(string $field, float $lat, float $lon, string $distance)
    {
        $this->field    = $field;
        $this->point    = ['lat' => $lat, 'lon' => $lon];
        $this->distance = $distance;
    }

    /**
     * @param string $field
     * @param float $lat
     * @param float $lon
     * @param string $distance
     * @return static
     */
    public static function make(string $field, float $lat, float $lon, string $distance): static
    {
        return new static($field, $lat, $lon, $distance);
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return [$this->name => [
            'distance'   => $this->distance,
            $this->field => $this->point
        ]];
    }
}
