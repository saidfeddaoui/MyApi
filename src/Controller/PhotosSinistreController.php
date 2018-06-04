<?php

namespace App\Controller;

use App\Entity\Attachment;
use App\Entity\Item;
use App\Entity\ItemList;
use App\Entity\PhotosSinistre;
use App\Form\PhotosSyinistreType;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\File;

class PhotosSinistreController extends Controller
{
    /**
     * @Route(path="/sinistre/photos", name="photos_sinistres", options={"expose"=true})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {   $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(PhotosSyinistreType::class, new PhotosSinistre(), [
            'action' => $this->generateUrl('add_photo_sinistre'),
        ]);
        /**
         * @var ItemList $typesList
         */
        $photos_sinistre = $em->getRepository('App:PhotosSinistre')->findAll();
        return $this->render('sinistre/photos.html.twig', [
            'page_title' => 'Photos Sinistre',
            'page_subtitle' => '',
            'photos' => $photos_sinistre,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route(path="/sinistre/photos/add", name="add_photo_sinistre", options={"expose"=true})
     *
     * @param Request $request
     * @return Response
     */
    public function addPhotoSinistre(Request $request)
    {
        $form = $this->createForm(PhotosSyinistreType::class);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var PhotosSinistre $photo_sinistre
             */
            $photo_sinistre = $form->getData();
            /**
             * @var UploadedFile $_img
             */
            $_img = $form->get('_img')->getData();
            if($_img){

                $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img/sinistre';
                $imageFile = $_img->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_img->guessExtension());

                $photo_sinistre
                    ->setPath($imageFile->getBasename())
                ;
            }

            $em->persist($photo_sinistre);
            $em->flush();
        }
        if ($errors = $form->getErrors()) {
            foreach ($errors as $error) {
                $this->addFlash('add.sinistre.error', $error->getMessage());
                break;
            }
        }
        return  $this->redirect($this->generateUrl('photos_sinistres'));
    }
    /**
     * @Route(path="/sinistre/photo/edit/{id}", name="edit_photo_sinistre", options={"expose"=true})
     *
     * @param PhotosSinistre $photoSinistre
     * @param Request $request
     * @return JsonResponse
     */
    public function editPhotoSinistre(PhotosSinistre $photoSinistre, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm( PhotosSyinistreType::class, $photoSinistre,[
            'action' => $this->generateUrl('edit_photo_sinistre',array('id' => $photoSinistre->getId()))]);
        $form->handleRequest($request);
        $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img/sinistre';
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var PhotosSinistre $photoSinistre
             */
            $photoSinistre = $form->getData();
            /**
             * @var UploadedFile $_img
             */
            $_img = $form->get('_img')->getData();

            if($_img != null){
                $imageFile = $_img->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_img->guessExtension());
                $photoSinistre
                    ->setPath($imageFile->getBasename());
            }
            ;

            $em->persist($photoSinistre);
            $em->flush();
            return  $this->redirect($this->generateUrl('photos_sinistres'));
        }
        return  $this->render('sinistre/edit_photo.html.twig',array(
            'form'=>$form->createView() ));
    }
    /**
     * @Route(path="/sinistre/photo/delete/{id}", name="delete_photo_sinistre", options={"expose"=true})
     *
     * @param PhotosSinistre $photoSinistre
     * @param Request $request
     * @return JsonResponse
     */
    public function deletePhotoSinistre(PhotosSinistre $photoSinistre, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($photoSinistre);
        $em->flush();
        return  new JsonResponse(array(
            "message" => "Photo siniste supprimée avec succès"
        ));
    }
}
