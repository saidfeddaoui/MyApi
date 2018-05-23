<?php

namespace App\Controller;

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

class ProduitController extends Controller
{

    /**
     * @Route(path="/produits", name="list_produit", options={"expose"=true})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $form = $this->createForm(ProduitType::class, new Item(), [
            'action' => $this->generateUrl('add_produit'),
        ]);
        $em = $this->getDoctrine()->getManager();
        /**
         * @var ItemList $productList
         */
        $productList = $em->getRepository('App:ItemList')->findOneByType('products');
        return $this->render('produit/index.html.twig', [
            'page_title' => 'Produits',
            'page_subtitle' => '',
            'products' => $productList->getItems(),
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route(path="/produits/add", name="add_produit", options={"expose"=true})
     *
     * @param Request $request
     * @return Response
     */
    public function addProduit(Request $request)
    {
        $form = $this->createForm(ProduitType::class);
        $form->handleRequest($request);
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
            $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img';
            $iconFile = $_icn->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_icn->guessExtension());
            $imageFile = $_img->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_img->guessExtension());
            $em = $this->getDoctrine()->getManager();
            $product
                ->setIcon(new Attachment($iconFile->getBasename()))
                ->setImage(new Attachment($imageFile->getBasename()))
            ;
            /**
             * @var ItemList $productList
             */
            $productList = $em->getRepository('App:ItemList')->findOneByType('products');
            $productList->addItem($product);
            $em->persist($productList);
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
    public function editProduit(Item $produit, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ProduitType::class, $produit,[
            'action' => $this->generateUrl('edit_produit',array('id' => $produit->getId()))]);
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
            $em->persist($produit);
            $em->flush();
            return  $this->redirect($this->generateUrl('list_produit'));
        }
        return  $this->render('produit/edit.html.twig',array(
            'form'=>$form->createView() ));
    }
    /**
     * @Route(path="/produits/delete/{id}", name="delete_produit", options={"expose"=true})
     *
     * @param Item $produit
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteProduit(Item $produit, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($produit);
        $em->flush();
        return  new JsonResponse(array(
            "message" => "Produit supprimée avec succès"
        ));
    }
}
