<?php

namespace App\DTO\Api\ContentType;

use JMS\Serializer\Annotation as Serializer;

class InfoPratique
{

    /**
     * @Serializer\Type("App\DTO\Api\ContentType\Prayer")
     * @var Prayer
     */
    private $prayer;
    /**
     * @Serializer\Type("App\DTO\Api\ContentType\Weather")
     * @var Weather
     */
    private $weather;

    /**
     * InfoPratique constructor.
     * @param Prayer $prayer
     * @param Weather $weather
     */
    public function __construct(Prayer $prayer = null, Weather $weather = null)
    {
        $this->prayer = $prayer;
        $this->weather = $weather;
    }

    /**
     * @return Prayer
     */
    public function getPrayer(): Prayer
    {
        return $this->prayer;
    }
    /**
     * @param Prayer $prayer
     * @return static
     */
    public function setPrayer(Prayer $prayer): self
    {
        $this->prayer = $prayer;
        return $this;
    }
    /**
     * @return Weather
     */
    public function getWeather(): Weather
    {
        return $this->weather;
    }
    /**
     * @param Weather $weather
     * @return static
     */
    public function setWeather(Weather $weather): self
    {
        $this->weather = $weather;
        return $this;
    }

}