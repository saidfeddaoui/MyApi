<?php

namespace App\Event;

use App\Entity\PreDeclaration;

class NewPreDeclarationEvent extends PreDeclarationEvent
{

    /**
     * NewPreDeclarationEvent constructor.
     * @param PreDeclaration $preDeclaration
     */
    public function __construct(PreDeclaration $preDeclaration)
    {
        parent::__construct($preDeclaration);
    }

}