<?php

namespace App\Controller\BackOffice;

use App\Entity\Attachment;
use App\Entity\Item;
use App\Entity\ItemList;
use App\Entity\Ville;
use App\Form\ProduitType;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProduitController extends Controller
{

    /**
     * @Route(path="/produits", name="list_produit", options={"expose"=true})
     * @param SessionInterface $session
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $form = $this->createForm(ProduitType::class, new Item(), [
            'action' => $this->generateUrl('add_produit'),
        ]);
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $data = array();
        /**
         * @var ItemList $productList
         */
        $productList = $em->getRepository('App:ItemList')->findOneBy(['type'=>'products','insuranceType'=> $session->get('insuranceType')]);

        foreach ($productList->getItems() as $key => $value){
            $translations =  $repository->findTranslations($value);
            $data[] = array(
                'id' => $value->getId(),
                'title' => $value->getTitle(),
                'image' => $value->getImage(),
                'icon' => $value->getIcon(),
                'title_ar' => $translations['ar']["title"] ?? '',
            );
        }
        return $this->render('produit/index.html.twig', [
            'page_title' => 'Produits',
            'page_subtitle' => '',
            'products' => $data ? $data : [],
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route(path="/produits/add", name="add_produit", options={"expose"=true})
     * @param SessionInterface $session
     * @param Request $request
     * @return Response
     */
    public function add(Request $request, SessionInterface $session)
    {
        $form = $this->createForm(ProduitType::class);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $insuranceType = $em->getRepository('App:InsuranceType')->find($session->get('insuranceType')->getId());
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Item $product
             */
            $product = $form->getData();
            /**
             * @var UploadedFile $_icn
             */
            $_icn = $form->get('_icn')->getData();
            /**
             * @var UploadedFile $_img
             */
            $_img = $form->get('_img')->getData();
            $iName_ar = $form->get('title_ar')->getData();
            $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img';
            if ($_icn) {
                $iconFile = $_icn->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_icn->guessExtension());
                $product->setIcon(new Attachment($iconFile->getBasename()));
            }
            if ($_img) {
                $imageFile = $_img->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_img->guessExtension());
                $product->setImage(new Attachment($imageFile->getBasename()));
            }
            /**
             * @var ItemList $productList
             */
            $productList = $em->getRepository('App:ItemList')->findOneBy(['type'=>'products','insuranceType'=> $insuranceType]);
            $productList->addItem($product);
            $em->persist($productList);
            $repository->translate($product, 'title', 'ar', $iName_ar) ;
            $em->flush();
        }
        if ($errors = $form->getErrors()) {
            foreach ($errors as $error) {
                $this->addFlash('add.product.error', $error->getMessage());
                break;
            }
        }
        return  $this->redirect($this->generateUrl('list_produit'));
    }
    /**
     * @Route(path="/produits/edit/{id}", name="edit_produit", options={"expose"=true})
     *
     * @param Item $produit
     * @param Request $request
     * @return JsonResponse
     */
    public function edit(Item $produit, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $form = $this->createForm(ProduitType::class, $produit,[
            'action' => $this->generateUrl('edit_produit',array('id' => $produit->getId()))]);
        $translations =  $repository->findTranslations($produit);
        if($translations){
            $form->get('title_ar')->setData($translations['ar']["title"]);
        }
        $form->handleRequest($request);
        $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img';
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Item $product
             */
            $product = $form->getData();
            /**
             * @var UploadedFile $_icn
             */
            $_icn = $form->get('_icn')->getData();
            /**
             * @var UploadedFile $_img
             */
            $_img = $form->get('_img')->getData();
            $iName_ar = $form->get('title_ar')->getData();

            if($_icn != null){
                $iconFile = $_icn->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_icn->guessExtension());
                $product
                    ->setIcon(new Attachment($iconFile->getBasename()));
            }
            if($_img != null){
                $imageFile = $_img->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_img->guessExtension());
                $product
                 ->setImage(new Attachment($imageFile->getBasename()));
            }
            ;

            $product
                ->setTitle($form->get('title')->getData());
            $repository->translate($product, 'title', 'ar', $iName_ar) ;
            $em->persist($produit);
            $em->flush();
            return  $this->redirect($this->generateUrl('list_produit'));
        }
        return  $this->render('produit/form.html.twig',array(
            'form'=>$form->createView() ));
    }
    /**
     * @Route(path="/produits/delete/{id}", name="delete_produit", options={"expose"=true})
     *
     * @param Item $produit
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Item $produit, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($produit);
        $em->flush();
        return  new JsonResponse(array(
            "message" => "Produit supprimée avec succès"
        ));
    }
}
