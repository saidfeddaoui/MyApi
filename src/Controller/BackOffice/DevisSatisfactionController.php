<?php

namespace App\Controller\BackOffice;


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
class DevisSatisfactionController extends Controller
{
    /**
     * @Route("/accepted", name="accepted",options={"expose"=true})
     *
     * @Breadcrumb(title="Acceptés")
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function accepted(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $devisAccepted = $em->getRepository('App:DevisSatisfaction')->findByStatut(true);
        return $this->render('devis/accepted.html.twig', [
            'page_title' => 'Liste des devis acceptés',
            'page_subtitle' => '',
            'data'=>$devisAccepted
        ]);

    }

    /**
     * @Route("/rejected", name="rejected",options={"expose"=true})
     *
     * @Breadcrumb(title="Rejetés")
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
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
}