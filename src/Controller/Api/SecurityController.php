<?php

namespace App\Controller\Api;

use App\DTO\Api\ApiResponse;
use App\DTO\Api\Security\LoginResponse;
use App\Event\ApplicationEvents;
use App\Event\SuccessLoginEvent;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * SecurityController constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param JWTEncoderInterface $jwtEncoder
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        JWTEncoderInterface $jwtEncoder,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->jwtEncoder = $jwtEncoder;
        $this->eventDispatcher = $eventDispatcher;
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
        $this->eventDispatcher->dispatch(ApplicationEvents::SUCCESS_LOGIN, new SuccessLoginEvent($token, $user));
        return $this->respondWith(new LoginResponse($token, $user));
    }

}