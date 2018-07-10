<?php

namespace App\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;

class SecurityController extends BaseController
{

    /**
     * @SWG\Post(
     *     tags={"Authentication"},
     *     description="Client phone number registration",
     *     @SWG\Parameter(
     *         name="Authorization",
     *         in="header",
     *         type="string",
     *         required=true,
     *         description="Basic auth",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Return Jwt Token",
     *     )
     * )
     *
     * @Rest\Post(name="login", path="/login")
     */
    public function login()
    {
        throw new \RuntimeException('You should never be here.');
    }
    /**
     * @SWG\Get(
     *     tags={"Secured Area"},
     *     description="Authentication Test",
     *     @SWG\Parameter(
     *         name="Authorization",
     *         in="header",
     *         type="string",
     *         required=true,
     *         description="Bearer auth",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Return hello world",
     *     )
     * )
     * @Rest\Get(name="test", path="/secured/test")
     */
    public function test()
    {
        dump('Hello World!');
        die;
    }

}