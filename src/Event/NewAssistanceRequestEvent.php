<?php

namespace App\Event;

use App\Entity\AssistanceRequest;
use Symfony\Component\EventDispatcher\Event;

class NewAssistanceRequestEvent extends Event
{

    /**
     * @var AssistanceRequest
     */
    protected $assistanceRequest;

    /**
     * NewAssistanceRequestEvent constructor.
     * @param AssistanceRequest $assistanceRequest
     */
    public function __construct(AssistanceRequest $assistanceRequest)
    {
        $this->assistanceRequest = $assistanceRequest;
    }

    /**
     * @return AssistanceRequest
     */
    public function getAssistanceRequest(): AssistanceRequest
    {
        return $this->assistanceRequest;
    }
    /**
     * @param AssistanceRequest $assistanceRequest
     * @return static
     */
    public function setAssistanceRequest(AssistanceRequest $assistanceRequest): self
    {
        $this->assistanceRequest = $assistanceRequest;
        return $this;
    }

}