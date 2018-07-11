<?php

namespace App\Normalizer;

use App\DTO\Api\ApiResponse;

class NotFoundHttpExceptionNormalizer extends AbstractNormalizer
{

    /**
     * @param \Exception $exception
     * @return ApiResponse
     */
    public function normalize(\Exception $exception): ApiResponse
    {
        $response = new ApiResponse(null, $exception->getStatusCode());
        $response->setStatus($exception->getMessage());
        return $response;
    }

}