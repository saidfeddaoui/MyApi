<?php

namespace App\Controller\Api;

use App\Annotation\ThrowViolations;
use App\DTO\Api\ApiResponse;
use App\Entity\AssistanceRequest;
use App\Entity\Client;
use App\Entity\Device;
use App\Entity\Group;
use App\Entity\InsuranceType;
use App\Entity\Role;
use App\Event\ApplicationEvents;
use App\Event\PhoneRegistrationEvent;
use App\Services\VerificationCodeGeneratorInterface;
use App\Utils\ConfigHostUtils;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Shema;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route(path="/public/registration", name="api_public_registration")
 */
class RegistrationController extends BaseController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var JWTEncoderInterface
     */
    private $jwtEncoder;
    /**
     * @var VerificationCodeGeneratorInterface
     */
    private $codeGenerator;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var ConfigHostUtils
     */
    private $configHostUtils;

    /**
     * RegistrationController constructor.
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @param JWTEncoderInterface $jwtEncoder
     * @param VerificationCodeGeneratorInterface $codeGenerator
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $encoder,
        JWTEncoderInterface $jwtEncoder,
        VerificationCodeGeneratorInterface $codeGenerator,
        EventDispatcherInterface $eventDispatcher,
        ConfigHostUtils $configHostUtils
    )
    {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->jwtEncoder = $jwtEncoder;
        $this->codeGenerator = $codeGenerator;
        $this->eventDispatcher = $eventDispatcher;
        $this->configHostUtils = $configHostUtils;
    }

    /**
     * @SWG\Post(
     *     tags={"Registration"},
     *     description="Client phone number registration",
     *     @SWG\Parameter(
     *         name="X-ENTITY",
     *         in="header",
     *         type="string",
     *         default="MAMDA",
     *         description="Specify the user's Entity",
     *     ),
     *     @SWG\Parameter(
     *         name="Client",
     *         in="body",
     *         description="Client object",
     *         required=true,
     *         @Model(type="App\Entity\Client", groups={"phone_registration"})
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Returns a registration token",
     *         @Model(type="App\DTO\Api\ApiResponse", groups={"all"}),
     *         examples={
     *             "Success response":
     *             {
     *                 "code"=201,
     *                 "status"="Created",
     *                 "data"={
     *                     "registration_token"="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1MzEyMjUyNjksImV4..."
     *                 }
     *             }
     *         }
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Failure response",
     *         @Model(type="App\DTO\Api\ApiResponse", groups={"all"}),
     *         examples={
     *             "Validation Error (Http Code: 406)":
     *             {
     *                 "code"=406,
     *                 "status"="Constraint Violation Error"
     *             },
     *             "Insurance Type Error (Http Code: 404)":
     *             {
     *                 "code"=404,
     *                 "status"="Requested Insurance Name Does Not exist"
     *             },
     *             "Token Encoding Error (Http Code: 401)":
     *             {
     *                 "code"=730,
     *                 "status"="Invalid Token"
     *             }
     *         }
     *     )
     * )
     *
     * @Rest\Post(path="/phone", name="phone")
     * @ParamConverter(name="client", converter="fos_rest.request_body", options={"validator"={ "groups"={"phone_registration"} }})
     * @ParamConverter(name="insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @Rest\View()
     *
     * @ThrowViolations()
     *
     * @param Client $client
     * @param InsuranceType $insuranceType
     * @param ConstraintViolationListInterface $violations
     *
     * @return ApiResponse
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException
     */
    public function phoneRegistration(Client $client, InsuranceType $insuranceType, ConstraintViolationListInterface $violations)
    {
        $token = $this->jwtEncoder->encode(['phone' => $client->getPhone()]);
        $role = $this->em->getRepository('App:Role')->findOneByRole(Role::MOBILE_CLIENT);
        $group = $this->em->getRepository('App:Group')->findOneByRole(Group::MOBILE_USER);
        $client
            ->setEnabled(false)
            ->setVerificationCode($this->codeGenerator->generate())
            ->setStatus(Client::STATUS_UNVERIFIED_WITH_SMS)
            ->addInsuranceType($insuranceType)
            ->addRole($role)
            ->setGroup($group)
        ;
        $this->em->persist($client);
        $this->em->flush();
        $this->eventDispatcher->dispatch(ApplicationEvents::PHONE_REGISTRATION, new PhoneRegistrationEvent($client));
        return $this->respondWith(['registration_token' => $token], ApiResponse::CREATED);
    }

    /**
     * @SWG\Post(
     *     tags={"Registration"},
     *     description="Client phone number registration",
     *     consumes={"application/x-www-form-urlencoded"},
     *     @SWG\Parameter(
     *         name="X-REGISTRATION-TOKEN",
     *         in="header",
     *         type="string",
     *         required=true,
     *         description="Registration Token",
     *     ),
     *     @SWG\Parameter(name="code", in="formData", type="string", required=true),
     *     @SWG\Response(
     *         response=200,
     *         description="Returns a success response if the verification code is correct",
     *         @Model(type="App\DTO\Api\ApiResponse", groups={"all"}),
     *         examples={
     *             "Success response":
     *             {
     *                 "code"=200,
     *                 "status"="Ok"
     *             }
     *         }
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Failure response",
     *         @Model(type="App\DTO\Api\ApiResponse", groups={"all"}),
     *         examples={
     *             "Validation Error (Http Code: 406)":
     *             {
     *                 "code"=406,
     *                 "status"="Constraint Violation Error"
     *             },
     *             "Token Decoding Error (Http Code: 401)":
     *             {
     *                 "code"=730,
     *                 "status"="Invalid Token"
     *             },
     *             "Already verified Error (Http Code: 406)":
     *             {
     *                 "code"=612,
     *                 "status"="Client is already verified"
     *             },
     *             "Verification code Error (Http Code: 400)":
     *             {
     *                 "code"=610,
     *                 "status"="Verification code is not correct"
     *             }
     *         }
     *     )
     * )
     *
     * @Rest\Post(path="/phone/verification", name="phone_verification")
     * @Rest\RequestParam(name="code", requirements="\d{4}", description="The verification code sent by SMS")
     * @ParamConverter(name="client", options={"converter":"App\ParamConverter\RegistrationTokenParamConverter"})
     *
     * @Rest\View()
     *
     * @param Client $client
     * @param string $code
     * @return ApiResponse
     */
    public function phoneVerification(Client $client, string $code)
    {
        if (!$client->isUnverified()) {
            return $this->respondWith(null, ApiResponse::CLIENT_ALREADY_VERIFIED_ERROR);
        }
        if ($code !== (string)$client->getVerificationCode()) {
            return $this->respondWith(null, ApiResponse::VERIFICATION_CODE_ERROR);
        }
        $client->setStatus(Client::STATUS_VERIFIED_WITH_SMS);
        $this->em->persist($client);
        $this->em->flush();
        return $this->respondWith(null);
    }
    /**
     * @SWG\Post(
     *     tags={"Registration"},
     *     description="Client account completion",
     *     @SWG\Parameter(
     *         name="X-REGISTRATION-TOKEN",
     *         in="header",
     *         type="string",
     *         required=true,
     *         description="Registration Token",
     *     ),
     *     @SWG\Parameter(
     *        name="Client",
     *        in="body",
     *        description="Client object",
     *        required=true,
     *        @Model(type="App\Entity\Client", groups={"client_account_creation"})
     *     ),
     *     @SWG\Response(
     *         response=202,
     *         description="Returns a success response if the client account wad successfully completed",
     *         @Model(type="App\DTO\Api\ApiResponse", groups={"all"}),
     *         examples={
     *             "Success response":
     *             {
     *                 "code"=202,
     *                 "status"="Updated"
     *             }
     *         }
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Failure response",
     *         @Model(type="App\DTO\Api\ApiResponse", groups={"all"}),
     *         examples={
     *             "Validation Error (Http Code: 406)":
     *             {
     *                 "code"=406,
     *                 "status"="Constraint Violation Error"
     *             },
     *             "Token Decoding Error (Http Code: 401)":
     *             {
     *                 "code"=730,
     *                 "status"="Invalid Token"
     *             },
     *             "Client Unverified Error (Http Code: 401)":
     *             {
     *                 "code"=611,
     *                 "status"="Unauthorized action for an unverified client"
     *             },
     *             "Account already created Error (Http Code: 406)":
     *             {
     *                 "code"=613,
     *                 "status"="Client has already created his account"
     *             }
     *         }
     *     )
     * )
     *
     * @Rest\Post(path="/account/creation", name="account_creation")
     * @ParamConverter(name="submittedClient", converter="fos_rest.request_body", options={"validator"={ "groups"={"client_account_creation"} }})
     * @ParamConverter(name="client", options={"converter":"App\ParamConverter\RegistrationTokenParamConverter"})
     *
     * @Rest\View(serializerGroups={"all", "include_id", "phone_registration", "client_account_creation"})
     *
     * @ThrowViolations()
     *
     * @param Client $client
     * @param Client $submittedClient
     * @param ConstraintViolationListInterface $violations
     *
     * @return ApiResponse
     */
    public function accountCreation(Client $client, Client $submittedClient, ConstraintViolationListInterface $violations)
    {

        if ($client->isUnverified()) {
            return $this->respondWith(null, ApiResponse::CLIENT_NOT_VERIFIED_ERROR);
        }
        if ($client->isUnconfirmed() || $client->isConfirmed()) {
            return $this->respondWith(null, ApiResponse::CLIENT_ACCOUNT_ALREADY_CREATED_ERROR);
        }
       // $device_uid=$request->headers->get('device_uid');
        //var_dump($request->headers);
        //var_dump($submittedClient->getDevice()->getDeviceUid());die;
        $device=$this->em->getRepository(Device::class)->findOneBy(array("device_uid"=>$submittedClient->getDevice()->getDeviceUid()));
      //  var_dump($device);die;
        $client
            ->setFamilyName($submittedClient->getFamilyName())
            ->setFirstName($submittedClient->getFirstName())
            ->setEmail($submittedClient->getEmail())
            ->setEmailCanonical($submittedClient->getEmailCanonical())
            ->setPassword($this->encoder->encodePassword($client, $submittedClient->getPlainPassword()))
            ->setContactPreference($submittedClient->getContactPreference())
            ->setCin($submittedClient->getCin())
            ->setStatus(Client::STATUS_UNCONFIRMED_ACCOUNT)
            ->setDevice($device)

            //->setEnabled(true)
        ;
        $this->em->persist($client);
        $this->em->flush();
        return $this->respondWith(null, ApiResponse::UPDATED);
    }

}