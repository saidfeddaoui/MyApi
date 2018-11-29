<?php

namespace App\Controller\Api;

use App\Annotation\ThrowViolations;
use App\DTO\Api\ApiResponse;
use App\DTO\Api\Devis\Mesure;
use App\Entity\DeviGaranties;
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
     * @ParamConverter(name="habitation", converter="fos_rest.request_body", options={"validator"={ "groups"={"devis_mrh"} }})
     * @Rest\View(serializerGroups={"all", "devis_mrh", "response_mrh"})
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
        $societaire = $this->em->getRepository('App:Societaire')->findOneBy([ "code" =>$habitation->getSocietaire()->getCode(),"type" => "MRH" ]);
        $categorie = $this->em->getRepository('App:MrhCategorie')->find($habitation->getCategorie()->getId());
        $propriete = $this->em->getRepository('App:MrhPropriete')->find($habitation->getPropriete()->getId());
        $valeurBatiment = $this->em->getRepository('App:MrhBatiment')->find($habitation->getBatiment()->getId());
        $devisHab = new DevisHabitation();
        $devisHab->setNom($habitation->getNom());
        $devisHab->setPrenom($habitation->getPrenom());
        $devisHab->setTel($habitation->getTel());
        $devisHab->setEmail($habitation->getEmail());
        $devisHab->setCivilite($habitation->getCivilite());
        $devisHab->setContenu($habitation->getContenu());
        $devisHab->setBatiment($valeurBatiment);
        $devisHab->setSocietaire($societaire);
        $devisHab->setCategorie($categorie);
        $devisHab->setPropriete($propriete);

        $em->persist($devisHab);

        $devis = $hapitation_api->getDevisHabitation($habitation);

        $reference = $devis->getTotal()->getId();
        $primeHT = $devis->getTotal()->getPrimeHT();
        $primeTTC = $devis->getTotal()->getPrimeTTC();

        $devisHab->setReference($reference);
        $devisHab->setPrimeHT($primeHT);
        $devisHab->setPrimeTTC($primeTTC);
        $this->em->flush();

        $garanties = $devis->getGaranties();
        foreach ($garanties as $garantie){
            $deviGaranties = new DeviGaranties();
            $deviGaranties->setGarantie($garantie->getGarantie());
            $deviGaranties->setAcquise($garantie->getAcquise());
            $deviGaranties->setDevis($devisHab);
            $deviGaranties->setType('DH');

            $this->em->persist($deviGaranties);
            $this->em->flush();
        }


        $idDevis =$devisHab->getId();
        $devis->getResult()->setIdDet($idDevis);
        return $this->respondWith($devis);
    }
}