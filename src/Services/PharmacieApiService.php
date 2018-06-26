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

    const ENCRYPT_IV = 'fedcba9876543210';
    const ENCRYPT_KEY = 'Mobiblanc___2012_';

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


    public function getPharmacies($latitude = null, $longitude = null)
    {
        if (!$latitude || !$longitude) {
            return $this->getPharmacyByCity();
        }
        $latitude = preg_replace('#\.#', '-', $latitude);
        $longitude = preg_replace('#\.#', '_', $longitude);
        $response = $this->httpClient->get("pharmacie/proximite/{$latitude}/{$longitude}", [
            'query' => [
                'method' => 'ssl'
            ],
        ]);
        $data = (string)($response->getBody());
        dump(__METHOD__, $data, $this->decrypt($data), error_get_last());
        die;
        return $data;
    }
    /**
     * @param string $city
     * @return string
     */
    public function getPharmacyByCity($city = self::DEFAULT_CITY)
    {
        $response = $this->httpClient->get('pharmacie/' . $city, [
            'query' => [
                'method' => 'ssl'
            ],
        ]);
        $data = (string)($response->getBody());
        dump(__METHOD__, $data, $this->decrypt($data), error_get_last());
        die;
        return $data;
    }
    protected function decrypt($code)
    {
        $code = $this->hex2bin($code);
        $td = mcrypt_module_open('rijndael-128', '', 'cbc', self::ENCRYPT_IV);
        mcrypt_generic_init($td, self::ENCRYPT_KEY, self::ENCRYPT_IV);
        $decrypted = mdecrypt_generic($td, $code);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return utf8_encode(trim($decrypted));
    }
    protected function hex2bin($hexData)
    {
        $binData = '';
        $n = strlen($hexData);
        for ($i = 0; $i < $n; $i += 2) {
            $binData .= chr(hexdec(substr($hexData, $i, 2)));
        }
        return $binData;
    }
    /*
    public function encrypt($plaintext)
    {
        $key = hex2bin(self::ENCRYPT_KEY);
        $ivlen = openssl_cipher_iv_length(self::ENCRYPT_CIPHER);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $cipherText_raw = openssl_encrypt($plaintext, self::ENCRYPT_CIPHER, $key, OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $cipherText_raw, $key, true);
        return base64_encode( $iv.$hmac.$cipherText_raw );
    }
    public function decrypt($string)
    {
//        list($iv, $raw) = explode('_____', $string);
//        dump(compact('iv', 'raw'));
//        return openssl_decrypt($raw, self::ENCRYPT_CIPHER, hex2bin(self::ENCRYPT_KEY), OPENSSL_RAW_DATA, $iv);
        $sha2len = 32;
        $key = hex2bin(self::ENCRYPT_KEY);
        $code = base64_decode($string);
        $ivlen = openssl_cipher_iv_length(self::ENCRYPT_CIPHER);
        $iv = substr($code, 0, $ivlen);
        $cipherText_raw = substr($code, $ivlen + $sha2len);
        return openssl_decrypt($cipherText_raw, self::ENCRYPT_CIPHER, $key, OPENSSL_RAW_DATA, $iv);
    }
*/
}