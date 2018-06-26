<?php
/**
 * Created by PhpStorm.
 * User: JIDAL_MOHAMED
 * Date: 25/06/2018
 * Time: 09:38
 */

namespace App\Services;

use \GuzzleHttp\Client;


class PharmacieApiService
{
    const DEFAULT_CITY = 'casablanca';

    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * PharmacieApiService constructor.
     *
     * @param \GuzzleHttp\Client $httpClient
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $city
     * @return string
     */
    public function getPharmacies($city = null)
    {
        if (!$city) {
            $city = self::DEFAULT_CITY;
        }
        $response = $this->httpClient->get('pharmacie/'.$city);
        $data = (string)($response->getBody());
        return $data;
    }
}