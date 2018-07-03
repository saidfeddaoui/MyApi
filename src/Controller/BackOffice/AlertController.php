<?php

namespace App\Controller\BackOffice;

use App\Entity\Alert;
use App\Form\AlertType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Flex\Response;

class AlertController extends Controller
{
    /**
     * @Route("/alertes", name="alerts")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $form = $this->createForm(AlertType::class, new Alert(), [
            'action' => $this->generateUrl('add_alert'),
        ]);
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $data = array();
        $alerts = $em->getRepository('App:Alert')->findAll();

        foreach ($alerts as $key => $value){
            $translations =  $repository->findTranslations($value);
            $data[] = array(
                'id' => $value->getId(),
                'title' => $value->getTitle(),
                'description' => $value->getDescription(),
                'date_creation' => $value->getDateCreation(),
                'date_expiration' => $value->getDateExpiration(),
                'title_ar' => $translations['ar']["title"] ?? '',
                'description_ar' => $translations['ar']["description"] ?? '',
            );
        }
        return $this->render('alertes/index.html.twig', [
            'page_title' => 'Produits',
            'page_subtitle' => '',
            'data' => $data ? $data : [],
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route(path="/alertes/add", name="add_alert", options={"expose"=true})
     *
     * @param Request $request
     * @return Response
     */
    public function addAlert(Request $request)
    {
        $form = $this->createForm(AlertType::class);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Alert $alert
             */
            $alert = $form->getData();

            $iTitle_ar = $form->get('title_ar')->getData();
            $iDescription_ar = $form->get('description_ar')->getData();
            $em->persist($alert);
            $repository->translate($alert, 'title', 'ar', $iTitle_ar) ;
            $repository->translate($alert, 'description', 'ar', $iDescription_ar) ;
            $em->flush();

        }
        if ($errors = $form->getErrors()) {
            foreach ($errors as $error) {
                $this->addFlash('add.product.error', $error->getMessage());
                break;
            }
        }
        return  $this->redirect($this->generateUrl('alerts'));
    }

    /**
     * @Route(path="/alertes/edit/{id}", name="edit_alert", options={"expose"=true})
     *
     * @param Alert $alert
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAlert(Alert $alert, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $form = $this->createForm(AlertType::class, $alert,[
            'action' => $this->generateUrl('edit_alert',array('id' => $alert->getId()))]);
        $translations =  $repository->findTranslations($alert);
        if($translations){
            $form->get('title_ar')->setData($translations['ar']["title"]);
            $form->get('description_ar')->setData($translations['ar']["description"]);
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $iTitle_ar = $form->get('title_ar')->getData();
            $iDescription_ar = $form->get('description_ar')->getData();

            $repository->translate($alert, 'title', 'ar', $iTitle_ar) ;
            $repository->translate($alert, 'description', 'ar', $iDescription_ar) ;
            $em->persist($alert);
            $em->flush();
            return  $this->redirect($this->generateUrl('alerts'));
        }
        return  $this->render('alertes/form.html.twig',array(
            'form'=>$form->createView() ));

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
