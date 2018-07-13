<?php

namespace App\Controller\Api;
use App\DTO\Api\ApiResponse;
use App\Entity\Circumstance;
use App\Entity\CircumstanceAttachment;
use App\Entity\PreDeclaration;
use App\Entity\TiersAttachment;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Ramsey\Uuid\Uuid;

/**
 * @Rest\Route(path="/pre_declaration", name="api_")
 */
class PreDeclarationController extends BaseController
{

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
     *        name="PreDeclaration",
     *        in="body",
     *        description="PreDeclaration object",
     *        required=true,
     *        @Model(type="App\Entity\PreDeclaration", groups={"client_pre_declaration"})
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Return Predclaration",
     *     )
     * )
     *
     * @Rest\Post(path="/", name="predeclaration")
     * @ParamConverter(name="preDeclaration", converter="fos_rest.request_body", options={"validator"={ "groups"={"client_pre_declaration"} }})
     * @Rest\View(serializerGroups={"all","show_predeclaration","client_pre_declaration"})
     *
     *
     * @param PreDeclaration $preDeclaration
     * @param ObjectManager $em
     * @param ConstraintViolationListInterface $violations
     *
     * @return ApiResponse
     */
    public function predeclaration(PreDeclaration $preDeclaration, ObjectManager $em, ConstraintViolationListInterface $violations)
    {
        $ids_circumstance = $preDeclaration->getCircumstance()->getPhotos()->map(function ($c) { return $c->getId(); })->toArray();
        $ids_damaged      = $preDeclaration->getVehiculeDamage()->getDamagedParts()->map(function ($c) { return $c->getId(); })->toArray();
        $ids_tiers        = $preDeclaration->getTiers()->getAttachments()->map(function ($c) { return $c->getId(); })->toArray();
        $circumstanceAttachments = $em->getRepository('App:CircumstanceAttachment')->findByIds($ids_circumstance);
        $damagedParts     = $em->getRepository('App:VehiculeComponent')->findByIds($ids_damaged);
        $tiersAttachment= $em->getRepository('App:TiersAttachment')->findByIds($ids_tiers);
        $marque   = $em->getRepository('App:MarqueVehicule')->findOneById($preDeclaration->getIdentification()->getMarque()->getId());
        $modele   = $em->getRepository('App:ModeleVehicule')->findOneById($preDeclaration->getIdentification()->getModele()->getId());
        $ville    = $em->getRepository('App:Ville')->findOneById($preDeclaration->getCircumstance()->getVille()->getId());
        $contrat  = $em->getRepository('App:Contract')->findOneById($preDeclaration->getContract()->getId());
        $Sceanrio = $em->getRepository('App:Item')->findOneById($preDeclaration->getScenario()->getId());

        $preDeclaration->getIdentification()
            ->setMarque($marque)
            ->setModele($modele)
        ;
        $preDeclaration->getCircumstance()
            ->setVille($ville)
        ;
        $preDeclaration->setContract($contrat);
        $preDeclaration->setScenario($Sceanrio);
        $preDeclaration->getCircumstance()->setPhotos($circumstanceAttachments);
        $preDeclaration->getVehiculeDamage()->setDamagedParts($damagedParts);
        $preDeclaration->getTiers()->setAttachments($tiersAttachment);

        $em->persist($preDeclaration);
        $em->flush();
        return $this->respondWith($preDeclaration);
    }

    /**
     * @SWG\Post(
     *     tags={"circumstanceAttachement"},
     *     description="upload cricumstance",
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
     *         description="Bearer auth",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Return Attachment",
     *     )
     * )
     *
     * @Rest\Post(path="/circumstances/attachements", name="circumstances_attachements")
     * @Rest\View()
     *
     *
     * @param ObjectManager $em
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function uploadCircumstanceAttachement(Request $request,ObjectManager $em)
    {
          $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img/circumstance';
          $Attachments = $request->files->get("attachments");
          $photos = [];
          foreach ($Attachments as $key => $attachment){
              /**
               * @var UploadedFile $attachment
               */
              $imageFile = $attachment->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $attachment->guessExtension());
              $CircumstanceAttachment = new CircumstanceAttachment();
              $CircumstanceAttachment->setPath($imageFile->getBasename());
              $em->persist($CircumstanceAttachment);
              $em->flush();
              $photos[] = ["id"=>$CircumstanceAttachment->getId()];
              return $this->respondWith($photos);
          }
        return new JsonResponse($photos);
    }


    /**
     * @SWG\Post(
     *     tags={"tiersAttachement"},
     *     description="upload tiers",
     *     consumes={"multipart/form-data"},
     *     @SWG\Parameter(
     *         name="Authorization",
     *         in="header",
     *         type="string",
     *         required=true,
     *         description="Bearer auth",
     *     ),
     *     @SWG\Parameter(
     *         name="gray_card",
     *         in="formData",
     *         type="file",
     *         required=true,
     *         description="Bearer auth",
     *     ),
     *     @SWG\Parameter(
     *         name="tiers_certif",
     *         in="formData",
     *         type="file",
     *         required=true,
     *         description="Bearer auth",
     *     ),
     *    @SWG\Parameter(
     *         name="tiers_vehicule",
     *         in="formData",
     *         type="file",
     *         required=false,
     *         description="Bearer auth",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Return tiersAttachement",
     *     )
     * )
     *
     * @Rest\Post(path="/tiers/attachements", name="tiers_attachements")
     * @Rest\View()
     *
     *
     * @param ObjectManager $em
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function uploadTiersAttachement(Request $request,ObjectManager $em)
    {
          $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img/tiers';
          $Attachments = $request->files->all();
          $tiersAttachments = [];

          foreach ($Attachments as $key => $attachment){
              /**
               * @var UploadedFile $attachment
               */

              $imageFile = $attachment->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $attachment->guessExtension());
              $tiersAttachment = new TiersAttachment();
              $tiersAttachment->setPath($imageFile->getBasename());
              $tiersAttachment->setType($key);
              $em->persist($tiersAttachment);
              $em->flush();
              $tiersAttachments[]=['id'=>$tiersAttachment->getId()];
          }

        return new JsonResponse($tiersAttachments);
    }

}