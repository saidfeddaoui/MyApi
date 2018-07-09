<?php

namespace App\Normalizer;

use App\DTO\Api\ApiResponse;

class NotFoundHttpExceptionNormalizer extends AbstractNormalizer
{

    public function normalize(\Exception $exception): ApiResponse
    {
        $response = new ApiResponse([], $exception->getStatusCode());
        $response->setStatus($exception->getReason());
        return $response;
    }

}