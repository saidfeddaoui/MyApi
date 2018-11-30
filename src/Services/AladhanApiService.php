<?php

namespace App\Services;

use GuzzleHttp\Client;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Translation\TranslatorInterface;

class AladhanApiService extends ApiCustomerService
{

    const DEFAULT_CITY = 'Casablanca';
    const DEFAULT_COUNTRY = 'Morocco';

    /**
     * @var TranslatorInterface $translator
     */
    private $translator;

    public function __construct(Client $httpClient, SerializerInterface $serializer, string $class = 'array', string $language = 'fr')
    {
        parent::__construct($httpClient, $serializer, $class);
        //$this->translator = $translator;
        //$this->translator->setLocale($language);
    }

    /**
     * @param double $latitude
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
        $prayer = $this->getResult($response);
        //$prayer->setName($this->translator->trans($prayer->getName(), [], 'salat'));
        return $prayer;
    }
    /**
     * @param string $city
     * @param string $country
     * @return \App\DTO\AladhanApi\Data|null
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