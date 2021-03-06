<?php

namespace App\Controller\Api;

use App\DTO\Api\ApiResponse;
use App\Entity\ChoixMDR;
use App\Entity\CircumstanceAttachment;
use App\Entity\Client;
use App\Entity\InsuranceType;
use App\Entity\Notification;
use App\Entity\NotificationDetail;
use App\Entity\PreDeclaration;
use App\Entity\TiersAttachment;
use App\Event\ApplicationEvents;
use App\Event\NewPreDeclarationEvent;
use App\Exception\MissingRequiredFileException;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Ramsey\Uuid\Uuid;
use App\Utils\ObjectMapper;
/**
 * @Rest\Route(path="/pre_declaration", name="api_pre_declaration_")
 */
class PreDeclarationController extends BaseController
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
     * @var $config
     */
    private $configNotif = null;

    /**
     * @var $config
     */
    private $configNotifDetail = null;


    /**
     * RegistrationController constructor.
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->configNotif = array('EntityName' => 'Notification','NameSpace' => '\App\Entity\\');
        $this->configNotifDetail = array('EntityName' => 'NotificationDetail','NameSpace' => '\App\Entity\\');
    }

    /**
     * @SWG\Post(
     *     tags={"Pré-déclaration"},
     *     description="Pre-declaration process",
     *     @SWG\Parameter(
     *         name="Authorization",
     *         in="header",
     *         type="string",
     *         required=true,
     *         description="Bearer auth",
     *     ),
     *     @SWG\Parameter(
     *         name="X-ENTITY",
     *         in="header",
     *         type="string",
     *         default="MAMDA",
     *         description="Specify the user's Entity",
     *     ),
     *     @SWG\Parameter(
     *        name="PreDeclaration",
     *        in="body",
     *        description="PreDeclaration object",
     *        required=true,
     *        @Model(type="App\Entity\PreDeclaration", groups={"client_pre_declaration"})
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
     * @Rest\Post(name="predeclaration")
     * @ParamConverter(
     *     name="preDeclaration",
     *     options={
     *         "converter":"App\ParamConverter\PreDeclarationParamConverter",
     *         "validator"={"groups"={"client_pre_declaration"}}
     *     }
     * )
     * @ParamConverter(name="insuranceType", options={"converter":"App\ParamConverter\InsuranceTypeParamConverter"})
     *
     * @Rest\View(serializerGroups={"all", "show_predeclaration", "client_pre_declaration"})
     *
     * @param PreDeclaration $preDeclaration
     * @param InsuranceType $insuranceType
     * @return ApiResponse
     */
    public function preDeclaration(PreDeclaration $preDeclaration, InsuranceType $insuranceType)
    {
        $inType = $this->em->getRepository("App:InsuranceType")->findOneById($insuranceType->getId());
        $preDeclaration->setStatus(PreDeclaration::STATUS_IN_PROGRESS)->setInsuranceType($inType);
        $this->em->persist($preDeclaration);
        $this->em->flush();


        $modesReparation=$this->em->getRepository("App:ModeReparation")->findAll();

        foreach ($modesReparation as $mode){
            $choixMDR=new ChoixMDR();
            $choixMDR->setPreDeclaration($preDeclaration);
            $choixMDR->setStatut(true);
            $choixMDR->setModeReparation($mode);
            $choixMDR->setClient($preDeclaration->getClient());
            $this->em->persist($choixMDR);
            $this->em->flush();
        }



        $client = $preDeclaration->getClient();
        $idSocietaire = $preDeclaration->getContrat()->getIdSocietaire();
        $sujet="Pré-déclaration";
        $message="Nous avons bien reçu votre dossier de pré-déclaration";
        $datenow=new \dateTime("now");
        $now=$datenow->format("Y-m-d");

        $data = array(
            "idSocietaire"=>$idSocietaire,
            "sujet"=>$sujet,
            "message"=>$message,
            "statut"=>false,
            "client"=>$client,
            "predeclaration"=>$preDeclaration,
            "type"=>"I",
            "dateCreation"=>new \dateTime("now"));

        $notification = ObjectMapper::mapObjectToEntity($data,$this->configNotif);
        $this->em->persist($notification);
        $this->em->flush();

        $data = array(
            "libelle"=>"date",
            "valeur"=>$now,
            "notification"=>$notification,
            "dateCreation"=>new \dateTime("now"));


        $notification_detail = ObjectMapper::mapObjectToEntity($data,$this->configNotifDetail);
        $this->em->persist($notification_detail);
        $this->em->flush();

        $tiersAttachement = $preDeclaration->getImages();

        foreach ($tiersAttachement as $attachement){
       //  $attachement=$this->em->getRepository("App:TiersAttachment")->findOneById($attachement->getId());
         $attachement->setPreDeclaration($preDeclaration);
        }

        $event = new NewPreDeclarationEvent($preDeclaration);
        $this->eventDispatcher->dispatch(ApplicationEvents::NEW_PRE_DECLARATION, $event);



        return $this->respondWith($preDeclaration, ApiResponse::CREATED);
    }
    /**
     * @SWG\Post(
     *     tags={"Pré-déclaration"},
     *     description="Upload circumstance's attachments",
     *     consumes={"multipart/form-data"},
     *     @SWG\Parameter(
     *         name="Authorization",
     *         in="header",
     *         type="string",
     *         required=true,
     *         description="Bearer auth",
     *     ),
     *     @SWG\Parameter(
     *         name="attachments[]",
     *         in="formData",
     *         type="file",
     *         required=true,
     *         description="Files",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Return Attachment",
     *     )
     * )
     *
     * @Rest\Post(path="/circumstances/attachments", name="circumstances_attachments")
     * @Rest\View()
     *
     * @param Request $request
     * @return ApiResponse
     */
    public function uploadCircumstanceAttachments(Request $request)
    {
        $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img/circumstances';
        $attachments = $request->files->get('attachments', []);
        $photos = [];
        foreach ($attachments as $attachment) {
            /**
             * @var UploadedFile $attachment
             */
            $imageFile = $attachment->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $attachment->guessExtension());
            $circumstanceAttachment = new CircumstanceAttachment($imageFile->getBasename());
            $this->em->persist($circumstanceAttachment);
            $this->em->flush();
            $photos[] = $circumstanceAttachment->getId();
        }
        return $this->respondWith($photos);
    }
    /**
     * @SWG\Post(
     *     tags={"Pré-déclaration"},
     *     description="upload tiers's attachments",
     *     consumes={"multipart/form-data"},
     *     @SWG\Parameter(
     *         name="Authorization",
     *         in="header",
     *         type="string",
     *         required=false,
     *         description="Bearer auth",
     *     ),
     *     @SWG\Parameter(
     *         name="permis",
     *         in="formData",
     *         type="file",
     *         required=false,
     *         description="Driver's license picture",
     *     ),
     *     @SWG\Parameter(
     *         name="attestation_assurance",
     *         in="formData",
     *         type="file",
     *         required=false,
     *         description="Insurance's attestation picture",
     *     ),
     *    @SWG\Parameter(
     *         name="constat_amiable",
     *         in="formData",
     *         type="file",
     *         required=false,
     *         description="Friendly report picture",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Return attachments list",
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Failure response",
     *         @Model(type="App\DTO\Api\ApiResponse", groups={"all"}),
     *         examples={
     *             "Missing requirement Error (Http Code: 400)":
     *             {
     *                 "code"=408,
     *                 "status"="Missing required file: ..."
     *             }
     *         }
     *     )
     * )
     *
     * @Rest\Post(path="/tiers/attachments", name="tiers_attachments")
     * @Rest\View()
     *
     * @param Request $request
     * @return ApiResponse
     * @throws MissingRequiredFileException
     */
    public function uploadTiersAttachments(Request $request)
    {
       // $types ="predeclaration";
        if (!count($request->files)) {
            throw new MissingRequiredFileException("no image");
        }

        $directory = $this->get('kernel')->getProjectDir() . '/public/img/tiers';
        $tiersAttachments = [];

       // var_dump(count($request->files));
        //die();

        foreach ($request->files as $type) {
             //en recois le nom de image xxx-12.png (exp)
            //on prend juste la premier partie avant - pour la mettre comme type(categorie)
            $img_arr = explode("-", $type->getClientOriginalName());
            $types=$img_arr[0];
            /**
             * @var UploadedFile $attachment
             */
            $attachment = $type;
            $file = $attachment->move($directory, Uuid::uuid4()->toString() . '.' . $attachment->guessExtension());
            $tiersAttachment = new TiersAttachment($types, $file->getBasename());
            $this->em->persist($tiersAttachment);
            $this->em->flush();
            $tiersAttachments[] = ['id' => $tiersAttachment->getId(), 'type' => $types];
        }
        return $this->respondWith($tiersAttachments);
    }

    /**
     * @SWG\Post(
     *     tags={"Pré-déclaration"},
     *     description="preDeclaration list",
     *     @SWG\Parameter(
     *         name="Authorization",
     *         in="header",
     *         type="string",
     *         required=true,
     *         description="Bearer auth",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Return attachments list",
     *     )
     * )
     *
     * @Rest\Post(path="/list/{client_id}", name="list")
     * @Rest\View(serializerGroups={"all","listPreDeclaration"})
     * @param $client_id
     * @return ApiResponse
     */
    public function listPreDeclaration($client_id)
    {
        $listPredeclaration = $this->em->getRepository("App:PreDeclaration")->findByClient($client_id);
        return $this->respondWith($listPredeclaration);
    }




  /*  public function uploadTiersAttachments(Request $request)
    {
        foreach (TiersAttachment::getRequiredAttachmentTypes() as $type) {
            if (!$request->files->has($type)) {
                throw new MissingRequiredFileException($type);
            }
        }
        $directory = $this->get('kernel')->getProjectDir() . '/public/img/tiers';
        $tiersAttachments = [];
        foreach (TiersAttachment::getAttachmentTypes() as $type) {

            $attachment = $request->files->get($type);

            $file = $attachment->move($directory, Uuid::uuid4()->toString() . '.' . $attachment->guessExtension());
            $tiersAttachment = new TiersAttachment($type, $file->getBasename());
            $this->em->persist($tiersAttachment);
            $this->em->flush();
            $tiersAttachments[] = ['id' => $tiersAttachment->getId(), 'type' => $type];
        }
        return $this->respondWith($tiersAttachments);
    }
    */

}