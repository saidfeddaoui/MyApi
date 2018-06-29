<?php

namespace App\Services;


use App\DTO\YahooWeatherApi\YahooResponse;
use GuzzleHttp\Client;
use JMS\Serializer\SerializerInterface;

class YahooWeatherApiService extends ApiCustomerService
{

    const DEFAULT_WOEID = 1532755; //Casablanca

    /**
     * @var YahooLocatorApiService
     */
    private $yahooLocator;

    /**
     * YahooWeatherApiService constructor.
     * @param Client $httpClient
     * @param SerializerInterface $serializer
     * @param string $class
     * @param YahooLocatorApiService $yahooLocator
     */
    public function __construct(Client $httpClient, SerializerInterface $serializer, string $class = 'array', YahooLocatorApiService $yahooLocator)
    {
        parent::__construct($httpClient, $serializer, $class);
        $this->yahooLocator = $yahooLocator;
    }

    /**
     * @param double $latitude
     * @param double $longitude
     * @return \App\DTO\YahooWeatherApi\Query|null
     */
    public function getWeather($latitude = null, $longitude = null)
    {
        $woeid = $this->getWoeidByLatitudeAndLongitude($latitude, $longitude) ?: self::DEFAULT_WOEID;
        $weather = $this->getWeatherDataByCityCode()->getWeather($woeid);
        $location = $this->yahooLocator->getLocation($woeid);
        if ($location) {
            $weather->setLocation($location);
        }
        return $weather;
    }
    /**
     * @param double $latitude
     * @param double $longitude
     * @return mixed
     */
    public function getWoeidByLatitudeAndLongitude($latitude, $longitude)
    {
        $query = "select woeid from geo.places where text=\"({$latitude}, {$longitude})\"";
        $result = $this->getResultByYahooQuery($query);
        return $result ? $result->getWoeid() : null;
    }
    /**
     * @param int $code
     * @return \App\DTO\YahooWeatherApi\Query|null
     */
    public function getWeatherDataByCityCode($code = self::DEFAULT_WOEID)
    {
        $query = "select * from weather.forecast where woeid ={$code} and u='c'";
        return $this->getResultByYahooQuery($query);
    }
    /**
     * @param string $query
     * @return \App\DTO\Api\ContentType\Weather|null
     */
    public function getResultByYahooQuery(string $query)
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
        return $yahooResponse->getQuery() ?: null;
    }

}