<?php

namespace App\Normalizer;

use App\DTO\Api\ApiResponse;

class MissingRequiredFileExceptionNormalizer extends AbstractNormalizer
{

    /**
     * @param \Exception $exception
     * @return ApiResponse
     */
    public function normalize(\Exception $exception): ApiResponse
    {
        $response = new ApiResponse(null, ApiResponse::MISSING_REQUIREMENT_ERROR);
        if ($exception->getMessage()) {
            $response->setStatus($exception->getMessage());
        }
        return $response;
    }

}