<?php

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintViolationException extends \Exception
{

    /**
     * @var ConstraintViolationListInterface
     */
    private $violations;

    /**
     * ValidationException constructor.
     * @param ConstraintViolationListInterface $violations
     */
    public function __construct(ConstraintViolationListInterface $violations)
    {
        $this->violations = $violations;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }

}