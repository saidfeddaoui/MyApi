<?php

namespace App\Controller\Api;

use App\Annotation\ThrowViolations;
use App\DTO\Api\ApiResponse;
use App\Entity\DevisSatisfaction;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Rest\Route(path="/public/devis", name="api_devis_auto_")
 */
class DevisSatisfactionController extends BaseController
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
     *     tags={"Devis Satisfaction"},
     *     description="devis satisfaction",
     *     @SWG\Parameter(
     *        name="DevisSatisfaisant",
     *        in="body",
     *        description="Satisfaction object",
     *        required=true,
     *        @Model(type="App\Entity\DevisSatisfaction", groups={"devis_accepted"})
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
     * @Rest\Post(path="/satisfied",name="satisfied")
     * @ParamConverter(name="devisAccepted", converter="fos_rest.request_body", options={"validator"={ "groups"={"devis_accepted"} }})
     * @Rest\View(serializerGroups={"all", "devis_accepted"})
     *
     * @ThrowViolations()
     *
     * @param  ConstraintViolationListInterface $violations
     * @param  DevisSatisfaction $devisAccepted
     * @return ApiResponse
     */
    public function accepted(DevisSatisfaction $devisAccepted, ConstraintViolationListInterface $violations)
    {

        if (($devisAccepted->getDevisAuto()->getId() == false) && ($devisAccepted->getDevisHabitation()->getId()== false)){

            return $this->respondWith(["Message"=>"vous devez remplire le devis "],ApiResponse::NOT_FOUND);
        }

        $auto =  strtoupper($devisAccepted->getAuto());

        $devisSatisfaction = new DevisSatisfaction();
        $devisSatisfaction->setEcheanceDate($devisAccepted->getEcheanceDate());
        $devisSatisfaction->setAgence($devisAccepted->getAgence());
        $devisSatisfaction->setComment($devisAccepted->getComment());
        $devisSatisfaction->setAuto($auto);
        $devisSatisfaction->setStatut(true);

        if ($auto == "DA"){
            $da_id = $devisAccepted->getDevisAuto()->getId();
            $devis = $this->em->getRepository("App:DevisAuto")->find($da_id);
            $devisSatisfaction->setDevisAuto($devis);
            $devisSatisfaction->setDevisHabitation(null);
        }else{

            $da_id = $devisAccepted->getDevisHabitation()->getId();

            $devis = $this->em->getRepository("App:DevisHabitation")->find($da_id);

            $devisSatisfaction->setDevisHabitation($devis);
            $devisSatisfaction->setDevisAuto(null);
        }
        $this->em->persist($devisSatisfaction);
        $this->em->flush();
        return $this->respondWith();
    }

    /**
     * @SWG\Post(
     *     tags={"Devis Satisfaction"},
     *     description="Non satisfaisant",
     *     @SWG\Parameter(
     *        name="DevisNonSatisfaisant",
     *        in="body",
     *        description="Satisfaction object",
     *        required=true,
     *        @Model(type="App\Entity\DevisSatisfaction", groups={"devis_refused"})
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
     * @Rest\Post(path="/unsatisfied",name="unsatisfied")
     * @ParamConverter(name="devisRejected", converter="fos_rest.request_body", options={"validator"={ "groups"={"devis_refused"} }})
     * @Rest\View(serializerGroups={"all", "devis_refused"})
     *
     * @ThrowViolations()
     *
     * @param  ConstraintViolationListInterface $violations
     * @param  DevisSatisfaction $devisRejected
     * @return ApiResponse
     */
    public function rejected(DevisSatisfaction $devisRejected, ConstraintViolationListInterface $violations,ObjectManager $em)
    {

        $auto =  strtoupper($devisRejected->getAuto());

        if (($devisRejected->getDevisAuto()->getId() == false) && ($devisRejected->getDevisHabitation()->getId()== false)){

            return $this->respondWith(["Message"=>"vous devez remplire le devis "],ApiResponse::NOT_FOUND);
        }

        $devisSatisfaction = new DevisSatisfaction();
        $devisSatisfaction->setComment($devisRejected->getComment());
        $devisSatisfaction->setAuto($auto);
        $devisSatisfaction->setStatut(false);

        $idlist= $devisRejected->getRaison()->getId();
        $list = $em->getRepository('App:ListSatisfaction')->find($idlist);
        $devisSatisfaction->setRaison($list);

        if ($auto == "DA"){
            $da_id = $devisRejected->getDevisAuto()->getId();
            $devis = $this->em->getRepository("App:DevisAuto")->find($da_id);
            $devisSatisfaction->setDevisAuto($devis);
            $devisSatisfaction->setDevisHabitation(null);
        }else{
            $da_id = $devisRejected->getDevisHabitation()->getId();
            $devis = $this->em->getRepository("App:DevisHabitation")->find($da_id);
            $devisSatisfaction->setDevisHabitation($devis);
            $devisSatisfaction->setDevisAuto(null);
        }

        $this->em->persist($devisSatisfaction);
        $this->em->flush();
        return $this->respondWith();
    }


    /**
     * @SWG\Get(
     *     tags={"Devis Satisfaction"},
     *     description="liste satisfaisant",
     *     @SWG\Parameter(
     *         name="lang",
     *         in="query",
     *         type="string",
     *         default="fr",
     *         description="Specify the user's language"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Slider successfully returned"
     *     )
     * )
     *
     * @Rest\Get(path = "/list", name = "list")
     * @Rest\View(serializerGroups={"all", "devis_list"})
     *
     * @param ObjectManager $em
     * @return ApiResponse
     */
    public function listSatisfaction(ObjectManager $em)
    {
        $list = $em->getRepository('App:ListSatisfaction')->findAll();
        return $this->respondWith($list);
    }


}