<?php

namespace App\Controller\BackOffice;


use App\Entity\DevisAuto;
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
class DevisController extends Controller
{
    /**
     * @Route("/auto", name="auto",options={"expose"=true})
     *
     * @Breadcrumb(title="Auto")
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $insuranceType = $em->getRepository('App:insuranceType')->find($session->get('insuranceType'));
        $societaire = $em->getRepository('App:Societaire')->findOneBy(["CodeInsurance" => $insuranceType->getName(), "type" => "AUTO" ]);
        $devis = $em->getRepository('App:DevisAuto')->findBySocietaire($societaire);

        return $this->render('devis/index.html.twig', [
            'page_title' => 'Liste des devis Auto',
            'page_subtitle' => '',
            'data'=>$devis
        ]);

    }


    /**
     * @Route("/auto/details/{id}", name="auto_details",options={"expose"=true})
     *
     * @Breadcrumb(title="Info véhicule")
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function details(DevisAuto $devisAuto)
    {
        $em = $this->getDoctrine()->getManager();
        $garanties = $em->getRepository('App:DeviGaranties')->findBy([
            "devis"=> $devisAuto->getId(),
            "type"=>"DA"
        ]);
        return $this->render('devis/details.html.twig', [
            'page_title' => 'Détail devis Auto',
            'page_subtitle' => '',
            'devisAuto'=>$devisAuto,
            'garanties'=>$garanties
        ]);
    }
}
