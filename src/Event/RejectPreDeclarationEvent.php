<?php

namespace App\Event;

use App\Entity\PreDeclaration;

class RejectPreDeclarationEvent extends PreDeclarationEvent
{

    /**
     * RejectPreDeclarationEvent constructor.
     * @param PreDeclaration $preDeclaration
     */
    public function __construct(PreDeclaration $preDeclaration)
    {
        parent::__construct($preDeclaration);
    }

}