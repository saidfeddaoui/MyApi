<?php

namespace App\Controller\Api;

use App\DTO\Api\ApiResponse;
use App\DTO\Api\LoginResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

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
     * @Rest\View(serializerGroups={"all", "login_response"})
     *
     * @param TokenStorageInterface $tokenStorage
     * @param JWTEncoderInterface $jwtEncoder
     *
     * @return ApiResponse
     */
    public function login(TokenStorageInterface $tokenStorage, JWTEncoderInterface $jwtEncoder)
    {
        $user = $tokenStorage->getToken()->getUser();
        $token = 'Bearer ' . $jwtEncoder->encode(['phone' => $user->getPhone()]);
        $loginResponse = new LoginResponse($token, $user);
        return $this->respondWith($loginResponse);
    }

}