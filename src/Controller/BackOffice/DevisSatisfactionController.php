<?php

namespace App\Controller\BackOffice;


use App\Entity\DevisSatisfaction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route(path="/devis", name="devis_")
 * @Security("is_granted('ROLE_DEVIS')")
 *
 * @Breadcrumb(title="Accueil")
 * @Breadcrumb(title="Devis")
 */
class DevisSatisfactionController extends Controller
{
    /**
     * @Route("/satisfaction", name="accepted",options={"expose"=true})
     *
     * @Breadcrumb(title="Devis")
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function satisfaction(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $devis = $em->getRepository("App:DevisSatisfaction")->findAllByOrder();

        return $this->render('devis/satisfaction.html.twig', [
            'page_title' => 'Liste des devis',
            'page_subtitle' => '',
            'data'=>$devis
        ]);

    }


    /**
     * @Route("/satisfaction/details/{id}", name="satisfaction_details",options={"expose"=true})
     *
     * @Breadcrumb(title="Detail devis satisfaction ")
     * @param DevisSatisfaction $devisSatisfaction
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function details(DevisSatisfaction $devisSatisfaction)
    {

        //dump($devisSatisfaction->getDevisHabitation());die;
        $em = $this->getDoctrine()->getManager();

        $garanties = $em->getRepository('App:DeviGaranties')->findBy([
            "devis"=> $devisSatisfaction->getDevisAuto(),
            "type"=>"DA"
        ]);
        return $this->render('devis/satisfaction_details.html.twig', [
            'page_title' => 'Détail devis',
            'page_subtitle' => '',
            'element'=>$devisSatisfaction,
            'garanties'=>$garanties
        ]);
    }

    /**
     * @Route("/satisfaction/update/action/{id}/{action}/{observation}", name="satisfaction_update_action",options={"expose"=true})
     *
     * @Breadcrumb(title="Update action devis satisfaction ")
     * @param DevisSatisfaction $devisSatisfaction
     * @param int $action
     * @param strig $observation
     *
     * @return JsonResponse
     */
    public function updateActionDevis(DevisSatisfaction $devisSatisfaction,$action,$observation){
        $em = $this->getDoctrine()->getManager();
        $devisSatisfaction->setAction($action);
        if(!empty($observation) and $observation!=''){
            $devisSatisfaction->setObservation($observation);
        }
        $em->flush();
        return  new JsonResponse(['message' => 'Action devis satisfaction modifié avec succés !!']);
    }






    /*
     *  il a ete chaque type dans une view accept and reject
     *
     * @Route("/rejected", name="rejected",options={"expose"=true})
     *
     * @Breadcrumb(title="Rejetés")
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response

    public function rejected(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $devisRejected = $em->getRepository('App:DevisSatisfaction')->findByStatut(false);
        return $this->render('devis/rejected.html.twig', [
            'page_title' => 'Liste des devis rejetés',
            'page_subtitle' => '',
            'data'=>$devisRejected
        ]);

    }

    */
}
