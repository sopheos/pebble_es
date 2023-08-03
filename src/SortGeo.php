<?php

namespace Pebble\ES;

/**
 * Sort
 *
 * @author mathieu
 */
class SortGeo extends AbstractRaw
{
    private string $name;
    private string $field;
    private float $lat;
    private float $lon;

    /**
     * @param string $field
     * @param float $lat
     * @param float $lon
     * @param string $name
     */
    public function __construct(string $field, float $lat, float $lon, string $name = '_geo_distance')
    {
        $this->name  = $name;
        $this->field = $field;
        $this->lat   = $lat;
        $this->lon   = $lon;
    }

    /**
     * @param string $field
     * @param float $lat
     * @param float $lon
     * @param string $name
     * @return static
     */
    public static function make(string $field, float $lat, float $lon, string $name = '_geo_distance'): static
    {
        return new static($field, $lat, $lon, $name);
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return [
            $this->name => [
                $this->field => [
                    "lat" => $this->lat,
                    "lon" => $this->lon
                ],
                "order"      => "asc",
                "unit"       => "m"
            ]
        ];
    }
}
