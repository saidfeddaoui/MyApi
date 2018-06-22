<?php

namespace App\Controller\BackOffice;

use App\Entity\Attachment;
use App\Entity\Item;
use App\Entity\ItemList;
use App\Form\ModeReparationType;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ModeReparationController extends Controller
{

    /**
     * @Route(path="/modes", name="list_mode", options={"expose"=true})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $form = $this->createForm(ModeReparationType::class, new Item(), [
            'action' => $this->generateUrl('add_mode'),
        ]);
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $data = array();
        /**
         * @var ItemList $modeReparationList
         */
        $modeReparationList = $em->getRepository('App:ItemList')->findOneByType('modes_reparation');
        foreach ($modeReparationList->getItems() as $key => $value){
            $translations =  $repository->findTranslations($value);
            $data[] = array(
                'id' => $value->getId(),
                'title' => $value->getTitle(),
                'image' => $value->getImage(),
                'content' => $value->getContent(),
                'title_ar' => $translations['ar']["title"] ?? '',
                'content_ar' => $translations['ar']["content"] ?? ''
            );
        }
        return $this->render('mode_reparation/index.html.twig', [
            'page_title' => 'Modes de réparation',
            'page_subtitle' => '',
            'modes' => $data ? $data : [],
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route(path="/modes/add", name="add_mode", options={"expose"=true})
     *
     * @param Request $request
     * @return Response
     */
    public function add(Request $request)
    {
        $form = $this->createForm(ModeReparationType::class);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Item $mode
             */
            $mode = $form->getData();
            /**
             * @var UploadedFile $_img
             */
            $_img = $form->get('_img')->getData();
            $iName_ar = $form->get('title_ar')->getData();
            $iContent_ar = $form->get('content_ar')->getData();
            $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img';
            if ($_img) {
                $imageFile = $_img->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_img->guessExtension());
                $mode->setImage(new Attachment($imageFile->getBasename()));
            }
            $em = $this->getDoctrine()->getManager();
            /**
             * @var ItemList $modeReparationList
             */
            $modeReparationList = $em->getRepository('App:ItemList')->findOneByType('modes_reparation');
            $modeReparationList->addItem($mode);
            $em->persist($modeReparationList);
            $repository->translate($mode, 'title', 'ar', $iName_ar) ;
            $repository->translate($mode, 'content', 'ar', $iContent_ar) ;
            $em->flush();
        }
        if ($errors = $form->getErrors()) {
            foreach ($errors as $error) {
                $this->addFlash('add.mode.error', $error->getMessage());
                break;
            }
        }
        return  $this->redirect($this->generateUrl('list_mode'));
    }
    /**
     * @Route(path="/modes/edit/{id}", name="edit_mode", options={"expose"=true})
     *
     * @param Item $mode
     * @param Request $request
     * @return Response
     */
    public function edit(Item $mode, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $form = $this->createForm(ModeReparationType::class, $mode, [
            'action' => $this->generateUrl('edit_mode', ['id' => $mode->getId()])
        ]);
        $translations =  $repository->findTranslations($mode);
        $form->get('title_ar')->setData($translations['ar']["title"]);
        $form->get('content_ar')->setData($translations['ar']["content"]);
        $form->handleRequest($request);
        $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img';
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Item $submittedMode
             */
            $submittedMode = $form->getData();
            $mode
                ->setTitle($submittedMode->getTitle())
                ->setContent($submittedMode->getContent())
            ;
            /**
             * @var UploadedFile $_img
             */
            $_img = $form->get('_img')->getData();
            $iName_ar = $form->get('title_ar')->getData();
            $iContent_ar = $form->get('content_ar')->getData();
            if ($_img) {
                $imageFile = $_img->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_img->guessExtension());
                $mode->setImage(new Attachment($imageFile->getBasename()));
            }
            $repository->translate($mode, 'title', 'ar', $iName_ar) ;
            $repository->translate($mode, 'content', 'ar', $iContent_ar) ;
            $em->persist($mode);
            $em->flush();
            return  $this->redirect($this->generateUrl('list_mode'));
        }
        return  $this->render('mode_reparation/form.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route(path="/modes/delete/{id}", name="delete_mode", options={"expose"=true})
     *
     * @param Item $mode
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Item $mode, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($mode);
        $em->flush();
        return  new JsonResponse(array(
            "message" => "Mode supprimé avec succès"
        ));
    }
}
