<?php

namespace App\Controller\Api;
use App\Annotation\ThrowViolations;
use App\DTO\Api\ApiResponse;
use App\Entity\AssistanceRequest;
use App\Entity\InsuranceType;
use App\Event\ApplicationEvents;
use App\Event\NewAssistanceRequestEvent;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Rest\Route(path="/public/assistance", name="api_public_assistance_")
 */
class AssistanceController extends BaseController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * AssistanceController constructor.
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }
    /**
     * @SWG\Post(
     *     tags={"Demande d'assistance"},
     *     description="Add a new assistance request",
     *     @SWG\Parameter(
     *         name="X-ENTITY",
     *         in="header",
     *         type="string",
     *         default="MAMDA",
     *         description="Specify the user's Entity",
     *     ),
     *     @SWG\Parameter(
     *        name="AssistanceRequest",
     *        in="body",
     *        description="AssistanceRequest object",
     *        required=true,
     *        @Model(type="App\Entity\AssistanceRequest", groups={"new_assistance"})
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
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
     *             "Not Found Error (Http Code: 404)":
     *             {
     *                 "code"=404,
     *                 "status"="Resource Not Found"
     *             }
     *         }
     *     )
     * )
     *
     * @Rest\Post(name="new")
     * @ParamConverter(name="assistanceRequest", converter="fos_rest.request_body", options={"validator"={ "groups"={"new_assistance"} }})
     * @ParamConverter(name="insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @Rest\View()
     * @ThrowViolations()
     *
     * @param AssistanceRequest $assistanceRequest
     * @param InsuranceType $insuranceType
     * @param ConstraintViolationListInterface $violations
     * @return ApiResponse
     */
    public function newAssistance(AssistanceRequest $assistanceRequest, InsuranceType $insuranceType, ConstraintViolationListInterface $violations)
    {
        $assistanceRequest->setInsuranceType($insuranceType);
        $this->em->persist($assistanceRequest);
        $this->em->flush();
        $event = new NewAssistanceRequestEvent($assistanceRequest);
        $this->eventDispatcher->dispatch(ApplicationEvents::NEW_ASSISTANCE_REQUEST, $event);
        return $this->respondWith(null, ApiResponse::CREATED);
    }

}