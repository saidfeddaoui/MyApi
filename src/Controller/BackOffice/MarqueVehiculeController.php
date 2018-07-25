<?php

namespace App\Controller\BackOffice;

use App\Entity\MarqueVehicule;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route(path="/content_types", name="content_types_")
 *
 * @Breadcrumb(title="Accueil")
 * @Breadcrumb(title="Gestion Contenu")
 */
class MarqueVehiculeController extends Controller
{

    /**
     * @Route("/marque", name="marque_vehicule",options={"expose"=true})
     *
     * @Breadcrumb(title="Marques véhicule")
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $Marques = $em->getRepository('App:MarqueVehicule')->findBy(['insuranceType'=> $session->get('insuranceType')]);
        return $this->render('marque_vehicule/index.html.twig', [
            'page_title' => 'Marque Véhicule',
            'page_subtitle' => '',
            'marques'=>$Marques
        ]);
    }

    /**
     * @Route(path="/marque/add", name="add_marque", options={"expose"=true})
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return JsonResponse
     */
    public function addMarque(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $insuranceType = $em->getRepository('App:InsuranceType')->find($session->get('insuranceType')->getId());
        $iName = $request->request->get('name');
        $marque = new MarqueVehicule();
        $marque->setNom($iName);
        $marque->setInsuranceType($insuranceType);
        $em->persist($marque);
        $em->flush();
        return  new JsonResponse([
            'id' => $marque->getId(),
            'message' => 'Marque véhicule ajoutée avec succès',
        ]);
    }
    /**
     * @Route(path="/marque/edit/{id}", name="edit_marque", options={"expose"=true})
     *
     * @param MarqueVehicule $marque
     * @param Request $request
     * @return JsonResponse
     */
    public function editMarque(MarqueVehicule $marque, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $iName = $request->request->get('name');
        $marque->setNom($iName);
        $em->persist($marque);
        $em->flush();
        return  new JsonResponse([
            'id' => $marque->getId(),
            'message' => 'Marque véhicule modifiée avec succès',
        ]);
    }

    /**
     * @Route(path="/marque/delete/{id}", name="delete_marque", options={"expose"=true})
     *
     * @param MarqueVehicule $marque
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteMarque(MarqueVehicule $marque, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($marque);
        $em->flush();
        return  new JsonResponse(['message' => 'Marque véhicule supprimée avec succès']);
    }
}
