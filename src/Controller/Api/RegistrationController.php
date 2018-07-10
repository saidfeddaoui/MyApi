<?php

namespace App\Controller\Api;
use App\Annotation\ThrowViolations;
use App\DTO\Api\ApiResponse;
use App\Entity\Client;
use App\Entity\Group;
use App\Entity\InsuranceType;
use App\Entity\Role;
use App\Services\SmsApiService;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Rest\Route(path="/public/registration", name="api_public_registration")
 */
class RegistrationController extends BaseController
{

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
     *        name="Client",
     *        in="body",
     *        description="Client object",
     *        required=true,
     *        @Model(type="App\Entity\Client", groups={"phone_registration"})
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Return client object after his creation",
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
     * @param SmsApiService $smsService
     * @param JWTEncoderInterface $jwtEncoder
     * @param ObjectManager $em
     * @param ConstraintViolationListInterface $violations
     *
     * @return ApiResponse
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException
     */
    public function phoneRegistration(Client $client, InsuranceType $insuranceType, SmsApiService $smsService, JWTEncoderInterface $jwtEncoder, ObjectManager $em, ConstraintViolationListInterface $violations)
    {
        $token = $jwtEncoder->encode(['phone' => $client->getPhone()]);
        $role = $em->getRepository('App:Role')->findOneByRole(Role::MOBILE_CLIENT);
        $group = $em->getRepository('App:Group')->findOneByRole(Group::MOBILE_USER);
        $verificationCode = random_int(1000, 9999);
        $client
            ->setEnabled(false)
            ->setVerificationCode($verificationCode)
            ->setStatus(Client::STATUS_UNVERIFIED_WITH_SMS)
            ->addInsuranceType($insuranceType)
            ->addRole($role)
            ->setGroup($group)
        ;
        $em->persist($client);
        $em->flush();
        $smsService->sendSms($client->getPhone(), $verificationCode);
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
     *     @SWG\Response(response=200, description="Success response returned if the verification code is correct"),
     *     @SWG\Response(response=400, description="Failure response returned if the verification code is not correct")
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
     * @param ObjectManager $em
     * @return ApiResponse
     */
    public function phoneVerification(Client $client, string $code, ObjectManager $em)
    {
        if (Client::STATUS_UNVERIFIED_WITH_SMS !== $client->getStatus() || $code !== (string)$client->getVerificationCode()) {
            return $this->respondWith(null, ApiResponse::VERIFICATION_CODE_ERROR);
        }
        $client->setStatus(Client::STATUS_VERIFIED_WITH_SMS);
        $em->persist($client);
        $em->flush();
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
     *         response=200,
     *         description="Return client object after completing his account",
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
     * @param UserPasswordEncoderInterface $encoder
     * @param ObjectManager $em
     * @param ConstraintViolationListInterface $violations
     *
     * @return ApiResponse
     */
    public function accountCreation(Client $client, Client $submittedClient, UserPasswordEncoderInterface $encoder, ObjectManager $em, ConstraintViolationListInterface $violations)
    {
        if (Client::STATUS_VERIFIED_WITH_SMS !== $client->getStatus()) {
            return $this->respondWith(null, ApiResponse::CLIENT_NOT_VERIFIED_ERROR);
        }
        $client
            ->setName($submittedClient->getName())
            ->setEmail($submittedClient->getEmail())
            ->setEmailCanonical($submittedClient->getEmailCanonical())
            ->setPassword($encoder->encodePassword($client, $submittedClient->getPlainPassword()))
            ->setContactPreference($submittedClient->getContactPreference())
            ->setStatus(Client::STATUS_UNCONFIRMED_ACCOUNT)
            ->setEnabled(true)
        ;
        $em->persist($client);
        $em->flush();
        return $this->respondWith(null, ApiResponse::UPDATED);
    }

}