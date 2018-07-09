<?php

namespace App\Normalizer;

use App\DTO\Api\ApiResponse;

class JWTFailureExceptionNormalizer extends AbstractNormalizer
{

    public function normalize(\Exception $exception): ApiResponse
    {
        $response = new ApiResponse([], ApiResponse::JWT_FAILURE_ERROR);
        $response->setStatus($exception->getMessage());
        return $response;
    }

}