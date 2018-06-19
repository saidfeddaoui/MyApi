<?php

namespace App\Controller;

use App\Entity\Accident;
use App\Form\AccidentType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AccidentController extends Controller
{

    /**
     * @Route(path="/accident", name="type_accident", options={"expose"=true})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = array();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $accident = $em->getRepository('App:Accident')->findAll();
        foreach ($accident as $key => $value){
            $translations =  $repository->findTranslations($value);
            $data[] = array(
                'id' => $value->getId(),
                'type' => $value->getType(),
                'type_ar' => $translations['ar']["type"] ?? '',
            );
        }
        return $this->render('accident/index.html.twig', [
            'page_title' => 'Types accident',
            'page_subtitle' => '',
            'data'=>$data
        ]);
    }
    /**
     * @Route(path="/accident/add", name="add_accident", options={"expose"=true})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function addAccident(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $iType = $request->request->get('type');
        $iType_ar = $request->request->get('type_ar');
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $accident = new Accident();
        $accident->setType($iType);
        $em->persist($accident);
        $repository->translate($accident, 'type', 'ar', $iType_ar) ;
        $em->flush();
        return  new JsonResponse(array(
            "id" => $accident->getId(),
            "message" => "Type accident ajouté avec succès",
        ));
    }
    /**
     * @Route(path="/accident/edit/{id}", name="edit_accident", options={"expose"=true})
     *
     * @param Accident $accident
     * @param Request $request
     * @return JsonResponse
     */
    public function editAccident(Accident $accident, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $iType = $request->request->get('type');
        $iType_ar = $request->request->get('type_ar');
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $accident->setType($iType);
        $repository->translate($accident, 'type', 'ar', $iType_ar) ;
        $em->persist($accident);
        $em->flush();
        return  new JsonResponse(array(
            "id" => $accident->getId(),
            "message" => "type accident modifié avec succès",
        ));
    }
    /**
     * @Route(path="/accident/delete/{id}", name="delete_accident", options={"expose"=true})
     *
     * @param Accident $accident
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAccident(Accident $accident, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($accident);
        $em->flush();
        return  new JsonResponse(array(
            "message" => "type accident supprimé avec succès"
        ));
    }
}
