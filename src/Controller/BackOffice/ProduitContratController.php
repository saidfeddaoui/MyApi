<?php

namespace App\Controller\BackOffice;

use App\Entity\ProduitContrat;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;


/**
 * @Route(path="/content_types", name="content_types_")
 * @Security("is_granted('ROLE_CONTENT_MANAGEMENT')")
 *
 * @Breadcrumb(title="Accueil")
 * @Breadcrumb(title="Gestion Contenu")
 */

class ProduitContratController extends Controller
{

    /**
     * @Route(path="/contrat/produit", name="produit_contrat", options={"expose"=true})
     * @Breadcrumb(title="Produit du contrat")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('App:produitContrat')->findAll();

        return $this->render('produit_contract/index.html.twig', [
            'page_title' => 'Liste des produit de contrat',
            'page_subtitle' => '',
            'data'=>$produits
        ]);

    }


    /**
     * @Route(path="/contrat/produit/add", name="produit_contrat_add", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function addProduit(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $iName = $request->request->get('name');
        $iCode = $request->request->get('code');
        $produit = new produitContrat();
        $produit->setName($iName);
        $produit->setCode($iCode);
        $em->persist($produit);
        $em->flush();
        return  new JsonResponse(array(
            "id" => $produit->getId(),
            "message" => "Produit ajoutée avec succès",
        ));
    }


    /**
     * @Route(path="/contrat/produit/edit/{id}", name="produit_contrat_edit", options={"expose"=true})
     *
     * @param ProduitContrat $produitContrat
     * @param Request $request
     * @return JsonResponse
     */
    public function editCategorie(ProduitContrat $produitContrat, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $iName = $request->request->get('name');
        $iCode = $request->request->get('code');
        $produitContrat->setName($iName);
        $produitContrat->setCode($iCode);
        $em->persist($produitContrat);
        $em->flush();
        return  new JsonResponse(array(
            "id" => $produitContrat->getId(),
            "message" => "Produit modifié avec succès",
        ));
    }

    /**
     * @Route(path="/contrat/produit/delete/{id}", name="produit_contrat_delete", options={"expose"=true})
     *
     * @param ProduitContrat $produitContrat
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteCategorie(ProduitContrat $produitContrat, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($produitContrat);
        $em->flush();
        return  new JsonResponse(array(
            "message" => "Produit supprimée avec succès"
        ));
    }




}
