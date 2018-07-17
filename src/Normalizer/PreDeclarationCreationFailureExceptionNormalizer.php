<?php

namespace App\Normalizer;

use App\DTO\Api\ApiResponse;

class PreDeclarationCreationFailureExceptionNormalizer extends AbstractNormalizer
{

    /**
     * @param \Exception $exception
     * @return ApiResponse
     */
    public function normalize(\Exception $exception): ApiResponse
    {
        $response = new ApiResponse(null, $exception->getCode());
        if ($exception->getMessage()) {
            $response->setStatus($exception->getMessage());
        }
        return $response;
    }

}