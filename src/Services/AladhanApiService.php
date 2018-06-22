<?php

namespace App\Services;

use \GuzzleHttp\Client;
use JMS\Serializer\SerializerInterface;

class AladhanApiService
{

    const DEFAULT_CITY = 'Casablanca';
    const DEFAULT_COUNTRY = 'Morocco';

    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;
    /**
     * @var \JMS\Serializer\SerializerInterface
     */
    protected $serializer;
    /**
     * DTO Class to deserialize the response into
     * @var string
     */
    protected $class;

    /**
     * AladhanApiService constructor.
     *
     * @param \GuzzleHttp\Client $httpClient
     * @param SerializerInterface $serializer
     * @param string $class
     */
    public function __construct(Client $httpClient, SerializerInterface $serializer, $class)
    {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->class = $class;
    }

    /**
     * @param double $latitude
     * @param double $longitude
     * @return \App\DTO\AladhanApi\Data|array
     */
    public function getTimingsData($latitude = null, $longitude = null)
    {
        if (!$latitude || !$longitude) {
            return $this->getTimingsDataByCityAndCountry();
        }
        $response = $this->httpClient->get('timings/' . time(), [
            'query' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
            ]
        ]);
        /**
         * @var \App\DTO\AladhanApi\AladhanResponse $aladhanResponse
         */
        $aladhanResponse = $this->serializer->deserialize((string)$response->getBody(), $this->class, 'json');
        return $aladhanResponse->isSuccess() ? $aladhanResponse->getData() : [];
    }

    /**
     * @param string $city
     * @param string $country
     * @return \App\DTO\AladhanApi\Data|array
     */
    public function getTimingsDataByCityAndCountry($city = self::DEFAULT_CITY, $country = self::DEFAULT_COUNTRY)
    {
        $response = $this->httpClient->get('timingsByCity/' . time(), [
            'query' => [
                'city' => $city,
                'country' => $country,
            ]
        ]);
        /**
         * @var \App\DTO\AladhanApi\AladhanResponse $aladhanResponse
         */
        $aladhanResponse = $this->serializer->deserialize((string)$response->getBody(), $this->class, 'json');
        return $aladhanResponse->isSuccess() ? $aladhanResponse->getData() : [];
    }

}