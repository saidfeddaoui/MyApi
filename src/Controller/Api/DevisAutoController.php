<?php

namespace App\Controller\Api;

use App\Annotation\ThrowViolations;
use App\DTO\Api\ApiResponse;
use App\DTO\Api\Devis\Mesure;
use App\Entity\DevisAuto;
use App\Services\DevisAutoApiService;
use App\Services\DevisAutoMesureApiService;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Rest\Route(path="/public/devis", name="api_devis_auto_")
 */
class DevisAutoController extends BaseController
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
     *     tags={"Devis Automobile"},
     *     description="devis auto process",
     *     @SWG\Parameter(
     *        name="DevisAuto",
     *        in="body",
     *        description="devis_auto object",
     *        required=true,
     *        @Model(type="App\Entity\DevisAuto", groups={"devis_auto"})
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
     * @Rest\Post(path="/auto",name="devis_auto")
     * @ParamConverter(name="auto", converter="fos_rest.request_body", options={"validator"={ "groups"={"devis_auto"} }})
     * @Rest\View(serializerGroups={"all", "devis_auto", "response_auto"})
     *
     * @ThrowViolations()
     *
     * @param  DevisAuto $auto
     * @param  ConstraintViolationListInterface $violations
     * @param  DevisAutoApiService $Auto_api
     * @param  ObjectManager $em
     * @return ApiResponse
     */
    public function normal(ObjectManager $em, DevisAuto $auto, ConstraintViolationListInterface $violations, DevisAutoApiService $Auto_api)
    {
        $societaire = $em->getRepository('App:Societaire')->findOneByCode($auto->getSocietaire()->getCode());
        $devi_auto = new DevisAuto();
        $devi_auto->setNom($auto->getNom());
        $devi_auto->setPrenom($auto->getPrenom());
        $devi_auto->setTel($auto->getTel());
        $devi_auto->setEmail($auto->getEmail());
        $devi_auto->setCivilite($auto->getCivilite());
        $devi_auto->setSocietaire($societaire);
        $em->persist($devi_auto);
        $em->flush();
        $devis = $Auto_api->getDevisAuto($auto);
        return $this->respondWith($devis);
    }

    /**
     * @SWG\Post(
     *     tags={"Devis Automobile"},
     *     description="devis auto Mesure process",
     *     @SWG\Parameter(
     *        name="DevisAutoMesure",
     *        in="body",
     *        description="devis_auto_mesure object",
     *        required=true,
     *        @Model(type="App\DTO\Api\Devis\Mesure")
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
     * @Rest\Post(path="/auto/mesure",name="devis_auto_mesure")
     * @ParamConverter(name="mesure", converter="fos_rest.request_body", options={"validator"={ "groups"={"auto_mesure"} }})
     * @Rest\View(serializerGroups={"all","response_mesure"})
     *
     * @ThrowViolations()
     *
     * @param  Mesure $mesure
     * @param  ConstraintViolationListInterface $violations
     * @param  DevisAutoMesureApiService $AutoMmesure
     * @param  ObjectManager $em
     * @return ApiResponse
     */
    public function mesure(ObjectManager $em, Mesure $mesure, ConstraintViolationListInterface $violations, DevisAutoMesureApiService $AutoMmesure)
    {
        $mesure_devis = $AutoMmesure->getDevisAuto($mesure);
        return $this->respondWith($mesure_devis);
    }
}