<?php

namespace App\Controller;

use App\Entity\Attachment;
use App\Entity\Item;
use App\Entity\ItemList;
use App\Form\TypeSinistreType;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\File;

class TypeSinistreController extends Controller
{

    /**
     * @Route(path="/sinistre/types", name="types_sinistres", options={"expose"=true})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $form = $this->createForm(TypeSinistreType::class, new Item(), [
            'action' => $this->generateUrl('add_type_sinistre'),
        ]);
        $em = $this->getDoctrine()->getManager();
        /**
         * @var ItemList $typesList
         */
        $typesList = $em->getRepository('App:ItemList')->findOneByType('sinistre');
        return $this->render('sinistre/index.html.twig', [
            'page_title' => 'Types Sinistre',
            'page_subtitle' => '',
            'types' => $typesList->getItems(),
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route(path="/sinistre/types/add", name="add_type_sinistre", options={"expose"=true})
     *
     * @param Request $request
     * @return Response
     */
    public function addTypeSinistre(Request $request)
    {
        $form = $this->createForm(TypeSinistreType::class);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Item $type_sinistre
             */
            $type_sinistre = $form->getData();
            /**
             * @var UploadedFile $_img
             */
            $_img = $form->get('_img')->getData();
            if($_img){

                $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img';
                $imageFile = $_img->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_img->guessExtension());

                $type_sinistre
                    ->setImage(new Attachment($imageFile->getBasename()))
                ;
            }
            /**
             * @var ItemList $type_sinistreList
             */
            $type_sinistreList = $em->getRepository('App:ItemList')->findOneByType('sinistre');
            $type_sinistreList->addItem($type_sinistre);
            $em->persist($type_sinistre);
            $em->flush();
        }
        if ($errors = $form->getErrors()) {
            foreach ($errors as $error) {
                $this->addFlash('add.sinistre.error', $error->getMessage());
                break;
            }
        }
        return  $this->redirect($this->generateUrl('types_sinistres'));
    }
    /**
     * @Route(path="/sinistre/types/edit/{id}", name="edit_type_sinistre", options={"expose"=true})
     *
     * @param Item $typeSinistre
     * @param Request $request
     * @return JsonResponse
     */
    public function editTypeSinistre(Item $typeSinistre, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(TypeSinistreType::class, $typeSinistre,[
            'action' => $this->generateUrl('edit_type_sinistre',array('id' => $typeSinistre->getId()))]);
        $form->handleRequest($request);
        $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img';
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Item $typeSinistre
             */
            $typeSinistre = $form->getData();
            /**
             * @var UploadedFile $_img
             */
            $_img = $form->get('_img')->getData();

            if($_img != null){
                $imageFile = $_img->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_img->guessExtension());
                $typeSinistre
                 ->setImage(new Attachment($imageFile->getBasename()));
            }
            ;

            $typeSinistre
                ->setTitle($form->get('title')->getData());
            $em->persist($typeSinistre);
            $em->flush();
            return  $this->redirect($this->generateUrl('types_sinistres'));
        }
        return  $this->render('sinistre/edit.html.twig',array(
            'form'=>$form->createView() ));
    }
    /**
     * @Route(path="/sinistre/types/delete/{id}", name="delete_type_sinistre", options={"expose"=true})
     *
     * @param Item $typeSinistre
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteTypeSinistre(Item $typeSinistre, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeSinistre);
        $em->flush();
        return  new JsonResponse(array(
            "message" => "Type siniste supprimé avec succès"
        ));
    }
}
