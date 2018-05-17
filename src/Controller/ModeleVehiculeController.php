<?php

namespace App\Controller;

use App\Entity\ModeleVehicule;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ModeleVehiculeController extends Controller
{
    /**
     * @Route("/modele", name="modele_vehicule",options={"expose"=true})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $Modeles = $em->getRepository('App:ModeleVehicule')->findAll();

        return $this->render('modele_vehicule/index.html.twig', [
            'page_title' => 'Modèle Véhicule',
            'page_subtitle' => '',
            'modeles'=>$Modeles
        ]);

    }

    /**
     * @Route(path="/modele/add", name="add_modele", options={"expose"=true})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function addModele(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $iName = $request->request->get('name');

        $modele = new ModeleVehicule();
        $modele->setNom($iName);
        $em->persist($modele);
        $em->flush();
        return  new JsonResponse(array(
            "id" => $modele->getId(),
            "message" => "Modèle véhicule ajouté avec succès",
        ));
    }

    /**
     * @Route(path="/modele/edit/{id}", name="edit_modele", options={"expose"=true})
     *
     * @param ModeleVehicule $modele
     * @param Request $request
     * @return JsonResponse
     */
    public function editMarque(ModeleVehicule $modele, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $iName = $request->request->get('name');
        $modele->setNom($iName);
        $em->persist($modele);
        $em->flush();
        return  new JsonResponse(array(
            "id" => $modele->getId(),
            "message" => "Modèle véhicule modifié avec succès",
        ));
    }

    /**
     * @Route(path="/modele/delete/{id}", name="delete_modele", options={"expose"=true})
     *
     * @param ModeleVehicule $modele
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteMarque(ModeleVehicule $modele, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($modele);
        $em->flush();
        return  new JsonResponse(array(
            "message" => "Modèle véhicule supprimé avec succès"
        ));
    }
}
