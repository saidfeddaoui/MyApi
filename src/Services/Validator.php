<?php
/**
 * Created by PhpStorm.
 * User: MOHAMEDfanane
 * Date: 05/09/2018
 * Time: 16:49
 */

namespace App\Services;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class Validator
 * @package App\Utils
 */
class Validator
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
     * @param $params
     * @return array
     */
    public function checkParams($params)
    {
        $optional = array();
        foreach ($params as $key => $param) {
            if ((empty($param) || is_null($param)) && !in_array($key, $optional)) {
                return array(
                    'status' => 'KO',
                    'code' => 406,
                    'message' => 'Paramètre (s) invalide (s) ' . $key
                );
            }
        }

        return array(
            'status' => 'OK',
            'code' => 100,
            'message' => 'Continue'
        );

    }

    /**
     * @param $method
     * @return array
     */
    public function checkMethod($method){
        if (!$this->currentRequest->isMethod($method)){
            return array(
                'status'=>'KO',
                'code' => 405,
                'message'=>'Method Not Allowed'
            );
        }
        return array(
            'status'=>'OK',
            'code' => 100,
            'message'=>'Continue'
        );
    }




}