<?php

namespace App\Controller;

use App\Entity\Attachment;
use App\Entity\Item;
use App\Entity\ItemList;
use App\Entity\Ville;
use App\Form\ProduitType;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @param Ville $ville
     * @param Request $request
     * @return JsonResponse
     */
    public function editVille(Ville $ville, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $iName = $request->request->get('name');
        $iName_ar = $request->request->get('name_ar');
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $ville->setNom($iName);
        $repository->translate($ville, 'nom', 'ar', $iName_ar) ;
        $em->persist($ville);
        $em->flush();
        return  new JsonResponse(array(
            "id" => $ville->getId(),
            "message" => "Ville modifiée avec succès",
        ));
    }
    /**
     * @Route(path="/produits/delete/{id}", name="delete_produit", options={"expose"=true})
     *
     * @param Ville $ville
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteVille(Ville $ville, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($ville);
        $em->flush();
        return  new JsonResponse(array(
            "message" => "Ville supprimée avec succès"
        ));
    }
}
