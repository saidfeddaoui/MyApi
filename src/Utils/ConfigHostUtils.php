<?php
/**
 * Created by PhpStorm.
 * User: MOHAMEDfanane
 * Date: 27/07/2018
 * Time: 12:20
 */

namespace App\Utils;
use http\Env\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\HttpKernelInterface;


/**
 * Class ConfigHostUtils
 * @package App\Utils
 */
class ConfigHostUtils
{
    /**
     * @var RequestStack
     */
    protected $currentRequest;

    /**
     * ConfigHostUtils constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->currentRequest = $requestStack->getCurrentRequest();
    }

    /**
     * @return RequestStack
     */
    public function getCurrentRequest(){
        return $this->currentRequest;
    }
}