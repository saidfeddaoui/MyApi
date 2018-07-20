<?php

namespace App\Event;

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