<?php

namespace App\Controller\BackOffice;

use App\Entity\Alert;
use App\Form\AlertType;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route(path="/alerts", name="alerts_")
 * @Security("is_granted('ROLE_ALERTS')")
 *
 * @Breadcrumb(title="Accueil")
 * @Breadcrumb(title="Alertes")
 */
class AlertController extends Controller
{

    /**
     * @Route(name="list")
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $form = $this->createForm(AlertType::class, new Alert(), [
            'action' => $this->generateUrl('alerts_add'),
        ]);
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $alerts = $em->getRepository('App:Alert')->findBy(['insuranceType'=> $session->get('insuranceType')]);
        $data = [];
        foreach ($alerts as $key => $value) {
            $translations =  $repository->findTranslations($value);
            $data[] = [
                'id' => $value->getId(),
                'title' => $value->getTitle(),
                'SubTitle' => $value->getSubTitle(),
                'description' => $value->getDescription(),
                'date_creation' => $value->getDateCreation(),
                'date_expiration' => $value->getDateExpiration(),
                'title_ar' => $translations['ar']['title'] ?? '',
                'description_ar' => $translations['ar']['description'] ?? '',
            ];
        }
        return $this->render('alertes/index.html.twig', [
            'page_title' => 'Produits',
            'page_subtitle' => '',
            'data' => $data,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route(path="/add", name="add", options={"expose"=true})
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function add(Request $request, SessionInterface $session)
    {
        $form = $this->createForm(AlertType::class);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $insuranceType = $em->getRepository('App:InsuranceType')->find($session->get('insuranceType')->getId());
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Alert $alert
             */
            $alert = $form->getData();
            $alert->setInsuranceType($insuranceType);
            $em->persist($alert);
            $repository->translate($alert, 'title', 'ar', $form->get('title_ar')->getData());
            $repository->translate($alert, 'subTitle', 'ar', $form->get('subTitle_ar')->getData());
            $repository->translate($alert, 'description', 'ar', $form->get('description_ar')->getData());
            $em->flush();
        }
        if ($errors = $form->getErrors()) {
            foreach ($errors as $error) {
                $this->addFlash('add.product.error', $error->getMessage());
                break;
            }
        }
        return  $this->redirect($this->generateUrl('alerts_list'));
    }
    /**
     * @Route(path="/edit/{id}", name="edit", options={"expose"=true})
     *
     * @param Alert $alert
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Alert $alert, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $form = $this->createForm(AlertType::class, $alert,[
            'action' => $this->generateUrl('alerts_edit', ['id' => $alert->getId()])
        ]);
        $translations =  $repository->findTranslations($alert);
        if ($translations) {
            $form->get('title_ar')->setData($translations['ar']['title']);
            $form->get('subTitle_ar')->setData($translations['ar']['subTitle']);
            $form->get('description_ar')->setData($translations['ar']['description']);
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository->translate($alert, 'title', 'ar', $form->get('title_ar')->getData()) ;
            $repository->translate($alert, 'subTitle', 'ar', $form->get('subTitle_ar')->getData()) ;
            $repository->translate($alert, 'description', 'ar', $form->get('description_ar')->getData());
            $em->persist($alert);
            $em->flush();
            return $this->redirect($this->generateUrl('alerts_list'));
        }
        return  $this->render('alertes/form.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route(path="/delete/{id}", name="delete", options={"expose"=true})
     *
     * @param Alert $alert
     * @return JsonResponse
     */
    public function delete(Alert $alert)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($alert);
        $em->flush();
        return  new JsonResponse(['message' => 'Alerte supprimée avec succès']);
    }

}
