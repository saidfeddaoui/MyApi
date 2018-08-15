<?php

namespace App\Controller\BackOffice;

use App\Entity\CirconstanceSinistre;
use APY\BreadcrumbTrailBundle\Annotation\Breadcrumb;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route(path="/content_types", name="content_types_")
 * @Security("is_granted('ROLE_CONTENT_MANAGEMENT')")
 *
 * @Breadcrumb(title="Accueil")
 * @Breadcrumb(title="Gestion Contenu")
 */
class CirconstanceSinistreController extends Controller
{
    /**
     * @Route(path="/circonstance-sinistre", name="list_CirconstanceSinistre", options={"expose"=true})
     *
     * @Breadcrumb(title="Les circonstances Sinistre")
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $data = array();
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $circonstanceSinistres = $em->getRepository('App:CirconstanceSinistre')->findBy(['insuranceType'=> $session->get('insuranceType')]);
        foreach ($circonstanceSinistres as $key => $value){
            $translations =  $repository->findTranslations($value);
            $data[] = array(
                'id' => $value->getId(),
                'title' => $value->getTitle(),
                'title_ar' => $translations['ar']["title"] ?? '',
            );
        }
        return $this->render('circonstanceSinistre/index.html.twig', [
            'page_title' => 'Les circonstances sinistre',
            'page_subtitle' => '',
            'data'=>$data
        ]);
    }

}
