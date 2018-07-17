<?php

namespace App\Event;

use App\Entity\PreDeclaration;
use Symfony\Component\EventDispatcher\Event;

class NewPreDeclarationEvent extends Event
{

    /**
     * @var PreDeclaration
     */
    private $preDeclaration;

    /**
     * NewPreDeclarationEvent constructor.
     * @param PreDeclaration $preDeclaration
     */
    public function __construct(PreDeclaration $preDeclaration)
    {
        $this->preDeclaration = $preDeclaration;
    }

    /**
     * @return PreDeclaration
     */
    public function getPreDeclaration(): PreDeclaration
    {
        return $this->preDeclaration;
    }

    /**
     * @param PreDeclaration $preDeclaration
     * @return static
     */
    public function setPreDeclaration(PreDeclaration $preDeclaration): self
    {
        $this->preDeclaration = $preDeclaration;
        return $this;
    }

}