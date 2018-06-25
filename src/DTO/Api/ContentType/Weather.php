<?php

namespace App\DTO\Api\ContentType;

use JMS\Serializer\Annotation as Serializer;

class Weather
{

    /**
     * @Serializer\Type("string")
     * @var string
     */
    private $temperature;
    /**
     * @Serializer\Type("string")
     * @var string
     */
    private $unit;
    /**
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     * @var \DateTime
     */
    private $date;
    /**
     * @Serializer\Type("string")
     * @var string
     */
    private $text;
    /**
     * @Serializer\Type("string")
     * @var string
     */
    private $icon;
    /**
     * @Serializer\Type("array")
     * @var array
     */
    private $location;
    /**
     * Weather constructor.
     * @param array $location
     * @param string $temperature
     * @param string $unit
     * @param \DateTime $date
     * @param string $text
     * @param string $icon
     */
    public function __construct(string $temperature = null, string $unit = null, \DateTime $date = null, string $text = null, string $icon = null, array $location = null)
    {
        $this->temperature = $temperature;
        $this->unit = $unit;
        $this->date = $date;
        $this->text = $text;
        $this->icon = $icon;
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getTemperature(): string
    {
        return $this->temperature;
    }
    /**
     * @param string $temperature
     * @return static
     */
    public function setTemperature(string $temperature): self
    {
        $this->temperature = $temperature;
        return $this;
    }
    /**
     * @return string
     */
    public function getUnit(): string
    {
        return $this->unit;
    }
    /**
     * @param string $unit
     * @return static
     */
    public function setUnit(string $unit): self
    {
        $this->unit = $unit;
        return $this;
    }
    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }
    /**
     * @param \DateTime $date
     * @return static
     */
    public function setDate(\DateTime $date): self
    {
        $this->date = $date;
        return $this;
    }
    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }
    /**
     * @param string $text
     * @return static
     */
    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }
    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }
    /**
     * @param string $icon
     * @return static
     */
    public function setIcon(string $icon): self
    {
        $this->icon = $icon;
        return $this;
    }
    /**
     * @return array
     */
    public function getLocation(): array
    {
        return $this->location;
    }
    /**
     * @param array $location
     * @return static
     */
    public function setLocation(array $location): self
    {
        $this->location = $location;
        return $this;
    }

}