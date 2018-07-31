<?php

namespace App\Controller\BackOffice;

use App\Entity\MrhPropriete;
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
class MrhProprieteController extends Controller
{

    /**
     * @Route(path="/mrh/proprietes", name="mrh_proprietes", options={"expose"=true})
     *
     * @Breadcrumb(title="Mrh Propriétés")
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $proprietes = $em->getRepository('App:MrhPropriete')->findAll();

        return $this->render('mrh/propriete/index.html.twig', [
            'page_title' => 'Liste des propriétés',
            'page_subtitle' => '',
            'data'=>$proprietes
        ]);
    }
    /**
     * @Route(path="/mrh/propriete/add", name="add_propriete", options={"expose"=true})
     * @param SessionInterface $session
     * @param Request $request
     * @return JsonResponse
     */
    public function addPropriete(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $iName = $request->request->get('name');
        $iCode = $request->request->get('code');
        $propriete = new MrhPropriete();
        $propriete->setName($iName);
        $propriete->setCode($iCode);
        $em->persist($propriete);
        $em->flush();
        return  new JsonResponse(array(
            "id" => $propriete->getId(),
            "message" => "Proprieté ajoutée avec succès",
        ));
    }
    /**
     * @Route(path="/mrh/propriete/edit/{id}", name="edit_propriete", options={"expose"=true})
     *
     * @param MrhPropriete $propriete
     * @param Request $request
     * @return JsonResponse
     */
    public function editPropriete(MrhPropriete $propriete, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $iName = $request->request->get('name');
        $iCode = $request->request->get('code');
        $propriete->setName($iName);
        $propriete->setCode($iCode);
        $em->persist($propriete);
        $em->flush();
        return  new JsonResponse(array(
            "id" => $propriete->getId(),
            "message" => "Proprieté modifiée avec succès",
        ));
    }
    /**
     * @Route(path="/mrh/propriete/delete/{id}", name="delete_propriete", options={"expose"=true})
     *
     * @param MrhPropriete $propriete
     * @param Request $request
     * @return JsonResponse
     */
    public function deletePropriete(MrhPropriete $propriete, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($propriete);
        $em->flush();
        return  new JsonResponse(array(
            "message" => "Proprieté supprimée avec succès"
        ));
    }
}
