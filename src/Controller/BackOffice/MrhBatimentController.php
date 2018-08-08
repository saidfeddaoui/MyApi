<?php

namespace App\Controller\BackOffice;

use App\Entity\MrhBatiment;
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
class MrhBatimentController extends Controller
{

    /**
     * @Route(path="/batiments", name="batiment", options={"expose"=true})
     *
     * @Breadcrumb(title="Mrh Batiments")
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $batiments = $em->getRepository('App:MrhBatiment')->findAll();

        return $this->render('mrh/batiment/index.html.twig', [
            'page_title' => 'Liste des batiments',
            'page_subtitle' => '',
            'data'=>$batiments
        ]);
    }
    /**
     * @Route(path="/batiment/add", name="add_batiment", options={"expose"=true})
     * @param SessionInterface $session
     * @param Request $request
     * @return JsonResponse
     */
    public function addBatiment(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $iValue = $request->request->get('value');
        $batiment = new MrhBatiment();
        $batiment->setValue($iValue);
        $em->persist($batiment);
        $em->flush();
        return  new JsonResponse(array(
            "id" => $batiment->getId(),
            "message" => "Batiment ajouté avec succès",
        ));
    }
    /**
     * @Route(path="/batiment/edit/{id}", name="edit_batiment", options={"expose"=true})
     *
     * @param MrhBatiment $batiment
     * @param Request $request
     * @return JsonResponse
     */
    public function editBatiment(MrhBatiment $batiment, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $iValue = $request->request->get('value');
        $batiment->setValue($iValue);
        $em->persist($batiment);
        $em->flush();
        return  new JsonResponse(array(
            "id" => $batiment->getId(),
            "message" => "Batiment modifié avec succès",
        ));
    }
    /**
     * @Route(path="/batiment/delete/{id}", name="delete_batiment", options={"expose"=true})
     *
     * @param MrhBatiment $batiment
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteBatiment(MrhBatiment $batiment, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($batiment);
        $em->flush();
        return  new JsonResponse(array(
            "message" => "Batiment supprimé avec succès"
        ));
    }
}
