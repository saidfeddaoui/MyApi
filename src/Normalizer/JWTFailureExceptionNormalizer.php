<?php

namespace App\Normalizer;

use App\DTO\Api\ApiResponse;

class JWTFailureExceptionNormalizer extends AbstractNormalizer
{

    /**
     * @param \Exception $exception
     * @return ApiResponse
     */
    public function normalize(\Exception $exception): ApiResponse
    {
        $response = new ApiResponse(null, ApiResponse::JWT_FAILURE_ERROR);
        $response->setStatus($exception->getMessage());
        return $response;
    }

}