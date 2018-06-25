<?php

namespace App\Services;

use Psr\Http\Message\ResponseInterface;

class AladhanApiService extends ApiCustomerService
{

    const DEFAULT_CITY = 'Casablanca';
    const DEFAULT_COUNTRY = 'Morocco';

    /**
     * @param double $latitude
     * @param double $longitude
     * @return \App\DTO\AladhanApi\Data|array
     */
    public function getPrayer($latitude = null, $longitude = null)
    {
        if (!$latitude || !$longitude) {
            return $this->getPrayerByCityAndCountry();
        }
        $response = $this->httpClient->get('timings/' . time(), [
            'query' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
            ]
        ]);
        return $this->getResult($response);
    }
    /**
     * @param string $city
     * @param string $country
     * @return \App\DTO\AladhanApi\Data|array
     */
    public function getPrayerByCityAndCountry($city = self::DEFAULT_CITY, $country = self::DEFAULT_COUNTRY)
    {
        $response = $this->httpClient->get('timingsByCity/' . time(), [
            'query' => [
                'city' => $city,
                'country' => $country,
            ]
        ]);
        return $this->getResult($response);
    }
    /**
     * @param ResponseInterface $response
     * @return \App\DTO\Api\ContentType\Prayer|null
     */
    protected function getResult(ResponseInterface $response)
    {
        if (200 !== $response->getStatusCode()) {
            return null;
        }
        /**
         * @var \App\DTO\AladhanApi\AladhanResponse $aladhanResponse
         */
        $aladhanResponse = $this->serializer->deserialize((string)$response->getBody(), $this->class, 'json');
        return $aladhanResponse->isSuccess() ? $aladhanResponse->getData()->getUpcomingPrayer() : null;
    }

}