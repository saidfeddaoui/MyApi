<?php

namespace App\Normalizer;

abstract class AbstractNormalizer implements NormalizerInterface
{

    /**
     * @var array
     */
    protected $exceptionTypes;

    /**
     * AbstractNormalizer constructor.
     * @param array $exceptionTypes
     */
    public function __construct(array $exceptionTypes)
    {
        $this->exceptionTypes = $exceptionTypes;
    }

    /**
     * @param \Exception $exception
     * @return bool
     */
    public function supports(\Exception $exception): bool
    {
        foreach ($this->exceptionTypes as $exceptionType) {
            if ($exception instanceof $exceptionType) {
                return true;
            }
        }
        return false;
    }

}