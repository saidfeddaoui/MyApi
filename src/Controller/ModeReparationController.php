<?php

namespace App\Controller;

use App\Entity\ModeReparation;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ModeReparationController extends Controller
{

    /**
     * @Route(path="/modes", name="list_modes", options={"expose"=true})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $modes = $em->getRepository('App:ModeReparation')->findAll();

        return $this->render('mode_reparation/index.html.twig', [
            'page_title' => 'Modes de réparation',
            'page_subtitle' => '',
            'modes'=>$modes
        ]);
    }
    /**
     * @Route(path="/modes/add", name="add_mode", options={"expose"=true})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function addMode(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $iName = $request->request->get('name');
        $mode = new ModeReparation();
        $mode->setNom($iName);
        $em->persist($mode);
        $em->flush();
        return  new JsonResponse(array(
            "id" => $mode->getId(),
            "message" => "Mode ajouté avec succès",
        ));
    }
    /**
     * @Route(path="/modes/edit/{id}", name="edit_mode", options={"expose"=true})
     *
     * @param ModeReparation $mode
     * @param Request $request
     * @return JsonResponse
     */
    public function editMode(ModeReparation $mode, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $iName = $request->request->get('name');
        $mode->setNom($iName);
        $em->persist($mode);
        $em->flush();
        return  new JsonResponse(array(
            "id" => $mode->getId(),
            "message" => "Mode modifié avec succès",
        ));
    }
    /**
     * @Route(path="/modes/delete/{id}", name="delete_mode", options={"expose"=true})
     *
     * @param ModeReparation $mode
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteMode(ModeReparation $mode, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($mode);
        $em->flush();
        return  new JsonResponse(array(
            "message" => "Mode supprimé avec succès"
        ));
    }
}
