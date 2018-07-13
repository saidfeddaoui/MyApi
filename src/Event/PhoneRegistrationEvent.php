<?php

namespace App\Event;


use App\Entity\Client;
use Symfony\Component\EventDispatcher\Event;

class PhoneRegistrationEvent extends Event
{

    /**
     * @var Client
     */
    private $client;

    /**
     * PhoneRegistrationEvent constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
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