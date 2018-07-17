<?php

namespace App\Exception;

use App\DTO\Api\ApiResponse;

class PreDeclarationCreationFailureException extends \Exception
{

    /**
     * PreDeclarationCreationFailureException constructor.
     * @param int $code
     * @param string $message
     */
    public function __construct(int $code = ApiResponse::PRE_DECLARATION_CREATION_FAILED, string $message = "")
    {
        parent::__construct($message, $code);
    }

}