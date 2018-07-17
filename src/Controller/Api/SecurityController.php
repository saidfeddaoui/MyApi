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
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var JWTEncoderInterface
     */
    private $jwtEncoder;

    /**
     * SecurityController constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param JWTEncoderInterface $jwtEncoder
     */
    public function __construct(TokenStorageInterface $tokenStorage, JWTEncoderInterface $jwtEncoder)
    {
        $this->tokenStorage = $tokenStorage;
        $this->jwtEncoder = $jwtEncoder;
    }

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
     *         description="Return Jwt Token with client data",
     *     )
     * )
     *
     * @Rest\Post(name="login", path="/login")
     * @Rest\View(serializerGroups={"all", "login_response"})
     *
     * @return ApiResponse
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException
     */
    public function login()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $token = 'Bearer ' . $this->jwtEncoder->encode(['phone' => $user->getPhone()]);
        return $this->respondWith(new LoginResponse($token, $user));
    }

}