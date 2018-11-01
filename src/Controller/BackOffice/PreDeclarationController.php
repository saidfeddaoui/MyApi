<?php

namespace App\Controller\BackOffice;

use App\Entity\ChoixMDR;
use App\Entity\InsuranceType;
use App\Entity\Notification;
use App\Entity\NotificationDetail;
use App\Entity\PreDeclaration;
use App\Event\AcceptPreDeclarationEvent;
use App\Event\ApplicationEvents;
use App\Event\RejectPreDeclarationEvent;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\PreDeclarationTriggerApiService;

/**
 * @Route(path="/pre_declarations", name="pre_declarations_")
 * @Security("is_granted('ROLE_PRE_DECLARATION')")
 *
 * @Breadcrumb(title="Accueil")
 * @Breadcrumb(title="Pré-déclarations")
 */
class PreDeclarationController extends Controller
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
     * PreDeclarationController constructor.
     *
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }
    /**
     * @Route(path="/in_progress", name="in_progress", options={"expose"=true})
     * @ParamConverter(name="insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @Breadcrumb(title="En cours")
     *
     * @param InsuranceType $insuranceType
     * @return Response
     */
    public function in_progress(InsuranceType $insuranceType)
    {
        $preDeclarations = $this->em->getRepository('App:PreDeclaration')->findAll();

        return $this->render('pre_declaration/index.html.twig', [
            'page_title' => 'Gestion des pré-déclarations',
            'page_subtitle' => '(En cours)',
            'portlet_title' => 'Liste des pré-déclarations en cours',
            'preDeclarations' => $preDeclarations,
        ]);
    }
    /**
     * @Route(path="/display/details/{id}", name="display_details", requirements={"id":"\d+"}, options={"expose"=true})
     * @ParamConverter(name="insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @Breadcrumb(title="{preDeclaration.contrat.client.firstName}")
     *
     * @param  PreDeclaration $preDeclaration
     * @return Response
     */
    public function displayDetails(PreDeclaration $preDeclaration)
    {
        $attachements = $this->em->getRepository('App:TiersAttachment')->findByPreDeclaration($preDeclaration);
        $sinistre = $this->em->getRepository('App:ItemList')->findOneBy(['type' => 'sinistre']);
        $sinistres = $this->em->getRepository('App:Item')->findBy(['itemList'=>$sinistre->getId()]);
        $listMDR=$this->em->getRepository('App:ModeReparation')->findAll();

        return $this->render('pre_declaration/display_details.html.twig', [
            'page_title' => 'Gestion des pré-déclarations',
            'page_subtitle' => '',
            'portlet_title' => "Pré-déclaration de {$preDeclaration->getContrat()->getClient()->getFirstName()}",
            'preDeclaration' => $preDeclaration,
            'attachements' => $attachements,
            'sinistres' => $sinistres,
            'list_mdr' => $listMDR,
        ]);
    }
    /**
     * @Route(path="/rejected", name="rejected", options={"expose"=true})
     * @ParamConverter(name="insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @Breadcrumb(title="Rejetées")
     *
     * @param InsuranceType $insuranceType
     * @return Response
     */
    public function rejected(InsuranceType $insuranceType)
    {
        $preDeclarations = $this->em->getRepository('App:PreDeclaration')->findByStatusAndInsuranceType(
            PreDeclaration::STATUS_REJECTED,
            $insuranceType
        );

        return $this->render('pre_declaration/index.html.twig', [
            'page_title' => 'Gestion des pré-déclarations',
            'page_subtitle' => '(Rejetées)',
            'portlet_title' => 'Liste des pré-déclarations rejetées',
            'preDeclarations' => $preDeclarations,
        ]);
    }
    /**
     * @Route(path="/accepted", name="accepted", options={"expose"=true})
     * @ParamConverter(name="insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @Breadcrumb(title="Acceptées")
     *
     * @param InsuranceType $insuranceType
     * @return Response
     */
    public function accepted(InsuranceType $insuranceType)
    {
        $preDeclarations = $this->em->getRepository('App:PreDeclaration')->findByStatusAndInsuranceType(
            PreDeclaration::STATUS_ACCEPTED,
            $insuranceType
        );
        return $this->render('pre_declaration/index.html.twig', [
            'page_title' => 'Gestion des pré-déclarations',
            'page_subtitle' => '(Acceptées)',
            'portlet_title' => 'Liste des pré-déclarations acceptées',
            'preDeclarations' => $preDeclarations,
        ]);
    }
    /**
     * @Route(path="/details/{id}", name="details", requirements={"id":"\d+"}, options={"expose"=true})
     *
     * @param  PreDeclaration $preDeclaration
     * @return Response
     */
    public function details(PreDeclaration $preDeclaration)
    {
        $attachements = $this->em->getRepository('App:TiersAttachment')->findByPreDeclaration($preDeclaration);

        return $this->render('pre_declaration/details.html.twig', [
            'preDeclaration' => $preDeclaration,
            'attachements' => $attachements,

        ]);
    }
    /**
     * @Route(path="/reject/{id}", name="reject", requirements={"id":"\d+"}, options={"expose"=true})
     *
     * @param  PreDeclaration $preDeclaration
     * @param  Request $request
     * @return Response
     */
    public function reject(PreDeclaration $preDeclaration, Request $request,PreDeclarationTriggerApiService $pdtas)
    {
        if (PreDeclaration::STATUS_IN_PROGRESS !== $preDeclaration->getStatus()) {
            return $this->json(['message' => 'la pré-declaration doit avoir le status en cours pour la rejeter'], 400);
        }
        if (!$request->request->has('description')) {
            return $this->json(['message' => 'la description est obligatoire pour rejeter une pré-declaration'], 400);
        }
        $motif=$request->request->get('description');
        $preDeclaration
            ->setStatus(PreDeclaration::STATUS_REJECTED)
            ->setDescription($request->request->get($motif))
        ;

        $idpredeclaration=$preDeclaration->getId();
        $preDeclarationInfo= array(
            "IdPreDeclaration"=>$idpredeclaration,
            "Statut"=>"r"
        );

        $dataPre=json_decode(json_encode($preDeclarationInfo),true);;

        $resp = $pdtas->triggerPredeclaration($dataPre);

        //return $this->json(['Code' => $resp->code,'message' => $resp->code]);


        if ($resp->code == "200") {

        $client = $preDeclaration->getClient();
        $idSocietaire = $preDeclaration->getContrat()->getIdSocietaire();
        $sujet="Pré-déclaration";
        $message="Votre pré-déclaration a été refusée";

        $notification = new Notification();
        $notification->setIdSocietaire($idSocietaire);
        $notification->setSujet($sujet);
        $notification->setMessage($message);
        $notification->setStatut(false);
        $notification->setClient($client);
        $notification->setPredeclaration($preDeclaration);
        $notification->setType("R");
        $notification->setDateCreation(new \dateTime("now"));
        $this->em->persist($preDeclaration);
        $this->em->persist($notification);
        $this->em->flush();

        //pour avoir id notification
        $datenow=new \dateTime("now");
        $now=$datenow->format("Y-m-d");

        $notification_detail = new NotificationDetail();
        $notification_detail->setLibelle("date");
        $notification_detail->setValeur($now);
        $notification_detail->setNotification($notification);
        $notification_detail->setDateCreation(new \dateTime("now"));

        $notification_detail2 = new NotificationDetail();
        $notification_detail2->setLibelle("Motif");
        $notification_detail2->setValeur($motif);
        $notification_detail2->setNotification($notification);
        $notification_detail2->setDateCreation(new \dateTime("now"));

        $this->em->persist($notification_detail);
        $this->em->persist($notification_detail2);
        $this->em->flush();

        $event = new RejectPreDeclarationEvent($preDeclaration);
        $this->eventDispatcher->dispatch(ApplicationEvents::REJECT_PRE_DECLARATION, $event);
        $this->json(['code'=>'ok','message' => 'la pré-declaration a été rejetée avec succès']);
        }else{
            return $this->json(['code'=>'ko','message' => $resp->message]);
        }
    }



    /**
     * @Route(path="/accept/{id}", name="accept", requirements={"id":"\d+"}, options={"expose"=true})
     *
     * @param  PreDeclaration $preDeclaration
     * @param  Request $request
     * @return Response
     */
    public function accept(PreDeclaration $preDeclaration,PreDeclarationTriggerApiService $pdtas,Request $request)
    {
        if (PreDeclaration::STATUS_IN_PROGRESS !== $preDeclaration->getStatus()) {
            return $this->json(['message' => 'la pré-declaration doit avoir le status en cours pour l\'accepter'], 400);
        }
        $preDeclaration->setStatus(PreDeclaration::STATUS_ACCEPTED);

        $idpredeclaration=$preDeclaration->getId();
        $preDeclarationInfo= array(
            "IdPreDeclaration"=>$idpredeclaration,
            "Statut"=>"r"
        );

        $valeursMDR=$request->request->get("mdr");

        $choixMDR = $this->em->getRepository('App:ChoixMDR')->findByPreDeclaration($preDeclaration);


        foreach ($choixMDR as $objetMDR){
            //if(!in_array($objetMDR->getId(),array_values($valeursMDR))){
                $objetMDR->setStatut(false);
            //}
        }
        $this->em->flush();


        $dataPre=json_decode(json_encode($preDeclarationInfo),true);;

        $resp = $pdtas->triggerPredeclaration($dataPre);

        //return $this->json(['Code' => $resp->code,'message' => $resp->code]);

        if ($resp->code == "200"){

        $client = $preDeclaration->getClient();
        $idSocietaire = $preDeclaration->getContrat()->getIdSocietaire();
        $sujet="Pré-déclaration";
        $message="Votre pré-déclaration a été acceptée";


        $notification = new Notification();
        $notification->setIdSocietaire($idSocietaire);
        $notification->setSujet($sujet);
        $notification->setMessage($message);
        $notification->setStatut(false);
        $notification->setClient($client);
        $notification->setPredeclaration($preDeclaration);
        $notification->setType("V");
        $notification->setDateCreation(new \dateTime("now"));

        $this->em->persist($preDeclaration);
        $this->em->persist($notification);
        $this->em->flush();


        //pour avoir id notification
        $datenow=new \dateTime("now");
        $now=$datenow->format("Y-m-d");
        $notification_detail = new NotificationDetail();
        $notification_detail->setLibelle("date");
        $notification_detail->setValeur($now);
        $notification_detail->setNotification($notification);
        $notification_detail->setDateCreation(new \dateTime("now"));;

        $this->em->persist($notification_detail);
        $this->em->flush();

        $event = new AcceptPreDeclarationEvent($preDeclaration);
        $this->eventDispatcher->dispatch(ApplicationEvents::ACCEPT_PRE_DECLARATION, $event);
        return $this->json(['code'=>'ok','message' => 'la pré-declaration a été acceptée avec succès']);

        }else{
            return $this->json(['code'=>'ko','message' => $resp->message]);
        }



    }



    /**
     * @Route(path="/update/{id}", name="update", options={"expose"=true})
     *
     * @param  PreDeclaration $preDeclaration
     * @param  Request $request
     * @return JsonResponse
     */
    public function update(PreDeclaration $preDeclaration,Request $request)
    {
        $sinistretype = $request->request->get('sinistretype');
        $sinistre = $this->em->getRepository('App:Item')->find($sinistretype);
        $adress = $request->request->get("adress");
        $nbv = $request->request->get('nbv');
        $nbi = $request->request->get('nbi');
        $description = $request->request->get('description');
         if ($sinistre){
             $preDeclaration->setTypeSinistre($sinistre);
         }
        $preDeclaration->getCircumstance()->setAdress($adress);
        $preDeclaration->setNbVehicule($nbv);
        $preDeclaration->setNbInjured($nbi);
        $preDeclaration->setDescription($description);

        $this->em->flush();
        return  new JsonResponse([
            'id' => $preDeclaration->getId(),
            'message' => 'pre_declarations modifiée avec succès',
        ]);


    }

}