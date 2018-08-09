<?php

namespace App\Controller\BackOffice;

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
class SocietaireController extends Controller
{

    /**
     * @Route(path="/societaires", name="societaire", options={"expose"=true})
     *
     * @Breadcrumb(title="Sociétaires")
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $societaires = $em->getRepository('App:Societaire')->findAll();

        return $this->render('societaire/index.html.twig', [
            'page_title' => 'Liste des sociétaires',
            'page_subtitle' => '',
            'data'=>$societaires
        ]);
    }
    /**
     * @Route(path="/societaires/add", name="add_societaire", options={"expose"=true})
     * @param SessionInterface $session
     * @param Request $request
     * @return JsonResponse
     */
    public function addSocietaire(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $iName = $request->request->get('name');
        $iCode = $request->request->get('code');
        $iType = $request->request->get('type');
        $aAssurance = $request->request->get('assurance');
        $societaire = new Societaire();
        $societaire->setName($iName);
        $societaire->setCode($iCode);
        $societaire->setType($iType);
        $societaire->setCodeInsurance($aAssurance);
        $em->persist($societaire);
        $em->flush();
        return  new JsonResponse(array(
            "id" => $societaire->getId(),
            "message" => "Societaire ajouté avec succès",
        ));
    }
    /**
     * @Route(path="/societaires/edit/{id}", name="edit_societaire", options={"expose"=true})
     *
     * @param Societaire $societaire
     * @param Request $request
     * @return JsonResponse
     */
    public function editSocietaire(Societaire $societaire, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $iName = $request->request->get('name');
        $iCode = $request->request->get('code');
        $iType = $request->request->get('type');
        $aAssurance = $request->request->get('assurance');
        $societaire->setName($iName);
        $societaire->setCode($iCode);
        $societaire->setType($iType);
        $societaire->setCodeInsurance($aAssurance);
        $em->persist($societaire);
        $em->flush();
        return  new JsonResponse(array(
            "id" => $societaire->getId(),
            "message" => "Societaire modifié avec succès",
        ));
    }
    /**
     * @Route(path="/societaires/delete/{id}", name="delete_societaire", options={"expose"=true})
     *
     * @param Societaire $societaire
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteVille(Societaire $societaire, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($societaire);
        $em->flush();
        return  new JsonResponse(array(
            "message" => "Societaire supprimé avec succès"
        ));
    }
}
