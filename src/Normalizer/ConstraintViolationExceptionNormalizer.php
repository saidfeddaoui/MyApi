<?php

namespace App\Normalizer;

use App\DTO\Api\ApiResponse;

class ConstraintViolationExceptionNormalizer extends AbstractNormalizer
{

    /**
     * @param \Exception $exception
     * @return ApiResponse
     */
    public function normalize(\Exception $exception): ApiResponse
    {
        $violations = $exception->getViolations();
        $errors = [];
        foreach ($violations as $violation) {
            $errors[] = [
                'property' => $violation->getPropertyPath(),
                'invalid_value' => $violation->getInvalidValue(),
                'error' => $violation->getMessage(),
            ];
        }
        $response = new ApiResponse(['violations' => $errors], ApiResponse::CONSTRAINT_VIOLATION_ERROR);
        return $response;
    }

}