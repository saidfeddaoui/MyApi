<?php

namespace App\Services;


use App\DTO\YahooWeatherApi\YahooResponse;

class YahooWeatherApiService extends ApiCustomerService
{

    const DEFAULT_CITY_CODE = 1532755; //Casablanca

    /**
     * @param double $latitude
     * @param double $longitude
     * @return \App\DTO\YahooWeatherApi\Query|null
     */
    public function getWeather($latitude = null, $longitude = null)
    {
        if (!$latitude || !$longitude) {
            return $this->getWeatherDataByCityCode();
        }
        $query = "select * from weather.forecast where woeid in (select woeid from geo.places where text=\"({$latitude}, {$longitude})\") and u='c'";
        return $this->getWeatherByYahooQuery($query);
    }
    /**
     * @param int $code
     * @return \App\DTO\YahooWeatherApi\Query|null
     */
    public function getWeatherDataByCityCode($code = self::DEFAULT_CITY_CODE)
    {
        $query = "select * from weather.forecast where woeid ={$code} and u='c'";
        return $this->getWeatherByYahooQuery($query);
    }
    /**
     * @param string $query
     * @return \App\DTO\Api\ContentType\Weather|null
     */
    public function getWeatherByYahooQuery(string $query)
    {
        $response = $this->httpClient->get('yql', [
            'query' => [
                'q' => $query,
                'format' => 'json',
            ]
        ]);
        if (200 !== $response->getStatusCode()) {
            return null;
        }
        /**
         * @var YahooResponse $yahooResponse
         */
        $yahooResponse = $this->serializer->deserialize((string)$response->getBody(), $this->class, 'json');
        return $yahooResponse->getQuery() ? $yahooResponse->getQuery()->getWeather() : null;
    }

}