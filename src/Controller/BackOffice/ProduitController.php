<?php

namespace App\Controller\BackOffice;

use App\Entity\Attachment;
use App\Entity\Item;
use App\Entity\ItemList;
use App\Form\ProduitType;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProduitController extends Controller
{

    /**
     * @Route(path="/produits", name="list_produit", options={"expose"=true})
     *
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(SessionInterface $session)
    {
        $form = $this->createForm(ProduitType::class, new Item(), [
            'action' => $this->generateUrl('add_produit'),
        ]);
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        /**
         * @var ItemList $productList
         */
        $productList = $em->getRepository('App:ItemList')->findOneBy([
            'type' => 'products',
            'insuranceType' => $session->get('insuranceType')
        ]);
        $products = [];
        foreach ($productList->getItems() as $product) {
            $translations =  $repository->findTranslations($product);
            $products[] = [
                'id' => $product->getId(),
                'title' => $product->getTitle(),
                'image' => $product->getImage(),
                'icon' => $product->getIcon(),
                'title_ar' => $translations['ar']['title'] ?? '',
                'content_ar' => $translations['ar']['content'] ?? '',
            ];
        }
        return $this->render('produit/index.html.twig', [
            'page_title' => 'Produits',
            'page_subtitle' => '',
            'products' => $products,
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
            $imgDirectory = $this->get('kernel')->getProjectDir() . '/public/img';
            /**
             * @var UploadedFile $_icn
             */
            if ($_icn = $form->get('_icn')->getData()) {
                $iconFile = $_icn->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_icn->guessExtension());
                $product->setIcon(new Attachment($iconFile->getBasename()));
            }
            /**
             * @var UploadedFile $_img
             */
            if ($_img = $form->get('_img')->getData()) {
                $imageFile = $_img->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_img->guessExtension());
                $product->setImage(new Attachment($imageFile->getBasename()));
            }
            /**
             * @var ItemList $productList
             */
            $productList = $em->getRepository('App:ItemList')->findOneBy([
                'type' => 'products',
                'insuranceType' => $insuranceType
            ]);
            $productList->addItem($product);
            $em->persist($productList);
            $repository->translate($product, 'title', 'ar', $form->get('title_ar')->getData());
            $repository->translate($product, 'content', 'ar', $form->get('content_ar')->getData());
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
        $form = $this->createForm(ProduitType::class, $produit, [
            'action' => $this->generateUrl('edit_produit', ['id' => $produit->getId()])
        ]);
        $translations =  $repository->findTranslations($produit);
        if ($translations) {
            $form->get('title_ar')->setData($translations['ar']['title']);
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
            if ($_icn = $form->get('_icn')->getData()) {
                $iconFile = $_icn->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_icn->guessExtension());
                $product->setIcon(new Attachment($iconFile->getBasename()));
            }
            /**
             * @var UploadedFile $_img
             */
            if ($_img = $form->get('_img')->getData()) {
                $imageFile = $_img->move($imgDirectory, Uuid::uuid4()->toString() . '.' . $_img->guessExtension());
                $product->setImage(new Attachment($imageFile->getBasename()));
            }
            $product->setTitle($form->get('title')->getData());
            $repository->translate($product, 'title', 'ar', $form->get('title_ar')->getData());
            $repository->translate($product, 'content', 'ar', $form->get('content_ar')->getData());
            $em->persist($produit);
            $em->flush();
            return  $this->redirect($this->generateUrl('list_produit'));
        }
        return $this->render('produit/form.html.twig', [
            'form'=>$form->createView()
        ]);
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
        return new JsonResponse([
            'message' => 'Produit supprimée avec succès'
        ]);
    }

}
