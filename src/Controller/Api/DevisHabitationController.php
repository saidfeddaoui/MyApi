<?php

namespace App\Controller\Api;

use App\Annotation\ThrowViolations;
use App\DTO\Api\ApiResponse;
use App\DTO\Api\Devis\Mesure;
use App\Entity\DevisAuto;
use App\Entity\DevisHabitation;
use App\Services\DevisAutoApiService;
use App\Services\DevisAutoMesureApiService;
use App\Services\DevisHabitationApiService;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Rest\Route(path="/public/devis", name="api_devis_habitation_")
 */
class DevisHabitationController extends BaseController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * DevisAutoController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @SWG\Post(
     *     tags={"Devis Habitation"},
     *     description="devis auto process",
     *     @SWG\Parameter(
     *        name="DevisHabitation",
     *        in="body",
     *        description="devis_habitation object",
     *        required=true,
     *        @Model(type="App\Entity\DevisHabitation", groups={"devis_mrh"})
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
     * @Rest\Post(path="/habitation",name="devis_habitation")
     * @ParamConverter(name="auto", converter="fos_rest.request_body", options={"validator"={ "groups"={"devis_mrh"} }})
     * @Rest\View(serializerGroups={"all", "devis_mrh"})
     *
     * @ThrowViolations()
     *
     * @param  DevisHabitation $habitation
     * @param  ConstraintViolationListInterface $violations
     * @param  DevisHabitationApiService $hapitation_api
     * @param  ObjectManager $em
     * @return ApiResponse
     */
    public function normal(ObjectManager $em, DevisHabitation $habitation, ConstraintViolationListInterface $violations, DevisHabitationApiService $hapitation_api)
    {
        $habitation->setNom($habitation->getNom());
        $habitation->setPrenom($habitation->getPrenom());
        $habitation->setTel($habitation->getTel());
        $habitation->setEmail($habitation->getEmail());
        $habitation->setCivilite($habitation->getCivilite());
        $em->persist($habitation);
        $em->flush();
        $devis = $hapitation_api->getDevisHabitation($habitation);
        return $this->respondWith($devis);
    }
}