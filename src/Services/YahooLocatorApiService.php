<?php

namespace App\Services;

use GuzzleHttp\Client;
use JMS\Serializer\SerializerInterface;

class YahooLocatorApiService extends ApiCustomerService
{

    /**
     * @var string $language
     */
    private $language;

    public function __construct(Client $httpClient, SerializerInterface $serializer, string $class = 'array', string $language = 'fr')
    {
        parent::__construct($httpClient, $serializer, $class);
        $this->language = $language;
    }

    /**
     * @param string $code
     * @return array|null
     */
    public function getLocation($code)
    {
        $response = $this->httpClient->get("WeatherService;woeids=[{$code}]", [ 'verify' => false,
            'query' => [
                'lang' => $this->language,
            ]
        ]);
        if (200 !== $response->getStatusCode()) {
            return null;
        }
        $yahooResponse = $this->serializer->deserialize((string)$response->getBody(), $this->class, 'json');
        $city = $yahooResponse['weathers'][0]['location']['displayName'] ?? null;
        $country = $yahooResponse['weathers'][0]['location']['countryName'] ?? null;
        return $city ? compact('city', 'country') : null;
    }


}