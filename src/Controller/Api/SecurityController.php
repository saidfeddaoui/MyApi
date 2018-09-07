<?php

namespace App\Controller\Api;

use App\DTO\Api\ApiResponse;
use App\DTO\Api\Security\LoginResponse;
use App\Entity\Client;
use App\Event\ApplicationEvents;
use App\Event\SuccessLoginEvent;
use App\Utils\ConfigHostUtils;
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
     * @var ConfigHostUtils
     */
    private $configHostUtils;

    /**
     * SecurityController constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param JWTEncoderInterface $jwtEncoder
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        JWTEncoderInterface $jwtEncoder,
        EventDispatcherInterface $eventDispatcher,
        ConfigHostUtils $configHostUtils
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->jwtEncoder = $jwtEncoder;
        $this->eventDispatcher = $eventDispatcher;
        $this->configHostUtils = $configHostUtils;
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
        $request = $this->configHostUtils->getCurrentRequest();
        $user = $this->tokenStorage->getToken()->getUser();
        $token = 'Bearer ' . $this->jwtEncoder->encode(['phone' => $user->getPhone()]);
        $device_uid = $request->request->get('device_uid')?:'';
        if ($device_uid){
            $device  = $this->em->getRepository(Device::class)->findOneBy(array('device_uid' => $device_uid));
            if ($device instanceof Device){
                $client_device = $device->getClient()?:Null;
                if (!is_null($client_device)){
                    $client_device->setDeviceUid(Null);
                    $this->em->flush();
                }
                $client = $this->em->getRepository(Client::class)->findOneBy(array('phone' => $user->getPhone()));
                dump($client);
                die();
                if ($client instanceof Client){
                    $client->setDeviceUid($device_uid);
                    $device->setClient($client->getPhone());
                    $this->em->flush();
                }
            }
        }
        $this->eventDispatcher->dispatch(ApplicationEvents::SUCCESS_LOGIN, new SuccessLoginEvent($token, $user));
        return $this->respondWith(new LoginResponse($token, $user));
    }

}