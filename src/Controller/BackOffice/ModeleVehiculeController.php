<?php

namespace App\Controller\BackOffice;

use App\Entity\ModeleVehicule;
use App\Form\ModeleVehiculeType;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Services\FonctionDivers;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route(path="/content_types", name="content_types_")
 * @Security("is_granted('ROLE_CONTENT_MANAGEMENT')")
 *
 * @Breadcrumb(title="Accueil")
 * @Breadcrumb(title="Gestion Contenu")
 */
class ModeleVehiculeController extends Controller
{

    /**
     * @Route("/modele", name="modele_vehicule",options={"expose"=true})
     *
     * @Breadcrumb(title="Modeles véhicule")
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ModeleVehiculeType::class,new ModeleVehicule(),[
            'action' => $this->generateUrl('content_types_add_modele'),
        ]);
        $Modeles = $em->getRepository('App:ModeleVehicule')->getModelesByInsurance($session->get('insuranceType'));
        return $this->render('modele_vehicule/index.html.twig', [
            'page_title' => 'Modèle Véhicule',
            'page_subtitle' => '',
            'modeles'=>$Modeles,
            "form"=>$form->createView()
        ]);
    }
    /**
     * @Route(path="/modele/add", name="add_modele", options={"expose"=true})
     * @param SessionInterface $session
     * @param  Request $request
     * @return Response
     */
    public function addModele(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $modele = new ModeleVehicule();
        $form = $this->createForm(ModeleVehiculeType::class,$modele);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($modele);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Modèle véhicule ajouté avec succès ');
        }else{
            $aErrors = FonctionDivers::getErrorsAsArray($form->getErrors(true,false));
            foreach ($aErrors as $iKey => $sError)
            {
                $this->get('session')->getFlashBag()->add('error', $sError);
            }
        }
        return  $this->redirect($this->generateUrl('content_types_modele_vehicule'));
    }
    /**
     * @Route(path="/modele/edit/{id}", name="edit_modele", options={"expose"=true})
     *
     * @param ModeleVehicule $modele
     * @param Request $request
     * @return JsonResponse
     */
    public function editModele(ModeleVehicule $modele, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ModeleVehiculeType::class, $modele, [
            'action' => $this->generateUrl('content_types_edit_modele', ['id' => $modele->getId()])
        ]);
        $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
             $em->persist($modele);
             $em->flush();
             $this->get('session')->getFlashBag()->add('success', 'Modèle véhicule modifié avec succès ');
             return  $this->redirect($this->generateUrl('content_types_modele_vehicule'));
         } else {
             $aErrors = FonctionDivers::getErrorsAsArray($form->getErrors(true,false));
             foreach ($aErrors as $iKey => $sError) {
                 $this->get('session')->getFlashBag()->add('error', $sError);
             }
         }
        return  $this->render('modele_vehicule/form.html.twig', [
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route(path="/modele/delete/{id}", name="delete_modele", options={"expose"=true})
     *
     * @param ModeleVehicule $modele
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteModele(ModeleVehicule $modele, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($modele);
        $em->flush();
        return  new JsonResponse(['message' => 'Modèle véhicule supprimé avec succès']);
    }

}
