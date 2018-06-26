<?php

namespace App\Services;

class PharmacieApiService extends ApiCustomerService
{

    const DEFAULT_CITY = 'casablanca';

    const ENCRYPT_IV = 'fedcba9876543210';
    const ENCRYPT_KEY = 'Mobiblanc___2012_';

    public function getPharmacy($latitude = null, $longitude = null)
    {
        if (!$latitude || !$longitude) {
            return $this->getPharmacyByCity();
        }
        $latitude = preg_replace('#\.#', '_', $latitude);
        $longitude = preg_replace('#\.#', '_', $longitude);
        $response = $this->httpClient->get("pharmacie/proximite/{$latitude}/{$longitude}", [
            'query' => [
                'method' => 'mmd'
            ],
        ]);
        return $this->getResult($response);
    }
    /**
     * @param string $city
     * @return string
     */
    public function getPharmacyByCity($city = self::DEFAULT_CITY)
    {
        $response = $this->httpClient->get('pharmacie/' . $city, [
            'query' => [
                'method' => 'mmd'
            ],
        ]);
        return $this->getResult($response);
    }
    protected function getResult($response)
    {
        if (200 !== $response->getStatusCode()) {
            return null;
        }
        $data = (string)($response->getBody());
        $pharmacie = $this->serializer->deserialize($this->decrypt($data), 'array', 'json');
        dump($pharmacie);
        die;
        /**
         * @var \App\DTO\AladhanApi\AladhanResponse $aladhanResponse
         */
        $aladhanResponse = $this->serializer->deserialize((string)$response->getBody(), $this->class, 'json');
        return $aladhanResponse->isSuccess() ? $aladhanResponse->getData()->getUpcomingPrayer() : null;
    }
    protected function decrypt($encryptedText)
    {
        $encryptedText = hex2bin(base64_decode($encryptedText));
        $td = mcrypt_module_open('rijndael-128', '', 'cbc', self::ENCRYPT_IV);
        mcrypt_generic_init($td, self::ENCRYPT_KEY, self::ENCRYPT_IV);
        $decrypted = mdecrypt_generic($td, $encryptedText);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $decrypted;
    }

}