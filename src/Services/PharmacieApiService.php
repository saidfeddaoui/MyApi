<?php

namespace App\Services;

class PharmacieApiService extends ApiCustomerService
{

    const DEFAULT_LATITUDE = 33.5739983;
    const DEFAULT_LONGITUDE = -7.6584367;

    const ENCRYPT_IV = 'fedcba9876543210';
    const ENCRYPT_KEY = 'Mobiblanc___2012_';

    public function getPharmacy($latitude = self::DEFAULT_LATITUDE, $longitude = self::DEFAULT_LATITUDE)
    {
        $latitude = preg_replace('#\.#', '_', $latitude);
        $longitude = preg_replace('#\.#', '_', $longitude);
        $response = $this->httpClient->get("pharmacie_garde/proximite/{$latitude}/{$longitude}");
        return $this->getResult($response);
    }
    protected function getResult($response)
    {
        if (200 !== $response->getStatusCode()) {
            return null;
        }
        $data = (string)($response->getBody());
        /**
         * @var \App\DTO\PharmacyApi\PharmacyResponse $pharmacieResponse
         */
        $pharmacieResponse = $this->serializer->deserialize($this->decrypt($data), $this->class, 'json');
        return $pharmacieResponse->getNearestPharmacy() ?: null;
    }

    /**
     * @param $encryptedText
     * @return null|string
     */
    protected function decrypt($encryptedText)
    {
        $encryptedText = hex2bin(base64_decode($encryptedText));
        $td = mcrypt_module_open('rijndael-128', '', 'cbc', self::ENCRYPT_IV);
        mcrypt_generic_init($td, self::ENCRYPT_KEY, self::ENCRYPT_IV);
        $decrypted = mdecrypt_generic($td, $encryptedText);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return preg_replace('/[[:cntrl:]]/', '', $decrypted);
    }

}