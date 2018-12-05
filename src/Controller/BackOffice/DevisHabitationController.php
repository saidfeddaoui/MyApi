<?php

namespace App\Controller\BackOffice;


use App\Entity\DevisHabitation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;

/**
 * @Route(path="/devis", name="devis_")
 * @Security("is_granted('ROLE_DEVIS')")
 *
 * @Breadcrumb(title="Accueil")
 * @Breadcrumb(title="Devis")
 */
class DevisHabitationController extends Controller
{
    /**
     * @Route("/mrh", name="mrh",options={"expose"=true})
     *
     * @Breadcrumb(title="MRH")
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        //$insuranceType = $em->getRepository('App:insuranceType')->find($session->get('insuranceType'));
       // $societaire = $em->getRepository('App:Societaire')->findOneBy(["CodeInsurance" => $insuranceType->getName(), "type" => "MRH" ]);
        $devis_mrh = $em->getRepository('App:DevisHabitation')->findAll($societaire);
        return $this->render('mrh/index.html.twig', [
            'page_title' => 'Liste des devis Mrh',
            'page_subtitle' => '',
            'data'=>$devis_mrh
        ]);

    }


    /**
     * @Route("/habitation/details/{id}", name="habitation_details",options={"expose"=true})
     *
     * @Breadcrumb(title="Info Devis Mrh")
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function details(DevisHabitation $devisHabitation)
    {
        $em = $this->getDoctrine()->getManager();
        $garanties = $em->getRepository('App:DeviGaranties')->findBy([
            "devis"=> $devisHabitation->getId(),
            "type"=>"DH"
        ]);
        return $this->render('mrh/details.html.twig', [
            'page_title' => 'Détail devis Mrh',
            'page_subtitle' => '',
            'devisHabitation'=>$devisHabitation,
            'garanties'=>$garanties
        ]);
    }
}
