<?php

namespace App\Services;

use GuzzleHttp\Client;
use JMS\Serializer\SerializerInterface;

abstract class ApiCustomerService
{

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
    public function __construct(Client $httpClient, SerializerInterface $serializer, $class = 'array')
    {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->class = $class;
    }

}