<?php

namespace App\DTO\Api;


use App\Entity\Client;
use JMS\Serializer\Annotation as Serializer;



class LoginResponse
{

    /**
     * @var \string
     *
     * @Serializer\Type("string")
     * @Serializer\Expose()
     * @Serializer\Groups("login_response")
     */
    private $token;

    /**
     * @var Client
     *
     * @Serializer\Type("App\Entity\Client")
     * @Serializer\Expose()
     * @Serializer\Groups("login_response")
     */
    private $client;

    /**
     * LoginResponse constructor.
     * @param string $token
     * @param Client $client
     */
    public function __construct($token, Client $client)
    {
        $this->token = $token;
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

}