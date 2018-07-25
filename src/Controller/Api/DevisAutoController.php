<?php

namespace App\Controller\Api;

use App\Annotation\ThrowViolations;
use App\DTO\Api\ApiResponse;
use App\Entity\DevisAuto;
use App\Services\DevisAutoApiService;
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
     *     tags={"DevisAuto"},
     *     description="devis auto process",
     *     @SWG\Parameter(
     *        name="DevisAuto",
     *        in="body",
     *        description="DevisAuto object",
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
    public function devisAuto(ObjectManager $em, DevisAuto $auto, ConstraintViolationListInterface $violations, DevisAutoApiService $Auto_api)
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
}