<?php

namespace App\Controller\BackOffice;

use App\Entity\MrhCategorie;
use App\Entity\Societaire;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
class MrhCategorieController extends Controller
{

    /**
     * @Route(path="/mrh/categories", name="mrh_categories", options={"expose"=true})
     *
     * @Breadcrumb(title="Mrh Catégories")
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('App:MrhCategorie')->findAll();

        return $this->render('mrh/categorie/index.html.twig', [
            'page_title' => 'Liste des catégories',
            'page_subtitle' => '',
            'data'=>$categories
        ]);
    }
    /**
     * @Route(path="/mrh/categorie/add", name="mrh_categorie_add", options={"expose"=true})
     * @param SessionInterface $session
     * @param Request $request
     * @return JsonResponse
     */
    public function addCategorie(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $iName = $request->request->get('name');
        $iCode = $request->request->get('code');
        $societaire = new MrhCategorie();
        $societaire->setName($iName);
        $societaire->setCode($iCode);
        $em->persist($societaire);
        $em->flush();
        return  new JsonResponse(array(
            "id" => $societaire->getId(),
            "message" => "Catégortie ajoutée avec succès",
        ));
    }
    /**
     * @Route(path="/mrh/categorie/edit/{id}", name="mrh_categorie_edit", options={"expose"=true})
     *
     * @param MrhCategorie $mrhCategorie
     * @param Request $request
     * @return JsonResponse
     */
    public function editCategorie(MrhCategorie $mrhCategorie, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $iName = $request->request->get('name');
        $iCode = $request->request->get('code');
        $mrhCategorie->setName($iName);
        $mrhCategorie->setCode($iCode);
        $em->persist($mrhCategorie);
        $em->flush();
        return  new JsonResponse(array(
            "id" => $mrhCategorie->getId(),
            "message" => "Catégorie modifié avec succès",
        ));
    }
    /**
     * @Route(path="/mrh/categorie/delete/{id}", name="mrh_categorie_delete", options={"expose"=true})
     *
     * @param MrhCategorie $mrhCategorie
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteCategorie(MrhCategorie $mrhCategorie, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($mrhCategorie);
        $em->flush();
        return  new JsonResponse(array(
            "message" => "Catégorie supprimée avec succès"
        ));
    }
}
