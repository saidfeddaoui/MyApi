<?php

namespace App\Exception;

use App\DTO\Api\ApiResponse;

class DuplicatedPreDeclarationException extends PreDeclarationCreationFailureException
{

    protected $code = ApiResponse::DUPLICATED_PRE_DECLARATION;

    /**
     * DuplicatedPreDeclarationException constructor.
     * @param string $message
     */
    public function __construct(string $message = "")
    {
        parent::__construct($this->code, $message);
    }

}