<?php

namespace App\DTO\YahooWeatherApi;

use JMS\Serializer\Annotation as Serializer;

class YahooResponse
{

    /**
     * @Serializer\Type("App\DTO\YahooWeatherApi\Query")
     * @var Query
     */
    private $query;

    /**
     * @return Query
     */
    public function getQuery(): Query
    {
        return $this->query;
    }
    /**
     * @param Query $query
     * @return static
     */
    public function setQuery(Query $query): self
    {
        $this->query = $query;
        return $this;
    }

}