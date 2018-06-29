<?php

namespace App\Normalizer;

use App\DTO\Api\ApiResponse;

interface NormalizerInterface
{

    public function normalize(\Exception $exception): ApiResponse;
    public function supports(\Exception $exception);

}