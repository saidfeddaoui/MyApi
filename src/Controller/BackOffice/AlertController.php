<?php

namespace App\Controller\BackOffice;

use App\Entity\Alert;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AlertController extends Controller
{
    /**
     * @Route("/alertes", name="alerts")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = array();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $alerts = $em->getRepository('App:Alert')->findAll();
        foreach ($alerts as $key => $value){
            $translations =  $repository->findTranslations($value);
            $data[] = array(
                'id' => $value->getId(),
                'title' => $value->getTitle(),
                'title_ar' => $translations['ar']["title"] ?? '',
                'description' => $value->getDescription(),
                'description_ar' => $translations['ar']["description"] ?? '',
                'date_creation' => $value->getDateCreation(),
                'date_expiration' => $value->getDateExpiration()
            );
        }
        return $this->render('alertes/index.html.twig', [
            'page_title' => 'Alertes',
            'page_subtitle' => '',
            'data'=>$data
        ]);
    }

    /**
     * @Route(path="/alertes/add", name="add_alert", options={"expose"=true})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function addAlert(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $iTitle = $request->request->get('title');
        $iTitle_ar = $request->request->get('title_ar');
        $iDescription = $request->request->get('description');
        $iDescription_ar = $request->request->get('description_ar');
        $iDate_creation = $request->request->get('date_creation');
        $iDate_expiration = $request->request->get('date_expiration');
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $alert = new Alert();
        $alert->setTitle($iTitle);
        $alert->setDescription($iDescription);
        $alert->setDateCreation(  ($iDate_creation ? new \DateTime($iDate_creation) : NULL ) );
        $alert->setDateExpiration(   ($iDate_expiration ? new \DateTime($iDate_expiration) : NULL ) );
        $em->persist($alert);
        $repository->translate($alert, 'title', 'ar', $iTitle_ar) ;
        $repository->translate($alert, 'description', 'ar', $iDescription_ar) ;
        $em->flush();
        return  new JsonResponse(array(
            "id" => $alert->getId(),
            "message" => "Alerte ajoutée avec succès",
        ));
    }

    /**
     * @Route(path="/alertes/edit/{id}", name="edit_alert", options={"expose"=true})
     *
     * @param Alert $alert
     * @param Request $request
     * @return JsonResponse
     */
    public function editAlert(Alert $alert, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $iTitle = $request->request->get('title');
        $iTitle_ar = $request->request->get('title_ar');
        $iDescription = $request->request->get('description');
        $iDescription_ar = $request->request->get('description_ar');
        $iDate_creation = $request->request->get('date_creation');
        $iDate_expiration = $request->request->get('date_expiration');

        $alert->setTitle($iTitle);
        $alert->setDescription($iDescription);
        $alert->setDateCreation(new \DateTime($iDate_creation));
        $alert->setDateExpiration(new \DateTime($iDate_expiration));
        $em->persist($alert);
        $repository->translate($alert, 'title', 'ar', $iTitle_ar) ;
        $repository->translate($alert, 'description', 'ar', $iDescription_ar) ;
        $em->flush();
        return  new JsonResponse(array(
            "id" => $alert->getId(),
            "message" => "Alerte modifiée avec succès",
        ));

    }

    /**
     * @Route(path="/alerts/delete/{id}", name="delete_alert", options={"expose"=true})
     *
     * @param Alert $alert
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAlert(Alert $alert, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($alert);
        $em->flush();
        return  new JsonResponse(array(
            "message" => "Alerte supprimée avec succès"
        ));
    }
}
