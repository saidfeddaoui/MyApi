<?php

namespace App\Controller\BackOffice;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;

/**
 * @Security("is_granted('ROLE_DEVIS')")
 *
 * @Breadcrumb(title="Accueil")
 * @Breadcrumb(title="Devis")
 */
class DevisController extends Controller
{
    /**
     * @Route("/devis/auto", name="auto",options={"expose"=true})
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
        $societaire = $em->getRepository('App:Societaire')->findByName($insuranceType->getName());
        $devis = $em->getRepository('App:DevisAuto')->findBySocietaire($societaire);

        return $this->render('devis/index.html.twig', [
            'page_title' => 'Liste des devis Auto',
            'page_subtitle' => '',
            'data'=>$devis
        ]);

    }
}
