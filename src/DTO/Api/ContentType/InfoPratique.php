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
     * @Serializer\Type("App\DTO\Api\ContentType\Pharmacy")
     * @var Pharmacy
     */
    private $pharmacy;

    /**
     * InfoPratique constructor.
     * @param Prayer $prayer
     * @param Weather $weather
     * @param Pharmacy $pharmacy
     */
    public function __construct(Prayer $prayer = null, Weather $weather = null, Pharmacy $pharmacy = null)
    {
        $this->prayer = $prayer;
        $this->weather = $weather;
        $this->pharmacy = $pharmacy;
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

    /**
     * @return Pharmacy
     */
    public function getPharmacy(): Pharmacy
    {
        return $this->pharmacy;
    }
    /**
     * @param Pharmacy $pharmacy
     * @return static
     */
    public function setPharmacy(Pharmacy $pharmacy): self
    {
        $this->pharmacy = $pharmacy;
        return $this;
    }

}