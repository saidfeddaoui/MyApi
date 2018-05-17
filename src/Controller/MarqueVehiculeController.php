<?php

namespace App\Controller;

use App\Entity\MarqueVehicule;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class MarqueVehiculeController extends Controller
{
    /**
     * @Route("/marque", name="marque_vehicule",options={"expose"=true})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $Marques = $em->getRepository('App:MarqueVehicule')->findAll();

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
     * @return JsonResponse
     */
    public function addMarque(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $iName = $request->request->get('name');

        $marque = new MarqueVehicule();
        $marque->setNom($iName);
        $em->persist($marque);
        $em->flush();
        return  new JsonResponse(array(
            "id" => $marque->getId(),
            "message" => "Marque véhicule ajoutée avec succès",
        ));
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
        return  new JsonResponse(array(
            "id" => $marque->getId(),
            "message" => "Marque véhicule modifiée avec succès",
        ));
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
        return  new JsonResponse(array(
            "message" => "Marque véhicule supprimée avec succès"
        ));
    }
}
