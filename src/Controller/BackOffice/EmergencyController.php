<?php

namespace App\Controller\BackOffice;

use App\Entity\Attachment;
use App\Entity\Item;
use App\Entity\ItemList;
use App\Form\EmergencyType;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route(path="/content_types", name="content_types_")
 *
 * @Breadcrumb(title="Accueil")
 * @Breadcrumb(title="Gestion Contenu")
 */
class EmergencyController extends Controller
{

    /**
     * @Route(path="/emergency", name="list_emergency", options={"expose"=true})
     *
     * @Breadcrumb(title="Numéros d'urgences")
     *
     * @param SessionInterface $session
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $form = $this->createForm(EmergencyType::class, new Item(), [
            'action' => $this->generateUrl('content_types_add_emergency'),
        ]);
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $data = array();
        /**
         * @var ItemList $emergencyList
         */
        $emergencyList = $em->getRepository('App:ItemList')->findOneBy(['type'=>'emergency','insuranceType'=> $session->get('insuranceType')]);
        foreach ($emergencyList->getItems() as $key => $value){
            $translations =  $repository->findTranslations($value);
            $data[] = array(
                'id' => $value->getId(),
                'title' => $value->getTitle(),
                'icon' => $value->getIcon(),
                'title_ar' => $translations['ar']["title"] ?? '',
                'subTitle' => $value->getSubTitle()
            );
        }
        return $this->render('emergency/index.html.twig', [
            'page_title' => 'Numéros d\'urgences',
            'page_subtitle' => '',
            'emergencies' => $data ? $data : [],
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route(path="/emergency/add", name="add_emergency", options={"expose"=true})
     *
     * @param Request $request
     * @param $validator
     * @param SessionInterface $session
     * @return Response
     */
    public function add(Request $request,ValidatorInterface $validator, SessionInterface $session)
    {
        $form = $this->createForm(EmergencyType::class);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $insuranceType = $em->getRepository('App:InsuranceType')->find($session->get('insuranceType')->getId());
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Item $emergency
             */
            $emergency = $form->getData();
            /**
             * @var UploadedFile $_icn
             */
            $_icn = $form->get('_icn')->getData();
            $iName_ar = $form->get('title_ar')->getData();
            $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img';
            if ($_icn) {
                $iconFile = $_icn->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_icn->guessExtension());
                $emergency->setIcon(new Attachment($iconFile->getBasename()));
            }
            $em = $this->getDoctrine()->getManager();
            /**
             * @var ItemList $emergencyList
             */
            $emergencyList = $em->getRepository('App:ItemList')->findOneBy(['type'=>'emergency','insuranceType'=> $insuranceType]);
            $emergencyList->addItem($emergency);
            $em->persist($emergencyList);
            $repository->translate($emergency, 'title', 'ar', $iName_ar) ;
            $em->flush();
        }
        if ($errors = $form->getErrors(true)) {
            foreach ($errors as $error) {
                $this->addFlash('warning', $error->getMessage());
                break;
            }
        }
        return  $this->redirect($this->generateUrl('content_types_list_emergency'));
    }
    /**
     * @Route(path="/emergency/edit/{id}", name="edit_emergency", options={"expose"=true})
     *
     * @param Item $emergency
     * @param Request $request
     * @return Response
     */
    public function edit(Item $emergency, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $form = $this->createForm(EmergencyType::class, $emergency, [
            'action' => $this->generateUrl('content_types_edit_emergency', ['id' => $emergency->getId()])
        ]);
        $translations =  $repository->findTranslations($emergency);
        $form->get('title_ar')->setData($translations['ar']["title"]);
        $form->handleRequest($request);
        $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img';
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Item $submittedMode
             */
            $submittedMode = $form->getData();
            $emergency
                ->setTitle($submittedMode->getTitle())
            ;
            /**
             * @var UploadedFile $_img
             */
            $_icn = $form->get('_icn')->getData();
            $iName_ar = $form->get('title_ar')->getData();
            if ($_icn) {
                $imageFile = $_icn->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_icn->guessExtension());
                $emergency->setIcon(new Attachment($imageFile->getBasename()));
            }
            $repository->translate($emergency, 'title', 'ar', $iName_ar) ;
            $em->persist($emergency);
            $em->flush();
            return  $this->redirect($this->generateUrl('content_types_list_emergency'));
        }
        return  $this->render('emergency/form.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route(path="/emergency/delete/{id}", name="delete_emergency", options={"expose"=true})
     *
     * @param Item emergency
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Item $emergency, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($emergency);
        $em->flush();
        return  new JsonResponse(array(
            "message" => "numéro d'urgence supprimé avec succès"
        ));
    }
}
