<?php

namespace App\DTO\YahooWeatherApi;

use App\DTO\Api\ContentType\Weather;
use JMS\Serializer\Annotation as Serializer;

class Query
{

    /**
     * @Serializer\Type("int")
     * @var int
     */
    private $count;
    /**
     * @Serializer\Type("DateTime")
     * @var \DateTime
     */
    private $created;
    /**
     * @Serializer\Type("string")
     * @var string
     */
    private $lang;
    /**
     * @Serializer\Type("array")
     * @var array
     */
    private $results;

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }
    /**
     * @param int $count
     * @return static
     */
    public function setCount(int $count): self
    {
        $this->count = $count;
        return $this;
    }
    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }
    /**
     * @param \DateTime $created
     * @return static
     */
    public function setCreated(\DateTime $created): self
    {
        $this->created = $created;
        return $this;
    }
    /**
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }
    /**
     * @param string $lang
     * @return static
     */
    public function setLang(string $lang): self
    {
        $this->lang = $lang;
        return $this;
    }
    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }
    /**
     * @param array $results
     * @return static
     */
    public function setResults(array $results): self
    {
        $this->results = $results;
        return $this;
    }

    /**
     * @return Weather
     */
    public function getWeather()
    {
        $location = $this->results['channel']['location'] ?? null;
        $temp = $this->results['channel']['item']['condition']['temp'] ?? null;
        $date = $this->results['channel']['item']['condition']['date'] ?? null;
        if ($date) {
            $date = new \DateTime($date);
        }
        $text = $this->results['channel']['item']['condition']['text'] ?? null;
        $unit = $this->results['channel']['units']['temperature'] ?? null;
        $code = $this->results['channel']['item']['condition']['code'] ?? null;
        $icon = $code ? "http://l.yimg.com/a/i/us/we/52/{$code}.gif" : null;
        return new Weather($temp, $unit, $date, $text, $icon, $location);
    }
    /**
     * @return string
     */
    public function getWoeid()
    {
        return $this->results['place']['woeid'] ?? '';
    }

}