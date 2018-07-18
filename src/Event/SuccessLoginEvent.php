<?php

namespace App\Event;

use App\Entity\Client;
use Symfony\Component\EventDispatcher\Event;

class SuccessLoginEvent extends Event
{

    /**
     * @var string
     */
    private $token;
    /**
     * @var Client
     */
    private $client;

    /**
     * SuccessLoginEvent constructor.
     * @param string $token
     * @param Client $client
     */
    public function __construct(string $token, Client $client)
    {
        $this->token = $token;
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
    /**
     * @param string $token
     * @return static
     */
    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }
    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }
    /**
     * @param Client $client
     * @return static
     */
    public function setClient(Client $client): self
    {
        $this->client = $client;
        return $this;
    }

}