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
 * @Rest\Route(name="api_")
 */
class PreDeclarationController extends BaseController
{

    /**
     * @SWG\Post(
     *     tags={"Pré-déclaration"},
     *     description="predeclaration process",
     *     @SWG\Parameter(
     *        name="PreDeclaration",
     *        in="body",
     *        description="PreDeclaration object",
     *        required=true,
     *        @Model(type="App\Entity\PreDeclaration")
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Return Predclaration",
     *     )
     * )
     *
     * @Rest\Post(path="/predeclaration", name="predeclaration")
     *
     * @Rest\View()
     *
     * @ThrowViolations()
     *
     * @param ObjectManager $em
     * @param ConstraintViolationListInterface $violations
     *
     * @return ApiResponse
     */
    public function predeclaration(ObjectManager $em, ConstraintViolationListInterface $violations)
    {
        die('okey');
//        $token = $jwtEncoder->encode(['phone' => $client->getPhone()]);
//        $role = $em->getRepository('App:Role')->findOneByRole(Role::MOBILE_CLIENT);
//        $group = $em->getRepository('App:Group')->findOneByRole(Group::MOBILE_USER);
//        $verificationCode = random_int(1000, 9999);
//        $client
//            ->setEnabled(false)
//            ->setVerificationCode($verificationCode)
//            ->setStatus(Client::STATUS_UNVERIFIED_WITH_SMS)
//            ->addInsuranceType($insuranceType)
//            ->addRole($role)
//            ->setGroup($group)
//        ;
//        $em->persist($client);
//        $em->flush();
//        $smsService->sendSms($client->getPhone(), $verificationCode);
//        return $this->respondWith(['registration_token' => $token], ApiResponse::CREATED);
    }

}