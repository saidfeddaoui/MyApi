<?php

namespace App\Event;

use App\Entity\PreDeclaration;

class AcceptPreDeclarationEvent extends PreDeclarationEvent
{

    /**
     * AcceptPreDeclarationEvent constructor.
     * @param PreDeclaration $preDeclaration
     */
    public function __construct(PreDeclaration $preDeclaration)
    {
        parent::__construct($preDeclaration);
    }

}