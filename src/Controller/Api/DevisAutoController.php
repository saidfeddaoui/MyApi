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
     * @return ApiResponse
     */
    public function normal(DevisAuto $auto, ConstraintViolationListInterface $violations, DevisAutoApiService $Auto_api)
    {
        $societaire = $this->em->getRepository('App:Societaire')->findOneBy([ "code" =>$auto->getSocietaire()->getCode(),"type" => "AUTO" ]);
        $marque = $this->em->getRepository('App:MarqueVehicule')->findOneBy([ "id" =>$auto->getMarque()->getId()]);
        $pack = $this->em->getRepository('App:Pack')->findOneBy([ "code" =>$auto->getPack()->getCode()]);
        $devi_auto = new DevisAuto();
        $devi_auto->setNom($auto->getNom());
        $devi_auto->setPrenom($auto->getPrenom());
        $devi_auto->setTel($auto->getTel());
        $devi_auto->setEmail($auto->getEmail());
        $devi_auto->setMarque($marque);
        $devi_auto->setMarque($pack);
        $devi_auto->setSocietaire($societaire);
        $devi_auto->setCivilite($auto->getCivilite());
        $devi_auto->setW($auto->getW());
        $devi_auto->setDateMec(new \datetime($auto->getDateMec()));
        $devi_auto->setPuissance($auto->getPuissance());
        $devi_auto->setCombustion($auto->getCombustion());
        $devi_auto->setVn($auto->getVn());
        $devi_auto->setVv($auto->getVv());
        $devi_auto->setVg($auto->getVg());

        $this->em->persist($devi_auto);
        $this->em->flush();
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
     * @param  DevisAutoMesureApiService $autoMesure
     * @return ApiResponse
     */
    public function mesure(Mesure $mesure, DevisAutoMesureApiService $autoMesure, ConstraintViolationListInterface $violations)
    {
        $mesureDevis = $autoMesure->getDevisAuto($mesure);
        return $this->respondWith($mesureDevis);
    }
}