<?php

namespace App\Controller\BackOffice;

use App\Entity\Attachment;
use App\Entity\Item;
use App\Entity\ItemList;
use App\Form\TypeSinistreType;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route(path="/content_types", name="content_types_")
 * @Security("is_granted('ROLE_CONTENT_MANAGEMENT')")
 *
 * @Breadcrumb(title="Accueil")
 * @Breadcrumb(title="Gestion Contenu")
 */
class TypeSinistreController extends Controller
{

    /**
     * @Route(path="/sinistre/types", name="types_sinistres", options={"expose"=true})
     *
     * @Breadcrumb(title="Types sinistre")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $form = $this->createForm(TypeSinistreType::class, new Item(), [
            'action' => $this->generateUrl('content_types_add_type_sinistre'),
        ]);
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $data = array();
        /**
         * @var ItemList $typesList
         */
        $typesList = $em->getRepository('App:ItemList')->findOneByType('sinistre');
        foreach ($typesList->getItems() as $key => $value){
            $translations =  $repository->findTranslations($value);
            $data[] = array(
                'id' => $value->getId(),
                'title' => $value->getTitle(),
                'image' => $value->getIcon(),
                'image_active' => $value->getImage(),
                'title_ar' => $translations['ar']["title"] ?? '',
            );
        }
        return $this->render('sinistre/index.html.twig', [
            'page_title' => 'Types Sinistre',
            'page_subtitle' => '',
            'types' => $data ? $data : [],
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route(path="/sinistre/types/add", name="add_type_sinistre", options={"expose"=true})
     *
     * @param Request $request
     * @return Response
     */
    public function add(Request $request)
    {
        $form = $this->createForm(TypeSinistreType::class);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Item $type_sinistre
             */
            $type_sinistre = $form->getData();
            /**
             * @var UploadedFile $_icn
             */
            $_icn = $form->get('_icn')->getData();
            $_img = $form->get('_img')->getData();
            $iName_ar = $form->get('title_ar')->getData();
            if($_icn) {
                $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img';
                $iconFile = $_icn->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_icn->guessExtension());
                $type_sinistre->setIcon(new Attachment($iconFile->getBasename()));
            }
            if($_img) {
                $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img';
                $imgFile = $_img->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_img->guessExtension());
                $type_sinistre->setImage(new Attachment($imgFile->getBasename()));
            }
            /**
             * @var ItemList $type_sinistreList
             */
            $type_sinistreList = $em->getRepository('App:ItemList')->findOneByType('sinistre');
            $type_sinistreList->addItem($type_sinistre);
            $em->persist($type_sinistre);
            $repository->translate($type_sinistre, 'title', 'ar', $iName_ar) ;
            $em->flush();
        }
        if ($errors = $form->getErrors()) {
            foreach ($errors as $error) {
                $this->addFlash('add.sinistre.error', $error->getMessage());
                break;
            }
        }
        return  $this->redirect($this->generateUrl('content_types_types_sinistres'));
    }
    /**
     * @Route(path="/sinistre/types/edit/{id}", name="edit_type_sinistre", options={"expose"=true})
     *
     * @param Item $typeSinistre
     * @param Request $request
     * @return JsonResponse
     */
    public function edit(Item $typeSinistre, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $form = $this->createForm(TypeSinistreType::class, $typeSinistre, [
            'action' => $this->generateUrl('content_types_edit_type_sinistre', ['id' => $typeSinistre->getId()])
        ]);
        $translations =  $repository->findTranslations($typeSinistre);
        if($translations){
            $form->get('title_ar')->setData($translations['ar']["title"]);
        }
        $form->handleRequest($request);
        $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img';
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Item $submittedSinistre
             */
            $submittedSinistre = $form->getData();
            /**
             * @var UploadedFile $_img
             */
            $_icn = $form->get('_icn')->getData();
            $_img = $form->get('_img')->getData();
            $iName_ar = $form->get('title_ar')->getData();
            if($_icn) {
                $iconFile = $_icn->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_icn->guessExtension());
                $typeSinistre->setIcon(new Attachment($iconFile->getBasename()));
            }
            if($_img) {
                $imgFile = $_img->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_img->guessExtension());
                $typeSinistre->setImage(new Attachment($imgFile->getBasename()));
            }
            $typeSinistre->setTitle($submittedSinistre->getTitle());
            $repository->translate($typeSinistre, 'title', 'ar', $iName_ar) ;
            $em->persist($typeSinistre);
            $em->flush();
            return  $this->redirect($this->generateUrl('content_types_types_sinistres'));
        }
        return  $this->render('sinistre/form.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route(path="/sinistre/types/delete/{id}", name="delete_type_sinistre", options={"expose"=true})
     *
     * @param Item $typeSinistre
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Item $typeSinistre, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($typeSinistre);
        $em->flush();
        return  new JsonResponse(array(
            "message" => "Type siniste supprimé avec succès"
        ));
    }

}
