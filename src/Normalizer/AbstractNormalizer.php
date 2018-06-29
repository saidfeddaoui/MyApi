<?php
/**
 * Created by PhpStorm.
 * User: JIDAL_MOHAMED
 * Date: 29/06/2018
 * Time: 14:51
 */

namespace App\Normalizer;


abstract class AbstractNormalizer implements NormalizerInterface
{
    protected $exceptionTypes;

    public function __construct(array $exceptionTypes)
    {
        $this->exceptionTypes = $exceptionTypes;
    }

    public function supports(\Exception $exception)
    {
        return in_array(get_class($exception), $this->exceptionTypes);
    }
}