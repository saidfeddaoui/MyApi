<?php

namespace App\Normalizer;

use App\DTO\Api\ApiResponse;

interface NormalizerInterface
{

    /**
     * @param \Exception $exception
     * @return ApiResponse
     */
    public function normalize(\Exception $exception): ApiResponse;
    /**
     * @param \Exception $exception
     * @return bool
     */
    public function supports(\Exception $exception): bool;

}